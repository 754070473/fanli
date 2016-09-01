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
}
