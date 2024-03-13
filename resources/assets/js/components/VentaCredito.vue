<template>
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a class="text-decoration-none" href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Ventas a crédito
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-4" v-model="criterio">
                                    <option value="nombre_cliente">Nombre del cliente</option>
                                    <option value="nombre_vendedor">Nombre del vendedor</option>
                                </select>
                                <input type="text" v-model="buscar"
                                    @keyup.enter="obtenerCreditos(1, buscar, criterio, filtroAvanzado)" class="form-control"
                                    placeholder="Texto a buscar">
                                <button type="submit" @click="obtenerCreditos(1, buscar, criterio, filtroAvanzado)"
                                    class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <select id="filtroAvanzado" class="form-control" v-model="filtroAvanzado"
                                @change="obtenerCreditos(1, buscar, criterio, filtroAvanzado)">
                                <option value="">
                                    Todos</option>
                                <option value="pagos_atrasados">Pagos atrasados</option>
                                <option value="pagos_hoy">Pagos para hoy</option>
                                <option value="pagos_completados">Pagos completados</option>
                                <option value="pagos_cercanos">Pagos próximos</option>
                            </select>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Acciones</th>
                                    <th>Cliente</th>
                                    <th>Vendedor</th>
                                    <th>Total de la venta</th>
                                    <th>Monto pendiente</th>
                                    <th>Pagadas</th>
                                    <th>Proximo pago</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="credito in arrayCreditos" :key="credito.id">
                                    <td>
                                        <icon-button v-if="credito.estado != 'Completado'" icon="fa fa-plus"
                                            label="Cobrar cuotas" size="small" color="success"
                                            @click="abrirDetalle(credito)" />
                                        <icon-button v-else icon="icon-eye" label="Ver cuotas" size="small"
                                            color="secondary" @click="abrirDetalle(credito)" />
                                    </td>
                                    <td v-text="credito.nombre_cliente"></td>
                                    <td v-text="credito.nombre_vendedor"></td>
                                    <td>{{ (credito.totalVenta * monedaVenta[0]).toFixed(2) }} {{ monedaVenta[1] }}</td>
                                    <td>{{ (credito.total * monedaVenta[0]).toFixed(2) }} {{ monedaVenta[1] }}</td>
                                    <td>{{ calcularCuotasPagadas(credito.totalVenta, credito.total, credito.numero_cuotas)
                                    }}/{{ credito.numero_cuotas }}</td>
                                    <td>{{ credito.estado == "Completado" ? "Sin Próximos Pagos" :
                                        formatFecha(credito.proximo_pago) }}</td>
                                    <td>
                                        <i class="fa fa-circle"
                                            :style="{ color: getColorForEstado(credito.estado, credito.proximo_pago) }"></i>&nbsp;
                                        {{ new Date(credito.proximo_pago) < new Date() ? 'Atrasado' : credito.estado }}
                                            </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#"
                                    @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page"
                                :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)"
                                    v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#"
                                    @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="modal " tabindex="-1" :class="{ 'mostrar': modalDetalle && modal == 0 }" role="dialog"
            aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Detalle de venta a crédito</h4>
                        <button type="button" class="close" @click="cerrarModalDetalle()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="arraySeleccionado">
                        <dl class="row">
                            <dt class="col-sm-3">Nombre del cliente</dt>
                            <dd class="col-sm-9">{{ arraySeleccionado.nombre_cliente }}</dd>
                            <dt class="col-sm-3">Nombre del vendedor</dt>
                            <dd class="col-sm-9">{{ arraySeleccionado.nombre_vendedor }}</dd>
                            <dt class="col-sm-3">Comprobante</dt>
                            <dd class="col-sm-9">
                                <p>{{ arraySeleccionado.tipo_comprobante }} - {{ arraySeleccionado.num_comprobante }}</p>
                            </dd>
                            <dt class="col-sm-3">Próximo pago</dt>
                            <dd class="col-sm-9">{{ formatFecha(arraySeleccionado.proximo_pago) }}</dd>
                            <dt class="col-sm-3">Total de la venta</dt>
                            <dd class="col-sm-3">{{ (arraySeleccionado.totalVenta * monedaVenta[0]).toFixed(2) }} {{ monedaVenta[1] }}
                            </dd>
                            <dt class="col-sm-3">Monto pendiente</dt>
                            <dd class="col-sm-3">{{ (arraySeleccionado.total * monedaVenta[0]).toFixed(2) }} {{ monedaVenta[1] }}</dd>
                        </dl>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha Pago</th>
                                            <th>Precio Cuota</th>
                                            <th>Cobrador</th>
                                            <th>Saldo</th>
                                            <th>Fecha Cancelado</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(cuota, index) in arrayCuotas" :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ new Date(cuota.fecha_pago).toLocaleDateString('es-ES') }}</td>
                                            <td>
                                                {{ (cuota.precio_cuota * monedaVenta[0]).toFixed(2) }}
                                                {{ monedaVenta[1] }}
                                            </td>
                                            <td>
                                                {{ (cuota.nombre_cobrador) }}
                                            </td>
                                            <td>
                                                {{ (cuota.saldo_restante * monedaVenta[0]).toFixed(2) }}
                                                {{ monedaVenta[1] }}
                                            </td>
                                            <td>{{ cuota.fecha_cancelado ? new Date(cuota.fecha_cancelado
                                            ).toLocaleDateString('es-ES') : "Sin fecha" }}</td>
                                            <td>
                                                <i class="fa fa-circle"
                                                    :style="{ color: getColorForEstado(cuota.estado, cuota.fecha_pago) }"></i>&nbsp;
                                                {{ new Date(cuota.fecha_pago) < new Date() && cuota.estado != "Pagado"
                                                    ? 'Atrasado' : cuota.estado }} </td>
                                            <td>

                                                <icon-button v-if="cuota.estado != 'Pagado'" icon="fa fa-inbox" size="small"
                                                    color="success" @click="abrirModal(cuota, index)" />
                                                <icon-button v-else icon="fa fa-check" size="small" color="success" />
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <AbonarCuota v-if="cuotaSeleccionada" :cuota="cuotaSeleccionada" :modal="modal" :moneda="monedaVenta"
                :ventaCredito="arraySeleccionado" :arrayCuotas="arrayCuotas" @cerrar-modal="cerrarModal" />
        </div>
    </main>
</template>

<script>
import VueBarcode from 'vue-barcode';

export default {
    data() {
        return {
            filtroAvanzado: "",
            monedaVenta: [],
            ultimaCuota: null,
            modalDetalle: 0,
            arraySeleccionado: null,
            cuotaSeleccionada: null,
            arrayCuotas: [],
            arrayCreditos: [],
            errores: {},
            modal: 0,
            tituloModal: '',
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,
            },
            offset: 3,
            criterio: 'nombre_cliente',
            buscar: ''
        }
    },
    computed: {
        isActived: function () {
            return this.pagination.current_page;
        },
        pagesNumber: function () {
            if (!this.pagination.to) {
                return [];
            }
            let from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }
            let to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            let pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
    },
    methods: {
        calcularCuotasPagadas(total, montoRestante, numeroCuotas) {
            const tamanoCuota = total / numeroCuotas;
            const montoPagado = total - montoRestante;
            const numeroCuotasPagadas = montoPagado / tamanoCuota;
            return Math.floor(numeroCuotasPagadas);
        },
        getColorForEstado(estado, fecha_final) {
            const fechaFinal = new Date(fecha_final) < new Date();
            if (fechaFinal && estado != "Pagado") {
                return '#ff0000';
            }
            switch (estado) {
                case 'Completado':
                    return '#5ebf5f';
                case 'Pagado':
                    return '#5ebf5f';
                case 'Inactivo':
                    return '#d76868';
                case 'Atrasado':
                    return '#ce4444';
                default:
                    return '#f9dda6';
            }
        },
        cerrarModalDetalle() {
            this.modalDetalle = 0;
            this.obtenerCreditos(1, this.buscar, this.criterio, this.filtroAvanzado)
        },
        abrirDetalle(credito) {
            this.arraySeleccionado = credito;
            this.obtenerCuotasCredito(credito.id);
            this.modalDetalle = 1;
        },
        async obtenerCuotasCredito(idCredito) {
            try {
                const response = await axios.post('/credito/cuotas', { id_credito: idCredito });
                this.arrayCuotas = response.data;
            } catch (error) {
                console.error('Error al obtener las cuotas de crédito:', error);
            }
        },
        async obtenerCreditos(page, buscar, criterio, filtroAvanzado) {
            try {
                const response = await axios.get('/credito', {
                    params: {
                        page: page,
                        buscar: buscar,
                        criterio: criterio,
                        filtro_avanzado: this.filtroAvanzado
                    }
                });
                this.arrayCreditos = response.data.creditos.data;
                this.pagination = response.data.pagination;
            } catch (error) {
                console.error('Error al obtener los créditos de venta:', error);
            }
        },


        formatFecha(fechaOriginal) {
            const fecha = new Date(fechaOriginal);
            const dia = fecha.getDate();
            const mes = fecha.getMonth() + 1;
            const anio = fecha.getFullYear();

            const diaFormateado = dia < 10 ? `0${dia}` : dia;
            const mesFormateado = mes < 10 ? `0${mes}` : mes;

            return `${diaFormateado}-${mesFormateado}-${anio}`;
        },
        cambiarPagina(page, buscar, criterio) {
            this.pagination.current_page = page;
            this.obtenerCreditos(page, buscar, criterio, filtroAvanzado);
        },
        cerrarModal() {
            this.modal = 0;
            this.obtenerCuotasCredito(this.arraySeleccionado.id);
        },
        abrirModal(cuota, index) {
            this.modal = 1;
            this.cuotaSeleccionada = cuota;
        },
        toastSuccess(mensaje) {
            this.$toasted.show(`<div style="height: 60px;font-size:16px;"><br>` + mensaje + `.<br></div>`, {
                type: "success",
                position: "bottom-right",
                duration: 4000
            });
        },
        toastError(mensaje) {
            this.$toasted.show(`<div style="height: 60px;font-size:16px;"><br>` + mensaje + `<br></div>`, {
                type: "error",
                position: "bottom-right",
                duration: 4000
            });
        },
        async datosConfiguracion() {
            try {
                const response = await axios.get('/configuracion');
                const respuesta = response.data;
                this.monedaVenta = [respuesta.configuracionTrabajo.valor_moneda_venta, respuesta.configuracionTrabajo.simbolo_moneda_venta];
            } catch (error) {
                console.log(error);
            }
        },
    },
    mounted() {
        this.obtenerCreditos(1, this.buscar, this.criterio, this.filtroAvanzado);
        this.datosConfiguracion();
    }
}
</script>
