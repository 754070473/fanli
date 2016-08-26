<?php
namespace Home\CheckParam;

use Home\Controller\CommonController;
use Home\Status\Status;
use Home\Status\Param;

/**
 * 缺少参数的提示
 */
class BaseCheck
{


    /**
     * 根据模块检测是否缺少参数
     */
    public function checkParam(&$data, $template)
    {
        //遍历判断是否传递必须参数,并且对参数进行强制类型转换
        foreach ($template as $key => $value) {

            //必传参数缺少
            if ($value['is_must'] == 1) {
                if (!isset($data[$key])) {
                    $error = array('mark' => Param::PARAM_MISS , 'msg' => sprintf(Param::PARAM_MISS_MSG, $key));
                    break;
                } else {

                    //强制类型转换
                    $this -> f( $value['type'] , $data[$key] );

                    if ( !empty( $value['enum_array'] ) && !in_array($data[$key], $value['enum_array'])) {
                        $error = array('mark' => Param::PARAM_ERROR, 'msg' => sprintf(Param::PARAM_ERROR_MSG, $key));
                        break;
                    }
                }
            }else{
                //强制类型转换
                $this -> f( $value['type'] , $data[$key] );

                //参数是否正确
                if ( !empty( $value['enum_array'] ) && !in_array($data[$key], $value['enum_array'])) {
                    $error = array('mark' => 1, 'msg' => sprintf(Param::PARAM_ERROR_MSG, $key));
                    break;
                }
            }
        }

        return $error;
    }

    /**
     * 按照定义格式格式化参数
     * @param unknown $f
     * @param unknown $val
     */
    private function f($f,&$val){

        switch (strtoupper($f)){
            case 'PASS' :;break;
            case 'I': $val = intval($val);break;
            case 'S': $val = strval($val);break;
            case 'F': $val = floatval($val);break;
            case 'D': $val = doubleval($val);break;
            case 'T10': $val = (string) $val;break;
            case 'T13': $val = (string) $val * 1000 ;break;
            case 'DOC2X' : $val = decimalFormat( $val , 2  );break;
            case 'DOC4X' : $val = decimalFormat( $val , 4  );break;
            case 'DOC6X' : $val = decimalFormat( $val , 6  );break;
            case 'DOC8X' : $val = decimalFormat( $val , 8  );break;
        }
    }

}