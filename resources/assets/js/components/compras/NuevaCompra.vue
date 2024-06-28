<template>
    <main class="main">
        <Panel>
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
                                                <InputText v-model="buscardorArticulos" class="p-inputtext-sm" placeholder="Buscador global" />
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

                    <div class="card">
                        <DataTable
                            ref="dt-lista-completo"
                            :value="array_articulos_seleccionados"
                            dataKey="id"
                            :paginator="false"
                        >
                            <Column field="codigo" header="Codigo" :sortable="true"></Column>
                            <Column header="Image">
                                <template #body>
                                    <img src="img/producto-imagen-default.jpg" alt="Articulo sin foto" class="product-image" />
                                </template>
                            </Column>
                            <Column field="nombre" header="Nombre" :sortable="true"></Column>
                            <Column field="nombre_proveedor" header="Proveedor" :sortable="true"></Column>
                            <Column field="precio_costo_paq" header="Costo Paquete" :sortable="true"></Column>
                            <Column field="precio_costo_unid" header="Costo Unidad" :sortable="true"></Column>
                            <Column field="unidad_envase" header="U / Paquete" :sortable="true"></Column>
                            <Column header="Fecha Vencimiento" :headerStyle="{'width': '150px'}" :bodyStyle="{'width': '150px'}">
                            <template #body>
                                <div class="p-inputgroup">
                                <Calendar v-model="value" :style="{width: '100%'}"/>
                                </div>
                            </template>
                            </Column> 
                            <Column header="Unidades" :sortable="true">
                                <template #body>
                                    <InputNumber
                                        id="horizontal"
                                        v-model="unidades_articulo"
                                        showButtons
                                        buttonLayout="horizontal"
                                        :step="1"
                                        :min="1"
                                        decrementButtonClass="p-button-danger"
                                        incrementButtonClass="p-button-success"
                                        incrementButtonIcon="pi pi-plus"
                                        decrementButtonIcon="pi pi-minus"
                                    />
                                </template>
                            </Column>
                            
                        </DataTable>
                    </div>
                    <Calendar v-model="value" :touchUI="true"  />
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
                {nombre: 'Factura', id: '2'},
                {nombre: 'Ticket', id: '3'},
            ],

            codigo: '',
            idproveedor: 6,

            buscardorArticulos: null,
            array_proveedores: [],
            loading: false,

            array_articulos_proveedor: [],
            array_articulos_seleccionados: [],

            unidades_articulo: 1,
            fechaVencimiento: null,
            minDate: null,

            value: null
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
    },

    computed: {
        isMobile() {
            return window.innerWidth <= 576;
        },
    },

    methods: {
        vaciarListaSeleccionados() {
            this.array_articulos_seleccionados.splice(0, this.array_articulos_seleccionados.length);
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

        buscardorArticulos(newVal) {
            if (newVal) {
                this.listarArticulo(this.buscardorArticulos, 'nombre');
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
.p-inputgroup {
    width: 100%;
}
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

.autocomplete-flecha >>> .p-button.p-button-icon-only {
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
</style>