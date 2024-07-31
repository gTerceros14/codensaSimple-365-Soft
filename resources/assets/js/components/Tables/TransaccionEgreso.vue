<template>
    <div class="table-responsive">
      <DataTable 
        :value="items" 
        :paginator="true" 
        :rows="perPage" 
        :totalRecords="rows" 
        @page="changePaginationCurrent"
        currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} registros"
      >
        <Column 
          v-for="field in fields" 
          :key="field.key" 
          :field="field.key" 
          :header="field.label" 
        />
      </DataTable>
    </div>
  </template>
  
  <script>
  import DataTable from 'primevue/datatable';
  import Column from 'primevue/column';
  import axios from 'axios';
  
  export default {
    components: {
      DataTable,
      Column
    },
    props: {
      data: {
        type: Object,
        required: true
      }
    },
    data() {
      return {
        perPage: 10,
        currentPage: 1,
        fields: [
          { key: 'id', label: 'N' },
          { key: 'fecha_hora', label: 'Fecha' },
          { key: 'nombre', label: 'Proveedor' },
          { key: 'tipo_comprobante', label: 'Tipo Comprobante' },
          { key: 'num_comprobante', label: 'Número Comprobante' },
          { key: 'total', label: 'Importe' },
          { key: 'usuario', label: 'Usuario' }
        ],
        items: [],
        totalItems: 0,
        path: ''
      };
    },
    created() {
      this.items = this.data.data;
      this.perPage = this.data.per_page;
      this.totalItems = this.data.total;
      this.path = this.data.path;
    },
    methods: {
      changePaginationCurrent(event) {
        const pageNum = event.page + 1; // La paginación en PrimeVue empieza en 0
        this.getDatawithPage(pageNum);
      },
      getDatawithPage(pageNumber) {
        const url = new URL(this.path, window.location.origin);
        axios.get(`${url.pathname}?page=${pageNumber}`)
          .then((response) => {
            this.items = response.data.data;
            this.perPage = response.data.per_page;
            this.totalItems = response.data.total;
          })
          .catch((error) => {
            console.error(error);
          });
      }
    }
  };
  </script>
  
  <style scoped>
  .table-responsive {
    overflow-x: auto;
  }
  </style>
  