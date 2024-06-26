<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <button class="sidebar-minimizer brand-minimizer" type="button" style="background-color: #D00000"></button>
            <li @click="menu=5" class="nav-item">
                <a class="nav-link active" href="#"><i class="fa fa-dashboard"></i> Escritorio</a>
            </li>
            <li class="nav-title">
                Operaciones
            </li>


            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-briefcase"></i> Empresa</a>
                <ul class="nav-dropdown-items">
                <li @click="menu=13" class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-building"></i> Empresa</a>
                </li>

                <li @click="menu=14" class="nav-item">
                    <a class="nav-link" href="#"><i class="fa fa-building"></i> Sucursales</a>
                </li>
                    <!--<li @click="menu=41" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Puntos de
                            Venta</a>
                    </li> -->

                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-usd"></i>
                    Finanzas</a>
                <ul class="nav-dropdown-items">
              <li @click="menu=16" class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-money"></i> Apertura/Cierre Caja</a>
          </li>
                    <!--
                    <li @click="menu=65" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Bancos</a>
                    </li>-->

                  <!--  <li @click="menu=15" class="nav-item">
    <a class="nav-link" href="#"><i class="fas fa-money-bill-alt"></i> Moneda</a>
</li>-->

                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    Ventas</a>
                <ul class="nav-dropdown-items">
                     <li @click="menu=0" class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-shopping-cart"></i> Ventas</a>
          <li @click="menu=55" class="nav-item">
                    <!-- <li @click="menu=40" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Registro
                            Ventas</a>
                    </li> -->
                    <li @click="menu=53" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Ventas a
                            credito</a>
                    </li>
                    <li @click="menu=6" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Clientes</a>
                    </li>
                    <li @click="menu=23" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Cotizaciones</a>
                    </li>


                </ul>
            </li>

            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-shopping-bag" aria-hidden="true"></i>
                    Compras</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=3" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Ingresos</a>
                    </li>
                    <li @click="menu=4" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Proveedores</a>
                    </li>
                    <!-- <li @click="menu=22" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Pedidos a
                            Prov.</a>
                    </li> -->
                    <li @click="menu=70" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px"></i> Nueva Compra</a>
                    </li>

                </ul>
            </li>

            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-file-text"></i> Almacén</a>
                <ul class="nav-dropdown-items">

                    <li @click="menu=24" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Almacenes</a>
                    </li>
                    <li @click="menu=25" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Inventario</a>
                    </li>
                    <li @click="menu=28" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Monitoreo
                            productos</a>
                    </li>

                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-tags"></i> Productos</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=2" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Productos</a>
                    </li>
                    <!--<li @click="menu=42" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Ofertas</a>
                    </li>
                    <li @click="menu=43" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Kits</a>
                    </li>

                    <li @click="menu=67" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Mayoreo</a>
                    </li>-->

                    <li @click="menu=18" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Marca</a>
                    </li>
                    <li @click="menu=19" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Linea</a>
                    </li>
                    <li @click="menu=20" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Industria</a>
                    </li>
                    <!--<li @click="menu=26" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Grupos</a>
                    </li>-->
                    <li @click="menu=27" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Medidas</a>
                    </li>
                    <li @click="menu=71" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Medidas</a>
                    </li>
                </ul>
            </li>



            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-lock"></i> Acceso</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=7" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Usuarios</a>
                    </li>
                    <li @click="menu=8" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Roles</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-line-chart"></i> Reportes Inventario</a>
                <ul class="nav-dropdown-items">

                    <!--<li @click="menu=49" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Inventarios</a>
                    </li>-->
                    <li @click="menu=51" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Kardex Fisico Valorado</a>
                    </li>
                 <li @click="menu=52" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Kardex Fisico</a>
                    </li>
                    <li @click="menu=58" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Resumen Fisico</a>
                    </li>
                    <li @click="menu=60" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Detallado Fisico de Movimientos</a>
                    </li>
                    <li @click="menu=63" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Inventario Fisico Valorado</a>
                    </li>
                    <li @click="menu=64" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Inventario Fisico</a>
                    </li>

                </ul>
            </li>

            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-line-chart"></i> Reportes Ventas</a>
                <ul class="nav-dropdown-items">

                    <li @click="menu=45" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Ventas Diarias</a>
                    </li>
                    <!--
                    <li @click="menu=62" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Ventas Detallado</a>
                    </li>
                    <li @click="menu=55" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Ventas x Documento</a>
                    </li>
                    <li @click="menu=57" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Ventas x Producto</a>
                    </li>-->

                </ul>
            </li>
            
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-line-chart"></i> Reportes Cientes</a>
                <ul class="nav-dropdown-items">

                    <li @click="menu=10" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Reporte Usuarios</a>
                    </li>
            <li @click="menu=50" class="nav-item">
                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Resumen de clientes</a>
            </li>
            <!--
            <li @click="menu=56" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Kardex Clientes Detallado Global</a>
                    </li>

                    <li @click="menu=59" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Kardex Clientes Resumen Global</a>
                    </li>

                    <li @click="menu=61" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Detalle - Recibo Cliente Por Documento</a>
                    </li>

                    <li @click="menu=68" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Resumen Ventas Y Cobranzas</a>
                    </li>

                </ul>
            </li>
            -->
        </ul>
    </nav>
    
</div>
