<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('log_dbactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('correlation_id')->nullable();
            $table->string('query');
            $table->float('duration_ms');
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('log_dbactions'); }
};
