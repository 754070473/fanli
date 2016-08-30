<?php
namespace Admin\Controller;
use Think\Controller;

class CommonController extends Controller
{
    public $_data = '';

    /**
     * 构造函数
     * CommonController constructor.
     */
    public function __construct()
    {
        //参数过滤【 必须要的，防止XSS攻击以及SQL注入等 】
        $all_data = array_merge ($_POST, $_GET, $_REQUEST);
        unset($_POST);
        unset($_GET);
        unset($_REQUEST);

        $_data = html_encode ($all_data);
        $this -> _data = $_data;


        //接口的参数校验
        //获取当前请求的控制器
        $Controller = ucwords( str_replace( __MODULE__ . '/' , '' , __CONTROLLER__ ) );

        //当前请求的方法
        //$Action = ucwords( str_replace( __CONTROLLER__ . '/' , '' , __ACTION__ ) );

        if( !in_array( $Controller , array( 'Login' , 'Common' ) ) )
        {
            $token = $_data['token'];
            $adm_id = $_data['adm_id'];
            if( $this -> testToken( $adm_id , $token ) == false )
            {
                $this -> errorMessage( 1 , '非法请求' );
                exit;
            }
        }

        /*if( !in_array( $Controller , array( 'Base' , 'Common' ) ) ){

            //拼装格式化类
            $Format_Controller = 'Check'.$Controller;

            //反射格式化类
            $ReflectionClass = new \ReflectionClass('Admin\CheckParam\\'.$Format_Controller);

            //获取格式化类的方法
            $ReflectionMethod = $ReflectionClass->getMethod($Action);

            //实例化格式化类
            $Instance = $ReflectionClass->newInstance();

            //格式化数据类型
			 $arr['data'] = $ReflectionMethod->invokeArgs( $Instance , array( $all_data ) );
        }*/
        //合并返回结果
//		$arr = array_merge( $arr , (array) $othor_data );
        if(isset($_data['token'])) {
            unset( $_data[ 'token' ] );
            unset( $_data['adm_id'] );
        }
        $sign = $_data['sign'];
        unset($_data['sign']);
        //判断sign值是否正确
        $_sign = $this -> setSign( $_data );
        if( $sign != $_sign )
        {
            $this -> errorMessage( 1 , '非法请求' );
            exit;
        }
    }



    /**
     * 输入json数据
     */
    public function JsonOutPut( $arr = array() , $other_data = array() )
    {

        if (!is_array ($arr)) {
            $arr = ( array )$arr;
        }

        //合并返回结果
        $arr = array_merge ($arr, (array)$other_data);
//
        //返回的JSON数据
        $json_str = json_encode ($arr);

        echo $json_str;

        exit;
    }

    /**
     * 错误信息返回
     * @param int    $error_status 状态码
     * @param string $error_msg    错误消息
     * @param array  $error_data   返回数组
     * @param array  $other_data   其他数组
     */
    public function errorMessage( $error_status = 1 , $error_msg = 'Error' , $error_data = array() , $other_data = array() )
    {

        //拼装数据
        $error_arr = [];

        //失败的状态码
        $error_arr['status'] = $error_status;

        //失败的提示信息
        $error_arr['msg'] = $error_msg;

        //失败返回的错误数据
        $error_arr['data'] = $error_data;

        $this->JsonOutPut ($error_arr, $other_data);
    }

    /**
     * 成功信息返回
     * @param int    $success_status 状态码
     * @param string $success_msg    成功消息
     * @param array  $data           返回数组
     * @param array  $other_data     其他数组
     */
    public function success( $success_status = 0  , $success_msg = 'success' , $data = array() ,  $other_data = array() )
    {

        //拼装数据
        $error_arr = array();

        //失败的状态码
        $error_arr['status'] = $success_status;

        //失败的提示信息
        $error_arr['msg'] = $success_msg;

        //失败返回的错误数据
        $error_arr['data'] = $data;

        $this -> JsonOutPut( $error_arr , $other_data  );

    }

    //生成sign
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

    //检测token
    private function testToken( $adm_id , $token )
    {
        $User = M("admin"); // 实例化User对象
        $data = $User->where('adm_id = '.$adm_id )->find();
        if ( $token == $data['token'] )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}