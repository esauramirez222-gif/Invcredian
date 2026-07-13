<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
        // 1. Forzar HTTPS en Vercel (Producción)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // 2. Crear la regla de seguridad (El Gafete VIP)
        Gate::define('admin', function (User $user) {
            $admins = ['jaziel@credian.mx', 'leonardo@credian.mx'];
            // Devuelve 'true' solo si el correo del usuario está en la lista
            return in_array($user->email, $admins);
        });
    }
}