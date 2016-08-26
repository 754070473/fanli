<?php


/**
 * 判断变量是否存在
 */
function IsNan( $param , $key , $default = '' ){

    return empty( $param[$key] ) ? $default : $param[$key];

}

/**
 * 生成随机的字符串
 * @param $length
 * @return null|string
 */
function mt_rand_str($length){
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol)-1;

    for($i=0;$i<$length;$i++){
        $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
    }

    return $str;
}

/**
 * 自定义打印
 * @param array $param
 * @param bool $exit_mark
 */
function P( $param = array() , $exit_mark = false ){

    headers_sent( ) ? '' :header('content-type:text/html;charset=utf-8');
    echo '<pre/>';

    print_r( $param );

    echo '<br/>';

    if( $exit_mark ){
        exit;
    }
}


/**
 * CurlPost 方法
 * 用于请求外部接口
 * @param $url 请求的路径
 * @param $data 请求的参数
 * @param $timeout
 */
function CurlPost($url, $param = null, $timeout = 10 ) {

    //初始化curl
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url); // 设置请求的路径
    curl_setopt($curl, CURLOPT_POST, 1); //设置POST提交
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 ); //显示输出结果
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);

    //提交数据
    if (is_array($param)) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($param));
    } else {
        curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
    }

    //执行请求
    $data = $data_str = curl_exec($curl);

    //处理错误
    if ($error = curl_error($curl)) {
        $logdata = array(
            'url' => $url,
            'param' => $param,
            'error' => '<span style="color:red;font-weight: bold">' . $error . '</span>',
        );

//            var_dump($logdata);exit;
        if( C( 'LOG_DEBUG' ) ){
            doApiLog( ' 请求接口发生错误，错误信息为'. print_r( $logdata, true ) );
        }
    }


    curl_close($curl);

    //json数据转换为数组
    $data = json_decode( $data , true );

    if( !is_array( $data ) ){
        $data = $data_str;
    }
    //记录请求日志
    if( C( 'LOG_DEBUG' ) ){
        doApiLog( ' 请求接口地址为：' . $url , '请求参数为：' . print_r( $param , true ) , '返回数据为：' . print_r( $data, true ) );
    }

    return $data;

}

/**
 * 参数过滤
 * @param $array
 * @return array|string
 */
function html_encode($array){
    if (empty($array))
        return $array;
    return is_array($array) ? array_map('html_encode', $array) : addslashes(stripslashes(str_replace(array(
        '\&quot',
        '\&#039;',
        '\\'
    ), array(
        '&quot',
        '&#039;',
        ''
    ), trim(htmlspecialchars($array, ENT_QUOTES)))));
}


/* 检验手机是否合法
 * @param    string      $str       待检验的手机号字符串
 * @return   boolean     true是合法的手机号,false为非法的手机号
 */
function is_phone($str){
    return @preg_match("/^1[0-9]{10}$/", $str);
}

/**
 * 创建文件夹
 * @param string 路径
 * @param int 权限
 * @return boolean
 */
function mkdirs_a($path, $mode = 0777)
{
    return @mkdir($path,$mode,true);
    //兼容低版本PHP
    /*$path      = str_replace("\\", '/', $path);
    $dirs      = explode('/', ($path = substr($path, -1, 1) !== '/' ? $path . '/' : $path));
    $pos       = strrpos($path, ".");
    $subamount = $pos === false ? 0 : 1;
    for ($c = 0; $c < count($dirs) - $subamount; $c++) {
        $thispath = "";
        for ($cc = 0; $cc <= $c; $cc++) {
            $thispath .= $dirs[$cc] . '/';
        }
        if (!file_exists($thispath)){
            //解决访问不允许访问的目录，报错的问题
            try {
                @mkdir($thispath, $mode);
            } catch (Exception $e) {
            }
        }
    }*/
}

/**
 * 写记录日志方法
 *     按天生成文件夹
 */
function doApiLog( ){
    if( C('API_LOG') ){

        $action = strtolower( str_replace( '/' , '_' , str_replace( __MODULE__ . '/' , '' , __ACTION__   ) ) );
        $args_num = func_num_args();

        $args = func_get_args();

        if( $args_num < 0  ){
            return false;
        }
        //设置错误日志的路径
        if( C('API_LOG_PATH') ){
            $error_log_path =  APP_PATH  . C('API_LOG_PATH');
        }else{
            $error_log_path =  APP_PATH ;
        }

        if( !is_dir( $error_log_path ) ){
            mkdir( $error_log_path );
        }

        //按天生成文件夹
        $error_log_path .= date('Ym');
        if( !is_dir( $error_log_path ) ){
            mkdir( $error_log_path );
        }

        $error_log_path .=  '/' . date('d');
        if( !is_dir( $error_log_path ) ){
            mkdir( $error_log_path );
        }
        //生成日志文件
        $log_file_name = $error_log_path . '/' .$action . '_access.log';

        //打开文件
        $handle = fopen( $log_file_name , 'a+' );

        if( !is_writable( $log_file_name )){
            echo "文件不可写" . $log_file_name;
            exit;
        }

        $log = '[' . sprintf( '%s' , date('Y-m-d H:i:s') ) . ']';

        //遍历参数，写入日志文件
        for( $i = 0 ; $i < $args_num ; $i ++){
            if( is_object( $args[$i] ) || is_array( $args[$i] ) ){
                $log .= sprintf( '%s' , print_r( $args[$i] , true ) ) . "\r\n" ;
            }else{
                $log .= sprintf( '%s' , $args[$i] ) . "\r\n" ;
            }
        }
        //写入文件
        fwrite( $handle , $log );
        fclose( $handle );
    }
}


function getApiRequestUrl( $api_conf = '' , $api_host = '' ){

    //接口的域名
    if( $api_host == '' ){
        $api_host = C('API_HOST');
    }

    //获取接口的配置
    $api_conf_arr = C('API_CONF');
    if( !empty( $api_conf_arr[$api_conf] ) ){
        $api_conf_str = $api_conf_arr[$api_conf];
    }else{
        $api_conf_str = '';
    }

    return $api_host . $api_conf_str;

}

/**
 * 获取API返回的数据
 */
function getApiResult(){

}

/**
 * 字符串转换FLOAT类型，不四舍五入
 */
function decimalFormat( $num , $sub = 2 ) {
    $num = (string) $num;
    $num = str_replace(',', '', $num);
    $dec = sprintf('%.8f', $num);
    $dec = substr( $dec , 0  , $sub + strpos( $dec , '.' ) + 1 );
    return floatval($dec);
}

/**
 * 保留两位小数，不四舍五入
 */
function numFormat( $num , $sub = 2 ) {
    $num = (string) $num;
    $num = str_replace(',', '', $num);
    $dec = sprintf('%.8f', $num);
    $dec = substr( $dec , 0  , $sub + strpos( $dec , '.' ) + 1 );
    return $dec;
}

/**
 * 生成随机的字符串
 */
function getRandStr( $len ){
    return substr( str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ") , 0, $len );
}

/**
 * 元转分
 */
function Y2F( $amount ){
    return $amount * 100;
}

/**
 * 分转元
 */
function F2Y( $amount ){
    return $amount / 100;
}