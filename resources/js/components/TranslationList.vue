<template>
  <div class="container mt-5">
    <h2>Translations</h2>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Key</th>
          <th>Value</th>
          <th>Locale</th>
          <th>Tag</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in translations" :key="item.id">
          <td>{{ item.key }}</td>
          <td>{{ item.value }}</td>
          <td>{{ item.locale }}</td>
          <td>{{ item.tag }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return { translations: [] };
  },
  mounted() {
    axios.get('/api/translations').then(res => {
      this.translations = res.data.data;
    });
  }
}
</script>
