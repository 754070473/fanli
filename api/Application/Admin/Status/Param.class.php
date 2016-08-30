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
     * 查询
     */

    //表名不能为空
    const SELECT_TABLE_NAME_IS_NULL = 1;
    const SELECT_TABLE_NAME_IS_NULL_MSG = '表名不能为空';
    
    //查询字段不能为空
    const SELECT_FIELD_NAME_IS_NULL = 1;
    const SELECT_FIELD_NAME_IS_NULL_MSG = '查询字段不能为空';
    
    /**
     * 登陆
     */
    
    //用户名不能为空
    const LOGIN_USER_NAME_IS_NULL = 1;
    const LOGIN_USER_NAME_IS_NULL_MSG = '用户名不能为空';
    
    //密码不能为空
    const LOGIN_PASSWORD_IS_NULL = 1;
    const LOGIN_PASSWORD_IS_NULL_MSG = '密码不能为空';


    /**
    *  品牌
    */
    const BRAND_IS_NULL = 1;
    const BRAND_IS_NULL_MSG = '请填写完整的信息';
}