<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Users
        Permission::create(['name' => 'create-users']);
        Permission::create(['name' => 'edit-users']);
        Permission::create(['name' => 'delete-users']);

        // Regions, Sub-regions, Sub-sub-regions
        Permission::create(['name' => 'create-region']);
        Permission::create(['name' => 'edit-region']);
        Permission::create(['name' => 'delete-region']);

        Permission::create(['name' => 'create-sub-region']);
        Permission::create(['name' => 'edit-sub-region']);
        Permission::create(['name' => 'delete-sub-region']);

        Permission::create(['name' => 'create-sub-sub-region']);
        Permission::create(['name' => 'edit-sub-sub-region']);
        Permission::create(['name' => 'delete-sub-sub-region']);

        // Community
        Permission::create(['name' => 'create-community']);
        Permission::create(['name' => 'edit-community']);
        Permission::create(['name' => 'delete-community']);

        Permission::create(['name' => 'create-sub-community']);
        Permission::create(['name' => 'edit-sub-community']);
        Permission::create(['name' => 'delete-sub-community']);

        Permission::create(['name' => 'create-community-donor']);
        Permission::create(['name' => 'edit-community-donor']);
        Permission::create(['name' => 'delete-community-donor']);

        Permission::create(['name' => 'create-second-name-community']);
        Permission::create(['name' => 'edit-second-name-community']);
        Permission::create(['name' => 'delete-second-name-community']);

        Permission::create(['name' => 'create-public-structure']);
        Permission::create(['name' => 'edit-public-structure']);
        Permission::create(['name' => 'delete-public-structure']);

        Permission::create(['name' => 'create-community-representative']);
        Permission::create(['name' => 'edit-community-representative']);
        Permission::create(['name' => 'delete-community-representative']);

        // Household
        Permission::create(['name' => 'create-household']);
        Permission::create(['name' => 'edit-household']);
        Permission::create(['name' => 'delete-household']);

        Permission::create(['name' => 'create-profession']);
        Permission::create(['name' => 'edit-profession']);
        Permission::create(['name' => 'delete-profession']);

        // Energy operations
        Permission::create(['name' => 'create-electricity-user']);
        Permission::create(['name' => 'edit-electricity-user']);
        Permission::create(['name' => 'delete-electricity-user']);

        Permission::create(['name' => 'create-electricity-donor']);
        Permission::create(['name' => 'edit-electricity-donor']);
        Permission::create(['name' => 'delete-electricity-donor']);

        Permission::create(['name' => 'create-electricity-public']);
        Permission::create(['name' => 'edit-electricity-public']);
        Permission::create(['name' => 'delete-electricity-public']);

        Permission::create(['name' => 'create-electricity-shared-public']);
        Permission::create(['name' => 'edit-electricity-shared-public']);
        Permission::create(['name' => 'delete-electricity-shared-public']);

        Permission::create(['name' => 'create-comet-meter']);
        Permission::create(['name' => 'edit-comet-meter']);
        Permission::create(['name' => 'delete-comet-meter']);
        
        Permission::create(['name' => 'create-meter']);
        Permission::create(['name' => 'edit-meter']);
        Permission::create(['name' => 'delete-meter']);

        Permission::create(['name' => 'create-household-meter']);
        Permission::create(['name' => 'edit-household-meter']);
        Permission::create(['name' => 'delete-household-meter']);

        Permission::create(['name' => 'create-energy-maintenance']);
        Permission::create(['name' => 'edit-energy-maintenance']);
        Permission::create(['name' => 'delete-energy-maintenance']);

        // Water operations
        Permission::create(['name' => 'create-water-user']);
        Permission::create(['name' => 'edit-water-user']);
        Permission::create(['name' => 'delete-water-user']);

        Permission::create(['name' => 'create-shared-h2o-user']);
        Permission::create(['name' => 'edit-shared-h2o-user']);
        Permission::create(['name' => 'delete-shared-h2o-user']);

        Permission::create(['name' => 'create-public-water']);
        Permission::create(['name' => 'edit-public-water']);
        Permission::create(['name' => 'delete-public-water']);

        Permission::create(['name' => 'create-water-maintenance']);
        Permission::create(['name' => 'edit-water-maintenance']);
        Permission::create(['name' => 'delete-water-maintenance']);

        // Internet operations
        Permission::create(['name' => 'create-internet-user']);
        Permission::create(['name' => 'edit-internet-user']);
        Permission::create(['name' => 'delete-internet-user']);

        Permission::create(['name' => 'create-internet-maintenance']);
        Permission::create(['name' => 'edit-internet-maintenance']);
        Permission::create(['name' => 'delete-internet-maintenance']);

        // Donor operations
        Permission::create(['name' => 'create-donors']);
        Permission::create(['name' => 'edit-donors']);
        Permission::create(['name' => 'delete-donors']);

        // Vendor operations
        Permission::create(['name' => 'create-vendor-users']);
        Permission::create(['name' => 'edit-vendor-users']);
        Permission::create(['name' => 'delete-vendor-users']);

        Permission::create(['name' => 'create-vendor-community']);
        Permission::create(['name' => 'edit-vendor-community']);
        Permission::create(['name' => 'delete-vendor-community']);

        Permission::create(['name' => 'create-vendors']);
        Permission::create(['name' => 'edit-vendors']);
        Permission::create(['name' => 'delete-vendors']);

        // Systems
        Permission::create(['name' => 'create-energy-systems']);
        Permission::create(['name' => 'edit-energy-systems']);
        Permission::create(['name' => 'delete-energy-systems']);

        Permission::create(['name' => 'create-water-systems']);
        Permission::create(['name' => 'edit-water-systems']);
        Permission::create(['name' => 'delete-water-systems']);

        Permission::create(['name' => 'create-internet-systems']);
        Permission::create(['name' => 'edit-internet-systems']);
        Permission::create(['name' => 'delete-internet-systems']);

        // Incidents
        Permission::create(['name' => 'create-water-incidents']);
        Permission::create(['name' => 'edit-water-incidents']);
        Permission::create(['name' => 'delete-water-incidents']);

        Permission::create(['name' => 'create-mg-systems']);
        Permission::create(['name' => 'edit-mg-systems']);
        Permission::create(['name' => 'delete-mg-systems']);

        Permission::create(['name' => 'create-fbs-systems']);
        Permission::create(['name' => 'edit-fbs-systems']);
        Permission::create(['name' => 'delete-fbs-systems']);

        // Refrigerator operations
        Permission::create(['name' => 'create-refrigerator-users']);
        Permission::create(['name' => 'edit-refrigerator-users']);
        Permission::create(['name' => 'delete-refrigerator-users']);

        // Water quality operations
        Permission::create(['name' => 'create-water-quality']);
        Permission::create(['name' => 'edit-water-quality']);
        Permission::create(['name' => 'delete-water-quality']);

        // Leqa, Tamar, Asmahan, Waseem, Elad
        $adminRole = Role::create(['name' => 'Admin']);

        // All the staff
        $viewerRole = Role::create(['name' => 'Viewer']);

        // Musab
        $energyManagerRole = Role::create(['name' => 'Energy Manager']);

        // Nidal
        $waterManagerRole = Role::create(['name' => 'Water Manager']);

        // Arafat
        $internetManagerRole = Role::create(['name' => 'Internet Manager']);

        // Mamoun, Dahham
        $energyEditorRole = Role::create(['name' => 'Energy Editor']);

        // Mutasem
        $waterEditorRole = Role::create(['name' => 'Water Editor']);

        // Anas, Ibrahim
        $internetEditorRole = Role::create(['name' => 'Internet Editor']);

        // Sujood
        $editorRole = Role::create(['name' => 'Editor']);

        // Dahham
        $energyMaintenanceRole = Role::create(['name' => 'Energy Maintenance Manager']);

        // Ahmad, Sujood
        $waterQualityExpertRole = Role::create(['name' => 'Water Quality Expert']);

        Permission::create(['name' => 'create-electricity-user']);
        Permission::create(['name' => 'edit-electricity-user']);
        Permission::create(['name' => 'delete-electricity-user']);

        Permission::create(['name' => 'create-electricity-donor']);
        Permission::create(['name' => 'edit-electricity-donor']);
        Permission::create(['name' => 'delete-electricity-donor']);

        Permission::create(['name' => 'create-electricity-public']);
        Permission::create(['name' => 'edit-electricity-public']);
        Permission::create(['name' => 'delete-electricity-public']);

        Permission::create(['name' => 'create-electricity-shared-public']);
        Permission::create(['name' => 'edit-electricity-shared-public']);
        Permission::create(['name' => 'delete-electricity-shared-public']);


        $energyEditorRole->givePermissionTo([
            'create-vendor-users',
            'edit-vendor-users',
            'delete-vendor-users',
            'create-vendor-community',
            'edit-vendor-community',
            'delete-vendor-community',
            'create-vendors',
            'edit-vendors',
            'delete-vendors',
            'edit-electricity-user',
            'create-household-meter',
            'edit-household-meter',
            'delete-household-meter',
            'edit-meter',
            'edit-comet-meter'
        ]);

        $waterEditorRole->givePermissionTo([
            'create-water-user',
            'edit-water-user',
            'delete-water-user',
            'create-shared-h2o-user',
            'edit-shared-h2o-user',
            'delete-shared-h2o-user',
            'create-public-water',
            'edit-public-water',
            'delete-public-water',
            'create-water-maintenance',
            'edit-water-maintenance',
            'delete-water-maintenance',
        ]);

        $internetEditorRole->givePermissionTo([
            'create-internet-user',
            'edit-internet-user',
            'delete-internet-user',
            'create-internet-maintenance',
            'edit-internet-maintenance',
            'delete-internet-maintenance',
        ]);

        $editorRole->givePermissionTo([
            'create-household',
            'edit-household',
            'delete-household',
            'create-profession',
            'edit-profession',
            'delete-profession',
            'create-community-representative',
            'edit-community-representative',
            'delete-community-representative',
            'create-public-structure',
            'edit-public-structure',
            'delete-public-structure',
            'create-second-name-community',
            'edit-second-name-community',
            'delete-second-name-community',
            'create-sub-community',
            'edit-sub-community',
            'delete-sub-community',
            'create-community',
            'edit-community',
            'delete-community'
        ]);

        $energyMaintenanceRole->givePermissionTo([
            'create-energy-maintenance',
            'edit-energy-maintenance',
            'delete-energy-maintenance'
        ]);

        $waterQualityExpertRole->givePermissionTo([
            'create-water-quality',
            'edit-water-quality',
            'delete-water-quality'
        ]);

        $energyManagerRole->givePermissionTo([
            'create-electricity-user',
            'edit-electricity-user',
            'delete-electricity-user',
            'create-electricity-public',
            'edit-electricity-public',
            'delete-electricity-public',
            'create-electricity-shared-public',
            'edit-electricity-shared-public',
            'delete-electricity-shared-public',
            'create-comet-meter',
            'edit-comet-meter',
            'delete-comet-meter',
            'create-meter',
            'edit-meter',
            'delete-meter',
            'create-household-meter',
            'edit-household-meter',
            'delete-household-meter'
        ]);

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

        $waterManagerRole->givePermissionTo([
            'create-water-systems',
            'edit-water-systems',
            'delete-water-systems',
            'create-water-user',
            'edit-water-user',
            'delete-water-user',
            'create-shared-h2o-user',
            'edit-shared-h2o-user',
            'delete-shared-h2o-user',
            'create-public-water',
            'edit-public-water',
            'delete-public-water',
            'create-water-maintenance',
            'edit-water-maintenance',
            'delete-water-maintenance'
        ]);
    }
}