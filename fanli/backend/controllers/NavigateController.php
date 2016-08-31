<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use yii\web\Request;

/**
 * Site controller
 */
class NavigateController extends CommonController
{
    /**
     * 导航管理
     * @var bool
     */
    public $layout=false;
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        $info = $this->classify('fanli_navigate','nav_pid');
        return $this->render('index.html',['list'=>$info]);
    }
    /*
     * 导航添加
     */
    public function actionAdd()
    {
        if( Yii::$app->request->ispost ){
            $data = Yii::$app->request->post();
            if($data['nav_id']==0){
                $data['nav_pid'] = 0;
            }else{
                $data['nav_pid'] = $data['nav_id'];
            }
            $data['nav_date'] = date("Y-m-d H:i:s",time());
            $file = $_FILES['myfile'];
            move_uploaded_file($_FILES["myfile"]["tmp_name"],"upload/" . $_FILES["myfile"]["name"]);
            if(empty($_FILES['myfile']['name'])){
                $data['nav_photo'] = '';
            }else{
                $data['nav_photo'] = "upload/" . $_FILES["myfile"]["name"];
            }
            $url = $this->apiUrl( 'Navigate' , 'insert' );
            //调用接口
            $arr_api = $this -> CurlPost( $url , $data );
			
            if($arr_api['status']==1){
                echo $arr_api['msg'];die;
            }else if($arr_api['status']==0){
                $this->redirect('?r=navigate/index');
            }
        }else{
            $connection = \Yii::$app->db;
            $command = $connection->createCommand('SELECT * FROM fanli_navigate where nav_pid=0');
            $posts = $command->queryAll();
            return $this->render('add.html',['list'=>$posts]);
        }
    }
    /*
     * 递归查询无限极
     */
    public function classify($table,$pid_name,$pid=0){
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('SELECT * FROM '.$table.' where '.$pid_name.'='.$pid.'');
        $arr = $command->queryAll();
        //查询子分类
        foreach($arr as $key=>$val)
        {
            $arr[$key]['son']=$this->classify( $table , $pid_name , $pid = $val['nav_id'] );
        }
        return $arr;
    }
    /*
     *  删除导航
     */
    public function actionDel(){
        $id = Yii::$app->request->get('nav_id');
        $data['nav_id'] = $id;
        $url = $this->apiUrl( 'Navigate' , 'delete' );
        $arr_api = $this -> CurlPost( $url , $data );
        print_r($arr_api['status']);
    }
    public function actionDele(){
        //导航ID
        $data['id'] = Yii::$app->request->get('id');
        //nav_status值
        $data['status'] = Yii::$app->request->get('status');
        $url = $this->apiUrl( 'Navigate' , 'update' );
        $arr_api = $this -> CurlPost( $url , $data );
        print_r($arr_api['status']);
    }
}
