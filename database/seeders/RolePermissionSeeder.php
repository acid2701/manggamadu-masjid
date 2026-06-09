<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view dashboard',
            'manage donations',
            'approve donations',
            'manage finance',
            'export finance',
            'manage posts',
            'manage events',
            'manage gallery',
            'manage committee',
            'manage users',
            'manage settings',
            'manage donation categories',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Super Admin — all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions($permissions);

        // Ketua — dashboard, donations, finance, content management
        $ketua = Role::firstOrCreate(['name' => 'ketua']);
        $ketua->syncPermissions([
            'view dashboard',
            'manage donations',
            'approve donations',
            'manage finance',
            'export finance',
            'manage posts',
            'manage events',
            'manage gallery',
            'manage committee',
        ]);

        // Sekretaris — dashboard, content management only
        $sekretaris = Role::firstOrCreate(['name' => 'sekretaris']);
        $sekretaris->syncPermissions([
            'view dashboard',
            'manage posts',
            'manage events',
            'manage gallery',
            'manage committee',
        ]);

        // Bendahara — dashboard, donations, finance only
        $bendahara = Role::firstOrCreate(['name' => 'bendahara']);
        $bendahara->syncPermissions([
            'view dashboard',
            'manage donations',
            'approve donations',
            'manage finance',
            'export finance',
            'manage donation categories',
        ]);
    }
}
