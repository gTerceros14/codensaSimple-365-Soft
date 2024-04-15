<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a class="text-decoration-none" href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div v-show="listado!=0" class="card">
                <div  class="card-header">
                    <i class="fa fa-align-justify"></i> Ventas
                    <button type="button" @click="mostrarDetalle" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <button v-if="permitirDevolucion === 1" type="button" @click="listado = 3"
                        class="btn btn-secondary">Devoluciones
                    </button>
                    <span class="badge bg-secondary" id="comunicacionSiat" style="color: white;">Desconectado</span>
                    <span class="badge bg-secondary" id="cuis">CUIS: Inexistente</span>
                    <span class="badge bg-secondary" id="cufd">No existe cufd vigente</span>
                    <span class="badge bg-secondary" id="direccion" v-show="mostrarDireccion">No hay dirección
                        registrada</span>
                    <span class="badge bg-primary" id="cufdValor" v-show="mostrarCUFD">No hay CUFD</span>
                </div>
                <!-- Listado-->
                <template v-if="listado == 1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                        <option value="tipo_comprobante">Tipo Comprobante</option>
                                        <option value="num_comprobante">Número Comprobante</option>
                                        <option value="fecha_hora">Fecha-Hora</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup="listarVenta(1, buscar, criterio)"
                                        class="form-control" placeholder="Texto a buscar">
                                </div>
                            </div>
                        </div>  
                        <div class="spinner-container" v-if="mostrarSpinner">
                            <div class="spinner-message"><strong>EMITIENDO FACTURA...</strong></div>
                            <TileSpinner color="blue" />
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Vendedor</th>
                                        <th>Cliente</th>
                                        <th>Documento</th>
                                        <th>N° de Documento</th>
                                        <th>Fecha y Hora</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="venta in arrayVenta" :key="venta.id">
                                        <td class="d-flex align-items-center">
                                            <button type="button" @click="verVenta(venta.id)"
                                                class="btn btn-success btn-sm mr-1">
                                                <i class="icon-eye"></i>
                                            </button>
                                            <button type="button" @click="pdfVenta(venta.id)"
                                                class="btn btn-info btn-sm mr-1">
                                                <i class="icon-doc"></i>
                                            </button>
                                            <template v-if="venta.estado == 'Registrado'">
                                                <button type="button" class="btn btn-danger btn-sm mr-1"
                                                    @click="desactivarVenta(venta.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                            <template v-if="venta.idtipo_venta == 2 && venta.estado == 'Pendiente'">
                                                <button type="button" class="btn btn-primary btn-sm mr-1"
                                                    @click="abrirModalCuotas(venta.id)">
                                                    <i class="icon-plus"></i>
                                                </button>
                                            </template>
                                        </td>
                                        <td v-text="venta.usuario"></td>
                                        <td v-text="venta.razonSocial"></td>
                                        <td v-text="venta.documentoid"></td>
                                        <td v-text="venta.num_comprobante"></td>
                                        <td v-text="venta.fecha_hora"></td>
                                        <td>
                                            {{ ((venta.total) * parseFloat(monedaVenta[0])).toFixed(2) }} {{
                        monedaVenta[1]
                    }}

                                        </td>
                                        <td>
                                            <a @click="verificarFactura(venta.cuf, venta.numeroFactura)" target="_blank"
                                                class="btn btn-info"><i class="icon-note"></i></a>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary" type="button"
                                                @click="imprimirFactura(venta.id, venta.correo)"><i
                                                    class="icon-printer"></i></button>
                                            <button class="btn btn-danger" type="button"
                                                @click="anularFactura(venta.id, venta.cuf)"><i
                                                    class="icon-close"></i></button>
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
                </template>
                <!--Fin Listado-->
                <!-- Detalle-->
                
                <!-- Fin Detalle-->
                <!--Ver ingreso-->
                
            </div>
            <!-- HASTA AQUI DEVOLUCIONES -->
        </div>

        <template v-if="listado ==0"  >
             <registrarventa  @cerrar="ocultarDetalle"  >
                </registrarventa></template>
        
    </main>
</template>

<script>
import vSelect from 'vue-select';
import { TileSpinner } from 'vue-spinners';

export default {
    data() {
        return {
            clienteDeudas: 0,
            arrayCuotas: [],
            arraySeleccionado: [],
            cuotaSeleccionada: null,
            modalCuotas: 0,

            tipo_pago: '',
            criterioKit: 'nombre',
            buscarKit: '',

            mensajesKit: [],
            arrayArticulosKit: [],
            datosFormularioKit: [],
            modalDetalleKit: 0,
            arrayKit: [],

            arrayPreciosEspeciales: [],
            modalDetalle: 0,
            datosFormularioPE: [],
            arrayArticulosPE: [],


            arrayPromocion: [],
            unidadPaquete: 1,

            monedaVenta: [],
            permitirDevolucion: '',
            saldosNegativos: 1,
            venta_id: 0,
            idcliente: 0,
            usuarioAutenticado: null,
            puntoVentaAutenticado: null,
            cliente: '',
            email: '',
            nombreCliente: '',
            documento: '',
            tipo_documento: '',
            complemento_id: '',
            descuentoAdicional: 0.00,
            descuentoGiftCard: '',
            tipo_comprobante: 'FACTURA',
            serie_comprobante: '',
            last_comprobante: 0,
            num_comprob: "",
            impuesto: 0.18,
            total: 0.0,
            totalImpuesto: 0.0,
            totalParcial: 0.0,
            arrayVenta: [],
            arrayCliente: [],
            arrayDetalle: [],
            arrayProductos: [],
            listado: 1,
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            errorVenta: 0,
            errorMostrarMsjVenta: [],
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,
            },
            offset: 3,
            criterio: 'num_comprobante',
            buscar: '',
            criterioA: 'nombre',
            buscarA: '',
            arrayArticulo: [],
            arraySeleccionado: [],
            idarticulo: 0,
            codigo: '',
            articulo: '',
            medida: '',
            codigoClasificador: '',
            codigoProductoSin: '',
            precio: 0,
            unidad_envase: 0,
            cantidad: 1,
            paquni: '',
            precioBloqueado: false,
            descuento: 0,
            descuentoProducto: 0,
            sTotal: 0,
            stock: 0,
            valorMaximoDescuento: '',
            mostrarDireccion: true,
            mostrarCUFD: true,
            casosEspeciales: false,
            leyendaAl: '',
            codigoExcepcion: 0,
            mostrarSpinner: false,
            primer_precio_cuota: 0,
            numeroTarjeta: null,
            metodoPago: '',

            //almacenes
            arrayAlmacenes: [],
            idAlmacen: null,
            //-----PRECIOS- AUMENTE 3/OCTUBRE--------
            precioseleccionado: '',
            //precio : '',
            arrayPrecios: [],
            nombre_precio: '',
            precio_uno: '',
            precio_dos: '',
            precio_tres: '',
            precio_cuatro: '',
            //-----MODAL 2---
            modal2: 0,
            tituloModal2: '',
            tipoAccion2: '',
            //-----MODAL 3---
            modal3: 0,
            tituloModal3: '',
            tipoAccion3: '',
            //--nue☺8os datos--
            // fecha_credito : '',
            // proforma : '',
            // refe_pago : '',
            // orden_compra : '',
            // fecha_entrega : '',
            // checkboxHabilitado :'',
            // diasToAdd : null,
            //----CALCULADORA---
            //totalprue: 80,//PRUEBA PORQUE NO DA EL CALCULAR
            recibido: 0,
            efectivo: 0,
            cambio: 0,
            faltante: 0,
            //-------DEStE AQUI 13-OCTUBRE--
            idtipo_pago: '',
            idtipo_venta: '1',
            tiempo_diaz: '',
            numero_cuotas: '',
            cuotas: [],//---para almacenar las fechas
            estadocrevent: 'activo',
            primera_cuota: '',
            habilitarPrimeraCuota: false,
            tipoPago: 'EFECTIVO',
            tiposPago: {
                        EFECTIVO: 1,
                        TARJETA: 2,
                        QR: 3
                        },

        }
    },
    watch: {
        codigo(newValue) {
            if (newValue) {
                this.buscarArticulo();
            }
        }
    },
    components: {
        TileSpinner,
        vSelect
    },
    computed: {

        resultadoMultiplicacion() {
            if (this.arraySeleccionado) {
                return this.precioseleccionado * this.unidadPaquete * this.cantidad;
            }
        },

        totalCantidades() {
            return this.arrayArticulosKit.reduce((total, articulo) => {
                return total + parseInt(articulo.cantidad);
            }, 0);
        },


        isActived: function () {
            return this.pagination.current_page;
        },

        //Calcula los elementos de la paginación
        pagesNumber: function () {
            if (!this.pagination.to) {
                return [];
            }

            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }

            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }

            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;

        },

        calcularTotal: function () {
            var resultado = 0.0;
            for (var i = 0; i < this.arrayDetalle.length; i++) {
                resultado += ((this.arrayDetalle[i].precioseleccionado * this.arrayDetalle[i].cantidad) - ((this.arrayDetalle[i].precioseleccionado * this.arrayDetalle[i].cantidad) * this.arrayDetalle[i].descuento / 100))

            }
            resultado -= this.descuentoAdicional;
            //resultado -= this.descuentoGiftCard;
            return resultado;
        },

        calcularSubTotal: function () {
            var resultado = 0.0;
            for (var i = 0; i < this.arrayDetalle.length; i++) {
                resultado = resultado + ((this.arrayDetalle[i].precioseleccionado * this.arrayDetalle[i].cantidad) - (this.arrayDetalle[i].precioseleccionado * this.arrayDetalle[i].cantidad * this.arrayDetalle[i].descuento / 100))
            }
            return resultado;
        },

    },
    methods: {
        calcularPrecioUnitario(articulo) {
            // Lógica para calcular el precio unitario según el rango total de cantidades
            if (this.totalCantidades >= this.datosFormularioPE.rango_inicio_r1 && this.totalCantidades <= this.datosFormularioPE.rango_final_r1) {
                return this.datosFormularioPE.precio_r1;
            } else if (this.totalCantidades >= this.datosFormularioPE.rango_inicio_r2 && this.totalCantidades <= this.datosFormularioPE.rango_final_r2) {
                return this.datosFormularioPE.precio_r2;
            } else if (this.totalCantidades >= this.datosFormularioPE.rango_inicio_r3 && this.totalCantidades <= this.datosFormularioPE.rango_final_r3) {
                return this.datosFormularioPE.precio_r3;
            } else {
                // Precio predeterminado si no está en ningún rango
                return articulo.precio_costo_unid;
            }
        },
        getClassByCantidad(total) {
        if (total >= this.datosFormularioPE.rango_inicio_r1 && total <= this.datosFormularioPE.rango_final_r1) {
            return 'rango-1'; // clase para el rango 1
        } else if (total >= this.datosFormularioPE.rango_inicio_r2 && total <= this.datosFormularioPE.rango_final_r2) {
            return 'rango-2'; // clase para el rango 2
        } else if (total >= this.datosFormularioPE.rango_inicio_r3 && total <= this.datosFormularioPE.rango_final_r3) {
            return 'rango-3'; // clase para el rango 3
        } else {
            return ''; // clase por defecto si no se cumple ningún rango
        }
    },
        abrirTipoVenta() {
            if (this.idtipo_venta == 1) {
                this.modal2 = 1;
                this.cliente = this.nombreCliente;
                this.tipoAccion2 = 1;
                this.scrollToTop()
            } else {
                this.modal3 = 1;
                this.cliente = this.nombreCliente;
                this.scrollToTop();
                this.tituloModal3 = 'CREDITOS ' + this.cliente;
                this.tipoAccion3 = 1;
                this.idtipo_pago = this.tipo_pago;
            }


        },
        cerrarModalCuotas() {
            this.modalCuotas = 0;
            this.listarVenta(1, this.buscar, this.criterio);

        },
        abrirModalCuotas(idventa) {
            this.modalCuotas = 1;
            axios.get(`/credito/cuotas/venta/${idventa}`)
                .then(response => {
                    this.arrayCuotas = response.data.cuotasCredito;
                    this.arraySeleccionado = response.data.creditoVenta;
                    const cuotasPendientes = this.arrayCuotas.filter(cuota => cuota.estado === 'Pendiente');
                    this.cuotaSeleccionada = cuotasPendientes.find(cuota => cuota.estado === 'Pendiente');
                })
                .catch(error => {
                    console.error('Error al obtener crédito y cuotas:', error);
                });

        },
        GetValidateKit(idPromocion) {
            return axios.get('/kits/id', {
                params: {
                    idPromocion: idPromocion
                }
            })
                .then((response) => {
                    const datos = response.data;
                    this.arrayArticulosKit = datos.articulosPorPromocion.map(item => ({
                        ...item.articulo,
                        cantidad: item.cantidad
                    }));
                    this.mensajesKit = datos.mensajes;
                })
                .catch((error) => {
                    console.error(error);
                    throw error; 
                });
        },
        seleccionarTipoPago(tipo) {
            this.tipoPago = tipo;
            this.tituloModal2 = `TIPO DE PAGO : ${tipo}`;
            this.idtipo_pago = this.tiposPago[tipo];
        },

        agregarKit(kit) {
            if (new Date(kit.fecha_final) < new Date()) {
                swal({
                    type: 'error',
                    title: 'Error...',
                    text: 'Este kit ha expirado!',
                });
                return;
            }
            //   this.GetValidateKit(kit['id'])
            this.GetValidateKit(kit['id'])
                .then(() => {

                    if (this.mensajesKit.length == 0) {

                        const totalKit = this.arrayArticulosKit.reduce((total, producto) => {
                            return total + (producto.cantidad * producto.precio_costo_unid);
                        }, 0);
                        this.arrayArticulosKit.forEach(producto => {
                            producto.porcentaje = ((producto.cantidad * producto.precio_costo_unid) / totalKit) * 100;
                        });

                        this.arrayArticulosKit.forEach(producto => {
                            producto.nuevo_precio = (kit.precio * producto.porcentaje) / 100;
                        });
                        console.log("Estos son los articulos: ", this.arrayArticulosKit);
                        this.arrayArticulosKit.forEach(articulo => {
                            this.arrayDetalle.push({
                                idkit: kit['id'],
                                idarticulo: articulo.id,
                                articulo: articulo.nombre,
                                medida: "KIT",
                                unidad_envase: articulo.unidad_envase,
                                cantidad: articulo.cantidad,
                                cantidad_paquetes: articulo.unidad_envase * articulo.cantidad,
                                precio: articulo.nuevo_precio,
                                descuento: 0,
                                stock: articulo.stock,
                                precioseleccionado: articulo.precio_costo_unid
                            });
                            let actividadEconomica = 461021;

                            this.arrayProductos.push({

                                actividadEconomica: actividadEconomica,
                                codigoProductoSin: articulo.id,
                                codigoProducto: articulo.codigo,
                                descripcion: articulo.nombre,
                                cantidad: articulo.cantidad,
                                unidadMedida: 25,
                                precioUnitario: parseFloat(articulo.precio_costo_unid * this.monedaVenta[0]).toFixed(2),
                                montoDescuento: ((articulo.precio_costo_unid * articulo.cantidad) * this.monedaVenta[0] - articulo.nuevo_precio * this.monedaVenta[0]).toFixed(2),
                                subTotal: (parseFloat(articulo.nuevo_precio * this.monedaVenta[0])).toFixed(2),
                                numeroSerie: null,
                                numeroImei: null
                            });
                            this.cerrarModal()
                        });
                        // this.arrayDetalle.push({
                        //                 idarticulo: this.arraySeleccionado.id,
                        //                 articulo: this.arraySeleccionado.nombre,
                        //                 medida: this.arraySeleccionado.medida,
                        //                 unidad_envase: this.arraySeleccionado.unidad_envase,
                        //                 cantidad: this.cantidad * this.unidadPaquete,
                        //                 cantidad_paquetes: this.arraySeleccionado.unidad_envase,
                        //                 precio: precioArticulo,
                        //                 descuento: this.arrayPromocion && this.arrayPromocion.porcentaje !== undefined ? this.arrayPromocion.porcentaje : 0,
                        //                 stock: this.arraySeleccionado.saldo_stock,
                        //                 precioseleccionado: this.precioseleccionado

                        //             });

                    } else {

                        swal({
                            type: 'error',
                            title: 'Stock insuficiente',
                            text: this.mensajesKit.join("\n\n"),
                        })

                    }

                })
                .catch((error) => {
                    // Maneja el error aquí
                    console.error(error);
                });
        },

        agregarPE(kit) {
            console.log('esto:', kit);
            kit['articulos'] = this.arrayArticulosKit;
            kit['precio'] = kit['precio'] / parseFloat(this.monedaVenta[0])
            axios.put('/ofertasespeciales/actualizar', kit);
            
            this.modalDetalle = 0;
            if (new Date(kit.fecha_final) < new Date()) {
                swal({
                    type: 'error',
                    title: 'Error...',
                    text: 'Este kit ha expirado!',
                });
                return;
            }
            console.log("datos formulario agregar PE", kit)
            //   this.GetValidateKit(kit['id'])
            this.GetValidateKit(kit['id'])
                .then(() => {

                    if (this.mensajesKit.length == 0) {
                        
                        const totalKit = this.arrayArticulosKit.reduce((total, producto) => {
                            return total + (producto.cantidad * producto.precio_costo_unid);
                        }, 0);
                        this.arrayArticulosKit.forEach(producto => {
                            producto.porcentaje = ((producto.cantidad * producto.precio_costo_unid) / totalKit) * 100;
                        });
                        console.log("precio especial ", this.arrayArticulosKit);
                        this.arrayArticulosKit.forEach(producto => {
                            producto.nuevo_precio = (this.calcularPrecioUnitario(kit) * producto.porcentaje) / 100;
                        });
                        console.log("Estos son los articulos: ", this.arrayArticulosKit);
                        this.arrayArticulosKit.forEach(articulo => {
                            this.arrayDetalle.push({
                                idkit: kit['id'],
                                idarticulo: articulo.id,
                                articulo: articulo.nombre,
                                medida: "KIT",
                                unidad_envase: articulo.unidad_envase,
                                cantidad: articulo.cantidad,
                                cantidad_paquetes: articulo.unidad_envase * articulo.cantidad,
                                precio: articulo.nuevo_precio,
                                descuento: 0,
                                stock: articulo.stock,
                                precioseleccionado: this.calcularPrecioUnitario(articulo)
                            });
                            let actividadEconomica = 461021;

                            this.arrayProductos.push({

                                actividadEconomica: actividadEconomica,
                                codigoProductoSin: articulo.id,
                                codigoProducto: articulo.codigo,
                                descripcion: articulo.nombre,
                                cantidad: articulo.cantidad,
                                unidadMedida: 25,
                                precioUnitario: parseFloat(this.calcularPrecioUnitario(articulo) * this.monedaVenta[0]).toFixed(2),
                                montoDescuento: ((articulo.precio_costo_unid * articulo.cantidad) * this.monedaVenta[0] - articulo.nuevo_precio * this.monedaVenta[0]).toFixed(2),
                                subTotal: (parseFloat(articulo.nuevo_precio * this.monedaVenta[0])).toFixed(2),
                                numeroSerie: null,
                                numeroImei: null
                            });
                            this.cerrarModal()
                        });
                    } else {

                        swal({
                            type: 'error',
                            title: 'Stock insuficiente',
                            text: this.mensajesKit.join("\n\n"),
                        })
                    }
                })
                .catch((error) => {
                    // Maneja el error aquí
                    console.error(error);
                });
        },
        

        abrirModalDetallesKit(data) {
            this.arrayArticulosSeleccionados = [];

            this.modalDetalleKit = 1;
            this.datosFormularioKit = {
                id: data['id'],
                nombre: data['nombre'],
                porcentaje: data['porcentaje'],
                codigo: data['codigo'],

                fecha_final: (new Date(data['fecha_final'])).toISOString().split('T')[0],
                tipo_promocion: data['tipo_promocion'],
                estado: data['estado'],
                precio: data['precio'],

            };
            this.obtenerDatosKit(data['id'])
        },

        abrirModalDetalles(data) {
            this.arrayArticulosSeleccionados = [];

            this.modalDetalle = 1;
            this.datosFormularioPE = {
                id: data['id'],
                nombre: data['nombre'],
                precio_r1: data['precio_r1'],
                rango_inicio_r1: data['rango_inicio_r1'],
                rango_final_r1: data['rango_final_r1'],
                precio_r2: data['precio_r2'],
                rango_inicio_r2: data['rango_inicio_r2'],
                rango_final_r2: data['rango_final_r2'],
                precio_r3: data['precio_r3'],
                rango_inicio_r3: data['rango_inicio_r3'],
                rango_final_r3: data['rango_final_r3'],

                fecha_final: (new Date(data['fecha_final'])).toISOString().split('T')[0],
                tipo_promocion: data['tipo_promocion'],
                estado: data['estado'],

            };
            this.obtenerDatosKit(data['id']),
            console.log(this.datosFormularioPE);
        },
        

        obtenerDatosKit(idPromocion) {
            return axios.get('/ofertas/id', {
                params: {
                    idPromocion: idPromocion
                }
            })
                .then((response) => {
                    const datos = response.data.articulosPorPromocion;
                    this.arrayArticulosKit = datos.map(item => ({
                        ...item.articulo,
                        cantidad: item.cantidad
                    }));
                })
                .catch((error) => {
                    console.error(error);
                    throw error; // Re-lanza el error para que pueda ser manejado en agregarKit
                });
        },


        getColorForEstado(estado, fecha_final) {
            const fechaFinal = new Date(fecha_final) < new Date();

            if (fechaFinal) {
                return '#ff0000';
            }
            switch (estado) {
                case 'Activo':
                    return '#5ebf5f';
                case 'Inactivo':
                    return '#d76868';
                case 'Agotado':
                    return '#ce4444';
                default:
                    return '#f9dda6';
            }
        },

        listarKits(page, buscar, criterio) {
            let me = this;
            let url = '/kits';

            axios.get(url, {
                params: {
                    page: page,
                    buscar: buscar,
                    criterio: criterio
                }
            }).then(function (response) {
                let respuesta = response.data;
                me.arrayKit = response.data.ofertas.data;
                // me.pagination = respuesta.pagination;
            }).catch(function (error) {
                console.log(error);
            });
        },

        listarOfertaEspecial(page, buscar, criterio) {
        let me = this;
        let url = '/ofertasespeciales';

        axios.get(url, {
            params: {
            page: page,
            buscar: buscar,
            criterio: criterio
            }
        }).then(function (response) {
            let respuesta = response.data;
            me.arrayPreciosEspeciales = response.data.ofertas.data;
            me.pagination = respuesta.pagination;
        }).catch(function (error) {
            console.log(error);
        });
        },

        scrollToSection() {
            $('html, body').animate({
                scrollTop: $('#seccionObjetivo').offset().top
            }, 50);
        },
        scrollToTop() {
            $('html, body').animate({
                scrollTop: 0
            }, 50);
        },
        calcularPrecioConDescuento(precioOriginal, porcentajeDescuento) {
            /*const descuento = precioOriginal * (porcentajeDescuento / 100);
            const precioConDescuento = precioOriginal - descuento;
            return precioConDescuento;*/
            const descuento = this.precioseleccionado * (this.descuentoProducto / 100);
            const precioConDescuento = this.precioseleccionado - descuento;
            const precioFinal = precioConDescuento * this.unidadPaquete * this.cantidad;
            return precioFinal;
        },
        calcularDiasRestantes(fechaFinal) {
            const fechaActual = new Date();
            const fechaObjetivo = new Date(fechaFinal);
            const diferenciaEnMilisegundos = fechaObjetivo - fechaActual;
            const diasRestantes = Math.ceil(diferenciaEnMilisegundos / (1000 * 60 * 60 * 24));
            return diasRestantes;
        },
        actualizarDetalle(index) {
            this.arrayDetalle[index].total = (this.arrayDetalle[index].precioseleccionado * this.arrayDetalle[index].cantidad).toFixed(2);
        },
        actualizarDetalleDescuento(index) {
            this.calcularTotal(index);
        },
        validarDescuentoAdicional() {
            if (this.descuentoAdicional >= this.totalParcial) {
                this.descuentoAdicional = 0;
                alert("El descuento adicional no puede ser mayor o igual al total.");
            }
        }, 

        habilitarNombreCliente() {
            if (this.casosEspeciales) {
                this.$refs.nombreRef.removeAttribute("readonly");
                this.documento = "99001";
                this.idcliente = "2";
                this.tipo_documento = "5"; 
            } else {
                this.$refs.nombreRef.setAttribute("readonly", true);
                this.documento = "";
                this.idcliente = "";
                this.tipo_documento = "";
            }
        },
        validarDescuentoGiftCard() {

            if (this.descuentoGiftCard >= this.calcularTotal) {
                this.descuentoGiftCard = 0;
                alert("El descuento Gift Card no puede ser mayor o igual al total.");
            }
        },
        buscarPromocion(idArticulo) {
            // Supongamos que el ID del artículo es 1, ajusta según tus necesidades

            axios.get(`/promocion/id?idArticulo=${idArticulo}`)
                .then(response => {
                    this.arrayPromocion = response.data.promocion
                })
                .catch(error => {
                    // Maneja los errores aquí
                    console.error('Error:', error);
                });
        },

        atajoButton: function (event) {
            if (event.shiftKey && event.keyCode === 81) {
                event.preventDefault();
                this.$refs.impuestoRef.focus();
            }
            if (event.shiftKey && event.keyCode === 87) {
                event.preventDefault();
                this.$refs.serieComprobanteRef.focus();
            }
            if (event.shiftKey && event.keyCode === 69) {
                event.preventDefault();
                this.$refs.numeroComprobanteRef.focus();
            }
            if (event.shiftKey && event.keyCode === 82) {
                event.preventDefault();
                this.$refs.articuloRef.focus();
            }
            if (event.shiftKey && event.keyCode === 84) {
                event.preventDefault();
                this.$refs.precioRef.focus();
            }
            if (event.shiftKey && event.keyCode === 89) {
                event.preventDefault();
                this.$refs.cantidadRef.focus();
            }
            if (event.shiftKey && event.keyCode === 65) {
                event.preventDefault();
                this.$refs.cantidad_paquetesRef.focus();
            }
            if (event.shiftKey && event.keyCode === 85) {
                event.preventDefault();
                this.$refs.descuentoRef.focus();
            }
        },

        verificarComunicacion() {
            axios.post('/venta/verificarComunicacion')
                .then(function (response) {
                    if (response.data.RespuestaComunicacion.transaccion === true) {
                        document.getElementById("comunicacionSiat").innerHTML = response.data.RespuestaComunicacion.mensajesList.descripcion;
                        document.getElementById("comunicacionSiat").className = "badge bg-success";
                        // Actualiza el valor de scuis
                        //this.scuis = response.data.scuis;
                    } else {
                        document.getElementById("comunicacionSiat").innerHTML = "Desconectado";
                        document.getElementById("comunicacionSiat").className = "badge bg-secondary";
                        // Actualiza el valor de scuis
                        //this.scuis = "Inexistente";
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        cuis() {
            axios.post('/venta/cuis')
                .then(function (response) {
                    if (response.data.RespuestaCuis.transaccion === false) {
                        document.getElementById("cuis").innerHTML = "CUIS: " + response.data.RespuestaCuis.codigo;
                        document.getElementById("cuis").className = "badge bg-primary";
                    } else {
                        document.getElementById("cuis").innerHTML = "CUIS: Inexistente";
                        document.getElementById("cuis").className = "badge bg-secondary";
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        cufd() {
            axios.post('/venta/cufd')
                .then(function (response) {
                    if (response.data.transaccion != false) {
                        document.getElementById("cufd").innerHTML = "CUFD vigente: " + response.data.fechaVigencia.substring(0, 16);
                        document.getElementById("direccion").innerHTML = response.data.direccion;
                        document.getElementById("cufdValor").innerHTML = response.data.codigo;
                        document.getElementById("cufd").className = "badge bg-info";
                    } else {
                        document.getElementById("cufd").innerHTML = "No existe CUFD vigente";
                        document.getElementById("cufd").className = "badge bg-secondary";
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        nextNumber() {
            if (!this.num_comprob || this.num_comprob === "") {
                this.last_comprobante++;
                // Completa con ceros a la izquierda hasta alcanzar 5 dígitos
                this.num_comprob = this.last_comprobante.toString().padStart(5, "0");
            }
        },


        listarVenta(page, buscar, criterio) {
            let me = this;
            var url = '/venta?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                console.log(respuesta)
                me.arrayVenta = respuesta.ventas.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        selectCliente(numero) {
            let me = this;
            var url = '/cliente/selectClientePorNumero?numero=' + numero;
            axios.get(url).then(function (response) {
                let respuesta  = response.data;
                q: numero;
                me.arrayCliente = respuesta.clientes;
                console.log(me.arrayCliente);
            }).catch(function (error) {
                console.log(error);
            });
        },

        /*selectCliente2(search, loading) {
            let me = this;
            loading(true)
            var url = '/cliente/selectCliente?filtro=' + search;
            axios.get(url).then(function (response) {
                //console.log(response.clientes);
                let respuesta = response.data;
                q: search
                me.arrayCliente = respuesta.clientes;
                console.log(me.arrayCliente);
                loading(false)
            })
                .catch(function (error) {
                    console.log(error);
                });
        },*/

        getDatosCliente(val1) {
            let me = this;
            me.loading = true;
            me.idcliente = val1.id;
            //console.log(val1);
            this.email = val1.email;
            this.nombreCliente = val1.nombre;
            this.documento = val1.num_documento;
            this.tipo_documento = val1.tipo_documento;
            this.complemento_id = val1.complemento_id;
            this.clienteDeudas = val1.cantidad_creditos;
        },

        buscarArticulo() {
            clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                let me = this;
                var url = '/articulo/buscarArticuloVenta?filtro=' + me.codigo;

                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    me.arraySeleccionado = respuesta.articulos[0];
                    console.log(me.arraySeleccionado)
                    // if (me.arraySeleccionado.length > 0) {
                    //     me.fecha_vencimiento = me.arraySeleccionado[0]['fecha_vencimiento'];
                    // }
                    // const fechaActual = new Date();
                    // const año = fechaActual.getFullYear();
                    // let mes = fechaActual.getMonth() + 1;
                    // mes = mes < 10 ? '0' + mes : mes;
                    // let dia = fechaActual.getDate();
                    // dia = dia < 10 ? '0' + dia : dia;
                    // const fechaVencimiento = me.fecha_vencimiento;
                    // if (fechaVencimiento <= `${año}-${mes}-${dia}`) {
                    //     console.log('VENCIDO', fechaVencimiento);
                    //     me.arraySeleccionado.length = 0;
                    //     me.precioseleccionado = null;
                    //     me.advertenciaFechaVencimiento();
                    //     me.codigo = null;
                    // } else {
                    //     if (me.arraySeleccionado.length > 0) {
                    //         me.articulo = me.arraySeleccionado[0]['nombre'];
                    //         me.idarticulo = me.arraySeleccionado[0]['id'];
                    //         me.codigo = me.arraySeleccionado[0]['codigo'];
                    //         me.precio = me.arraySeleccionado[0]['precio_venta'];
                    //         me.stock = me.arraySeleccionado[0]['saldo_stock'];
                    //         me.medida = me.arraySeleccionado[0]['medida'];
                    //         me.codigoClasificador = me.arraySeleccionado[0]['codigoClasificador'];
                    //         me.codigoProductoSin = me.arraySeleccionado[0]['codigoProductoSin'];

                    //         //----precios---
                    //         me.unidad_envase = me.arraySeleccionado[0]['unidad_envase'];
                    //         me.precio_uno = me.arraySeleccionado[0]['precio_uno'];
                    //         me.precio_dos = me.arraySeleccionado[0]['precio_dos'];
                    //         me.precio_tres = me.arraySeleccionado[0]['precio_tres'];
                    //         me.precio_cuatro = me.arraySeleccionado[0]['precio_cuatro'];
                    //         me.fotografia = me.arraySeleccionado[0]['fotografia'];
                    //     }
                    //     else {
                    //         me.articulo = 'No existe este articulo';
                    //         me.idarticulo = 0;
                    //     }
                    //     me.codigo = me.codigo;
                    //     console.log('SIRVE', fechaVencimiento);  
                    // }
                    // // console.log('fechaDeVencimiento:', me.fecha_vencimiento);
                    // console.log('Fecha Actual:', fechaActual);
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            }, 1000);
        },
        pdfVenta(id) {
            window.open('/venta/pdf/' + id, '_blank');
        },
        cambiarPagina(page, buscar, criterio) {
            let me = this;
            me.pagination.current_page = page;
            me.listarVenta(page, buscar, criterio);
        },
        encuentra(id) {
            var sw = 0;
            for (var i = 0; i < this.arrayDetalle.length; i++) {
                if (this.arrayDetalle[i].idarticulo == id) {
                    sw = true;
                }
            }
            return sw;
        },
        eliminarDetalle(index) {
            let me = this;
            me.arrayDetalle.splice(index, 1);
            me.arrayProductos.splice(index, 1);
        },
        eliminarKit(id) {
            const indicesEliminar = [];
            for (let i = 0; i < this.arrayDetalle.length; i++) {
                if (this.arrayDetalle[i].idkit === id) {
                    indicesEliminar.push(i);
                }
            }

            indicesEliminar.forEach(index => {
                this.arrayProductos.splice(index, 1);
            });

            for (let i = indicesEliminar.length - 1; i >= 0; i--) {
                this.arrayDetalle.splice(indicesEliminar[i], 1);
            }
        },

        agregarDetalle() {
            let actividadEconomica = 461021;
            // let codigoProductoSin = document.getElementById("codigoProductoSin").value; si hay
            // let codigoProducto = document.getElementById("codigo").value; si 
            // let descripcion = document.getElementById("nombre_producto").value; nombre
            // let cantidad = document.getElementById("cantidad").value; cantidad
            // let unidadMedida = document.getElementById("codigoClasificador").value;
            // let precioUnitario = document.getElementById("precio").value; precio  sin descuento
            // let montoDescuento = document.getElementById("descuento").value; descuento
            // let subTotalValor = document.getElementById("sTotal");  total con descuento
            // let subTotal = subTotalValor.textContent;
            let numeroSerie = null;
            let numeroImei = null;
            let descuento = ((this.precioseleccionado * this.cantidad) * (this.descuentoProducto / 100)).toFixed(2);

            if (this.encuentra(this.arraySeleccionado.id)) {
                swal({
                    type: 'error',
                    title: 'Error...',
                    text: 'Este Artículo ya se encuentra agregado!',
                })
            } else {
                if (this.saldosNegativos === 0 && this.arraySeleccionado.saldo_stock < this.cantidad * this.unidadPaquete) {
                    swal({
                        type: 'error',
                        title: 'Error...',
                        text: 'No hay stock disponible!'
                    });
                    return
                }

                const precioArticulo = (this.calcularPrecioConDescuento(this.resultadoMultiplicacion, this.arrayPromocion ? this.arrayPromocion.porcentaje : 0)) * this.monedaVenta[0]
                console.log("Este es el precio del articulo: ", precioArticulo);
                this.arrayDetalle.push({
                    idkit: -1,
                    idarticulo: this.arraySeleccionado.id,
                    articulo: this.arraySeleccionado.nombre,
                    medida: this.arraySeleccionado.medida,
                    unidad_envase: this.arraySeleccionado.unidad_envase,
                    cantidad: this.cantidad * this.unidadPaquete,
                    cantidad_paquetes: this.arraySeleccionado.unidad_envase,
                    precio: precioArticulo,
                    //descuento: this.arrayPromocion && this.arrayPromocion.porcentaje !== undefined ? this.arrayPromocion.porcentaje : 0,
                    descuento: this.descuentoProducto,
                    stock: this.arraySeleccionado.saldo_stock,
                    precioseleccionado: this.precioseleccionado

                });
                console.log(this.arrayDetalle)
                this.arrayProductos.push({
                    actividadEconomica: actividadEconomica,
                    codigoProductoSin: this.arraySeleccionado.codigoProductoSin,
                    codigoProducto: this.arraySeleccionado.codigo,
                    descripcion: this.arraySeleccionado.nombre,
                    cantidad: this.cantidad * this.unidadPaquete,
                    unidadMedida: this.arraySeleccionado.codigoClasificador,
                    precioUnitario: parseFloat(this.precioseleccionado).toFixed(2),
                    //montoDescuento: this.arrayPromocion && this.arrayPromocion.porcentaje ? ((this.arrayPromocion.porcentaje / this.resultadoMultiplicacion) * 100).toFixed(2) : 0,
                    montoDescuento: descuento,   
                    subTotal: precioArticulo.toFixed(2),
                    numeroSerie: numeroSerie,
                    numeroImei: numeroImei
                });
                console.log("pa la factura: ", this.arrayProductos)
                this.precioBloqueado = true;
                this.arraySeleccionado = [];
                this.cantidad = 1;
                this.unidadPaquete = 1;
                this.codigo = '';
                this.descuentoProducto = 0;
            }
        },

        /*agregarDetalle() {
            let actividadEconomica = 461021;
            // let codigoProductoSin = document.getElementById("codigoProductoSin").value; si hay
            // let codigoProducto = document.getElementById("codigo").value; si 
            // let descripcion = document.getElementById("nombre_producto").value; nombre
            // let cantidad = document.getElementById("cantidad").value; cantidad
            // let unidadMedida = document.getElementById("codigoClasificador").value;
            // let precioUnitario = document.getElementById("precio").value; precio  sin descuento
            // let montoDescuento = document.getElementById("descuento").value; descuento
            // let subTotalValor = document.getElementById("sTotal");  total con descuento
            // let subTotal = subTotalValor.textContent;
            let numeroSerie = null;
            let numeroImei = null;
            let descuento = ((this.precioseleccionado * this.cantidad) * (this.descuentoProducto / 100)).toFixed(2);

            if (this.encuentra(this.arraySeleccionado.id)) {
                swal({
                    type: 'error',
                    title: 'Error...',
                    text: 'Este Artículo ya se encuentra agregado!',
                })
            } else {
                if (this.saldosNegativos === 0 && this.arraySeleccionado.saldo_stock < this.cantidad * this.unidadPaquete) {
                    swal({
                        type: 'error',
                        title: 'Error...',
                        text: 'No hay stock disponible!'
                    });
                    return
                }

                const precioArticulo = (this.calcularPrecioConDescuento(this.resultadoMultiplicacion, this.arrayPromocion ? this.arrayPromocion.porcentaje : 0)) * this.monedaVenta[0]
                console.log("Este es el precio del articulo: ", precioArticulo);
                this.arrayDetalle.push({
                    idkit: -1,
                    idarticulo: this.arraySeleccionado.id,
                    articulo: this.arraySeleccionado.nombre,
                    medida: this.arraySeleccionado.medida,
                    unidad_envase: this.arraySeleccionado.unidad_envase,
                    cantidad: this.cantidad * this.unidadPaquete,
                    cantidad_paquetes: this.arraySeleccionado.unidad_envase,
                    precio: precioArticulo,
                    //descuento: this.arrayPromocion && this.arrayPromocion.porcentaje !== undefined ? this.arrayPromocion.porcentaje : 0,
                    descuento: this.descuentoProducto,
                    stock: this.arraySeleccionado.saldo_stock,
                    precioseleccionado: parseFloat(this.precioseleccionado).toFixed(2)

                });
                console.log(this.arrayDetalle)
                this.arrayProductos.push({
                    actividadEconomica: actividadEconomica,
                    codigoProductoSin: this.arraySeleccionado.codigoProductoSin,
                    codigoProducto: this.arraySeleccionado.codigo,
                    descripcion: this.arraySeleccionado.nombre,
                    cantidad: this.cantidad * this.unidadPaquete,
                    unidadMedida: this.arraySeleccionado.codigoClasificador,
                    precioUnitario: parseFloat(this.precioseleccionado).toFixed(2),
                    //montoDescuento: this.arrayPromocion && this.arrayPromocion.porcentaje ? ((this.arrayPromocion.porcentaje / this.resultadoMultiplicacion) * 100).toFixed(2) : 0,
                    montoDescuento: descuento,   
                    subTotal: precioArticulo.toFixed(2),
                    numeroSerie: numeroSerie,
                    numeroImei: numeroImei
                });
                console.log("pa la factura: ", this.arrayProductos)
                this.precioBloqueado = true;
                this.arraySeleccionado = [];
                this.cantidad = 1;
                this.unidadPaquete = 1;
                this.codigo = '';
                this.descuentoProducto = 0;
            }
        },*/

        agregarDetalleModal(data) {
            this.scrollToSection();
            this.codigo = data.codigo;
            console.log('SLECCIONE ESTO:', data);

            this.buscarPromocion(data.id);
            this.precioseleccionado = data.precio_uno;

            this.cerrarModal();
        },
        eliminarSeleccionado() {
            this.codigo = '';
            this.arraySeleccionado = []
        },
        listarArticulo(buscar, criterio) {
            let me = this;
            var url = '/articulo/listarArticuloVenta?buscar=' + buscar + '&criterio=' + criterio + '&idAlmacen=' + this.idAlmacen;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayArticulo = respuesta.articulos;
                console.log('listar articulo', respuesta);
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        // obtenerSaldosNegativos() {
        //     let me = this;
        //     axios.get('/configuracion/saldos-negativos')
        //         .then(response => {
        //             me.saldosNegativos = response.data.saldosNegativos;
        //             console.log('Saldos Negativos:', me.saldosNegativos);
        //         })
        //         .catch(error => {
        //             console.error('Error al obtener saldos negativos:', error);
        //         });
        // },
        datosConfiguracion() {
            let me = this;
            var url = '/configuracion';

            axios.get(url).then(function (response) {
                let respuesta = response.data;
                console.log(respuesta)
                me.saldosNegativos = respuesta.configuracionTrabajo.saldosNegativos;
                me.permitirDevolucion = respuesta.configuracionTrabajo.permitirDevolucion;
                me.monedaVenta = [respuesta.configuracionTrabajo.valor_moneda_venta, respuesta.configuracionTrabajo.simbolo_moneda_venta]

            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        obtenerDatosUsuario() {
            axios.get('/venta')
                .then(response => {
                    this.usuarioAutenticado = response.data.usuario.usuario;
                    this.puntoVentaAutenticado = response.data.usuario.idpuntoventa;
                })
                .catch(error => {
                    console.error(error);
                });
        },

        verificarFactura(cuf, numeroFactura){
                var url = 'https://pilotosiat.impuestos.gob.bo/consulta/QR?nit=5153610012&cuf='+cuf+'&numero='+numeroFactura+'&t=2';
                window.open(url);
                
            },

            anularFactura(id, cuf) {
            swal({
                title: '¿Está seguro de anular la factura?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                let me = this;
                axios.get('/factura/obtenerDatosMotivoAnulacion')
                    .then(function(response) {
                    var respuesta = response.data;
                    me.arrayMotivosAnulacion = respuesta.motivo_anulaciones;
                    
                    console.log('Motivos obtenidos:', me.arrayMotivosAnulacion);

                    let options = {};
                    me.arrayMotivosAnulacion.forEach(function(motivo) {
                        options[motivo.codigo] = motivo.descripcion;
                    });

                    // Muestra un segundo modal para seleccionar el motivo
                    swal({
                        title: 'Seleccione un motivo de anulación',
                        input: 'select',
                        inputOptions: options,
                        inputPlaceholder: 'Seleccione un motivo',
                        showCancelButton: true,
                        inputValidator: function (value) {
                        return new Promise(function (resolve, reject) {
                            if (value !== '') {
                            resolve();
                            } else {
                            reject('Debe seleccionar un motivo');
                            }
                        });
                        }
                    }).then((result) => {
                        if (result.value) {
                        // Aquí obtienes el motivo seleccionado y puedes realizar la solicitud para anular la factura
                        const motivoSeleccionado = result.value;
                        axios.get('/factura/anular/' + cuf +"/" + motivoSeleccionado)
                            .then(function(response) {
                            const data = response.data;
                            if (data === 'ANULACION CONFIRMADA') {
                                swal(
                                'FACTURA ANULADA',
                                data,
                                'success'
                                );
                            } else {
                                swal(
                                'ANULACION RECHAZADA',
                                data,
                                'warning'
                                );
                            }
                            })
                            .catch(function(error) {
                            console.log(error);
                            });
                        }
                    });
                    })
                    .catch(function(error) {
                    console.log(error);
                    });
                }
            });
            },

            imprimirFactura(id, correo) {
            swal({
                title: 'Selecciona un tamaño de factura',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'CARTA',
                cancelButtonText: 'ROLLO',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    console.log("El boton CARTA fue presionado");
                    axios.get('/factura/imprimirCarta/' + id + '/' + correo, { responseType: 'blob' })
                        .then(function (response) {
                            window.location.href = "docs/facturaCarta.pdf";
                            console.log("Se generó el factura en Carta correctamente");
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                } else if (result.dismiss === swal.DismissReason.cancel) {
                    console.log("El boton ROLLO fue presionado");
                    axios.get('/factura/imprimirRollo/' + id + '/' + correo, { responseType: 'blob' })
                        .then(function (response) {
                            window.location.href = "docs/facturaRollo.pdf";
                            console.log("Se generó el la factura en Rollo correctamente");
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
            }).catch((error) => {
                console.error('Error al mostrar el diálogo:', error);
            });
        },
        //--------HASTA AQUI--------------------
        selectAlmacen() {
            let me = this;
            let url = '/almacen/selectAlmacen';
            axios.get(url).then(function (response) {
                let respuesta = response.data;
                me.arrayAlmacenes = respuesta.almacenes;
                console.log(me.arrayAlmacenes);

            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getAlmacenProductos(almacen) {
            this.idAlmacen = almacen.id;
        },
        validarVenta() {
            let me = this;
            me.errorVenta = 0;
            me.errorMostrarMsjVenta = [];
            var art;

            me.arrayDetalle.map(function (x) {
                if (x.cantidad > x.stock) {
                    art = x.articulo + " Stock insuficiente";
                    me.errorMostrarMsjVenta.push(art);
                }
            });

            if (me.idcliente == 0) me.errorMostrarMsjVenta.push("Seleccione un Cliente");
            if (me.tipo_comprobante == 0) me.errorMostrarMsjVenta.push("Seleccione el Comprobante");
            if (!me.impuesto) me.errorMostrarMsjVenta.push("Ingrese el impuesto de compra");
            if (me.arrayDetalle.length <= 0) me.errorMostrarMsjVenta.push("Ingrese detalles");

            if (me.errorMostrarMsjVenta.length) me.errorVenta = 1;

            return me.errorVenta;
        },

        aplicarDescuento() {
            const descuentoGiftCard = this.descuentoGiftCard;
            const numeroTarjeta = this.numeroTarjeta;
            let idtipo_pago;

            if (numeroTarjeta && descuentoGiftCard) {
                idtipo_pago = 86;
            } else if (numeroTarjeta && !descuentoGiftCard) {
                idtipo_pago = 10;
            } else {
                idtipo_pago = descuentoGiftCard ? 35 : 1;
            }

            this.registrarVenta(idtipo_pago);
        },

        aplicarCombinacion() {
            const descuentoGiftCard = this.descuentoGiftCard
            const idtipo_pago = descuentoGiftCard ? 40 : 2; 

            this.registrarVenta(idtipo_pago);
        },

        otroMetodo(metodoPago){
            const idtipo_pago = metodoPago;
            this.registrarVenta(idtipo_pago);
        },
        
        registrarVenta(idtipo_pago) {
            if (this.validarVenta()) {
                return;
            }

            let me = this;
            this.mostrarSpinner = true;
            this.idtipo_pago = idtipo_pago;

            for (let i = 0; i < me.cuotas.length; i++) {                
                console.log('LLEGA ARRAYDATA!', me.cuotas[i]);
            }

            console.log("hola");
            console.log(this.primer_precio_cuota);
            axios.post('/venta/registrar', {
                'idcliente': this.idcliente,
                'tipo_comprobante': this.tipo_comprobante,
                'serie_comprobante': this.serie_comprobante,
                'num_comprobante': this.num_comprob,
                'impuesto': this.impuesto,
                'total': this.calcularTotal,
                'idAlmacen': this.idAlmacen,
                'idtipo_pago': idtipo_pago,
                'idtipo_venta': this.idtipo_venta,
                'primer_precio_cuota': this.primer_precio_cuota,
                // Datos para el crédito de venta
                'idpersona': this.idcliente,
                'numero_cuotasCredito': this.numero_cuotas, // Cambio de nombre
                'tiempo_dias_cuotaCredito': this.tiempo_diaz, // Cambio de nombre
                'totalCredito': this.primera_cuota ? this.calcularTotal - this.cuotas[0].totalCancelado : this.calcularTotal, // Asegúrate de tener esta variable
                'estadoCredito': "Pendiente", // Asegúrate de tener esta variable

                // Cuotas del crédito
                'cuotaspago': this.cuotas,
                'data': this.arrayDetalle

            }).then((response) => {
                let idVentaRecienRegistrada = response.data.id;
                this.emitirFactura(idVentaRecienRegistrada);

                if (response.data.id > 0) {
                    // Restablecer valores después de una venta exitosa
                    me.listado = 1;
                    me.listarVenta(1, '', 'num_comprob');
                    me.cerrarModal2();
                    me.cerrarModal3();
                    me.idproveedor = 0;
                    me.tipo_comprobante = 'FACTURA';
                    me.nombreCliente = '';
                    me.idcliente = 0;
                    me.tipo_documento = 0;
                    me.complemento_id = '';
                    me.documento = '';
                    me.imagen = '';
                    me.serie_comprobante = '';
                    me.num_comprob = '';
                    me.impuesto = 0.18;
                    me.total = 0.0;
                    me.idarticulo = 0;
                    me.articulo = '';
                    me.cantidad = 0;
                    me.precio = 0;
                    me.stock = 0;
                    me.codigo = '';
                    me.descuento = 0;
                    me.arrayDetalle = [];
                    me.primer_precio_cuota = 0;

                    //window.open('/factura/imprimir/' + response.data.id);
                } else {
                    console.log(response)
                    if (response.data.valorMaximo) {
                        //alert('El valor de descuento no puede exceder el '+ response.data.valorMaximo);
                        swal(
                            'Aviso',
                            'El valor de descuento no puede exceder el ' + response.data.valorMaximo,
                            'warning'
                        )
                        return;
                    } else {

                        //alert(response.data.caja_validado); 
                        swal(
                            'Aviso',
                            response.data.caja_validado,
                            'warning'
                        )
                        return;
                    }
                    //console.log(response.data.valorMaximo)
                }

            }).catch((error) => {
                console.log(error);
            });
        },

        async emitirFactura(idVentaRecienRegistrada) {

            let me = this;

            let idventa = idVentaRecienRegistrada;
            let numeroFactura = document.getElementById("num_comprobante").value;
            let cuf = "464646464";
            let cufdValor = document.getElementById("cufdValor");
            let cufd = cufdValor.textContent;
            let direccionValor = document.getElementById("direccion");
            let direccion = direccionValor.textContent;
            var tzoffset = (new Date()).getTimezoneOffset() * 60000;
            let fechaEmision = (new Date(Date.now() - tzoffset)).toISOString().slice(0, -1);
            let id_cliente = document.getElementById("idcliente").value;
            let nombreRazonSocial = document.getElementById("nombreCliente").value;
            let numeroDocumento = document.getElementById("documento").value;
            let complemento = document.getElementById("complemento_id").value;
            let tipoDocumentoIdentidad = document.getElementById("tipo_documento").value;
            let montoTotal = (this.calcularTotal * parseFloat(this.monedaVenta[0])).toFixed(2);
            let descuentoAdicional = document.getElementById("descuentoAdicional").value;
            let usuario = document.getElementById("usuarioAutenticado").value;
            //let codigoPuntoVenta = this.puntoVentaAutenticado;
            let codigoPuntoVenta = 0;
            let montoGiftCard = document.getElementById("descuentoGiftCard").value;
            let codigoMetodoPago = this.idtipo_pago;
            let montoTotalSujetoIva = montoTotal - this.descuentoGiftCard;
            let correo = document.getElementById("email").value;

            console.log("El monto de Descuento de Gift Card es: " + this.descuentoGiftCard);
            console.log("El tipo de documento es: " + tipoDocumentoIdentidad);
            console.log("El complemento de documento es: " + complemento);

            try {
                const response = await axios.get('/factura/obtenerLeyendaAleatoria');
                this.leyendaAl = response.data.descripcionLeyenda;
                console.log("El dato de leyenda llegado es: " + this.leyendaAl);
            } catch (error) {
                console.error(error);
                return '"Ley N° 453: Los servicios deben suministrarse en condiciones de inocuidad, calidad y seguridad."';
            }

            try {
                    if (tipoDocumentoIdentidad === '5') {
                        const response = await axios.post('/factura/verificarNit/' + numeroDocumento);
                        if (response.data === 'NIT ACTIVO') {
                            me.codigoExcepcion = 0;
                            alert("NIT VÁLIDO.");
                        } else {
                            me.codigoExcepcion = 1;
                            alert("NIT INVÁLIDO.");
                        }
                    }else{
                        me.codigoExcepcion = 0;
                    }
                } catch (error) {
                    console.error(error);
                    return 'No se pudo verificar el NIT';
                }

            var factura = [];
            factura.push({
                cabecera: {
                    nitEmisor: "5153610012",
                    razonSocialEmisor: "365 SOFT",
                    municipio: "Cochabamba",
                    telefono: "77490451",
                    numeroFactura: numeroFactura,
                    cuf: cuf,
                    cufd: cufd,
                    codigoSucursal: 0,
                    direccion: direccion,
                    codigoPuntoVenta: codigoPuntoVenta,
                    fechaEmision: fechaEmision,
                    nombreRazonSocial: nombreRazonSocial,
                    codigoTipoDocumentoIdentidad: tipoDocumentoIdentidad,
                    numeroDocumento: numeroDocumento,
                    complemento: complemento,
                    codigoCliente: numeroDocumento,
                    codigoMetodoPago: codigoMetodoPago,
                    numeroTarjeta: this.numeroTarjeta,
                    montoTotal: montoTotal,
                    montoTotalSujetoIva: montoTotalSujetoIva,
                    codigoMoneda: 1,
                    tipoCambio: 1,
                    montoTotalMoneda: montoTotal,
                    montoGiftCard: this.descuentoGiftCard,
                    descuentoAdicional: descuentoAdicional,
                    codigoExcepcion: this.codigoExcepcion,
                    cafc: null,
                    leyenda: this.leyendaAl,
                    usuario: usuario,
                    codigoDocumentoSector: 1
                }
            })
            me.arrayProductos.forEach(function (prod) {
                factura.push({ detalle: prod })
            })

            var datos = { factura };

            axios.post('/venta/emitirFactura', {
                factura: datos,
                id_cliente: id_cliente,
                idventa: idventa,
                correo: correo
            })
                .then(function (response) {
                    var data = response.data;
                    console.log(response);

                    if (data === "VALIDADA") {
                        swal(
                            'FACTURA VALIDADA',
                            'Correctamente',
                            'success'
                        )
                        me.arrayProductos = [];
                        me.codigoExcepcion = 0;
                        me.idtipo_pago = '';
                        me.email = '';
                        me.descuentoGiftCard = '';
                        me.numeroTarjeta =  null;
                        me.recibido = '';
                        me.metodoPago = '';
                        me.cerrarModal2();
                        me.cerrarModal3();
                        me.listarVenta(1, '', 'id');
                        me.mostrarSpinner = false;
                    } else{
                        me.arrayProductos = [];
                        me.codigoExcepcion = 0;
                        me.idtipo_pago = '';
                        me.descuentoGiftCard = '';
                        me.numeroTarjeta =  null;
                        me.recibido = '';
                        me.metodoPago = '';
                        me.cerrarModal2();
                        me.cerrarModal3();
                        me.listarVenta(1, '', 'id');
                        me.mostrarSpinner = false;
                        swal(
                            'FACTURA VALIDADA',
                            'éxito',
                            'success'
                        );
                    }
                })
                .catch(function (error) {
                    me.arrayProductos = [];
                    me.codigoExcepcion = 0;
                    swal(
                        'INTENTE DE NUEVO',
                        'Comunicacion con SIAT fallida',
                        'error');
                    me.mostrarSpinner = false;
                    me.idtipo_pago = '';
                    me.numeroTarjeta =  null;
                    me.descuentoGiftCard = '';
                    me.recibido = '';
                    me.metodoPago = '';
                });
        },

        mostrarDetalle() {
            let me = this;
            me.selectAlmacen();
            me.listado = 0;

            me.idproveedor = 0;
            me.tipo_comprobante = 'FACTURA';
            me.serie_comprobante = '';
            me.nextNumber();
            //me.num_comprobante = '';
            me.impuesto = 0.18;
            me.total = 0.0;
            me.idarticulo = 0;
            me.articulo = '';
            me.cantidad = 1;
            me.precio = 0;
            me.arrayDetalle = [];
            me.arraySeleccionado = [];
        },
        ocultarDetalle() {
            console.log("CERRANDO ")
            this.listado = 1;
            this.codigo = null;
            this.arrayArticulo.length = 0;
            this.precioseleccionado = null;
            this.medida = null;
            this.nombreCliente = null;
            this.documento = null;
            this.email = null;
            this.idAlmacen = null;
            this.arrayProductos = [];
            this.arrayDetalle = [];
            this.precioBloqueado = false;

        },
        verVenta(id) {
            let me = this;
            me.listado = 2;

            //Obtener datos del ingreso
            var arrayVentaT = [];
            var url = '/venta/obtenerCabecera?id=' + id;

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                arrayVentaT = respuesta.venta;
                console.log("VIENDO ", respuesta)

                me.cliente = arrayVentaT[0]['nombre'];
                me.tipo_comprobante = arrayVentaT[0]['tipo_comprobante'];
                me.serie_comprobante = arrayVentaT[0]['serie_comprobante'];
                me.num_comprobante = arrayVentaT[0]['num_comprobante'];
                me.impuesto = arrayVentaT[0]['impuesto'];
                me.total = arrayVentaT[0]['total'];
            })
                .catch(function (error) {
                    console.log(error);
                });

            //obtener datos de los detalles
            var url = '/venta/obtenerDetalles?id=' + id;

            axios.get(url).then(function (response) {
                //console.log(response);
                var respuesta = response.data;
                me.arrayDetalle = respuesta.detalles;
                console.log(array)

            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        cerrarModal() {
            this.modal = 0;
            this.tituloModal = '';
        },
        abrirModal() {
            this.scrollToTop()
            this.listarArticulo('', 'nombre');
            this.listarKits(1, '', 'nombre');
            this.listarOfertaEspecial(1, '', 'nombre');

            this.arrayArticulo = [];
            this.modal = 1;
            this.tituloModal = 'Seleccione los articulos que desee';
        },
        advertenciaFechaVencimiento() {
            swal({
                title: 'No Disponible',
                text: 'No puede seleccionar este producto porque esta vencido.',
                type: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar',
                confirmButtonClass: 'btn btn-success',
                buttonsStyling: false,
            }).then(() => {
                this.cerrarModal();
            });
        },

        desactivarVenta(id) {
            swal({
                title: 'Esta seguro de anular esta venta?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/venta/desactivar', {
                        'id': id
                    }).then(function (response) {
                        me.listarVenta(1, '', 'num_comprobante');
                        swal(
                            'Anulado!',
                            'La venta ha sido anulado con éxito.',
                            'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });


                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
            })
        },
        //-------------OBTENER PRECIOS Y MABRIR_MODAL----------
        listarPrecio() {
            let me = this;
            var url = '/precios';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayPrecios = respuesta.precio.data;
                console.log('PRECIOS', me.arrayPrecios);
                //me.precioCount = me.arrayBuscador.length;
            }).catch(function (error) {
                console.log(error);
            });
        },
        mostrarSeleccion() {
            console.log('Precio seleccionado:', this.precioseleccionado);
        },
        
        //--------------ABRIR MODAL------------------
        registrarAbrilModal() {
            this.modal2 = 1;
            this.cliente = this.nombreCliente;
            console.log('USUARIO LLEGA:', this.cliente);
            this.tituloModal2 = 'VENTA  AL CONTADO ' + this.cliente; // Usamos '+' para concatenar el nombre del cliente
            this.tipoAccion2 = 1;
            this.idtipo_venta = 1;
            console.log('idtipo_venta LLEGA:', this.idtipo_venta);
            console.log('idtipo_pago LLEGA:', this.idtipo_pago);
        },

        registrarAbrilModal2() {
            this.modal3 = 1;
            this.cliente = this.nombreCliente;
            console.log('USUARIO LLEGA:', this.cliente);
            this.tituloModal3 = 'CREDITOS ' + this.cliente; // Usamos '+' para concatenar el nombre del cliente
            this.tipoAccion3 = 1;
            this.idtipo_venta = 2;
            console.log('idtipo_venta LLEGA:', this.idtipo_venta);
            console.log('idtipo_pago LLEGA:', this.idtipo_pago);
        },
        cerrarModal2() {
            this.modal2 = 0;
            this.tituloModal2 = '';
            this.idtipo_pago = '';
            this.tipoPago = '';
        },
        cerrarModal3() {
            this.modal3 = 0;
            this.tituloModal3 = '';
            this.numero_cuotas = '';
            this.tiempo_diaz = '';
            this.primera_cuota = false;
            this.cuotas = [];
        },
        // inicializarFechas() {
        //         const fechaActual = new Date().toISOString().substr(0, 10);
        //         this.fecha_credito = fechaActual;
        //         this.fecha_entrega = fechaActual;
        //         this.diasToAdd = 0;
        //         console.log('fecha_credito inicial:', this.fecha_credito);
        //         console.log('diasToAdd inicial:', this.diasToAdd);
        // },
        // //--------SUMAR LOS DIAS---
        // sumarDias() {
        //     console.log('Evento @input llamado');
        //     if (this.diasToAdd && this.diasToAdd > 0) {
        //         const fechaActual = new Date(this.fecha_credito);
        //         fechaActual.setDate(fechaActual.getDate() + parseInt(this.diasToAdd));
        //         this.fecha_credito = fechaActual.toISOString().substr(0, 10);
        //         // this.fecha_credito = fechaActual.toISOString().split('T')[0];
        //     }
        // },
        //------CALCULAR CALCULADORA--
        // calcularCambio() {
        //     if (isNaN(this.totalprue) || isNaN(this.recibido)) {
        //         alert('Ingresa valores válidos.');
        //         return;
        //     }
        //     if (this.recibido === 0) {
        //         this.efectivo = this.recibido;
        //         console.log('EFECTIVO',this.efectivo);
        //         this.cambio = 0;
        //         this.faltante = 0;

        //     } else if (this.recibido < this.totalprue) {
        //         this.efectivo = this.recibido;
        //         this.faltante = (this.totalprue - this.efectivo).toFixed(2);
        //     } else if (this.recibido === this.totalprue) {
        //         this.efectivo = this.recibido;
        //         this.cambio = '0 BS (Sin cambio)';
        //         this.cambio = 0;
        //         this.faltante = 0;
        //     } else {
        //         this.efectivo = this.recibido;
        //         this.cambio = (this.efectivo - this.totalprue).toFixed(2);
        //         this.faltante = 0;
        //     }
        // },
        calcularCambio() {
            // Convierte this.recibido a un número
            // cambio /parseFloat(monedaVenta[0])).toFixed(2)
            const recibidoNumero = parseFloat(this.recibido / parseFloat(this.monedaVenta[0]));
            if (recibidoNumero === 0) {
                this.efectivo = recibidoNumero;
                console.log('EFECTIVO', this.efectivo);
                this.cambio = 0;
                this.faltante = 0;
            } else if (recibidoNumero < this.calcularTotal) {
                this.efectivo = recibidoNumero;
                this.faltante = (this.calcularTotal - this.efectivo).toFixed(2);
            } else if (recibidoNumero === this.calcularTotal) {
                this.efectivo = recibidoNumero;
                this.cambio = 0;
                this.faltante = 0;
            } else {
                this.efectivo = recibidoNumero;
                this.cambio = (this.efectivo - this.calcularTotal).toFixed(2);
                this.faltante = 0;
            }
        },
        generarCuotas() {
            this.cuotas = []; // Limpiamos la lista de cuotas
            const fechaHoy = new Date(); // Obtenemos la fecha de hoy

            // Calcular el monto entero y el monto con decimal
            const montoEntero = Math.floor(this.calcularTotal / this.numero_cuotas);
            const montoDecimal = (this.calcularTotal - montoEntero * (this.numero_cuotas - 1)).toFixed(2);


            let fechaInicioPago;
            let saldos_total;
            let estadoCuota;
            if (this.primera_cuota) {
                this.primer_precio_cuota = montoEntero;
                console.log('primer_precio_cuota', this.primer_precio_cuota);
                fechaInicioPago = fechaHoy;
                saldos_total = (this.calcularTotal - this.primer_precio_cuota).toFixed(2);
                console.log('saldos_total', saldos_total);
                estadoCuota = 'Pagado';
            } else {
                this.primer_precio_cuota = 0;
                fechaInicioPago = new Date(fechaHoy.getTime() + this.tiempo_diaz * 24 * 60 * 60 * 1000); // Sumar 7 días
                saldos_total = this.calcularTotal;
                estadoCuota = 'Pendiente';
            }

            for (let i = 0; i < this.numero_cuotas; i++) {
                const fechaPago = new Date(fechaInicioPago.getTime() + i * this.tiempo_diaz * 24 * 60 * 60 * 1000);
                const dia = fechaPago.getDate();
                const mes = fechaPago.getMonth() + 1;
                const año = fechaPago.getFullYear();

                const cuota = {
                    fecha_pago: `${año}-${mes}-${dia} ${fechaPago.toLocaleTimeString()}`,
                    precio_cuota: i === this.numero_cuotas - 1 ? parseFloat(montoDecimal).toFixed(2) : montoEntero,
                    totalCancelado: i === 0 ? this.primer_precio_cuota : 0,
                    saldo_restante: saldos_total,//saldo
                    fecha_cancelado: i === 0 && this.primera_cuota ? `${año}-${mes}-${dia} ${fechaPago.toLocaleTimeString()}` : null,
                    estado: i === 0 ? (this.primera_cuota ? 'Pagado' : estadoCuota) : 'Pendiente',
                };

                this.cuotas.push(cuota);
            }
        },
    },

    created() {
        this.listarPrecio();
        axios.get('/ruta-a-tu-endpoint-laravel-para-obtener-ultimo-comprobante')
            .then(response => {
                const lastComprobante = response.data.last_comprobante;

                this.last_comprobante = lastComprobante;

                this.nextNumber();
            })
            .catch(error => {
                console.error('Error al obtener el último comprobante:', error);
            });
    },
    mounted() {
        this.datosConfiguracion();

        this.listarVenta(1, this.buscar, this.criterio);

        window.addEventListener('keydown', this.atajoButton);
        this.verificarComunicacion();
        this.cuis();
        this.cufd();
        this.obtenerDatosUsuario();
    }
}
</script>
<style scoped>    
    
/* Estilos para los iconos (ajusta según tus necesidades) */
.fa-check-circle {
    margin-left: 5px; /* Espacio entre el precio y el icono */
}

.custom-btn {
    width: 100%;
    text-align: center;
    padding: 5px;
    color: #939392;
    border: 1px solid #939392;
    background-color: white;
    margin-left: 5px;
    margin-right: 5px;
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: center;
    border-radius: 5%;
    /* Agregando sombra */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
}


.selected {
    background-color: rgba(5, 75, 122, 0.1);
    /* Ajusta el último valor para cambiar la opacidad */
    font-weight: 100;
    color: #054b7a;
    border: 1px solid #054b7a;
    transition: all 0.5s ease;


}

/* #054b7a; */
.efectivo {
    color: #054b7a;
    transition: all 0.3s ease;

}

.qr {
    transition: all 0.3s ease;

    color: #054b7a;
}

.transferencia {
    transition: all 0.3s ease;

    color: #054b7a;
}

.modal-content {
    width: 100% !important;
    position: absolute !important;
}

.mostrar {
    display: list-item !important;
    opacity: 1 !important;
    position: absolute !important;
    background-color: #3c29297a !important;
}

.div-error {
    display: flex;
    justify-content: center;
}

.text-error {
    color: red !important;
    font-weight: bold;
}

@media (min-width: 600px) {
    .btnagregar {
        margin-top: 2rem;
    }
}

.card-img {
    width: auto;
    height: 200px;
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
}

    .spinner-container {
        position: relative;
    }

    .spinner-container > * {
        position: absolute; 
        top: 50%; 
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .spinner-message {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translate(-50%, -170%);
        z-index: 1;
    }



/* .negrita-input {
        font-weight: bold;
    } */
</style>
