<?php

namespace nplesa\observer\Listeners;

class LogApplicationEvent
{
    public function handle($event, $payload)
    {
        try {
            $eventClass = is_object($event) ? get_class($event) : (string)$event;

            $only = config('observer.log_events.only', []);
            $match = false;
            foreach ($only as $prefix) {
                if (str_starts_with($eventClass, $prefix)) {
                    $match = true;
                    break;
                }
            }
            if (!$match) return;

            $payloadArray = is_array($payload) ? $payload : [$payload];

            $jsonPayload = json_encode($payloadArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

            \Log::info("Event fired: {$eventClass} - Payload: {$jsonPayload}");

        } catch (\Throwable $e) {
            \Log::warning("LogApplicationEvent failed: ".$e->getMessage());
        }
    }
}
