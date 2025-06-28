<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bienvenido - Sistema Médico</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            body {
                font-family: 'Public Sans', sans-serif;
                min-height: 100vh;
                margin: 0;
                padding: 0;
                position: relative;
                overflow: hidden;
            }
            .background-image {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
                object-fit: cover;
            }
            .content-overlay {
                position: fixed;
                top: 50%;
                left: 5%;
                transform: translateY(-50%);
                padding: 4rem;
                background: rgba(255, 255, 255, 0.95);
                border-radius: 20px;
                width: 30%;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            }
            h1 {
                color: #2c3e50;
                margin-bottom: 2rem;
                font-size: 3rem;
                font-weight: 700;
            }
            p {
                color: #6c757d;
                margin-bottom: 2rem;
                font-size: 1.1rem;
                line-height: 1.6;
            }
            .btn {
                display: inline-block;
                padding: 0.8rem 2rem;
                border-radius: 50px;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s ease;
                margin: 0 0.5rem;
                font-size: 1rem;
            }
            .btn-primary {
                background-color: #0088cc;
                color: white;
            }
            .btn-secondary {
                background-color: #00a884;
                color: white;
            }
            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }
            @media (max-width: 1024px) {
                .content-overlay {
                    left: 10%;
                    width: 40%;
                    padding: 2rem;
                }
                h1 {
                    font-size: 2rem;
                }
                p {
                    font-size: 1rem;
                }
                .btn {
                    padding: 0.6rem 1.5rem;
                }
            }
            @media (max-width: 768px) {
                .content-overlay {
                    left: 5%;
                    width: 50%;
                    padding: 1.5rem;
                }
                h1 {
                    font-size: 1.5rem;
                }
            }
        </style>
    </head>
    <body>
        <img src="../plantilla/assets/img/backgrounds/fondo_welcome.webp" alt="Clínica" class="background-image">
        <div class="content-overlay">
            <h1>Bienvenido al Sistema Médico</h1>
            <p>Administre sus citas y pacientes de manera eficiente. Nuestro sistema le permite gestionar su clínica de manera profesional y eficaz.</p>
            <a href="{{ route('login') }}" class="btn btn-primary">Iniciar Sesión</a>
            <a href="{{ route('register') }}" class="btn btn-secondary">Registrarse</a>
        </div>
    </body>
</html>
