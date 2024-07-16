<template>
    <main class="main">
        <Panel>
            <template #header>
                <div class="panel-header">
                    <i class="pi pi-bars"></i>
                    <h4 class="panel-title">Artículos</h4>
                </div>
            </template>
            <div class="toolbar-container">
                <div class="toolbar">
                    <Button label="Nuevo" icon="pi pi-plus" class="p-button-secondary p-button-sm" @click="abrir"/>
                    <Button label="Reporte" icon="pi pi-file" class="p-button-success p-button-sm" />
                    <Button label="Importar" icon="pi pi-upload" class="p-button-help p-button-sm" />
                </div>
                <div class="searchbar">
                    <InputText v-model="searchText" placeholder="Texto a buscar" class="p-inputtext-sm" />
                    <Button label="Buscar" icon="pi pi-search" @click="search" class="p-button-help p-button-sm" />
                </div>
            </div>
            <DataTable :value="arrayArticulo" class="p-datatable-gridlines p-datatable-sm" responsiveLayout="scroll" paginator :rows="9">
                <Column field="acciones" header="ACCIONES" />
                <Column field="codigo" header="CODIGO" />
                <Column field="nombre" header="NOMBRE COMERCIAL" />
                <Column field="nombre_generico" header="NOMBRE GENERICO" />
                <Column field="unidad_envase" header="UNIDADES POR PAQUETE" />
                <Column field="precio_costo_unid" header="COSTO UNITARIO" />
                <Column field="precio_costo_paq" header="COSTO PAQUETE" />
                <Column field="precio_uno" header="PRECIO ESTUDIANTE" />
                <Column field="precio_dos" header="PRECIO PUBLICO" />
                <Column field="precio_tres" header="PRECIO FACTURA" />
                <Column field="nombre_categoria" header="LINEA" />
                <Column field="nombre_industria" header="INDUSTRIA" />
                <Column field="nombre_marca" header="MARCA" />
                <Column field="stock" header="STOCK MINIMO" />
                <Column field="nombre_proveedor" header="PROVEEDOR" />
                <Column field="descripcion" header="DESCRIPCION" />
                <Column field="condicion" header="CONTROLADO" />
                <Column field="nombre_grupo" header="GRUPO/FAMILIA" />
                <Column field="fotografia" header="FOTO" />
            </DataTable>
        </Panel>
        <!-- MODAL REGISTRAR PRODUCTO -->
        <Dialog :visible.sync="dialogVisible" :modal="true" header="Registrar Artículo" :closable="true" @hide="closeDialog" :containerStyle="{width: '800px'}" >
            <form>
                <div class="form-group row">
                    <div class="col-md-6">
                        <div>
                            <label class="font-weight-bold" for="nombreProducto">Nombre del Producto <span class="text-danger">*</span></label>
                            <InputText id="nombreProducto" v-model="nuevoArticulo.nombreComercial" placeholder="Ej. Ibuprofeno 400 mg (20 comprimidos)" class="form-control p-inputtext-sm" />
                        </div>
                        <div>
                            <label class="font-weight-bold" for="nombreGenerico">Nombre Genérico <span class="text-danger">*</span></label>
                            <InputText id="nombreGenerico" v-model="nuevoArticulo.nombreGenerico" placeholder="Ej. Ibuprofeno" class="form-control p-inputtext-sm" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div>
                            <label class="font-weight-bold" for="foto">Foto del Producto</label>
                            <div class="container">
                                <div class="row">
                                    <div class="d-flex justify-content-center">
                                        <div v-if="!imagen" class="bg-light p-5 rounded">
                                            <i class="fa fa-camera fa-2x" style="color:#6e6e6e" aria-hidden="true"></i>
                                        </div>
                                        <figure v-else>
                                            <ImagePreview :src="imagen" alt="Image" width="140" height="140" />
                                        </figure>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mt-2">
                                <input type="file" @change="obtenerFotografia" class="form-control" placeholder="fotografia" ref="fotografiaInput">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <div>
                            <label class="font-weight-bold" for="descripcion">Descripción del Producto <span class="text-danger">*</span></label>
                            <Textarea id="descripcion" v-model="nuevoArticulo.descripcion" placeholder="Ej. Alivio rápido para el dolor de cabeza y fiebre" rows="3" class="form-control p-inputtext-sm" />
                        </div>
                        <div>
                            <label class="font-weight-bold" for="codigoAlfanumerico">Código Alfanumérico <span class="text-danger">*</span></label>
                              <InputText id="codigoAlfanumerico" v-model="nuevoArticulo.codigoAlfanumerico" placeholder="Ej. ABC123" class="form-control p-inputtext-sm" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="font-weight-bold" for="codigo">Código del Producto <span class="text-danger">*</span> </label>
                        <InputText id="codigo" v-model="nuevoArticulo.codigo" placeholder="Ej. SKU123" class="form-control p-inputtext-sm" />
                        <div class="d-flex mt-4 justify-content-center" style="width:250px;overflow-x: auto;">
                            <barcode :value="nuevoArticulo.codigo" :options="{ format: 'EAN-13' }"></barcode>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label class="font-weight-bold" for="descripcionFabricacion">Descripción de Fabricación (Opcional)</label>
                        <Textarea id="descripcionFabricacion" v-model="nuevoArticulo.descripcionFabricacion" placeholder="Ej. Producto fabricado por Laboratorios XYZ" rows="3" class="form-control p-inputtext-sm" />
                    </div>
                    <div class="col-md-6">
                        <label class="font-weight-bold" for="proveedor">Proveedor <span class="text-danger">*</span></label>
                        <div class="p-inputgroup ">
                            <InputText id="proveedor" v-model="proveedorSeleccionado.nombre"  placeholder="Seleccione un proveedor" class="form-control p-inputtext-sm bold-input" disabled />
                            <Button label="..." class="p-button-primary p-button-sm" @click="abrirProveedores" />
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label class="font-weight-bold" for="linea">Línea <span class="text-danger">*</span></label>
                        <div class="p-inputgroup">
                            <InputText id="linea"  v-model="lineaSeleccionado.nombre" placeholder="Seleccione una línea" class="form-control p-inputtext-sm bold-input" disabled />
                            <Button label="..." class="p-button-primary p-button-sm" @click="abrirLineas" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="font-weight-bold" for="marca">Marca <span class="text-danger">*</span></label>
                        <div class="p-inputgroup">
                            <InputText id="marca"  v-model="marcaSeleccionado.nombre" placeholder="Seleccione una marca" class="form-control p-inputtext-sm bold-input" disabled />
                            <Button label="..." class="p-button-primary p-button-sm" @click="abrirMarcas" />
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label class="font-weight-bold" for="industria">Industria <span class="text-danger">*</span></label>
                        <div class="p-inputgroup">
                            <InputText id="industria" v-model="industriaSeleccionado.nombre"  placeholder="Seleccione una industria" class="form-control p-inputtext-sm bold-input" disabled />
                            <Button label="..." class="p-button-primary p-button-sm" @click="abrirIndustrias" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="font-weight-bold" for="grupoFamilia">Grupo/Familia <span class="text-danger">*</span></label>
                        <div class="p-inputgroup">
                            <InputText id="grupoFamilia"  v-model="grupoSeleccionado.nombre_grupo" placeholder="Seleccione un grupo" class="form-control p-inputtext-sm bold-input" disabled />
                            <Button label="..." class="p-button-primary p-button-sm" @click="abrirGrupos" />
                        </div>
                    </div>
                </div>
                <!-- Agregar campos adicionales -->
                <div class="form-group row">
                    <div class="col-md-4">
                        <label class="font-weight-bold" for="stockMinimo">Stock Mínimo <span class="text-danger">*</span></label>
                        <div class="p-inputgroup">
                            <InputNumber id="stockMinimo"  placeholder="Ej: 10" class="p-inputtext-sm" />
                            <Dropdown label="..." v-model="tipo_stock" :options="tipoEnvase" optionLabel="etiqueta" optionValue="valor" class="p-dropdown-sm p-inputtext-sm" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label  class="font-weight-bold" for="unidadEnvase">Unidades por Envase <span class="text-danger">*</span></label>
                        <div class="p-inputgroup">
                            <InputNumber id="unidadEnvase"placeholder="Ej: 24" class="p-inputtext-sm" />
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="font-weight-bold" for="medida">Medidas <span class="text-danger">*</span></label>
                        <div class="p-inputgroup">
                            <InputText id="medida" v-model="medidaSeleccionado.descripcion_medida"  placeholder="Seleccione una medida" class="form-control p-inputtext-sm bold-input" disabled />
                            <Button label="..." class="p-button-primary p-button-sm" @click="abrirMedidas" />
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label class="font-weight-bold" for="preciounitario">Precio Unitario <span class="text-danger">*</span></label>
                        <div class="p-inputgroup">
                            <InputNumber id="preciounitario"  v-model="grupoSeleccionado.nombre_grupo" placeholder="Sin decimales" class=" p-inputtext-sm bold-input" />
                            <Button label="Calcular" class="p-button-primary p-button-sm" @click="abrirGrupos" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="font-weight-bold"  for="preciopaquete">Precio Paquete <span class="text-danger">*</span></label>
                        <div class="p-inputgroup">
                            <InputNumber id="preciopaquete"  v-model="grupoSeleccionado.nombre_grupo" placeholder="Sin decimales" class=" p-inputtext-sm bold-input"  />
                            <Button label="Calcular" class="p-button-primary p-button-sm" @click="abrirGrupos" />
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6 switch-container" >
                        <label class="font-weight-bold" style="margin-bottom: 0px;"  for="switchvencimiento">Fecha vencimiento <span class="text-danger">*</span></label>
                            <InputSwitch id="switchvencimiento" v-model="fechaVencimientoSeleccion"  class="p-inputswitch-sm" />
                    </div>
                    <div class="col-md-6 switch-container">
                        <label class="font-weight-bold" style="margin-bottom: 0px;" for="switchstock">Agregar a Stock <span class="text-danger">*</span></label>
                            <InputSwitch id="switchstock" v-model="agregarStock"  class="p-inputswitch-sm" />
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-center">
                            <Button label="Guardar" icon="pi pi-save" class="p-button-primary p-button-sm" @click="guardarArticulo" />
                            <Button label="Cancelar" icon="pi pi-times" class="p-button-secondary p-button-sm ml-2" @click="closeDialog" />
                        </div>
                    </div>
                </div>
            </form>
        </Dialog>

        <!-- MODALES DINÁMICOS -->
        <DialogProveedores :visible.sync="mostrarDialogoProveedores" @close="cerrarDialogoProveedores" @proveedor-seleccionado="manejarProveedorSeleccionado" />
        <DialogLineas :visible.sync="mostrarDialogoLineas" @close="cerrarDialogoLineas" @linea-seleccionado="manejarLineaSeleccionado"/>
        <DialogMarcas :visible.sync="mostrarDialogoMarcas" @close="cerrarDialogoMarcas" @marca-seleccionado="manejarMarcaSeleccionado"/>
        <DialogIndustrias :visible.sync="mostrarDialogoIndustrias" @close="cerrarDialogoIndustrias" @industria-seleccionado="manejarIndustriaSeleecionado"/>
        <DialogGrupos :visible.sync="mostrarDialogoGrupos" @close="cerrarDialogoGrupos" @grupo-seleccionado="manejarGrupoSeleccionado"/>
        <DialogMedidas :visible.sync="mostrarDialogoMedidas" @close="cerrarDialogoMedidas" @medida-seleccionado="manejarMedidaSeleccionado"/>

    </main>
</template>

<script>
import Panel from 'primevue/panel';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Textarea from 'primevue/textarea';
import InputNumber from 'primevue/inputnumber';
import ImagePreview from 'primevue/imagepreview';
import DialogProveedores from './modales/DialogProveedores.vue';
import DialogLineas from './modales/DialogLineas.vue';
import DialogMarcas from './modales/DialogMarcas.vue';
import DialogIndustrias from './modales/DialogIndustrias.vue';
import DialogGrupos from './modales/DialogGrupos.vue';
import DialogMedidas from './modales/DialogMedidas.vue'
import Dropdown from 'primevue/dropdown';
import InputSwitch from 'primevue/inputswitch';
import VueBarcode from 'vue-barcode';
export default {
    components: {
        Panel,
        Button,
        InputText,
        Dialog,
        DataTable,
        Column,
        Textarea,
        InputNumber,
        ImagePreview,
        Dropdown,
        InputSwitch,
        'barcode': VueBarcode,
        DialogProveedores,
        DialogLineas,
        DialogMarcas,
        DialogIndustrias,
        DialogGrupos,
        DialogMedidas
    },
    data() {
        return {
            searchText: '',
            arrayArticulo: [], // Datos del artículo
            dialogVisible: false,
            agregarStock: false,
            fechaVencimientoSeleccion: false,
            nuevoArticulo: {
                nombreComercial: '',
                nombreGenerico: '',
                fotografia: null,
                descripcion: '',
                codigoAlfanumerico: '',
                codigo: '',
                descripcionFabricacion: '',
                proveedor: '',
                linea: '',
                marca: '',
                industria: '',
                grupoFamilia: '',
                stockMinimo: '',
                precioCosto: '',
                precioPublico: '',
                precioEstudiante: '',
                precioFactura: '',
                unidadEnvase: '',
                controlado: false,
            },
            tipo_stock: '',
            tipoEnvase: [
                { valor: "paquetes", etiqueta: "Paquetes" },
                { valor: "unidades", etiqueta: "Unidades" }
            ],
            mostrarDialogoProveedores: false,
            mostrarDialogoLineas: false,
            mostrarDialogoMarcas: false,
            mostrarDialogoIndustrias: false,
            mostrarDialogoGrupos: false,
            mostrarDialogoMedidas: false,
            imagen: null,
            proveedorSeleccionado: [],
            lineaSeleccionado: [],
            marcaSeleccionado: [],
            industriaSeleccionado : [],
            grupoSeleccionado: [],
            medidaSeleccionado: []
        };
    },
    methods: {
        abrir() {
            this.dialogVisible = true;
        },
        closeDialog() {
            this.dialogVisible = false;
        },
        obtenerFotografia(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagen = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        manejarProveedorSeleccionado(proveedor) {
            this.proveedorSeleccionado = proveedor;
            console.log('Proveedor seleccionado:', this.proveedorSeleccionado.nombre);
            // Puedes hacer cualquier otra cosa con el objeto seleccionado aquí
        },
        manejarLineaSeleccionado(linea){
            this.lineaSeleccionado = linea;
            console.log("grupo ",this.grupoSeleccionado)
        },
        manejarMarcaSeleccionado(marca){
            this.marcaSeleccionado = marca;
        },
        manejarIndustriaSeleecionado(industria){
            this.industriaSeleccionado = industria;
        },
        manejarGrupoSeleccionado(grupo){
            this.grupoSeleccionado = grupo;
        },
        manejarMedidaSeleccionado(medida){
            this.medidaSeleccionado = medida
        },
        abrirProveedores() {
            this.mostrarDialogoProveedores = true;
            this.dialogVisible= false;
        },
        cerrarDialogoProveedores() {
            this.mostrarDialogoProveedores = false;
            this.dialogVisible = true;
        },
        abrirLineas() {
            this.mostrarDialogoLineas = true;
            this.dialogVisible= false;
        },
        cerrarDialogoLineas() {
            this.mostrarDialogoLineas = false;
            this.dialogVisible = true;
        },
        abrirMarcas() {
            this.mostrarDialogoMarcas = true;
            this.dialogVisible= false;
        },
        cerrarDialogoMarcas() {
            this.mostrarDialogoMarcas = false;
            this.dialogVisible= true;
        },
        abrirIndustrias() {
            this.mostrarDialogoIndustrias = true;
            this.dialogVisible= false;
        },
        cerrarDialogoIndustrias() {
            this.mostrarDialogoIndustrias = false;
            this.dialogVisible= true;
        },
        abrirGrupos() {
            this.mostrarDialogoGrupos = true;
            this.dialogVisible= false;
        },
        cerrarDialogoGrupos() {
            this.mostrarDialogoGrupos = false;
            this.dialogVisible= true;
        },
        abrirMedidas() {
            this.mostrarDialogoMedidas = true;
            this.dialogVisible= false;
        },
        cerrarDialogoMedidas() {
            this.mostrarDialogoMedidas = false;
            this.dialogVisible= true;
        },
        guardarArticulo() {
            // Lógica para guardar el artículo
        },
        search() {
            // Lógica para la búsqueda
        },
    },
};
</script>

<style scoped>
.bold-input {
    font-weight: bold;
}
.panel-header {
    display: flex;
    align-items: center;
}
.panel-title {
    margin-left: 8px;
}
.toolbar-container {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}
.toolbar {
    display: flex;
    align-items: center;
}
.searchbar {
    display: flex;
    align-items: center;
}
.form-group {
    margin-bottom: 15px;
}
.p-dialog-mask {
  z-index: 9999 !important;
}
.p-dialog {
  z-index: 10000 !important;
}
>>> .p-dropdown .p-dropdown-trigger {
    width: 2rem;
}
.switch-container {
    display: flex;
    align-items: center;
    justify-content: space-evenly;
}
</style>
