<?php

namespace App\Console\Commands;

use App\Jobs\ProcessGenImage;
use Illuminate\Console\Command;
use App\Models\Video;
use App\Models\Videofiles;
use App\Models\video_preview;
use DB;

class imagepreview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'preivew:genimgall  {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '預覽圖片建立 可帶上起始ID';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->points = 20; #節點數
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
            $total_ts = DB::select('select count(*) as total_ts from videofiles where vid = '.$video->id);
            $total_ts = $total_ts[0]->total_ts;
            //  $total_ts = 365;
            if($total_ts <= $this->points){
                $gap = 1; 
            }else{
                $gap = ($total_ts/$this->points);
                $gap = (int)floor($gap);
            }
            $ts_list = [] ; 
            
            $vf_list = videofiles::where('vid',$video->id)->get();

            $i=0; 
            while($i <$total_ts){
                // $ts_list[]= '/mv/'.$video->id.'/file'.$i.'.ts'; 
                $ts_list[]=$vf_list[$i]->file_path;
                $i=$i+$gap;
            }
            //dd($ts_list);
            ProcessGenImage::dispatch($video,$ts_list);
            // dd($ts_list);
            
            
        }

        return true;
    }
}
