<?php

namespace App\Providers;

use App\Events\EmpresaSolicitudRegistrada;
use App\Listeners\EnviarCorreoNuevaSolicitudEmpresa;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        Event::listen(EmpresaSolicitudRegistrada::class, EnviarCorreoNuevaSolicitudEmpresa::class);
    }
}
