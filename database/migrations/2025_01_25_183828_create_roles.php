<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // roles
        $admin = Role::create(['name' => 'admin']);
        Role::create(['name' => 'profesor']);

        // permissions
        $addProfesor = Permission::create(['name' => 'add profesors']);
        $editAbsence = Permission::create(['name' => 'edit absence']);
        $deleteAbsence = Permission::create(['name' => 'delete absence']);

        // assign permissions to roles
        $admin->givePermissionTo([$addProfesor, $editAbsence, $deleteAbsence]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // nothing to do here
    }
};
