<!doctype html>
<html
    lang="es"
    class="layout-menu-fixed layout-compact"
    data-assets-path="/plantilla/assets/"
    data-template="vertical-menu-template-free">
    <head>
      <meta charset="utf-8" />
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

      <title>@yield('title')</title>

      <!-- Favicon -->
      <link rel="icon" type="image/x-icon" href="/plantilla/assets/img/favicon/favicon.ico" />

      <!-- Fonts -->
      <link rel="preconnect" href="https://fonts.googleapis.com" />
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
      <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

      <!-- Icons -->
      <link rel="stylesheet" href="/plantilla/assets/vendor/fonts/iconify-icons.css" />

      <!-- Core CSS -->
      <link rel="stylesheet" href="/plantilla/assets/vendor/css/core.css" />
      <link rel="stylesheet" href="/plantilla/assets/css/demo.css" />

      <!-- Vendors CSS -->
      <link rel="stylesheet" href="/plantilla/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
      <link rel="stylesheet" href="/plantilla/assets/vendor/libs/apex-charts/apex-charts.css" />

      <!-- Core JS -->
      <script src="/plantilla/assets/vendor/js/helpers.js"></script>

      <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
      <script src="/plantilla/assets/js/config.js"></script>

      <!-- Global site tag (gtag.js) - Google Analytics -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=DEFAULT-1"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'DEFAULT-1');
      </script>
    </head>
    <body>
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <!-- Menu -->
                @include('components.sidebar')
                <!-- / Menu -->

                <!-- Layout page -->
                <div class="layout-page">
                    <!-- Navbar -->
                    @include('components.navbar')
                    <!-- / Navbar -->

                    <!-- Content -->
                    <div class="content-wrapper">
                        <div class="container-xxl flex-grow-1 container-p-y">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Vendors JS -->
        <script src="/plantilla/assets/vendor/libs/jquery/jquery.js"></script>
        <script src="/plantilla/assets/vendor/libs/popper/popper.js"></script>
        <script src="/plantilla/assets/vendor/js/bootstrap.js"></script>
        <script src="/plantilla/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

        <script src="/plantilla/assets/vendor/js/menu.js"></script>
        <!-- endbuild -->

        <!-- Vendors JS -->
        <script src="/plantilla/assets/vendor/libs/apex-charts/apexcharts.js"></script>

        <!-- Main JS -->
        <script src="/plantilla/assets/js/main.js"></script>

        <!-- Page JS -->
        <script src="/plantilla/assets/js/dashboards-analytics.js"></script>

        <!-- Place this tag in your head or just before your close body tag. -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
    </body>
</html>
