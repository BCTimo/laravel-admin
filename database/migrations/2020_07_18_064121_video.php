<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Video extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100)->nullable();
            $table->integer('price')->nullable();
            $table->string('m3u8_path',100)->nullable();
            $table->integer('m3u8_secs')->nullable();
            $table->string('video_path',100);
            $table->string('video_size',100);
            $table->text('content')->nullable();
            $table->integer('status')->nullable();
            $table->integer('hot')->nullable();
            $table->integer('sort')->nullable();
            $table->string('key_path',100)->nullable();
            $table->string('iv',50)->nullable();
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
        Schema::drop('videos');
    }
}
