<?php
namespace Home\Controller;

use Think\Controller;

class CommonController extends Controller
{


    /*public function __construct()
    {
        //参数过滤【 必须要的，防止XSS攻击以及SQL注入等 】
        //xxxxxxxxxxxxxx

        //获取当前请求的控制器
        $Controller = ucwords( str_replace( __MODULE__ . '/' , '' , __CONTROLLER__ ) );

        //当前请求的方法
        $Action = ucwords( str_replace( __CONTROLLER__ . '/' , '' , __ACTION__ ) );

//        var_dump( $Action );exit;

        //判断参数是不是缺少

        if( !in_array( $Controller , array( 'Base' , 'Common' ) ) ){
            //拼装格式化类
            $Check_Controller = 'Check' . $Controller;

            //反射格式化类
            $ReflectionClass = new \ReflectionClass('Home\CheckParam\\'.$Check_Controller);

            //获取格式化类的方法
            $ReflectionMethod = $ReflectionClass->getMethod($Action);

            //实例化格式化类
            $Instance = $ReflectionClass->newInstance();

            //格式化数据类型
            $error_info = $ReflectionMethod->invokeArgs( $Instance , array( $_REQUEST ) );

            //提示参数错误
            if( !empty( $error_info ) && $error_info['mark'] != 0 ){
                $this ->  failure( $error_info['mark'] , $error_info['msg'] );
            }
        }


    }*/


    /**
     * 输入json数据
     */
    public function JsonOutPut( $arr = array() , $other_data = array() ){

        if( !is_array( $arr ) ){
            $arr = ( array ) $arr;
        }

        //获取当前请求的控制器
        $Controller = ucwords( str_replace( __MODULE__ . '/' , '' , __CONTROLLER__ ) );

        //当前请求的方法
        $Action = ucwords( str_replace( __CONTROLLER__ . '/' , '' , __ACTION__ ) );

        if( !in_array( $Controller , array( 'Base' , 'Common' ) ) ){
            //拼装格式化类
            $Format_Controller = 'Format'.$Controller;

            //反射格式化类
            $ReflectionClass = new \ReflectionClass('Home\Format\\'.$Format_Controller);

            //获取格式化类的方法
            $ReflectionMethod = $ReflectionClass->getMethod($Action);

            //实例化格式化类
            $Instance = $ReflectionClass->newInstance();

            //格式化数据类型
            $arr['data'] = $ReflectionMethod->invokeArgs( $Instance , array( $arr['data'] ) );

        }
        //合并返回结果
        $arr = array_merge( $arr , (array) $other_data );

        //返回的JSON数据
        $json_str = json_encode( $arr );

        //记录返回数据
        if( C('API_LOG') ){
            doApiLog( '本次返回数据为：', $arr );
            if( C('API_LOG_MORE') ){
                doApiLog( '本次返回JSON数据为：', $json_str );
            }
        }
        echo $json_str;
        exit;
    }

    /**
     * 失败输出
     */
    public function failure( $error_status = 1 , $error_msg = 'ERROR' , $error_data = array() , $other_data = array() ){

        //拼装数据
        $error_arr = array();

        //失败的状态码
        $error_arr['status'] = $error_status;

        //失败的提示信息
        $error_arr['msg'] = $error_msg;

        //失败返回的错误数据
        $error_arr['data'] = $error_data;

        $this -> JsonOutPut( $error_arr , $other_data );

    }

    /**
     * 成功输入
     */
    public function success( $data = array() , $success_msg = 'success' , $success_status = 0 ,  $other_data = array() ){

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


}