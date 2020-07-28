<?php

namespace App\Admin\Controllers;

use App\Models\Video;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Carbon\Carbon;
use App\Models\Tag;

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
        /*README
            https://laravel-admin.org/docs/zh/model-grid
        */
        $grid->sortable();
        $grid->column('id', 'ID')->sortable();
        $grid->column('name', '标题')->editable();
        $grid->tags()->pluck('name')->label();
        $grid->column('video_path','檔案下載')->downloadable();
        $grid->column('video_size', '檔案大小')->filesize();
        $grid->column('price', '價格')->editable();
        // $grid->column('status', '上架狀態')->using(['0' => '<font color="red">未上架</font>', '1' => '<font color="blue">上架</font>']);
        $status_list = [
            'on'  => ['value' => 1, 'text' => '上架', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '未上架', 'color' => 'default'],
        ];
        $grid->column('status','上架狀態')->switch($status_list);
        // $grid->column('hot','熱門')->using(['0' => '無', '1' => '<font color="blue">熱門</font>']);
        $hot_list = [
            'on'  => ['value' => 1, 'text' => '熱門', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '一般', 'color' => 'default'],
        ];
        $grid->column('hot','熱門')->switch($hot_list);
        $grid->column('created_at','建立時間')->display(function($created_at){
            return Carbon::parse($created_at,'UTC')->tz('Asia/Taipei')->isoFormat("YYYY/M/D HH:mm:ss");
        });
        $grid->column('updated_at','最後更新時間')->display(function($updated_at){
            return Carbon::parse($updated_at,'UTC')->tz('Asia/Taipei')->isoFormat("YYYY/M/D HH:mm:ss");
        });

        
        
        $grid->actions(function ($actions) {
            $actions->disableView();
            // $actions->disableDelete();
            // $actions->disableEdit();
        });

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

        // $show->field('id', 'ID');
        // $show->field('name', '标题');
        
        // $show->field('tags');
        // $show->field('content');

        // $show->field('created_at')->display(function($created_at){
        //     return Carbon::parse($created_at,'UTC')->tz('Asia/Taipei')->isoFormat("YYYY/M/D h:mm:ss");
        // });;
        // $show->field('updated_at')->display(function($updated_at){
        //     return Carbon::parse($updated_at,'UTC')->tz('Asia/Taipei')->isoFormat("YYYY/M/D h:mm:ss");
        // });;

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
        $form->number("price",'價格')->min(0);
        $form->multipleSelect('tags','標籤')->options(Tag::all()->pluck('name', 'id'));
        // $form->multipleSelect('tags','標籤')->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
        $form->file('video_path','視頻')->required();
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
        
        return $form;
    }
}
