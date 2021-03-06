<?php

namespace App\Admin\Controllers;

use Illuminate\Support\Facades\File;
use App\Jobs\ProcessM3U8;
use App\Models\Video;
use App\Models\VideoTags;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Carbon\Carbon;
use App\Models\Tag;
use App\Models\Videofiles;
use App\Models\Video_log;
use DB;
class VideoController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Video';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        //dd(Videofiles::where('vid',1)->get());
        
        $grid = new Grid(new Video());
        
        /*README
            https://laravel-admin.org/docs/zh/model-grid
        */
        //$grid->model()->orderBy('id','desc');

        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('status', '上架', ['0' => '未上架', '1' => '上架']);
            $selector->select('hot', '熱門', ['0' => '一般', '1' => '熱門']);
            
            $videotags = Tag::all();
            $type_arr = [];
            if(!$videotags->isEmpty()){
                foreach ($videotags as $tag) {
                    $type_arr[$tag->id] = $tag->name;
                }
            }
            $selector->select('tags', '類型', $type_arr, function ($query, $value) {
                //dd($value);
                $query->whereHas('tags', function ($query) use ($value) {
                    $query->whereIN('video_tags.tag_id', $value);
                });
            });

        });

        $grid->sortable();
        $grid->id('ID')->sortable();
        $grid->column('預覽')->display(function(){
            $domain = DB::select('select domain from domains where type=1 and status =2');
            return '<a target="_blank" href="'.$domain[0]->domain.'/#/test?vid='.$this->id.'&token=13500000099">預覽</a>'; //http://vdw1.lixiaowei168.com/#/video?vid=581&token=13500000099
            
        });
        //$grid->column('img_path','轉檔狀態')->image(env('S3_URL'), 100, 100);
        // $grid->column('img_path')->help('點擊數統計')->display(function ($title) {
        //     $img_path = str_replace ('jpeg','html',$this->getOriginal()['img_path']);
        //     $img_text = file_get_contents(env('S3_URL').$img_path);
        //     $img_text = str_replace ('/','*',$img_text);
        //     $img_text = str_replace ('+','/',$img_text);
        //     $img_text = str_replace ('*','+',$img_text);
        //     return "<div style='position:relative; width:200px'><img src='$img_text' style='width:100%'></div>";
        // });
        
        // $grid->column('整包大小')->display(function () {
        //     $du_cmd = 'du -hd 0 '.public_path().'/mv/'.$this->id.'/ | cut -f 1';
        //     $du = exec($du_cmd);
        //     return $du;
        // });
        $grid->column('name', '标题')->editable();
        $grid->tags('標籤')->pluck('name')->label();
        
        $grid->column('view','觀看次數')->help('分分鐘更新')->label('primary')->sortable()->totalRow();
        $grid->column('favorite','收藏數')->help('分分鐘更新')->label('warning')->sortable()->totalRow();
        $grid->column('paid','幣收益')->sortable()->totalRow();
        // $grid->column('觀看次數')->help('點擊數統計')->display(function () {
        //     return Video_log::where('vid',$this->id)->count();
        // });

        //$grid->column('video_path','原檔下載')->downloadable();
        $grid->column('video_size', '原檔大小')->sortable()->filesize();
        $grid->column('m3u8_secs', '總秒數')->sortable();
        $grid->column('price', '價格')->sortable()->editable();
        // $grid->column('status', '上架狀態')->using(['0' => '<font color="red">未上架</font>', '1' => '<font color="blue">上架</font>']);
        $status_list = [
            'on'  => ['value' => 1, 'text' => '上架', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '未上架', 'color' => 'default'],
        ];
        $grid->column('status','上架狀態')->switch($status_list)->sortable();
        // $grid->column('hot','熱門')->using(['0' => '無', '1' => '<font color="blue">熱門</font>']);
        $hot_list = [
            'on'  => ['value' => 1, 'text' => '熱門', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '一般', 'color' => 'default'],
        ];
        $grid->column('hot','熱門')->switch($hot_list)->sortable();
        $grid->column('created_at','建立時間')->display(function($created_at){
            return Carbon::parse($created_at,'UTC')->tz('Asia/Taipei')->isoFormat("YYYY/M/D HH:mm:ss");
        });
        // $grid->column('updated_at','最後更新時間')->display(function($updated_at){
        //     return Carbon::parse($updated_at,'UTC')->tz('Asia/Taipei')->isoFormat("YYYY/M/D HH:mm:ss");
        // });

        
        
        $grid->actions(function ($actions) {
            $actions->disableView();
            // $actions->disableDelete();
            // $actions->disableEdit();
        });

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            //$filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('name', '標題');

        });
        return $grid;
    }

    
    
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        // $m3u8_info = $this->parseHLS(public_path().'/MV/41/file.m3u8');
        // dd($m3u8_info['data'][0]['sec']);
        $form = new Form(new Video());
        $form->text("name",'标题')->required();
        $form->number("price",'價格')->default(0)->min(0);
        //$form->multipleSelect('tags','標籤')->options(Tag::all()->pluck('name', 'id'));
        $form->checkbox('tags','標籤')->options(Tag::all()->pluck('name', 'id'));
        // $form->multipleSelect('tags','標籤')->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
        $form->file('video_path','視頻')->required();
        $form->datetime('title_sec','封面截圖秒數')->format('HH:mm:ss')->default('00:00:05');
        $form->image('custom_image','自訂封面')->removable();
        $form->hidden('video_size');
        $form->saving(function ($form){
            if($form->video_path){
                $form->video_size = $form->video_path->getSize();
            }
        });


        $form->ckeditor('content','內容說明');
        $form->switch('status', '发布？');
        $form->switch('hot', '熱門');
        $form->datetime("created_at",'建立時間')->disable();
        $form->datetime("updated_at",'最後更新時間')->disable();


        //功能開關
        $form->tools(function(Form\Tools $tools){
            $tools->disableView();
            $tools->disableDelete();
        });

        //功能開關
        $form->footer(function ($footer){
            $footer->disableReset();
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });
        
        // 在表单提交前调用
        $form->submitted(function (Form $form) {
            //dd(1);
        });

        //保存前回调
        $form->saving(function (Form $form) {
            //dd($form);
        });

        //保存后回调
        $form->saved(function (Form $form) {
            //如果 影片更換 或封面截圖秒數有異動才重轉
            $video_path ='/upload/'. $form->model()->getOriginal()['video_path'];
            $videoId = $form->model()->getOriginal()['id'];
            $title_sec = $form->model()->getOriginal()['title_sec'];
            // $MV_path = public_path().'/mv/'.$videoId;
            $filename=rand(1000,9999);

            if($form->model()->wasRecentlyCreated){  //新增模式
                $this->convertM3U8($video_path,$videoId,$title_sec,$filename);
            }else{ //編輯模式
                if(isset($form->model()->getChanges()['video_path']) || isset($form->model()->getChanges()['title_sec']) || $form->model()->getOriginal()['custom_image'] || @$form->model()->getChanges()['custom_image'] ){
                    $this->convertM3U8($video_path,$videoId,$title_sec,$filename);
                }
            }
        });


        return $form;
    }
    private function convertM3U8($video_path,$videoId,$title_sec,$filename){
        // $cmd = "ffmpeg -y -i /project/test.mp4 -hls_time 2 -hls_key_info_file /project/enc.keyinfo -hls_playlist_type vod -hls_segment_filename /project/file%d.ts /project/index.m3u8";
        $source_path = $video_path;//$key_info_path=public_path('key/').'enc.keyinfo';
        $target_path='/mv/'.$videoId.'/file.m3u8';//$target_path=base_path("public/MV/").'file.m3u8';
        $key_info_path=base_path('key/').'enc.keyinfo';//$key_info_path='/project/enc.keyinfo';

        
        //ProcessM3U8::dispatch($source_path,$target_path,$key_info_path)->onConnection('redis');
        ProcessM3U8::dispatch($source_path,$target_path,$videoId,$title_sec,$filename);

        // Video::where('id',$videoId)->update(['m3u8_path'] => "done");
        
    }

    
}
