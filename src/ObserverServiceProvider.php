<?php
namespace nplesa\Observer;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use nplesa\Observer\Http\Livewire\RequestsTable;
use nplesa\Observer\Http\Livewire\DbTable;
use nplesa\Observer\Http\Livewire\EventsTable;
use nplesa\Observer\Http\Livewire\RecordedRequestsTable;
use nplesa\Observer\Http\Livewire\JobsTable;

class ObserverServiceProvider extends ServiceProvider {
    public function boot() {
        $this->publishes([__DIR__.'/config/observer.php'=>config_path('observer.php')],'config');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        Livewire::component('requests-table',RequestsTable::class);
        Livewire::component('db-table',DbTable::class);
        Livewire::component('events-table',EventsTable::class);
        Livewire::component('recorded-requests-table',RecordedRequestsTable::class);
        Livewire::component('jobs-table',JobsTable::class);
        $this->loadViewsFrom(__DIR__.'/resources/views','observer');
    }
    public function register(){}
}
