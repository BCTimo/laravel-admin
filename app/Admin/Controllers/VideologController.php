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
    protected $title = '每日人流報表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $headers = ['日期', '人流'];
        $rows = Video_log::user_count_by_day();
        //dd($rows);
        // $rows = [
        //     [1, 'labore21@yahoo.com', 'Ms. Clotilde Gibson', 'Goodwin-Watsica'],
        //     [2, 'omnis.in@hotmail.com', 'Allie Kuhic', 'Murphy, Koepp and Morar'],
        //     [3, 'quia65@hotmail.com', 'Prof. Drew Heller', 'Kihn LLC'],
        //     [4, 'xet@yahoo.com', 'William Koss', 'Becker-Raynor'],
        //     [5, 'ipsa.aut@gmail.com', 'Ms. Antonietta Kozey Jr.', 'Goooogle'],
        // ];

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
