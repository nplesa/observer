<?php

namespace nplesa\observer\Listeners;

class LogApplicationEvent
{
    public function handle($event, $payload)
    {
        $eventClass = is_object($event) ? get_class($event) : (string)$event;
        $payloadArray = is_array($payload) ? $payload : [$payload];

        \Log::info("Event fired: {$eventClass}", $payloadArray);
    }
}
