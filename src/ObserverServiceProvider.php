<?php

namespace nplesa\Observer;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Http\Kernel;
use nplesa\Observer\Observers\ModelObserver;
use nplesa\Observer\Http\Middleware\LogRequests;

class ObserverServiceProvider extends ServiceProvider
{
    public function register()
    {
        // încarcă config default dacă nu a fost publicat
        $this->mergeConfigFrom(
            __DIR__.'/config/observer.php',
            'observer'
        );
    }

    public function boot()
    {
        // publicare config
        $this->publishes([
            __DIR__.'/config/observer.php' => config_path('observer.php')
        ], 'config');

        // load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // REQUEST LOGGING
        if (config('observer.log_requests.enabled')) {
            $this->app->make(Kernel::class)
                ->pushMiddleware(LogRequests::class);
        }

        // MODEL LOGGING
        if (!empty(config('observer.log_models.enabled'))) {
            $models = config('observer.log_models.only');

            if (!empty($models)) {
                foreach ($models as $model) {
                    $model::observe(ModelObserver::class);
                }
            } else {
                // toate modelele
                Model::observe(ModelObserver::class);
            }
        }
    }
}
