<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-[#0F4E88] to-[#33AD72] tracking-tight">
            Gestión de Inventario
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-[#33AD72]/10 border border-[#33AD72]/20 backdrop-blur-md text-[#22774e] rounded-2xl shadow-sm flex items-center gap-3 animate-fade-up">
                    <svg class="w-6 h-6 text-[#33AD72]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Controles Superiores (Filtros y Botón Nuevo) -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6 animate-fade-up" style="animation-delay: 0.1s;">
                
                <!-- Filtros de Categorías -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('inventory.index') }}" 
                       class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 shadow-sm
                       {{ !request('category') 
                            ? 'bg-gradient-to-r from-[#0F4E88] to-[#33AD72] text-white shadow-md shadow-[#0F4E88]/20 scale-105' 
                            : 'bg-white/60 backdrop-blur-md text-gray-600 border border-white/60 hover:bg-white hover:text-[#0F4E88] hover:shadow-md' }}">
                        Todas
                    </a>
                    
                    @foreach($categories as $category)
                        <a href="{{ route('inventory.index', ['category' => $category->id]) }}" 
                           class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 shadow-sm
                           {{ request('category') == $category->id 
                                ? 'bg-gradient-to-r from-[#0F4E88] to-[#33AD72] text-white shadow-md shadow-[#0F4E88]/20 scale-105' 
                                : 'bg-white/60 backdrop-blur-md text-gray-600 border border-white/60 hover:bg-white hover:text-[#0F4E88] hover:shadow-md' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>

                <!-- Botón Nuevo Recurso -->
                <div>
                    <a href="{{ route('inventory.create') }}" class="group relative inline-flex items-center justify-center px-6 py-3 font-bold text-white transition-all duration-300 bg-gradient-to-r from-[#0F4E88] to-[#1564ad] rounded-2xl shadow-lg shadow-[#0F4E88]/20 hover:scale-105 hover:shadow-xl hover:shadow-[#0F4E88]/30 overflow-hidden active:scale-95 whitespace-nowrap">
                        <div class="absolute inset-0 bg-gradient-to-r from-[#0F4E88] to-[#33AD72] opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <span class="relative flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Nuevo Recurso
                        </span>
                    </a>
                </div>
            </div>

            <!-- Tabla de Inventario (Glassmorphism) -->
            <div class="bg-white/50 backdrop-blur-xl border border-white/80 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden animate-fade-up" style="animation-delay: 0.2s;">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/40 border-b border-white/60">
                                <th class="p-5 font-semibold text-gray-500 text-xs tracking-widest uppercase">Código</th>
                                <th class="p-5 font-semibold text-gray-500 text-xs tracking-widest uppercase">Nombre</th>
                                <th class="p-5 font-semibold text-gray-500 text-xs tracking-widest uppercase">Categoría</th>
                                <th class="p-5 font-semibold text-gray-500 text-xs tracking-widest uppercase text-center">Disponible</th>
                                <th class="p-5 font-semibold text-gray-500 text-xs tracking-widest uppercase text-center">Estado</th>
                                <th class="p-5 font-semibold text-gray-500 text-xs tracking-widest uppercase text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/40">
                            @forelse ($resources as $resource)
                                <tr class="hover:bg-white/60 transition-colors duration-200">
                                    <td class="p-5 text-sm font-mono text-gray-500">{{ $resource->internal_code }}</td>
                                    <td class="p-5 font-bold text-gray-800">{{ $resource->name }}</td>
                                    <td class="p-5">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold text-[#0F4E88] bg-[#0F4E88]/10 tracking-wider uppercase border border-[#0F4E88]/20">
                                            {{ $resource->category->name }}
                                        </span>
                                    </td>
                                    <td class="p-5 text-center">
                                        <span class="font-extrabold text-lg {{ $resource->available_quantity > 5 ? 'text-[#33AD72]' : 'text-red-500' }}">
                                            {{ $resource->available_quantity }}
                                        </span>
                                        <span class="text-xs text-gray-400 font-medium block">/ {{ $resource->total_quantity }} total</span>
                                    </td>
                                    <!-- AQUI CORREGIMOS LA LECTURA DEL ESTADO -->
                                    <td class="p-5 text-center">
                                        @if($resource->status === 'active')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold text-[#33AD72] bg-[#33AD72]/10 border border-[#33AD72]/20">
                                                <span class="relative flex h-2 w-2">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#33AD72] opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#33AD72]"></span>
                                                </span>
                                                Activo
                                            </span>
                                        @elseif($resource->status === 'maintenance')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold text-yellow-600 bg-yellow-100 border border-yellow-200">
                                                En Mantenimiento
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold text-red-600 bg-red-100 border border-red-200">
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-5 text-right">
                                        <div class="flex justify-end gap-4 items-center">
                                            <a href="{{ route('inventory.edit', $resource) }}" class="text-[#0F4E88] hover:text-[#33AD72] font-semibold text-sm transition-colors duration-300">Editar</a>
                                            <form action="{{ route('inventory.destroy', $resource) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este recurso?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600 font-semibold text-sm transition-colors duration-300">Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-white/50 rounded-full flex items-center justify-center mb-4 border border-white">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            </div>
                                            <p class="text-gray-500 font-medium">No hay recursos registrados en el inventario.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 animate-fade-up" style="animation-delay: 0.3s;">
                {{ $resources->links() }}
            </div>
        </div>
    </div>
</x-app-layout>