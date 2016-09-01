<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;



/**
 * Site controller
 */
class IndexController extends CommonController
{
    public $layout=false;
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        return $this -> render('index.html');
    }

    /**
     * 根据id查询二级分类
     * @param string $pid
     * @return mixed
     */
    function actionType( $pid = '' )
    {
        if ( !preg_match('/^[0-9]{1,}$/', $pid) ) {
            $arr = $this->databasesSelect('fanli_classify', '*', "pid=$pid" );
        } else {
            $arr =[
                'status' => 1,
                'msg'    => 'pid参数错误!',
                'data'   => array()
            ];
       }

        return $arr;
    }
     /**
     * 根据品牌获取所有商品
     * @param string $bra_id
     * @return array|mixed
     */
    function actionBrashop( $bra_id = '' )
    {
        if ( !preg_match('/^[0-9]{1,}$/' , $bra_id ) )
        {
            $arr = $this->databasesSelect('fanli_goods', '*', "bra_id=$bra_id" );
        } else {
            $arr =[
                'status' => 1,
                'msg'    => 'bra_id参数错误!',
                'data'   => array()
            ];
        }
        return $arr;
    }

}
