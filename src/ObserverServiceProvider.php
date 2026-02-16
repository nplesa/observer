<?php
namespace nplesa\Observer;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider {
    public function boot() {
        $this->publishes([__DIR__.'/config/observer.php'=>config_path('observer.php')],'config');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->app->make(Kernel::class)
        ->pushMiddleware(\nplesa\Observer\Http\Middleware\ObserverMiddleware::class);
    }
    public function register(){
        $this->mergeConfigFrom(
            __DIR__.'/config/observer.php',
            'observer'
        );
    }
}
