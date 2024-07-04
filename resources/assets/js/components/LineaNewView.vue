<template>
    <main class="main">
      <Panel>
        <template #header>
          <div class="panel-header">
            <i class="pi pi-align-justify"></i>
            <h4 class="panel-title">Lineas</h4>
          </div>
        </template>
        <div class="toolbar">
          <Button label="Nuevo" icon="pi pi-plus" @click="abrirModal('categoria', 'registrar')" class="p-button-secondary p-button-sm" />
          <Button label="Exportar" icon="pi pi-cloud-download" @click="cargarExcel" class="p-button-success p-button-sm" />
          <Button label="Importar" icon="pi pi-cloud-upload" @click="showUploadDialog" class="p-button-help p-button-sm" />
        </div>
        <div class="search-bar">
          <input type="text" placeholder="Nombre" v-model="nombre" class="p-inputtext" />
          <input type="text" placeholder="Texto a buscar" v-model="buscarTexto" class="p-inputtext" />
          <Button class="p-button p-component p-button-primary" @click="buscar">
            Buscar
          </Button>
        </div>
        <DataTable :value="arrayCategoria" class="p-datatable-sm p-datatable-gridlines"  responsiveLayout="scroll" paginator :rows="9">
          <Column header="Opciones">
            <template #body="slotProps">
                <Button icon="pi pi-pencil" class="p-button-sm p-button-warning custom-icon-size" @click="abrirModal('categoria', 'actualizar', slotProps.data)" />
                <Button v-if="slotProps.data.condicion" icon="pi pi-ban" class="p-button-sm p-button-danger custom-icon-size" @click="desactivarCategoria(slotProps.data.id)" />
                <Button v-else icon="pi pi-check-circle" class="p-button-sm p-button-success custom-icon-size" @click="activarCategoria(slotProps.data.id)" />
            </template>
          </Column>
          <Column field="nombre" header="Nombre"></Column>
          <Column field="codigoProductoSin" header="Código"></Column>
          <Column field="descripcion" header="Descripción"></Column>
          <Column field="estado" header="Estado">
            <template #body="slotProps">
                <span :class="['status-badge', slotProps.data.estado === 1 ? 'active' : 'inactive']">
                    {{ slotProps.data.condicion === 1 ? 'Activo' : 'Inactivo' }}
                </span>
            </template>
              </Column>
        </DataTable>
        <Dialog :visible.sync="modal" modal :header="tituloModal" :closable="true" @hide="cerrarModal">
          <template #footer>
            <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="cerrarModal()" />
            <Button v-if="tipoAccion === 1" label="Guardar" icon="pi pi-check" class="p-button-text" @click="registrarCategoria()" />
            <Button v-if="tipoAccion === 2" label="Actualizar" icon="pi pi-check" class="p-button-text" @click="actualizarCategoria()" />
          </template>
          <div class="p-fluid ">
            <div class="p-field input-container">
              <label for="nombre">Nombre Línea</label>
              <InputText id="nombre" v-model="nombre" required autofocus :class="{'p-invalid': nombreError}" @input="validarNombreEnTiempoReal" />
              <small class="p-error error-message" v-if="nombreError">{{ nombreError }}</small>
            </div>
            <div class="p-field input-container">
              <label for="descripcion">Descripción</label>
              <InputText id="descripcion" v-model="descripcion" required :class="{'p-invalid': descripcionError}" @input="validarDescripcionEnTiempoReal" />
              <small class="p-error error-message" v-if="descripcionError">{{ descripcionError }}</small>
            </div>
            <div class="p-field input-container">
              <label for="codigo">Código</label>
              <InputNumber :useGrouping="false" id="codigo" v-model="codigoProductoSin" required :class="{'p-invalid': codigoProductoSinError}" @input="validarCodigoEnTiempoReal" />
              <small class="p-error error-message" v-if="codigoProductoSinError">{{ codigoProductoSinError }}</small>
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
import InputNumber from 'primevue/inputnumber';
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
      InputNumber
    },
    data() {
      return {
        buscarTexto: '',
        items: [
          { opciones: '', nombre: 'linea2', codigo: 3, descripcion: 'linea2', estado: 'Activo' },
          { opciones: '', nombre: 'LINEA1', codigo: 1, descripcion: 'LINEA1', estado: 'Activo' },
        ],
        modal: false,
        tituloModal: '',
        tipoAccion: 1,
        nombre: '',
        descripcion: '',
        codigoProductoSin: 0,
        codigoProductoSinError: '',
        nombreError: '',
        descripcionError: '',
        codigoError: '',
        arrayCategoria: [],
        criterio: 'nombre',
        buscar: '',
        modalImportar: 0,
        showUpload: false,
        importar: false,
        archivo: null
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
          // Crear un formulario temporal
          const tempForm = document.createElement('form');
          tempForm.id = 'mainFormUsers';

          // Crear un input de archivo y añadirlo al formulario
          const fileInput = document.createElement('input');
          fileInput.type = 'file';
          fileInput.name = 'select_users_file'; // Asegúrate de que este nombre coincida con lo que espera tu backend
          tempForm.appendChild(fileInput);

          // Asignar el archivo seleccionado al input
          const dataTransfer = new DataTransfer();
          dataTransfer.items.add(this.archivo);
          fileInput.files = dataTransfer.files;
          const formData = new FormData(tempForm);
          axios.post('/linea/import_excel', formData)
                .then((res) => {
                    console.log("Importado");
                    swal(
                        'IMPORTADO!',
                        'Lista de Linea.',
                        'success'
                    );
                    this.cerrarModalImportar();
                    this.listarCategoria(1, '', 'nombre');
                }).catch(function (error) {
                    console.log(error);
                });
      },
      showUploadDialog() {
        this.importar = true;
      },
      cargarExcel() {
            window.open('/linea/exportexcel', '_blank');
        },
      validarNombreEnTiempoReal() {
        if (!this.nombre.trim()) {
          this.nombreError = "El nombre de la linea no puede estar vacío.";
        } else {
          this.nombreError = '';
        }
      },
      validarDescripcionEnTiempoReal() {
        if (!this.descripcion.trim()) {
          this.descripcionError = "La descripción de la linea no puede estar vacía.";
        } else {
          this.descripcionError = '';
        }
      },
      validarCodigoEnTiempoReal() {
      if (this.codigoProductoSin === null || this.codigoProductoSin === undefined || String(this.codigoProductoSin).trim() === '') {
        this.codigoProductoSinError = 'El código no puede estar vacío.';
      } else {
        this.codigoProductoSinError = '';
      }
    },
      registrarCategoria() {
        if (this.validarCategoria()) {
           return;
        }
        let me = this;
        axios.post('/categoria/registrar', {
            'nombre': this.nombre,
            'descripcion': this.descripcion,
            'codigoProductoSin': this.codigoProductoSin
        }).then(function (response) {
            me.cerrarModal();
            me.listarCategoria(1, '', 'nombre');
        }).catch(function (error) {
            console.log(error);
        });
      },
      validarCategoria() {
        this.errorCategoria = 0;
        this.errorMostrarMsjCategoria = [];
        if (!this.nombre) this.errorMostrarMsjCategoria.push("El nombre de la categoría no puede estar vacío.");
        if (this.errorMostrarMsjCategoria.length) this.errorCategoria = 1;
        return this.errorCategoria;
      },
      actualizarCategoria() {
        if (this.validarCategoria()) {
            return;
        }
        let me = this;
        axios.put('/categoria/actualizar', {
            'nombre': this.nombre,
            'descripcion': this.descripcion,
            'codigoProductoSin': this.codigoProductoSin,
            'id': this.categoria_id
        }).then(function (response) {
            me.cerrarModal();
            me.listarCategoria(1, '', 'nombre');
        }).catch(function (error) {
            console.log(error);
        });
      },
      listarCategoria(page, buscar, criterio) {
        let me = this;
        var url = '/categorianewview?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
        axios.get(url).then(function (response) {
            var respuesta = response.data;
            //consol.log('Linea',respuesta);
            me.arrayCategoria = respuesta.categorias;
            me.pagination = respuesta.pagination;
        })
            .catch(function (error) {
                console.log(error);
            });
      },
      desactivarCategoria(id) {
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
             axios.put('/categoria/desactivar', {
                 'id': id
             }).then(function (response) {
                 me.listarCategoria(1, '', 'nombre');
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
        activarCategoria(id) {
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
             axios.put('/categoria/activar', {
                 'id': id
             }).then(function (response) {
                 me.listarCategoria(1, '', 'nombre');
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
            ) {}
            })
        },
      abrirModal(modelo, accion, data = []) {
        switch (modelo) {
          case "categoria":
            {
              switch (accion) {
                case 'registrar':
                  {
                    this.modal = true;
                    this.tituloModal = 'Registrar Categoría';
                    this.nombre = '';
                    this.descripcion = '';
                    this.codigoProductoSin = 0;
                    this.tipoAccion = 1;
                    break;
                  }
                case 'actualizar':
                  {
                    //console.log(data);
                    this.modal = true;
                    this.tituloModal = 'Actualizar categoría';
                    this.tipoAccion = 2;
                    this.categoria_id = data['id'];
                    this.nombre = data['nombre'];
                    this.descripcion = data['descripcion'];
                    this.codigoProductoSin = data['codigoProductoSin'];
                    break;
                  }
              }
            }
        }
        },
        cerrarModal() {
            this.modal = false;
            this.tituloModal = '';
            this.nombre = '';
            this.descripcion = '';
            this.codigoProductoSin = '';
            this.nombreError= '';
            this.descripcionError= '';
            this.codigoProductoSinError = '';
        },
        cerrarModalImportar(){
          this.importar= false;
        }
    },
    mounted(){
      this.listarCategoria(1, this.buscar, this.criterio);

    }
  };
  </script>
  
  <style scoped>
  .input-container {
    position: relative;
    padding-bottom: 20px; /* Espacio para el mensaje de error */
}

.input-container .p-inputtext {
    width: 100%;
    margin-bottom: 0; /* Eliminar margen inferior si existe */
}

.error-message {
    position: absolute;
    bottom: 0;
    left: 0;
    font-size: 0.75rem; /* Tamaño de fuente más pequeño */
    margin-top: 2px; /* Pequeño espacio entre el input y el mensaje */
}
>>>.p-paginator {
    padding: 0px;
  }
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

  .toolbar {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
  }
  .search-bar {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
  }
  .activo {
    color: green;
    font-weight: bold;
  }
  </style>
  