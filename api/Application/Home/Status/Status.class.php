<?php
namespace Home\Status;
/**
 * 错误时候的提示
 */
class Status {

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
    
    //没有此商品
    const SELECT_GOODS_ERROR = 1;
    const SELECT_GOODS_ERROR_MSG = '没有此商品!';

    //没有此商品
    const SELECT_GOODS_SELL_OUT = 1;
    const SELECT_GOODS_SELL_OUT_MSG = '商品已售完!';

}