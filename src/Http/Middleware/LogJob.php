<?php

namespace nplesa\Observer\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Queue;
use nplesa\Observer\Models\LogJob as LogJobModel;

class LogJobs
{
    public function handle($job, Closure $next)
    {
        $config = config('observer.log_jobs', []);
        if (empty($config['enabled'])) {
            return $next($job);
        }

        $only = $config['only'] ?? [];

        // dacă avem only, verificăm job-ul
        if (!empty($only) && !in_array(get_class($job), $only)) {
            return $next($job);
        }

        $data = [
            'job_class' => get_class($job),
            'payload' => method_exists($job,'toArray') ? $job->toArray() : (array) $job,
            'user_id' => Auth::id(),
        ];

        // async sau sync
        if (!empty($config['queue'])) {
            dispatch(function() use ($data) {
                LogJobModel::create($data);
            });
        } else {
            LogJobModel::create($data);
        }

        return $next($job);
    }
}
