<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
class Video_log extends Model
{
    protected $table = 'video_log';

    public static function user_count_by_day(){
        //每日不重複人流
        // return Video::selectRaw('select date(create_time) as date,count(DISTINCT uid) as uidcnt from video_log group by date(create_time)');
        $res = DB::select('select date(create_time) as date,count(DISTINCT uid) as uidcnt from video_log group by date(create_time) order by date desc');
        //$res = static::hydrate($res);
        return $res;
    }

    public static function bookbean_by_day(){
        $sql='
        SELECT count(id) as "点击量",sum(paid) as "消耗金币总量",DATE_FORMAT(create_time,"%Y-%m-%d") as g_t ,
        sum( if( client = 1, 1,0)) as "wap点击量",
        sum( if( client = 2, 1,0)) as "app点击量" ,
        sum( if( client = 1, paid,0)) as "wap金币消耗量" ,
        sum( if( client = 2, paid,0)) as "app金币消耗量" 
        FROM video.video_log
        group by g_t;
        ';
        $res = DB::select($sql);
        return $res;
    }

    


    
}
