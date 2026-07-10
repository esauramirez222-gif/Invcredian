<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('inventory.index') }}" class="w-10 h-10 rounded-full bg-white/50 flex items-center justify-center text-gray-500 hover:bg-white hover:text-[#0F4E88] hover:shadow-md transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-extrabold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-[#0F4E88] to-[#33AD72] tracking-tight">
                Editar Recurso: {{ $resource->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 animate-fade-up">
            <div class="bg-white/50 backdrop-blur-xl border border-white/80 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
                
                <form action="{{ route('inventory.update', $resource) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-1 ml-1">Nombre del Recurso</label>
                            <input type="text" name="name" required value="{{ old('name', $resource->name) }}" class="w-full bg-white/60 backdrop-blur-sm border border-white/80 rounded-xl px-4 py-3 text-gray-800 focus:bg-white focus:border-[#0F4E88]/50 focus:ring-2 focus:ring-[#0F4E88]/20 transition-all shadow-sm">
                            @error('name') <span class="text-red-500 text-xs font-semibold ml-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Código Interno -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 ml-1">Código Interno</label>
                            <input type="text" name="internal_code" required value="{{ old('internal_code', $resource->internal_code) }}" class="w-full bg-white/60 backdrop-blur-sm border border-white/80 rounded-xl px-4 py-3 text-gray-800 font-mono focus:bg-white focus:border-[#0F4E88]/50 focus:ring-2 focus:ring-[#0F4E88]/20 transition-all shadow-sm">
                            @error('internal_code') <span class="text-red-500 text-xs font-semibold ml-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Categoría -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 ml-1">Categoría</label>
                            <select name="category_id" required class="w-full bg-white/60 backdrop-blur-sm border border-white/80 rounded-xl px-4 py-3 text-gray-800 focus:bg-white focus:border-[#0F4E88]/50 focus:ring-2 focus:ring-[#0F4E88]/20 transition-all shadow-sm appearance-none">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $resource->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-red-500 text-xs font-semibold ml-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Cantidad Total -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 ml-1">Cantidad Total Física</label>
                            <input type="number" name="total_quantity" required min="1" value="{{ old('total_quantity', $resource->total_quantity) }}" class="w-full bg-white/60 backdrop-blur-sm border border-white/80 rounded-xl px-4 py-3 text-gray-800 font-bold focus:bg-white focus:border-[#0F4E88]/50 focus:ring-2 focus:ring-[#0F4E88]/20 transition-all shadow-sm">
                            @error('total_quantity') <span class="text-red-500 text-xs font-semibold ml-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Estado -->
                        <div>
                            <div class="flex items-center mt-6 bg-white/40 px-4 py-3 rounded-xl border border-white/80 shadow-sm">
                                <input type="hidden" name="status" value="inactive">
                                <input type="checkbox" name="status" id="status" value="active" {{ old('status', $resource->status) === 'active' ? 'checked' : '' }} class="w-5 h-5 text-[#33AD72] border-gray-300 rounded focus:ring-[#33AD72]">
                                <label for="status" class="ml-3 text-sm font-bold text-gray-700 cursor-pointer">Recurso Activo (Visible en catálogo)</label>
                            </div>
                            @error('status') <span class="text-red-500 text-xs font-semibold ml-1 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Botón Guardar -->
                    <div class="mt-8 pt-6 border-t border-gray-200/50 flex gap-4">
                        <a href="{{ route('inventory.index') }}" class="w-1/3 flex items-center justify-center px-6 py-4 font-bold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors duration-300">
                            Cancelar
                        </a>
                        <button type="submit" class="w-2/3 group relative inline-flex items-center justify-center px-6 py-4 font-bold text-white transition-all duration-300 bg-gradient-to-r from-[#0F4E88] to-[#1564ad] rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] overflow-hidden active:scale-95">
                            <div class="absolute inset-0 bg-gradient-to-r from-[#0F4E88] to-[#33AD72] opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <span class="relative flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Actualizar Recurso
                            </span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>