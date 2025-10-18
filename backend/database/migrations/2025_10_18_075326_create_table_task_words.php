<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_words', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('word_id');
            $table->uuid('task_id');

              // Добавляем внешний ключ
            $table->foreign('word_id')
                ->references('id')
                ->on('words');

                  // Добавляем внешний ключ
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks');
             
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_words');
    }
};
