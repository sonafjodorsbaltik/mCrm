<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Forget cached permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions

        $permissions = [
            'view tickets',
            'update ticket status',
            'delete tickets',
            'manage users'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles            
            
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);

        // Assign permissions to roles

        $adminRole->syncPermissions($permissions);
        $managerRole->syncPermissions([
            'view tickets',
            'update ticket status',
        ]);
    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Role::whereIn('name', ['admin', 'manager'])->delete();
        Permission::whereIn('name', [
            'view tickets', 
            'update ticket status', 
            'delete tickets', 
            'manage users'
        ])->delete();
    }
};
