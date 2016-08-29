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
class BrandController extends CommonController
{
    /**
     * 品牌管理
     * @var bool
     */
    public $layout=false;
    public $enableCsrfValidation = false;
    //品牌列表
    public function actionIndex()
    {
        return $this->render('index.html');
    }

    //品牌添加
    public function actionAdd()
    {
        return $this->render('add.html');
    }

}
