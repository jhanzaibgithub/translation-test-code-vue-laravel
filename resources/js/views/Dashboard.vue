<template>
  <MainLayout>
    <template #default>
      <h3>🌐 All Translations</h3>
      <div class="row mb-3">
        <div class="col-md-3" v-for="(placeholder, key) in { key: 'Search Key', value: 'Search Value' }" :key="key">
          <input v-model="filters[key]" :placeholder="placeholder" class="form-control" @input="debouncedFetchTranslations" />
        </div>
        <div class="col-md-2">
          <select v-model="filters.tag" class="form-select" @change="fetchTranslations(1)">
            <option value="">All Tags</option>
            <option v-for="tag in availableTags" :key="tag" :value="tag">{{ tag }}</option>
          </select>
        </div>
        <div class="col-md-2">
          <select v-model="perPage" class="form-select" @change="fetchTranslations(1)">
            <option value="10">10 / page</option>
            <option value="25">25 / page</option>
            <option value="50">50 / page</option>
            <option value="100">100 / page</option>
            <option value="all">Show All</option>
          </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
          <button class="btn btn-secondary w-50" @click="resetFilters">Reset</button>
          <button class="btn btn-success w-50" @click="exportTranslations">Export</button>
        </div>
      </div>

      <div class="d-flex justify-content-end mb-2">
        <button class="btn btn-primary" @click="showCreateModal = true">➕ Add</button>
      </div>

      <div v-if="loading" class="text-center my-5">Loading...</div>

      <div v-else>
        <table class="table table-bordered table-hover">
          <thead class="table-dark">
            <tr>
              <th>ID</th><th>Key</th><th>Value</th><th>Locale</th><th>Tag</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="t in translations.data" :key="t.id">
              <td>{{ t.id }}</td>
              <td>{{ t.key }}</td>
              <td>{{ t.value }}</td>
              <td>{{ t.locale }}</td>
              <td>{{ t.tag || '-' }}</td>
              <td>
                <button class="btn btn-sm btn-primary me-2" @click="editTranslation(t)">Edit</button>
                <button class="btn btn-sm btn-danger" @click="deleteTranslation(t.id)">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>

        <nav v-if="!showAll && translations.last_page > 1">
          <ul class="pagination">
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
              <a class="page-link" href="#" @click.prevent="fetchTranslations(currentPage - 1)">Previous</a>
            </li>
            <li v-for="page in visiblePages" :key="page" :class="['page-item', { active: page === currentPage, disabled: page === '...' }]">
              <span v-if="page === '...'" class="page-link">…</span>
              <a v-else class="page-link" href="#" @click.prevent="fetchTranslations(page)">{{ page }}</a>
            </li>
            <li class="page-item" :class="{ disabled: currentPage === translations.last_page }">
              <a class="page-link" href="#" @click.prevent="fetchTranslations(currentPage + 1)">Next</a>
            </li>
          </ul>
        </nav>
      </div>

      <!-- Edit Modal -->
      <div v-if="editing" class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header"><h5>Edit</h5><button class="btn-close" @click="editing = false"></button></div>
            <div class="modal-body">
              <div v-for="f in ['key', 'value', 'locale', 'tag']" :key="f" class="mb-2">
                <input v-model="editForm[f]" class="form-control" :placeholder="f" />
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" @click="editing = false">Cancel</button>
              <button class="btn btn-success" @click="updateTranslation">Save</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Create Modal -->
      <div v-if="showCreateModal" class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header"><h5>Add</h5><button class="btn-close" @click="showCreateModal = false"></button></div>
            <div class="modal-body">
              <div v-for="f in ['key', 'value', 'locale', 'tag']" :key="f" class="mb-2">
                <input v-model="createForm[f]" class="form-control" :placeholder="f" />
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" @click="showCreateModal = false">Cancel</button>
              <button class="btn btn-success" @click="createTranslation">Create</button>
            </div>
          </div>
        </div>
      </div>
    </template>
  </MainLayout>
</template>

<script>
import axios from 'axios';
import MainLayout from '../components/layouts/MainLayout.vue';
import debounce from 'lodash/debounce';
import Swal from 'sweetalert2';

export default {
  components: { MainLayout },
  data: () => ({
    translations: { data: [], current_page: 1, last_page: 1 },
    loading: true,
    editing: false,
    showCreateModal: false,
    showAll: false,
    perPage: '10',
    createForm: { key: '', value: '', locale: '', tag: '' },
    editForm: { id: '', key: '', value: '', locale: '', tag: '' },
    filters: { key: '', value: '', tag: '' },
    availableTags: []
  }),
  computed: {
    currentPage() {
      return this.translations.current_page;
    },
    visiblePages() {
      const total = this.translations.last_page;
      const current = this.currentPage;
      const delta = 2, pages = [];
      for (let i = Math.max(1, current - delta); i <= Math.min(total, current + delta); i++) pages.push(i);
      if (pages[0] > 1) pages.unshift(pages[0] > 2 ? '...' : 1);
      if (pages[pages.length - 1] < total) pages.push(pages[pages.length - 1] < total - 1 ? '...' : total);
      return pages;
    }
  },
  created() {
    this.debouncedFetchTranslations = debounce(() => this.fetchTranslations(1), 300);
  },
  async mounted() {
    await Promise.all([this.fetchTranslations(), this.fetchAvailableTags()]);
  },
  methods: {
    async fetchTranslations(page = 1) {
      this.loading = true;
      axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
      try {
        if (this.perPage === 'all') {
          const { data } = await axios.get('/api/translations-show');
          let allData = data.data;

          // Apply filters manually
          if (this.filters.key) {
            allData = allData.filter(t => t.key.toLowerCase().includes(this.filters.key.toLowerCase()));
          }
          if (this.filters.value) {
            allData = allData.filter(t => t.value.toLowerCase().includes(this.filters.value.toLowerCase()));
          }
          if (this.filters.tag) {
            allData = allData.filter(t => t.tag === this.filters.tag);
          }

          this.translations = {
            data: allData,
            current_page: 1,
            last_page: 1
          };
          this.showAll = true;
        } else {
          const { data } = await axios.get('/api/translations', {
            params: { page, per_page: this.perPage, ...this.filters }
          });
          this.translations = data.data;
          this.showAll = false;
        }
      } finally {
        this.loading = false;
      }
    },
    async fetchAvailableTags() {
      const { data } = await axios.get('/api/tags');
      this.availableTags = data.tags || [];
    },
    resetFilters() {
      this.filters = { key: '', value: '', tag: '' };
      this.fetchTranslations(1);
    },
    editTranslation(t) {
      this.editing = true;
      this.editForm = { ...t };
    },
    async updateTranslation() {
      await axios.post(`/api/translations/update/${this.editForm.id}`, this.editForm);
      this.editing = false;
      this.fetchTranslations(this.currentPage);
    },
    async createTranslation() {
      await axios.post('/api/translations/create', this.createForm);
      this.showCreateModal = false;
      this.fetchTranslations(this.currentPage);
      this.createForm = { key: '', value: '', locale: '', tag: '' };
    },
    async deleteTranslation(id) {
      const confirm = await Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this translation!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      });

      if (confirm.isConfirmed) {
        await axios.post(`/api/translations/delete/${id}`);
        await this.fetchTranslations(this.currentPage);
        Swal.fire('Deleted!', 'The translation has been deleted.', 'success');
      }
    },
    async exportTranslations() {
      const blob = new Blob([JSON.stringify(this.translations.data, null, 2)], { type: 'application/json' });
      const url = URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href = url;
      link.download = `translations_${this.showAll ? 'all' : 'page_' + this.currentPage}.json`;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      URL.revokeObjectURL(url);
    }
  }
};
</script>
