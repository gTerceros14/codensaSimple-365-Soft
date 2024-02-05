<div class="sidebar">
            <nav class="sidebar-nav">
                <ul class="nav">
                    <li @click="menu=0" class="nav-item">
                        <a class="nav-link active" href="#"><i class="fa fa-dashboard"></i> Escritorio</a>
                    </li>
                    <li class="nav-title">
                        Operaciones
                    </li>


                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-briefcase"  ></i> Empresa</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=13" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Empresa</a>
                    </li>

                            <li @click="menu=14" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Sucursales</a>
                            </li>
                            <li @click="menu=41" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Puntos de Venta</a>
                            </li>
                            <li @click="menu=15" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Moneda</a>
                            </li>
                            <li @click="menu=16" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Apertura/Cierre Caja</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-list-alt"></i> Ventas</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=5" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Ventas</a>
                            </li>
                            <li @click="menu=40" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Registro Ventas</a>
                            </li>
                            <li @click="menu=48" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Ventas Institucionales</a>
                            </li>
                            <li @click="menu=6" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Clientes</a>
                            </li>
                            <li @click="menu=23" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Cotizaciones</a>
                            </li>
                            <li @click="menu=29" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Facturas</a>
                            </li>
                            <li @click="menu=38" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Eventos Significativos</a>
                            </li>
                            <li @click="menu=39" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Factura Fuera de Línea</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-shopping-cart"></i> Compras</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=3" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Ingresos</a>
                            </li>
                            <li @click="menu=4" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Proveedores</a>
                            </li>
                            <li @click="menu=22" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Pedidos a Prov.</a>
                            </li>
               
                        </ul>
                    </li>

                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-file-text"></i> Almacén</a>
                        <ul class="nav-dropdown-items">
                      
                            <li @click="menu=24" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Almacenes</a>
                            </li>
                            <li @click="menu=30" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Traspasos</a>
                            </li>
                            <li @click="menu=25" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Inventario</a>
                            </li>
                            <li @click="menu=28" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Monitoreo productos</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-tags"></i> Productos</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=2" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Productos</a>
                            </li>
                            
                            <li @click="menu=18" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Marca</a>
                            </li>
                            <li @click="menu=19" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Linea</a>
                            </li>
                            <li @click="menu=20" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Industria</a>
                            </li>
                            <li @click="menu=26" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Grupos</a>
                            </li>
                            <li @click="menu=27" class="nav-item">
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
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-line-chart"></i> Reportes</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=45" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Reporte Ventas Diarias</a>
                            </li>
                            <li @click="menu=10" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Reporte Inventarios</a>
                            </li>
                            <li @click="menu=10" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Reporte Usuarios</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-info"></i>SIAT</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=31" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i>Actividades</a>
                            </li>
                            <li @click="menu=32" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Tipos Factura</a>
                            </li>
                            <li @click="menu=33" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Leyendas Factura</a>
                            </li>
                            <li @click="menu=34" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Productos Servicios</a>
                            </li>
                            <li @click="menu=35" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Motivo Anulación</a>
                            </li>
                            <li @click="menu=36" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Eventos Significativos</a>
                            </li>
                            <li @click="menu=37" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Unidad Medida</a>
                            </li>
                        </ul>
                    </li>

                    <li @click="menu=11" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-question-circle"></i> Ayuda <span class="badge badge-danger">PDF</span></a>
                    </li>
                    <li @click="menu=12" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-info-circle"></i> Acerca de...<span class="badge badge-info">IT</span></a>
                    </li>

     
                </ul>
            </nav>
            <button class="sidebar-minimizer brand-minimizer" type="button"></button>
        </div>