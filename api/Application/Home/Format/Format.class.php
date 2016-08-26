<?php
namespace Home\Format;

/**
 * 格式化数据
 * Class Format
 * @package Home\Format
 */
class Format{
    /**
     * 格式化数据
     * @param array $param	需要格式化的数据
     * @param array $template 格式化的模板
     * @return array
     */
    protected  function format(&$param,$template){
        if(is_array($template)){
            foreach ($param as $key=> &$val){
                if(is_numeric($key)){
                    $this->format($val,$template[0]);
                }else if(is_string($template[$key])){
                    if(isset($template[$key])){
                        $this->f($template[$key],$val);
                    }else{
                        unset($param[$key]);
                    }
                }else if(is_array($template[$key])){
                    $k = key($template[$key]);
                    if(is_numeric($k)){
                        foreach ($param[$key] as $nk=> &$item){
                            $this->format($item,$template[$key][$k]);
                        }
                    }else if(is_string($k)){
                        foreach ($val as $mk=>&$mv){
                            $this->f($template[$key][$mk],$mv);
                        }
                    }
                }

            }
        }else if(is_string($template)){
            $this->f($template,$param);
        }
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
