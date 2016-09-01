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

    //根据分类id查询该分类下所有正在进行促销活动的品牌
    public function actionClass_activity($cla_id = 1)
    {
    	$table = array(['table1'=>'fanli_goods','table2'=>'fanli_classify','join'=>'cla_id'],['table1'=>'fanli_goods','table2'=>'fanli_activity','join'=>'act_id']);
    	$arr = $this -> databasesSelect($table , 0 ,'fanli_classify.cla_id = '.$cla_id);
    	 // print_r($arr);die;
    	return $arr;
    }

}
