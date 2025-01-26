<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('ai_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('prompt');
            $table->text('response')->nullable();
            $table->string('model')->default('gpt-4');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('ai_requests');
    }
};
