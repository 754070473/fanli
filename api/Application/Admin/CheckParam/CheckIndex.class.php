<?php
namespace Admin\CheckParam;
/**
 * 缺少参数的提示
 */
class CheckIndex extends BaseCheck
{


    /**
     * 检测登录接口是否缺少参数
     */
    public function login3($data)
    {


        $check_param_template = array(

            /**
             * type 表示类型
             * is_must 表示是否必须[1、必须]
             * enum 表示参数必须在数组的值之中
             */
            'user_name' => array(
                'type' => 's',
                'is_must' => 1
            ),
            'user_password' => array(
                'type' => 's',
                'is_must' => 1 ,
                'enum_array' => array(
                )
            ),
            'status' => array(
                'type' => 'i',
                'is_must' => 0 ,
                'enum_array' => array(
                    1,2
                )
            )
        );
        return $this -> checkParam( $data , $check_param_template );

    }


}