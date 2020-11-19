<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DB;
use Videofiles;

class ProcessGenImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $video;
    public $ts_list;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($video,$ts_list)
    {
        $this->video = $video;
        $this->ts_list = $ts_list;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        Log::info('================Queue===執行轉換 Start===================ID: '.$this->video->id);
        #取間隔ts檔案轉成jpg => 轉成html
        $j=0;
        DB::table('video_preview_img')->where('vid', $this->video->id)->delete();
        foreach($this->ts_list as $v){
            #組檔案
            $m3u8 ='#EXTM3U
#EXT-X-VERSION:3
#EXT-X-TARGETDURATION:18
#EXT-X-MEDIA-SEQUENCE:0
#EXT-X-PLAYLIST-TYPE:VOD
#EXT-X-KEY:METHOD=AES-128,URI="'.env('S3_URL').$this->video->key_path.'",IV='.$this->video->iv.'
#EXTINF:3,
'.env('S3_URL').$v.'
#EXT-X-ENDLIST
            ';
            $video_path = '/tmp/mv/'.$this->video->id.'/';
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
            }
            DB::insert('insert into video_preview_img(vid,img_path,sort) values('.$this->video->id.',"/mv/'.$this->video->id.'/'.$zeroj.'.html",'.$j.')');
            #上傳至AWS
            $upload_s3_cmd = '/usr/local/bin/aws s3 sync '.$video_path.' s3://mv-video/mv/'.$this->video->id.'/  --exclude "*" --include "*.html"';
            exec($upload_s3_cmd);
            $j++;
        }
       
        Log::info('================Queue===執行轉換 End  ===================ID: '.$this->video->id);
    }
}
