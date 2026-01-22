<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
        <flux:brand href="/dashboard" logo="{{ asset('img/logo_papeleria.png') }}" name="Papelería Artes" class="px-2" />
        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Platform')" class="grid">
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <!-- Productos -->
        @if(Auth::user()->tienePermiso('productos.ver'))
        <flux:navlist.item icon="shopping-bag" href="{{ route('productos') }}" :current="request()->routeIs('productos')">
            Productos
        </flux:navlist.item>
        @endif

        <!-- Categorías -->
        @if(Auth::user()->tienePermiso('categorias.ver'))
        <flux:navlist.item icon="tag" href="{{ route('categorias') }}" :current="request()->routeIs('categorias')">
            Categorías
        </flux:navlist.item>
        @endif

        <flux:spacer />

        <!-- Ventas -->
        @if(Auth::user()->tienePermiso('ventas.ver'))
        <flux:navlist.item icon="shopping-cart" href="{{ route('ventas.index') }}" :current="request()->routeIs('ventas.*')">
            Ventas
        </flux:navlist.item>
        @endif

        <!-- Proveedores -->
        @if(Auth::user()->tienePermiso('proveedores.ver'))
        <flux:navlist.item icon="truck" href="{{ route('proveedores') }}" :current="request()->routeIs('proveedores')">
            Proveedores
        </flux:navlist.item>
        @endif

        <!-- Compras -->
        @if(Auth::user()->tienePermiso('compras.ver'))
        <flux:navlist.item icon="truck" href="{{ route('compras') }}" :current="request()->routeIs('compras')">
            Compras
        </flux:navlist.item>
        @endif

        <flux:spacer />

        <!-- Usuarios -->
        @if(Auth::user()->tienePermiso('usuarios.ver'))
        <flux:navlist.item icon="users" href="{{ route('admin.usuarios.index') }}" :current="request()->routeIs('admin.usuarios.*')">
            Usuarios
        </flux:navlist.item>
        @endif

        <!-- Roles -->
        @if(Auth::user()->tienePermiso('roles.ver'))
        <flux:navlist.item icon="shield-check" href="{{ route('admin.roles.index') }}" :current="request()->routeIs('admin.roles.*')">
            Roles
        </flux:navlist.item>
        @endif

        <!-- Configuraciones -->
        @if(Auth::user()->tienePermiso('configuraciones.ver'))
        <flux:navlist.item icon="cog-6-tooth" href="{{ route('admin.configuraciones.index') }}" :current="request()->routeIs('admin.configuraciones.*')">
            Configuraciones
        </flux:navlist.item>
        @endif

        <flux:spacer />

        <!-- Desktop User Menu -->
        <flux:dropdown class="hidden lg:block" position="bottom" align="start">
            <flux:profile
                :name="auth()->user()->name"
                :initials="auth()->user()->initials()"
                icon:trailing="chevrons-up-down" />
            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>
                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>
                <flux:menu.separator />
                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                </flux:menu.radio.group>
                <flux:menu.separator />
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <flux:spacer />
        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />
            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>
                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>
                <flux:menu.separator />
                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                </flux:menu.radio.group>
                <flux:menu.separator />
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @fluxScripts
</body>
</html>