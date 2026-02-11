<?php
namespace nplesa\Observer;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider {
    public function boot() {
        $this->publishes([__DIR__.'/config/observer.php'=>config_path('observer.php')],'config');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
    public function register(){

    }
}
