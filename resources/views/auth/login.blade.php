<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión </title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo_credian.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>
<body class="text-gray-800 font-sans antialiased min-h-screen relative flex items-center justify-center selection:bg-[#33AD72] selection:text-white">

    <!-- Fondo Glassmorphism (Orbes difuminados) -->
    <div class="fixed inset-0 z-[-1] bg-[#F8FAFC] overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[600px] h-[600px] rounded-full bg-[#0F4E88]/15 blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] rounded-full bg-[#33AD72]/15 blur-[120px]"></div>
    </div>

    <!-- Contenedor de la Tarjeta -->
    <div class="w-full max-w-md px-6 animate-fade-up">
        
        <div class="bg-white/60 backdrop-blur-xl border border-white/80 rounded-[2rem] p-10 shadow-[0_8px_30px_rgb(0,0,0,0.04)] text-center relative overflow-hidden">
            
            <!-- Resplandor sutil dentro de la tarjeta -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-32 bg-gradient-to-br from-[#0F4E88]/10 to-[#33AD72]/10 blur-2xl rounded-full"></div>

            <!-- Logo con tu Imagen PNG -->
            <div class="mx-auto flex items-center justify-center mb-6 relative z-10">
                <img src="{{ asset('img/logo_credian.png') }}" alt="Logo del Sistema" class="h-20 w-auto drop-shadow-lg transition-transform duration-300 hover:scale-105">
            </div>

            <!-- Textos de Bienvenida -->
            <h2 class="text-2xl font-extrabold tracking-tight text-gray-900 mb-2 relative z-10">
                Sistema de <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#0F4E88] to-[#33AD72]">Inventario</span>
            </h2>
            <p class="text-gray-500 text-sm mb-8 relative z-10">
                Acceso exclusivo para personal autorizado. Inicia sesión con tu cuenta corporativa para continuar.
            </p>

            <!-- Alertas de Sesión / Errores -->
            @if (session('status'))
                <div class="mb-6 p-3 bg-green-50 border border-green-200 text-green-600 rounded-xl text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-medium">
                    Autenticación fallida o denegada.
                </div>
            @endif

            <!-- Botón Único de Google -->
            <a href="{{ route('google.login') }}" class="group relative flex items-center justify-center w-full px-4 py-4 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-[#0F4E88]/30 transition-all duration-300 transform active:scale-95 z-10 overflow-hidden">
                <!-- Efecto hover de fondo suave -->
                <div class="absolute inset-0 bg-gradient-to-r from-[#0F4E88]/5 to-[#33AD72]/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                
                <img class="h-6 w-6 mr-3 relative z-10" src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google logo">
                <span class="text-gray-700 font-bold text-sm relative z-10 group-hover:text-[#0F4E88] transition-colors">
                    Acceder con Google
                </span>
            </a>
        </div>

        <p class="text-center text-xs text-gray-400 mt-8 font-medium">
            &copy; {{ date('Y') }} Sistema de Inventario Premium.
        </p>
    </div>

</body>
</html>