<?php
namespace Admin\Controller;

use Admin\Controller\CommonController;
use Think\Controller;
use Admin\Status\Status;
use Admin\Status\Success;
use Admin\Status\Param;
class ActivityController extends CommonController{
    /*
     * 添加活动
     * */
    public  function add(){
        //接收数据
        $act_name = IsNaN( $this -> _data , 'act_name');
        $type_id = IsNaN( $this -> _data , 'type_id');
        $start_time = IsNaN( $this -> _data , 'start_time');
        $end_time = IsNaN( $this -> _data , 'end_time');
        $act_date = IsNaN( $this -> _data , 'act_date');
        $act_desc = IsNaN( $this -> _data , 'act_desc');
        $act_order = IsNaN( $this -> _data , 'act_order');
         $act_img = IsNaN( $this -> _data , 'act_img');
        $data = [ 
         'act_img' => $act_img,
            'act_name' => $act_name,
            'type_id' => $type_id,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'act_date' => $act_date,
            'act_desc' => $act_desc,
            'act_order' => $act_order,
        ];
        // 实例化
        $Activity = M('activity');
        // 添加活动
        $act_info = $Activity->add($data);
        if($act_info){
            $this -> success( Success::ADMIN_ADD , Success::ADMIN_ADD_MSG , array());
        }else{
            $this -> errorMessage( Status::ADMIN_NAME_ERROR , Status::ADMIN_NAME_ERROR_MSG );
        }
    }
    function del(){
         $act_id = IsNaN( $this -> _data , 'act_id');
         if(!empty($act_id)){
            $Activity = M('activity');
            $act_info = $Activity
                        ->where('act_id='.$act_id)
                        ->delete();
            $this -> success( 0 , '成功' );
         }
         else
         {
            $this -> errorMessage( '1' , '失败');
         }
        
    }
}