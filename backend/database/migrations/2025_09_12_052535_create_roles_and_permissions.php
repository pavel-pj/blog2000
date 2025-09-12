<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Services\RoleMigrationService;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        RoleMigrationService::deleteALlRoles();
        RoleMigrationService::createRoles();
        RoleMigrationService::createPermissions();
        RoleMigrationService::givePermissionsToRoles();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        RoleMigrationService::deleteALlRoles();
    }
};
