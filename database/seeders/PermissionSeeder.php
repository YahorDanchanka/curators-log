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

        $groupPermissions = ['groups.viewAny', 'groups.viewOwn', 'groups.create', 'groups.editOwn', 'groups.deleteOwn'];

        $studentPermissions = [
            'students.viewAny',
            'students.viewOwn',
            'students.create',
            'students.achievements',
            'students.asocialBehaviors',
            'students.expertAdvice',
            'students.individualWorks',
        ];

        $permissions = [
            ...$permissions,
            ...$specialtyPermissions,
            ...$curatorPermissions,
            ...$groupPermissions,
            ...$studentPermissions,
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // create roles and assign existing permissions
        $curator = Role::create(['name' => 'curator']);
        $curator->givePermissionTo([
            ...$specialtyPermissions,
            ...$curatorPermissions,
            ...$groupPermissions,
            ...$studentPermissions,
        ]);

        // create roles and assign existing permissions
        $psychologist = Role::create(['name' => 'psychologist']);
        $psychologist->givePermissionTo([
            $groupPermissions[0],
            'students.viewAny',
            'students.viewOwn',
            'students.achievements',
            'students.asocialBehaviors',
            'students.expertAdvice',
            'students.individualWorks',
        ]);

        Role::create(['name' => 'admin']);
    }
}
