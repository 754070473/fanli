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
        print_r($classify);die;
        return $this -> render('index.html');
    }

    public function actionDetails()
    {
        return $this -> render('details.html');
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
    public function actionSeckill(){
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
}
