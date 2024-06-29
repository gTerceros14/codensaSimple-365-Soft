<template>
    <main class="main">
        <Panel>
            <Toast :breakpoints="{'920px': {width: '100%', right: '0', left: '0'}}" style="padding-top: 40px;"></Toast>
            <template #header>
                <div class="panel-header">
                    <i class="pi pi-shopping-cart panel-icon"></i>
                    <h4 class="panel-title">Registrar Compra</h4>
                </div>
            </template>

            <template #icons>
                <Button class="p-button-warning p-button-sm" icon="pi pi-cog" />
            </template>

            <TabView ref="tabview" :activeIndex="activeIndex">
                <TabPanel :disabled="activeIndex !== 0">
                    <template #header>
                        <Button label="1" class="p-button-secondary p-button-rounded non-clickable" />
                    </template>

                    <div class="card formulario-card">
                    <Card>
                        <template #title>
                            <div style="padding-left: 1rem;">
                                <h5>Complete el Formulario</h5>
                            </div>
                        </template>

                        <template #content>
                            <div class="p-fluid p-formgrid p-grid">
                                <div class="p-field p-col-12 p-md-3">
                                    <label for="autocomplete">Proveedor</label>
                                    <div class="autocomplete-flecha">
                                        <AutoComplete
                                            id="autocomplete"
                                            class="p-inputtext-sm"
                                            forceSelection
                                            :dropdown="true"
                                            v-model="form.proveedorSeleccionado" 
                                            :suggestions="array_proveedores" 
                                            field="nombre" 
                                            @complete="selectProveedor($event)" 
                                            placeholder="Buscar Proveedores..." 
                                        >
                                        </AutoComplete>
                                    </div>
                                </div>
                                <div class="p-field p-col-12 p-md-3">
                                    <label for="tipoComprobante">Tipo Comprobante</label>
                                    <Dropdown id="tipoComprobante" class="p-inputtext-sm" v-model="form.tipo_comprobante" :options="lista_comprobantes" optionLabel="nombre" placeholder="Lista comprobantes ..." />
                                </div>
                                <div class="p-field p-col-12 p-md-3">
                                    <label for="serieComprobante">Serie Comprobante</label>
                                    <InputText id="serieComprobante" class="p-inputtext-sm" v-model="form.serie_comprobante" placeholder="Serie ..." />
                                </div>
                                <div class="p-field p-col-12 p-md-3">
                                    <label for="numeroComprobante">Número Comprobante</label>
                                    <InputText id="numeroComprobante" class="p-inputtext-sm" v-model="form.num_comprobante" placeholder="Número ..." />
                                </div>
                            </div>
                        </template>

                    </Card>
                    </div>

                    <div class="p-grid">
                        <div class="p-col-6">
                            <div class="card">
                                <DataTable
                                    ref="dt-articulos"
                                    :value="array_articulos_proveedor"
                                    selectionMode="multiple"
                                    dataKey="codigo"
                                    :selection.sync="array_articulos_seleccionados"
                                    responsiveLayout="scroll"
                                    :paginator="true"
                                    :rows="5"
                                >
                                    <template #header>
                                        <div class="tablas-articulos-header">
                                            <h5>Selecciona articulos</h5>
                                            <span class="p-input-icon-left">
                                                <i class="pi pi-search" />
                                                <InputText v-model="buscadorArticulos" class="p-inputtext-sm" placeholder="Buscador global" />
                                            </span>
                                        </div>
                                    </template>

                                    <Column selectionMode="multiple" ></Column>
                                    <Column field="codigo" header="Codigo" :sortable="true" ></Column>
                                    <Column field="nombre" header="Nombre" :sortable="true" ></Column>
                                    <Column field="precio_costo_unid" header="Precio Unidad" :sortable="true" ></Column>
                                    <Column field="precio_costo_paq" header="Precio Paquete" :sortable="true" ></Column>

                                    <template #empty>
                                        Articulos no encontrados ...
                                    </template>
                                </DataTable>
                            </div>
                        </div>

                        <div class="p-col-6">
                            <div class="card">
                                <DataTable
                                    ref="dt-seleccionados"
                                    :value="array_articulos_seleccionados"
                                    dataKey="codigo"
                                    responsiveLayout="scroll"
                                    :paginator="true"
                                    :rows="5"
                                >
                                    <template #header>
                                        <div class="tablas-articulos-header">
                                            <h5>Articulos seleccionados</h5>
                                            <Button class="p-button-sm p-button-danger" label="Vaciar lista" icon="pi pi-trash" @click="vaciarListaSeleccionados"/>
                                        </div>
                                    </template>

                                    <Column field="codigo" header="Codigo" :sortable="true" ></Column>
                                    <Column field="nombre" header="Nombre" :sortable="true" ></Column>
                                    <Column field="precio_costo_unid" header="Precio Unidad" :sortable="true" ></Column>
                                    <Column field="precio_costo_paq" header="Precio Paquete" :sortable="true" ></Column>
                                    <Column :headerStyle="{'min-width': '4rem', 'text-align': 'center'}" :bodyStyle="{'text-align': 'center', overflow: 'visible'}">
                                        <template #body="slotProps">
                                            <Button type="button" icon="pi pi-delete-left" class="p-button-danger p-button-sm" @click="eliminarArticuloSeleccionado(slotProps.data)"></Button>
                                        </template>
                                    </Column>

                                    <template #empty>
                                        Sin articulos seleccionados ...
                                    </template>
                                </DataTable>
                            </div>
                        </div>
                    </div>

                    <!--<div class="total-saldo-card">
                        <Card>
                            <template #content>
                                <div class="saldo-total"><strong>TOTAL Bs. 0</strong></div>
                            </template>
                        </Card>
                    </div>-->

                    <Button class="p-button-success p-button-sm" icon="pi pi-sync" label="Actualizar lista" @click="actualizarLista"></Button>

                    <div class="card">
                        <DataTable
                            ref="dt-lista-completo"
                            :value="array_articulos_completo"
                            dataKey="id"
                            :paginator="false"
                            :expandedRows.sync="expandedRows"
                            @row-expand="onRowExpand"
                            @row-collapse="onRowCollapse"
                            responsiveLayout="scroll"
                            editMode="row"
                            :editingRows.sync="editingRows"
                            @row-edit-save="onRowEditSave"
                        >
                            <Column :expander="true" :headerStyle="{'width': '3%'}" />
                            <Column field="codigo" header="Codigo" :sortable="true" :styles="{width:'5%'}"></Column>
                            <Column header="Image" :styles="{width:'7%'}">
                                <template #body>
                                    <img src="img/producto-imagen-default.jpg" alt="Articulo sin foto" class="product-image" />
                                </template>
                            </Column>
                            <Column field="nombre" header="Nombre" :sortable="true" :styles="{width:'15%'}"></Column>
                            <Column field="nombre_proveedor" header="Proveedor" :styles="{width:'10%'}"></Column>
                            <Column field="unidad_envase" header="Unidades por Paquete" :sortable="true" :styles="{width:'10%'}" :bodyStyle="{'text-align':'center'}"></Column>
                            <Column field="precio_costo_paq" header="Costo Paquete" :sortable="true" :styles="{width:'20%'}" :bodyStyle="{'text-align':'center'}">
                                <template #editor="slotProps">
                                    <InputText v-model="slotProps.data[slotProps.column.field]" class="p-inputtext-sm"/>
                                </template>
                            </Column>
                            <Column field="precio_costo_unid" header="Costo Unidad" :sortable="true" :styles="{width:'20%'}" :bodyStyle="{'text-align':'center'}">
                                <template #editor="slotProps">
                                    <InputText v-model="slotProps.data[slotProps.column.field]" class="p-inputtext-sm"/>
                                </template>
                            </Column>

                            <Column :rowEditor="true" :styles="{width:'10%', 'min-width':'8rem'}" :bodyStyle="{'text-align':'center'}"></Column>
                            <Column :headerStyle="{'width': '5%','min-width':'8rem'}" :bodyStyle="{'text-align': 'center', overflow: 'visible'}">
                                <template #body="slotProps">
                                    <Button type="button" icon="pi pi-delete-left" class="p-button-danger p-button-sm" @click="eliminarArticuloListaCompleta(slotProps.data)"></Button>
                                </template>
                            </Column>

                            <template #empty>
                                Datos del articulo no encontrados ...
                            </template>

                            <template #expansion="slotProps">
                                <div class="orders-subtable">
                                    <h5>Informacion adicional</h5>
                                    <DataTable :value="[slotProps.data]" responsiveLayout="scroll">
                                        <Column header="Fecha Vencimiento" :styles="{width:'15%'}">
                                            <template #body="slotProps">
                                                <Calendar v-model="slotProps.data.vencimiento" :touchUI="true" dateFormat="dd.mm.yy" :minDate="minDate"/>
                                            </template>
                                        </Column>
                                        <Column field="nombre_categoria" header="Categoria" :styles="{width:'15%'}"></Column>
                                        <Column :header="slotProps.data.esPaquetesCantidad ? ' Cantidad en: Paquetes' : 'Cantidad en: Unidades'" :styles="{width:'20%'}">
                                            <template #body="slotProps">
                                                <div class="p-inputgroup">
                                                    <Button 
                                                        :label="slotProps.data.esPaquetesCantidad ? 'Paquetes' : 'Unidades'"
                                                        @click="toggleUnidadesPaquetesCantidad(slotProps.data)"
                                                        icon="pi pi-bell"
                                                        class="p-button-sm p-button-secondary"
                                                    />
                                                    <InputNumber
                                                        v-model="slotProps.data.unidades"
                                                        mode="decimal"
                                                        :step="1"
                                                        showButtons
                                                        :min="0"
                                                        decrementButtonClass="p-button-danger p-button-sm"
                                                        incrementButtonClass="p-button-sm"
                                                        incrementButtonIcon="pi pi-plus"
                                                        decrementButtonIcon="pi pi-minus"
                                                        @update="updateSubtotal(slotProps.data)"
                                                    />
                                                </div>
                                            </template>
                                        </Column>
                                        <Column :header="slotProps.data.esPaquetesBonificacion ? 'Bonificacion en: Paquetes' : 'Bonificacion en: Unidades'" :styles="{width:'20%'}">
                                            <template #body="slotProps">
                                                <div class="p-inputgroup">
                                                    <Button 
                                                        :label="slotProps.data.esPaquetesBonificacion ? 'Paquetes' : 'Unidades'"
                                                        @click="toggleUnidadesPaquetesBonificacion(slotProps.data)"
                                                        icon="pi pi-bell"
                                                        class="p-button-sm p-button-secondary"
                                                    />
                                                    <InputNumber
                                                        v-model="slotProps.data.bonificacion"
                                                        mode="decimal"
                                                        :step="1"
                                                        showButtons
                                                        :min="0"
                                                        decrementButtonClass="p-button-danger p-button-sm"
                                                        incrementButtonClass="p-button-sm"
                                                        incrementButtonIcon="pi pi-plus"
                                                        decrementButtonIcon="pi pi-minus"
                                                        @update="updateSubtotal(slotProps.data)"
                                                    />
                                                </div>
                                            </template>
                                        </Column>
                                        <Column field="descuento" header="Descuento" :styles="{width:'15%'}">
                                            <template #body="slotProps">
                                                <InputNumber v-model="slotProps.data.descuento" prefix="% " mode="decimal" :maxFractionDigits="2" :max="100" :min="0" @update="updateSubtotal(slotProps.data)"/>
                                            </template>
                                        </Column>
                                        <Column header="Subtotal" :styles="{width:'15%'}">
                                            <template #body="slotProps">
                                                <InputNumber disabled :value="calculateSubtotal(slotProps.data)" mode="decimal" />
                                            </template>
                                        </Column>

                                        <template #empty>
                                            Datos del articulo no encontrados ...
                                        </template>
                                    </DataTable>
                                </div>
                            </template>
                        </DataTable>
                        <Button class="p-button-sm p-button-help" icon="pi pi-sync" label="Actualizar" @click="mostrarListaCompleta"></Button>
                    </div>
                </TabPanel>

                <TabPanel :disabled="activeIndex !== 1">
                    <template #header>
                        <Button  label="2" class="p-button-secondary p-button-rounded non-clickable" />
                    </template>

                    <InputText placeholder="Email" v-model="form.email" />
                </TabPanel>

            </TabView>

            <!--<div class="flechas-buttons">
                <Button class="p-button-sm p-button-secondary" label="Anterior" @click="prevStep" :disabled="activeIndex === 0" style="margin-right: 30px;"/>
                <Button class="p-button-sm p-button-secondary" label="Siguiente" @click="nextStep" :disabled="activeIndex === steps.length - 1" />
            </div>-->

        </Panel>
    </main>
</template>

<script>
import useVuelidate from '@vuelidate/core';
import { email, maxLength, minLength, numeric, required } from '@vuelidate/validators';

import Button from 'primevue/button';
import Panel from 'primevue/panel';
import InputText from 'primevue/inputtext';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Card from 'primevue/card';
import Dropdown from 'primevue/dropdown';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import AutoComplete from 'primevue/autocomplete';
import InputNumber from 'primevue/inputnumber';
import Calendar from 'primevue/calendar';
import Toast from 'primevue/toast';
import SplitButton from 'primevue/splitbutton';


export default {
    setup () {
        return { 
            v$: useVuelidate() 
        }
    },

    data() {
        return {

            activeIndex: 0,
            form: {
                proveedorSeleccionado: null, 
                tipo_comprobante: '',
                serie_comprobante: '',
                num_comprobante: ''
            },
            steps: [
                { label: 'Paso 1' },
                { label: 'Paso 2' },
            ],
            lista_comprobantes: [
                {nombre: 'Recibo', id: '1'},
                {nombre: 'Nota de Ingreso', id: '2'},
            ],

            codigo: '',
            idproveedor: null,

            buscadorArticulos: null,
            array_proveedores: [],
            loading: false,

            array_articulos_proveedor: [],
            array_articulos_seleccionados: [],
            array_articulos_completo: [],

            minDate: null,

            expandedRows: [],
            editingRows: [],
        }
    },

    validations() {
        return {
            form: {
                proveedor: {
                    required,
                }
            }
        }
    },

    components: {
        Button,
        Panel,
        InputText,
        TabView,
        TabPanel,
        Card,
        Dropdown,
        DataTable,
        Column,
        ColumnGroup,
        AutoComplete,
        InputNumber,
        Calendar,
        Toast,
        SplitButton,
    },

    computed: {
        isMobile() {
            return window.innerWidth <= 576;
        },
    },

    methods: {
        mostrarListaCompleta() {
            console.log('LISTA COMPLETO: ', this.array_articulos_completo);
        },

        /*updateSubtotal(articulo) {
            const cantidad = articulo.unidades;
            const bonificacion = articulo.bonificacion;
            const precio = articulo.esPaquetesCantidad ? articulo.precio_costo_paq : articulo.precio_costo_unid;
            articulo.subtotal = cantidad * precio;
            const unidadesBonificacion = articulo.esPaquetesBonificacion ? bonificacion * articulo.unidad_envase : bonificacion;
            articulo.unidadesTotales = (articulo.esPaquetesCantidad ? cantidad * articulo.unidad_envase : cantidad) + unidadesBonificacion;
        },*/

        updateSubtotal(articulo) {
            const cantidad = articulo.unidades;
            const bonificacion = articulo.bonificacion;
            const precio = articulo.esPaquetesCantidad ? articulo.precio_costo_paq : articulo.precio_costo_unid;
            const descuento = (articulo.descuento / 100);
            const precioDescontado = precio * (1 - descuento);
            articulo.subtotal = cantidad * precioDescontado;
            const unidadesBonificacion = articulo.esPaquetesBonificacion ? bonificacion * articulo.unidad_envase : bonificacion;
            articulo.unidadesTotales = (articulo.esPaquetesCantidad ? cantidad * articulo.unidad_envase : cantidad) + unidadesBonificacion;
        },

        /*calculateSubtotal(articulo) {
            const cantidad = articulo.unidades;
            const precio = articulo.esPaquetesCantidad ? articulo.precio_costo_paq : articulo.precio_costo_unid;
            return cantidad * precio;
        },*/

        calculateSubtotal(articulo) {
            const cantidad = articulo.unidades;
            const precio = articulo.esPaquetesCantidad ? articulo.precio_costo_paq : articulo.precio_costo_unid;
            const descuento = (articulo.descuento / 100);
            const precioDescontado = precio * (1 - descuento);
            return cantidad * precioDescontado;
        },

        toggleUnidadesPaquetesCantidad(articulo) {
            articulo.esPaquetesCantidad = !articulo.esPaquetesCantidad;
            this.updateSubtotal(articulo);
        },

        toggleUnidadesPaquetesBonificacion(articulo) {
            articulo.esPaquetesBonificacion = !articulo.esPaquetesBonificacion;
            this.updateSubtotal(articulo);
        },

        actualizarLista() {
            this.array_articulos_completo = this.array_articulos_seleccionados.map(articulo => ({
                ...articulo,
                unidadesTotales: 0,
                vencimiento: null,
                unidades: 0,
                bonificacion: 0,
                descuento: 0,
                subtotal: 0,
                esPaquetesCantidad: false,
                esPaquetesBonificacion: false,
            }));
            console.log('ARRAY COMPLETO:', this.array_articulos_completo);
        },

        onRowEditSave(event) {
            console.log('EDITINGROWS antes: ', this.editingRows)
            let { newData, index } = event;

            this.array_articulos_completo[index] = newData;
            console.log('EDITINGROWS despues: ', this.editingRows)
        },

        onRowExpand(event) {
            console.log('EXPANDEDROWS: ',this.expandedRows)
            this.$toast.add({severity: 'info', summary: 'Product Expanded', detail: event.data.name, life: 3000});
        },

        onRowCollapse(event) {
            console.log('EXPANDEDROWS: ',this.expandedRows)
            this.$toast.add({severity: 'success', summary: 'Product Collapsed', detail: event.data.name, life: 3000});
        },

        vaciarListaSeleccionados() {
            this.array_articulos_seleccionados.splice(0, this.array_articulos_seleccionados.length);
        },

        eliminarArticuloListaCompleta(articulo) {
            this.array_articulos_completo = this.array_articulos_completo.filter(a => a.id !== articulo.id);
        },

        eliminarArticuloSeleccionado(articulo) {
            this.array_articulos_seleccionados = this.array_articulos_seleccionados.filter(a => a.id !== articulo.id);
        },

        nextStep() {
            if (this.activeIndex < this.steps.length - 1) {
                this.activeIndex++;
            }
        },

        prevStep() {
            if (this.activeIndex > 0) {
                this.activeIndex--;
            }
        },

        listarArticulo(buscar, criterio) {
            let me = this;
            var url = '/articulo/listarArticulo?buscar=' + buscar + '&criterio=' + 'nombre' + '&idProveedor=' + this.idproveedor;
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.array_articulos_proveedor = respuesta.articulos.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        selectProveedor(event) {
            let me = this;

            if (!event.query.trim().length) {
                var url = `/proveedor?page=${1}&buscar=${''}&criterio=${'todos'}&por_pagina=${3}`;
                axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.array_proveedores = respuesta.personas.data;
                        me.loading = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                        me.loading = false;
                    });
            }
            else {
                this.loading = true;

                var url = `/proveedor?page=${1}&buscar=${me.form.proveedorSeleccionado}&criterio=${'todos'}&por_pagina=${3}`;
                axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.array_proveedores = respuesta.personas.data;
                        me.loading = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                        me.loading = false;
                    });
                }
            }

    },

    watch: {
        'form.proveedorSeleccionado.id': {
            handler(newVal) {
                if (newVal) {
                    this.idproveedor = newVal;
                    this.listarArticulo('', 'nombre');
                }
            }
        },

        buscadorArticulos(newVal) {
            if (newVal) {
                this.listarArticulo(this.buscadorArticulos, 'nombre');
            }
        }
    },

    created() {
        this.minDate = new Date();
    },

    mounted() {
        //this.buscarArticulo();
    },

    beforeDestroy() {
        
    },
}
</script>

<style scoped>
/* Panel */
>>> .p-panel-header {
    padding: 0.75rem;
}

.panel-header {
    display: flex;
    align-items: center;
    padding-right: 20px;
}

.panel-icon {
    font-size: 2rem;
    margin-right: 10px;
}

.panel-title {
    font-size: 1.5rem;
    margin: 0;
}

>>> .p-panel-content {
    padding: 0 0.75rem 0.75rem 0.75rem;
}

/* TabView */
>>> .p-tabview .p-tabview-nav li .p-tabview-nav-link {
    color: #5b5656 !important;
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
    padding-left: 23.3vw;
    padding-right: 23.3vw;
}

.non-clickable {
    cursor: default !important;
}

>>> .p-tabview-panels {
    padding: 1.5rem 0;
}

/* Card */
.formulario-card >>> .p-card {
    box-shadow: none !important;
}

.total-saldo-card >>> .p-card {
    box-shadow: none !important;
    background: #bcffbf;
    margin-top: 1.5rem;
    margin-left: 5.5rem;
    margin-right: 5.5rem;
}

>>> .p-card-content {
    padding: 0.5rem 0;
}

>>> .p-card-body {
    padding: 0.5rem 0 0 0;
}

>>> .p-card-footer {
    padding: 0 0 0 0;
    display: flex;
    justify-content: right;
}

>>> .autocomplete-flecha .p-button.p-button-icon-only {
    padding: 0.4rem 0;
}

/* Tablas Articulos */
.tablas-articulos-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Grid */
>>> .p-md-3 {
    padding: 0 1.5rem 0 1.5rem !important;
    margin-bottom: 0.5rem;
}

.flechas-buttons {
    padding-top: 0.5rem;
    display: flex;
    justify-content: right;
}

.saldo-total {
    margin: 0.5rem 0.5rem;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Tabla Lista Completa */
.product-image {
    border-radius: 10px;
    width: 100px;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
}

>>> .p-inputgroup > .p-inputwrapper:last-child > .p-inputtext {
    border-top-right-radius: 0px !important;
    border-bottom-right-radius: 0px !important;
}

/* Calendar */
@media (min-width: 768px) {
    >>> .p-datepicker-touch-ui {
        min-width: 60vw !important;
    }

    >>> .p-datepicker table th {
        padding: 0.5rem 0.5rem 0.5rem 3.2em;
    }
}
</style>