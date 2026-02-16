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
    // public function register()
    // {
    //     $this->mergeConfigFrom(
    //         __DIR__.'/config/observer.php',
    //         'observer'
    //     );
    // }

    public function boot()
    {
        // Publica config și migrations – sigur la discovery
        $this->publishes([
            __DIR__.'/config/observer.php' => config_path('observer.php')
        ], 'config');

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // Amânăm toate logările și observer-ele până când aplicația e complet încărcată
        $this->app->booted(function () {
            $this->registerRequestMiddleware();
            $this->registerObservers();
            $this->registerGlobalEventListener();
            $this->registerJobLogging();
        });
    }

    protected function registerRequestMiddleware()
    {
        if (!empty(config('observer.log_requests.enabled'))) {
            try {
                $this->app->make(Kernel::class)
                    ->pushMiddleware(LogRequests::class);
            } catch (\Throwable $e) {
                \Log::warning("ObserverServiceProvider: failed to push request middleware: ".$e->getMessage());
            }
        }
    }

    protected function registerObservers()
    {
        if (empty(config('observer.log_models.enabled'))) return;

        $models = config('observer.log_models.only');

        if (!empty($models)) {
            foreach ($models as $model) {
                $this->safeObserveModel($model);
            }
        } else {
            $modelsPath = app_path('Models');
            if (is_dir($modelsPath)) {
                $iterator = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($modelsPath)
                );

                foreach ($iterator as $file) {
                    if (!$file->isFile() || $file->getExtension() !== 'php') continue;

                    $relativePath = str_replace($modelsPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $class = 'App\\Models\\' . str_replace([DIRECTORY_SEPARATOR, '.php'], ['\\', ''], $relativePath);

                    $this->safeObserveModel($class);
                }
            }
        }
    }

    protected function registerGlobalEventListener()
    {
        if (!empty(config('observer.log_events.enabled'))) {
            try {
                Event::listen('*', LogApplicationEvent::class);
            } catch (\Throwable $e) {
                \Log::warning("ObserverServiceProvider: failed to register global event listener: ".$e->getMessage());
            }
        }
    }

    protected function registerJobLogging()
    {
        if (!empty(config('observer.log_jobs.enabled'))) {
            try {
                Queue::before(function ($event) {
                    (new LogJobs())->handle($event->job, fn($job) => $job);
                });
            } catch (\Throwable $e) {
                \Log::warning("ObserverServiceProvider: failed to register job listener: ".$e->getMessage());
            }
        }
    }

    protected function safeObserveModel(string $class): void
    {
        if (!class_exists($class)) return;
        if (!is_subclass_of($class, Model::class)) return;

        try {
            $reflection = new ReflectionClass($class);
            if ($reflection->isAbstract()) return;

            $class::observe(ModelObserver::class);
        } catch (\Throwable $e) {
            \Log::warning("ObserverServiceProvider: failed to observe $class: ".$e->getMessage());
        }
    }
}
