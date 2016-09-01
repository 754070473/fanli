<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
use Think\Controller;
use Admin\Status\Status;
use Admin\Status\Success;
use Admin\Status\Param;
/**
 * Class IndexController
 * @package Admin\Controller
 *
 * 考虑如何防止暴力破解密码
 *
 */
class NavigateController extends CommonController{
    /*
     * 导航的添加接口
     */
    public function insert()
    {
        $User = M("navigate"); // 实例化User对象
        $nav_name   = IsNaN( $this -> _data , 'nav_name' );
        if( empty( $nav_name ) ) {
            $this -> errorMessage( Param::NAV_NAME_NULL , Param::NAV_NAME_NULL_MSG );
            exit;
        }
        $nav_photo  = IsNaN( $this -> _data , 'nav_photo');
        if( empty( $nav_photo ) ) {
            $this -> errorMessage( Param::NAV_PHOTO_NULL , Param::NAV_PHOTO_NULL_MSG );
            exit;
        }
        $time      =  IsNaN( $this -> _data , 'nav_date' );
        $nav_pid = IsNaN( $this -> _data , 'nav_pid' );
        $nav_status = IsNaN( $this -> _data , 'nav_status' );
        $data = array(
            'nav_name'     => $nav_name,
            'nav_status'    => $nav_status,
            'nav_pid'      => $nav_pid,
            'nav_date'      => $time,
            'nav_photo'     => $nav_photo
        );
        $re = $User->add($data);
        if( $re ){
            $this -> success( Success::NAV_ADD_SUCCESS , Success::NAV_ADD_SUCCESS_MSG );
            exit;
        }else{
            $this -> errorMessage( Status::NAV_NAME_ERROR , Status::NAV_NAME_ERROR_MSG );
            exit;
        }
    }
    /*
    * 导航的删除接口
    */
    public function delete(){
        $User = M("navigate"); // 实例化User对象
        $nav_id   = IsNaN( $this -> _data , 'nav_id' );
        $where['nav_pid'] = $nav_id;
        $res = $User->where($where)->select();
        if($res){
            $this -> success( 0 );
            exit;
        }else{
            $aa['nav_id'] = $nav_id;
            $User->where($aa)->delete();
            $this -> errorMessage( 1 );
            exit;
        }
    }
   /*
    * 导航状态值修改
    */
    public function update(){
        $User = M("navigate"); // 实例化User对象
        $nav_id   = IsNaN( $this -> _data , 'id' );
        $nav_status   = IsNaN( $this -> _data , 'status' );
        $info['nav_status'] = $nav_status;
        $User->where('nav_id='.$nav_id)->save($info);
        $this -> success( 0 );
        exit;
    }
}