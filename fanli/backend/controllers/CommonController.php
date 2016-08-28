<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class CommonController extends Controller
{
    /**
     * 引入function.php
     * @return \CommonFunction
     */
    public function commonFunction()
    {
        require_once (__DIR__.'/../../common/common/function.php');
        return new \CommonFunction();
    }
    
    /**
     * 拼接接口地址
     * @param        $controller
     * @param        $function
     * @param string $module
     *
     * @return string
     */
    public function apiUrl( $controller , $function , $module = 'admin' )
    {
        return 'http://api.fanli.com/'.$module.'/'.$controller.'/'.$function;
    }

    /**
     * 设置token值
     */
    protected function setToken()
    {
        $token = $this -> commonFunction() -> getRandStr(20);
        $token = md5($token);
    }

    protected function CurlPost( $url , $data = array() )
    {
        if( empty( $data ) )
        {
            $arr = $this -> commonFunction() -> CurlPost( $url );
        }
        else
        {
            if ( is_array( $data ) )
            {
                $sign = $this -> setSign( $data );
            }
            else
            {
                $data = array( 'val' => $data );
                $sign = $this -> setSign( $data );
            }
            $data = array_merge( $data , array( 'sign' => $sign ) );
            $arr = $this -> commonFunction() -> CurlPost( $url , $data );
        }
        return $arr;
    }

    private function setSign( $data )
    {
        $num = count( $data );

        // 对数组的值按key排序
        ksort($data);

        // 生成url的形式
        $params = http_build_query($data);

        // 拼接密钥 且 md5
        $signAll = md5($params);

        // 计算截取长度
        $len = $num%32+6;

        // 得到数据签名
        $sign = substr($signAll,0,$len);

        return $sign;
    }
}