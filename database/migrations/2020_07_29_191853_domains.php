<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Domains extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->increments('id')->comment("主键");
            $table->string('domain',100)->comment("域名");
            $table->tinyInteger('type')->comment("域名类型：1、加密资源域名  2、微信推广域名 3、推广2层  4、主体域名 6、微信推广js域名 7、微信外链域名 8、资源域名  9、微信推广CDN域名  10、动态主体域名");
            $table->tinyInteger('status')->comment("域名状态：1、备用；2、启用；3、被拦截");
            $table->dateTime('intercept_time')->nullable()->comment("域名拦截时间");
            $table->string('explain',255)->nullable()->comment("使用说明");
            $table->tinyInteger('power')->comment("是否高权域名：1、是；0、否");
            $table->tinyInteger('ssl')->comment("是否https访问：1、是；0、否；");
            $table->tinyInteger('base64')->nullable()->comment("資源域名用加密欄位0:不加密1加密");
            
            $table->dateTime('create_time')->nullable()->comment("创建时间");
            $table->dateTime('enable_time')->nullable()->comment("启用时间");
            $table->tinyInteger('platform')->nullable()->comment("使用平台：1、WAP；2、APP；3、微信；");
            $table->dateTime('expiration_time')->nullable()->comment("過期時間");
            
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
        Schema::drop('domains');
    }
}
