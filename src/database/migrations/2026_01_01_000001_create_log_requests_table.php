<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('log_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('correlation_id')->nullable();
            $table->string('method');
            $table->string('url');
            $table->integer('status');
            $table->text('payload')->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('log_requests'); }
};
