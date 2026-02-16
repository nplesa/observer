<?php

namespace nplesa\observer;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use nplesa\observer\Http\Middleware\LogRequests;
use nplesa\observer\Http\Middleware\LogJobs;
use nplesa\observer\Observers\ModelObserver;
use nplesa\observer\Listeners\LogApplicationEvent;

class ObserverServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/observer.php',
            'observer'
        );
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/observer.php' => config_path('observer.php')
        ], 'config');

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // REQUEST LOGGING
        if (!empty(config('observer.log_requests.enabled'))) {
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
                Model::observe(ModelObserver::class);
            }
        }

        // EVENT LOGGING
        if (!empty(config('observer.log_events.enabled'))) {
            Event::listen('*', LogApplicationEvent::class);
        }

        // JOB LOGGING
        if (!empty(config('observer.log_jobs.enabled'))) {
            Queue::before(function ($event) {
                (new LogJobs())->handle($event->job, fn($job) => $job);
            });
        }
    }
}
