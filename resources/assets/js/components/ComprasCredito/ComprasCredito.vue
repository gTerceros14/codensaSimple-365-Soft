<template>
    <main class="main">
        <Panel>
            <Toast :breakpoints="{'920px': {width: '100%', right: '0', left: '0'}}" style="padding-top: 40px;"></Toast>
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
                responsiveLayout="scroll"
            >
                <!--<template #header>
                    <div class="flex justify-content-between align-items-center">
                        <h5 class="m-0">Customers</h5>
                        <span class="p-input-icon-left">
                            <i class="pi pi-search" />
                            <InputText placeholder="Keyword Search" class="p-inputtext-sm"/>
                        </span>
                    </div>
                </template>-->

                <template #empty> Sin compras a credito ... </template>

                <Column header="Acciones">
                    <template #body="slotProps">
                        <Button
                            type="button"
                            class="p-button-success p-button-sm"
                            icon="pi pi-eye"
                            @click="listarDetallesIngreso(slotProps.data)"
                        ></Button>
                        <Button
                            type="button"
                            class="p-button-warning p-button-sm"
                            icon="pi pi-eject"
                            @click="openModalListaCuotas(slotProps.data.id)"
                        ></Button>
                    </template>
                </Column>
                <Column field="proveedor" header="Proveedor"></Column>
                <Column field="usuario" header="Comprador"></Column>
                <Column field="nombre_almacen" header="Almacen"></Column>
                <Column field="total" header="Total Compra"></Column>
                <Column field="cuota_inicial" header="Cuota Inicial"></Column>
                <Column field="saldo_restante" header="Deuda"></Column>
                <Column header="Estado">
                    <template #body="slotProps">
                        <Tag
                            v-if="slotProps.data.estado_pago == 'Pagado'"
                            class="mr-2"
                            severity="success"
                            value="Success"
                            >Pagado</Tag
                        >
                        <Tag
                            v-else
                            class="mr-2"
                            severity="warning"
                            value="Warning"
                            >Pendiente</Tag
                        >
                    </template>
                </Column>
            </DataTable>
        </Panel>

        <div class="div-lista-cuota">
            <Dialog
                header="Cuotas Pendientes"
                :visible.sync="displayListaCuotas"
                :modal="true"
                position="center"
                :containerStyle="{ width: '55vw' }"
                @hide="closeModalListaCuotas"
            >
                <DataTable
                    :value="array_cuotas"
                    :paginator="false"
                    class="p-datatable-sm"
                    dataKey="id"
                    :rowHover="true"
                    responsiveLayout="scroll"
                >
                    <Column
                        field="fecha_pago"
                        header="Fecha Pago"
                        :styles="{ width: '13%' }"
                    ></Column>
                    <Column field="precio_cuota" header="Precio Cuota"></Column>
                    <Column
                        field="total_cancelado"
                        header="Total Cancelado"
                    ></Column>
                    <Column
                        field="saldo_restante"
                        header="Saldo Restante"
                    ></Column>
                    <Column
                        field="fecha_cancelado"
                        header="Fecha Cancelado"
                        :styles="{ width: '13%' }"
                    ></Column>
                    <Column field="tipo_pago_cuota" header="Tipo Pago"></Column>
                    <Column header="Estado">
                        <template #body="slotProps">
                            <Tag
                                v-if="slotProps.data.estado == 'Pagado'"
                                class="mr-2"
                                severity="success"
                                value="Success"
                                >Pagado</Tag
                            >
                            <Tag
                                v-else
                                class="mr-2"
                                severity="warning"
                                value="Warning"
                                >Pendiente</Tag
                            >
                        </template>
                    </Column>
                    <Column :bodyStyle="{ 'text-align': 'center' }">
                        <template #body="slotProps">
                            <Button
                                v-if="slotProps.data.estado == 'Pagado'"
                                disabled
                                type="button"
                                class="p-button-sm p-button-warning"
                                label="Pagado"
                                icon="pi pi-flag-fill"
                            ></Button>
                            <Button
                                v-else
                                type="button"
                                class="p-button-sm p-button-success"
                                label="Pagar"
                                icon="pi pi-dollar"
                                @click="openModalPagoCuota(slotProps.data)"
                            ></Button>
                        </template>
                    </Column>
                </DataTable>
            </Dialog>
        </div>

        <div class="div-pagar-cuota">
            <Dialog
                header="Pagar Cuota"
                :visible.sync="displayPagarCuota"
                :modal="true"
                position="center"
                :contentStyle="{ overflow: 'visible' }"
                :containerStyle="{ width: '30vw' }"
                @hide="cancelarPagoCuota"
            >
                <div class="p-fluid p-formgrid p-grid">
                    <div class="p-field p-col-12 p-md-6">
                        <label for="tipoPago">Tipo de Pago</label>
                        <Dropdown
                            class="p-inputtext-sm"
                            v-model="form.tipo_pago_cuota"
                            :options="lista_tipo_pago_cuotas"
                            optionLabel="nombre"
                            placeholder="Selecciona el tipo de pago"
                            :class="{'p-invalid': submitted && v$.form.tipo_pago_cuota.$invalid}"
                        />
                        <small
                            class="p-error"
                            v-if="(submitted && v$.form.tipo_pago_cuota.required.$invalid)">
                            <strong>Tipo Pago es obligatorio.</strong>
                        </small>
                    </div>

                    <div class="p-field p-col-12 p-md-6">
                        <label for="fechaPago">Fecha de Pago</label>
                        <InputText
                            class="p-inputtext-sm"
                            id="fechaPago"
                            v-model="form.fecha_pago"
                            disabled
                        />
                    </div>
                </div>

                <div class="p-fluid p-formgrid p-grid">
                    <div class="p-field p-col-12 p-md-6">
                        <label for="cuotaActual">Cuota Actual</label>
                        <InputNumber
                            class="p-inputtext-sm"
                            id="cuotaActual"
                            v-model="form.cuota_actual"
                            prefix="Bs "
                            disabled
                        />
                    </div>

                    <div class="p-field p-col-12 p-md-6">
                        <label for="montoPagar">Monto a Pagar</label>
                        <InputNumber
                            placeholder="Bs 0"
                            class="p-inputtext-sm"
                            id="montoPagar"
                            v-model="form.pago_actual"
                            :min="0"
                            mode="decimal"
                            prefix="Bs "
                            :maxFractionDigits="2"
                            :class="{'p-invalid': submitted && v$.form.pago_actual.$invalid}"
                        />
                        <small
                            class="p-error"
                            v-if="(submitted && v$.form.pago_actual.required.$invalid)">
                            <strong>Cuota actual es obligatorio.</strong>
                        </small>
                        <small
                            class="p-error"
                            v-if="(submitted && v$.form.pago_actual.minValueValue.$invalid)">
                            <strong>No puede ser 0.</strong>
                        </small>
                    </div>
                </div>

                <template #footer>
                    <Button
                        label="Cancelar"
                        icon="pi pi-times"
                        class="p-button-sm p-button-danger"
                        @click="cancelarPagoCuota"
                    />
                    <Button
                        label="Abonar"
                        icon="pi pi-check-square"
                        class="p-button-sm p-button-help"
                        autofocus
                        @click="pagarCuota"
                    />
                </template>
            </Dialog>
        </div>

        <div class="div-detalles">
            <Dialog
                header="Detalles de la Compra"
                :visible.sync="displayPagarCuota"
                :modal="true"
                position="center"
                :contentStyle="{ overflow: 'visible' }"
                :containerStyle="{ width: '30vw' }"
                @hide="cancelarPagoCuota"
            >

                <template #footer>
                    <Button
                        label="Cancelar"
                        icon="pi pi-times"
                        class="p-button-sm p-button-danger"
                        @click="openModalMostrarDetalle"
                    />
                    <Button
                        label="Abonar"
                        icon="pi pi-check-square"
                        class="p-button-sm p-button-help"
                        autofocus
                        @click="openModalMostrarDetalle"
                    />
                </template>
            </Dialog>
        </div>
    </main>
</template>

<script src="./ComprasCredito.js"></script>

<style scoped src="./ComprasCredito.css" ></style>
