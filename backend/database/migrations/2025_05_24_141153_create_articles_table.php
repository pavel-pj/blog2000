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
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name',255);
            $table->string('title',255);
            $table->mediumText('html_content')->nullable();
            $table->string('slug')->unique();
            $table->uuid('catalog_id');
            $table->timestamps();

            // Добавляем внешний ключ
            $table->foreign('catalog_id')
                ->references('id')
                ->on('catalogs');


            // Внешний ключ на эту же таблицу
            //$table->foreign('catalog_id')
            //    ->references('id')
            //    ->on('catalogs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
