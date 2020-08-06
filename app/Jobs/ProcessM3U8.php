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
    public function __construct(string $input,string $output, int $videoId)
    {
        $this->input = $input;
        $this->output = $output;
        
        $this->videoId = $videoId;
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
        //動態產生key

        $directory = pathinfo(public_path().$this->output)['dirname'];
        File::isDirectory($directory) or File::makeDirectory($directory);


        $key_gen_cmd='openssl rand -base64 16 > '.$MV_path.'/enc.key';
        Log::info('****產enc.key****');
        exec($key_gen_cmd);

        Log::info('****產enc.keyinfo****');
        $keninfo_gen_cmd = 'echo -e "enc.key\n'.$MV_path.'/enc.key\n'.$iv.'" > '.$MV_path.'/enc.keyinfo';
        exec($keninfo_gen_cmd);

        

        Log::info('圖片採集 Start');
        $get_img = 'ffmpeg -y -i '.public_path().$this->input.' -ss 00:00:05 -r 0.01 -vframes 1 -f image2 '.$MV_path.'/title.jpeg';
        exec($get_img,$res);

        
        Log::info('圖片採集 End');
        $cmd='ffmpeg -y -i '.public_path().$this->input.' -hls_time 10 -hls_key_info_file '.$MV_path.'/enc.keyinfo -hls_playlist_type vod -hls_segment_filename '.$MV_path.'/file%d.ts '.$MV_path.'/file.m3u8';
        exec($cmd,$res);
        
        //dd($res);
        // Log::info('執行轉換:'.public_path().$this->output);
        // ini_set('memory_limit',$this->memory.'M');
        // $ffmpeg = FFMpeg\FFMpeg::create([
        //     'ffmpeg.binaries'  => '/usr/bin/ffmpeg',//exec('which ffmpeg'),
        //     'ffprobe.binaries' => '/usr/bin/ffprobe',//exec('which ffprobe'),
        //     'timeout'          => $this->timeout,
        //     'ffmpeg.threads'   => $this->threads,
        // ]);
        // //ffmpeg -y -i /project/fuck.avi -hls_time 20 -hls_key_info_file enc.keyinfo -hls_playlist_type vod -hls_segment_filename /project/file%d.ts /project/index.m3u8
        // $video = $ffmpeg->open(public_path().$this->input);
        // $format = new FFMpeg\Format\Video\X264('aac', 'libx264');
        // $format
        //     ->setAdditionalParameters(['-hls_time',$this->section, '-hls_playlist_type','vod'])
        //     ->setAdditionalParameters(['-hls_key_info_file',$this->keyinfo])
        //     // ->setAdditionalParameters(['-hls_segment_filename ',$MV_path.'/xx%d.ts ',$MV_path.'/vv.m3u8'])
        //     ->setKiloBitrate(1000)
        //     ->setAudioChannels(2)
        //     ->setAudioKiloBitrate(256)
        //     ->setPasses(2);
        // $format->on('progress', function ($video, $format, $percentage) {
        //     if ($percentage > $this->progress){
        //         Log::info('轉換進度:'.$percentage.'%');
        //         $this->progress = $percentage;
        //     }
        // });
        // $directory = pathinfo(public_path().$this->output)['dirname'];
        // File::isDirectory($directory) or File::makeDirectory($directory);
        // try {
        //     $video->save($format, public_path().$this->output);
        // } catch (\Exception $e) {
        //     Log::error('錯誤:'.$e->getMessage());
        // } finally {
        //     $this->result = $this->output;
        //     Log::info('轉換成功:'.public_path().$this->output);
        // }
        
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

            $toHtml = "echo data:image/jpeg;base64, > ".$MV_path."/title.html ; base64 ".$MV_path."/title.jpeg  | sed 's/[+]/*/g' |sed 's/\//+/g' | sed 's/[*]/\//g'  >> ".$MV_path."/title.html";
            exec($toHtml);


            // $data = file_get_contents($path);
            // $base64_img = base64_encode($data);
            
            // $base65_img = $this->base65($base64_img);
            // $base65_img = chunk_split($base65_img, 64, "\n");
            // //產html檔來放
            
            // $myfile = fopen($MV_path.'/title.html', "w");
            // $txtformat = 'data:image/jpeg;base64,';
            // $txt = $base65_img;
            // fwrite($myfile, $txtformat.$txt);
            // fclose($myfile);
            

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


    function base65($str){
        $str = base64_encode($str);
        $str = str_replace("+", "*", $str);
        $str = str_replace("/", "+", $str);
        $str = str_replace("*", "/", $str);
    
        return $str;
    }
}