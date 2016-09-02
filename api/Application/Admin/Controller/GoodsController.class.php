<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
use Think\Controller;
use Admin\Status\Status;
use Admin\Status\Success;
class GoodsController extends CommonController
{
    /**
     *  商品品牌
     */
    public function sall(){
        $goods = M('brand');
        $arr = $goods->select();
  //    print_r($arr );//  return $arr ;
        $this ->success( $success_status = 0  , $success_msg = 'success' , $arr ,  $other_data = array());
    }
    public function Add(){
    //    $array = $this->Sall();

        $goods_photo   = IsNan( $this -> _data , 'arr');
        $goods = M('brand');
        $arr = $goods->add($goods_photo['arr']);
        $this ->success( $success_status = 0  , $success_msg = 'success' , $arr ,  $other_data = array());
    }
    /**
     * 商品添加
     */
    function shopadd(){
        $goods_photo   = IsNan( $this -> _data , 'goods_photo');
        $goods_name    = IsNan( $this -> _data , 'goods_name');
        $goods_price   = IsNan( $this -> _data , 'goods_price');
        $goods_stock   = IsNan( $this -> _data , 'goods_stock');
        $goods_rebate  = IsNan( $this -> _data , 'goods_rebate');
        $cla_id        = IsNan( $this -> _data , 'cla_id');
        $goods_date    = IsNan( $this -> _data , 'goods_date');
        $goods_url     = IsNan( $this -> _data , 'goods_url');
        $bra_id        = IsNan( $this -> _data , 'bra_id');
        $data['bra_id']   =   $bra_id   ;
        $data['goods_photo']   =   $goods_photo   ;
        $data['goods_name']    =   $goods_name   ;
        $data['goods_price']   =   $goods_price ;
        $data['goods_stock']   =   $goods_stock;
        $data['goods_rebate']  =   $goods_rebate ;
        $data['cla_id']        =   $cla_id ;
        $data['goods_date']    =   $goods_date ;
        $data['goods_url']     =   $goods_url ;

        $goods = M('goods');
        $bool =   $goods->add($data);
        if($bool){
            $this -> success(
                Success::SHOP_SUCCESS ,
                Success::SHOP_ADD ,
                $data
            );
        }else{
            $this ->errorMessage(
                $error_status = 1 ,
                $error_msg = 'Error' ,
                $error_data = $data   ,
                $other_data = array()
            );
        }
    }
    /**
     * 查询出所有分类
     */
    function type(){
        $goods = M('classify');
        $arr = $goods->select();
        $this ->success( $success_status = 0  , $success_msg = 'success' , $arr ,  $other_data = array());
    }
    /*
     * 商品列表查询
     */
    function shopSelect(){

        $page   = IsNan( $this -> _data , 'page');
        $num   = IsNan( $this -> _data , 'num');
        empty( $page )? $page = 1 : '';
        empty( $num )? $num = 10 : '';
        if( $page < 1 ) $page = 1;
        $User = M('goods');
        $list = $User
//            ->join('fanli_classify','fanli_classify.cla_id=fanli_goods.cla_id')
            ->page($page.','.$num)
            ->select();
        $this ->success( $success_status = 0  , $success_msg = 'success' , $list,  $other_data = array());
    }
    function brand(){
        $User = M('brand');
        $list = $User->select();
        $this ->success( $success_status = 0  , $success_msg = 'success' , $list,  $other_data = array());

    }
}