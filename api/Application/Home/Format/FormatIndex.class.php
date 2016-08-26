<?php
namespace Home\Format;
/**
 * 格式化AccountController
 * Class FormatAccount
 * @auth LiKang
 * @date 2015-12-08
 */
class FormatIndex extends Format{

    /**
     * 格式化login
     */
    public function login3( $data ){
        $template = array(
            'id' => 'i',
            'user_name' => 's',
            'user_psd' => 's',
            'slat' => 'i',
            'status' => 'i',
            'create_time' => 's',
            'update_time' => 's',
        );
        $this -> format( $data ,$template );
        return $data;
    }
}