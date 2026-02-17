<?php

namespace nplesa\observer\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use nplesa\observer\Models\LogModel;
use nplesa\observer\Support\ObserverContext;

class LogModelJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected array $data;
    protected int $user_id;

    public function __construct(array $data, ?int $user_id)
    {
        $this->data = $data;
        $this->user_id = $user_id;
    }

    public function handle()
    {
        ObserverContext::setUserId($this->user_id);
        try {
            LogModel::create($this->data);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
