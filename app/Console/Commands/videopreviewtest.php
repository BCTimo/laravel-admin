<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;
use App\Models\Videofiles;
use App\Models\video_preview;
use DB;

class videopreviewtest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'preivew:genalltest {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '預覽影片測試連續建立 可帶上起始ID';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->min_prevsecs = 20;
        $this->max_prevsecs = 25;
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

            $center = DB::select('SELECT CEIL(COUNT(*)/2) as center FROM videofiles where vid = '.$video->id);//取中間值
            // dd($center[0]->center);

            // $avg_sec = DB::query('select round(avg(sec),1) as avgsec from videofiles where vid = '.$video->id);
            $filelists = DB::select('select * from videofiles where vid = '.$video->id .' limit '.$center[0]->center .' ,2');
            dd($filelists);
            $nowsecs = 0; 
            foreach($filelists as $k=>$v){
                if($nowsecs > $this->min_prevsecs) { break;}
                if($nowsecs + $v->sec > $this->max_prevsecs){ break;}
                $nowsecs += $v->sec;
                $prev_lists[$k]['vid']=$v->vid;
                $prev_lists[$k]['id']=$v->id;
                $prev_lists[$k]['secs']=$v->sec;
            }
            

            $i=0;
            foreach($prev_lists as $prev){
                $i+=1;
                // DB::insert('insert into video_preview_app(vid,videofiles_id,sort) values('.$prev['vid'].','.$prev['id'].','.$i.')');
            }
            

        }

        return true;
    }
}
