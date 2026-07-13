<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Solicitud e Historial</title>
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
<body class="text-gray-800 font-sans antialiased min-h-screen relative selection:bg-[#33AD72] selection:text-white">

    <!-- Fondo Glassmorphism (Orbes difuminados) -->
    <div class="fixed inset-0 z-[-1] bg-[#F8FAFC] overflow-hidden pointer-events-none">
        <div class="absolute top-[-15%] left-[-10%] w-[600px] h-[600px] rounded-full bg-[#0F4E88]/15 blur-[120px]"></div>
        <div class="absolute bottom-[-15%] right-[-10%] w-[600px] h-[600px] rounded-full bg-[#33AD72]/15 blur-[120px]"></div>
    </div>
    
    <!-- Navbar Flotante Glassmorphism -->
    <nav class="sticky top-0 z-50 bg-white/60 backdrop-blur-xl border-b border-white/50 shadow-[0_4px_30px_rgba(0,0,0,0.03)] transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex justify-between items-center">
            
            <!-- Botón Volver -->
            <div class="flex items-center gap-3">
                <a href="{{ route('catalog.index') }}" class="w-10 h-10 rounded-xl bg-white/80 border border-white flex items-center justify-center shadow-sm hover:shadow-md hover:scale-105 transition-all text-[#0F4E88] group">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="font-bold text-xl tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-[#0F4E88] to-[#33AD72] hidden sm:block">
                    Volver al Catálogo
                </h1>
            </div>
            
            <!-- Indicador de Solicitud -->
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Lista de Préstamo</span>
                <div class="w-2 h-2 rounded-full bg-[#33AD72] animate-pulse"></div>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- ========================================== -->
        <!-- SECCIÓN 1: CARRITO DE SOLICITUD ACTUAL     -->
        <!-- ========================================== -->

        <!-- Encabezado -->
        <div class="mb-10 text-center animate-fade-up">
            <h2 class="text-3xl lg:text-4xl font-extrabold tracking-tight text-gray-900 mb-3">
                Tu <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#0F4E88] to-[#33AD72]">Solicitud</span>
            </h2>
            <p class="text-gray-500 max-w-xl mx-auto">Revisa los equipos que has seleccionado y completa tus datos para procesar el préstamo de forma rápida y segura.</p>
        </div>

        <!-- Alertas de Error -->
        @if(session('error'))
            <div class="mb-8 p-4 bg-red-50/80 backdrop-blur-md border border-red-200 text-red-600 rounded-2xl shadow-sm animate-fade-up flex items-center gap-3">
                <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @if(count($list ?? []) > 0)
            <div class="flex flex-col lg:flex-row gap-8 items-start mb-16">
                
                <!-- Columna Izquierda: Lista de Equipos (Glassmorphism) -->
                <div class="w-full lg:w-3/5 bg-white/50 backdrop-blur-xl border border-white/80 rounded-[2rem] p-6 sm:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] animate-fade-up" style="animation-delay: 0.1s;">
                    <div class="flex items-center justify-between border-b border-gray-200/50 pb-5 mb-6">
                        <h3 class="font-extrabold text-xl text-[#0F4E88]">Equipos Seleccionados</h3>
                        <span class="px-3 py-1 bg-[#0F4E88]/10 text-[#0F4E88] font-bold rounded-full text-sm border border-[#0F4E88]/20">{{ count($list) }} Ítems</span>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($list as $id => $details)
                            <div class="group flex items-center justify-between p-4 bg-white/60 rounded-2xl border border-white hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                                <div>
                                    <h4 class="font-bold text-gray-800 text-lg group-hover:text-[#0F4E88] transition-colors">{{ $details['name'] }}</h4>
                                    <p class="text-xs text-gray-400 font-mono mt-0.5">Ref: {{ $details['code'] ?? 'N/A' }}</p>
                                </div>
                                <div class="flex items-center gap-5 sm:gap-6">
                                    <div class="text-center bg-gray-50/50 px-4 py-2 rounded-xl border border-gray-100">
                                        <span class="block text-[10px] uppercase tracking-wider text-gray-400 font-bold mb-0.5">Cant.</span>
                                        <span class="text-xl font-extrabold text-[#33AD72]">{{ $details['quantity'] }}</span>
                                    </div>
                                    <form action="{{ route('catalog.remove', $id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-full bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors hover:shadow-md" title="Eliminar de la lista">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Columna Derecha: Formulario de Checkout (Tarjeta Premium) -->
                <div class="w-full lg:w-2/5 bg-gradient-to-br from-[#0F4E88] to-[#1564ad] rounded-[2rem] p-8 shadow-xl shadow-[#0F4E88]/20 text-white relative overflow-hidden animate-fade-up" style="animation-delay: 0.2s;">
                    
                    <!-- Elementos decorativos de fondo -->
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-[#33AD72]/20 to-transparent"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <h3 class="font-extrabold text-xl">Completar Solicitud</h3>
                        </div>
                        
                        <form action="{{ route('catalog.submit') }}" method="POST" class="space-y-5">
                            @csrf
                            
                            <!-- Información Automática del Usuario -->
                            <div class="bg-white/10 border border-white/20 rounded-xl p-4 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-[#33AD72] flex items-center justify-center text-white font-bold text-xl shadow-inner">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-xs text-white/70 font-semibold uppercase tracking-wider mb-0.5">Solicitante Autorizado</p>
                                    <p class="text-white font-bold text-lg leading-none">{{ Auth::user()->name }}</p>
                                    <p class="text-white/60 text-sm mt-1">{{ Auth::user()->email }}</p>
                                </div>
                            </div>

                            <!-- Solo dejamos el motivo opcional -->
                            <div>
                                <label class="block text-sm font-semibold text-white/90 mb-1.5 ml-1">Motivo del Préstamo <span class="text-white/50 font-normal">(Opcional)</span></label>
                                <textarea name="notes" rows="2" 
                                          class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3.5 text-white placeholder-white/40 focus:bg-white/20 focus:border-white/50 focus:ring-0 transition-all shadow-sm font-medium resize-none" 
                                          placeholder="Ej. Presentación en sala de juntas"></textarea>
                            </div>
                            
                            <button type="submit" class="w-full mt-6 relative group overflow-hidden bg-white text-[#0F4E88] font-extrabold py-4 px-4 rounded-xl shadow-lg transition-transform transform active:scale-95 flex justify-center items-center gap-2">
                                <span class="relative z-10 flex items-center gap-2">
                                    Confirmar Préstamo
                                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </span>
                                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <!-- Estado Vacío (Empty State) -->
            <div class="max-w-2xl mx-auto text-center py-16 px-4 bg-white/50 backdrop-blur-xl border border-white/80 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] animate-fade-up mb-16">
                <!-- Logo en lugar del icono gris -->
                <div class="flex justify-center mb-8">
                    <img src="{{ asset('img/logo_credian.png') }}" alt="Logo del Sistema" class="h-24 w-auto drop-shadow-sm opacity-75 hover:opacity-100 transition-opacity duration-300">
                </div>
                <h3 class="text-2xl font-extrabold text-gray-800 mb-3">Tu lista está vacía</h3>
                <p class="text-gray-500 mb-8 text-lg">Aún no has seleccionado ningún recurso para tu préstamo. Explora nuestro catálogo para encontrar lo que necesitas.</p>
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-[#0F4E88] to-[#33AD72] text-white px-8 py-4 rounded-full font-bold hover:shadow-lg hover:shadow-[#0F4E88]/20 hover:-translate-y-1 transition-all duration-300">
                    Explorar Catálogo
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        @endif

        <!-- ========================================== -->
        <!-- SECCIÓN 2: HISTORIAL DE PRÉSTAMOS DEL USUARIO -->
        <!-- ========================================== -->
        <div class="animate-fade-up" style="animation-delay: 0.3s;">
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-extrabold tracking-tight text-gray-900">
                    Historial de <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#0F4E88] to-[#33AD72]">Tus Solicitudes</span>
                </h2>
                <p class="text-gray-500 text-sm mt-2">Aquí puedes consultar el estado de los préstamos que has realizado anteriormente.</p>
            </div>

            <div class="bg-white/50 backdrop-blur-xl border border-white/80 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/40 border-b border-white/60">
                                <th class="p-5 font-semibold text-gray-500 text-xs tracking-widest uppercase">Folio</th>
                                <th class="p-5 font-semibold text-gray-500 text-xs tracking-widest uppercase">Fecha</th>
                                <th class="p-5 font-semibold text-gray-500 text-xs tracking-widest uppercase">Equipos Solicitados</th>
                                <th class="p-5 font-semibold text-gray-500 text-xs tracking-widest uppercase text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/40">
                            @forelse ($myLoans as $loan)
                                <tr class="hover:bg-white/60 transition-colors duration-200">
                                    <td class="p-5 text-sm font-mono text-gray-500 font-bold">#{{ str_pad($loan->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="p-5 text-sm text-gray-600">{{ $loan->created_at?->format('d/m/Y h:i A') ?? 'N/A' }}</td>
                                    <td class="p-5">
                                        <ul class="text-sm text-gray-700 space-y-1">
                                            @foreach($loan->items as $item)
                                                <li class="flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-[#0F4E88]/50"></span>
                                                    <span class="font-bold text-[#33AD72]">{{ $item->quantity }}x</span> 
                                                    {{ $item->resource->name ?? 'Recurso eliminado' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="p-5 text-center">
                                        @if($loan->status === 'pending')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold text-yellow-700 bg-yellow-100 border border-yellow-200 uppercase tracking-wider shadow-sm">Pendiente</span>
                                        @elseif($loan->status === 'approved')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold text-[#33AD72] bg-[#33AD72]/10 border border-[#33AD72]/20 uppercase tracking-wider shadow-sm">Aprobado</span>
                                        @elseif($loan->status === 'returned')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold text-[#0F4E88] bg-[#0F4E88]/10 border border-[#0F4E88]/20 uppercase tracking-wider shadow-sm">Devuelto</span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold text-red-600 bg-red-100 border border-red-200 uppercase tracking-wider shadow-sm">Rechazado</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-12 h-12 bg-white/50 rounded-full flex items-center justify-center mb-3 border border-white">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <p class="text-gray-500 font-medium">Aún no tienes historial de préstamos.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>
</body>
</html>