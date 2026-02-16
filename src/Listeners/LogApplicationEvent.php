<?php

namespace nplesa\observer\Listeners;

use nplesa\observer\Models\LogEvent;
use nplesa\observer\Jobs\LogEventJob;

class LogApplicationEvent
{
    public function handle($event)
    {
        $config = config('observer.log_events', []);
        if (empty($config['enabled'])) return;

        // dacă avem only, verificăm
        if (!empty($config['only']) && !in_array(get_class($event), $config['only'])) {
            return;
        }

        $data = [
            'event_class' => get_class($event),
            'payload' => method_exists($event,'toArray') ? $event->toArray() : (array) $event,
            'user_id' => auth()->id(),
        ];

        if (!empty($config['queue'])) {
            LogEventJob::dispatch($data);
        } else {
            LogEvent::create($data);
        }
    }
}
