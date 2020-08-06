<?php

use Illuminate\Database\Seeder;

class AdminUsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_users')->delete();
        
        \DB::table('admin_users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'username' => 'admin',
                'password' => '$2y$10$nS.QW.HfP89BcUqWt3/kdeORn1.Xuw4fk5vOi190VtOzPpP/OHAUe',
                'name' => 'Administrator',
                'avatar' => NULL,
                'remember_token' => 'gImdJQ2bkGKHGJABA1HNeuR2LWZu7s4utEjo5SNiGccTr6dM8qk2jPys3yzr',
                'created_at' => '2020-07-21 10:03:31',
                'updated_at' => '2020-07-21 10:03:31',
            ),
            1 => 
            array (
                'id' => 2,
                'username' => 'timo',
                'password' => '$2y$10$Zd8SKiP5otnMs9SwxfVdOe1JKr07I5XBrSmS1mwChwpqZq0a0qo76',
                'name' => 'timo',
                'avatar' => NULL,
                'remember_token' => 'C7pX0XKPR7HgueL90kCWdC7PCT4nVK1A9NmjyI3Naq36QaPTfItgY3lsbAoj',
                'created_at' => '2020-08-06 16:22:29',
                'updated_at' => '2020-08-06 16:22:29',
            ),
        ));
        
        
    }
}