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
            <Dialog :visible.sync="dialogVisible" :modal="true" header="Registrar Artículo" :closable="true" @hide="closeDialog" :style="{ '--overlay-zindex': '9999' }"
            :contentStyle="{ zIndex: 10000 }">
                <form>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="nombreProducto">Nombre del Producto *</label>
                            <InputText id="nombreProducto" v-model="nuevoArticulo.nombreComercial" placeholder="Ej. Ibuprofeno 400 mg (20 comprimidos)" class="form-control p-inputtext-sm" />
                        </div>
                        <div class="col-md-6">
                            <label for="nombreGenerico">Nombre Genérico *</label>
                            <InputText id="nombreGenerico" v-model="nuevoArticulo.nombreGenerico" placeholder="Ej. Ibuprofeno" class="form-control p-inputtext-sm" />
                        </div>
                        <div class="col-md-6">
                            <label for="foto">Foto del Producto</label>
                            <FileUpload name="foto[]"  chooseLabel="Seleccionar archivo" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="descripcion">Descripción del Producto *</label>
                            <Textarea id="descripcion" v-model="nuevoArticulo.descripcion" placeholder="Ej. Alivio rápido para el dolor de cabeza y fiebre" rows="3" class="form-control" />
                        </div>
                        <div class="col-md-6">
                            <label for="codigo">Código del Producto *</label>
                            <InputText id="codigo" v-model="nuevoArticulo.codigo" placeholder="Ej. SKU123" class="form-control p-inputtext-sm" />
                        </div>
                        <div class="col-md-6">
                            <label for="codigoAlfanumerico">Código Alfanumérico (Opcional)</label>
                            <InputText id="codigoAlfanumerico" v-model="nuevoArticulo.codigoAlfanumerico" placeholder="Ej. ABC123" class="form-control p-inputtext-sm" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="descripcionFabricacion">Descripción de Fabricación (Opcional)</label>
                            <Textarea id="descripcionFabricacion" v-model="nuevoArticulo.descripcionFabricacion" placeholder="Ej. Producto fabricado por Laboratorios XYZ" rows="3" class="form-control" />
                        </div>
                        <div class="col-md-6">
                            <label for="proveedor">Proveedor *</label>
                            <Dropdown id="proveedor" v-model="nuevoArticulo.proveedor" :options="proveedores" optionLabel="name" placeholder="Seleccione un proveedor" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="linea">Línea *</label>
                            <Dropdown id="linea" v-model="nuevoArticulo.linea" :options="lineas" optionLabel="name" placeholder="Seleccione una línea" class="form-control" />
                        </div>
                        <div class="col-md-6">
                            <label for="marca">Marca *</label>
                            <Dropdown id="marca" v-model="nuevoArticulo.marca" :options="marcas" optionLabel="name" placeholder="Seleccione una marca" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="industria">Industria *</label>
                            <InputText id="industria" v-model="nuevoArticulo.industria" placeholder="Ej. Farmacéutica" class="form-control p-inputtext-sm" />
                        </div>
                        <div class="col-md-6">
                            <label for="grupoFamilia">Grupo/Familia *</label>
                            <InputText id="grupoFamilia" v-model="nuevoArticulo.grupoFamilia" placeholder="Ej. Analgésicos" class="form-control p-inputtext-sm" />
                        </div>
                    </div>
                    <!-- Agregar campos adicionales -->
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="stockMinimo">Stock Mínimo *</label>
                            <InputText id="stockMinimo" v-model="nuevoArticulo.stockMinimo" placeholder="Ej. 50" class="form-control p-inputtext-sm" />
                        </div>
                        <div class="col-md-6">
                            <label for="unidadesPorPaquete">Unidades por Paquete *</label>
                            <InputText id="unidadesPorPaquete" v-model="nuevoArticulo.unidadesPorPaquete" placeholder="Ej. 10" class="form-control p-inputtext-sm" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="controlado">Controlado *</label>
                            <Dropdown id="controlado" v-model="nuevoArticulo.controlado" :options="controladoOptions" optionLabel="label" placeholder="Seleccione una opción" class="form-control" />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="precioEstudiante">Precio Estudiante *</label>
                            <InputText id="precioEstudiante" v-model="nuevoArticulo.precioEstudiante" placeholder="Ej. 15.00" class="form-control p-inputtext-sm" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="precioPublico">Precio Público *</label>
                            <InputText id="precioPublico" v-model="nuevoArticulo.precioPublico" placeholder="Ej. 20.00" class="form-control p-inputtext-sm" />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="precioFactura">Precio Factura *</label>
                            <InputText id="precioFactura" v-model="nuevoArticulo.precioFactura" placeholder="Ej. 18.00" class="form-control p-inputtext-sm" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="industria">Industria *</label>
                            <InputText id="industria" v-model="nuevoArticulo.industria" placeholder="Ej. Farmacéutica" class="form-control p-inputtext-sm" />
                        </div>
                        
                    </div>
                </form>
                <template #footer>
                    <div class="flex justify-content-end">
                        <Button label="Cancelar" icon="pi pi-times" @click="closeDialog" class="p-button-text mr-2" />
                        <Button label="Guardar" icon="pi pi-check" @click="saveArticulo" autofocus />
                    </div>
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
import Dropdown from 'primevue/dropdown';
import Textarea from 'primevue/textarea';
import axios from 'axios';

export default {
    name: 'ArticleTable',
    components: {
        DataTable,
        Column,
        Button,
        FileUpload,
        InputText,
        Panel,
        Dialog,
        Dropdown,
        Textarea
    },
    data() {
        return {
            dialogVisible: false,
            nuevoArticulo: {
                nombreComercial: '',
                nombreGenerico: '',
                descripcion: '',
                codigo: '',
                codigoAlfanumerico: '',
                descripcionFabricacion: '',
                proveedor: null,
                linea: null,
                marca: null,
                // ... otros campos necesarios
            },
            arrayArticulo: [],
            criterio: 'nombre',
            buscar: '',
            proveedores: [
                { name: 'Proveedor 1', code: 'PROV1' },
                { name: 'Proveedor 2', code: 'PROV2' },
                // ... más proveedores
            ],
            lineas: [
                { name: 'Línea 1', code: 'LIN1' },
                { name: 'Línea 2', code: 'LIN2' },
                // ... más líneas
            ],
            marcas: [
                { name: 'Marca 1', code: 'MAR1' },
                { name: 'Marca 2', code: 'MAR2' },
                // ... más marcas
            ],
        };
    },
    methods: {
        search() {
            // Lógica de búsqueda aquí
        },
        closeDialog() {
            this.dialogVisible = false;
        },
        saveArticulo() {
            // Lógica para guardar el artículo
        },
        abrir(){
            this.dialogVisible = true;
        },
        listarArticulo(page, buscar, criterio) {
            let me = this;
            var url = '/articulonewindex?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayArticulo = respuesta.articulos;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
    },
    mounted() {
        this.listarArticulo(1, this.buscar, this.criterio);

    }
};
</script>

<style scoped>
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.field {
    display: flex;
    flex-direction: column;
}

.photo-field {
    grid-column: span 2;
}

.full-width {
    grid-column: span 2;
}

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
    align-items: center;    
    justify-content: flex-start;
}
.p-dialog-mask {
  z-index: 9999 !important;
}
.p-dialog {
  z-index: 10000 !important;
}
</style>
