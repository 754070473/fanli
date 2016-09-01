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
        return $this -> render('index.html');
    }

    public function actionClassify()
    {
        $table = 'fanli_classify';
        $arr = $this -> databasesSelect($table , 0 ,'pid = 0');
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
