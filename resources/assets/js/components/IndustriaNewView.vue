<template>
    <main class="main">
        <Panel>
            <template #header>
                <div class="panel-header">
                    <i class="pi pi-align-justify"></i>
                    <h4 class="panel-title">Industrias</h4>
                </div>
            </template>
      
      <div class="toolbar-container">
            <div class="searchbar">
                <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText type="text" v-model="searchTerm" placeholder="Buscar por descripcion" class="p-inputtext-sm custom-input" />
                </span>
            </div>
            <div class="toolbar">
                <Button  @click="abrirModal('medida', 'registrar')" class=" p-button-sm p-button-secondary">
                <i class="pi pi-plus"></i> Nuevo
                </Button>
                <Button @click="uploadFile" class="p-button-sm p-button-secondary">
                Subir Archivo
                </Button>
            </div>
        </div>
      <DataTable :value="industries" class="p-datatable-gridlines">
        <Column field="opciones" header="Opciones"></Column>
        <Column field="nombre" header="Nombre"></Column>
        <Column header="Estado"></Column>
      </DataTable>

      <Dialog :visible.sync="modal" modal :header="tituloModal" :closable="true">
          <template #footer>
            <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="cerrarModal()" />
            <Button v-if="tipoAccion === 1" label="Guardar" icon="pi pi-check" class="p-button-text" @click="registrarMedida()" />
            <Button v-if="tipoAccion === 2" label="Actualizar" icon="pi pi-check" class="p-button-text" @click="actualizarMedida()" />
          </template>
          <div class="p-fluid">
            <div class="p-field">
              <label for="name">Nombe Industria</label>
              <InputText id="name" v-model="nombre" required autofocus />
            </div>
          </div>
        </Dialog>
    </Panel>
    </main>
  </template>
  
  <script>
  import Dialog from 'primevue/dialog';
  import Panel from 'primevue/panel';
  import DataTable from 'primevue/datatable';
  import Column from 'primevue/column';
  import Button from 'primevue/button';
  import FileUpload from 'primevue/fileupload';
  import InputText from 'primevue/inputtext';
  
  export default {
    components: {
      DataTable,
      Column,
      Button,
      FileUpload,
      InputText,
      Panel,
      Dialog
    },
    data() {
      return {
        selectedFile: null,
        selectedFileName: 'Ningún archivo seleccionado',
        searchText: '',
        industries: [
          { name: 'SIN INDUSTRIA', status: 'Activo' }
        ],
        modal:true,
        tituloModal:'Registrar industria',
        tipoAccion: 1,
        arrayIndustria: [],
        criterio: 'nombre',
        buscar: '',
        industria_id: 0,
        nombre: '',
        archivo: null,
      };
    },
    methods: {
      onFileSelected(event) {
        this.selectedFile = event.target.files[0];
        this.selectedFileName = this.selectedFile ? this.selectedFile.name : 'Ningún archivo seleccionado';
      },
      uploadFile() {
        if (this.selectedFile) {
          // Handle file upload
          console.log('File to upload:', this.selectedFile);
        } else {
          console.error('No file selected');
        }
      },
      abrirModal(modelo, accion, data = []) {
        switch (modelo) {
          case 'medida': {
            switch (accion) {
              case 'registrar': {
                this.modal = true;
                this.tituloModal = 'Registrar Medida';
                this.descripcionMedida = '';
                this.codigoClasificador = '';
                this.tipoAccion = 1;
                break;
              }
              case 'actualizar': {
                this.modal = true;
                this.tituloModal = 'Actualizar medida';
                this.tipoAccion = 2;
                this.medida_id = data['id'];
                this.descripcionMedida = data['descripcion_medida'];
                this.codigoClasificador = data['codigoClasificador'];
                break;
              }
            }
          }
        }
      },
      cerrarModal() {
        this.modal = false;
        this.tituloModal = '';
        this.descripcionMedida = '';
        this.codigoClasificador = '';
      },
    }
  };
  </script>
  
  <style scoped>
  >>> .p-panel .p-panel-header {
  padding-top: 10px;
  padding-bottom: 10px;
}
  .panel-header {
  display: flex;
  align-items: center;
}
.panel-title {
  margin: 0;
  padding-left: 5px; 
}
.toolbar-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
  }
  .toolbar {
    display: flex;
    align-items: center;
    justify-content: flex-end   ;
    gap: 10px;
  }
  .searchbar {
    display: flex;
    width: 50%;
    align-items: center;    
    justify-content: flex-start;
    display: inline-block; /* Asegura que el contenedor se ajuste al contenido */
  }
  
  .p-datatable-gridlines .p-datatable-tbody > tr > td {
    border-right: 1px solid var(--surface-d);
  }
  
  .p-datatable-gridlines .p-datatable-tbody > tr > td:last-child {
    border-right: 0;
  }
  
  .p-tag {
    display: inline-block;
    padding: .25em .4em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: .25rem;
  }
  
  .p-tag-success {
    background-color: #28a745;
  }
  </style>
  