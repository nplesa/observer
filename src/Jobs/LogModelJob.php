<?php

namespace nplesa\Observer\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use nplesa\Observer\Models\LogModel;

class LogModelJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        try {
            LogModel::create($this->data);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
