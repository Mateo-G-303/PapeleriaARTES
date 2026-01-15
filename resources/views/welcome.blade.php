<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Papelería Artes</title>

        <link rel="icon" href="{{ asset('img/favicon.ico') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <style>
            /*! tailwindcss v4.0.14 | MIT License | https://tailwindcss.com */
            @layer theme {
                :root, :host {
                    --font-sans: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
                    /* Colores de Papelería Artes */
                    --color-brand: #6D28D9; /* Morado Principal */
                    --color-brand-dark: #4C1D95; /* Morado Oscuro */
                    --color-brand-light: #F3E8FF; /* Morado Muy Claro */
                    --color-acento: #FF007F; /* Fucsia */
                }
            }
            /* Reset básico y utilidades necesarias */
            *, ::after, ::before { box-sizing: border-box; border-width: 0; border-style: solid; border-color: #e5e7eb; }
            html { line-height: 1.5; -webkit-text-size-adjust: 100%; tab-size: 4; font-family: var(--font-sans); }
            body { margin: 0; line-height: inherit; }
            .bg-gray-50 { background-color: #f9fafb; }
            .text-gray-900 { color: #111827; }
            .text-gray-600 { color: #4b5563; }
            .font-bold { font-weight: 700; }
            .min-h-screen { min-height: 100vh; }
            .flex { display: flex; }
            .flex-col { flex-direction: column; }
            .items-center { align-items: center; }
            .justify-center { justify-content: center; }
            .w-full { width: 100%; }
            .max-w-4xl { max-width: 56rem; }
            .p-6 { padding: 1.5rem; }
            .gap-4 { gap: 1rem; }
            .rounded-lg { border-radius: 0.5rem; }
            .shadow-xl { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
            .bg-white { background-color: #ffffff; }
            .overflow-hidden { overflow: hidden; }
            .lg\:flex-row { @media (min-width: 1024px) { flex-direction: row; } }
            .lg\:w-1\/2 { @media (min-width: 1024px) { width: 50%; } }
            .p-8 { padding: 2rem; }
            .lg\:p-12 { @media (min-width: 1024px) { padding: 3rem; } }
            .mb-4 { margin-bottom: 1rem; }
            .mb-8 { margin-bottom: 2rem; }
            .text-3xl { font-size: 1.875rem; line-height: 2.25rem; }
            .text-brand { color: var(--color-brand); }
            .bg-brand-light { background-color: var(--color-brand-light); }
            
            /* Botones personalizados estilo Flux */
            .btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.75rem 1.5rem; border-radius: 0.375rem; font-weight: 600; transition: all 0.2s; width: 100%; text-decoration: none; cursor: pointer; }
            .btn-primary { background-color: var(--color-brand); color: white; }
            .btn-primary:hover { background-color: var(--color-brand-dark); }
            .btn-secondary { background-color: white; color: var(--color-brand); border: 1px solid var(--color-brand); }
            .btn-secondary:hover { background-color: var(--color-brand-light); }

            /* Animaciones */
            .fade-in { animation: fadeIn 0.8s ease-out; }
            @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        </style>
    </head>
    <body class="bg-gray-50 text-gray-900 min-h-screen flex items-center justify-center p-6">

        <div class="w-full max-w-4xl bg-white rounded-lg shadow-xl overflow-hidden flex flex-col lg:flex-row fade-in">
            
            <div class="w-full lg:w-1/2 p-8 lg:p-12 flex flex-col justify-center">
                
                <div class="mb-6">
                    <span class="inline-block py-1 px-3 rounded-full bg-brand-light text-brand text-xs font-bold tracking-wide uppercase">
                        Sistema de Gestión
                    </span>
                </div>

                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">
                    Papelería <span class="text-brand">Artes</span>
                </h1>
                
                <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                    Donde la creatividad toma forma. Gestiona tu inventario, ventas y clientes desde una sola plataforma.
                </p>

                @if (Route::has('login'))
                    <div class="flex flex-col gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                                Ir al Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                Iniciar Sesión
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-secondary">
                                    Registrarse
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
                
                <div class="mt-8 pt-8 border-t border-gray-100 text-sm text-gray-400">
                    &copy; {{ date('Y') }} Papelería Artes. Todos los derechos reservados.
                </div>
            </div>

            <div class="w-full lg:w-1/2 bg-brand-light flex items-center justify-center p-8 lg:p-12 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-20"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 rounded-full bg-white opacity-20"></div>

                <div class="relative z-10 text-center">
                    <img src="{{ asset('img/logo_papeleria.png') }}" 
                         alt="Logo Artes Papelería" 
                         class="max-w-[280px] w-full h-auto drop-shadow-lg mx-auto transform hover:scale-105 transition-transform duration-500">
                </div>
            </div>

        </div>

    </body>
</html>