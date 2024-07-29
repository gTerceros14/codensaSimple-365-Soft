<template>
    <div class="main">
        <div class="p-col-12">
            <Card>
                <template #header>
                    <div class="p-d-flex p-jc-between p-ai-center">
                        <h5 class="p-m-0">Inventario</h5>
                        <Button label="Importar" icon="pi pi-plus" @click="abrirModalImportar" class="p-button-success" />
                    </div>
                </template>
                <template #content>
                    <div class="p-grid p-mb-3">
                        <div class="p-col-4">
                            <label>ALMACEN DE TRABAJO</label>
                            <Dropdown v-model="AlmacenSeleccionado" :options="arrayAlmacenes" optionLabel="nombre_almacen" 
                                      optionValue="id" placeholder="Seleccione" @change="getDatosAlmacen" class="p-mb-2" />
                        </div>
                        <div class="p-col-4">
                            <label class="p-mb-2">MODO VISTA</label>
                            <div class="p-d-flex p-flex-column">
                                <RadioButton v-model="tipoSeleccionado" inputId="item" name="tipo" value="item" @change="cambiarTipo" />
                                <label for="item" class="p-ml-2">Por Item</label>
                                <RadioButton v-model="tipoSeleccionado" inputId="lote" name="tipo" value="lote" @change="cambiarTipo" />
                                <label for="lote" class="p-ml-2">Por Lote</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-grid p-mb-3">
                        <div class="p-col-6">
                            <span class="p-input-icon-left p-input-icon-right">
                                <i class="pi pi-search" />
                                <InputText v-model="buscar" placeholder="Buscar por nombre de producto" 
                                           @input="listarInventario(1, buscar, criterio)" />
                                <Button icon="pi pi-search" @click="listarInventario" />
                            </span>
                        </div>
                    </div>

                    <DataTable :value="arrayInventario"  :rows="10" 
                               :rowsPerPageOptions="[5,10,20]" responsiveLayout="scroll">
                        <Column v-for="col in columnas" :key="col.field" :field="col.field" :header="col.header"></Column>
                    </DataTable>

                    <Paginator :rows="10" :totalRecords="pagination.total" 
                               @page="onPageChange($event)" :rowsPerPageOptions="[5,10,20]" />
                </template>
            </Card>
        </div>

        <div v-if="modalImportar">
            <ImportarExcelInventario @cerrar="cerrarModalImportar" />
        </div>
    </div>
</template>

<script>
import Card from 'primevue/card';
import Button from 'primevue/button';
import Dropdown from 'primevue/dropdown';
import RadioButton from 'primevue/radiobutton';
import InputText from 'primevue/inputtext';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Paginator from 'primevue/paginator';
import Dialog from 'primevue/dialog';

export default {
    components: {
        Card,
        Button,
        Dropdown,
        RadioButton,
        InputText,
        DataTable,
        Column,
        Paginator,
        Dialog
    },
    data() {
        return {
            arrayInventario: [],
            arrayAlmacenes: [],
            AlmacenSeleccionado: 1,
            idalmacen: 0,
            tipoSeleccionado: 'item',
            modalImportar: false,
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
            },
            offset: 3,
            criterio: 'nombre',
            buscar: '',
            columnas: []
        }
    },
    methods: {
        abrirModalImportar() {
            this.modalImportar = true;
        },
        cerrarModalImportar() {
            this.modalImportar = false;
            this.listarInventario(1, '', 'nombre');
        },
        cambiarPagina(page, buscar, criterio) {
            this.pagination.current_page = page;
            this.listarInventario(page, buscar, criterio);
        },
        listarInventario(page, buscar, criterio) {
            let me = this;
            let url = '/inventarios/itemLote/' + me.tipoSeleccionado + '?&idAlmacen=' + me.almacenSeleccionado + '&buscar=' + buscar + '&criterio=' + criterio + '&page=' + page;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayInventario = respuesta.inventarios.data;
                me.pagination = respuesta.pagination;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        selectAlmacen() {
            let me = this;
            var url = '/almacen/selectAlmacen';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayAlmacenes = respuesta.almacenes;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getDatosAlmacen() {
            if (this.AlmacenSeleccionado !== '') {
                this.almacenSeleccionado = this.AlmacenSeleccionado;
                this.idalmacen = Number(this.AlmacenSeleccionado);
                this.listarInventario(1, '', 'nombre');
            }
        },
        cambiarTipo() {
            this.getDatosAlmacen();
            this.setColumnas();
        },
        onPageChange(event) {
            this.cambiarPagina(event.page + 1, this.buscar, this.criterio);
        },
        setColumnas() {
            if (this.tipoSeleccionado === 'item') {
                this.columnas = [
                    {field: 'nombre_producto', header: 'Producto'},
                    {field: 'unidad_envase', header: 'Unidad X Paq.'},
                    {field: 'saldo_stock_total', header: 'Saldo Stock Total'},
                    {field: 'cantidad', header: 'Cantidad'},
                    {field: 'nombre_almacen', header: 'Almacén'}
                ];
            } else {
                this.columnas = [
                    {field: 'nombre_producto', header: 'Producto'},
                    {field: 'unidad_envase', header: 'Unid.X.Paq'},
                    {field: 'precio_costo_unid', header: 'Costo Unidad'},
                    {field: 'saldo_stock', header: 'Saldo Stock'},
                    {field: 'cantidad', header: 'Cantidad'},
                    {field: 'fecha_ingreso', header: 'Fecha Ingreso'},
                    {field: 'fecha_vencimiento', header: 'Fecha Vencimiento'},
                    {field: 'nombre_almacen', header: 'Almacén'}
                ];
            }
        }
    },
    mounted() {
        this.getDatosAlmacen();
        this.listarInventario(1, '', 'nombre');
        this.selectAlmacen();
        this.setColumnas();
    }
}
</script>
