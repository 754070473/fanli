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
class GoodsController extends CommonController
{
    /**
     * 商品管理
     * @var bool
     */
    public $layout=false;
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        return $this->render('index.html');
    }
}