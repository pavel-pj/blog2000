<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         $user = User::create([
            'email' => 'admin@m.ru',
            'name' => 'admin',
            'password' => Hash::make('123456'),
        ]);
         
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
