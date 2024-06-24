<template>
    <main class="main">
        <!-- Breadcrumb -->


        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Ventas
                <button type="button" @click="abrirTipoVenta()" class="btn btn-primary btn-lg">
                    <i class="icon-plus"></i> Nuevo
                </button>
            </div>
            <!-- Listado-->
            <template v-if="listado == 1">

                <div class="form-group row">
                    <div class="col-md-8">
                        <div class="input-group">
                            <select class="selectpicker show-tick" v-model="criterio">
                                <option value="" disabled selected>Seleccione</option>
                                <option value="tipo_comprobante">Tipo Comprobante</option>
                                <option value="num_comprobante">Número Comprobante</option>
                                <option value="fecha_hora">Fecha-Hora</option>
                                <option value="usuario">Usuario</option>
                            </select>
                            <input type="search" v-model="buscar" @keyup="listarVenta(1, buscar, criterio)"
                                class="form-control" placeholder="Texto a buscar">
                        </div>
                    </div>
                </div>


                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Vendedor</th>
                                <th>Cliente</th>
                                <th class="d-none d-md-table-cell">Documento</th>
                                <th class="d-none d-md-table-cell">N° de Comprobante</th>
                                <th class="d-none d-md-table-cell">Fecha y Hora</th>
                                <th>Total</th>
                                <th class="d-none d-md-table-cell">Estado</th>
                                <!--<th class="d-none d-md-table-cell">Recibo</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="venta in arrayVenta" :key="venta.id">
                                <td class="d-flex align-items-center">
                                    <button type="button" @click="verVenta(venta.id)"
                                        class="btn btn-success btn-sm mr-1">
                                        <i class="icon-eye"></i>
                                    </button>
                                    <!--
                                    <button type="button" @click="pdfVenta(venta.id)" class="btn btn-info btn-sm mr-1">
                                        <i class="icon-doc"></i>
                                    </button>
                                    -->

                                    <template v-if="venta.estado == 'Registrado'">
                                        <button type="button" class="btn btn-danger btn-sm mr-1"
                                            @click="desactivarVenta(venta.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <button class="btn btn-primary" type="button"
                                        @click="imprimirResivo(venta.id, venta.correo)">
                                        <i class="icon-printer"></i>
                                    </button>
                                    <template v-if="venta.idtipo_venta == 2 && venta.estado == 'Pendiente'">
                                        <button type="button" class="btn btn-primary btn-sm mr-1"
                                            @click="abrirModalCuotas(venta.id)">
                                            <i class="icon-plus"></i>
                                        </button>
                                    </template>
                                </td>
                                <td v-text="venta.usuario"></td>
                                <td v-text="venta.razonSocial"></td>
                                <td class="d-none d-md-table-cell" v-text="venta.documentoid"></td>
                                <td class="d-none d-md-table-cell" v-text="venta.num_comprobante"></td>
                                <td class="d-none d-md-table-cell" v-text="venta.fecha_hora"></td>
                                <td>{{ (venta.total * parseFloat(monedaVenta[0])).toFixed(2) }} {{ monedaVenta[1] }}
                                </td>
                                <td class="d-none d-md-table-cell" v-text="venta.estado"></td>
                                <!--<td class="d-none d-md-table-cell">
                                    <button class="btn btn-primary" type="button"
                                        @click="imprimirResivo(venta.id, venta.correo)">
                                        <i class="icon-printer"></i>
                                    </button>
                                </td>-->
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

            </template>



            <!--Fin Listado-->

            <!--Ver ingreso-->
            <template v-else-if="listado == 2">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="form-group row border p-3">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Cliente</label>
                                    <p class="mb-0" v-text="cliente"></p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tipo Comprobante</label>
                                    <p class="mb-0" v-text="tipo_comprobante"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Número Comprobante</label>
                                    <p class="mb-0" v-text="num_comprobante"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Artículo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <td v-text="detalle.articulo"></td>
                                            <td>
                                                {{
                    (detalle.precio * parseFloat(monedaVenta[0])).toFixed(2)
                }}
                                                {{ monedaVenta[1] }}
                                            </td>
                                            <td v-text="detalle.cantidad"></td>
                                            <td>
                                                {{
                    (
                        (detalle.precio * detalle.cantidad) *
                        parseFloat(monedaVenta[0])
                    ).toFixed(2)
                }}
                                                {{ monedaVenta[1] }}
                                            </td>
                                        </tr>
                                        <tr class="bg-light font-weight-bold">
                                            <td colspan="3" class="text-right">Total Neto:</td>
                                            <td>
                                                {{ (total * parseFloat(monedaVenta[0])).toFixed(2) }}
                                                {{ monedaVenta[1] }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12 text-right">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <!--Fin ver ingreso-->
            <!-- Vista Devoluciones -->
            <template v-else-if="listado == 3">
                <div>
                    <devoluciones></devoluciones>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <!-- HASTA AQUI DEVOLUCIONES -->
        <template>
            <div class="modal" tabindex="-1" :class="{ 'mostrar': modal2 }" role="dialog" aria-labelledby="myModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detalle Ventas</h5>
                            <button type="button" class="close" @click="cerrarModal2()" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="linear-stepper">
                                <div class="step-container">
                                    <div class="step" :class="{ active: step === 1, completed: step > 1 }">
                                        <span class="step-number" v-if="step > 1">✔</span>
                                        <span class="step-number" v-else>1</span>
                                        <span class="step-name">Cliente</span>
                                    </div>
                                    <div class="step-line" v-if="step >= 2"></div>
                                </div>
                                <div class="step-container">
                                    <div class="step" :class="{ active: step === 2, completed: step > 2 }">
                                        <span class="step-number" v-if="step > 2">✔</span>
                                        <span class="step-number" v-else>2</span>
                                        <span class="step-name">Producto</span>
                                    </div>
                                    <div class="step-line" v-if="step >= 3"></div>
                                </div>
                                <div class="step-container">
                                    <div class="step" :class="{ active: step === 3, completed: step > 3 }">
                                        <span class="step-number" v-if="step > 3">✔</span>
                                        <span class="step-number" v-else>3</span>
                                        <span class="step-name">Pagos</span>
                                    </div>
                                </div>
                            </div>
                            <div v-show="step === 1" class="step-content">
                                <!-- Cliente Selection -->

                                <div class="form-group row border">
                                    <!-- Cliente Selection -->
                                    <div class="col-md-4">
                                        <label class="font-weight-bold">Documento <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="documento" class="form-control" v-model="documento"
                                            @keyup.enter="buscarClientePorDocumento" />
                                    </div>

                                    <!-- Nombre Input -->
                                    <div class="col-md-4">
                                        <label class="font-weight-bold">Cliente <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="nombreCliente" class="form-control"
                                            v-model="nombreCliente" :readonly="!nombreClienteEditable" />
                                    </div>

                                    <!-- Hidden Inputs -->
                                    <input type="hidden" id="idcliente" class="form-control" v-model="idcliente"
                                        ref="idRef" readonly />
                                    <input type="hidden" id="tipo_documento" class="form-control"
                                        v-model="tipo_documento" ref="tipoDocumentoRef" readonly />
                                    <input type="hidden" id="complemento_id" class="form-control"
                                        v-model="complemento_id" ref="complementoIdRef" readonly />
                                    <input type="hidden" id="usuarioAutenticado" class="form-control"
                                        v-model="usuarioAutenticado" readonly />
                                    <input type="hidden" id="puntoVentaAutenticado" class="form-control"
                                        v-model="puntoVentaAutenticado" readonly />
                                    <input type="hidden" id="email" class="form-control" v-model="email" readonly />

                                    <!-- Tipo de Comprobante Selection -->
                                    <div class="col-md-4">
                                        <label class="font-weight-bold">Tipo de comprobante <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" v-model="tipo_comprobante"
                                            ref="tipoComprobanteRef">

                                            <option value="RESIVO">RECIBO</option>
                                        </select>
                                    </div>

                                    <!-- Numero de Comprobante Input -->
                                        <input type="hidden" id="num_comprobante" class="form-control"
                                            v-model="num_comprob" disabled />
                                </div>

                            </div>

                            <div v-show="step === 2" class="step-content">
                                <!-- Producto Selection -->
                                <div class="form-group row border">

                                    <div class="form-group row border">
                                        <div class="col-md-6">
                                            <label class="font-weight-bold">Almacen <span
                                                    class="text-danger">*</span></label>
                                            <v-select label="nombre_almacen" :options="arrayAlmacenes"
                                                placeholder="Seleccione un almacen"
                                                :onChange="getAlmacenProductos"></v-select>
                                        </div>


                                        <div class="col-md-6">
                                            <label class="font-weight-bold">Buscar articulo</label>
                                            <div class="input-group mb-3">
                                                <input :disabled="!idAlmacen" type="text" class="form-control"
                                                    v-model="codigo" placeholder="Codigo del articulo"
                                                    aria-label="Codigo del articulo" @keyup="buscarArticulo()" />
                                                <button :disabled="!idAlmacen" class="btn btn-primary" type="button"
                                                    @click="abrirModal()">...</button>
                                            </div>
                                        </div>


                                        <!-- Articulo Details -->
                                        <template v-if="arraySeleccionado && arraySeleccionado.id">
                                            <div class="col-md-12">
                                                <div class="card-body">
                                                    <h3 style="margin:0px">{{ arraySeleccionado.nombre }}</h3>
                                                    <span class="badge bg-primary">Medida: {{ arraySeleccionado.medida
                                                        }}</span>
                                                    <span class="badge bg-primary">Linea: {{
                    arraySeleccionado.nombre_categoria }}</span>
                                                    <p>{{ arraySeleccionado.descripcion }}</p>
                                                    <h3 v-if="arrayPromocion && arrayPromocion.id"
                                                        style="display:flex;align-items:center;margin:0px;">
                                                        <b v-if="arrayPromocion.porcentaje == 100">GRATIS</b>
                                                        <b v-else>{{
                    (calcularPrecioConDescuento(resultadoMultiplicacion,
                        arrayPromocion.porcentaje)
                        *
                        parseFloat(monedaVenta[0])).toFixed(2) }} {{ monedaVenta[1]
                                                            }}</b>
                                                        <s style="font-size:15px" class="lead">{{
                    calcularPrecioConDescuento(resultadoMultiplicacion
                        *
                        parseFloat(monedaVenta[0])).toFixed(2) }} {{ monedaVenta[1]
                                                            }}</s>
                                                    </h3>
                                                    <h3 v-else style="display:flex;align-items:center;margin:0px;">
                                                        <b>{{ calcularPrecioConDescuento(resultadoMultiplicacion *
                    parseFloat(monedaVenta[0])).toFixed(2)
                                                            }} {{ monedaVenta[1] }}</b>
                                                    </h3>
                                                    <p style="margin:0px" v-if="arrayPromocion && arrayPromocion.id"
                                                        class="lead">{{
                    arrayPromocion.porcentaje }} % de descuento</p>
                                                    <p style="margin:0px" v-if="arrayPromocion && arrayPromocion.id"
                                                        class="text-danger"><i class="fa fa-clock-o"
                                                            aria-hidden="true"></i> Esta oferta termina en {{
                    calcularDiasRestantes(arrayPromocion.fecha_final) }} días</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 d-flex flex-column align-items-center">
                                                <img v-if="arraySeleccionado.fotografia"
                                                    :src="'img/articulo/' + arraySeleccionado.fotografia + '?t=' + new Date().getTime()"
                                                    width="50" height="50" ref="imagen" class="card-img" />
                                                <img v-else src="img/productoSinImagen.png" alt="Imagen del Card"
                                                    class="card-img" />
                                                <div :class="{
                    alert: true,
                    'alert-success': arraySeleccionado.saldo_stock / unidadPaquete - cantidad > arraySeleccionado.stock / unidadPaquete,
                    'alert-warning': arraySeleccionado.saldo_stock / unidadPaquete - cantidad <= arraySeleccionado.stock / unidadPaquete,
                    'alert-danger': arraySeleccionado.saldo_stock / unidadPaquete - cantidad <= 0,
                }" role="alert">
                                                    <p style="margin:0px">Stock disponible</p>
                                                    <b>{{ arraySeleccionado.saldo_stock / unidadPaquete - cantidad }} {{
                    unidadPaquete == 1 ?
                        "Unidades"
                        : "Paquetes" }}</b>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Tipo de venta <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-select" v-model="unidadPaquete"
                                                        aria-label="Default select example">
                                                        <option :value="arraySeleccionado.unidad_envase">Por paquete
                                                        </option>
                                                        <option value="1">Por unidad</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Cantidad <span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" id="cantidad" value="1" class="form-control"
                                                        v-model="cantidad" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group d-flex">
                                                    <button @click="agregarDetalle()"
                                                        class="btn btn-success flex-fill btnagregar">
                                                        <i class="icon-plus"></i> Agregar
                                                    </button>
                                                    <button @click="eliminarSeleccionado()"
                                                        class="btn btn-danger flex-fill btnagregar ml-2">
                                                        <i class="icon-minus"></i> Eliminar
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="form-group row border">
                                        <div class="table-responsive col-md-12">
                                            <table class="table table-bordered table-striped table-sm"
                                                style="text-align: center; margin: 0 auto;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10%;">Opciones</th>
                                                        <th style="width: 30%;">Artículo</th>
                                                        <th style="width: 15%;">Precio Unidad</th>
                                                        <th style="width: 15%;">Unidades</th>
                                                        <th style="width: 20%;">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody v-if="arrayDetalle.length">
                                                    <tr v-for="(detalle, index) in arrayDetalle" :key="detalle.id">
                                                        <td>
                                                            <button v-if="detalle.medida != 'KIT'"
                                                                @click="eliminarDetalle(index)" type="button"
                                                                class="btn btn-danger btn-sm">
                                                                <i class="icon-close"></i>
                                                            </button>
                                                            <button v-else @click="eliminarKit(detalle.idkit)"
                                                                type="button" class="btn btn-danger btn-sm">
                                                                <i class="icon-close"></i>
                                                            </button>
                                                        </td>
                                                        <td v-text="detalle.articulo"></td>
                                                        <td>{{ (detalle.precioseleccionado *
                    parseFloat(monedaVenta[0])).toFixed(2) }} {{
                    monedaVenta[1]
                }}</td>
                                                        <td>
                                                            <input type="number" v-model="detalle.cantidad" min="1"
                                                                @input="actualizarDetalle(index)"
                                                                style="border: none; outline: none; width: 50px; text-align: center;" />
                                                        </td>
                                                        <td>{{ (detalle.precioseleccionado * detalle.cantidad *
                    parseFloat(monedaVenta[0])).toFixed(2)
                                                            }}
                                                            {{ monedaVenta[1] }}</td>
                                                    </tr>
                                                    <tr style="background-color: #CEECF5;">
                                                        <td colspan="4" style="text-align: right; font-weight: bold;">
                                                            Total Neto:</td>
                                                        <td id="montoTotal">{{ (calcularTotal *
                    parseFloat(monedaVenta[0])).toFixed(2) }} {{
                    monedaVenta[1] }}</td>
                                                    </tr>
                                                </tbody>
                                                <tbody v-else>
                                                    <tr>
                                                        <td colspan="5" style="text-align: center;">No hay artículos
                                                            agregados</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-show="step === 3" class="step-content">
                                <!-- Pagos Selection -->
                                <div class="d-flex justify-content-center mb-3">
                                    <div class="form-group">
                                        <div class="btn-group">
                                            <button class="btn btn-primary" @click="opcionPago = 'efectivo'">
                                                <i class="fa fa-money mr-2" aria-hidden="true"></i>
                                                Efectivo
                                            </button>
                                            <button class="btn btn-primary" @click="opcionPago = 'qr'">
                                                <i class="fa fa-qrcode mr-2" aria-hidden="true"></i>
                                                QR
                                            </button>
                                        </div>
                                    </div><br>
                                    <div v-if="opcionPago === 'efectivo'">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <form>
                                                            <div class="form-group">
                                                                <label for="montoEfectivo"><i
                                                                        class="fa fa-money mr-2"></i> Monto
                                                                    Recibido:</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">{{ monedaVenta[1]
                                                                            }}</span>
                                                                    </div>
                                                                    <input type="number" class="form-control"
                                                                        id="montoEfectivo" v-model="recibido"
                                                                        placeholder="Ingrese el monto recibido" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="cambioRecibir"><i
                                                                        class="fa fa-exchange mr-2"></i> Cambio a
                                                                    Entregar:</label>
                                                                <input type="text" class="form-control"
                                                                    id="cambioRecibir"
                                                                    placeholder="Se calculará automáticamente"
                                                                    :value="recibido - calcularTotal * parseFloat(monedaVenta[0])"
                                                                    readonly />
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="mb-3">
                                                            <h5 class="mb-0">Detalle de Venta</h5>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <span><i class="fa fa-dollar mr-2"></i> Monto Total:</span>
                                                            <span class="font-weight-bold">{{ (calcularTotal *
                    parseFloat(monedaVenta[0])).toFixed(2)
                                                                }}
                                                                {{
                    monedaVenta[1] }}</span>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <span><i class="fa fa-money mr-2"></i> Total a Pagar:</span>
                                                            <span class="font-weight-bold h5">{{ (calcularTotal *
                    parseFloat(monedaVenta[0])).toFixed(2)
                                                                }} {{
                    monedaVenta[1] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" @click="aplicarDescuento"
                                                    class="btn btn-success btn-block">
                                                    <i class="fa fa-check mr-2"></i> Registrar Pago
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else-if="opcionPago === 'qr'">
                                        <div class="container">
                                            <div class="row justify-content-center">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <input v-model="alias" readonly style="display: none;" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="montoEfectivo">Monto:</label>
                                                        <span class="font-weight-bold">{{ montoEfectivo =
                (calcularTotal).toFixed(2) }}</span>
                                                    </div>
                                                    <button class="btn btn-primary mb-2" @click="generarQr">Generar
                                                        QR</button>
                                                    <div v-if="qrImage" class="mb-2 text-center">
                                                        <img :src="qrImage" alt="Código QR" class="img-fluid" />
                                                    </div>
                                                    <button class="btn btn-secondary mb-2" @click="verificarEstado"
                                                        v-if="qrImage">Verificar
                                                        Estado
                                                        de
                                                        Pago</button>
                                                    <div v-if="estadoTransaccion" class="card p-2">
                                                        <div class="font-weight-bold">Estado Actual:</div>
                                                        <div>
                                                            <span :class="'badge badge-' + badgeSeverity">{{
                    estadoTransaccion.objeto.estadoActual
                }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" @click="registrarVenta(7)"
                                                    class="btn btn-success btn-block">
                                                    <i class="fa fa-check mr-2"></i> Registrar Pago
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="buttons d-flex justify-content-center">
                                <button class="btn btn-primary mr-2" @click="prevStep"
                                    :disabled="step === 1">Anterior</button>
                                <button class="btn btn-primary" @click="validarYAvanzar"
                                    :disabled="step === 3">Siguiente</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal " tabindex="-1" :class="{ mostrar: modal }" role="dialog"
                    aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" v-text="tituloModal"></h4>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div>
                                    <b-tabs content-class="mt-3">
                                        <b-tab title="Articulos" active>
                                            <template>
                                                <div class="form-group row">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <select class="form-control col-md-3" v-model="criterioA">
                                                                <option value="nombre">Nombre</option>
                                                                <option value="descripcion">Descripción</option>
                                                                <option value="codigo">Código</option>
                                                            </select>
                                                            <input type="text" v-model="buscarA"
                                                                @keyup="listarArticulo(buscarA, criterioA)"
                                                                class="form-control" placeholder="Texto a buscar" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Opciones</th>
                                                                <th>Código</th>
                                                                <th>Nombre</th>
                                                                <th>Categoría</th>
                                                                <th>Precio Venta</th>
                                                                <th>Stock</th>
                                                                <th>Estado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                                                <td>
                                                                    <button type="button"
                                                                        @click="agregarDetalleModal(articulo)"
                                                                        class="btn btn-success btn-sm">
                                                                        <i class="icon-check"></i>
                                                                    </button>
                                                                </td>
                                                                <td v-text="articulo.codigo"></td>
                                                                <td v-text="articulo.nombre"></td>
                                                                <td v-text="articulo.nombre_categoria"></td>
                                                                <td>
                                                                    {{
                    (
                        articulo.precio_venta *
                        parseFloat(monedaVenta[0])
                    ).toFixed(2)
                }}
                                                                    {{ monedaVenta[1] }}
                                                                </td>
                                                                <td v-text="articulo.saldo_stock"></td>
                                                                <td>
                                                                    <div v-if="articulo.condicion">
                                                                        <span class="badge badge-success">Activo</span>
                                                                    </div>
                                                                    <div v-else>
                                                                        <span
                                                                            class="badge badge-danger">Desactivado</span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </template>
                                        </b-tab>



                                    </b-tabs>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">
                                    Cerrar
                                </button>
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-primary"
                                    @click="registrarPersona()">
                                    Guardar
                                </button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-primary"
                                    @click="actualizarPersona()">
                                    Actualizar
                                </button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            </div>
        </template>
        <!--##################---HASTA AQUI---####################-->
    </main>
</template>

<script>
import vSelect from "vue-select";
import { TileSpinner } from "vue-spinners";

export default {
    data() {
        return {
            step: 1,
            modal2: false,
            opcionPago: "",
            modal: false,
            zIndexBase: 1050,
            //qr
            alias: '',
            montoQR: 0,
            qrImage: '',
            aliasverificacion: '',
            estadoTransaccion: null,
            currency: 'BOB', // Define tu moneda
            resivo: "",
            clienteDeudas: 0,
            arrayCuotas: [],
            arraySeleccionado: [],
            cuotaSeleccionada: null,
            modalCuotas: 0,

            tipo_pago: "",
            criterioKit: "nombre",
            buscarKit: "",

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
            permitirDevolucion: "",
            saldosNegativos: 1,
            venta_id: 0,
            idcliente: 0,
            usuarioAutenticado: null,
            puntoVentaAutenticado: null,
            idsucursalAutenticado:  null,
            cliente: "",
            email: "",
            nombreCliente: "",
            nombreClienteEditable: false,
            documento: "",
            tipo_documento: "1",
            complemento_id: "",
            descuentoAdicional: 0.0,
            descuentoGiftCard: "",
            tipo_comprobante: "RESIVO",
            serie_comprobante: "",
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
            tituloModal: "",
            tipoAccion: 0,
            errorVenta: 0,
            errorMostrarMsjVenta: [],
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
            },
            offset: 3,
            criterio: "num_comprobante",
            buscar: "",
            criterioA: "nombre",
            buscarA: "",
            arrayArticulo: [],
            arraySeleccionado: [],
            idarticulo: 0,
            codigo: "",
            articulo: "",
            medida: "",
            codigoClasificador: "",
            codigoProductoSin: "",
            precio: 0,
            unidad_envase: 0,
            cantidad: 1,
            paquni: "",
            precioBloqueado: false,
            descuento: 0,
            descuentoProducto: 0,
            sTotal: 0,
            stock: 0,
            valorMaximoDescuento: "",
            mostrarDireccion: true,

            casosEspeciales: false,
            mostrarCampoCorreo: false,
            leyendaAl: "",
            codigoExcepcion: 0,
            mostrarSpinner: false,
            primer_precio_cuota: 0,
            numeroTarjeta: null,
            metodoPago: "",
            criterioVenta: "ci",
            //almacenes
            arrayAlmacenes: [],
            idAlmacen: null,
            //-----PRECIOS- AUMENTE 3/OCTUBRE--------
            precioseleccionado: "",
            //precio : '',
            arrayPrecios: [],
            nombre_precio: "",
            precio_uno: "",
            precio_dos: "",
            precio_tres: "",
            precio_cuatro: "",
            //-----MODAL 2---
            modal2: 0,
            tituloModal2: "",
            tipoAccion2: "",

            modal3: 0,
            tituloModal3: "",
            tipoAccion3: "",

            recibido: 0,
            efectivo: 0,
            cambio: 0,
            faltante: 0,
            cantidadClientes: 0,
            idtipo_pago: "",
            idtipo_venta: 1,
            tiempo_diaz: "",
            numero_cuotas: "",
            cuotas: [], //---para almacenar las fechas
            estadocrevent: "activo",
            primera_cuota: "",
            habilitarPrimeraCuota: false,
            tipoPago: "EFECTIVO",
            tiposPago: {
                EFECTIVO: 1,
                TARJETA: 2,
                QR: 3,
            },
        };
    },
    watch: {
        codigo(newValue) {
            if (newValue) {
                this.buscarArticulo();
            }
        },
        documento(newDocumento) {
            this.mostrarCampoCorreo =
                newDocumento === "99002" || newDocumento === "99003";
        },
    },
    components: {
        TileSpinner,
        vSelect,
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

            var to = from + this.offset * 2;
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
                resultado +=
                    this.arrayDetalle[i].precioseleccionado *
                    this.arrayDetalle[i].cantidad -
                    (this.arrayDetalle[i].precioseleccionado *
                        this.arrayDetalle[i].cantidad *
                        this.arrayDetalle[i].descuento) /
                    100;
            }
            resultado -= this.descuentoAdicional;
            //resultado -= this.descuentoGiftCard;
            return resultado;
        },

        calcularSubTotal: function () {
            var resultado = 0.0;
            for (var i = 0; i < this.arrayDetalle.length; i++) {
                resultado =
                    resultado +
                    (this.arrayDetalle[i].precioseleccionado *
                        this.arrayDetalle[i].cantidad -
                        (this.arrayDetalle[i].precioseleccionado *
                            this.arrayDetalle[i].cantidad *
                            this.arrayDetalle[i].descuento) /
                        100);
            }
            return resultado;
        },
        badgeSeverity() {
            if (this.estadoTransaccion && this.estadoTransaccion.objeto.estadoActual === 'PENDIENTE') {
                return 'danger'; // Rojo para estado PENDIENTE
            } else if (this.estadoTransaccion && this.estadoTransaccion.objeto.estadoActual === 'PAGADO') {
                return 'success'; // Verde para estado PAGADO
            } else {
                return 'info'; // Otros estados
            }
        }
    },

    methods: {
        validarYAvanzar() {
            const errores = [];

            if (this.step === 2) {
                if (!this.idAlmacen) errores.push('Seleccione un almacén');
            }

            if (errores.length > 0) {
                const mensaje = errores.join('\n');
                swal('Campos incompletos', mensaje, 'warning');
            } else {
                this.nextStep();
            }
        },
        abrirModal() {
            this.showModal = true;
        },
        cerrarModal2() {
            this.modal2 = false;
        },
        nextStep() {
            if (this.step < 3) {
                this.step++;
            }
        },
        prevStep() {
            if (this.step > 1) {
                this.step--;
            }
        },

        actualizarFechaHora() {
            const now = new Date();
            this.alias = now.toLocaleString();
        },
        verificarEstado() {
            axios.post('/qr/verificarestado', {
                alias: this.aliasverificacion,
            })
                .then(response => {
                    this.estadoTransaccion = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },

        generarQr() {
            this.aliasverificacion = this.alias;
            axios.post('/qr/generarqr', {
                alias: this.alias,
                monto: this.calcularTotal
            })
                .then(response => {
                    const imagenBase64 = response.data.objeto.imagenQr;
                    this.qrImage = `data:image/png;base64,${imagenBase64}`;
                })
                .catch(error => {
                    console.error(error);
                });

            this.alias = '';
            this.montoQR = 0;
        },


        calcularPrecioUnitario(articulo) {
            // Lógica para calcular el precio unitario según el rango total de cantidades
            if (
                this.totalCantidades >= this.datosFormularioPE.rango_inicio_r1 &&
                this.totalCantidades <= this.datosFormularioPE.rango_final_r1
            ) {
                return this.datosFormularioPE.precio_r1;
            } else if (
                this.totalCantidades >= this.datosFormularioPE.rango_inicio_r2 &&
                this.totalCantidades <= this.datosFormularioPE.rango_final_r2
            ) {
                return this.datosFormularioPE.precio_r2;
            } else if (
                this.totalCantidades >= this.datosFormularioPE.rango_inicio_r3 &&
                this.totalCantidades <= this.datosFormularioPE.rango_final_r3
            ) {
                return this.datosFormularioPE.precio_r3;
            } else {
                // Precio predeterminado si no está en ningún rango
                return articulo.precio_costo_unid;
            }
        },
        getClassByCantidad(total) {
            if (
                total >= this.datosFormularioPE.rango_inicio_r1 &&
                total <= this.datosFormularioPE.rango_final_r1
            ) {
                return "rango-1"; // clase para el rango 1
            } else if (
                total >= this.datosFormularioPE.rango_inicio_r2 &&
                total <= this.datosFormularioPE.rango_final_r2
            ) {
                return "rango-2"; // clase para el rango 2
            } else if (
                total >= this.datosFormularioPE.rango_inicio_r3 &&
                total <= this.datosFormularioPE.rango_final_r3
            ) {
                return "rango-3"; // clase para el rango 3
            } else {
                return ""; // clase por defecto si no se cumple ningún rango
            }
        },
        abrirTipoVenta() {
            if (this.idtipo_venta == 1) {
                this.modal2 = 1;
                this.cliente = this.nombreCliente;
                this.tipoAccion2 = 1;
                this.scrollToTop();
            }
        },

        seleccionarTipoPago(tipo) {
            console.log("TIPO PAGO ", tipo)
            this.tipoPago = tipo;
            this.tituloModal2 = `TIPO DE PAGO : ${tipo}`;
            this.idtipo_pago = this.tiposPago[tipo];
        },

        agregarKit(kit) {
            if (new Date(kit.fecha_final) < new Date()) {
                swal({
                    type: "error",
                    title: "Error...",
                    text: "Este kit ha expirado!",
                });
                return;
            }
            //   this.GetValidateKit(kit['id'])
            this.GetValidateKit(kit["id"])
                .then(() => {
                    if (this.mensajesKit.length == 0) {
                        const totalKit = this.arrayArticulosKit.reduce(
                            (total, producto) => {
                                return total + producto.cantidad * producto.precio_costo_unid;
                            },
                            0
                        );
                        this.arrayArticulosKit.forEach((producto) => {
                            producto.porcentaje =
                                ((producto.cantidad * producto.precio_costo_unid) / totalKit) *
                                100;
                        });

                        this.arrayArticulosKit.forEach((producto) => {
                            producto.nuevo_precio = (kit.precio * producto.porcentaje) / 100;
                        });
                        console.log("Estos son los articulos: ", this.arrayArticulosKit);
                        this.arrayArticulosKit.forEach((articulo) => {
                            this.arrayDetalle.push({
                                idkit: kit["id"],
                                idarticulo: articulo.id,
                                articulo: articulo.nombre,
                                medida: "KIT",
                                unidad_envase: articulo.unidad_envase,
                                cantidad: articulo.cantidad,
                                cantidad_paquetes: articulo.unidad_envase * articulo.cantidad,
                                precio: articulo.nuevo_precio,
                                descuento: 0,
                                stock: articulo.stock,
                                precioseleccionado: articulo.precio_costo_unid,
                            });
                            let actividadEconomica = 461021;

                            this.arrayProductos.push({
                                actividadEconomica: actividadEconomica,
                                codigoProductoSin: articulo.id,
                                codigoProducto: articulo.codigo,
                                descripcion: articulo.nombre,
                                cantidad: articulo.cantidad,
                                unidadMedida: 25,
                                precioUnitario: parseFloat(
                                    articulo.precio_costo_unid * this.monedaVenta[0]
                                ).toFixed(2),
                                montoDescuento: (
                                    articulo.precio_costo_unid *
                                    articulo.cantidad *
                                    this.monedaVenta[0] -
                                    articulo.nuevo_precio * this.monedaVenta[0]
                                ).toFixed(2),
                                subTotal: parseFloat(
                                    articulo.nuevo_precio * this.monedaVenta[0]
                                ).toFixed(2),
                                numeroSerie: null,
                                numeroImei: null,
                            });
                            this.cerrarModal();
                        });

                    } else {
                        swal({
                            type: "error",
                            title: "Stock insuficiente",
                            text: this.mensajesKit.join("\n\n"),
                        });
                    }
                })
                .catch((error) => {
                    // Maneja el error aquí
                    console.error(error);
                });
        },

        agregarPE(kit) {
            console.log("esto:", kit);
            kit["articulos"] = this.arrayArticulosKit;
            kit["precio"] = kit["precio"] / parseFloat(this.monedaVenta[0]);
            axios.put("/ofertasespeciales/actualizar", kit);

            this.modalDetalle = 0;
            if (new Date(kit.fecha_final) < new Date()) {
                swal({
                    type: "error",
                    title: "Error...",
                    text: "Este kit ha expirado!",
                });
                return;
            }
            console.log("datos formulario agregar PE", kit);
            //   this.GetValidateKit(kit['id'])
            this.GetValidateKit(kit["id"])
                .then(() => {
                    if (this.mensajesKit.length == 0) {
                        const totalKit = this.arrayArticulosKit.reduce(
                            (total, producto) => {
                                return total + producto.cantidad * producto.precio_costo_unid;
                            },
                            0
                        );
                        this.arrayArticulosKit.forEach((producto) => {
                            producto.porcentaje =
                                ((producto.cantidad * producto.precio_costo_unid) / totalKit) *
                                100;
                        });
                        console.log("precio especial ", this.arrayArticulosKit);
                        this.arrayArticulosKit.forEach((producto) => {
                            producto.nuevo_precio =
                                (this.calcularPrecioUnitario(kit) * producto.porcentaje) / 100;
                        });
                        console.log("Estos son los articulos: ", this.arrayArticulosKit);
                        this.arrayArticulosKit.forEach((articulo) => {
                            this.arrayDetalle.push({
                                idkit: kit["id"],
                                idarticulo: articulo.id,
                                articulo: articulo.nombre,
                                medida: "KIT",
                                unidad_envase: articulo.unidad_envase,
                                cantidad: articulo.cantidad,
                                cantidad_paquetes: articulo.unidad_envase * articulo.cantidad,
                                precio: articulo.nuevo_precio,
                                descuento: 0,
                                stock: articulo.stock,
                                precioseleccionado: this.calcularPrecioUnitario(articulo),
                            });
                            let actividadEconomica = 461021;

                            this.arrayProductos.push({
                                actividadEconomica: actividadEconomica,
                                codigoProductoSin: articulo.id,
                                codigoProducto: articulo.codigo,
                                descripcion: articulo.nombre,
                                cantidad: articulo.cantidad,
                                unidadMedida: 25,
                                precioUnitario: parseFloat(
                                    this.calcularPrecioUnitario(articulo) * this.monedaVenta[0]
                                ).toFixed(2),
                                montoDescuento: (
                                    articulo.precio_costo_unid *
                                    articulo.cantidad *
                                    this.monedaVenta[0] -
                                    articulo.nuevo_precio * this.monedaVenta[0]
                                ).toFixed(2),
                                subTotal: parseFloat(
                                    articulo.nuevo_precio * this.monedaVenta[0]
                                ).toFixed(2),
                                numeroSerie: null,
                                numeroImei: null,
                            });
                            this.cerrarModal();
                        });
                    } else {
                        swal({
                            type: "error",
                            title: "Stock insuficiente",
                            text: this.mensajesKit.join("\n\n"),
                        });
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
                id: data["id"],
                nombre: data["nombre"],
                porcentaje: data["porcentaje"],
                codigo: data["codigo"],

                fecha_final: new Date(data["fecha_final"]).toISOString().split("T")[0],
                tipo_promocion: data["tipo_promocion"],
                estado: data["estado"],
                precio: data["precio"],
            };
            this.obtenerDatosKit(data["id"]);
        },

        abrirModalDetalles(data) {
            this.arrayArticulosSeleccionados = [];

            this.modalDetalle = 1;
            this.datosFormularioPE = {
                id: data["id"],
                nombre: data["nombre"],
                precio_r1: data["precio_r1"],
                rango_inicio_r1: data["rango_inicio_r1"],
                rango_final_r1: data["rango_final_r1"],
                precio_r2: data["precio_r2"],
                rango_inicio_r2: data["rango_inicio_r2"],
                rango_final_r2: data["rango_final_r2"],
                precio_r3: data["precio_r3"],
                rango_inicio_r3: data["rango_inicio_r3"],
                rango_final_r3: data["rango_final_r3"],

                fecha_final: new Date(data["fecha_final"]).toISOString().split("T")[0],
                tipo_promocion: data["tipo_promocion"],
                estado: data["estado"],
            };
            this.obtenerDatosKit(data["id"]), console.log(this.datosFormularioPE);
        },

        obtenerDatosKit(idPromocion) {
            return axios
                .get("/ofertas/id", {
                    params: {
                        idPromocion: idPromocion,
                    },
                })
                .then((response) => {
                    const datos = response.data.articulosPorPromocion;
                    this.arrayArticulosKit = datos.map((item) => ({
                        ...item.articulo,
                        cantidad: item.cantidad,
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
                return "#ff0000";
            }
            switch (estado) {
                case "Activo":
                    return "#5ebf5f";
                case "Inactivo":
                    return "#d76868";
                case "Agotado":
                    return "#ce4444";
                default:
                    return "#f9dda6";
            }
        },



        listarOfertaEspecial(page, buscar, criterio) {
            let me = this;
            let url = "/ofertasespeciales";

            axios
                .get(url, {
                    params: {
                        page: page,
                        buscar: buscar,
                        criterio: criterio,
                    },
                })
                .then(function (response) {
                    let respuesta = response.data;
                    me.arrayPreciosEspeciales = response.data.ofertas.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        scrollToSection() {
            $("html, body").animate(
                {
                    scrollTop: $("#seccionObjetivo").offset().top,
                },
                50
            );
        },
        scrollToTop() {
            $("html, body").animate(
                {
                    scrollTop: 0,
                },
                50
            );
        },
        calcularPrecioConDescuento(precioOriginal, porcentajeDescuento) {

            const descuento =
                this.precioseleccionado * (this.descuentoProducto / 100);
            const precioConDescuento = this.precioseleccionado - descuento;
            const precioFinal =
                precioConDescuento * this.unidadPaquete * this.cantidad;
            return precioFinal;
        },
        calcularDiasRestantes(fechaFinal) {
            const fechaActual = new Date();
            const fechaObjetivo = new Date(fechaFinal);
            const diferenciaEnMilisegundos = fechaObjetivo - fechaActual;
            const diasRestantes = Math.ceil(
                diferenciaEnMilisegundos / (1000 * 60 * 60 * 24)
            );
            return diasRestantes;
        },
        actualizarDetalle(index) {
            this.arrayDetalle[index].total = (
                this.arrayDetalle[index].precioseleccionado *
                this.arrayDetalle[index].cantidad
            ).toFixed(2);
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

            axios
                .get(`/promocion/id?idArticulo=${idArticulo}`)
                .then((response) => {
                    this.arrayPromocion = response.data.promocion;
                })
                .catch((error) => {
                    // Maneja los errores aquí
                    console.error("Error:", error);
                });
        },

        async obtenerDatosUsuario() {
            try {
                const response = await axios.get('/venta');
                this.usuarioAutenticado = response.data.usuario.usuario;
                this.usuario_autenticado = this.usuarioAutenticado;
                this.idrol = response.data.usuario.idrol;
                this.idsucursalAutenticado = response.data.usuario.idsucursal;
                console.log("Obtener Datos Usuario: " + this.idsucursalAutenticado);
                this.puntoVentaAutenticado = response.data.codigoPuntoVenta;
            } catch (error) {
                console.error(error);
            }
        },

        async obtenerDatosSesionYComprobante() {
            try {
                const idsucursal = this.idsucursalAutenticado;
                console.log("El idsucursal es: " + idsucursal);
                const response = await axios.get('/obtener-ultimo-comprobante', {
                    params: {
                        idsucursal: idsucursal
                    }
                });
                const lastComprobante = response.data.last_comprobante;
                this.last_comprobante = lastComprobante;
                console.log("El ultimo comprobante es: " + this.last_comprobante);
                this.nextNumber(lastComprobante);
            } catch (error) {
                console.error('Error al obtener el último comprobante:', error);
            }
        },

        async ejecutarFlujoCompleto() {
            await this.obtenerDatosUsuario();
            await this.obtenerDatosSesionYComprobante();
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
            var url =
                "/venta?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    console.log(respuesta);
                    me.arrayVenta = respuesta.ventas.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        selectCliente(numero) {
            let me = this;
            var url = "/cliente/selectClientePorNumero?numero=" + numero;
            axios
                .get(url)
                .then(function (response) {
                    let respuesta = response.data;
                    q: numero;
                    me.arrayCliente = respuesta.clientes;
                    console.log(me.arrayCliente);
                    me.cantidadClientes = me.arrayCliente.length;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        selectClienteNombre(nombre) {
            console.log("nombre ", nombre)
            let me = this;
            var url = "/cliente/selectCliente?filtro=" + nombre;
            axios
                .get(url)
                .then(function (response) {
                    let respuesta = response.data;
                    q: nombre;
                    me.arrayCliente = respuesta.clientes;
                    console.log(me.arrayCliente);
                    me.cantidadClientes = me.arrayCliente.length;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        getDatosCliente(val1) {
            let me = this;
            if (val1 !== null) {
                me.loading = true;
                me.idcliente = val1.id;
                //console.log(val1);
                this.email = val1.email;
                this.nombreCliente = val1.nombre;
                this.documento = val1.num_documento;
                this.tipo_documento = val1.tipo_documento;
                this.complemento_id = val1.complemento_id;
                this.clienteDeudas = val1.cantidad_creditos;
            }
            else {
                //console.log(val1);
                this.email = '';
                this.nombreCliente = '';
                this.documento = '';
                this.tipo_documento = '';
                this.complemento_id = '';
                this.clienteDeudas = '';
            }
        },
        getDatosCliente2(val1) {
            let me = this;
            me.loading = true;
            if (val1 !== null) {
                me.loading = true;
                me.idcliente = val1.id;
                //console.log(val1);
                this.email = val1.email;
                this.nombreCliente = val1.nombre;
                this.documento = val1.num_documento;
                this.tipo_documento = val1.tipo_documento;
                this.complemento_id = val1.complemento_id;
                this.clienteDeudas = val1.cantidad_creditos;
            }
            else {
                //console.log(val1);
                this.email = '';
                this.nombreCliente = '';
                this.documento = '';
                this.tipo_documento = '';
                this.complemento_id = '';
                this.clienteDeudas = '';
            }
        },
        buscarArticulo() {
            clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                let me = this;
                var url = "/articulo/buscarArticuloVenta?filtro=" + me.codigo;

                axios
                    .get(url)
                    .then(function (response) {
                        let respuesta = response.data;
                        me.arraySeleccionado = respuesta.articulos[0];
                        console.log(me.arraySeleccionado);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }, 1000);
        },
        pdfVenta(id) {
            window.open("/venta/pdf/" + id, "_blank");
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

            indicesEliminar.forEach((index) => {
                this.arrayProductos.splice(index, 1);
            });

            for (let i = indicesEliminar.length - 1; i >= 0; i--) {
                this.arrayDetalle.splice(indicesEliminar[i], 1);
            }
        },

        agregarDetalle() {
            let actividadEconomica = 461021;
            let numeroSerie = null;
            let numeroImei = null;
            let descuento = (
                this.precioseleccionado *
                this.cantidad *
                (this.descuentoProducto / 100)
            ).toFixed(2);

            if (this.encuentra(this.arraySeleccionado.id)) {
                swal({
                    type: "error",
                    title: "Error...",
                    text: "Este Artículo ya se encuentra agregado!",
                });
            } else {
                if (
                    this.saldosNegativos === 0 &&
                    this.arraySeleccionado.saldo_stock <
                    this.cantidad * this.unidadPaquete
                ) {
                    swal({
                        type: "error",
                        title: "Error...",
                        text: "No hay stock disponible!",
                    });
                    return;
                }

                const precioArticulo =
                    this.calcularPrecioConDescuento(
                        this.resultadoMultiplicacion,
                        this.arrayPromocion ? this.arrayPromocion.porcentaje : 0
                    ) * this.monedaVenta[0];
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
                    precioseleccionado: this.precioseleccionado,
                });
                console.log(this.arrayDetalle);
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
                    numeroImei: numeroImei,
                });
                console.log("pa la factura: ", this.arrayProductos);
                this.precioBloqueado = true;
                this.arraySeleccionado = [];
                this.cantidad = 1;
                this.unidadPaquete = 1;
                this.codigo = "";
                this.descuentoProducto = 0;
            }
        },

        agregarDetalleModal(data) {
            //this.scrollToSection();
            this.codigo = data.codigo;
            console.log("SLECCIONE ESTO:", data);

            this.buscarPromocion(data.id);
            this.precioseleccionado = data.precio_uno;

            this.cerrarModal();
        },
        eliminarSeleccionado() {
            this.codigo = "";
            this.arraySeleccionado = [];
        },
        listarArticulo(buscar, criterio) {
            let me = this;
            var url =
                "/articulo/listarArticuloVenta?buscar=" +
                buscar +
                "&criterio=" +
                criterio +
                "&idAlmacen=" +
                this.idAlmacen;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayArticulo = respuesta.articulos;
                    console.log("listar articulo", respuesta);
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        datosConfiguracion() {
            let me = this;
            var url = "/configuracion";

            axios
                .get(url)
                .then(function (response) {
                    let respuesta = response.data;
                    console.log(respuesta);
                    me.saldosNegativos = respuesta.configuracionTrabajo.saldosNegativos;
                    me.permitirDevolucion =
                        respuesta.configuracionTrabajo.permitirDevolucion;
                    me.monedaVenta = [
                        respuesta.configuracionTrabajo.valor_moneda_venta,
                        respuesta.configuracionTrabajo.simbolo_moneda_venta,
                    ];
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        
        selectAlmacen() {
            let me = this;
            let url = "/almacen/selectAlmacen";
            axios
                .get(url)
                .then(function (response) {
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

            if (me.tipo_comprobante == 0)
                me.errorMostrarMsjVenta.push("Seleccione el Comprobante");
            if (!me.impuesto)
                me.errorMostrarMsjVenta.push("Ingrese el impuesto de compra");
            if (me.arrayDetalle.length <= 0)
                me.errorMostrarMsjVenta.push("Ingrese detalles");

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
            const descuentoGiftCard = this.descuentoGiftCard;
            const idtipo_pago = descuentoGiftCard ? 40 : 2;

            this.registrarVenta(idtipo_pago);
        },

        otroMetodo(metodoPago) {
            const idtipo_pago = metodoPago;
            this.registrarVenta(idtipo_pago);
        },
        emitirComprobante() {
            if (!this.tipo_comprobante) {
                alert("Por favor seleccione un tipo de comprobante.");
                return;
            }

            if (this.tipo_comprobante === "RESIVO") {
                this.emitirResivo();
            } else if (this.tipo_comprobante === "FACTURA") {
                this.emitirFactura();
            }
        },
        async emitirResivo(idVentaRecienRegistrada) {
            let me = this;

            let idventa = idVentaRecienRegistrada;
            let numeroResivo = document.getElementById("num_comprobante").value;
            let id_cliente = document.getElementById("idcliente").value;
            let nombreRazonSocial = document.getElementById("nombreCliente").value;
            let numeroDocumento = document.getElementById("documento").value;
            let complemento = document.getElementById("complemento_id").value;
            let tipoDocumentoIdentidad = document.getElementById("tipo_documento")
                .value;
            let montoTotal = (
                this.calcularTotal * parseFloat(this.monedaVenta[0])
            ).toFixed(2);
            let usuario = document.getElementById("usuarioAutenticado").value;

            try {
                const response = await axios.get("/resivo/obtenerLeyendaAleatoria");
                this.leyendaAl = response.data.descripcionLeyenda;
                console.log("El dato de leyenda llegado es: " + this.leyendaAl);
            } catch (error) {
                console.error(error);
                return '"Ley N° 453: Los servicios deben suministrarse en condiciones de inocuidad, calidad y seguridad."';
            }

            var resivo = [];
            resivo.push({
                cabecera: {
                    municipio: "Cochabamba",
                    telefono: "77490451",
                    numeroResivo: numeroResivo,
                    codigoSucursal: 0,
                    direccion: "Av. Ejemplo 123",
                    codigoPuntoVenta: 0,
                    fechaEmision: new Date().toISOString().slice(0, -1),
                    nombreRazonSocial: nombreRazonSocial,
                    codigoTipoDocumentoIdentidad: tipoDocumentoIdentidad,
                    numeroDocumento: numeroDocumento,
                    complemento: complemento,
                    codigoCliente: numeroDocumento,
                    montoTotal: montoTotal,
                    codigoMoneda: 1,
                    tipoCambio: 1,
                    montoTotalMoneda: montoTotal,
                    usuario: usuario,
                    leyenda: this.leyendaAl,
                },
            });
            me.arrayProductos.forEach(function (prod) {
                resivo.push({ detalle: prod });
            });

            var datos = { resivo };

            axios
                .post("/venta/emitirResivo", {
                    resivo: datos,
                    id_cliente: id_cliente,
                    idventa: idventa,
                })
                .then(function (response) {
                    var data = response.data;

                    if (data === "VALIDADA") {
                        swal("RESIVO VALIDADO", "Correctamente", "success");
                        me.arrayProductos = [];
                        me.cerrarModal2();
                        me.cerrarModal3();
                        me.listarVenta(1, "", "id");
                        me.mostrarSpinner = false;
                    } else {
                        me.arrayProductos = [];
                        me.cerrarModal2();
                        me.cerrarModal3();
                        me.listarVenta(1, "", "id");
                        me.mostrarSpinner = false;
                        swal("RESIVO VALIDADO", "éxito", "success");
                    }
                })
                .catch(function (error) {
                    me.arrayProductos = [];
                    swal("INTENTE DE NUEVO", "Comunicacion fallida", "error");
                    me.mostrarSpinner = false;
                });
        },

        imprimirResivo(id) {
            swal({
                title: "Selecciona un tamaño para imprimir el recibo",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "CARTA",
                cancelButtonText: "ROLLO",
                reverseButtons: true,
            })
                .then((result) => {
                    if (result.value) {
                        console.log("Se seleccionó imprimir en CARTA");
                        axios
                            .get("/resivo/imprimirCarta/" + id, {
                                responseType: "blob",
                            })
                            .then(function (response) {
                                const url = window.URL.createObjectURL(
                                    new Blob([response.data])
                                );
                                const link = document.createElement("a");
                                link.href = url;
                                link.setAttribute("download", "recibo_carta.pdf");
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);
                                console.log("Se imprimió el recibo en CARTA correctamente");
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    } else if (result.dismiss === swal.DismissReason.cancel) {
                        console.log("Se seleccionó imprimir en ROLLO");
                        axios
                            .get("/resivo/imprimirRollo/" + id, {
                                responseType: "blob",
                            })
                            .then(function (response) {
                                const url = window.URL.createObjectURL(
                                    new Blob([response.data])
                                );
                                const link = document.createElement("a");
                                link.href = url;
                                link.setAttribute("download", "recibo_rollo.pdf");
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);
                                console.log("Se imprimió el recibo en ROLLO correctamente");
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    }
                    s;
                })
                .catch((error) => {
                    console.error("Error al mostrar el diálogo:", error);
                });
        },
        async registrarVenta(idtipo_pago) {
            if (this.validarVenta()) {
                return;
            }
            console.log(" REGISTRANDO VENTA")
            let me = this;
            this.mostrarSpinner = false;
            this.idtipo_pago = idtipo_pago;

            for (let i = 0; i < me.cuotas.length; i++) {
                console.log("LLEGA ARRAYDATA!", me.cuotas[i]);
            }
            console.log("hola");
            console.log(this.primer_precio_cuota);
            if (this.tipo_comprobante === "RESIVO") {
                const response = await axios.get(`/api/clientes/existe?documento=${this.documento}`);
                if (!response.data.existe) {
                    const nuevoClienteResponse = await axios.post('/cliente/registrar', {
                        'nombre': this.nombreCliente,
                        'num_documento': this.documento,
                        'tipo_documento': '5'
                    });
                    this.idcliente = nuevoClienteResponse.data.id;
                } else {
                    this.idcliente = response.data.cliente.id;
                }
                axios
                    .post("/venta/registrar", {
                        idcliente: this.idcliente,
                        resivo: this.resivo, // Campo adicional para RESIVO
                        tipo_comprobante: this.tipo_comprobante,
                        serie_comprobante: this.serie_comprobante, // Agregado para prueba
                        num_comprobante: this.num_comprob, // Agregado para prueba
                        impuesto: this.impuesto,
                        total: this.calcularTotal,
                        idAlmacen: this.idAlmacen,
                        idtipo_pago: idtipo_pago,
                        idtipo_venta: this.idtipo_venta,
                        data: this.arrayDetalle,
                    })
                    .then((response) => {
                        if (response.data.id > 0) {
                            // Restablecer valores después de una venta exitosa
                            me.listado = 1;
                            me.ejecutarFlujoCompleto();
                            me.listarVenta(1, "", "num_comprob");
                            me.cerrarModal2();
                            me.cerrarModal3();
                            this.imprimirResivo(response.data.id);
                            me.idproveedor = 0;
                            me.tipo_comprobante = "FACTURA";
                            me.nombreCliente = "";
                            me.idcliente = 0;
                            me.tipo_documento = 0;
                            me.complemento_id = "";
                            me.documento = "";
                            me.imagen = "";
                            me.serie_comprobante = "";
                            me.num_comprob = "";
                            me.impuesto = 0.18;
                            me.total = 0.0;
                            me.idarticulo = 0;
                            me.articulo = "";
                            me.cantidad = 0;
                            me.precio = 0;
                            me.stock = 0;
                            me.codigo = "";
                            me.descuento = 0;
                            me.arrayDetalle = [];
                            me.primer_precio_cuota = 0;
                            me.step = 1;
                            me.recibido = 0;
                        } else {
                            console.log(response);
                            me.ejecutarFlujoCompleto();
                            // Manejo de errores
                        }
                    })
                    .catch((error) => {
                        me.ejecutarFlujoCompleto();
                        console.log(error);
                    });
            } else {
                axios
                    .post("/venta/registrar", {
                        idcliente: this.idcliente,
                        tipo_comprobante: this.tipo_comprobante,
                        serie_comprobante: this.serie_comprobante,
                        num_comprobante: this.num_comprob,
                        impuesto: this.impuesto,
                        total: this.calcularTotal,
                        idAlmacen: this.idAlmacen,
                        idtipo_pago: idtipo_pago,
                        idtipo_venta: this.idtipo_venta,
                        idpersona: this.idcliente,
                        data: this.arrayDetalle,
                    })
                    .then((response) => {
                        let idVentaRecienRegistrada = response.data.id;
                        //this.emitirFactura(idVentaRecienRegistrada);

                        if (response.data.id > 0) {
                            // Restablecer valores después de una venta exitosa
                            me.listado = 1;
                            me.listarVenta(1, "", "num_comprob");
                            me.cerrarModal2();
                            me.cerrarModal3();
                            me.idproveedor = 0;
                            me.tipo_comprobante = "RESIVO";
                            me.nombreCliente = "";
                            me.idcliente = 0;
                            me.tipo_documento = 0;
                            me.complemento_id = "";
                            me.documento = "";
                            me.imagen = "";
                            me.serie_comprobante = "";
                            me.num_comprob = "";
                            me.impuesto = 0.18;
                            me.total = 0.0;
                            me.idarticulo = 0;
                            me.articulo = "";
                            me.cantidad = 0;
                            me.precio = 0;
                            me.stock = 0;
                            me.codigo = "";
                            me.descuento = 0;
                            me.arrayDetalle = [];
                            me.primer_precio_cuota = 0;

                            //window.open('/factura/imprimir/' + response.data.id);
                        } else {
                            console.log(response);
                            if (response.data.valorMaximo) {
                                //alert('El valor de descuento no puede exceder el '+ response.data.valorMaximo);
                                swal(
                                    "Aviso",
                                    "El valor de descuento no puede exceder el " +
                                    response.data.valorMaximo,
                                    "warning"
                                );
                                return
                            } else {
                                //alert(response.data.caja_validado);
                                swal("Aviso", response.data.caja_validado, "warning");
                                return;
                            }
                            //console.log(response.data.valorMaximo)
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },

        eliminarVenta(idVenta) {
            axios.delete('/venta/eliminarVenta/' + idVenta)
                .then(function (response) {
                    console.log('Venta eliminada correctamente:', response);
                })
                .catch(function (error) {
                    console.error('Error al eliminar la venta:', error);
                });
        },

        mostrarDetalle() {
            /*const idsucursal = this.idsucursalAutenticado;
            console.log("El idsucursal es: " + idsucursal);
            axios.get('/obtener-ultimo-comprobante', {
                params: {
                    idsucursal: idsucursal
                }
            })
            .then(response => {
                const lastComprobante = response.data.last_comprobante;
                this.last_comprobante = lastComprobante;
                this.nextNumber(lastComprobante);
            })
            .catch(error => {
                console.error('Error al obtener el último comprobante:', error);
            });*/
            let me = this;
            me.selectAlmacen();
            me.listado = 0;

            me.idproveedor = 0;
            me.tipo_comprobante = 'RESIVO';
            me.serie_comprobante = '';
            //me.nextNumber();
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
            var url = "/venta/obtenerCabecera?id=" + id;

            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    arrayVentaT = respuesta.venta;
                    console.log("VIENDO ", respuesta);

                    me.cliente = arrayVentaT[0]["nombre"];
                    me.tipo_comprobante = arrayVentaT[0]["tipo_comprobante"];
                    me.serie_comprobante = arrayVentaT[0]["serie_comprobante"];
                    me.num_comprobante = arrayVentaT[0]["num_comprobante"];
                    me.impuesto = arrayVentaT[0]["impuesto"];
                    me.total = arrayVentaT[0]["total"];
                })
                .catch(function (error) {
                    console.log(error);
                });

            //obtener datos de los detalles
            var url = "/venta/obtenerDetalles?id=" + id;

            axios
                .get(url)
                .then(function (response) {
                    //console.log(response);
                    var respuesta = response.data;
                    me.arrayDetalle = respuesta.detalles;
                    console.log(array);
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        cerrarModal() {
            this.modal = 0;
            this.tituloModal = "";
        },
        abrirModal() {
            this.scrollToTop();
            this.listarArticulo("", "nombre");

            this.selectAlmacen();
            this.arrayArticulo = [];
            this.modal = 1;
            this.tituloModal = "Seleccione los articulos que desee";
        },
        advertenciaFechaVencimiento() {
            swal({
                title: "No Disponible",
                text: "No puede seleccionar este producto porque esta vencido.",
                type: "warning",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Aceptar",
                confirmButtonClass: "btn btn-success",
                buttonsStyling: false,
            }).then(() => {
                this.cerrarModal();
            });
        },

        desactivarVenta(id) {
            swal({
                title: "Esta seguro de anular esta venta?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar!",
                cancelButtonText: "Cancelar",
                confirmButtonClass: "btn btn-success",
                cancelButtonClass: "btn btn-danger",
                buttonsStyling: false,
                reverseButtons: true,
            }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios
                        .put("/venta/desactivar", {
                            id: id,
                        })
                        .then(function (response) {
                            me.listarVenta(1, "", "num_comprobante");
                            swal(
                                "Anulado!",
                                "La venta ha sido anulado con éxito.",
                                "success"
                            );
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                }
            });
        },
        //-------------OBTENER PRECIOS Y MABRIR_MODAL----------
        listarPrecio() {
            let me = this;
            var url = "/precios";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayPrecios = respuesta.precio.data;
                    console.log("PRECIOS", me.arrayPrecios);
                    //me.precioCount = me.arrayBuscador.length;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        mostrarSeleccion() {
            console.log("Precio seleccionado:", this.precioseleccionado);
        },


        cerrarModal2() {
            this.modal2 = 0;
            this.tituloModal2 = "";
            this.idtipo_pago = "";
            this.tipoPago = "";
        },
        cerrarModal3() {
            this.modal3 = 0;
            this.tituloModal3 = "";
            this.numero_cuotas = "";
            this.tiempo_diaz = "";
            this.primera_cuota = false;
            this.cuotas = [];
        },

        calcularCambio() {

            const recibidoNumero = parseFloat(
                this.recibido / parseFloat(this.monedaVenta[0])
            );
            if (recibidoNumero === 0) {
                this.efectivo = recibidoNumero;
                console.log("EFECTIVO", this.efectivo);
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

        buscarClientePorDocumento() {
            axios.get(`/api/clientes?documento=${this.documento}`)
                .then(response => {
                    const cliente = response.data;
                    console.log(cliente);
                    this.nombreCliente = cliente.nombre;
                    this.nombreClienteEditable = false; // Deshabilita el input si se encuentra el cliente
                })
                .catch(error => {
                    if (error.response && error.response.status === 404) {
                        this.nombreCliente = '';
                        this.nombreClienteEditable = true; // Habilita el input si no se encuentra el cliente
                        alert('Cliente no encontrado');
                    } else {
                        console.error('Error al buscar el cliente:', error);
                        this.nombreCliente = '';
                        this.nombreClienteEditable = false; // Asegura que el input esté deshabilitado en caso de error
                        alert('Error al buscar el cliente');
                    }
                });
        },
    },
    created() {
        this.listarPrecio();
    },
    mounted() {
        this.datosConfiguracion();
        this.selectAlmacen();
        this.listarVenta(1, this.buscar, this.criterio);
        //this.obtenerDatosUsuario();
        this.actualizarFechaHora();
        this.ejecutarFlujoCompleto();
    },
};
</script>
<style scoped>
/* Estilos para los iconos (ajusta según tus necesidades) */
.fa-check-circle {
    margin-left: 5px;
    /* Espacio entre el precio y el icono */
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

.spinner-container>* {
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

/* Global styles */
body {
    font-family: 'Roboto', sans-serif;
    background-color: #f8f9fa;
    color: #495057;
}

.card-body {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Form styles */
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    font-weight: bold;
    color: #343a40;
}

.form-control,
.form-select {
    border-radius: 4px;
}

input[readonly] {
    background-color: #e9ecef;
}

/* Custom select component */
.v-select {
    width: 100%;
}

.v-select .vs__dropdown-toggle {
    border-radius: 4px;
}

.v-select .vs__selected-options {
    padding: 0.375rem 0.75rem;
}

.v-select .vs__dropdown-menu {
    border-radius: 4px;
}

/* Table styles */
.table {
    margin-bottom: 0;
}

.table thead th {
    background-color: #343a40;
    color: #ffffff;
}

.table tbody td {
    vertical-align: middle;
}

/* Badge styles */
.badge.bg-primary {
    background-color: #007bff;
    color: #ffffff;
    margin-right: 5px;
}

.text-danger {
    color: #dc3545 !important;
}

/* Button styles */
.btnagregar {
    margin-top: 25px;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-primary:hover,
.btn-secondary:hover,
.btn-danger:hover {
    opacity: 0.85;
}

/* Alert styles */
.alert {
    text-align: center;
    font-weight: bold;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
}

.alert-warning {
    background-color: #fff3cd;
    color: #856404;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
}

/* Miscellaneous */
input[type="number"] {
    text-align: center;
}

.text-right {
    text-align: right !important;
}

.lead {
    font-size: 1.25rem;
}

.icon-plus,
.icon-minus {
    font-size: 1rem;
}

body {
    font-family: 'Roboto', sans-serif;
    /* Ejemplo de fuente de Google Fonts */
}

label {
    font-weight: 500;
    /* Añadir un poco de grosor a las etiquetas */
}

input,
select {
    border-radius: 4px;
    padding: 8px 12px;
    border: 1px solid #ccc;
    background-color: #f8f8f8;
    transition: border-color 0.3s ease;
}

input:focus,
select:focus {
    outline: none;
    border-color: #4d90fe;
}

.text-danger {
    color: #d32f2f;
}

input:required {
    border-color: #d32f2f;
}

.linear-stepper {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 2px 0;
    position: relative;
}

.step-container {
    display: flex;
    align-items: center;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 0 10px;
    opacity: 0.5;
    position: relative;
}

.step.active,
.step.completed {
    opacity: 1;
}

.step-number {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: #ccc;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 18px;
    z-index: 1;
}

.step.active .step-number {
    background-color: #007bff;
}

.step.completed .step-number {
    background-color: #34bc9b;
}

.step-line {
    height: 3px;
    width: 40px;
    background-color: #ccc;
    transition: background-color 0.3s;
    z-index: 0;
}

.step.completed+.step-line {
    background-color: #34bc9b;
}

.step.active+.step-line {
    background-color: #007bff;
}

.step-name {
    margin-top: 10px;
}

.buttons {
    margin-top: 20px;
}

.btn {
    font-size: 16px;
}
</style>
