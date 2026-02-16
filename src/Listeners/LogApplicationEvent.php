<?php

namespace nplesa\observer\Listeners;

use Illuminate\Contracts\Events\Dispatcher;

class LogApplicationEvent
{
    /**
     * Handle the event.
     *
     * @param  mixed  $event
     * @param  array  $payload
     * @return void
     */
    public function handle($event, $payload)
    {
        // Determinăm numele evenimentului
        $eventClass = is_object($event) ? get_class($event) : (string)$event;

        // Convertim payload în array pentru logging
        $payloadArray = is_array($payload) ? $payload : [$payload];

        // Logăm evenimentul
        \Log::info("Event fired: {$eventClass}", $payloadArray);
    }
}
