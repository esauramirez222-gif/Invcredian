<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Recursos</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo_credian.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Ocultar las flechas nativas del input number para un look más limpio */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type="number"] {
            -moz-appearance: textfield;
        }
        
        /* Animación suave de entrada para las tarjetas */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="text-gray-800 font-sans antialiased min-h-screen relative selection:bg-[#33AD72] selection:text-white">

    <!-- Fondo Glassmorphism (Orbes difuminados) -->
    <div class="fixed inset-0 z-[-1] bg-[#F8FAFC] overflow-hidden pointer-events-none">
        <div class="absolute top-[-15%] left-[-10%] w-[600px] h-[600px] rounded-full bg-[#0F4E88]/15 blur-[120px]"></div>
        <div class="absolute bottom-[-15%] right-[-10%] w-[600px] h-[600px] rounded-full bg-[#33AD72]/15 blur-[120px]"></div>
    </div>
    
    <!-- Navbar Flotante Glassmorphism -->
    <nav class="sticky top-0 z-50 bg-white/60 backdrop-blur-xl border-b border-white/50 shadow-[0_4px_30px_rgba(0,0,0,0.03)] transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex justify-between items-center">
            
            <!-- Logo con Imagen -->
            <div class="flex items-center gap-3">
                <!-- Aquí entra tu PNG -->
                <img src="{{ asset('img/logo_credian.png') }}" alt="Logo del Sistema" class="h-10 w-auto drop-shadow-sm transition-transform duration-300 hover:scale-105">
                
                <h1 class="font-bold text-2xl tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-[#0F4E88] to-[#33AD72]">
                    Inventario
                </h1>
            </div>
            
            <!-- Menú -->
            <div class="flex items-center gap-6">
                <a href="{{ route('catalog.list') }}" class="group relative text-sm font-semibold text-gray-700 hover:text-[#0F4E88] transition-colors">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 opacity-70 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Mi Solicitud
                    </span>
                    @if($listCount > 0)
                        <span class="absolute -top-2 -right-3 bg-gradient-to-r from-[#0F4E88] to-[#33AD72] text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-md transform scale-100 group-hover:scale-110 transition-transform">
                            {{ $listCount }}
                        </span>
                    @endif
                </a>
                
                <div class="w-px h-6 bg-gray-300/50"></div>

                @auth
                    <!-- 1. Si es Administrador, mostramos el Panel -->
                    @can('admin')
                        <a href="{{ route('loans.index') }}" class="text-sm font-medium text-gray-500 hover:text-[#0F4E88] transition-colors">Ir al Panel</a>
                    @endcan
                    
                    <!-- 2. Botón de Salir para CUALQUIER usuario logueado -->
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 inline-flex items-center">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-gray-500 hover:text-red-500 transition-colors">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-[#0F4E88] transition-colors">Acceso</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        
        <!-- Alertas -->
        @if(session('success'))
            <div class="mb-8 p-4 bg-[#33AD72]/10 border border-[#33AD72]/20 backdrop-blur-md text-[#22774e] rounded-2xl shadow-sm flex items-center gap-3 animate-fade-up">
                <svg class="w-6 h-6 text-[#33AD72]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Encabezado de la página -->
        <div class="mb-10 text-center sm:text-left animate-fade-up" style="animation-delay: 0.1s;">
            <h2 class="text-4xl lg:text-5xl font-extrabold tracking-tight text-gray-900 mb-4">
                Recursos <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#0F4E88] to-[#33AD72]">Disponibles</span>
            </h2>
            <p class="text-gray-500 text-lg max-w-2xl">Selecciona los equipos o accesorios que necesitas y agrégalos a tu solicitud de préstamo.</p>
        </div>

        <!-- Filtros de Categorías (Píldoras Premium) -->
        <div class="mb-12 flex flex-wrap justify-center sm:justify-start gap-3 animate-fade-up" style="animation-delay: 0.2s;">
            <a href="{{ route('catalog.index') }}" 
               class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 shadow-sm
               {{ !request('category') 
                    ? 'bg-gradient-to-r from-[#0F4E88] to-[#33AD72] text-white shadow-md shadow-[#0F4E88]/20 scale-105' 
                    : 'bg-white/60 backdrop-blur-md text-gray-600 border border-white/60 hover:bg-white hover:text-[#0F4E88] hover:shadow-md' }}">
                Explorar Todo
            </a>
            
            @foreach($categories as $category)
                <a href="{{ route('catalog.index', ['category' => $category->id]) }}" 
                   class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 shadow-sm
                   {{ request('category') == $category->id 
                        ? 'bg-gradient-to-r from-[#0F4E88] to-[#33AD72] text-white shadow-md shadow-[#0F4E88]/20 scale-105' 
                        : 'bg-white/60 backdrop-blur-md text-gray-600 border border-white/60 hover:bg-white hover:text-[#0F4E88] hover:shadow-md' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <!-- Grid de Tarjetas (Glassmorphism) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 xl:gap-8">
            @foreach($resources as $index => $resource)
                <div class="group flex flex-col bg-white/50 backdrop-blur-xl border border-white/80 rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgba(15,78,136,0.12)] transition-all duration-500 ease-out hover:-translate-y-2 relative overflow-hidden animate-fade-up" style="animation-delay: {{ 0.1 * ($index % 8) }}s;">
                    
                    <!-- Resplandor sutil en hover -->
                    <div class="absolute inset-0 bg-gradient-to-br from-[#0F4E88]/[0.03] to-[#33AD72]/[0.03] opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                    <!-- Contenido Superior -->
                    <div class="relative z-10 flex-grow">
                        <div class="flex justify-between items-start mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold text-[#0F4E88] bg-[#0F4E88]/10 tracking-wide uppercase">
                                {{ $resource->category->name }}
                            </span>
                            <!-- Indicador de Disponibilidad -->
                            <div class="flex items-center gap-1.5 bg-[#33AD72]/10 px-2 py-1 rounded-full border border-[#33AD72]/20">
                                <span class="flex h-2 w-2 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#33AD72] opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#33AD72]"></span>
                                </span>
                                <span class="text-[11px] font-bold text-[#33AD72]">{{ $resource->available_quantity }} left</span>
                            </div>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-1 leading-tight group-hover:text-[#0F4E88] transition-colors">{{ $resource->name }}</h3>
                        <p class="text-sm text-gray-400 font-mono mb-6">Ref: {{ $resource->internal_code }}</p>
                    </div>
                    
                    <!-- Área de Acción -->
                    <div class="relative z-10 pt-5 border-t border-gray-200/50 mt-auto">
                        <form action="{{ route('catalog.add', $resource) }}" method="POST" class="flex gap-3 relative">
                            @csrf
                            
                            <!-- Input Number Personalizado -->
                            <div class="relative w-20">
                                <label for="quantity-{{ $resource->id }}" class="sr-only">Cantidad</label>
                                <input type="number" id="quantity-{{ $resource->id }}" name="quantity" min="1" max="{{ $resource->available_quantity }}" value="1" 
                                       class="w-full h-12 bg-white/70 backdrop-blur-sm border border-gray-200 rounded-2xl text-center font-bold text-gray-800 shadow-sm focus:border-[#0F4E88] focus:ring-2 focus:ring-[#0F4E88]/20 transition-all outline-none">
                            </div>

                            <!-- Botón con Degradado y Microinteracción -->
                            <button type="submit" class="flex-1 h-12 relative overflow-hidden rounded-2xl text-white font-bold shadow-md transition-all duration-300 transform active:scale-95 group/btn">
                                <!-- Fondo normal -->
                                <div class="absolute inset-0 bg-gradient-to-r from-[#0F4E88] to-[#1564ad] transition-opacity duration-300 group-hover/btn:opacity-0"></div>
                                <!-- Fondo en hover (Invirtiendo al secundario) -->
                                <div class="absolute inset-0 bg-gradient-to-r from-[#0F4E88] to-[#33AD72] opacity-0 group-hover/btn:opacity-100 transition-opacity duration-500"></div>
                                
                                <span class="relative z-10 flex items-center justify-center gap-2 h-full">
                                    Solicitar
                                    <svg class="w-4 h-4 transition-transform duration-300 group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación con estilo -->
        <div class="mt-12 animate-fade-up" style="animation-delay: 0.5s;">
            {{ $resources->links() }}
        </div>
    </main>

</body>
</html>