<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'create-users']);
        Permission::create(['name' => 'edit-users']);
        Permission::create(['name' => 'delete-users']);

        Permission::create(['name' => 'create-region']);
        Permission::create(['name' => 'edit-region']);
        Permission::create(['name' => 'delete-region']);

        Permission::create(['name' => 'create-sub-region']);
        Permission::create(['name' => 'edit-sub-region']);
        Permission::create(['name' => 'delete-sub-region']);

        Permission::create(['name' => 'create-community']);
        Permission::create(['name' => 'edit-community']);
        Permission::create(['name' => 'delete-community']);

        Permission::create(['name' => 'create-household']);
        Permission::create(['name' => 'edit-household']);
        Permission::create(['name' => 'delete-household']);

        Permission::create(['name' => 'create-electricity-user']);
        Permission::create(['name' => 'edit-electricity-user']);
        Permission::create(['name' => 'delete-electricity-user']);

        Permission::create(['name' => 'create-water-user']);
        Permission::create(['name' => 'edit-water-user']);
        Permission::create(['name' => 'delete-water-user']);

        Permission::create(['name' => 'create-internet-user']);
        Permission::create(['name' => 'edit-internet-user']);
        Permission::create(['name' => 'delete-internet-user']);

        Permission::create(['name' => 'create-meter']);
        Permission::create(['name' => 'edit-meter']);
        Permission::create(['name' => 'delete-meter']);

        Permission::create(['name' => 'create-household-meter']);
        Permission::create(['name' => 'edit-household-meter']);
        Permission::create(['name' => 'delete-household-meter']);

        Permission::create(['name' => 'create-sub-community']);
        Permission::create(['name' => 'edit-sub-community']);
        Permission::create(['name' => 'delete-sub-community']);

        Permission::create(['name' => 'create-profession']);
        Permission::create(['name' => 'edit-profession']);
        Permission::create(['name' => 'delete-profession']);

        Permission::create(['name' => 'create-community-donor']);
        Permission::create(['name' => 'edit-community-donor']);
        Permission::create(['name' => 'delete-community-donor']);

        Permission::create(['name' => 'create-second-name-community']);
        Permission::create(['name' => 'edit-second-name-community']);
        Permission::create(['name' => 'delete-second-name-community']);

        Permission::create(['name' => 'create-public-structure']);
        Permission::create(['name' => 'edit-public-structure']);
        Permission::create(['name' => 'delete-public-structure']);

        Permission::create(['name' => 'create-vendor']);
        Permission::create(['name' => 'edit-vendor']);
        Permission::create(['name' => 'delete-vendor']);

        Permission::create(['name' => 'create-vendor-user']);
        Permission::create(['name' => 'edit-vendor-user']);
        Permission::create(['name' => 'delete-vendor-user']);

        Permission::create(['name' => 'create-vendor-community']);
        Permission::create(['name' => 'edit-vendor-community']);
        Permission::create(['name' => 'delete-vendor-community']);

        Permission::create(['name' => 'create-community-representative']);
        Permission::create(['name' => 'edit-community-representative']);
        Permission::create(['name' => 'delete-community-representative']);

        Permission::create(['name' => 'create-vendor']);
        Permission::create(['name' => 'edit-vendor']);
        Permission::create(['name' => 'delete-vendor']);

        Permission::create(['name' => 'create-vendor']);
        Permission::create(['name' => 'edit-vendor']);
        Permission::create(['name' => 'delete-vendor']);

        Permission::create(['name' => 'create-vendor']);
        Permission::create(['name' => 'edit-vendor']);
        Permission::create(['name' => 'delete-vendor']);

        

        $adminRole = Role::create(['name' => 'Admin']);
        $editorRole = Role::create(['name' => 'Editor']);

        $adminRole->givePermissionTo([
            'create-users',
            'edit-users',
            'delete-users',
            'create-region',
            'edit-region',
            'delete-region',
            'create-sub-region',
            'edit-sub-region',
            'delete-sub-region',
            'create-community',
            'edit-community',
            'delete-community',
            'create-household',
            'edit-household',
            'delete-household',
            'create-electricity-user',
            'edit-electricity-user',
            'delete-electricity-user',
            'create-water-user',
            'edit-water-user',
            'delete-water-user',
            'create-internet-user',
            'edit-internet-user',
            'delete-internet-user',
            'create-meter',
            'edit-meter',
            'delete-meter',
            'create-household-meter',
            'edit-household-meter',
            'delete-household-meter',
            'create-sub-community',
            'edit-sub-community',
            'delete-sub-community',
        ]);

        $editorRole->givePermissionTo([
            'create-blog-posts',
            'edit-blog-posts',
            'delete-blog-posts',
        ]);
    }
}
