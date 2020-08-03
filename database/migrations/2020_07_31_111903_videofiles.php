<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Videofiles extends Migration
{
    public function up()
    {
        Schema::create('videofiles', function (Blueprint $table) {
            $table->increments('id')->comment("主键");
            $table->string('vid')->comment("視頻id");
            $table->string('file_path')->comment("ts_path");
            $table->string('sec')->comment("second");
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
        Schema::drop('videofiles');
    }
}
