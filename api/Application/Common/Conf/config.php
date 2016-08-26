<?php
return array(
	//'配置项'=>'配置值'

    'SHOW_PAGE_TRACE' => true ,

    #数据库配置
    'DB_DEPLOY_TYPE'=> 0,            //是否启用分布式
    'DB_RW_SEPARATE'=> false,     //是否启用智能读写分离
    'DB_TYPE'   => 'mysqli',
    'DB_HOST'   => '127.0.0.1',
    'DB_NAME'   => 'test',
    'DB_USER'   => 'root',
    'DB_PWD'    => '123456',
    'DB_PORT'   => '3306',
    'DB_PREFIX' => '',
);