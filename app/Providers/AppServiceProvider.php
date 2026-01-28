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
        // ex: $this->app->bind(SomeInterface::class, SomeImplementation::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Optionnel : fixer une longueur par défaut pour les champs string pour PostgreSQL/MySQL
        Schema::defaultStringLength(191);

        // Exemple : réponse JSON par défaut pour toutes les réponses
        Response::macro('api', function ($data = [], $status = 200, $message = 'success') {
            return response()->json([
                'status' => $status < 300 ? 'success' : 'error',
                'message' => $message,
                'data' => $data,
            ], $status);
        });
    }
}
