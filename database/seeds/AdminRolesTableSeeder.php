<?php

use Illuminate\Database\Seeder;

class AdminRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_roles')->delete();
        
        \DB::table('admin_roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Administrator',
                'slug' => 'administrator',
                'created_at' => '2020-07-22 03:08:18',
                'updated_at' => '2020-07-22 03:08:18',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'book',
                'slug' => 'book',
                'created_at' => '2020-08-06 16:21:06',
                'updated_at' => '2020-08-06 16:21:06',
            ),
        ));
        
        
    }
}