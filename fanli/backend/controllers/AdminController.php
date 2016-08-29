<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;


/*
 * @author:dongmengtao
 * @controller:管理员
 * @time:2016/08/27
 */




/**
 * Site controller
 */
class AdminController extends Controller
{
    /**
     * 管理员管理
     * @var bool
     */
    public $layout=false;
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        return $this->render('index.html');
    }

    public function actionAdd()
    {
        return $this->render('add.html');
    }
}
