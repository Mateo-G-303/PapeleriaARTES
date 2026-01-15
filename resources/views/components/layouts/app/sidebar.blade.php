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

        @php
        $rolUsuario = auth()->user()->rol->nombrerol ?? null;
        @endphp

        <!-- Productos - Admin, Propietario y Empleado -->
        @if(in_array($rolUsuario, ['Administrador', 'Propietario', 'Empleado']))
        <flux:navlist.item icon="shopping-bag" href="{{ route('productos') }}" :current="request()->routeIs('productos')">
            Productos
        </flux:navlist.item>
        <!-- Editado Botón para ir a la sección Categorias-->
        <flux:navlist.item icon="tag" href="{{ route('categorias') }}">Categorías</flux:navlist.item>
        <!-- Hasta acá -->
        <flux:spacer />
        @endif

        <!-- Ventas - Solo Propietario y Empleado -->
        @if(in_array($rolUsuario, ['Administrador', 'Propietario', 'Empleado']))
        <flux:navlist.item icon="shopping-cart" href="{{ route('ventas.index') }}" :current="request()->routeIs('ventas.*')">
            Ventas
        </flux:navlist.item>
        @endif

        <!-- Proveedores -->
        <flux:navlist.item icon="truck" href="{{ route('proveedores.index') }}" :current="request()->routeIs('proveedores.*')">
            Proveedores
        </flux:navlist.item>
        <flux:spacer />

        <!-- Reportes - Solo Admin y Propietario -->
        @if(in_array($rolUsuario, ['Administrador', 'Propietario']))
        <flux:navlist.item icon="chart-bar" href="#" :current="false">
            Reportes
        </flux:navlist.item>
        @endif

        <!-- Compras/Proveedores - Solo Admin y Propietario -->
        @if(in_array($rolUsuario, ['Administrador', 'Propietario']))
        <flux:navlist.item icon="truck" href="#" :current="false">
            Compras
        </flux:navlist.item>
        @endif

        <!-- Administración - Solo Administrador -->
        @if($rolUsuario === 'Administrador')
        <flux:navlist.item icon="shield-check" href="{{ route('admin.dashboard') }}" :current="request()->routeIs('admin.*')">
            Administración
        </flux:navlist.item>
        @endif

        <flux:spacer />

        <flux:navlist variant="outline">
            <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
            </flux:navlist.item>

            <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                {{ __('Documentation') }}
            </flux:navlist.item>
        </flux:navlist>

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
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
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
            <flux:profile
                :initials="auth()->user()->initials()"
                icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
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