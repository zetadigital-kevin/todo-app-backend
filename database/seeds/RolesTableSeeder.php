<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	DB::statement("SET foreign_key_checks=0");
    	DB::table('roles')->truncate();
		DB::statement("SET foreign_key_checks=1");

		$roles = [
			['name' => 'superuser', 'guard_name' => 'api', 'display_name' => 'Superuser'],
            ['name' => 'director', 'guard_name' => 'api', 'display_name' => 'Director']
		];

		$data = array();
		foreach ($roles as $role) {
			array_push($data,
				array(
					'name' => $role['name'],
					'guard_name' => $role['guard_name'],
                    'display_name' => $role['display_name'],
					'created_at' => now(),
					'updated_at' => now(),
				)
			);
		}

		DB::table('roles')->insert($data);
    }
}
