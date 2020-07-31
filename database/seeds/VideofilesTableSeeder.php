<?php

use Illuminate\Database\Seeder;

class VideofilesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('videofiles')->delete();
        
        \DB::table('videofiles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'vid' => '1',
                'file_path' => 'file0.ts',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'vid' => '1',
                'file_path' => 'file1.ts',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'vid' => '1',
                'file_path' => 'file2.ts',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'vid' => '1',
                'file_path' => 'file3.ts',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'vid' => '1',
                'file_path' => 'file4.ts',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'vid' => '1',
                'file_path' => 'file5.ts',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}