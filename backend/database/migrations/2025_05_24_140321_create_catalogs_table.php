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
        Schema::create('catalogs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name',255);
            //$table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();

            // Внешний ключ на эту же таблицу
           // $table->foreign('parent_id')
           //     ->references('id')
           //    ->on('catalogs');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogs');
    }
};
