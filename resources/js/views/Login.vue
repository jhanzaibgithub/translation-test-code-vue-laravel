<template>
  <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow p-4" style="width: 400px;">
      <h4 class="text-center mb-4">🔐 Login</h4>
      <form @submit.prevent="login">
        <input v-model="email" type="email" class="form-control mb-3" placeholder="Email" required />
        <input v-model="password" type="password" class="form-control mb-3" placeholder="Password" required />
        <button class="btn btn-primary w-100 mb-2">Login</button>
        <button type="button" class="btn btn-outline-secondary w-100" @click="goToRegister">
          Don't have an account? Register
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
      email: '',
      password: '',
    };
  },
  methods: {
    async login() {
      try {
        const res = await axios.post('/api/login', {
          email: this.email,
          password: this.password,
        });
        localStorage.setItem('token', res.data.token);
        this.$router.push('/');
      } catch {
        alert('Login failed');
      }
    },
    goToRegister() {
      this.$router.push('/register');
    },
  },
};
</script>
