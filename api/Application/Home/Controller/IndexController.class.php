<?php
namespace Home\Controller;

use Home\Controller\CommonController;
use Home\Status\Status;

/**
 * Class IndexController
 * @package Home\Controller
 *
 * 考虑如何防止暴力破解密码
 *
 */
class IndexController extends CommonController
{
    public function index()
	{
		echo "Hello Word!!";
	}
}