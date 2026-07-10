<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('inventory.index') }}" class="w-10 h-10 rounded-full bg-white/50 flex items-center justify-center text-gray-500 hover:bg-white hover:text-[#0F4E88] hover:shadow-md transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-extrabold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-[#0F4E88] to-[#33AD72] tracking-tight">
                Agregar Nuevo Recurso
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 animate-fade-up">
            <div class="bg-white/50 backdrop-blur-xl border border-white/80 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
                
                <form action="{{ route('inventory.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-1 ml-1">Nombre del Recurso</label>
                            <input type="text" name="name" required value="{{ old('name') }}" class="w-full bg-white/60 backdrop-blur-sm border border-white/80 rounded-xl px-4 py-3 text-gray-800 focus:bg-white focus:border-[#0F4E88]/50 focus:ring-2 focus:ring-[#0F4E88]/20 transition-all shadow-sm" placeholder="Ej. Proyector Epson 1080p">
                            @error('name') <span class="text-red-500 text-xs font-semibold ml-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Código Interno -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 ml-1">Código Interno</label>
                            <input type="text" name="internal_code" required value="{{ old('internal_code') }}" class="w-full bg-white/60 backdrop-blur-sm border border-white/80 rounded-xl px-4 py-3 text-gray-800 font-mono focus:bg-white focus:border-[#0F4E88]/50 focus:ring-2 focus:ring-[#0F4E88]/20 transition-all shadow-sm" placeholder="Ej. PRJ-001">
                            @error('internal_code') <span class="text-red-500 text-xs font-semibold ml-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Categoría -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 ml-1">Categoría</label>
                            <select name="category_id" required class="w-full bg-white/60 backdrop-blur-sm border border-white/80 rounded-xl px-4 py-3 text-gray-800 focus:bg-white focus:border-[#0F4E88]/50 focus:ring-2 focus:ring-[#0F4E88]/20 transition-all shadow-sm appearance-none">
                                <option value="">Selecciona una categoría...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-red-500 text-xs font-semibold ml-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Cantidad Total -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 ml-1">Cantidad Total</label>
                            <input type="number" name="total_quantity" required min="1" value="{{ old('total_quantity', 1) }}" class="w-full bg-white/60 backdrop-blur-sm border border-white/80 rounded-xl px-4 py-3 text-gray-800 font-bold focus:bg-white focus:border-[#0F4E88]/50 focus:ring-2 focus:ring-[#0F4E88]/20 transition-all shadow-sm">
                            @error('total_quantity') <span class="text-red-500 text-xs font-semibold ml-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Estado (CORREGIDO PARA ENVIAR 'status') -->
                        <div>
                            <div class="flex items-center mt-6 bg-white/40 px-4 py-3 rounded-xl border border-white/80 shadow-sm">
                                <!-- Si no se marca el check, envía 'inactive' -->
                                <input type="hidden" name="status" value="inactive">
                                <!-- Si se marca el check, envía 'active' -->
                                <input type="checkbox" name="status" id="status" value="active" {{ old('status', 'active') == 'active' ? 'checked' : '' }} class="w-5 h-5 text-[#33AD72] border-gray-300 rounded focus:ring-[#33AD72]">
                                <label for="status" class="ml-3 text-sm font-bold text-gray-700 cursor-pointer">Recurso Activo (Visible en catálogo)</label>
                            </div>
                            @error('status') <span class="text-red-500 text-xs font-semibold ml-1 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Botón Guardar -->
                    <div class="mt-8 pt-6 border-t border-gray-200/50">
                        <button type="submit" class="w-full group relative inline-flex items-center justify-center px-6 py-4 font-bold text-white transition-all duration-300 bg-gradient-to-r from-[#0F4E88] to-[#1564ad] rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] overflow-hidden active:scale-95">
                            <div class="absolute inset-0 bg-gradient-to-r from-[#0F4E88] to-[#33AD72] opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <span class="relative flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Guardar Recurso en Inventario
                            </span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>