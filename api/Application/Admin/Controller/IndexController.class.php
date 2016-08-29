<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
use Think\Controller;
use Admin\Status\Status;
use Admin\Status\Success;

/**
 * Class IndexController
 * @package Admin\Controller
 *
 * 考虑如何防止暴力破解密码
 *
 */
class IndexController extends CommonController
{
    public function index()
	{
		$arr = array(1,1);
		$this -> success(
			Success::LOGIN_SUCCESS ,
			Success::LOGIN_SUCCESS_MSG ,
			$arr
		);
	}
}