<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->truncate();

        $settings = [

        ];

        $data = array();
        foreach ($settings as $setting) {
            array_push($data,
                array(
                    'category' => $setting['category'],
                    'scope' => $setting['scope'],
                    'field' => $setting['field'],
                    'value' => $setting['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                )
            );
        }

        DB::table('settings')->insert($data);
    }
}
