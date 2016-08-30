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
class LoginController extends CommonController
{
    /**
     * 登录页面
     * @var bool
     */
    public $layout=false;
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        return $this->render('login.html');
    }
    
    /**
     * 登录
     */
    public function actionInfo()
    {
        //接收数据
        $request = Yii::$app->request;
        $username = $request -> post( 'username' , '' );
        $password = $request -> post( 'password' , '' );

        $url = $this->apiUrl( 'Login' , 'index' );
        $data = array( 'account' => $username , 'password' => $password );
        //调用接口
        $arr_api = $this -> CurlPost( $url , $data );
        if( $arr_api['status'] == 0 )
        {
            $session = Yii::$app->session;
            $session->set('admin', $arr_api['data']);
            $this->redirect(array('/index/index'));
        }
        else
        {
            $msg = $arr_api['msg'];
            echo "<script>alert('$msg');location.href='index.php?r=login/index'</script>";
        }
    }

    /**
     * 退出
     */
    public function actionTakeout()
    {
        $session = Yii::$app->session;
        $session->destroy();
        $this->redirect(array('/login/index'));
    }
}
