<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        $this->call(AdminMenuTableSeeder::class);
        $this->call(AdminRolesTableSeeder::class);
        $this->call(AdminPermissionsTableSeeder::class);
        $this->call(AdminUsersTableSeeder::class);
        $this->call(AdminRoleMenuTableSeeder::class);
        $this->call(AdminRolePermissionsTableSeeder::class);
        $this->call(AdminRoleUsersTableSeeder::class);
        $this->call(CrontabTableSeeder::class);
        $this->call(AdminConfigTableSeeder::class);
        $this->call(VideoTagsTableSeeder::class);
        $this->call(DomainsTableSeeder::class);
    }
}
