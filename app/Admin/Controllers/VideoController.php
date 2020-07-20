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
        
        $grid->column('id', 'ID')->sortable();
        $grid->column('name', '标题')->editable();
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
        $form->text("name",'名稱');
        $form->text("tags",'標籤');

        $form->file('video_path','視頻')->downloadable();

        $form->ckeditor('content','內容說明');
//  $form->file($column[, $label]);
// // 修改文件上传路径和文件名
// $form->file($column[, $label])->move($dir, $name);
// // 并设置上传文件类型
// $form->file($column[, $label])->rules('mimes:doc,docx,xlsx');
// // 添加文件删除按钮
// $form->file($column[, $label])->removable();
// // 删除数据时保留文件
// $form->file($column[, $label])->retainable();
// // 增加一个下载按钮，可点击下载
// $form->file($column[, $label])->downloadable();

        $form->display("created_at");
        $form->display("updated_at");

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
