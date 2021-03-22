<?php

use Illuminate\Database\Seeder;

class AdministratorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$roles = array();

		$admins = [
            [
                'given_name' => 'Zeta',
                'family_name' => 'Digital',
                'username' => 'zetadigital',
                'mobile' => '',
                'email' => 'webmaster@sexynine.com.au',
                'password' => bcrypt('Teamzeta99'),
                'created_at' => now(),
                'updated_at' => now()
            ]
		];

		$roles = [
            ['role_id' => 1, 'model_type' => 'App\Http\Models\Administrator', 'model_id' => 1]
		];

		// Create Administrators
       	DB::statement("SET foreign_key_checks=0");
    	DB::table('administrators')->truncate();
		DB::statement("SET foreign_key_checks=1");
		DB::table('administrators')->insert($admins);

		// Assign Roles to Administrators
		DB::statement("SET foreign_key_checks=0");
    	DB::table('model_has_roles')->truncate();
		DB::statement("SET foreign_key_checks=1");
		DB::table('model_has_roles')->insert($roles);
    }
}
