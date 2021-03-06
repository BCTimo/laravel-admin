<?php

namespace App\Admin\Controllers;

use App\Models\Domain;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Carbon\Carbon;

use Encore\Admin\Widgets\Tab;
class DomainController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Domain';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Domain());
        

        $grid->selector(function (Grid\Tools\Selector $selector) {
            $type_list = [1=>'H5域名', 20=>'封面域名' , 21=> 'KEY域名' ,22=>'資源域名'];
            $selector->select('type','域名类型', $type_list);
            $status_list = [ 1 => '备用' , 2 => '启用' , 3 => '被拦截'];
            $selector->select('status','域名状态',$status_list);


        });

        $grid->column('domain', '域名')->link();
        $grid->column('explain','使用说明');
        $type_list = [1=>'H5域名', 20=>'封面域名' , 21=> 'KEY域名' ,22=>'資源域名'];
        // $grid->column('type','域名类型')->options($type_list);//->default('m');
        // $grid->column('type','域名类型')->radio($type_list);
        $grid->column('type','域名类型')->using($type_list)->sortable()->filter($type_list);
        
        

        // $platform_list = [1 => 'WAP',2 => 'APP'];
        // $grid->column('platform','使用平台')->radio($platform_list);//->default('m');
        
        // $states = [
        //     'on'  => ['value' => 1, 'text' => '打开', 'color' => 'success'],
        //     'off' => ['value' => 0, 'text' => '关闭', 'color' => 'danger'],
        // ];        
        // $grid->column('ssl', '是否https访问')->switch($states);
        // $grid->column('power', '是否高权域名')->switch($states);

        // $ssl_states = [
        //     '加密'  => ['value' => 1, 'text' => '打开', 'color' => 'success'],
        //     '未加密' => ['value' => 0, 'text' => '关闭', 'color' => 'danger'],
        // ];
        // $grid->column('base64', '資源域名用加密欄位')->switch($ssl_states);

        // $grid->column('create_time', __('Create time'));
        // $grid->column('enable_time', __('Enable time'));
        // $grid->column('expiration_time', __('Expiration time'));
        // $grid->column('created_at', __('Created at'));
        $grid->column('updated_at','最後更新時間')->sortable()->display(function($updated_at){
            return Carbon::parse($updated_at,'UTC')->tz('Asia/Taipei')->isoFormat("YYYY/M/D HH:mm:ss");
        });
        $status_list = [ 1 => '备用' , 2 => '启用' , 3 => '被拦截'];
        $grid->column('status','域名状态')->using($status_list)->sortable()->filter($status_list);

        
        // $grid->model()->where('id',1);

        // $tab = new Tab();
        // $tab->add('Grid', $grid->render());
        // $tab->add('H5', $grid->render());
        // $tab->add('封面域名', Domain::where('type',20)->get());
        // //dd($grid);
        // return  $tab->render();
        

        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Domain());

        $form->text('domain', '域名');
        $form->text('explain','使用说明');
        $type_list = [1=>'H5域名', 20=>'封面域名' , 21=> 'KEY域名' ,22=>'資源域名'];
        $form->radioCard('type','域名类型')->options($type_list);//->default('m');

        $platform_list = [1 => 'WAP',2 => 'APP'];
        $form->radioCard('platform','使用平台')->options($platform_list);//->default('m');
        
        $states = [
            'on'  => ['value' => 1, 'text' => '打开', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '关闭', 'color' => 'danger'],
        ];        
        $form->switch('ssl', '是否https访问')->states($states);
        $form->switch('power', '是否高权域名')->states($states);

        $ssl_states = [
            '加密'  => ['value' => 1, 'text' => '打开', 'color' => 'success'],
            '未加密' => ['value' => 0, 'text' => '关闭', 'color' => 'danger'],
        ];
        $form->switch('base64', '資源域名用加密欄位')->states($ssl_states);

        $status_list = [ 1 => '备用' , 2 => '启用' , 3 => '被拦截'];
        $form->radioCard('status','域名状态')->options($status_list)->default(1);
        // $form->datetime('intercept_time', '域名拦截时间')->default(date('Y-m-d H:i:s'));
        // $form->datetime('create_time', __('Create time'))->default(date('Y-m-d H:i:s'));
        // $form->datetime('enable_time', __('Enable time'))->default(date('Y-m-d H:i:s'));
        // $form->datetime('expiration_time', '過期時間')->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
