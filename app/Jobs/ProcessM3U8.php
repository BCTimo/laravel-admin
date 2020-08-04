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
    public function __construct(string $input,string $output,string $keyinfo, int $videoId)
    {
        $this->input = $input;
        $this->output = $output;
        $this->keyinfo = $keyinfo;
        $this->videoId = $videoId;
    }

    /**
     * Execute the job.
     * @note The Handle need composer package as php-ffmpeg by command "composer require php-ffmpeg/php-ffmpeg"
     * @afterChange "php artisan queue:restart & php artisan queue:work --queue=video"
     */
    public function handle()
    {
        Log::info('執行轉換:'.public_path().$this->output);
        ini_set('memory_limit',$this->memory.'M');
        $ffmpeg = FFMpeg\FFMpeg::create([
            'ffmpeg.binaries'  => '/usr/bin/ffmpeg',//exec('which ffmpeg'),
            'ffprobe.binaries' => '/usr/bin/ffprobe',//exec('which ffprobe'),
            'timeout'          => $this->timeout,
            'ffmpeg.threads'   => $this->threads,
        ]);
        //ffmpeg -y -i /project/fuck.avi -hls_time 20 -hls_key_info_file enc.keyinfo -hls_playlist_type vod -hls_segment_filename /project/file%d.ts /project/index.m3u8
        $video = $ffmpeg->open(public_path().$this->input);
        $format = new FFMpeg\Format\Video\X264('aac', 'libx264');
        $format
            ->setAdditionalParameters(['-hls_time',$this->section, '-hls_playlist_type','vod'])
            ->setAdditionalParameters(['-hls_key_info_file',$this->keyinfo])
            // ->setAdditionalParameters(['-hls_segment_filename','/project/MV/xx%d.ts','/project/MV/vv.m3u8'])
            ->setKiloBitrate(1000)
            ->setAudioChannels(2)
            ->setAudioKiloBitrate(256)
            ->setPasses(2);
        $format->on('progress', function ($video, $format, $percentage) {
            if ($percentage > $this->progress){
                Log::info('轉換進度:'.$percentage.'%');
                $this->progress = $percentage;
            }
        });
        $directory = pathinfo(public_path().$this->output)['dirname'];
        File::isDirectory($directory) or File::makeDirectory($directory);
        try {
            $video->save($format, public_path().$this->output);
        } catch (\Exception $e) {
            Log::error('錯誤:'.$e->getMessage());
        } finally {
            $this->result = $this->output;
            Log::info('轉換成功:'.public_path().$this->output);
        }
        
        if(file_exists(public_path().'/MV/'.$this->videoId.'/file.m3u8')){
            Log::info('M3U8 檔案存在');
            //產動態密鑰
            $Video_iv = '3c44008a7e2e5f0877c73ecfab3d0b43';
            $Video_enckeyinfo = '
                enc.key 
                /project/laravel-admin/key/enc.key
                3c44008a7e2e5f0877c73ecfab3d0b43
            ';
            //File::put('/MV/'.$this->videoId.'/enc.keyinfo',$Video_enckeyinfo);
            //塞入DB table
            
            Log::info('.ts資料寫入');
            $m3u8_info = $this->parseHLS(public_path().'/MV/'.$this->videoId.'/file.m3u8');
            $video = Videofiles::where('vid',$this->videoId)->delete(); //更新刪除重做
            $total_sec = 0;
            foreach($m3u8_info['data'] as $v){
                $videofile = new Videofiles;
                $videofile->vid = $this->videoId;
                $videofile->file_path = $v['url'];
                $videofile->sec = $v['sec'];
                $total_sec += $v['sec'];
                $videofile->save();
            };
            Log::info('.ts資料寫入完成');

            Log::info('videos更新中');
            $video = Video::find($this->videoId);
            $video->m3u8_path = '/MV/'.$this->videoId.'/file.m3u8';
            $video->key_path = '/MV/'.$this->videoId.'/enc.keyinfo';
            $video->iv = $Video_iv;
            $video->m3u8_secs = $total_sec;
            $video->save();
            Log::info('videos更新完成');
        }else{
            Log::error('M3U8 檔案不存在:'.public_path().'/MV/'.$this->videoId.'/file.m3u8');   
        };
        
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
}