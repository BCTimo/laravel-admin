<?php

use Illuminate\Database\Seeder;

class VideoTagsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('video_tags')->delete();
        
        \DB::table('video_tags')->insert(array (
            0 => 
            array (
                'id' => 1,
                'video_tags_id' => 1,
                'tag_id' => 1,
                'video_tags_type' => 'App\\Models\\Video',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'video_tags_id' => 1,
                'tag_id' => 2,
                'video_tags_type' => 'App\\Models\\Video',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 5,
                'video_tags_id' => 1,
                'tag_id' => 3,
                'video_tags_type' => 'App\\Models\\Video',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 6,
                'video_tags_id' => 1,
                'tag_id' => 4,
                'video_tags_type' => 'App\\Models\\Video',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 7,
                'video_tags_id' => 2,
                'tag_id' => 3,
                'video_tags_type' => 'App\\Models\\Video',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 8,
                'video_tags_id' => 2,
                'tag_id' => 1,
                'video_tags_type' => 'App\\Models\\Video',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}