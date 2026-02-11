<?php

namespace nplesa\Observer\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use nplesa\Observer\Models\LogRequest;

class LogRequests
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $logConfig = config('observer.log_requests', []);

        if (empty($logConfig['enabled'])) {
            return $response;
        }

        $ignoreMethods = $logConfig['ignore_methods'] ?? [];
        if (in_array($request->method(), $ignoreMethods)) {
            return $response;
        }

        $excludeRoutes = $logConfig['exclude_routes'] ?? [];
        foreach ($excludeRoutes as $pattern) {
            if ($request->is($pattern)) {
                return $response;
            }
        }

        $status = $response->status();
        $logAll = $logConfig['log_all_statuses'] ?? true;
        if (!$logAll && $status < 400) {
            return $response;
        }

        $userId = optional($request->user())->id;

        try {
            LogRequest::create([
                'method'     => $request->method(),
                'url'        => $request->fullUrl(),
                'ip'         => $request->ip(),
                'user_id'    => $userId,          // <-- aici salvăm user_id
                'user_agent' => $request->userAgent(),
                'status'     => $status,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        return $response;
    }
}
