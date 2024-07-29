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
                :rows="8"
                dataKey="id"
                :rowHover="true"
                responsiveLayout="scroll"
                showGridlines
                :scrollable="true"
                scrollHeight="77vh"
                tableStyle="height:77vh"
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

                <Column header="Acciones" :styles="{'max-width':'7%'}">
                    <template #body="slotProps">
                        <div style="width: 100%; display: flex; justify-content: space-between; align-items: center;">
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
                        </div>
                    </template>
                </Column>
                <Column field="proveedor" header="Proveedor" :styles="{'max-width':'10%'}"></Column>
                <Column field="usuario" header="Comprador" :styles="{'max-width':'10%'}"></Column>
                <Column field="fecha_hora" header="Fecha" :styles="{'max-width':'8%'}"></Column>
                <Column field="nombre_almacen" header="Almacen" :styles="{'max-width':'10%'}"></Column>
                <Column :styles="{'max-width':'10%'}">
                    <template #header>
                        Comprobante
                    </template>
                    <template #body="slotProps">
                        <div style="text-align: center; width: 100%;">
                            {{ slotProps.data.tipo_comprobante }}
                        </div>
                    </template>
                </Column>
                <Column :styles="{'max-width':'10%'}">
                    <template #header>
                        Compra Total
                    </template>
                    <template #body="slotProps">
                        <div style="text-align: center; width: 100%;">
                            {{ slotProps.data.total_compra }}
                        </div>
                    </template>
                </Column>
                <Column :styles="{'max-width':'9%'}">
                    <template #header>
                        Cuota Inicial
                    </template>
                    <template #body="slotProps">
                        <div style="text-align: center; width: 100%;">
                            {{ slotProps.data.cuota_inicial }}
                        </div>
                    </template>
                </Column>
                <Column :styles="{'max-width':'9%'}">
                    <template #header>
                        Deuda Restante
                    </template>
                    <template #body="slotProps">
                        <div style="text-align: center; width: 100%;">
                            {{ slotProps.data.total_restante }}
                        </div>
                    </template>
                </Column>
                <Column :styles="{'max-width':'9%'}">
                    <template #header>
                        Num. Cuotas
                    </template>
                    <template #body="slotProps">
                        <div style="text-align: center; width: 100%;">
                            {{ slotProps.data.num_cuotas }}
                        </div>
                    </template>
                </Column>
                <Column header="Estado" :bodyStyle="{'text-align': 'center'}" :styles="{'max-width':'8%'}">
                    <template #body="slotProps">
                        <div style="text-align: center; width: 100%;">
                            <Tag
                                v-if="slotProps.data.estado_compra == 'Pagado'"
                                class="mr-2"
                                severity="success"
                                :value="slotProps.data.estado_compra"
                            />
                            <Tag
                                v-else
                                class="mr-2"
                                severity="warning"
                                :value="slotProps.data.estado_compra"
                            />
                        </div>
                    </template>
                </Column>

                <template #empty>
                    <div class="imagen-tabla-vacia">
                        <img src="img/iconos/colateral.png" alt="Lista de creditos" class="product-image" />
                        <h5 style="margin-top: 15px;"><strong>Sin compras de credito ...</strong></h5>
                    </div>
                </template>
            </DataTable>
        </Panel>

        <div class="div-lista-cuota">
            <Dialog
                header="Cuotas Pendientes"
                :visible.sync="displayListaCuotas"
                :modal="true"
                :position="getDialogPosition()"
                @hide="closeModalListaCuotas"
            >
                <DataTable
                    :value="array_cuotas"
                    :paginator="true"
                    :rows="5"
                    class="p-datatable-sm"
                    dataKey="id"
                    :rowHover="true"
                    responsiveLayout="scroll"
                    showGridlines
                >
                    <Column
                        field="fecha_pago"
                        header="Fecha Pago"
                    ></Column>
                    <Column
                        field="precio_cuota"
                        header="Precio Cuota"
                    ></Column>
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
                    ></Column>
                    <Column field="tipo_pago_cuota" header="Tipo Pago" ></Column>
                    <Column header="Estado" >
                        <template #body="slotProps">
                            <div style="text-align: center; width: 100%;">
                                <Tag
                                    v-if="slotProps.data.estado == 'Pagado'"
                                    class="mr-2"
                                    severity="success"
                                    >Pagado</Tag
                                >
                                <Tag
                                    v-else
                                    class="mr-2"
                                    severity="warning"
                                    >Pendiente</Tag
                                >
                            </div>
                        </template>
                    </Column>
                    <Column >
                        <template #body="slotProps">
                            <div style="text-align: center; width: 100%;">
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
                            </div>
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
                :containerStyle="{ width: '35vw' }"
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
                            :class="{'p-invalid': (submitted && v$.form.pago_actual.$invalid) || (form.cuota_actual != form.pago_actual)}"
                        />
                        <small
                            class="p-error"
                            v-if="(submitted && v$.form.pago_actual.required.$invalid)">
                            <strong>Cuota actual es obligatorio.</strong>
                        </small>
                        <small
                            class="p-error"
                            v-if="(form.cuota_actual != form.pago_actual)">
                            <strong>Debe ser igual a la Cuota Actual</strong>
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
                :visible.sync="displayMostrarDetalles"
                :modal="true"
                position="center"
                :containerStyle="{ width: '45vw' }"
            >

                <div class="card">
                    <div class="p-fluid p-formgrid p-grid">
                        <div class="p-col p-md-6">
                            <label for="nombreProveedor"><strong>Proveedor</strong></label>
                            <p>{{ cabecera.nombre_proveedor }}</p>
                        </div>
                        <div class="p-col p-md-6">
                            <label for="nombreAlmacen"><strong>Almacen</strong></label>
                            <p>{{ cabecera.nombre_almacen }}</p>
                        </div>

                        <div class="p-col p-md-6">
                            <label for="nombreProveedor"><strong>Serie Comprobante</strong></label>
                            <p>{{ cabecera.serie_comprobante }}</p>
                        </div>
                        <div class="p-col p-md-6">
                            <label for="nombreAlmacen"><strong>Numero Comprobante</strong></label>
                            <p>{{ cabecera.num_comprobante }}</p>
                        </div>

                        <div class="p-col p-md-6">
                            <label for="nombreProveedor"><strong>Comprobante</strong></label>
                            <p>{{ cabecera.tipo_comprobante }}</p>
                        </div>
                        <div class="p-col p-md-6">
                            <label for="nombreAlmacen"><strong>Fecha / Hora</strong></label>
                            <p>{{ cabecera.fecha_hora }}</p>
                        </div>

                        <div class="p-col p-md-6">
                            <label for="nombreProveedor"><strong>Total</strong></label>
                            <p style="color: red;"><strong>Bs. {{ cabecera.total }}</strong></p>
                        </div>
                        <div class="p-col p-md-6">
                            <label for="nombreAlmacen"><strong>Descuento Global</strong></label>
                            <p>{{ cabecera.descuento_global }} %</p>
                        </div>

                        <div class="p-col p-md-6">
                            <label for="nombreProveedor"><strong>Cuota Inicial</strong></label>
                            <p>Bs. {{ cabecera.cuota_inicial }}</p>
                        </div>
                        <div class="p-col p-md-6">
                            <label for="nombreAlmacen"><strong>Tipo Pago</strong></label>
                            <p>{{ cabecera.tipo_pago_inicial }}</p>
                        </div>
                    </div>
                </div>

                <DataTable
                    :value="array_detalles_ingreso"
                    :paginator="false"
                    class="p-datatable-sm"
                    dataKey="id"
                    :rowHover="true"
                    responsiveLayout="scroll"
                    showGridlines
                    :scrollable="true"
                    scrollHeight="25vh"
                    tableStyle="height:25vh"
                >
                    <Column :styles="{'max-width':'55%'}"
                        field="nombre"
                        header="Nombre"
                    />
                    <Column :styles="{'max-width':'15%'}"
                        field="cantidad"
                        header="Cantidad"
                    />
                    <Column :styles="{'max-width':'15%'}"
                        field="precio"
                        header="Precio"
                    />
                    <Column :styles="{'max-width':'15%'}">
                        <template #header>
                            Descuento
                        </template>
                        <template #body="slotProps">
                            <div style="text-align: center; width: 100%;">
                                {{ slotProps.data.descuento }} %
                            </div>
                        </template>
                    </Column>

                </DataTable>

                <template #footer>
                    <Button
                        label="Cancelar"
                        icon="pi pi-times"
                        class="p-button-sm p-button-danger"
                        @click="closeModalMostrarDetalle"
                        autofocus
                    />
                </template>
            </Dialog>
        </div>
    </main>
</template>

<script src="./ComprasCredito.js"></script>

<style scoped src="./ComprasCredito.css" ></style>
