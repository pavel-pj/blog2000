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
        Schema::create('repetition_words', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('word_id');
            $table->uuid('repetition_id');

              // Добавляем внешний ключ
            $table->foreign('word_id')
                ->references('id')
                ->on('words');

                  // Добавляем внешний ключ
            $table->foreign('repetition_id')
                ->references('id')
                ->on('repetitions');
             
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repetition_words');
    }
};
