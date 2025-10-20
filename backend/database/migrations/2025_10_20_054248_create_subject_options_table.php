<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\RepetitionType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subject_options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('subject_id');
            $table->integer('total_rows')->default(30);
            $table->integer('new_words')->default(5);
            $table->integer('important_words')->default(5);
            $table->string('repetition_type')->default(RepetitionType::NEW);
            $table->string('repetition_theme',255)->default('wild animals');
            $table->tinyInteger('row_length')->default(10);
              // Добавляем внешний ключ
            $table->foreign('subject_id')
                ->references('id')
                ->on('subjects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_options');
    }
};
