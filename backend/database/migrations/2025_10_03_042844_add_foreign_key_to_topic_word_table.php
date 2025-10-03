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
        Schema::table('topic_words', function (Blueprint $table) {
           
        $table->foreign('word_id')
                  ->references('id')
                  ->on('words')
                  ->onDelete('cascade');
                  
            // Add foreign key constraint for topic_id
            $table->foreign('topic_id')
                  ->references('id')
                  ->on('topics')
                  ->onDelete('cascade');
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topic_words', function (Blueprint $table) {
            $table->dropForeign(['word_id']);
            $table->dropForeign(['topic_id']);
        });
    }
};
