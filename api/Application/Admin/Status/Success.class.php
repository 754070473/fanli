<?php
namespace Admin\Status;
/**
 * 成功时候的提示
 */
class Success
{

    //登录成功
    const LOGIN_SUCCESS = 0;
    const LOGIN_SUCCESS_MSG = '登录成功!';

    //查询成功
    const SELECT_SUCCESS = 0;
    const SELECT_SUCCESS_MSG = '查询成功!';
    //商品添加
    const SHOP_SUCCESS = 0;
    const SHOP_ADD = '成功';

    //管理员添加
    const ADMIN_ADD = 0;
    const ADMIN_ADD_MSG = '添加成功';

    //品牌添加成功
    const BRAND_ADD_SUCCESS = 0;
    const BRAND_ADD_SUCCESS_MSG = '品牌添加成功!';

    
    //导航添加成功
    const NAV_ADD_SUCCESS = 0;
    const NAV_ADD_SUCCESS_MSG = '导航添加成功!';

 //品牌禁用或者启用
    const BRAND_LOCK_SUCCESS = 0;
    const BRAND_LOCK_SUCCESS_MSG = '启用或禁用成功';



    //品牌删除成功
    const BRAND_DEL_SUCCESS = 0;
    const BRAND_DEL_SUCCESS_MSG = '品牌删除成功';



}