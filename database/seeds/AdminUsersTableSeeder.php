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
        ));
        
        
    }
}