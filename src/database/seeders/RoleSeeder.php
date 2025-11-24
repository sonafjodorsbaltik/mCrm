<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Permissions

        $permissions = [
            'view tickets',
            'update ticket status',
            'delete tickets',
            'manage users'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }


        //Roles            
            
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);


        //Assign permissions to roles

        $adminRole->syncPermissions([
            'view tickets',
            'update ticket status',
            'delete tickets',
            'manage users'       
        ]);

        $managerRole->syncPermissions([
            'view tickets',
            'update ticket status',
        ]);


        //Assign roles to users

        $admin = User::firstOrCreate(
            ['email' => "admin@test.com"],
            [
                'name' => 'Admin',
                'password' => Hash::make('password')
            ]
        );

        $admin->syncRoles($adminRole);

        $manager = User::firstOrCreate(
            ['email' => 'manager@test.com'],
            [
                'name' => 'Manager',
                'password' => Hash::make('password')
            ]
        );

        $manager->syncRoles($managerRole);

    }
}
