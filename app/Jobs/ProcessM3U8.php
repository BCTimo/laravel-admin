<?php

namespace App\Jobs;

use FFMpeg;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Video;
use App\Models\Videofiles;

class ProcessM3U8 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $input;
    public $output;
    public $keyinfo; 
    public $videoId;
    public $title_sec;
    public $timeout = 3600; // 定義操作延遲時間,單位:秒;
    public $tries = 5;  // 定義隊列重試次數;
    public $memory = 1024;  // 定義隊列所佔最大內存限制;
    public $threads = 2;    // 定義隊列所佔最大線程數;
    public $section = 10;    // 定義視頻切片大小,單位:秒;
    public $progress = 0;   // 當前轉碼進度;

    /**
     * Create a new job instance.
     *
     * ProcessM3U8 constructor.
     * @param string $input
     * @param string $output
     */
    public function __construct(string $input,string $output, int $videoId, string $title_sec = '00:00:05')
    {
        $this->input = $input;
        $this->output = $output;
        $this->videoId = $videoId;
        $this->title_sec = $title_sec;
        
    }

    /**
     * Execute the job.
     * @note The Handle need composer package as php-ffmpeg by command "composer require php-ffmpeg/php-ffmpeg"
     * @afterChange "php artisan queue:restart & php artisan queue:work --queue=video"
     */
    public function handle()
    {

        Log::info('================Queue===執行轉換 Start===================');

        $MV_path = public_path().'/mv/'.$this->videoId;
        $iv = '3c44008a7e2e5f0877c73ecfab3d0b43';
        
        //開資料夾
        $directory = pathinfo(public_path().$this->output)['dirname'];
        File::isDirectory($directory) or File::makeDirectory($directory);
        //清除現有資料夾
        exec('rm -Rf '.$directory.'/*.ts');
        
        //動態產生key
        $key_gen_cmd='openssl rand -base64 16 > '.$MV_path.'/enc.key';
        Log::info('****產enc.key****');
        exec($key_gen_cmd);

        Log::info('****產enc.keyinfo****');
        $keninfo_gen_cmd = 'echo -e "enc.key\n'.$MV_path.'/enc.key\n'.$iv.'" > '.$MV_path.'/enc.keyinfo';
        exec($keninfo_gen_cmd);

        

        Log::info('圖片採集 Start');
        $get_img = 'ffmpeg -y -i '.public_path().$this->input.' -ss '.$this->title_sec.' -r 0.01 -vframes 1 -f image2 '.$MV_path.'/title.jpeg';
        exec($get_img,$res);
        Log::info('圖片採集 End');

        Log::info('影片轉碼');
        $tsfilename='file'.rand(10,99).'%d.ts';
        // //ffmpeg -y -i /project/fuck.avi -hls_time 20 -hls_key_info_file enc.keyinfo -hls_playlist_type vod -hls_segment_filename /project/file%d.ts /project/index.m3u8
        $cmd='ffmpeg -y -i '.public_path().$this->input.' -vcodec copy -acodec copy -hls_time 10 -hls_key_info_file '.$MV_path.'/enc.keyinfo -hls_playlist_type vod -hls_segment_filename '.$MV_path.'/'.$tsfilename.' '.$MV_path.'/file.m3u8';
        exec($cmd,$res);
        

        $this->del_cache_m3u8($this->videoId);
        
        if(file_exists($MV_path.'/file.m3u8')){
            Log::info('id: '.$this->videoId.' M3U8 檔案存在');
            //產動態密鑰
            $Video_iv = '0x3c44008a7e2e5f0877c73ecfab3d0b43';
            $Video_enckeyinfo = '
                enc.key 
                /project/laravel-admin/key/enc.key
                3c44008a7e2e5f0877c73ecfab3d0b43
            ';
            //File::put('/mv/'.$this->videoId.'/enc.keyinfo',$Video_enckeyinfo);
            //塞入DB table
            
            Log::info('id: '.$this->videoId.' .ts資料寫入');
            $m3u8_info = $this->parseHLS($MV_path.'/file.m3u8');
            $video = Videofiles::where('vid',$this->videoId)->delete(); //更新刪除重做
            $total_sec = 0;

            ///轉圖到base64存db
            // $path = $MV_path.'/title.jpeg';

            $toHtml = "echo 'data:image/jpeg;base64,' > ".$MV_path."/title.html ; base64 ".$MV_path."/title.jpeg  | sed 's/[+]/*/g' |sed 's/\//+/g' | sed 's/[*]/\//g'  >> ".$MV_path."/title.html";
            exec($toHtml);


            foreach($m3u8_info['data'] as $v){
                $videofile = new Videofiles;
                $videofile->vid = $this->videoId;
                $videofile->file_path = '/mv/'.$this->videoId.'/'.$v['url'];
                $videofile->sec = $v['sec'];
                $total_sec += $v['sec'];
                $videofile->save();
            };
            Log::info('id: '.$this->videoId.' .ts資料寫入完成');

            Log::info('id: '.$this->videoId.' videos更新中');
            $video = Video::find($this->videoId);
            $video->m3u8_path = '/mv/'.$this->videoId.'/file.m3u8';
            $video->key_path = '/mv/'.$this->videoId.'/enc.key';
            $video->img_path = '/mv/'.$this->videoId.'/title.jpeg';
            $video->iv = $Video_iv;
            $video->m3u8_secs = $total_sec;
            $video->save();
            Log::info('id: '.$this->videoId.' videos更新完成');
        }else{
            Log::error('M3U8 檔案不存在:'.$MV_path.'/file.m3u8');   
        };
        Log::info('================Queue===執行轉換 End  ===================');
        
    }

    function parseHLS($file) {
        $return = array();
        $i = 0;
        $handle = fopen($file, "r");
        if($handle) {
            while(($line = fgets($handle)) !== FALSE) {
                if(strpos($line,"EXT-X-STREAM-INF") !== FALSE) {
                    if ($c=preg_match_all ("/.*?(BANDWIDTH)(.*?)(,)(RESOLUTION)(.*?)(,)/is", $line, $matches)) {
                        $return['data'][$i]['bandwidth'] = str_replace("=","",$matches[2][0]);
                        $return['data'][$i]['resolution'] = str_replace("=","",$matches[5][0]);
                    }
                }
                if(strpos($line,"EXTINF") !== FALSE) {
                    $return['data'][$i]['sec'] = str_replace(array("\r","\n","#EXTINF:",","),"",$line);
                 
                }
                if(strpos($line,".ts") !== FALSE) {
                    $return['data'][$i]['url'] = str_replace(array("\r","\n"),"",$line);
                    $i++;
                }
            }
            fclose($handle);
        }
        return $return;
    }

    function del_cache_m3u8($vid){
        $api_url = 'http://vapi.yichuba.com/bcb/delm3u8';
        $key = 'Cartoon$2019&#';
        $timestamp = time()*1000;

        $str= 'timestamp='.$timestamp.'&vid='.$vid.'&key='.$key;
        $sign = strtoupper(md5($str));


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'timestamp: '.$timestamp,
            'sign: '.$sign
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            'vid='.$vid
        );
        $output = curl_exec($ch);

        curl_close($ch);
    }

}