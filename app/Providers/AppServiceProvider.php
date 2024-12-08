<?php

namespace App\Providers;

use App\Services\AutumPlatformService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        Relation::enforceMorphMap([
            'user' => 'App\Models\User',
            'team' => 'App\Models\Team',
        ]);

        $this->app->bind(AutumPlatformService::class, function(Application $app) {

            return new AutumPlatformService(
                env('AUTUM_API_KEY'),
                env('APP_ENV'),
            );
        });
    }
}
