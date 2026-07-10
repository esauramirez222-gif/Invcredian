<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('loans.index') }}" class="w-10 h-10 rounded-full bg-white/50 flex items-center justify-center text-gray-500 hover:bg-white hover:text-[#0F4E88] hover:shadow-md transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-extrabold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-[#0F4E88] to-[#33AD72] tracking-tight">
                Solicitud #{{ str_pad($loan->id, 4, '0', STR_PAD_LEFT) }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white/50 backdrop-blur-xl border border-white/80 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden animate-fade-up">
                
                <!-- 1. Datos del Solicitante -->
                <div class="p-8 border-b border-white/60">
                    <h3 class="text-sm font-bold text-[#0F4E88] tracking-widest uppercase mb-4">Información del Solicitante</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white/60 p-4 rounded-xl border border-white">
                            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Nombre Completo</p>
                            <p class="text-lg font-bold text-gray-800">{{ $loan->applicant_name }} {{ $loan->applicant_last_name }}</p>
                        </div>
                        <div class="bg-white/60 p-4 rounded-xl border border-white">
                            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Fecha de Solicitud</p>
                            <p class="text-lg font-bold text-gray-800">{{ $loan->created_at?->format('d/m/Y h:i A') ?? 'N/A' }}</p>
                        </div>
                        @if($loan->notes)
                            <div class="md:col-span-2 bg-white/60 p-4 rounded-xl border border-white">
                                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Notas / Motivo</p>
                                <p class="text-gray-700 italic">"{{ $loan->notes }}"</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 2. Recursos Solicitados -->
                <div class="p-8">
                    <h3 class="text-sm font-bold text-[#0F4E88] tracking-widest uppercase mb-4">Recursos Solicitados</h3>
                    <div class="bg-white/60 rounded-2xl border border-white overflow-hidden">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-white/40 border-b border-white/60">
                                    <th class="p-4 font-semibold text-gray-500 text-xs uppercase">Recurso</th>
                                    <th class="p-4 font-semibold text-gray-500 text-xs uppercase text-center">Cant. Pedida</th>
                                    <th class="p-4 font-semibold text-gray-500 text-xs uppercase text-center">Stock Disponible</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/40">
                                @foreach($loan->items as $item)
                                    <tr>
                                        <td class="p-4">
                                            <p class="font-bold text-gray-800">{{ $item->resource->name }}</p>
                                            <p class="text-xs font-mono text-gray-500">{{ $item->resource->internal_code }}</p>
                                        </td>
                                        <td class="p-4 text-center">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 font-extrabold text-gray-800">{{ $item->quantity }}</span>
                                        </td>
                                        <td class="p-4 text-center">
                                            <span class="font-extrabold text-lg {{ $item->resource->available_quantity < $item->quantity ? 'text-red-500' : 'text-[#33AD72]' }}">
                                                {{ $item->resource->available_quantity }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. Controles de Acción -->
                <div class="p-8 bg-gray-50/50 border-t border-white/60">
                    @if($loan->status === 'pending')
                        <div class="flex flex-col sm:flex-row gap-4">
                            <!-- Botón Aprobar -->
                            <form action="{{ route('loans.approve', $loan) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Confirmas aprobar este préstamo y descontar el inventario?');">
                                @csrf
                                <button type="submit" class="w-full bg-[#33AD72] hover:bg-[#288f5d] text-white px-6 py-4 rounded-xl font-bold transition-all shadow-md hover:shadow-lg active:scale-95 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Autorizar Préstamo
                                </button>
                            </form>

                            <!-- Botón Rechazar -->
                            <form action="{{ route('loans.reject', $loan) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Estás seguro de rechazar esta solicitud?');">
                                @csrf
                                <button type="submit" class="w-full bg-white text-red-500 border-2 border-red-100 hover:border-red-500 hover:bg-red-50 px-6 py-4 rounded-xl font-bold transition-all shadow-sm active:scale-95 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Declinar Solicitud
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white/60 p-6 rounded-2xl border border-white">
                            <div>
                                <span class="text-gray-500 font-medium text-sm">Estado Actual:</span>
                                <span class="ml-2 font-bold uppercase px-4 py-1.5 rounded-full text-xs shadow-sm
                                    {{ $loan->status === 'approved' ? 'bg-[#33AD72]/10 text-[#33AD72] border border-[#33AD72]/20' : 
                                      ($loan->status === 'rejected' ? 'bg-red-100 text-red-600 border border-red-200' : 'bg-[#0F4E88]/10 text-[#0F4E88] border border-[#0F4E88]/20') }}">
                                    {{ $loan->status === 'approved' ? 'Aprobado' : ($loan->status === 'rejected' ? 'Rechazado' : 'Devuelto') }}
                                </span>
                            </div>
                            
                            <!-- Botón Devolución (Solo si está Aprobado) -->
                            @if($loan->status === 'approved')
                                <form action="{{ route('loans.return', $loan) }}" method="POST" onsubmit="return confirm('¿Confirmas que los equipos fueron devueltos?');">
                                    @csrf
                                    <button type="submit" class="bg-[#0F4E88] hover:bg-[#0c3e6d] text-white px-6 py-3 rounded-xl font-bold transition-all shadow-md hover:shadow-lg active:scale-95 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        Registrar Devolución
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>