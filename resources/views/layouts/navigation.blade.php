<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Título / Logo -->
                <!-- Imagen / Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('loans.index') }}">
                        <img src="{{ asset('img/logo_credian.png') }}" alt="Logo del Sistema" class="block h-10 w-auto transition-transform duration-300 hover:scale-105" />
                    </a>
                </div>

                <!-- Enlaces de Navegación -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @php
                        // Consultamos cuántas solicitudes pendientes hay
                        $pendingLoans = \App\Models\Loan::where('status', 'pending')->count();
                    @endphp

                    <!-- NUEVO: Enlace al Catálogo (Welcome) -->
                    <x-nav-link :href="route('catalog.index')" :active="request()->routeIs('catalog.*')">
                        {{ __('Solicitudes') }}
                    </x-nav-link>

                    <!-- Enlace de Inventario -->
                    <x-nav-link :href="route('inventory.index')" :active="request()->routeIs('inventory.*')">
                        {{ __('Inventario') }}
                    </x-nav-link>

                    <!-- Enlace de Pendientes (Admin) -->
                    <x-nav-link :href="route('loans.index')" :active="request()->routeIs('loans.*')" class="relative flex items-center gap-1.5">
                        {{ __('Pendientes') }}
                        
                        @if($pendingLoans > 0)
                            <span class="flex items-center justify-center w-5 h-5 text-[11px] font-bold text-white bg-gradient-to-br from-[#0F4E88] to-[#33AD72] rounded-full shadow-sm shadow-[#33AD72]/30 mb-3 -ml-1 z-10">
                                {{ $pendingLoans }}
                            </span>
                        @endif
                    </x-nav-link>
                </div>
            </div>

            <!-- Menú Desplegable del Usuario (Derecha) -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Botón para cerrar sesión -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Botón de Hamburguesa para Móviles -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menú Responsivo (Móviles) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <!-- NUEVO: Enlace al Catálogo Móvil -->
            <x-responsive-nav-link :href="route('catalog.index')" :active="request()->routeIs('catalog.*')">
                {{ __('Solicitudes') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('inventory.index')" :active="request()->routeIs('inventory.*')">
                {{ __('Inventario') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('loans.index')" :active="request()->routeIs('loans.*')">
                {{ __('Pendientes') }} ({{ $pendingLoans }})
            </x-responsive-nav-link>
        </div>

        <!-- Opciones de Usuario Móvil -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>