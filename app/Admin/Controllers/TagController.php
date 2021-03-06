<?php

namespace App\Admin\Controllers;

use App\Models\Tag;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Carbon\Carbon;

class TagController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Tag';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Tag());

        $grid->sortable();
        $grid->column('id', 'ID')->sortable();
        $grid->column('name', __('標籤'))->editable();
        $grid->column('top', '置頂')->switch();
        // $grid->column('type', '分類')->switch();
        $type_list = ['0' => '一般標籤', '1'=> '猜你喜歡類', '2'=> '排行類'];
        $grid->column('type','分類')->radio($type_list);

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

        $grid->filter(function($filter){
            $filter->like('name', '標題');
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
        $show = new Show(Tag::findOrFail($id));

        // $show->field('id', __('Id'));
        // $show->field('name', __('Name'));
        // $show->field('sort', __('Sort'));
        // $show->field('created_at', __('Created at'));
        // $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Tag());

        $form->text('name', '標籤名稱');
        $form->switch('top', '置頂');
        // $form->switch('type', '分類');
        $type_list = ['0' => '一般標籤', '1'=> '猜你喜歡類', '2'=> '排行類'];
        $form->radio('type','分類')->options($type_list)->default(0);


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
