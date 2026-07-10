<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-[#0F4E88] to-[#33AD72] tracking-tight">
            Gestión de Solicitudes
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

            <div class="bg-white/50 backdrop-blur-xl border border-white/80 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden animate-fade-up">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/40 border-b border-white/60">
                                <th class="p-5 font-semibold text-gray-500 text-xs tracking-widest uppercase">ID</th>
                                <th class="p-5 font-semibold text-gray-500 text-xs tracking-widest uppercase">Solicitante</th>
                                <th class="p-5 font-semibold text-gray-500 text-xs tracking-widest uppercase">Fecha</th>
                                <th class="p-5 font-semibold text-gray-500 text-xs tracking-widest uppercase text-center">Estado</th>
                                <th class="p-5 font-semibold text-gray-500 text-xs tracking-widest uppercase text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/40">
                            @forelse ($loans as $loan)
                                <tr class="hover:bg-white/60 transition-colors duration-200">
                                    <td class="p-5 text-sm font-mono text-gray-500 font-bold">#{{ str_pad($loan->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="p-5">
                                        <span class="font-bold text-gray-800">{{ $loan->applicant_name }} {{ $loan->applicant_last_name }}</span>
                                    </td>
                                    <td class="p-5 text-sm text-gray-600">{{ $loan->created_at?->format('d/m/Y h:i A') ?? 'N/A' }}</td>
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
                                    <td class="p-5 text-right">
                                        <div class="flex justify-end gap-2 items-center">
                                            <!-- Botón de Revisar (Icono de Ojo) -->
                                            <a href="{{ route('loans.show', $loan) }}" class="p-2 text-[#0F4E88] hover:text-[#33AD72] bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-300 shadow-sm" title="Revisar Solicitud">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>

                                            <!-- Botón de Eliminar (Icono de Basurero) -->
                                            <form action="{{ route('loans.destroy', $loan) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta solicitud de forma permanente?');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-500 hover:text-red-700 bg-white border border-gray-200 rounded-lg hover:bg-red-50 transition-colors duration-300 shadow-sm" title="Eliminar Solicitud">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-white/50 rounded-full flex items-center justify-center mb-4 border border-white">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <p class="text-gray-500 font-medium">No hay solicitudes registradas aún.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 animate-fade-up" style="animation-delay: 0.2s;">
                {{ $loans->links() }}
            </div>
        </div>
    </div>
</x-app-layout>