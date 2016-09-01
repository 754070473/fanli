<?php
namespace Admin\Controller;

use Admin\Controller\CommonController;
use Think\Controller;
use Admin\Status\Status;
use Admin\Status\Success;
use Admin\Status\Param;


/*
 * @author:dongmengtao
 * @controller:管理员
 * @time:2016/08/27
 */

/**
 * Class IndexController
 * @package Admin\Controller
 *
 * 考虑如何防止暴力破解密码
 *
 */
class AdminController extends CommonController
{
    public function index(){
        //用户名
        $user_name = IsNaN( $this -> _data , 'account');
        if(empty($user_name)){
            $this -> errorMessage(Param::ADMIN_NAME_IS_NULL, Param::ADMIN_NAME_IS_NULL_MSG);
        }

        //密码
        $password = IsNaN( $this -> _data , 'password');
        if(empty($password)){
            $this-> errorMessage(Param::ADMIN_PASSWORD_IS_NULL, Param::ADMIN_PASSWORD_IS_NULL_MSG );
        }

        $date = date( 'Y-m-d' , time() );
        //实例化对象
        $User = M('admin');
        $data = array( 'account' => $user_name , 'password' => md5($password) ,'adm_date' => $date );
        $arr_add = $User->add($data);
      //  var_dump($arr_add);die;
        if($arr_add){
            $this -> success( Success::ADMIN_ADD , Success::ADMIN_ADD_MSG , $arr_add);
        }else{
            $this -> errorMessage( Status::ADMIN_NAME_ERROR , Status::ADMIN_NAME_ERROR_MSG );
        }
    }

}