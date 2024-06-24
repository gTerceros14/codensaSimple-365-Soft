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

                    <div class="fomulario-card">
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

                        <!--<template #footer>
                            <Button icon="pi pi-sync" label="Actualizar productos" class="p-button-success p-button-sm" />
                        </template>-->
                    </Card>
                    </div>

                    <div class="actualizador-buscador">
                        <span class="p-input-icon-left">
                            <i class="pi pi-search"/>
                            <InputText style="width: 15rem;" placeholder="Buscar artículo ..." class="p-inputtext-sm" ></InputText>
                        </span>
                        <Button icon="pi pi-sync" label="Actualizar productos" class="p-button-success p-button-sm" style="width: 15rem;"/>
                    </div>


                    <PickList v-model="products" listStyle="height:342px" dataKey="id">
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
                    </PickList>

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
                {nombre: 'Boleta', id: '1'},
                {nombre: 'Factura', id: '2'},
                {nombre: 'Ticket', id: '3'},
            ],
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
    },

    computed: {
        isMobile() {
            return window.innerWidth <= 576;
        },
    },

    methods: {
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

    },

    watch: {

    },

    created() {

    },

    mounted() {

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

/* Buscador */
.actualizador-buscador {
    display: flex;
    justify-content: space-between;
    width: 100%;
    align-items: center;
    padding: 0.5rem 5.5rem;
}

/* Card */
.fomulario-card >>> .p-card {
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

/* Grid */
>>> .p-md-3 {
    padding: 0 0.65rem 0 0.5rem !important;
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