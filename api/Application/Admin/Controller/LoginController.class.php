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
class LoginController extends CommonController
{
    public function index()
	{
		//用户名
		$user_name = IsNaN( $this -> _data , 'account' );
		if( empty( $user_name ) ){
			$this -> errorMessage( Param::LOGIN_USER_NAME_IS_NULL , Param::LOGIN_USER_NAME_IS_NULL_MSG );
		}
		
		//密码
		$user_password = IsNaN( $this -> _data , 'password' );
		if( empty( $user_name ) ){
			$this -> errorMessage( Param::LOGIN_USER_NAME_IS_NULL , Param::LOGIN_USER_NAME_IS_NULL_MSG );
		}

		$User = M("admin"); // 实例化User对象
		$arr_login = $User->where('account = ' . "'$user_name'")->find();
		if ( empty( $arr_login ) ) {
			//用户不存在
			$this -> errorMessage( Status::USER_NOT_FOUND , Status::USER_NOT_FOUND_MSG );
		} else {
			//判断密码是否正确
			if ( md5( $user_password ) == $arr_login['password'] ) {
				//密码正确 登录成功
				$token = $this -> createToken();

				//存储token
				$User->where('adm_id = ' . $arr_login['adm_id'])-> save( array( 'token' => $token ) ); // 根据条件更新记录
				$arr_login['token'] = $token ;
				$this -> success( Success::LOGIN_SUCCESS , Success::LOGIN_SUCCESS_MSG , $arr_login );
			} else {
				//密码错误
				$this -> errorMessage( Status::USER_PASSWORD_ERROR , Status::USER_PASSWORD_ERROR_MSG );
			}
		}
	}


	private function createToken()
    {
		$len = rand ( 1 , 20 );
        $token = mt_rand_str( $len ) . time();
		return md5( $token );
    }
}