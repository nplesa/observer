<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_models', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('event'); // created, updated, deleted, restored
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
            $table->index('event');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_models');
    }
};
