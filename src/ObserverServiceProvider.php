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
use ReflectionClass;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

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
                // Observăm modelele specificate în config
                foreach ($models as $model) {
                    $this->observeConcreteModel($model);
                }
            } else {
                // Observăm toate modelele concrete din app/Models (recursiv)
                $modelsPath = app_path('Models');
                if (is_dir($modelsPath)) {
                    $iterator = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($modelsPath)
                    );

                    foreach ($iterator as $file) {
                        if (!$file->isFile() || $file->getExtension() !== 'php') {
                            continue; // ignorăm foldere și fișiere non-PHP
                        }

                        $relativePath = str_replace($modelsPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                        $class = 'App\\Models\\' . str_replace([DIRECTORY_SEPARATOR, '.php'], ['\\', ''], $relativePath);

                        $this->observeConcreteModel($class);
                    }
                }
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

    /**
     * Aplică ModelObserver doar modelelor concrete.
     */
    protected function observeConcreteModel(string $class): void
    {
        if (!class_exists($class)) {
            return; // clasa nu există
        }

        if (!is_subclass_of($class, Model::class)) {
            return; // nu e un model Eloquent
        }

        $reflection = new ReflectionClass($class);
        if ($reflection->isAbstract()) {
            return; // ignorăm abstractele
        }

        $class::observe(ModelObserver::class);
    }
}
