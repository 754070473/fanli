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
        $table = array(
            [ 'table1' => 'fanli_goods', 'table2' => 'fanli_navigate' , 'join' => 'nav_id' ]
        );
        $arr = $this -> databasesSelect($table , 5);
        print_r($arr);die;
        $session = Yii::$app->session;
        $account = $session['admin']['account'];
        return $this->render('index.html' , array( 'account' => $account ));
    }
}
