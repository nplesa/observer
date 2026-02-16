<?php

namespace nplesa\observer\Listeners;

use Illuminate\Support\Arr;

class LogApplicationEvent
{
    public function handle($event, $payload)
    {
        try {
            $eventClass = is_object($event) ? get_class($event) : (string)$event;
            $payloadArray = is_array($payload) ? $payload : [$payload];

            $config = config('observer.log_events', []);

            $ignore = Arr::get($config, 'ignore', []);
            if (in_array($eventClass, $ignore)) {
                return;
            }

            $only = Arr::get($config, 'only', []);
            if (!empty($only)) {
                $match = false;
                foreach ($only as $prefix) {
                    if (str_starts_with($eventClass, $prefix)) {
                        $match = true;
                        break;
                    }
                }
                if (!$match) return;
            }

            \Log::info("Event fired: {$eventClass}", $payloadArray);
        } catch (\Throwable $e) {
            \Log::warning("LogApplicationEvent: failed to log event: ".$e->getMessage());
        }
    }
}
