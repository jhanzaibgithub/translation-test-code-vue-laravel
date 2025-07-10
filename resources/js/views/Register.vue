<template>
  <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow p-4" style="width: 400px;">
      <h4 class="text-center mb-4">📝 Register</h4>
      <form @submit.prevent="register">
        <input v-model="name" type="text" class="form-control mb-3" placeholder="Full Name" required />
        <input v-model="email" type="email" class="form-control mb-3" placeholder="Email" required />
        <input v-model="password" type="password" class="form-control mb-3" placeholder="Password" required />
        <input v-model="password_confirmation" type="password" class="form-control mb-3" placeholder="Confirm Password" required />
        <button class="btn btn-success w-100 mb-2">Register</button>
        <button type="button" class="btn btn-outline-secondary w-100" @click="goToLogin">
          Already have an account? Login
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
    };
  },
  methods: {
    async register() {
      try {
        // Register the user
        await axios.post('/api/register', {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
        });

        // Automatically login after successful registration
        const res = await axios.post('/api/login', {
          email: this.email,
          password: this.password,
        });

        localStorage.setItem('token', res.data.token);
        this.$router.push('/');
      } catch (err) {
        alert('Registration failed');
        console.error(err);
      }
    },
    goToLogin() {
      this.$router.push('/login');
    },
  },
};
</script>
