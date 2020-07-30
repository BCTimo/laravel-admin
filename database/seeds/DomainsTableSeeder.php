<?php

use Illuminate\Database\Seeder;

class DomainsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('domains')->delete();
        
        \DB::table('domains')->insert(array (
            0 => 
            array (
                'id' => 1,
                'domain' => 'test.com',
                'type' => 1,
                'status' => 1,
                'intercept_time' => NULL,
                'explain' => '測試用',
                'power' => 1,
                'ssl' => 1,
                'base64' => 1,
                'create_time' => NULL,
                'enable_time' => NULL,
                'platform' => 1,
                'expiration_time' => NULL,
                'created_at' => '2020-07-30 12:05:13',
                'updated_at' => '2020-07-30 12:05:13',
            ),
        ));
        
        
    }
}