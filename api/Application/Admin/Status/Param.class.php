<?php
namespace Admin\Status;
/**
 * 缺少参数的提示
 */
class Param {

    //用户不能为空
    const PARAM_MISS = 1;
    const PARAM_MISS_MSG = '缺少参数:%s!';

    //用户不能为空
    const PARAM_ERROR = 1;
    const PARAM_ERROR_MSG = '参数错误:%s!';
    
    /**
     * 登陆
     */
    
    //用户名不能为空
    const LOGIN_USER_NAME_IS_NULL = 1;
    const LOGIN_USER_NAME_IS_NULL_MSG = '用户名不能为空';
    
    //密码不能为空
    const LOGIN_PASSWORD_IS_NULL = 1;
    const LOGIN_PASSWORD_IS_NULL_MSG = '密码不能为空';



    //管理员添加用户名不能为空
    const  ADMIN_NAME_IS_NULL = 1;
    const  ADMIN_NAME_IS_NULL_MSG = '管理员用户名不能为空';

    //管理员添加密码不能为空
    const ADMIN_PASSWORD_IS_NULL = 1;
    const ADMIN_PASSWORD_IS_NULL_MSG = '管理员密码不能为空';

}