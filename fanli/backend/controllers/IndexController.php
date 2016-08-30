<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class IndexController extends CommonController
{
    /**
     * 后台主页
     * @var bool
     */
    public $layout=false;
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        // $url = $this -> apiUrl('Index','index');
        // $data = array('name' => '张三','id' => 1);
        // print_r($this -> CurlPost($url , $data));
        return $this->render('index.html');
    }
}
