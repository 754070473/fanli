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

    //品牌添加数据
    public function actionBrand_add()
    {
        $request = \Yii::$app->request;
        $brand = $request->post();
        //实例化上传类
        $upload = new UploadedFile();
        $name=$upload->getInstanceByName('brand_log'); //获取文件原名称
        $img=$_FILES['brand_log']; //获取上传文件参数
        // print_r($img);
        $upload->tempName=$img['tmp_name']; //设置上传的文件的临时名称
        $img_path='uploads/'.$name; //设置上传文件的路径名称(这里的数据进行入库)
        $arr=$upload->saveAs($img_path); //保存文件

        $data['bra_name'] = $brand['brand'];   //品牌名称
        $data['bra_logo'] = $img_path;       //品牌logo
        $data['bra_connect'] = $brand['connect'];       //品牌简介
        $data['bra_remark'] = $brand['remark'];   //品牌备注
        $data['bra_status'] = $brand['status'];   //状态
        // print_r($data);die;
        $url = $this->apiUrl( 'Brand' , 'index' );
        // print_r($url);die;
        //调用接口
        $arr_api = $this -> CurlPost( $url , $data );
        // var_dump($arr_api);die;
        if ($arr_api['status']==0) 
        {
           echo 1;
        }
        else
        {
            $msg = $arr_api['msg'];
            echo "<script>alert('$msg');location.href='index.php?r=brand/add'</script>";
        }

    }


}
