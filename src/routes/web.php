<?php
use Illuminate\Support\Facades\Route;
use nplesa\Observer\Http\Livewire\RequestsTable;
use nplesa\Observer\Http\Livewire\DbTable;
use nplesa\Observer\Http\Livewire\EventsTable;
use nplesa\Observer\Http\Livewire\RecordedRequestsTable;
use nplesa\Observer\Http\Livewire\JobsTable;

Route::prefix('observer')->group(function () {
    Route::get('/', fn() => view('observer::dashboard'));
    Route::get('/requests', RequestsTable::class);
    Route::get('/db', DbTable::class);
    Route::get('/events', EventsTable::class);
    Route::get('/recorded', RecordedRequestsTable::class);
    Route::get('/jobs', JobsTable::class);
});
