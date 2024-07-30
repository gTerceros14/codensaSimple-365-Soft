<div class="sidebar">
    <button class="sidebar-minimizer brand-minimizer" type="button" style="background-color: #a4b7c1"></button>
    <nav class="sidebar-nav">
        <ul class="nav">
          
            <li @click="menu=5" class="nav-item">
                <a class="nav-link active" href="#"><i class="fa fa-dashboard"></i> ESCRITORIO</a>
            </li>
            <li class="nav-title">
                OPERACIONES
            </li>


            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-briefcase"></i> EMPRESA</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=13" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-building" style="font-size: 19px;"></i> EMPRESA</a>
                    </li>
                    <li @click="menu=14" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-sitemap" style="font-size: 19px;"></i> SUCURSALES</a>
                    </li>
                    <!--<li @click="menu=41" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> PUNTOS DE
                            VENTA</a>
                    </li> -->

                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-usd"></i>
                    FINANZAS</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=16" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-money"></i> APERTURA/CIERRE CAJA</a>
                    </li>
                    <!--
                    <li @click="menu=65" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> BANCOS</a>
                    </li>-->

                  <!--  <li @click="menu=15" class="nav-item">
    <a class="nav-link" href="#"><i class="fas fa-money-bill-alt"></i> MONEDA</a>
</li>-->
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    VENTAS</a>
                <ul class="nav-dropdown-items">
                     <li @click="menu=0" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-shopping-cart"></i> VENTAS</a>
                    <li @click="menu=55" class="nav-item">
                    <!-- <li @click="menu=40" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> REGISTRO
                            VENTAS</a>
                    </li> -->
                    <li @click="menu=53" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-credit-card" style="font-size: 16px;"></i> VENTAS A CREDITO</a>
                    </li>
                    <li @click="menu=6" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-users" style="font-size: 16px;"></i> CLIENTES</a>
                    </li>
                    <li @click="menu=23" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-file-text-o" style="font-size: 16px;"></i> COTIZACIONES</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-shopping-bag" aria-hidden="true"></i>
                    COMPRAS</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=3" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> INGRESOS</a>
                    </li>
                    <li @click="menu=4" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> PROVEEDORES</a>
                    </li>
                    <!-- <li @click="menu=22" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> PEDIDOS A
                            PROV.</a>
                    </li> -->
                    <li @click="menu=70" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px"></i> NUEVA COMPRA</a>
                    </li>
                    <li @click="menu=72" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px"></i> COMPRAS A CREDITO</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-file-text"></i> ALMACÉN</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=24" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-building" style="font-size: 19px;"></i> ALMACENES</a>
                    </li>
                    <li @click="menu=25" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-cubes" style="font-size: 19px;"></i> INVENTARIO</a>
                    </li>
                    <li @click="menu=28" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-line-chart" style="font-size: 19px;"></i> MONITOREO PRODUCTOS</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-tags"></i> PRODUCTOS</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=2" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-product-hunt" style="font-size: 19px;"></i> PRODUCTOS</a>
                    </li>
                    <li @click="menu=71" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> PRODUCTOS NUEVO</a>
                    </li>
                    <li @click="menu=18" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-trademark" style="font-size: 19px;"></i> MARCA</a>
                    </li>
                    <li @click="menu=19" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-tags" style="font-size: 19px;"></i> LINEA</a>
                    </li>
                    <li @click="menu=20" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-industry" style="font-size: 19px;"></i> INDUSTRIA</a>
                    </li>
                    <li @click="menu=27" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-arrows-alt" style="font-size: 19px;"></i> MEDIDAS</a>
                    </li>
                </ul>
            </li>



            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-lock"></i> ACCESO</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=7" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-user" style="font-size: 19px;"></i> USUARIOS</a>
                    </li>
                    <li @click="menu=8" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-id-badge" style="font-size: 19px;"></i> ROLES</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-line-chart"></i> REPORTES INVENTARIO</a>
                <ul class="nav-dropdown-items">

                    <!--<li @click="menu=49" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> INVENTARIOS</a>
                    </li>-->
                    <li @click="menu=51" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-file-text-o" style="font-size: 19px;"></i> KARDEX FISICO VALORADO</a>
                    </li>
                    <li @click="menu=52" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-file-o" style="font-size: 19px;"></i> KARDEX FISICO</a>
                    </li>
                    <li @click="menu=58" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-list-alt" style="font-size: 19px;"></i> RESUMEN FISICO</a>
                    </li>
                    <li @click="menu=60" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-exchange" style="font-size: 19px;"></i> DETALLADO FISICO DE MOVIMIENTOS</a>
                    </li>
                    <li @click="menu=63" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-archive" style="font-size: 19px;"></i> INVENTARIO FISICO VALORADO</a>
                    </li>
                    <li @click="menu=64" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-cubes" style="font-size: 19px;"></i> INVENTARIO FISICO</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-line-chart"></i> REPORTES VENTAS</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=45" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-calendar-check-o" style="font-size: 19px;"></i> VENTAS DIARIAS</a>
                    </li>
                    <!--
                    <li @click="menu=62" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> VENTAS DETALLADO</a>
                    </li>
                    <li @click="menu=55" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> VENTAS X DOCUMENTO</a>
                    </li>
                    <li @click="menu=57" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> VENTAS X PRODUCTO</a>
                    </li>-->

                </ul>
            </li>
            
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-line-chart"></i> REPORTES CLIENTES</a>
                <ul class="nav-dropdown-items">

                    <li @click="menu=10" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-users" style="font-size: 19px;"></i> REPORTE USUARIOS</a>
                    </li>
                    <li @click="menu=50" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-user-circle" style="font-size: 19px;"></i> RESUMEN DE CLIENTES</a>
                    </li>
                    <!--
                    <li @click="menu=56" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> KARDEX CLIENTES DETALLADO GLOBAL</a>
                            </li>

                            <li @click="menu=59" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> KARDEX CLIENTES RESUMEN GLOBAL</a>
                            </li>

                            <li @click="menu=61" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> DETALLE - RECIBO CLIENTE POR DOCUMENTO</a>
                            </li>

                            <li @click="menu=68" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> RESUMEN VENTAS Y COBRANZAS</a>
                            </li>

                        </ul>
                    </li>
                    -->
                </ul>
    </nav>
   
</div>