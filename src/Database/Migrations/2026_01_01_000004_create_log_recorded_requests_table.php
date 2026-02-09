<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('log_recorded_requests', function(Blueprint $table){
            $table->id();
            $table->uuid('correlation_id')->nullable();
            $table->text('payload');
            $table->string('method');
            $table->string('url');
            $table->integer('status')->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('log_recorded_requests'); }
};
