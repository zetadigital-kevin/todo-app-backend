<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// Create Permissions
     	DB::statement("SET foreign_key_checks=0");
    	DB::table('permissions')->truncate();
		DB::statement("SET foreign_key_checks=1");

        $permissions = [
            // Administrator Permissions
            ['name' => 'administrator:self:password:update', 'display_name' => 'Update Password', 'collection' => 'Administrator Self Management', 'description' => 'Update login password'],
            ['name' => 'administrator:self:update', 'display_name' => 'Update Profile', 'collection' => 'Administrator Self Management', 'description' => 'Update profile'],
            ['name' => 'administrator:create', 'display_name' => 'Create Administrator Account', 'collection' => 'Administrator Management', 'description' => 'Create administrator account and assign roles'],
            ['name' => 'administrator:list', 'display_name' => 'View Administrators List', 'collection' => 'Administrator Management', 'description' => 'View administrators list'],
            ['name' => 'administrator:view', 'display_name' => 'View Administrator Profile', 'collection' => 'Administrator Management', 'description' => 'View administrator profile'],
            ['name' => 'administrator:update', 'display_name' => 'Update Administrator Profile', 'collection' => 'Administrator Management', 'description' => 'Update administrator profile'],
            ['name' => 'administrator:role:update', 'display_name' => 'Update Administrator Roles', 'collection' => 'Administrator Management', 'description' => 'Assign or dismiss administrator roles'],
            ['name' => 'administrator:activated:update', 'display_name' => 'Activate / Deactivate Administrator Account', 'collection' => 'Administrator Management', 'description' => 'Activate or deactivate administrator account'],
            ['name' => 'administrator:delete', 'display_name' => 'Delete Administrator Account', 'collection' => 'Administrator Management', 'description' => 'Delete administrator account'],

            // Role Permissions
            ['name' => 'role:list', 'display_name' => 'View Roles List', 'collection' => 'Role & Permission Management', 'description' => 'View roles list'],
            ['name' => 'role:view', 'display_name' => 'View Roles Profile', 'collection' => 'Role & Permission Management', 'description' => 'View Roles profile'],
            ['name' => 'role:create', 'display_name' => 'Create Role', 'collection' => 'Role & Permission Management', 'description' => 'Create role'],
            ['name' => 'role:update', 'display_name' => 'Update Role Profile', 'collection' => 'Role & Permission Management', 'description' => 'Update role profile'],
            ['name' => 'role:delete', 'display_name' => 'Delete Role Profile', 'collection' => 'Role & Permission Management', 'description' => 'Delete role'],
            ['name' => 'permission:list', 'display_name' => 'View Permissions List', 'collection' => 'Role & Permission Management', 'description' => 'View permissions list'],
            ['name' => 'role:permission:list', 'display_name' => 'View Role Permissions List', 'collection' => 'Role & Permission Management', 'description' => 'View role permissions list'],
            ['name' => 'role:permission:update', 'display_name' => 'Update Role Permissions', 'collection' => 'Role & Permission Management', 'description' => 'Update role permissions'],
            ['name' => 'role:administrator:list', 'display_name' => 'View Role Administrator List', 'collection' => 'Role & Permission Management', 'description' => 'View role administrators list'],
        ];

        $data = array();
        foreach ($permissions as $permission) {
            array_push($data,
                array(
                    'name' => $permission['name'],
                    'display_name' => $permission['display_name'],
                    'collection' => $permission['collection'],
                    'description' => $permission['description'],
                    'guard_name' => 'api',
                    'created_at' => now(),
                    'updated_at' => now(),
                )
            );
        }

        DB::table('permissions')->insert($data);


        // Assign Permissions to Superuser and Director
        DB::statement("SET foreign_key_checks=0");
        DB::table('role_has_permissions')->truncate();
        DB::statement("SET foreign_key_checks=1");

        $data = array();
        // Assign Permissions to Superuser and Directors
        for ($i = 1; $i <= count($permissions); $i++){
            array_push($data,
                array(
                    'permission_id' => $i,
                    'role_id' => 1,
                )
            );
            array_push($data,
                array(
                    'permission_id' => $i,
                    'role_id' => 2,
                )
            );
        }

        DB::table('role_has_permissions')->insert($data);
    }
}
