<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $guard = 'web';

        // Crear permisos con guard
        $permissions = ['users.index', 'users.create', 'users.edit', 'users.delete'];
        foreach ($permissions as $name) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => $guard]
            );
        }

        // Crear rol con guard
        $admin = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => $guard]
        );

        // Asignar permisos al rol
        $admin->syncPermissions($permissions);

        // Asignar rol al primer usuario
        $user = User::first();
        if ($user && !$user->hasRole('admin')) {
            $user->assignRole('admin');
        }
    }
}
