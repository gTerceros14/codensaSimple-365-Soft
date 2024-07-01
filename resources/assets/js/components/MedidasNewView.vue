<template>
    <main class="main">
      <Panel>
        <template #header>
          <div class="panel-header">
            <i class="pi pi-align-justify"></i>
            <h4 class="panel-title">Medidas</h4>
          </div>
        </template>
        <div class="toolbar-container">
            <div class="searchbar">
                <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText type="text" v-model="searchTerm" placeholder="Buscar por descripcion" class="p-inputtext-sm custom-input" />
                </span>
            </div>
          <!--<div class="toolbar">
            <Button label="Nuevo" icon="pi pi-plus" @click="abrirModal('medida', 'registrar')" class="p-button-secondary p-button-sm custom-icon-size" />
            <Button label="Exportar" icon="pi pi-file-excel" class="btn btn-info p-button-sm custom-icon-size" style="background-color: #0dcaf0; color: black;" />
            <Button label="Importar" icon="pi pi-upload" class="p-button-success p-button-sm custom-icon-size" style="background-color: green;" />
          </div>
          -->
        </div>
        <DataTable class="p-datatable-sm "   responsiveLayout="scroll" :value="filteredProducts" paginator :rows="9">
          <Column header="Opciones">
            <template #body="slotProps">
              <Button icon="pi pi-pencil" class="p-button-sm p-button-warning custom-icon-size"  @click="abrirModal('medida', 'actualizar', slotProps.data)" />
              <Button v-if="slotProps.data.estado" icon="pi pi-ban" class="p-button-sm p-button-danger custom-icon-size" @click="desactivarMedida(slotProps.data.id)" />
              <Button v-else icon="pi pi-check-circle" class="p-button-sm p-button-success custom-icon-size" @click="activarMedida(slotProps.data.id)" />

            </template>
          </Column>
          <Column field="descripcion_medida" header="Descripción" ></Column>
          <Column field="codigoClasificador" header="Código Clasificador" ></Column>
          <Column field="estado" header="Estado">
            <template #body="slotProps">
              <span :class="['status-badge', slotProps.data.estado === 1 ? 'active' : 'inactive']">
                {{  slotProps.data.estado === 1 ? 'Activo' : 'Inactivo'}}
              </span>
            </template>
          </Column>
        </DataTable>
    
        <Dialog :visible.sync="modal" modal :header="tituloModal" :closable="true">
          <template #footer>
            <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="cerrarModal()" />
            <Button v-if="tipoAccion === 1" label="Guardar" icon="pi pi-check" class="p-button-text" @click="registrarMedida()" />
            <Button v-if="tipoAccion === 2" label="Actualizar" icon="pi pi-check" class="p-button-text" @click="actualizarMedida()" />
          </template>
          <div class="p-fluid">
            <div class="p-field">
              <label for="name">Descripción</label>
              <InputText id="name" v-model="descripcionMedida" required autofocus />
            </div>
            <div class="p-field">
              <label for="code">Código Clasificador</label>
              <InputText id="code" v-model="codigoClasificador" required />
            </div>
          </div>
        </Dialog>
    
        <ConfirmDialog />
      </Panel>
    </main>
  </template>
    
  <script>
  import Panel from 'primevue/panel';
  import DataTable from 'primevue/datatable';
  import Column from 'primevue/column';
  import Button from 'primevue/button';
  import Dialog from 'primevue/dialog';
  import InputText from 'primevue/inputtext';
  import ConfirmDialog from 'primevue/confirmdialog';
    
  export default {
    name: 'DataTableCrud',
    components: {
      DataTable,
      Column,
      Button,
      Dialog,
      InputText,
      ConfirmDialog,
      Panel
    },
    data() {
      return {
        medida_id: 0,
        descripcionMedida: '',
        codigoClasificador: '',
        tipoAccion: 0,
        arrayMedida: [],
        criterio: 'descripcion_medida',
        buscar: '',
        modal:'',
        modal: false,
        selectedProduct: null,
        searchTerm: '',
        tituloModal:'',
        errorMedida: 0,
      errorMostrarMsjMedida: [],
      };
    },
    computed: {
      filteredProducts() {
        return this.arrayMedida.filter(arrayMedida => 
        arrayMedida.descripcion_medida.toLowerCase().includes(this.searchTerm.toLowerCase())
        );
      }
    },
    methods: {

      listarMedidas(page, buscar, criterio) {
        let me = this;
        var url = '/medida2?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
        axios
          .get(url)
          .then(function (response) {
            var respuesta = response.data;
            me.arrayMedida = respuesta.medidas;
            me.paginationMedida = respuesta.paginationMedida;
          })
          .catch(function (error) {
            console.log(error);
          });
      },
      registrarMedida() {
        if (this.validarMedida()) {
          return;
        }

        let me = this;
        axios
          .post('/medida/registrar', {
            descripcion_medida: this.descripcionMedida,
            codigoClasificador: this.codigoClasificador,
          })
          .then(function (response) {
            me.cerrarModal();
            me.listarMedidas(1, '', 'descripcion_medida');
          })
          .catch(function (error) {
            console.log(error);
          });
      },
      actualizarMedida() {
        if (this.validarMedida()) {
          return;
        }

        let me = this;
        axios
          .put('/medida/actualizar', {
            descripcion_medida: this.descripcionMedida,
            codigoClasificador: this.codigoClasificador,
            id: this.medida_id,
          })
          .then(function (response) {
            me.cerrarModal();
            me.listarMedidas(1, '', 'descripcion_medida');
          })
          .catch(function (error) {
            console.log(error);
          });
      },
      validarMedida() {
        this.errorMedida = 0;
        this.errorMostrarMsjMedida = [];

        if (!this.descripcionMedida) this.errorMostrarMsjMedida.push('La descripción de la medida no puede estar vacía.');
        if (!this.codigoClasificador) this.errorMostrarMsjMedida.push('El código clasificador no puede estar vacío.');

        if (this.errorMostrarMsjMedida.length) this.errorMedida = 1;

        return this.errorMedida;
      },
      desactivarMedida(id) {
        swal({
          title: 'Esta seguro de desactivar esta Medida?',
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

            axios.put('/medida/desactivar', {
              'id': id
            }).then(function (response) {
              me.listarMedidas(1, '', 'nombre');
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
      activarMedida(id) {
        swal({
          title: 'Esta seguro de activar esta Medida?',
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

            axios.put('/medida/activar', {
              'id': id
            }).then(function (response) {
              me.listarMedidas(1, '', 'nombre');
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
    },
    mounted() {
    this.listarMedidas(1, this.buscar, this.criterio);
  },
  };
  </script>
    
  <style scoped>
  >>> .custom-icon-size .pi {
  font-size: 1.2rem; /* Ajusta este tamaño según sea necesario */
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
  
  .p-fluid .p-field {
    margin-bottom: 1rem;
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
  .custom-button-sm {
  padding: 0; /* Eliminar el padding para un mejor control de las dimensiones */
  width: 35px; /* Ajusta este valor según sea necesario */
  height: 35px; /* Ajusta este valor según sea necesario */
  align-items: center;
  justify-content: center;
}

.custom-button-sm .pi {
  font-size: 1rem; /* Ajusta el tamaño del icono */
}
.custom-input {
  width: 300%;
 }
  @media (max-width: 768px) {
    .toolbar-container {
    flex-direction: column;
    align-items: flex-start;
    }
    .toolbar {
        width: 100%;
        margin-bottom: 10px;
        justify-content: space-between;
    }
    .searchbar {
        margin-bottom: 10px;
        order: 1; /* Esto asegura que la barra de búsqueda esté abajo en vista móvil */
    }
    .custom-input {
      width: 150%;
    }
  }
  </style>
  