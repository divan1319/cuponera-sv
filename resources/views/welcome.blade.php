@extends('layouts.app')
@section('title', 'La Cuponera SV')

@section('content')
<div class="-mx-4 -mt-4 space-y-0 sm:-mx-6 lg:-mx-8">
    {{-- Hero --}}
    <section class="relative overflow-hidden px-4 pb-16 pt-10 sm:px-6 sm:pb-20 sm:pt-14 lg:px-8 lg:pb-24">
        <div class="absolute inset-0 -z-10 bg-gradient-to-br from-slate-950 via-blue-950 to-indigo-950"></div>
        <div class="absolute -right-24 top-0 -z-10 h-96 w-96 rounded-full bg-blue-500/20 blur-3xl sm:right-0"></div>
        <div class="absolute -left-32 bottom-0 -z-10 h-80 w-80 rounded-full bg-indigo-500/15 blur-3xl"></div>
        <div class="absolute inset-0 -z-10 opacity-[0.35]" style="background-image: url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%23ffffff%22 fill-opacity=%220.06%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

        <div class="relative mx-auto max-w-7xl">
            <div class="max-w-2xl">
                <p class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-blue-200 backdrop-blur-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                    </span>
                    Ofertas verificadas · El Salvador
                </p>
                <h1 class="mt-6 text-4xl font-extrabold leading-[1.1] tracking-tight text-white sm:text-5xl lg:text-6xl">
                    Ahorra en grande con
                    <span class="bg-gradient-to-r from-amber-200 via-yellow-200 to-amber-100 bg-clip-text text-transparent">cupones reales</span>
                </h1>
                <p class="mt-6 max-w-lg text-lg leading-relaxed text-blue-100/90">
                    Explora descuentos de negocios aprobados, compra con confianza y disfruta promociones que sí valen la pena.
                </p>
                <div class="mt-10 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                    <a href="#ofertas"
                       class="inline-flex items-center justify-center rounded-xl bg-white px-7 py-3.5 text-sm font-bold text-slate-900 shadow-lg shadow-black/20 transition hover:bg-blue-50 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-2 focus:ring-offset-slate-900">
                        Ver ofertas
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    </a>
                    @guest
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-white/25 bg-white/5 px-7 py-3.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:border-white/40 hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/40">
                        Ya tengo cuenta
                    </a>
                    @endguest
                </div>
            </div>


        </div>
    </section>

    {{-- Credenciales / valor --}}
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-6 sm:grid-cols-3">
            <div class="group rounded-2xl border border-gray-200/80 bg-white p-6 shadow-sm transition hover:border-blue-200 hover:shadow-md">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-600/25">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                </div>
                <h2 class="mt-4 text-sm font-bold text-gray-900">Empresas revisadas</h2>
                <p class="mt-2 text-sm leading-relaxed text-gray-600">Solo publicamos ofertas de negocios con solicitud aprobada en la plataforma.</p>
            </div>
            <div class="group rounded-2xl border border-gray-200/80 bg-white p-6 shadow-sm transition hover:border-emerald-200 hover:shadow-md">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/25">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h2 class="mt-4 text-sm font-bold text-gray-900">Precios claros</h2>
                <p class="mt-2 text-sm leading-relaxed text-gray-600">Compara precio regular vs oferta y vigencia del cupón sin letras pequeñas ocultas.</p>
            </div>
            <div class="group rounded-2xl border border-gray-200/80 bg-white p-6 shadow-sm transition hover:border-violet-200 hover:shadow-md">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-600 text-white shadow-lg shadow-violet-600/25">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75v-4.125c0-.621.504-1.125 1.125-1.125h4.125c.621 0 1.125.504 1.125 1.125v4.125zM9 9.75h6.75a.75.75 0 00.75-.75V6a.75.75 0 00-.75-.75H9A.75.75 0 008.25 6v3a.75.75 0 00.75.75zm9 0h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25H16.5c-1.52 0-2.76-1.227-2.613-2.742.142-1.443.966-2.672 2.13-3.35a2.25 2.25 0 011.724-.314V9.75z"/></svg>
                </div>
                <h2 class="mt-4 text-sm font-bold text-gray-900">Stock visible</h2>
                <p class="mt-2 text-sm leading-relaxed text-gray-600">Ves cuántos cupones quedan; si se agotan, lo indicamos sin rodeos.</p>
            </div>
        </div>
    </section>

    {{-- Ofertas --}}
    <section id="ofertas" class="scroll-mt-28 border-t border-gray-200/80 bg-gray-50/80 px-4 py-16 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Ofertas disponibles</h2>
                    <p class="mt-2 max-w-xl text-sm text-gray-600 sm:text-base">
                        Promociones vigentes de negocios verificados.
                    </p>
                </div>
            </div>

            @if($ofertas->isEmpty())
                <div class="mx-auto mt-12 max-w-md rounded-2xl border border-dashed border-gray-300 bg-white px-8 py-14 text-center shadow-sm">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-500">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="mt-4 text-base font-medium text-gray-900">Pronto tendremos novedades</p>
                    <p class="mt-2 text-sm text-gray-600">Vuelve en otro momento o regístrate para no perderte las próximas ofertas.</p>
                </div>
            @else
                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($ofertas as $oferta)
                        <x-oferta-publica-card :oferta="$oferta" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Footer de página --}}
    <footer class="border-t border-gray-200 bg-white px-4 py-14 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="flex flex-col gap-12 lg:flex-row lg:justify-between">
                <div class="max-w-sm">
                    <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight text-gray-900">
                        La Cuponera <span class="text-blue-600">SV</span>
                    </a>
                    <p class="mt-4 text-sm leading-relaxed text-gray-600">
                        La forma sencilla de descubrir ofertas locales y gestionar cupones con negocios de confianza.
                    </p>
                </div>

                @guest
                <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3 lg:gap-16">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Cuenta</h3>
                        <ul class="mt-4 space-y-3">
                            <li>
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 transition hover:text-blue-600">Iniciar sesión</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Clientes</h3>
                        <ul class="mt-4 space-y-3">
                            <li>
                                <a href="{{ route('cliente.register') }}" class="text-sm font-medium text-gray-700 transition hover:text-blue-600">Crear cuenta cliente</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Negocios</h3>
                        <ul class="mt-4 space-y-3">
                            <li>
                                <a href="{{ route('empresa.register') }}" class="text-sm font-medium text-gray-700 transition hover:text-blue-600">Registrar mi empresa</a>
                            </li>
                        </ul>
                    </div>
                </div>
                @else
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Tu cuenta</h3>
                    <ul class="mt-4 space-y-3">
                        @if(Auth::user()->rol?->nombre === 'Empresa')
                            <li><a href="{{ route('empresa.dashboard') }}" class="text-sm font-medium text-gray-700 transition hover:text-blue-600">Ir al panel empresa</a></li>
                        @elseif(Auth::user()->rol?->nombre === 'Cliente')
                            <li><a href="{{ route('cliente.dashboard') }}" class="text-sm font-medium text-gray-700 transition hover:text-blue-600">Ir a mi cuenta</a></li>
                        @elseif(Auth::user()->rol?->nombre === 'Admin')
                            <li><a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-700 transition hover:text-blue-600">Panel administración</a></li>
                        @endif
                    </ul>
                </div>
                @endguest
            </div>

            <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-gray-100 pt-8 sm:flex-row">
                <p class="text-xs text-gray-500">
                    © {{ date('Y') }} La Cuponera SV. Todos los derechos reservados.
                </p>
                <a href="#ofertas" class="text-xs font-medium text-blue-600 hover:text-blue-700">↑ Volver a ofertas</a>
            </div>
        </div>
    </footer>
</div>
@endsection
