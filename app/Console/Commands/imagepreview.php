<?php

namespace App\Console\Commands;

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
            $i=0; 
            while($i <$total_ts){
                $ts_list[]= '/mv/'.$video->id.'/file'.$i.'.ts'; 
                $i=$i+$gap;
            }

            // dd($ts_list);
            #取間隔ts檔案轉成jpg => 轉成html
            $j=0;
            foreach($ts_list as $v){
                #組檔案
                $m3u8 ='#EXTM3U
#EXT-X-VERSION:3
#EXT-X-TARGETDURATION:18
#EXT-X-MEDIA-SEQUENCE:0
#EXT-X-PLAYLIST-TYPE:VOD
#EXT-X-KEY:METHOD=AES-128,URI="'.env('S3_URL').$video->key_path.'",IV='.$video->iv.'
#EXTINF:3,
'.env('S3_URL').$v.'
#EXT-X-ENDLIST
                ';
                $video_path = '/tmp/mv/'.$video->id.'/';
                exec('mkdir -p ' . $video_path);
                // #寫檔案+產JPG
                $file = fopen($video_path."tmp.m3u8","w+"); //開啟檔案
                fwrite($file,$m3u8);
                fclose($file);

                $zeroj = str_pad($j,3,'0',STR_PAD_LEFT);
                $cutimg_cmd = 'ffmpeg -y -allowed_extensions ALL -protocol_whitelist file,http,https,tcp,tls,crypto -i '.$video_path.'/tmp.m3u8 -ss 00:00:00.0001 -vframes 1 '.$video_path.$zeroj.'.jpg';
                exec($cutimg_cmd,$res,$m);
                
                if($m ==0 ){
                    #加密
                    $encrypt_img = 'echo \'data:image/jpeg;base64,\' > '.$video_path.$zeroj.'.html && base64 '.$video_path.$zeroj.".jpg | sed 's/[+]/*/g' |sed 's/\//+/g' | sed 's/[*]/\//g' >> ".$video_path.$zeroj.".html";
                    exec($encrypt_img);
                    $j++;
                }
                
                #上傳至AWS
                $upload_s3_cmd = 'aws s3 sync '.$video_path.' s3://mv-video/mv/'.$video->id.'/  --exclude "*" --include "*.html"';
                exec($upload_s3_cmd);
            }

        }

        return true;
    }
}
