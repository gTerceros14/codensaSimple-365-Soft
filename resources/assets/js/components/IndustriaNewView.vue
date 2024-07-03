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
            <div class="toolbar">
                  <Button @click="abrirModal('industria', 'registrar')" class=" p-button-sm p-button-success">
                      <i class="pi pi-plus"></i> Nuevo
                  </Button>
                  <Button @click="showUploadDialog" class="p-button-sm p-button-help">
                    <i class="pi pi-file-excel"></i> Subir Archivo
                  </Button>
              </div>
              <div class="searchbar">
                  <span class="p-input-icon-left">
                      <i class="pi pi-search" />
                      <InputText type="text" v-model="buscar" placeholder="Buscar por descripción" class="p-inputtext-sm custom-input" />
                  </span>
              </div>
              
          </div>

          <DataTable :value="arrayIndustria" class="p-datatable-gridlines" paginator :rows="9">
              <Column header="Opciones">
                  <template #body="slotProps">
                      <Button icon="pi pi-pencil" class="p-button-sm p-button-warning custom-icon-size" @click="abrirModal('industria', 'actualizar', slotProps.data)" />
                      <Button v-if="slotProps.data.estado" icon="pi pi-ban" class="p-button-sm p-button-danger custom-icon-size" @click="desactivarIndustria(slotProps.data.id)" />
                      <Button v-else icon="pi pi-check-circle" class="p-button-sm p-button-success custom-icon-size" @click="activarIndustria(slotProps.data.id)" />
                  </template>
              </Column>
              <Column field="nombre" header="Nombre"></Column>
              <Column field="estado" header="Estado">
                  <template #body="slotProps">
                      <span :class="['status-badge', slotProps.data.estado === 1 ? 'active' : 'inactive']">
                          {{ slotProps.data.estado === 1 ? 'Activo' : 'Inactivo' }}
                      </span>
                  </template>
              </Column>
          </DataTable>

          <Dialog :visible.sync="modal" modal :header="tituloModal" :closable="true">
              <template #footer>
                  <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="cerrarModal()" />
                  <Button v-if="tipoAccion === 1" label="Guardar" icon="pi pi-check" class="p-button-text" @click="registrarIndustria()" />
                  <Button v-if="tipoAccion === 2" label="Actualizar" icon="pi pi-check" class="p-button-text" @click="actualizarIndustria()" />
              </template>
              <div class="p-fluid">
                  <div class="p-field">
                      <label for="name">Nombre Industria</label>
                      <InputText id="name" v-model="nombre" required autofocus />
                  </div>
              </div>
          </Dialog>

          <Dialog :visible.sync="importar" modal :header="'Importar Industrias'" :closable="true">
              <FileUpload  @select="onFileSelect" :showUploadButton="false"  chooseLabel="Seleccionar" cancelLabel="Cancelar" :customUpload="true" :multiple="false" accept=".xls,.xlsx,.csv" :maxFileSize="1000000">
                  <template #empty>
                      <p>Arrastra y suelta archivos aquí o haz clic para seleccionar.</p>
                  </template>
              </FileUpload>
              <template #footer>
                <Button label="Subir archivo" icon="pi pi-upload" class="p-button-help" @click="uploadFile" style="margin-top: 1.5rem;"/>
              </template>
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
import axios from 'axios';

export default {
  components: {
      DataTable,
      Column,
      Button,
      FileUpload,
      InputText,
      Panel,
      Dialog,
  },
  data() {
      return {
          modal: false,
          tituloModal: 'Registrar industria',
          tipoAccion: 1,
          arrayIndustria: [],
          criterio: 'nombre',
          buscar: '',
          nombre: '',
          archivo: null,
          importar: false,
      };
  },
  methods: {
      onFileSelect(event) {
          this.archivo = event.files[0];
      },
      uploadFile() {
          if (!this.archivo) {
              console.error('No file selected');
              return;
          }

          const formData = new FormData();
          formData.append('archivo', this.archivo);

          axios.post('/industrias/importar', formData)
              .then(response => {
                  console.log(response.data.message);
                  this.listarIndustria(1, '', 'nombre');
                  this.importar = false; // Cerrar diálogo de importación después de subir
              })
              .catch(error => {
                  console.error(error);
              });
      },
      listarIndustria(page, buscar, criterio) {
          let url = '/industrianewview?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
          axios.get(url)
              .then(response => {
                  this.arrayIndustria = response.data.industrias;
              })
              .catch(error => {
                  console.error(error);
              });
      },
      desactivarIndustria(id) {
            swal({
                title: 'Esta seguro de desactivar esta categoría?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/industria/desactivar', {
                        'id': id
                    }).then(function (response) {
                        me.listarIndustria(1, '', 'nombre');
                        swal(
                            'Desactivado!',
                            'El registro ha sido desactivado con éxito.',
                            'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });


                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
            })
        },
        activarIndustria(id) {
            swal({
                title: 'Esta seguro de activar esta categoría?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/industria/activar', {
                        'id': id
                    }).then(function (response) {
                        me.listarIndustria(1, '', 'nombre');
                        swal(
                            'Activado!',
                            'El registro ha sido activado con éxito.',
                            'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });


                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
            })
        },
      abrirModal(modelo, accion, data = []) {
          switch (modelo) {
              case "industria":
                  switch (accion) {
                      case 'registrar':
                          this.modal = true;
                          this.tituloModal = 'Registrar Industria';
                          this.tipoAccion = 1;
                          break;
                      case 'actualizar':
                          this.modal = true;
                          this.tituloModal = 'Actualizar Industria';
                          this.tipoAccion = 2;
                          this.nombre = data['nombre'];
                          break;
                  }
                  break;
          }
      },
      cerrarModal() {
          this.modal = false;
          this.tituloModal = '';
          this.nombre = '';
      },
      showUploadDialog() {
          this.importar = true;
      }
  },
  mounted() {
      this.listarIndustria(1, '', this.criterio);
  }
};
</script>

<style scoped>
.p-panel .p-panel-header {
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
  justify-content: flex-end;
  gap: 10px;
}
.searchbar {
  display: flex;
  width: 50%;
  align-items: center;    
  justify-content: flex-start;
  display: inline-block;
}
.status-badge {
  padding: 0.25em 0.5em;
  border-radius: 4px;
  color: white;
}
.status-badge.active {
  background-color: rgb(0, 225, 0);
}
.status-badge.inactive {
  background-color: red;
}
>>> .p-fileupload .p-button.p-fileupload-choose {
    background-color: #22c55e !important;
    border-color: #22c55e !important;
    color: #ffffff !important;
    transition: all 0.2s ease-in-out !important;
}

/* Efecto hover */
>>> .p-fileupload .p-button.p-fileupload-choose:enabled:hover {
    background-color: #16a34a !important;
    border-color: #16a34a !important;
}

/* Efecto focus */
>>> .p-fileupload .p-button.p-fileupload-choose:focus {
    box-shadow: 0 0 0 0.2rem rgba(34, 197, 94, 0.5) !important;
}

/* Efecto active (cuando se hace clic) */
>>> .p-fileupload .p-button.p-fileupload-choose:enabled:active {
    background-color: #15803d !important;
    border-color: #15803d !important;
}

/* Estilo cuando está deshabilitado */
>>> .p-fileupload .p-button.p-fileupload-choose:disabled {
    background-color: #22c55e !important;
    border-color: #22c55e !important;
    opacity: 0.6;
}
>>> .p-fileupload .p-fileupload-buttonbar .p-button.p-component:not(.p-fileupload-choose) {
    background: #ef4444 !important;
    border-color: #ef4444 !important;
    color: #ffffff !important;
    transition: all 0.2s ease-in-out !important;
}

/* Efecto hover */
>>> .p-fileupload .p-fileupload-buttonbar .p-button.p-component:not(.p-fileupload-choose):enabled:hover {
    background: #dc2626 !important;
    border-color: #dc2626 !important;
}

/* Efecto focus */
>>> .p-fileupload .p-fileupload-buttonbar .p-button.p-component:not(.p-fileupload-choose):focus {
    box-shadow: 0 0 0 0.2rem rgba(239, 68, 68, 0.5) !important;
}

/* Efecto active (cuando se hace clic) */
>>> .p-fileupload .p-fileupload-buttonbar .p-button.p-component:not(.p-fileupload-choose):enabled:active {
    background: #b91c1c !important;
    border-color: #b91c1c !important;
}

/* Estilo cuando está deshabilitado */
>>> .p-fileupload .p-fileupload-buttonbar .p-button.p-component:not(.p-fileupload-choose):disabled {
    background: #ef4444 !important;
    border-color: #ef4444 !important;
    opacity: 0.6;
}
>>> .p-fileupload .p-fileupload-files .p-button {
    background: #ef4444 !important;
    border-color: #ef4444 !important;
    color: #ffffff !important;
    transition: all 0.2s ease-in-out !important;
}

/* Efecto hover */
>>> .p-fileupload .p-fileupload-files .p-button:enabled:hover {
    background: #dc2626 !important;
    border-color: #dc2626 !important;
}

/* Efecto focus */
>>> .p-fileupload .p-fileupload-files .p-button:focus {
    box-shadow: 0 0 0 0.2rem rgba(239, 68, 68, 0.5) !important;
}

/* Efecto active (cuando se hace clic) */
>>> .p-fileupload .p-fileupload-files .p-button:enabled:active {
    background: #b91c1c !important;
    border-color: #b91c1c !important;
}

/* Estilo cuando está deshabilitado */
>>> .p-fileupload .p-fileupload-files .p-button:disabled {
    background: #ef4444 !important;
    border-color: #ef4444 !important;
    opacity: 0.6;
}

/* Asegurar que el icono dentro del botón también sea blanco */
>>> .p-fileupload .p-fileupload-files .p-button .p-button-icon {
    color: #ffffff !important;
} 
>>> .p-fileupload-row > div:first-child {
    display: none !important;
}
>>> .p-dialog .p-dialog-content{
  padding: 0 1.5rem 1.5rem 1.5rem;
}
</style>
