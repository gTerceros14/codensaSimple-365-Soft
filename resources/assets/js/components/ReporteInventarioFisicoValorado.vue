<template>
    <main class="main">
        <ol class="breadcrumb">
             <li class="breadcrumb-item"><a class="text-decoration-none" href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <!--<div class="card-header">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label> ALMACEN DE TRABAJO </label>
                                <select class="form-control" v-model="AlmacenSeleccionado" @change="getDatosAlmacen">
                                    <option value="0" disabled>Seleccione</option>
                                    <option v-for="opcion in arrayAlmacenes" :key="opcion.id" :value="opcion.id">{{ opcion.nombre_almacen }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex flex-column">
                                <label class="mb-1"> MODO VISTA </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" v-model="tipoSeleccionado" value="item" @change="cambiarTipo">
                                    <label class="form-check-label ms-2">Por Item</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" v-model="tipoSeleccionado" value="lote" @change="cambiarTipo">
                                    <label class="form-check-label ms-2">Por Lote</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->

                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Inventario Fisico Valorado
                    <button type="button" @click="abrirModal('articulo', 'registrar'); listarPrecio()"
                    class="btn btn-primary">
                        <i class="fa fa-search"></i>&nbsp;Filtros</button>
                    <!--<button type="button" @click="exportarExcel" class="btn btn-success">
                        <i class="icon-doc"></i>&nbsp;Exportar a Excel
                    </button>
                    <button @click="exportarPDF" class="btn btn-danger">Exportar a PDF</button>-->

                    <div class="col-md-3">
                        <div class="d-flex flex-column">
                            <label class="mb-1"> MODO VISTA </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" v-model="tipoSeleccionado" value="item" @change="cambiarTipo">
                                    <label class="form-check-label ms-2">Por Item</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" v-model="tipoSeleccionado" value="lote" @change="cambiarTipo">
                                    <label class="form-check-label ms-2">Por Lote</label>
                                </div>
                        </div>
                     </div>

                </div>
                <div class="card-body">
                    <div v-if="tipoSeleccionado == 'item'" class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <!-- <th>Opciones</th> -->
                                    <th>Almacen</th>
                                    <th>Marca</th>
                                    <th>Linea</th>
                                    <th>Industria</th>
                                    <th>Producto</th>
                                    <th>Unidad X Paq.</th>                                   
                                    <th>Saldo_stock_total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="inventario in arrayInventario" :key="inventario.id">
                                    <!-- <td>
                                        <button type="button" @click="abrirModal('almacenes','actualizar',inventario)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                    </td> -->
                                    <td v-text="inventario.nombre_almacen"></td>
                                    <td v-text="0"></td>
                                    <td v-text="0"></td>
                                    <td v-text="0"></td>
                                    <td v-text="inventario.nombre_producto"></td>
                                    <td v-text="inventario.unidad_envase"></td>
                                    <td v-text="inventario.saldo_stock_total"></td>
                                </tr>                                
                            </tbody>
                        </table>
                    </div>
                    <!--#####################################-LIStADO LOTE-###############-->
                    <div v-if="tipoSeleccionado == 'lote'" class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Almacen</th>
                                    <th>Fecha Ingreso</th>
                                    <th>Marca</th>
                                    <th>Linea</th>
                                    <th>Industria</th>
                                    <th>Producto</th>
                                    <th>Unid.X.Paq</th>
                                    <th>Costo Unidad</th>
                                    <th>Saldo Stock</th>
                                    <th>Fecha Vencimiento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="inventario in arrayInventario" :key="inventario.id">
                                    <td v-text="inventario.nombre_almacen"></td>
                                    <td v-text="inventario.fecha_ingreso"></td>
                                    <td v-text="0"></td>
                                    <td v-text="0"></td>
                                    <td v-text="0"></td>
                                    <td v-text="inventario.nombre_producto"></td>
                                    <td v-text="inventario.unidad_envase"></td>
                                    <td v-text="inventario.precio_costo_unid"></td>
                                    <td v-text="inventario.saldo_stock"></td>
                                    <td v-text="inventario.fecha_vencimiento"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--######################################-hasta AQUI#################-->
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                    
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!--Inicio del modal agregar/actualizar-->
        <div class="modal" tabindex="-1" :class="{ 'mostrar': modal }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Filtros de reportes</h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form @submit.prevent="enviarFormulario">
                        <div class="modal-body">
                            <div class="form-group row">

                                <div class="col-md-6">
                                    <label for="" class="font-weight-bold">Almacen <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" placeholder="Seleccione un almacen" disabled
                                            v-model="almacenseleccionada.nombre_almacen" :class="{ 'is-invalid': errores.idAlmacen }" 
                                            @input="validarCampo('codigo')">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button"
                                                @click="abrirModal2('Almacen')">...</button>
                                        </div>
                                    </div>
                                    <p class="text-danger" v-if="errores.idcategoria">{{ errores.idcategoria }}</p>

                                    <label for="" class="font-weight-bold">Marca <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" placeholder="Seleccione una marca" disabled
                                            v-model="marcaseleccionada.nombre" :class="{ 'is-invalid': errores.idmarca }"
                                            @input="validarCampo('idmarca')">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button"
                                                @click="abrirModal2('Marcas')">...</button>
                                        </div>
                                    </div>
                                    <p class="text-danger" v-if="errores.idmarca">{{ errores.idmarca }}</p>

                                      
                                    <label for="" class="font-weight-bold">Industria <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" placeholder="Seleccione una industria"
                                            disabled v-model="industriaseleccionada.nombre"
                                            :class="{ 'is-invalid': errores.idindustria }" @input="validarCampo('codigo')">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button"
                                                @click="abrirModal2('Industrias')">...</button>
                                        </div>
                                    </div>
                                    <p class="text-danger" v-if="errores.idindustria">{{ errores.idindustria }}</p>
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="font-weight-bold">Articulo <span class="text-danger">*</span></label>
                                    <div class="input-group">  
                                        <input class="form-control" type="text" placeholder="Seleccione un articulo" disabled
                                        v-model="articuloseleccionada.nombre"
                                            :class="{ 'is-invalid': errores.idcategoria }" @input="validarCampo('codigo')">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button"
                                                @click="abrirModal2('Articulo')">...</button>
                                        </div>
                                    </div>
                                    <p class="text-danger" v-if="errores.idcategoria">{{ errores.idcategoria }}</p>

                                    
                                    <label for="" class="font-weight-bold">Linea <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" placeholder="Seleccione una linea" disabled
                                            v-model="lineaseleccionada.nombre"
                                            :class="{ 'is-invalid': errores.idcategoria }" @input="validarCampo('codigo')">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button"
                                                @click="abrirModal2('Lineas')">...</button>
                                        </div>
                                    </div>
                                    <p class="text-danger" v-if="errores.idcategoria">{{ errores.idcategoria }}</p>
                                    
                                
                                </div> 
                                <div class="col-md-6">
                                    <label for="" class="font-weight-bold">Fecha Inicio: <span class="text-danger">*</span> </label>
                                    <input class="form-control" type="date" v-model="fechaInicio" >
                                    
                                    
                                </div>
                                <div class="col-md-6">
                                    
                                    <label for="" class="font-weight-bold">Fecha Fin: <span class="text-danger">*</span></label>
                                    <input class="form-control" type="date" v-model="fechaFin" >
                                    
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" @click="cerrarModal()">Cerrar</button>
                            <button type="submit" @click="listaReporte(); cerrarModal()" class="btn btn-primary">Visualizar Reporte</button>
                        </div>
                    </form>

                </div>
                <!-- /.modal-content -->
            </div>


            <!-- /.modal-dialog -->
        </div>

        <div class="modal " tabindex="-1" :class="{ 'mostrar': modal2 }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal2"></h4>
                        <button type="button" class="close" @click="modal2 = false" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioA">
                                        <option v-if="tituloModal2 !== 'Grupos'" value="nombre">Nombre</option>
                                        <option v-if="tituloModal2 == 'Articulo'" value="descripcion">Descripcion</option>
                                        <option v-else-if="tituloModal2 == 'Grupos'" value="nombre_grupo">Grupo</option>
                                        <!-- <option v-if="tituloModal2=='Grupos'" value="nombre_grupo">Nombre_grupo</option> -->
                                    </select>
                                    <input v-if="tituloModal2 == 'Marcas'" type="text" v-model="buscarA"
                                        @keyup="listarMarca(1, buscarA, criterioA)" class="form-control"
                                        placeholder="Texto a buscar">
                                    <input v-if="tituloModal2 == 'Industrias'" type="text" v-model="buscarA"
                                        @keyup="listarIndustria(1, buscarA, criterioA)" class="form-control"
                                        placeholder="Texto a buscar">
                                    <input v-if="tituloModal2 == 'Lineas'" type="text" v-model="buscarA"
                                        @keyup="listarLinea(1, buscarA, criterioA)" class="form-control"
                                        placeholder="Texto a buscar">
                                    <input v-if="tituloModal2 == 'Proveedors'" type="text" v-model="buscarA"
                                        @keyup="listarproveedor(1, buscarA, criterioA)" class="form-control"
                                        placeholder="Texto a buscar">
                                    <input v-if="tituloModal2 == 'Grupos'" type="text" v-model="buscarA"
                                        @keyup="listargrupo(1, buscarA, criterioA)" class="form-control"
                                        placeholder="Texto a buscar">
                                    <input v-if="tituloModal2 == 'Almacen'" type="text" v-model="buscarA"
                                        @keyup="listarAlmacen(1, buscarA, criterioA)" class="form-control"
                                        placeholder="Texto a buscar">
                                    <input v-if="tituloModal2 == 'Articulo'" type="text" v-model="buscarA"
                                        @keyup="listarArticulo(1, buscarA, criterioA)" class="form-control"
                                        placeholder="Texto a buscar">
                                    <input v-if="tituloModal2 == 'Cliente'" type="text" v-model="buscarA"
                                        @keyup="listarPersona(1, buscarA, criterioA)" class="form-control"
                                        placeholder="Texto a buscar">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm" v-if="tituloModal2 !== 'Grupos' && tituloModal2 !== 'Almacen'">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Id</th>

                                        <th>Nombre</th>
                                        <!-- <th>Estado</th> -->
                                        <th>
                                            <div v-if="tituloModal2 == 'Proveedors'">
                                                Nit
                                            </div>
                                            <div v-else>
                                                Estado
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="arrayelemento in arrayBuscador" :key="arrayelemento.id">
                                        <td>
                                            <button type="button" @click="seleccionar(arrayelemento)"
                                                class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                        </td>
                                        <td v-text="arrayelemento.id"></td>
                                        <!-- <div v-if="tituloModal2=='Grupos'">
                                            <td  v-text="arrayelemento.nombre_grupo"></td>
                                        </div> -->
                                        <td v-text="arrayelemento.nombre"></td>
                                        <td v-if="tituloModal2 == 'Industrias'">
                                            <div v-if="arrayelemento.estado">
                                                <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-danger">Desactivado</span>
                                            </div>
                                        </td>
                                        <td v-if="tituloModal2 == 'Marcas' || tituloModal2 == 'Lineas' || tituloModal2 == 'Sucursal' || tituloModal2 == 'Articulo'">
                                            <div v-if="arrayelemento.condicion">
                                                <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-danger">Desactivado</span>
                                            </div>
                                        </td>
                                        
                                        <div v-if="tituloModal2 == 'Proveedors'">
                                            <td v-text="arrayelemento.num_documento"></td>
                                        </div>
                                    </tr>
                                </tbody>
                            </table>
                            <!--###########################################################-->
                            <table class="table table-bordered table-striped table-sm" v-else-if="tituloModal2 == 'Grupos'">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Id</th>
                                        <th>Nombre</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="arrayelemento in arrayBuscador" :key="arrayelemento.id">
                                        <td>
                                            <button type="button" @click="seleccionar(arrayelemento)"
                                                class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                           
                                        </td>
                                        <td v-text="arrayelemento.id"></td>
                                        <td v-text="arrayelemento.nombre_grupo"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--##################################################################3-->
                            <table class="table table-bordered table-striped table-sm" v-else-if="tituloModal2 == 'Almacen'">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Id</th>
                                        <th>Nombre Sucursal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="arrayelemento in arrayBuscador" :key="arrayelemento.id">
                                        <td>
                                            <button type="button" @click="seleccionar(arrayelemento)"
                                                class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                           
                                        </td>
                                        <td v-text="arrayelemento.id"></td>
                                        <td v-text="arrayelemento.nombre_almacen"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--##################################################################3-->
                        </div>
                        <nav v-if="tituloModal2 == 'Marcas'">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaMarca(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaMarca(page, buscar, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaMarca(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                        <nav v-else-if="tituloModal2 == 'Lineas'">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaLinea(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaLinea(page, buscar, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaLinea(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                        <nav v-else-if="tituloModal2 == 'Industrias'">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaIndustria(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActivedMar ? 'active' : '']">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaIndustria(page, buscar, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaIndustria(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                        <nav v-else-if="tituloModal2 == 'Proveedors'">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaProveedor(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActivedMar ? 'active' : '']">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaProveedor(page, buscar, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaProveedor(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                        <nav v-else-if="tituloModal2 == 'Grupos'">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaGrupo(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActivedMar ? 'active' : '']">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaGrupo(page, buscar, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaGrupo(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul> 
                        </nav>
                        <nav v-else-if="tituloModal2 == 'Almacen'">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaAlmacen(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActivedMar ? 'active' : '']">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaAlmacen(page, buscar, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaAlmacen(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="modal2 = false">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        
        <!--Fin del modal-->
    </main>
</template>
<script>
    export default {
        data (){
            return {
                datosFormulario: {
                    nombre: '',
                    nombre_almacen: '',
                    descripcion: '',
                    nombre_generico: '',
                    unidad_envase: 0,
                    precio_costo_unid: 0,
                    precio_costo_paq: 0,
                    precio_venta: 0,
                    precio_uno: 0,
                    precio_dos: 0,
                    precio_tres: 0,
                    precio_cuatro: 0,
                    stock: 0,
                    costo_compra: 0,
                    codigo: '',
                    codigo_alfanumerico: '',
                    descripcion_fabrica: '',
                    idcategoria: null,
                    idmarca: null,
                    idindustria: null,
                    idgrupo: null,
                    idproveedor: null,
                    idmedida: null,
                },
                arrayInventario: [],
                //---------------------
                //arrayAlmacenes: [],
                //AlmacenSeleccionado: 1,
                //idalmacen: 0,
                tipoSeleccionado: 'item',
                
                //---------------------
                // almacen_id:0,
                // nombre_almacen: '',
                // ubicacion: '',
                // encargado: '',
                // telefono: '',
                // lugar: '',
                // observacion: '',

                // modal : 0,
                // tituloModal : '',
                // tipoAccion : 0,
                // errorMostrarMsjIndustria: [],
                // errorIndustria : 0,
                pagination: this.createPaginationObject(),
                paginationMedida: this.createPaginationObject(),
                offset: {
                    pagination: 3,
                    paginationMedida: 3,
                },
                criterio : 'nombre',
                buscar : '',

                //FILTROS
                tituloModal2: '',
                modal: 0,
                modal2: false,
                errores: {},
                nombre: '',
                descripcion: '',
                nombre_generico: '',
                criterioA: 'nombre',
                buscarA: '',

                almacenseleccionada:[],
                articuloseleccionada : [], 
                marcaseleccionada: [],
                industriaseleccionada: [],
                lineaseleccionada: [],

                arrayBuscador: [],

                //fechas
                fechaInicio:'',
                fechaFin:'',



                //Validacion de inputs
                lineaseleccionadaVacio: false,
                marcaseleccionadaVacio: false,
                industriaseleccionadaVacio: false,
                almacenseleccionadaVacio: false,
                articuloseleccionadaVacio: false,
                

            }
        },
        computed:{
            isActived: function () {
            return this.pagination.current_page;
            },
            isActivedM: function () {
                return this.pagination.current_page;
            },
            isActivedMar: function () {
                return this.pagination.current_page;
            },

            pagesNumber: function () {
                return this.calculatePages(this.pagination, this.offset.pagination);
            },
            pagesNumberMedida: function () {
                return this.calculatePages(this.paginationMedida, this.offset.paginationMedida);
        },

        },
        methods : {
            calculatePages: function (paginationObject, offset) {
            if (!paginationObject.to) {
                return [];
            }

            var from = paginationObject.current_page - offset;
            if (from < 1) {
                from = 1;
            }

            var to = from + (offset * 2);
            if (to >= paginationObject.last_page) {
                to = paginationObject.last_page;
            }

            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        },

        createPaginationObject() {
            return {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,
            }
        },

        async validarCampo(campo) {
            this.asignarCampos();
            try {
                await esquemaArticulos.validateAt(campo, this.datosFormulario);
                this.errores[campo] = null;
            } catch (error) {
                this.errores[campo] = error.message;
            }
        },

        calcularPrecioValorMoneda(precio) {
            console.log(precio)
            return ((precio * parseFloat(this.monedaPrincipal)).toFixed(2))
        },
        convertDolar(precio) {
            return (precio / parseFloat(this.monedaPrincipal))
        },

        asignarCampos() {
            this.datosFormulario.idcategoria = this.lineaseleccionada.id;
            this.datosFormulario.idmarca = this.marcaseleccionada.id;
            this.datosFormulario.idproveedor = this.proveedorseleccionada.id;
            this.datosFormulario.idindustria = this.industriaseleccionada.id;
            this.datosFormulario.idmedida = this.medidaseleccionada.id;
            this.datosFormulario.idgrupo = this.gruposeleccionada.id;
            this.datosFormulario.idAlmacen = this.almacenseleccionada.id;
            this.datosFormulario.idArticulo = this.articuloseleccionada.id;
            this.datosFormulario.idCliente = this.clienteseleccionada.id;


            this.datosFormulario.precio_costo_unid = this.convertDolar(this.datosFormulario.precio_costo_unid);
            this.datosFormulario.precio_costo_paq = this.convertDolar(this.datosFormulario.precio_costo_paq);
            this.datosFormulario.precio_venta = this.convertDolar(this.datosFormulario.precio_venta);

            this.datosFormulario.precio_uno = this.convertDolar(this.precio_uno);
            this.datosFormulario.precio_dos = this.convertDolar(this.precio_dos);
            this.datosFormulario.precio_tres = this.convertDolar(this.precio_tres);
            this.datosFormulario.precio_cuatro = this.convertDolar(this.precio_cuatro);
            this.datosFormulario.costo_compra = this.convertDolar(this.datosFormulario.costo_compra);
        },

            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarInventario(page,buscar,criterio);
            },
            //---------------------------------------
            listarInventario (){
                let me=this;
                let url = '/inventarios/itemLote/' + me.tipoSeleccionado + '?&idAlmacen=' + me.almacenSeleccionado + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    console.log("ARRAy:",respuesta);
                    me.arrayInventario = respuesta.inventarios;
                    console.log('LLEGA:',me.arrayInventario);
                    //me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectAlmacen() {
                let me = this;
                var url = '/almacen/selectAlmacen';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAlmacenes = respuesta.almacenes;
                    console.log('ALMACEN:',me.arrayAlmacenes);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosAlmacen() {
                let me = this;
                if (me.AlmacenSeleccionado !== '') {
                    me.loading = true;
                    me.almacenSeleccionado = me.AlmacenSeleccionado; // Almacenar el valor seleccionado
                    me.idalmacen = Number(me.AlmacenSeleccionado);
                    console.log('IDalmacen: ' + me.idalmacen);
                    me.listarInventario();
                }
            },
            cambiarTipo() {
                this.getDatosAlmacen(); // Actualizar datos de almacén
                //this.listarInventario(); // Listar inventario basado en almacén seleccionado
            },
            
            abrirModal(modelo, accion, data = []) {
                this.almacenseleccionada = false;
                this.articuloseleccionada = false;
                this.lineaseleccionada = false;
                this.marcaseleccionada = false;
                this.industriaseleccionada = false;
                this.fechaInicio = '';
                this.fechaFin ='';
                switch (modelo) {
                    case "articulo":
                        {
                            switch (accion) {

                                case 'registrar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'Registrar Artículo';

                                        this.tipoAccion = 1;
                                        this.fotografia = '';

                                        this.datosFormulario = {
                                            nombre: '',
                                            descripcion: '',
                                            nombre_generico: '',
                                            unidad_envase: 0,
                                            precio_costo_unid: 0,
                                            precio_costo_paq: 0,
                                            precio_venta: 0,
                                            precio_uno: 0,
                                            precio_dos: 0,
                                            precio_tres: 0,
                                            precio_cuatro: 0,
                                            stock: 0,
                                            costo_compra: 0,
                                            codigo: '',
                                            codigo_alfanumerico: '',
                                            descripcion_fabrica: '',
                                            idcategoria: null,
                                            idmarca: null,
                                            idindustria: null,
                                            idgrupo: null,
                                            idproveedor: null,
                                            idmedida: null
                                        };
                                        this.errores = {};
                                        break;
                                    }
                                case 'actualizar':
                                    {
                                        console.log("datass", data);
                                        this.modal = 1;
                                        this.tituloModal = 'Actualizar Artículo';
                                        this.tipoAccion = 2;
                                        this.datosFormulario = {
                                            nombre: data['nombre'],
                                            descripcion: data['descripcion'],
                                            nombre_generico: data['nombre_generico'],
                                            unidad_envase: data['unidad_envase'],
                                            precio_costo_unid: this.calcularPrecioValorMoneda(data['precio_costo_unid']),
                                            precio_costo_paq: this.calcularPrecioValorMoneda(data['precio_costo_paq']),
                                            precio_venta: this.calcularPrecioValorMoneda(data['precio_venta']),
                                            precio_uno: 0,
                                            precio_dos: 0,
                                            precio_tres: 0,
                                            precio_cuatro: 0,
                                            stock: data['stock'],
                                            costo_compra: this.calcularPrecioValorMoneda(data['costo_compra']),
                                            codigo: data['codigo'],
                                            codigo_alfanumerico: data['codigo_alfanumerico'],
                                            descripcion_fabrica: data['descripcion_fabrica'],
                                            idcategoria: null,
                                            idmarca: null,
                                            idindustria: null,
                                            idgrupo: null,
                                            idproveedor: null,
                                            idmedida: data['idmedida'],
                                            id: data['id']
                                        };
                                        this.errores = {};
                                        this.idmedida = data['idmedida'];

                                        this.fotografia = data['fotografia'];
                                        this.fotoMuestra = data['fotografia'] ? 'img/articulo/' + data['fotografia'] : null;
                                        //this.industriaseleccionada = { nombre: data['industriaseleccionada.nombre'] };

                                        //this.industriaseleccionada = {nombre: data['nombre_industria']};
                                        this.industriaseleccionada = { nombre: data['nombre_industria'], id: data['idindustria'] };
                                        //this.lineaseleccionada = {nombre: data['nombre_categoria']};
                                        this.lineaseleccionada = { nombre: data['nombre_categoria'], id: data['idcategoria'] };
                                        //this.marcaseleccionada = {nombre: data['nombre_marca']};
                                        this.marcaseleccionada = { nombre: data['nombre_marca'], id: data['idmarca'] };
                                        this.proveedorseleccionada = { nombre: data['nombre_proveedor'], id: data['idproveedor'] };
                                        //this.gruposeleccionada = {nombre_grupo: data['nombre_grupo']};
                                        this.gruposeleccionada = { nombre_grupo: data['nombre_grupo'], id: data['idgrupo'] };
                                        this.medidaseleccionada = { descripcion_medida: data['descripcion_medida'], id: data['idmedida'] };

                                        this.precio_uno = this.calcularPrecioValorMoneda(data['precio_uno']);
                                        this.precio_dos = this.calcularPrecioValorMoneda(data['precio_dos']);
                                        this.precio_tres = this.calcularPrecioValorMoneda(data['precio_tres']);
                                        this.precio_cuatro = this.calcularPrecioValorMoneda(data['precio_cuatro']);
                                        // this.precios.forEach((precio) => {
                                        //     this.calcularPrecio(precio);
                                        // });
                                        break;

                                    }
                                case 'registrarInd':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'Registrar Industria';
                                        this.nombre = '';
                                        //this.descripcion = '';
                                        this.tipoAccion = 3;
                                        break;
                                    }
                            }
                        }
                }
            },
        
            cerrarModal() {
            this.modal = 0;
            this.tituloModal = '';
            //this.idcategoria = 0;
            //this.nombre_categoria = '';
            //validacion para quitar borde rojo en los inputs
            this.codigoVacio = false;
            this.nombreProductoVacio = false;
            this.unidad_envaseVacio = false;
            this.nombre_genericoVacio = false;
            this.precio_costo_unidVacio = false;
            this.precio_costo_paqVacio = false;
            this.precio_ventaVacio = false;
            this.costo_compraVacio = false;
            this.stockVacio = false;
            this.descripcionVacio = false;
            this.fotografiaVacio = false;
            this.lineaseleccionadaVacio = false;
            this.marcaseleccionadaVacio = false;
            this.industriaseleccionadaVacio = false;
            this.proveedorseleccionadaVacio = false;
            this.gruposeleccionadaVacio = false;
            this.medidaseleccionadaVacio = false;
            this.articuloseleccionadaVacio = false;
            

            //
            this.codigo = '';
            this.nombre_producto = '';
            this.nombre_generico = '';
            this.precio_venta = 0;
            this.precio_costo_unid = 0;
            this.precio_costo_paq = 0;
            this.stock = 5;
            this.descripcion = '';
            this.fotografia = ''; //Pasando el valor limpio de la referencia
            this.fotoMuestra = null;
            //this.lineaseleccionada.nombre = '';
            //this.marcaseleccionada.nombre = '';
            this.industriaseleccionada.nombre = '';
            this.proveedorseleccionada.nombre = '';
            this.gruposeleccionada.nombre_grupo = '';
            this.medidaseleccionada.descripcion_medida = '';
            //this.lineaseleccionada.nombre = '';
            //this.articuloseleccionada.nombre = '';
            //this.sucursalseleccionada.nombre = '';
            this.errorArticulo = 0;

            this.idmedida = 0;
            this.costo_compra = 0;

            this.precio_uno = 0;
            this.precio_dos = 0;
            this.precio_tres = 0;
            this.precio_cuatro = 0;
            
            // unidad_envaseVacio: false,
            // nombre_genericoVacio: false,
            // precio_costo_unidVacio: false,
            // precio_costo_paqVacio: false,
            // precio_ventaVacio: false,
            // costo_compraVacio: false,
            // stockVacio: false,
            // descripcionVacio: false,
            // fecha_vencimientoVacio: false,
            // fotografiaVacio: false,
            // lineaseleccionadaVacio: false,
            // marcaseleccionadaVacio: false,
            // industriaseleccionadaVacio: false,
            // proveedorseleccionadaVacio: false,
            // gruposeleccionadaVacio: false,
            // medidaseleccionadaVacio: false,
        },
        
        abrirModal2(titulo) {
            if (titulo == "Marcas") {
                this.listarMarca(1, '', 'nombre');
                this.modal2 = true;
                this.tituloModal2 = titulo;
                this.marcaseleccionadaVacio = false;

            } else if (titulo == "Industrias") {
                this.listarIndustria(1, '', 'nombre');
                this.modal2 = true;
                this.tituloModal2 = titulo;
                this.industriaseleccionadaVacio = false;

            }else if (titulo == "Lineas") {
                this.listarLinea(1, '', 'nombreLinea');
                this.modal2 = true;
                this.tituloModal2 = titulo;
                this.lineaseleccionadaVacio = false;

            }else if (titulo == "Almacen") {
                this.listarAlmacen(1, '', 'nombre_almacen');
                this.modal2 = true;
                this.tituloModal2 = titulo;
                this.gruposeleccionadaVacio = false;

            }else if (titulo == "Articulo") {
                this.listarArticulo(1, '', '');
                this.modal2 = true;
                this.tituloModal2 = titulo;
                this.articuloseleccionadaVacio = false;
            }
        },

        seleccionar(selected) {
            if (this.tituloModal2 == "Marcas") {
                this.marcaseleccionadaVacio = false;
                if (selected.condicion == 1) {
                    this.marcaseleccionada = selected;
                    this.validarCampo("idmarca");

                } else if (selected.condicion == 0) {
                    this.advertenciaInactiva('Marcas');
                }

            } else if (this.tituloModal2 == "Industrias") {
                this.industriaseleccionadaVacio = false;
                if (selected.estado == 1) {
                    this.industriaseleccionada = selected;
                    this.validarCampo("idindustria");

                } else if (selected.estado == 0) {
                    this.advertenciaInactiva('Industrias');
                }
            } else if (this.tituloModal2 == "Lineas") {
                if (selected.condicion == 1) {
                    this.lineaseleccionada = selected;
                    this.validarCampo("idcategoria");

                } else if (selected.condicion == 0) {
                    this.advertenciaInactiva('Lineas');
                }
            } else if (this.tituloModal2 == "Proveedors") {
                // this.proveedorseleccionada.id = selected.id;
                // this.proveedorseleccionada.nombre = selected.nombre;
                this.proveedorseleccionada = selected;
                this.validarCampo("idproveedor");

            } else if (this.tituloModal2 == "Grupos") {
                this.gruposeleccionada = selected;
                this.validarCampo("idgrupo");

            }else if (this.tituloModal2 == "Almacen") {
                    this.almacenseleccionadaVacio = false;
                    this.almacenseleccionada = selected;

            }else if (this.tituloModal2 == "Articulo") {
                if (selected.condicion == 1) {
                    this.articuloseleccionada = selected;
                    this.validarCampo("idArticulo");

                } else if (selected.condicion == 0) {
                    this.advertenciaInactiva('Articulo');
                }
                
            } else if (this.tituloModal2 == "Cliente") {
                this.clienteseleccionadaVacio = false;
                this.clienteseleccionada = selected;
            }
            
            // if (this.marcaseleccionada.condicion == 1 ){
            //     console.log("selcciona", selected);
            // }else if (this.marcaseleccionada.condicion == 0 ){
            //     console.log("Estado invalido", this.marcaseleccionada.condicion);
            // }
            console.log("Seleccionado", selected);
            this.arrayBuscador = [];
            this.modal2 = false;
        },
        
        listaReporte() {
            let me = this;
            var url = '/reporte-inventario-fisico-valorado/'+me.tipoSeleccionado +'?&fecha_vencimiento=2026-01-01 ';

            // Agregar los parámetros obligatorios
            //url += 'almacen=' + this.almacenseleccionada.id + '&articulo=' + this.articuloseleccionada.id + '&marca=' + this.marcaseleccionada.id + '&linea=' + this.lineaseleccionada.id + '&industria=' + this.industriaseleccionada.id ;

            // Agregar las fechas de inicio y fin
            //url += '&fechaInicio=' + me.fechaInicio + '&fechaFin=' + me.fechaFin;

            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.total_saldofisico = respuesta.total_saldo;
                    me.arrayReporte = respuesta.inventarios_valorado;
                    console.log("array reporte",me.arrayReporte);
                })
                .catch(function (error) {
                    console.log('ERRORES', error);
                });
        },

        listarPrecio() {
            let me = this;
            var url = '/precios';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.precios = respuesta.precio.data;
                console.log('PRECIOS', me.precios);
                //me.precioCount = me.arrayBuscador.length;
            }).catch(function (error) {
                console.log(error);
            });
            },
        
        listarArticulo(page, buscar, criterio) {
            let me = this;
            var url = '/articulo?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayBuscador = respuesta.articulos.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        listarMarca(page, buscar, criterio) {
            let me = this;
            console.log("Listano");
            var url = '/marca?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;

            axios.get(url).then(function (response) {

                var respuesta = response.data;
                console.log(respuesta);

                me.arrayBuscador = respuesta.marcas.data;
                me.pagination = respuesta.pagination;
                console.log("Listad0");

            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        listarIndustria(page, buscar, criterio) {
            let me = this;
            var url = '/industria?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayBuscador = respuesta.industrias.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        listarLinea(page, buscar, criterio) {
            let me = this;
            var url = '/categoria?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayBuscador = respuesta.categorias.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        listarAlmacen(page, buscar, criterio) {
            let me = this;
                var url = '/almacen/selectAlmacen';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayBuscador = respuesta.almacenes;
                    console.log('ALMACEN:',me.arrayAlmacenes);
                    console.log('Buscador:',me.arrayBuscador);

                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        cambiarPagina(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarArticulo(page, buscar, criterio);
        },
        cambiarPaginaMedida(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.paginationMedida.current_page = page;
            me.listarMedida(page, buscar, criterio);
            //Envia la petición para visualizar la data de esa página
        },
        cambiarPaginaMarca(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            me.listarMarca(page, buscar, criterio);
            //Envia la petición para visualizar la data de esa página
        },
        cambiarPaginaLinea(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            me.listarLinea(page, buscar, criterio);
            //Envia la petición para visualizar la data de esa página
        },
        cambiarPaginaIndustria(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            me.listarIndustria(page, buscar, criterio);
            //Envia la petición para visualizar la data de esa página
        },
        cambiarPaginaProveedor(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            me.listarproveedor(page, buscar, criterio);
            //Envia la petición para visualizar la data de esa página
        },
        cambiarPaginaGrupo(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            me.listargrupo(page, buscar, criterio);
            //Envia la petición para visualizar la data de esa página
        },
        cambiarPaginaAlmacen(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarAlmacen(page, buscar, criterio);
        },

        },
        mounted() {
            //this.listarInventario(1,this.buscar,this.criterio);
            this.getDatosAlmacen();
            //this.listarInventario(1,this.buscar,this.criterio);
            this.listarInventario();
            this.selectAlmacen();
            this.listarPrecio();//aumenTe 6julio
            this.listarArticulo(1, this.buscar, this.criterio);


        },
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>