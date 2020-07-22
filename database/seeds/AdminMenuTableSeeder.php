<?php

use Illuminate\Database\Seeder;

class AdminMenuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_menu')->delete();
        
        \DB::table('admin_menu')->insert(array (
            0 => 
            array (
                'id' => 1,
                'parent_id' => 0,
                'order' => 1,
                'title' => 'Dashboard',
                'icon' => 'fa-bar-chart',
                'uri' => '/',
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'parent_id' => 0,
                'order' => 5,
                'title' => 'Admin',
                'icon' => 'fa-tasks',
                'uri' => '',
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => '2020-07-21 10:05:13',
            ),
            2 => 
            array (
                'id' => 3,
                'parent_id' => 2,
                'order' => 6,
                'title' => 'Users',
                'icon' => 'fa-users',
                'uri' => 'auth/users',
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => '2020-07-21 10:05:13',
            ),
            3 => 
            array (
                'id' => 4,
                'parent_id' => 2,
                'order' => 7,
                'title' => 'Roles',
                'icon' => 'fa-user',
                'uri' => 'auth/roles',
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => '2020-07-21 10:05:13',
            ),
            4 => 
            array (
                'id' => 5,
                'parent_id' => 2,
                'order' => 8,
                'title' => 'Permission',
                'icon' => 'fa-ban',
                'uri' => 'auth/permissions',
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => '2020-07-21 10:05:13',
            ),
            5 => 
            array (
                'id' => 6,
                'parent_id' => 2,
                'order' => 9,
                'title' => 'Menu',
                'icon' => 'fa-bars',
                'uri' => 'auth/menu',
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => '2020-07-21 10:05:13',
            ),
            6 => 
            array (
                'id' => 7,
                'parent_id' => 2,
                'order' => 10,
                'title' => 'Operation log',
                'icon' => 'fa-history',
                'uri' => 'auth/logs',
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => '2020-07-21 10:05:13',
            ),
            7 => 
            array (
                'id' => 8,
                'parent_id' => 0,
                'order' => 2,
                'title' => '視頻管理',
                'icon' => 'fa-video-camera',
                'uri' => 'videos',
                'permission' => NULL,
                'created_at' => '2020-07-21 10:04:24',
                'updated_at' => '2020-07-21 10:05:13',
            ),
            8 => 
            array (
                'id' => 9,
                'parent_id' => 0,
                'order' => 3,
                'title' => '排程管理',
                'icon' => 'fa-clock-o',
                'uri' => 'crontabs',
                'permission' => NULL,
                'created_at' => '2020-07-21 10:04:43',
                'updated_at' => '2020-07-21 10:05:13',
            ),
            9 => 
            array (
                'id' => 10,
                'parent_id' => 0,
                'order' => 4,
                'title' => 'Redis管理',
                'icon' => 'fa-list-alt',
                'uri' => 'redis',
                'permission' => NULL,
                'created_at' => '2020-07-21 10:05:02',
                'updated_at' => '2020-07-21 10:05:13',
            ),
            10 => 
            array (
                'id' => 11,
                'parent_id' => 0,
                'order' => 0,
                'title' => '多媒體管理',
                'icon' => 'fa-file-video-o',
                'uri' => 'media',
                'permission' => NULL,
                'created_at' => '2020-07-22 16:29:16',
                'updated_at' => '2020-07-22 16:29:16',
            ),
            11 => 
            array (
                'id' => 12,
                'parent_id' => 0,
                'order' => 11,
                'title' => 'Backup',
                'icon' => 'fa-copy',
                'uri' => 'backup',
                'permission' => NULL,
                'created_at' => '2020-07-22 16:29:45',
                'updated_at' => '2020-07-22 16:29:45',
            ),
            12 => 
            array (
                'id' => 13,
                'parent_id' => 0,
                'order' => 12,
                'title' => 'Config',
                'icon' => 'fa-toggle-on',
                'uri' => 'config',
                'permission' => NULL,
                'created_at' => '2020-07-22 16:33:09',
                'updated_at' => '2020-07-22 16:33:09',
            ),
        ));
        
        
    }
}