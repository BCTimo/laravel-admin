<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VideoTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('taggable_id');
            $table->string('taggable_type');
            $table->timestamps();    
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('video_tags');
    }
}
