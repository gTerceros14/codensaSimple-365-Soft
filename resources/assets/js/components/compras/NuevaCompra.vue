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
                            <h5>Complete el Formulario</h5>
                        </template>

                        <template #content>
                            <div class="p-fluid p-formgrid p-grid">
                                <div class="p-field p-col-12 p-md-3">
                                    <label for="proveedor">Proveedor</label>
                                    <Dropdown id="proveedor" class="p-inputtext-sm" v-model="form.proveedorSeleccionado" :options="proveedores" optionLabel="nombre" placeholder="Lista proveedores ..." />
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

                    <!--<PickList v-model="products" listStyle="height:320px" dataKey="id">
                        <template #sourceheader>
                            Artículos disponibles ({{ form.proveedorSeleccionado.nombre }})
                        </template>
                        <template #targetheader>
                            Artículos seleccionados
                        </template>
                        <template #item="slotProps">
                            <div class="product-item">
                                <div class="image-container">
                                    <img :src="'demo/images/product/' + slotProps.item.image" :alt="slotProps.item.name" />
                                </div>
                                <div class="product-list-detail">
                                    <h5 class="mb-2">{{slotProps.item.name}}</h5>
                                    <i class="pi pi-tag product-category-icon"></i>
                                    <span class="product-category">{{slotProps.item.category}}</span>
                                </div>
                                <div class="product-list-action">
                                    <h6 class="mb-2">${{slotProps.item.price}}</h6>
                                    <span :class="'product-badge status-'+slotProps.item.inventoryStatus.toLowerCase()">{{slotProps.item.inventoryStatus}}</span>
                                </div>
                            </div>
                        </template>
                    </PickList>-->

                    <div class="p-grid">
                        <div class="p-col-6">
                            <div class="card">
                                <DataTable
                                    :value="arrayArticuloSeleccionado"
                                    selectionMode="multiple"
                                    dataKey="codigo"
                                    :selection.sync="articulosSeleccionados"
                                    responsiveLayout="scroll"
                                    :paginator="true"
                                    :rows="5"
                                >
                                    <template #header>
                                        <div class="tablas-articulos-header">
                                            <h5>Selecciona articulos</h5>
                                            <span class="p-input-icon-left">
                                                <i class="pi pi-search" />
                                                <InputText class="p-inputtext-sm" placeholder="Buscador global" />
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
                                    :value="articulosSeleccionados"
                                    dataKey="codigo"
                                    responsiveLayout="scroll"
                                    :paginator="true"
                                    :rows="5"
                                >
                                    <template #header>
                                        <div class="tablas-articulos-header">
                                            <h5>Articulos seleccionados</h5>
                                            <Button class="p-button-sm p-button-danger" label="Vaciar lista" icon="pi pi-trash" @click="actualizarLista"/>
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

                    <div class="total-saldo-card">
                        <Card>
                            <template #content>
                                <div class="saldo-total"><strong>TOTAL Bs. 0</strong></div>
                            </template>
                        </Card>
                    </div>
                </TabPanel>

                <TabPanel :disabled="activeIndex !== 1">
                    <template #header>
                        <Button  label="2" class="p-button-secondary p-button-rounded non-clickable" />
                    </template>

                    <InputText placeholder="Email" v-model="form.email" />
                </TabPanel>

            </TabView>

            <div class="flechas-buttons">
                <Button class="p-button-sm p-button-secondary" label="Anterior" @click="prevStep" :disabled="activeIndex === 0" style="margin-right: 30px;"/>
                <Button class="p-button-sm p-button-secondary" label="Siguiente" @click="nextStep" :disabled="activeIndex === steps.length - 1" />
            </div>

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
import PickList from 'primevue/picklist';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup'; 

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
                proveedorSeleccionado: '',
                tipo_comprobante: '',
                serie_comprobante: '',
                num_comprobante: ''
            },
            steps: [
                { label: 'Paso 1' },
                { label: 'Paso 2' },
            ],
            proveedores: [
                {nombre: 'Valentin', code: 'VL'},
                {nombre: 'German', code: 'GT'},
                {nombre: 'Armin', code: 'AG'},
                {nombre: 'Sandy', code: 'JS'},
            ],
            lista_comprobantes: [
                {nombre: 'Recibo', id: '1'},
                {nombre: 'Factura', id: '2'},
                {nombre: 'Ticket', id: '3'},
            ],

            codigo: '',
            idproveedor: 6,
            arrayArticuloSeleccionado: [],
            articulosSeleccionados: [],
            targetProducts: []
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
        PickList,
        DataTable,
        Column,
        ColumnGroup,
    },

    computed: {
        isMobile() {
            return window.innerWidth <= 576;
        },
    },

    methods: {
        actualizarLista() {
            console.log('SELECCIONADOS: ',this.articulosSeleccionados)
        },

        eliminarArticuloSeleccionado(articulo) {
            this.articulosSeleccionados = this.articulosSeleccionados.filter(a => a.id !== articulo.id);
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

        buscarArticulo() {
            clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                let me = this;
                var url = '/articulo/listarArticulo?buscar=' + me.codigo + '&criterio=' + 'codigo' + '&idProveedor=' + me.idproveedor
                axios
                    .get(url)
                    .then(function (response) {
                        let respuesta = response.data;
                        me.arrayArticuloSeleccionado = respuesta.articulos.data;
                        console.log(me.arrayArticuloSeleccionado);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }, 1000);
        },

    },

    watch: {

    },

    created() {

    },

    mounted() {
        this.buscarArticulo();
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
    padding: 0.5rem 0;
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
</style>