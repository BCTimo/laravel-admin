<?php

namespace App\Admin\Controllers;

use App\Models\Video;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

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
        $grid->column('name', '标题');
        $grid->column('created_at');
        $grid->column('updated_at');
        
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
        $show->field('created_at');
        $show->field('updated_at');

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

        $form->text("name");


        return $form;
    }
}
