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
                'order' => 2,
                'title' => 'Dashboard',
                'icon' => 'fa-bar-chart',
                'uri' => '/',
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => '2020-07-27 14:07:24',
            ),
            1 => 
            array (
                'id' => 2,
                'parent_id' => 0,
                'order' => 10,
                'title' => 'Admin',
                'icon' => 'fa-tasks',
                'uri' => NULL,
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => '2020-08-06 16:33:25',
            ),
            2 => 
            array (
                'id' => 3,
                'parent_id' => 2,
                'order' => 11,
                'title' => 'Users',
                'icon' => 'fa-users',
                'uri' => 'auth/users',
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => '2020-07-27 14:07:24',
            ),
            3 => 
            array (
                'id' => 4,
                'parent_id' => 2,
                'order' => 12,
                'title' => 'Roles',
                'icon' => 'fa-user',
                'uri' => 'auth/roles',
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => '2020-07-27 14:07:24',
            ),
            4 => 
            array (
                'id' => 5,
                'parent_id' => 2,
                'order' => 13,
                'title' => 'Permission',
                'icon' => 'fa-ban',
                'uri' => 'auth/permissions',
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => '2020-07-27 14:07:24',
            ),
            5 => 
            array (
                'id' => 6,
                'parent_id' => 2,
                'order' => 14,
                'title' => 'Menu',
                'icon' => 'fa-bars',
                'uri' => 'auth/menu',
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => '2020-07-27 14:07:24',
            ),
            6 => 
            array (
                'id' => 7,
                'parent_id' => 2,
                'order' => 15,
                'title' => 'Operation log',
                'icon' => 'fa-history',
                'uri' => 'auth/logs',
                'permission' => NULL,
                'created_at' => NULL,
                'updated_at' => '2020-07-27 14:07:24',
            ),
            7 => 
            array (
                'id' => 8,
                'parent_id' => 0,
                'order' => 3,
                'title' => '視頻管理',
                'icon' => 'fa-video-camera',
                'uri' => 'videos',
                'permission' => '視頻',
                'created_at' => '2020-07-21 10:04:24',
                'updated_at' => '2020-08-06 16:24:01',
            ),
            8 => 
            array (
                'id' => 9,
                'parent_id' => 0,
                'order' => 5,
                'title' => '排程管理',
                'icon' => 'fa-clock-o',
                'uri' => 'crontabs',
                'permission' => NULL,
                'created_at' => '2020-07-21 10:04:43',
                'updated_at' => '2020-07-23 15:26:28',
            ),
            9 => 
            array (
                'id' => 10,
                'parent_id' => 0,
                'order' => 6,
                'title' => 'Redis管理',
                'icon' => 'fa-list-alt',
                'uri' => 'redis',
                'permission' => NULL,
                'created_at' => '2020-07-21 10:05:02',
                'updated_at' => '2020-07-23 15:26:28',
            ),
            10 => 
            array (
                'id' => 11,
                'parent_id' => 0,
                'order' => 1,
                'title' => '多媒體管理',
                'icon' => 'fa-file-video-o',
                'uri' => 'media',
                'permission' => NULL,
                'created_at' => '2020-07-22 16:29:16',
                'updated_at' => '2020-07-27 14:07:24',
            ),
            11 => 
            array (
                'id' => 13,
                'parent_id' => 0,
                'order' => 7,
                'title' => '系統參數設置',
                'icon' => 'fa-toggle-on',
                'uri' => 'config',
                'permission' => NULL,
                'created_at' => '2020-07-22 16:33:09',
                'updated_at' => '2020-07-23 15:26:28',
            ),
            12 => 
            array (
                'id' => 14,
                'parent_id' => 0,
                'order' => 8,
                'title' => 'Env 設定檔',
                'icon' => 'fa-gears',
                'uri' => 'env-manager',
                'permission' => NULL,
                'created_at' => '2020-07-23 15:23:20',
                'updated_at' => '2020-07-23 15:28:03',
            ),
            13 => 
            array (
                'id' => 16,
                'parent_id' => 0,
                'order' => 9,
                'title' => 'Log viewer',
                'icon' => 'fa-database',
                'uri' => 'logs',
                'permission' => NULL,
                'created_at' => '2020-07-23 15:28:43',
                'updated_at' => '2020-07-27 14:07:24',
            ),
            14 => 
            array (
                'id' => 17,
                'parent_id' => 0,
                'order' => 4,
                'title' => '標籤管理',
                'icon' => 'fa-tags',
                'uri' => 'tags',
                'permission' => '標籤',
                'created_at' => '2020-07-27 14:07:04',
                'updated_at' => '2020-08-06 16:24:37',
            ),
            15 => 
            array (
                'id' => 18,
                'parent_id' => 0,
                'order' => 0,
                'title' => '域名管理',
                'icon' => 'fa-cloud',
                'uri' => 'domains',
                'permission' => NULL,
                'created_at' => '2020-07-30 11:02:58',
                'updated_at' => '2020-07-30 11:03:11',
            ),
        ));
        
        
    }
}