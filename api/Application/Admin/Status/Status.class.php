<?php
namespace Admin\Status;
/**
 * 错误时候的提示
 */
class Status
{
    
    //用户被锁定
    const USER_IS_LOCKER = 1;
    const USER_IS_LOCKER_MSG = '账号被锁定，请稍后再试!';
    
    
    //用户没有找到
    const USER_NOT_FOUND = 1;
    const USER_NOT_FOUND_MSG = '账号没有找到!';
    
    //账号密码不匹配
    const USER_PASSWORD_ERROR = 1;
    const USER_PASSWORD_ERROR_MSG = '账号密码不匹配!';
    
    //未查询到数据
    const SELECT_DATA_ERROR = 1;
    const SELECT_DATA_ERROR_MSG = '未查询到数据!';


    //品牌名称已存在
    const BRAND_NAME = 1;
    const BRAND_NAME_MSG = '品牌已存在';

    //品牌名称不合法
    const BRAND_NAME_ERROR = 1;
    const BRAND_NAME_ERROR_MSG = '纯汉字或者纯英文';

    //品牌添加失败
    const BRAND_ADD_ERROR = 1;
    const BRAND_ADD_ERROR_MSG = '品牌添加失败';

    //品牌启用或禁用失败
    const BRAND_LOCK_ERROR = 1;
    const BRAND_LOCK_ERROR_MSG = '品牌启用或禁用失败';

    //品牌删除失败
    const BRAND_DEL_ERROR = 1;
    const BRAND_DEL_ERROR_MSG = '品牌删除失败';


    //管理员添加失败
    const ADMIN_NAME_ERROR = 1;
    const ADMIN_NAME_ERROR_MSG = '管理员添加失败';

     //导航名称不合法
    const NAV_NAME_ERROR = 1;
    const NAV_NAME_ERROR_MSG = '添加失败，请重试';
}