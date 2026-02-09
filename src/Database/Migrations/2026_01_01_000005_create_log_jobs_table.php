<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('log_jobs', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->json('payload')->nullable();
            $table->string('status')->default('pending');
            $table->uuid('correlation_id')->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('log_jobs'); }
};
