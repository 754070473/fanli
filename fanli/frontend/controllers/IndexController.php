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
