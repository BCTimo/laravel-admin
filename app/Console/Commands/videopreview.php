<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;
use App\Models\Videofiles;
use App\Models\video_preview;
use DB;

class videopreview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'preivew:genall {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '預覽影片建立 可帶上起始ID';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if($this->argument('id')){
            $startId = $this->argument('id');
            $videos = Video::where('id','>=',$startId)->get();
        }else{
            $videos = Video::all();
        }
        
        foreach ($videos as $video){
            echo '==執行video ID: '.$video->id."\n";
            // $avg_sec = DB::query('select round(avg(sec),1) as avgsec from videofiles where vid = '.$video->id);
            $avg_sec = DB::select('select round(avg(sec),1) as avgsec from videofiles where vid = '.$video->id);
            if($avg_sec > 5 ){ 
                $filelimit = 3;
            }else{
                $filelimit = 7;
            }
            #算出TS總數後 取出現有小於平均數的ts list 後取三比最小秒數的ts_file  最後順序排出
            $sql = 'select id,vid,file_path,sec from  ( 
                select * from videofiles vf
                where vf.vid = '.$video->id.' 
                and vf.sec < (select round(avg(sec),1) as avgsec from videofiles where vid = vf.vid )
                and sec > 1 
                order by rand() 
                limit '.$filelimit.'
                ) as xx 
            order by id asc';

            $prev_lists = DB::select($sql);
            $i=0;
            foreach($prev_lists as $prev){
                $i+=1;
                DB::insert('insert into video_preview(vid,videofiles_id,sort) values('.$prev->vid.','.$prev->id.','.$i.')');
            }
            

        }

        return true;
    }
}
