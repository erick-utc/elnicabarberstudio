<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden lg:-my-px lg:ms-2 lg:flex gap-2">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Inicio') }}
                    </x-nav-link>
                    @can('ver permisos')
                    <x-nav-link href="{{ route('permission.index') }}" :active="request()->routeIs('permission.index')">
                        {{ __('Permisos') }}
                    </x-nav-link>
                    @endcan
                    @can('ver roles')
                    <x-nav-link href="{{ route('role.index') }}" :active="request()->routeIs('role.index')">
                        {{ __('Roles') }}
                    </x-nav-link>
                    @endcan
                    @can('ver horarios')
                    <x-nav-link href="{{ route('horario.index') }}" :active="request()->routeIs('horario.index')">
                        {{ __('Horarios') }}
                    </x-nav-link>
                    @endcan
                    @can('ver descansos')
                    <x-nav-link href="{{ route('descanso.index') }}" :active="request()->routeIs('descanso.index')">
                        {{ __('Descansos') }}
                    </x-nav-link>
                    @endcan
                    @can('ver usuarios')
                    <x-nav-link href="{{ route('usuario.index') }}" :active="request()->routeIs('usuario.index')">
                        {{ __('Usuarios') }}
                    </x-nav-link>
                    @endcan
                    @can('ver paquetes')
                    <x-nav-link href="{{ route('paquete.index') }}" :active="request()->routeIs('paquete.index')">
                        {{ __('Paquetes') }}
                    </x-nav-link>
                    @endcan
                    @can('ver citas')
                    <x-nav-link href="{{ route('cita.index') }}" :active="request()->routeIs('cita.index')">
                        {{ __('Citas') }}
                    </x-nav-link>
                    {{-- <x-nav-link href="{{ route('cita.calendar') }}" :active="request()->routeIs('cita.calendar')">
                        {{ __('Calendario Citas') }}
                    </x-nav-link> --}}
                    @endcan
                    @can('ver reportes')
                    <x-nav-link href="{{ route('reporte.index') }}" :active="request()->routeIs('reporte.index')">
                        {{ __('Reportes') }}
                    </x-nav-link>
                    @endcan
                    @can('ver bitacoras')
                    <x-nav-link href="{{ route('bitacora.index') }}" :active="request()->routeIs('bitacora.index')">
                        {{ __('Bitacoras') }}
                    </x-nav-link>
                    @endcan
                    <x-nav-link href="{{ route('acerca.index') }}" :active="request()->routeIs('acerca.index')">
                        {{ __('Acerca de...') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('ayuda.index') }}" :active="request()->routeIs('ayuda.index')">
                        {{ __('Ayuda') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden lg:flex lg:items-center lg:ms-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block fixed w-screen h-screen bg-white': open, 'hidden': ! open}" class="hidden lg:hidden z-50">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Inicio') }}
            </x-responsive-nav-link>
            @can('ver permisos')
            <x-responsive-nav-link href="{{ route('permission.index') }}" :active="request()->routeIs('permission.index')">
                {{ __('Permisos') }}
            </x-responsive-nav-link>
            @endcan
            @can('ver roles')
            <x-responsive-nav-link href="{{ route('role.index') }}" :active="request()->routeIs('role.index')">
                {{ __('Roles') }}
            </x-responsive-nav-link>
            @endcan
            @can('ver horarios')
            <x-responsive-nav-link href="{{ route('horario.index') }}" :active="request()->routeIs('horario.index')">
                {{ __('Horarios') }}
            </x-responsive-nav-link>
            @endcan
            @can('ver descansos')
            <x-responsive-nav-link href="{{ route('descanso.index') }}" :active="request()->routeIs('descanso.index')">
                {{ __('Descansos') }}
            </x-responsive-nav-link>
            @endcan
            @can('ver usuarios')
            <x-responsive-nav-link href="{{ route('usuario.index') }}" :active="request()->routeIs('usuario.index')">
                {{ __('Usuarios') }}
            </x-responsive-nav-link>
            @endcan
            @can('ver citas')
            <x-responsive-nav-link href="{{ route('cita.index') }}" :active="request()->routeIs('cita.index')">
                {{ __('Citas') }}
            </x-responsive-nav-link>
            {{-- <x-responsive-nav-link href="{{ route('cita.calendar') }}" :active="request()->routeIs('cita.calendar')">
                {{ __('Calendario de Citas') }}
            </x-responsive-nav-link> --}}
            @endcan
            @can('ver reportes')
            <x-responsive-nav-link href="{{ route('reporte.index') }}" :active="request()->routeIs('reporte.index')">
                {{ __('Reportes') }}
            </x-responsive-nav-link>
            @endcan
            @can('ver bitacoras')
            <x-responsive-nav-link href="{{ route('bitacora.index') }}" :active="request()->routeIs('bitacora.index')">
                {{ __('Bitacoras') }}
            </x-responsive-nav-link>
            @endcan
            <x-responsive-nav-link href="{{ route('acerca.index') }}" :active="request()->routeIs('acerca.index')">
                {{ __('Acerca de...') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('ayuda.index') }}" :active="request()->routeIs('ayuda.index')">
                {{ __('Ayuda') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-gray-200"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>
