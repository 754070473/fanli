<?php


/**
 * 生成密码
 * @param $length
 * @return null|string
 */
function make_password( $password , $slat ,$is_register = 0 ){

    //生成密码
    $password = strtolower( $password );
    if( $is_register ){
        return  md5( md5( $password ) . $slat );
    }else{
        return  md5( $password . $slat );
    }
}