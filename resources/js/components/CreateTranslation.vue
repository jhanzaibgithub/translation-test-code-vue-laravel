<template>
  <div class="container mt-4">
    <h2>Create New Translation</h2>
    <form @submit.prevent="submitTranslation">
      <div class="mb-3">
        <label class="form-label">Key</label>
        <input v-model="form.key" type="text" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Locale</label>
        <input v-model="form.locale" type="text" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Value</label>
        <textarea v-model="form.value" class="form-control" required></textarea>
      </div>

      <button type="submit" class="btn btn-primary">Create</button>
    </form>
  </div>
</template>

<script>
export default {
  name: "CreateTranslation",
  data() {
    return {
      form: {
        key: '',
        locale: '',
        value: ''
      }
    };
  },
  methods: {
    async submitTranslation() {
      try {
        await axios.post('/api/translations', this.form);
        alert('Translation created successfully!');
        this.form.key = '';
        this.form.locale = '';
        this.form.value = '';
      } catch (error) {
        alert('Error creating translation.');
        console.error(error);
      }
    }
  }
};
</script>
