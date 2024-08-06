<template>
    <div class="table-responsive">
      <TableBase 
        :items="items" 
        :rows="rows" 
        :per-page="perPage" 
        :fields="fields"
        @change-pagination="changePaginationCurrent" 
      />
    </div>
  </template>
  
  <script>
  import TableBase from './TableBase.vue';
  import axios from 'axios';
  
  export default {
    components: {
      TableBase
    },
    props: {
      data: {
        type: Object,
        required: true
      }
    },
    data() {
      return {
        perPage: 0,
        currentPage: 1,
        fields: [
          { key: 'id', label: 'N' },
          { key: 'fecha_hora', label: 'Fecha' },
          { key: 'nombre', label: 'Cliente' },
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
    computed: {
      rows() {
        return this.totalItems;
      }
    },
    methods: {
      changePaginationCurrent(event) {
        const pageNum = event.page + 1; // Ajuste de la paginación, PrimeVue usa 0-based index
        this.getDatawithPage(pageNum);
      },
      getDatawithPage(pageNumber) {
        const url = new URL(this.path, window.location.origin); // Asegúrate de usar el origen del sitio
        console.log(`${url.pathname}?page=${pageNumber}`);
        axios.get(`${url.pathname}?page=${pageNumber}`)
          .then((response) => {
            const respuesta = response.data;
            this.items = respuesta.data; // Ajusta según la estructura de tu respuesta
            this.perPage = respuesta.per_page;
            this.totalItems = respuesta.total; // Actualiza el totalItems para la paginación
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
  