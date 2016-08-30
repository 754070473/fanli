<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
use Think\Controller;
use Admin\Status\Status;
use Admin\Status\Success;
class GoodsController extends CommonController
{
    /**
     *  商品分类
     */
    public function actionAdd(){
        $content = file_get_contents('http://m.api.fanli.com/app/v3/sf/cats.htm?src=2&v=5.2.0.34&abtest=17454_a-32579_b-72_c-138_b-112_b-6_b-9874_a-2_a-326b');
        $content2= json_decode($content,true);
        $arr =  $content2['data']['cats'];
        $shop_new=[];
        foreach($arr as $k=>$v){
            $shop_new[$k]['cla_id'] =$v['id'];
            $shop_new[$k]['cla_name'] =$v['name'];
            $shop_new[$k]['cla_order'] =$v['sort'];
            $shop_new[$k]['pid'] =$v['parentId'];
            $shop_new[$k]['cla_date'] =time();
            $shop_new[$k]['cla_status'] =1;
            if(empty($v['sort'])){
                $shop_new[$k]['cla_id'] =$v['id'];
                $shop_new[$k]['cla_name'] =$v['name'];
                $shop_new[$k]['cla_order'] =1;
                $shop_new[$k]['pid'] =0;
                $shop_new[$k]['cla_date'] =time();
                $shop_new[$k]['cla_status'] =1;
            }else{
                $shop_new[$k]['cla_id'] =$v['id'];
                $shop_new[$k]['cla_name'] =$v['name'];
                $shop_new[$k]['cla_order'] =$v['sort'];
                $shop_new[$k]['pid'] =$v['parentId'];
                $shop_new[$k]['cla_date'] =time();
                $shop_new[$k]['cla_status'] =1;
            }
        }
        $goods = M('fanli_classify');
        $goods->add($shop_new);
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
        $fanli_goods   = IsNan( $this -> _data , 'goods_date');
        $goods_url   = IsNan( $this -> _data , 'goods_url');

        $data['goods_photo']   =   $goods_photo   ;
        $data['goods_name']    =   $goods_name   ;
        $data['goods_price']   =   $goods_price ;
        $data['goods_stock']   =   $goods_stock;
        $data['goods_rebate']  =   $goods_rebate ;
        $data['cla_id']        =   $cla_id ;
        $data['goods_date']    =   $fanli_goods ;
        $data['goods_url']    =   $goods_url ;
        $goods = M('fanli_goods');
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
        $goods = M('fanli_classify');
        $arr = $goods->select();
        $this ->success( $success_status = 0  , $success_msg = 'success' , $arr ,  $other_data = array());
    }
}