<template>
    <main class="main">
        <Panel>
            <template #header>
                <div class="panel-header">
                    <i class="pi pi-book panel-icon"></i>
                    <h4 class="panel-icon">Pagar Creditos de Compra</h4>
                </div>
            </template>

            <DataTable
                :value="array_ingresos"
                :paginator="true"
                class="p-datatable-sm"
                :rows="10"
                dataKey="id"
                :rowHover="true"
            >
                <template #header>
                    <div class="flex justify-content-between align-items-center">
                        <h5 class="m-0">Customers</h5>
                        <span class="p-input-icon-left">
                            <i class="pi pi-search" />
                            <InputText placeholder="Keyword Search" class="p-inputtext-sm"/>
                        </span>
                    </div>
                </template>

                <template #empty>
                    Sin compras a credito ...
                </template>

                <Column header="Acciones">
                    <template #body="slotProps">
                        <Button type="button" class="p-button-success p-button-sm" icon="pi pi-eye"></Button>
                        <Button type="button" class="p-button-warning p-button-sm" icon="pi pi-eject" @click="openModalListaCuotas(slotProps.data.id)"></Button>
                    </template>
                </Column>
                <Column field="proveedor" header="Proveedor" ></Column>
                <Column field="usuario" header="Comprador" ></Column>
                <Column field="nombre_almacen" header="Almacen" ></Column>
                <Column field="total" header="Total Compra" ></Column>
                <Column field="cuota_inicial" header="Cuota Inicial" ></Column>
                <Column field="saldo_restante" header="Monto Pendiente" ></Column>
                <Column header="Estado">
                    <template #body="slotProps">
                        <Tag v-if="slotProps.data.estado_pago == 'Pagado'" class="mr-2" severity="success" value="Success">Pagado</Tag>
                        <Tag v-else class="mr-2" severity="warning" value="Warning">Pendiente</Tag>
                    </template>
                </Column>
            </DataTable>
        </Panel>

        <Dialog header="Cuotas Pendientes" :visible.sync="displayListaCuotas" :modal="true" position="center" :containerStyle="{width: '65vw'}">
                <DataTable
                    :value="array_cuotas"
                    :paginator="false"
                    class="p-datatable-sm"
                    dataKey="id"
                    :rowHover="true"
                >
                    <Column field="fecha_pago" header="Fecha Pago"></Column>
                    <Column field="precio_cuota" header="Precio Cuota"></Column>
                    <Column field="total_cancelado" header="Total Cancelado"></Column>
                    <Column field="saldo_restante" header="Saldo Restante"></Column>
                    <Column field="fecha_cancelado" header="Fecha Cancelado"></Column>
                    <Column field="estado" header="Estado"></Column>
                    <Column :bodyStyle="{'text-align': 'center'}">
                        <template #body>
                            <Button type="button" class="p-button-sm p-button-success" label="Pagar" icon="pi pi-dollar"></Button>
                        </template>
                    </Column>
                </DataTable>
                <template #footer>
                </template>
        </Dialog>

    </main>
</template>

<script>
import useVuelidate from '@vuelidate/core';
import { required, minValue } from '@vuelidate/validators';
import Panel from 'primevue/panel';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup'; 
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Dialog from 'primevue/dialog';

export default {
    setup () {
        return { 
            v$: useVuelidate() 
        }
    },

    data() {
        return {

            array_ingresos: [],
            array_cuotas: [],
            displayListaCuotas: false,
        }
    },

    components: { 
        Panel,
        DataTable,
        Column,
        ColumnGroup,
        InputText,
        Button,
        Tag,
        Dialog,
    },

    computed: {

    },

    watch: {

    },

    methods: {

        listarIngresosCuotas() {
            let me = this;
            var url = '/ingresoCuotas/listarCuotas';
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.array_ingresos = respuesta.ingresos;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        async listarCuotasPorIngreso(ingresoId) {

            try {
                const cuotasResponse = await axios.get('/ingresoCuotas/cuotasPorIngreso?id=' + ingresoId);
            
                if (cuotasResponse.data.status === 'success') {
                    this.array_cuotas = cuotasResponse.data.cuotas;
                }
            } catch (error) {

            }
        },

        openModalListaCuotas(ingresoId) {
            this.displayListaCuotas = true;
            this.listarCuotasPorIngreso(ingresoId);
        },

        closeModalListaCuotas() {
            this.displayListaCuotas = false;
        }
    },

    created() {

    },

    mounted() {
        this.listarIngresosCuotas();
    },
}
</script>

<style scoped>
/* Panel */
.panel-header {
    display: flex;
    align-items: center;
}

.panel-icon {
    font-size: 2rem;
    padding-left: 10px;
}

.panel-icon {
    font-size: 1.5rem;
    margin: 0;
}
</style>