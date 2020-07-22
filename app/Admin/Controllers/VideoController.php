<?php

namespace App\Admin\Controllers;

use App\Models\Video;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Carbon\Carbon;

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
        $grid = new Grid(new Video());
        $grid->sortable();
        $grid->column('id', 'ID')->sortable();
        $grid->column('name', '标题')->editable();
        $grid->column('video_size', '檔案大小')->display(function($video_size){
        return round($video_size/1024/1024) ." Mb";
        });
        $grid->column('price', '價格')->editable();
        $grid->column('status', '上架狀態');

        $grid->column('created_at','建立時間')->display(function($created_at){
            return Carbon::parse($created_at,'UTC')->tz('Asia/Taipei')->isoFormat("YYYY/M/D h:mm:ss");
        });
        $grid->column('updated_at','最後更新時間')->display(function($updated_at){
            return Carbon::parse($updated_at,'UTC')->tz('Asia/Taipei')->isoFormat("YYYY/M/D h:mm:ss");
        });

        
        /*README
            https://laravel-admin.org/docs/zh/model-grid
        $grid->model()->where('id', '>', 100);
        $grid->model()->whereIn('id', [1, 2, 3]);
        $grid->model()->whereBetween('votes', [1, 100]);
        $grid->model()->whereColumn('updated_at', '>', 'created_at');
        $grid->model()->orderBy('id', 'desc');
        $grid->model()->take(100);
        */

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Video::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('name', '标题');
        
        $show->field('tags');
        $show->field('content');

        $show->field('created_at')->display(function($created_at){
            return Carbon::parse($created_at,'UTC')->tz('Asia/Taipei')->isoFormat("YYYY/M/D h:mm:ss");
        });;
        $show->field('updated_at')->display(function($updated_at){
            return Carbon::parse($updated_at,'UTC')->tz('Asia/Taipei')->isoFormat("YYYY/M/D h:mm:ss");
        });;

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
    


        $form = new Form(new Video());
        $form->text("name",'标题')->required();
        $form->number("price",'價格');
    // $form->tagsinput('values', '可选值');
    
        // $form->multipleSelect('tags','標籤')->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
        //$form->multipleSelect('tags','標籤')->options(Tag::all()->pluck('name', 'id'));
        $form->file('video_path','視頻')->required();
        $form->hidden('video_size');
        $form->saving(function ($form){
            if($form->video_path){
                $form->video_size = $form->video_path->getSize();
            }
        });


        $form->ckeditor('content','內容說明');
        $form->switch('status', '发布？');
        $form->display("created_at",'建立時間');
        $form->display("updated_at",'最後更新時間');

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
            //$footer->disableCreatingCheck();
        });
        
        return $form;
    }
}
