<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

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

    //品牌添加页面
    public function actionAdd()
    {
        return $this->render('add.html');
    }

    //添加数据
    public function actionBrand_add()
    {
        $request = \Yii::$app->request;
        $arr = $request->post();
        //实例化上传类
        $upload = new UploadedFile();
        $name=$upload->getInstanceByName('brand_log'); //获取文件原名称
        $img=$_FILES['brand_log']; //获取上传文件参数
        // print_r($img);
        $upload->tempName=$img['tmp_name']; //设置上传的文件的临时名称
        $img_path='uploads/'.$name; //设置上传文件的路径名称(这里的数据进行入库)
        $arr=$upload->saveAs($img_path); //保存文件
        $brand = $arr['brand'];  //品牌名称

    }
}
