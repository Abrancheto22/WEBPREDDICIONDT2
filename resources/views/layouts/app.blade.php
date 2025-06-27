<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <!-- Aquí tus estilos -->
</head>
<body>
    @include('components.navbar')

    <div class="main-container">
        @include('components.sidebar')

        <div class="content">
            @yield('content')
        </div>
    </div>

    <!-- Aquí tus scripts -->
</body>
</html>
