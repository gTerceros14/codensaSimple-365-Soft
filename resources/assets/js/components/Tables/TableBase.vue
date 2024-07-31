<template>
    <div class="table-responsive">
      <DataTable 
        :value="items"
        :paginator="true"
        :rows="perPage"
        :totalRecords="rows"
        :currentPage="currentPage"
        @page="changePagination"
        dataKey="id"
      >
        <Column
          v-for="field in fields"
          :key="field.key"
          :field="field.key"
          :header="field.label"
        />
        <Column
          v-if="items.length <= 0"
          :header="'No data'"
          body="No data available"
        />
      </DataTable>
    </div>
  </template>
  
  <script>
  import DataTable from 'primevue/datatable';
  import Column from 'primevue/column';
  import Paginator from 'primevue/paginator';
  
  export default {
    components: {
      DataTable,
      Column,
      Paginator
    },
    props: {
      items: {
        type: Array,
        required: true
      },
      perPage: {
        type: Number,
        required: true
      },
      rows: {
        type: Number,
        required: true
      },
      fields: {
        type: Array,
        required: true
      }
    },
    data() {
      return {
        currentPage: 1
      };
    },
    methods: {
      changePagination(event) {
        this.currentPage = event.page + 1; // PrimeVue uses zero-based index
        this.$emit('change-pagination', this.currentPage);
      }
    }
  };
  </script>
  
  <style scoped>
  .table-responsive {
    overflow-x: auto;
  }
  </style>
  