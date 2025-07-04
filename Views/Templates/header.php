<?php require_once 'Config/Helpers.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Panel Administrativo</title>

    <!-- SEO -->
    <meta name="description" content="Panel administrativo basado en Bootstrap 4, con funcionalidades modernas como calendario, alertas y formularios dinámicos.">

    <!-- Redes Sociales -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:site" content="@pratikborsadiya">
    <meta property="twitter:creator" content="@pratikborsadiya">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Vali Admin">
    <meta property="og:title" content="Vali - Free Bootstrap 4 admin theme">
    <meta property="og:url" content="http://pratikborsadiya.in/blog/vali-admin">
    <meta property="og:image" content="http://pratikborsadiya.in/blog/vali-admin/hero-social.png">
    <meta property="og:description" content="Vali es un panel administrativo responsivo y modular.">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo base_url; ?>Assets/img/favicon.png">

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- CSS: Frameworks y Plugins -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- DataTables Responsive CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/select2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.7.9/dist/css/tempus-dominus.min.css">
    <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/adminlte.min.css">
    <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/swalfire.css">
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/mainn.css">
    <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/estilos.css">


    <!-- JS: jQuery y plugins (orden importante) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- DataTables Responsive JS -->
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script src="<?php echo base_url; ?>Assets/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.7.9/dist/js/tempus-dominus.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>


</head>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleBtn = document.querySelector('.app-sidebar__toggle');
        const overlay = document.querySelector('.app-sidebar__overlay');
        const body = document.body;

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                body.classList.toggle('sidenav-toggled');
            });
        }

        if (overlay) {
            overlay.addEventListener('click', function() {
                body.classList.remove('sidenav-toggled');
            });
        }
    });
</script>
<style>
    .app-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1040;
        height: 56px;
        background-color: #007bff;
        color: white;
    }

    .treeview-menu {
        display: none;
        padding-left: 15px;
    }

    .treeview:hover>.treeview-menu {
        display: block;
    }

    .app-content {
        margin-top: 56px;
        /* Altura del header */
        margin-left: 56px;
        /* Espacio para el sidebar compacto */
        padding: 10px;
        transition: margin-left 0.2s ease;
    }

    body.sidenav-toggled .app-content {
        margin-left: 0 !important;
        width: 100% !important;
        margin-top: 56px;
    }




    /* Sidebar estilo fijo (compacto por defecto) */
    .app-sidebar {
        width: 55px;
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        background-color: #343a40;
        overflow-y: auto;
        overflow-x: hidden;
        transition: width 0.3s ease, margin-left 0.2s ease;
        z-index: 1050;
        /* por encima del header */
    }

    /* Cuando la clase sidenav-toggled está activa, ocultamos el sidebar */
    body.sidenav-toggled .app-sidebar {
        margin-left: -60px;
        transition: margin-left 0.2s ease;
    }

    /* Sidebar expandido al pasar el mouse */
    .app-sidebar:hover {
        width: 180px;
    }

    /* Ocultar los labels del menú cuando está colapsado */
    .app-sidebar .app-menu__label {
        opacity: 0;
        transition: opacity 0.3s ease;
        white-space: nowrap;
    }

    /* Mostrar los labels al hacer hover */
    .app-sidebar:hover .app-menu__label {
        opacity: 1;
    }

    /* Header (queda debajo visualmente del sidebar) */


    /* Dropdown para usuario (centrado bajo el ícono) */
    .dropdown-menu-user {
        top: 100% !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        right: auto !important;
    }
</style>

<body class="app">
    <header class="app-header bg-primary text-white">
    <!-- Navbar Right Menu -->
    <ul class="app-nav ml-auto">
        <li class="nav-item dropdown user-menu position-relative">
            <a href="#" class="app-nav__item dropdown-toggle d-flex align-items-center" data-toggle="dropdown">
                <span class="d-none d-md-inline">
                    <?php echo isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : 'Usuario'; ?>
                </span>
            </a>

            <ul class="dropdown-menu dropdown-menu-user animated fadeIn" style="min-width: 220px;">
                <li>
                    <a class="dropdown-item" href="<?php echo base_url; ?>Usuarios/perfil">
                        Perfil
                    </a>
                </li>

                <li class="dropdown-divider"></li>

                <?php if (tienePermiso('Usuarios')) { ?>
                    <li>
                        <a class="dropdown-item" href="<?php echo base_url; ?>Usuarios/colaboradores">
                            Colaboradores
                        </a>
                    </li>
                <?php } ?>

                <li class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="<?php echo base_url; ?>Usuarios/salir">
                        Salir
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</header>

<div class="app-sidebar__overlay"></div>
<aside class="app-sidebar">
    <ul class="app-menu">
        <!-- Escritorio -->
        <li>
            <a class="app-menu__item" href="<?php echo base_url; ?>Configuracion/admin">
                <span class="app-menu__label">Escritorio</span>
            </a>
        </li>

        <!-- Registrar -->
        <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <span class="app-menu__label">Registrar</span>
            </a>

            <ul class="treeview-menu">
                <?php if (tienePermiso('Conductores')) { ?>
                    <li>
                        <a class="treeview-item" href="<?php echo base_url; ?>Conductor">
                            Conductores
                        </a>
                    </li>
                <?php } ?>
                <?php if (tienePermiso('Empresas')) { ?>
                    <li>
                        <a class="treeview-item" href="<?php echo base_url; ?>Empresa">
                            Empresas
                        </a>
                    </li>
                <?php } ?>
                <?php if (tienePermiso('Pago')) { ?>
                    <li>
                        <a class="treeview-item" href="<?php echo base_url; ?>Pago">
                            Pagos
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </li>

        <!-- Búsqueda -->
        <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <span class="app-menu__label">Búsqueda</span>
            </a>
            <ul class="treeview-menu">
                <?php if (tienePermiso('Conductores')) { ?>
                    <li>
                        <a class="treeview-item" href="<?php echo base_url; ?>Conductor/Conductores">
                            Conductores
                        </a>
                    </li>
                <?php } ?>
                <?php if (tienePermiso('Empresas')) { ?>
                    <li>
                        <a class="treeview-item" href="<?php echo base_url; ?>Empresa/Empresas">
                            Empresas
                        </a>
                    </li>
                <?php } ?>
                <?php if (tienePermiso('Pago')) { ?>
                    <li>
                        <a class="treeview-item" href="<?php echo base_url; ?>Pago/Pagos">
                            Pagos
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </li>

        <!-- Validar Pagos -->
        <?php if (tienePermiso('Validar_pagos')) { ?>
            <li>
                <a class="app-menu__item" href="<?php echo base_url; ?>Pago/Validar_pago">
                    <span class="app-menu__label">Validar Pagos</span>
                </a>
            </li>
        <?php } ?>
    </ul>
</aside>


    <main class="app-content">