<?php

namespace nplesa\observer\Observers;

use nplesa\observer\Models\LogModel;
use nplesa\observer\Jobs\LogModelJob;

class ModelObserver
{
    public function created($model)  { 
        $this->handle('created', $model); 
    }
    
    public function updated($model)  { 
        $this->handle('updated', $model); 
    }
    
    public function deleted($model)  { 
        $this->handle('deleted', $model); 
    }
    
    public function restored($model) { 
        $this->handle('restored', $model); 
    }

    protected function handle(string $event, $model)
    {
        if ($model instanceof LogModel) {
            return;
        }

        $config = config('observer.log_models', []);

        if (empty($config['enabled'])) {
            return;
        }

        $oldValues = null;
        $newValues = null;

        if ($event === 'updated' && !empty($config['log_only_dirty'])) {
            $changes = $model->getChanges();
            $oldValues = array_intersect_key($model->getOriginal(), $changes);
            $newValues = $changes;
        } else {
            $oldValues = $model->getOriginal();
            $newValues = $model->getAttributes();
        }

        $data = [
            'model_type' => get_class($model),
            'model_id'   => $model->getKey(),
            'event'      => $event,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'user_id'    => auth()->id(),
        ];

        if (!empty($config['queue'])) {
            LogModelJob::dispatch($data);
        } else {
            LogModel::create($data);
        }
    }
}
