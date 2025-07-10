<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="#">🌐 Translations</a>

    <ul class="navbar-nav ms-auto">
      <li class="nav-item dropdown">
        <a
          class="nav-link dropdown-toggle text-white"
          href="#"
          role="button"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          👤 {{ user.name || 'User' }}
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="#" @click.prevent="logout">Logout</a></li>
        </ul>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  data() {
    return {
      user: {},
    };
  },
  mounted() {
    this.getUser();
  },
  methods: {
    async getUser() {
      const token = localStorage.getItem('token');
      if (token) {
        try {
          const res = await fetch('/api/user', {
            headers: {
              Authorization: `Bearer ${token}`,
              Accept: 'application/json',
            },
          });
          const json = await res.json();
          this.user = json;
        } catch {
          this.user = { name: 'Guest' };
        }
      }
    },
    logout() {
      localStorage.removeItem('token');
      this.$router.push('/login');
    },
  },
};
</script>
