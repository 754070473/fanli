<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;



/**
 * Site controller
 */
class IndexController extends CommonController
{
    public $layout=false;
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        $classify = $this -> actionClassify();
        $data = array();
        $data['classify'] = $classify['data'];
        return $this -> render('index.html');
    }

    public function actionDetails()
    {
        return $this -> render('details.html');
    }
    public function actionClass()
    {
        return $this -> render('classify.html');
    }
    public function actionSeckill()
    {
        return $this -> render('seckill.html');
    }

    /**
     * 查询一级分类
     * @return mixed
     */
    public function actionClassify()
    {
        $table = 'fanli_classify';
        $arr = $this -> databasesSelect($table , 0 ,'pid = 0');
        return $arr;
    }




    /* 即将售罄 查询正在进行活动的商品中剩余库存低于总库存50%的商品（按库存百分比升序排列）
     */
    public function actionSellout()
    {
        $field = 'end_time,goods_stock,surplus_stock,goods_name,goods_url,goods_rebate';
        $table = [['table1' => 'fanli_activity' , 'table2' => 'fanli_goods' , 'join' => 
        'act_id']];
        $where = "(surplus_stock / goods_stock) < 0.5";
        $order = '(surplus_stock / goods_stock),goods_id';
        $result = $this->databasesSelect( $table , $num = 0 , $where, $field, $order);
        return $result;
    }

	  //根据分类id查询该分类下所有正在进行促销活动的品牌
    public function actionClass_activity($cla_id = 1)
    {
        $table = array(['table1' => 'fanli_goods', 'table2' => 'fanli_classify', 'join' => 'cla_id'], ['table1' => 'fanli_goods', 'table2' => 'fanli_activity', 'join' => 'act_id']);
        $arr = $this->databasesSelect($table, 0, 'fanli_classify.cla_id = ' . $cla_id);
        // print_r($arr);die;
        return $arr;
    }

  //限量秒杀
    public function actionSeckil(){
        $table1 = "fanli_acttype";
        $table2 = "fanli_activity";
        $join = "type_id";
        $table = array( ['table1' => $table1 , 'table2' => $table2 ,  'join' => $join]);
        $arr = $this->databasesSelect($table , 0 , 'fanli_acttype.type_id = 1');
        return $arr;
    }

    //精选活动
    public function actionChoiceness(){
        $table1 = "fanli_acttype";
        $table2 = "fanli_activity";
        $join = "type_id";
        $table = array( ['table1' => $table1 , 'table2' => $table2 ,  'join' => $join]);
        $arr = $this->databasesSelect($table , 0 , 'fanli_acttype.type_id = 2');
        return $arr;
    }

 /**
     * 根据id查询二级分类
     * @param string $pid
     * @return mixed
     */
    function actionType( $pid = '' )
    {
        if ( !preg_match('/^[0-9]{1,}$/', $pid) ) {
            $arr = $this->databasesSelect('fanli_classify', '*', "pid=$pid" );
        } else {
            $arr =[
                'status' => 1,
                'msg'    => 'pid参数错误!',
                'data'   => array()
            ];
       }

        return $arr;
    }
     /**
     * 根据品牌获取所有商品
     * @param string $bra_id
     * @return array|mixed
     */
    function actionBrashop( $bra_id = '' )
    {
        if ( !preg_match('/^[0-9]{1,}$/' , $bra_id ) )
        {
            $arr = $this->databasesSelect('fanli_goods', '*', "bra_id=$bra_id" );
        } else {
            $arr =[
                'status' => 1,
                'msg'    => 'bra_id参数错误!',
                'data'   => array()
            ];
        }
        return $arr;
    }
	    /*
     *  查询正在进行中的品牌活动
     */
    public function actionBrand(){
        $table = 'fanli_activity';
        $now_date = date("Y-m-d H:i:s",time());
        $where = 'type_id=3';
        $list = $this -> databasesSelect($table ,0,$where );
        $arr = array();
        foreach( $list['data'] as $key => $val )
        {
            if( $val['start_time'] < $now_date && $val['end_time'] > $now_date )
            {
                $arr[] = $val;
            }
        }
        return $arr;
    }
    /*
     *  根据分类ID查询当天的秒杀活动
     */
    public function actionKill(){
        $table = 'fanli_activity';
        $now_date = date("Y-m-d H:i:s",time());
        $where = 'type_id=1';
        $list = $this -> databasesSelect($table ,0,$where );
        //获取当天的年份
        $y = date("Y");
        //获取当天的月份
        $m = date("m");
        //获取当天的号数
        $d = date("d");
        //将今天开始的年月日时分秒，转换成unix时间戳(开始示例：2015-10-12 00:00:00)
        $tobegintime = date('Y-m-d H:i:s',mktime(0,0,0,$m,$d,$y));
        $todayendtime = date('Y-m-d H:i:s',mktime(23,59,59,$m,$d,$y));
        $arr = array();
        foreach( $list['data'] as $key => $val )
        {
            if( $val['start_time'] > $tobegintime && $val['end_time'] < $todayendtime )
            {
                $arr[] = $val;
            }
        }
        return $arr;
    }

}

