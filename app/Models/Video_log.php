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


    
}
