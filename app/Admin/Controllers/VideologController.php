<?php

namespace App\Admin\Controllers;

use App\Models\Video_log;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Jxlwqq\DataTable\DataTable;

class VideologController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '每日消耗報表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(){
        $headers = ['点击量', '消耗金币总量','日期','wap点击量','app点击量','wap金币消耗量','app金币消耗量'];
        $rows = Video_log::bookbean_by_day();
        $style = ['table-bordered','table-hover', 'table-striped'];
        $options = [
            'paging' => true,
            'lengthChange' => false,
            'searching' => false,
            'ordering' => false,
            'info' => true,
            'autoWidth' => false,
        ];
        $dataTable = new DataTable($headers, $rows, $style, $options);

        return $dataTable->render();
    }

  
}
