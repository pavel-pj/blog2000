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
        Schema::create('repetitions', function (Blueprint $table) {
        
            $table->uuid('id')->primary();
            $table->uuid('subject_id');
            $table->string('task');
            $table->string('answer');
            $table->timestamps();
             
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
        Schema::dropIfExists('repetitions');
    }
};
