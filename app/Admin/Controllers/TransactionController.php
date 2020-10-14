<?php

namespace App\Admin\Controllers;


use App\Models\Transaction;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Jxlwqq\DataTable\DataTable;
use Encore\Admin\Layout\Content;
use DB;
class TransactionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '閱讀紀錄';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */


    protected function grid()
    {
        
    }

    public function search_record(Request $request){
        $utype = $request->input('uidtype');
        $uid = $request->input('uid');

        $table = $this->getTransTbname($utype,$uid);
        
        if($utype && $uid){
            $rows = DB::select('select * from '.$table.' where userid = '.$uid .' order by time desc');
        }else{
            return view('transaction', ['data' => 'foo']);
        }
        $headers = ['id', 'uid', 'videoID', 'videoName','書幣','剩餘書幣','換取時間'];


        $style = ['table-bordered','table-hover', 'table-striped'];

        $options = [
            'paging' => 0,
            'lengthChange' => 1,
            'searching' => 1,
            'ordering' => true,
            'info' => true,
            'autoWidth' => 0,
        ];

        $dataTable = new DataTable($headers, $rows, $style, $options);

        return $dataTable->render();
    }
    public function getTransTbname($utype,$uid){

        if($utype=="tempUser"){
            if(strlen($uid)>1){
                $a = substr($uid,-2);
            }else{
                $a = $uid;
            }

            if($a<20){
                $tbname="c_trade_record_temp";
            }else{
                $tb= $a%20;
                $tbname="c_trade_record_temp_".$tb;
            }

        }else{
            if(strlen($uid)>1){
                $a = substr($uid,-2);
            }else{
                $a = $uid;
            }

            if($a<10){
                $tbname="c_trade_record";
            }else{
                $tb= $a%10;
                $tbname="c_trade_record_".$tb;
            }

        }

        return $tbname;
    }
}
