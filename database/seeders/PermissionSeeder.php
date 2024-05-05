<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [];

        $specialtyPermissions = [
            'specialties.viewAny',
            'specialties.view',
            'specialties.create',
            'specialties.edit',
            'specialties.delete',
        ];

        $curatorPermissions = [
            'curators.viewAny',
            'curators.viewOwn',
            'curators.create',
            'curators.editOwn',
            'curators.deleteOwn',
        ];

        $permissions = [...$permissions, ...$specialtyPermissions, ...$curatorPermissions];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // create roles and assign existing permissions
        $curator = Role::create(['name' => 'curator']);
        $curator->givePermissionTo([...$specialtyPermissions, ...$curatorPermissions]);

        Role::create(['name' => 'admin']);
    }
}
