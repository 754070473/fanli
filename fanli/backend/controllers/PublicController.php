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
class PublicController extends CommonController
{
    public $layout=false;
    public function actionIndex()
    {
        return $this->render('index/index.html');
    }
}
