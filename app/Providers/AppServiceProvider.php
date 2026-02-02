<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Ici tu peux enregistrer des services, bindings ou singletons si besoin
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Longueur par défaut pour les string
        Schema::defaultStringLength(191);

        // Macro pour réponses JSON standardisées
        Response::macro('api', function ($data = [], $status = 200, $message = 'success') {
            return response()->json([
                'status' => $status < 300 ? 'success' : 'error',
                'message' => $message,
                'data' => $data,
            ], $status);
        });

       
    }
}
