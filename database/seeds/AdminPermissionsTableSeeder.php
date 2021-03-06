<?php

use Illuminate\Database\Seeder;

class AdminPermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_permissions')->delete();
        
        \DB::table('admin_permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'All permission',
                'slug' => '*',
                'http_method' => '',
                'http_path' => '*',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'http_method' => 'GET',
                'http_path' => '/',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Login',
                'slug' => 'auth.login',
                'http_method' => '',
                'http_path' => '/auth/login
/auth/logout',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'User setting',
                'slug' => 'auth.setting',
                'http_method' => 'GET,PUT',
                'http_path' => '/auth/setting',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Auth management',
                'slug' => 'auth.management',
                'http_method' => '',
                'http_path' => '/auth/roles
/auth/permissions
/auth/menu
/auth/logs',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Backup',
                'slug' => 'ext.backup',
                'http_method' => '',
                'http_path' => '/backup*',
                'created_at' => '2020-07-22 16:29:45',
                'updated_at' => '2020-07-22 16:29:45',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Admin Config',
                'slug' => 'ext.config',
                'http_method' => '',
                'http_path' => '/config*',
                'created_at' => '2020-07-22 16:33:09',
                'updated_at' => '2020-07-22 16:33:09',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Logs',
                'slug' => 'ext.log-viewer',
                'http_method' => '',
                'http_path' => '/logs*',
                'created_at' => '2020-07-23 15:28:43',
                'updated_at' => '2020-07-23 15:28:43',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Video',
                'slug' => '視頻',
                'http_method' => '',
                'http_path' => '/videos',
                'created_at' => '2020-08-06 16:20:51',
                'updated_at' => '2020-08-06 16:25:21',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'tags',
                'slug' => '標籤',
                'http_method' => '',
                'http_path' => '/tags',
                'created_at' => '2020-08-06 16:21:43',
                'updated_at' => '2020-08-06 16:25:12',
            ),
        ));
        
        
    }
}