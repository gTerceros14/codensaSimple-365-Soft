<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema Ventas Laravel Vue Js- Compartiendocodigos">
    <meta name="author" content="compartiendocodigos.net">
    <meta name="keyword" content="Sistema ventas Laravel Vue Js, Sistema compras Laravel Vue Js">
    <link rel="shortcut icon" href="img/InserteLogo.jpg">
    <meta name="userId" content="{{ Auth::check() ? Auth::user()->id : ''}}">
    <title>Sistema de Inventarios</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

    <!-- Icons -->
    <link href="css/plantilla.css" rel="stylesheet">
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <div id="app">
        <header class="app-header navbar">
            <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto border-0" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a class="navbar-brand" href="#"></a>
            <button class="navbar-toggler sidebar-toggler d-md-down-none border-0" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>

            <ul class="nav navbar-nav ml-auto mr-3">
                <notification :notifications="notifications"></notification>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button"
                        aria-haspopup="true" aria-expanded="false">
                        <img src="img/usuarios/{{ session('fotografia') }}?t={{ time() }}" class="img-avatar"
                            alt="Fotografia">
                        <span class="d-md-down-none">{{Auth::user()->usuario}}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header text-center">
                            <strong>Cuenta</strong>
                        </div>

                        <a class="dropdown-item" href="#" @click="menu=17">
                            <i class="fa fa-user-circle"></i> Perfil</a>

                        @if(Auth::check())
                        @if(Auth::user()->idrol == 1)
                        <a class="dropdown-item" href="#" @click="menu=21">
                            <i class="fa fa-wrench"></i> Configuración</a>
                        @endif
                        @endif

                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-lock"></i> Cerrar sesión</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>

                    </div>
                </li>
            </ul>
        </header>

        <div class="app-body">
            @if(Auth::check())
            @if (Auth::user()->idrol == 1)
            @include('plantilla.sidebaradministrador')
            @elseif (Auth::user()->idrol == 2)
            @include('plantilla.sidebarvendedor')
            @elseif (Auth::user()->idrol == 3)
            @include('plantilla.sidebaralmacenero')
            @elseif (Auth::user()->idrol == 4)
            @include('plantilla.sidebarencargado')
            @else

            @endif
            @endif
            <!-- Contenido Principal -->
            @yield('contenido')
            <!-- /Fin del contenido principal -->
        </div>
    </div>

    <!-- Agrega esta línea en tu plantilla de Laravel -->
    <script>
        window.userData = {
            rol: {{ Auth::check() ? Auth::user()->idrol : null }}
        };
    </script>

    <script src="js/app.js"></script>
    <script src="js/plantilla.js"></script>

    <!-- Agrega este código para cerrar la barra lateral automáticamente -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.sidebar');

            function closeSidebar() {
                if (sidebar) {
                    sidebar.classList.toggle('sidebar-show');
                    sidebar.classList.toggle('sidebar-hidden');
                }
            }

            const submenuItems = document.querySelectorAll('.nav-dropdown-items .nav-link');

            submenuItems.forEach(item => {
                item.addEventListener('click', () => {
                    // Cerrar la barra lateral inmediatamente después de hacer clic
                    closeSidebar();
                });
            });
        });
    </script>
</body>

</html>
