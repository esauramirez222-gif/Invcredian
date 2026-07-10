<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-[#0F4E88] to-[#33AD72] tracking-tight">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Tarjetas de Estadísticas (Glassmorphism) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-up">
                
                <!-- Tarjeta 1: Total de Recursos -->
                <div class="bg-white/50 backdrop-blur-xl border border-white/80 rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center">
                        <div class="p-4 rounded-2xl bg-gradient-to-br from-[#0F4E88]/20 to-[#0F4E88]/5 text-[#0F4E88] mr-4 border border-[#0F4E88]/10">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total de Inventario</p>
                            <p class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-[#0F4E88] to-[#1564ad]">{{ $stats['total_resources'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 2: Préstamos Pendientes -->
                <div class="bg-white/50 backdrop-blur-xl border border-white/80 rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-lg hover:-translate-y-1 transition-all duration-300" style="animation-delay: 0.1s;">
                    <div class="flex items-center">
                        <div class="p-4 rounded-2xl bg-gradient-to-br from-yellow-500/20 to-yellow-500/5 text-yellow-600 mr-4 border border-yellow-500/10">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Por Revisar</p>
                            <p class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-yellow-500 to-yellow-600">{{ $stats['pending_loans'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 3: Stock Bajo -->
                <div class="bg-white/50 backdrop-blur-xl border border-white/80 rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-lg hover:-translate-y-1 transition-all duration-300" style="animation-delay: 0.2s;">
                    <div class="flex items-center">
                        <div class="p-4 rounded-2xl bg-gradient-to-br from-red-500/20 to-red-500/5 text-red-600 mr-4 border border-red-500/10">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Stock Crítico (≤ 5)</p>
                            <p class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-red-600">{{ $stats['low_stock'] }}</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>