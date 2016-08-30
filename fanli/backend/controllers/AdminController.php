<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use yii\web\Request;

/*
 * @author:dongmengtao
 * @controller:管理员
 * @time:2016/08/27
 */


/**
 * Site controller
 */
class AdminController extends CommonController
{
    /**
     * 管理员管理
     * @var bool
     */
    public $layout=false;
    public $enableCsrfValidation = false;
    //管理员列表展示页面
    public function actionIndex()
    {
        return $this->render('index.html');
    }

    //管理员添加展示页面
    public function actionAdd()
    {
        return $this->render('add.html');
    }

    //管理员添加方法
    public function actionInfo(){
        //接收数据
        $request = Yii::$app-> request;
        $username = $request -> post('username','');
        $password = $request -> post('password','');

        $url = $this->apiUrl('Admin','index');
        $data = array('account' => $username,'password' => $password);

        //调用接口
        $api_url = $this->CurlPost($url,$data);
        if(!empty($api_url)){
            $this->redirect(array('/admin/index'));
        }else{
            echo "<script>alert('添加失败');location.href='index.php?r=admin/add'</script>";
        }
    }
}
