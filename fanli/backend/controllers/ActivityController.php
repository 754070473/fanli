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
class ActivityController extends CommonController
{
    public $rule = [
        'act_name' => [
            'name' => '活动名称',
            'type' => 'ZN',
            'msg' => '权限名称只能为2-10位汉字',
            'is_html' =>'1',
        ],
        'type_id' => [
            'name' => '活动类型',
            'type' => 'I',
            'msg' => '活动类型不存在',
            'is_html' =>'0',
        ],
        'start_time' => [
            'name' => '开始时间',
            'type' => 'DATE',
            'msg' => '时间格式不正确,例如2016-08-31或2016/08/31',
            'is_html' =>'0',
            'compare' =>[
                'value1' => 'start_time',
                'value2' => 'end_time',
                'type' => '>',
                'msg' =>'结束时间必须大于开始时间',
            ],
        ],
        'end_time' => [
            'name' => '结束时间',
            'type' => 'DATE',
            'msg' => '时间格式不正确,例如2016-08-31或2016/08/31',
            'is_html' =>'0',
        ],
        'act_order' => [
            'name' => '排序',
            'type' => 'I',
            'msg' => '排序只能是数字 ',
            'is_html' =>'0',
        ],
        'act_desc' => [
            'name' => '活动介绍',
            'is_html' =>'1',
        ]

    ];
    /**
     * 活动管理
     * @var bool
     */
    public $layout=false;
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        $field = 'fanli_acttype.type_id,type_name,act_id,act_name,start_time,end_time,act_date,act_order,act_desc';
        $table = [['table1' => 'fanli_activity' , 'table2' => 'fanli_acttype' , 'join' => 
        'type_id']];
        $p = Yii::$app->request->post('p')?:1;
        $order = 'act_id';
        $result = $this->databasesSelect( $table , $num = 0 , $where = 1 , $field, $order,$p);
        return $this->render('index.html',['result'=>$result['data']]);
    }
    /**
     * 活动添加
     */
    public function actionAdd()
    {
        if(Yii::$app->request->post()){//活动添加
            // 接收数据
            $post = Yii::$app->request->post();
            $error = $this->checkParam($post,$this->rule);
            if(isset($error['mark'])){
                $this->msg($error['msg'],'?r=activity/add');
                die();
            }
            $post['act_date'] = date('Y-m-d',time());
            // 获取路由
            $url = $this->apiUrl('Activity','add');
            // 发送数据
            $result = $this->CurlPost($url,$post);
            if($result['status'] == 0){
                $res = $this->msg($result['msg'],'?r=activity/index');
            }else{
                $this->msg($result['msg'],-1);
            }
        }else{//活动添加页面
            //获取活动分类信息
            $field = 'type_id,type_name';
            $table = 'fanli_acttype';
            // print_r($table);die;
            $result = $this->databasesSelect( $table , $num = 0 , $where = 1 , $field, $order = 1 );
            return $this->render('add.html',['result'=>$result['data']]);
        }
    }
    /**
     * 活动修改
     */
    public function actionEdit()
    {
        return $this->render('edit.html');
    }
    /**
     * 提示信息
     */
    public function Msg($msg,$url)
    {
        $str = "<script>alert('%s');%s</script>";
        if(is_int($url)){
            $url = "window.history.go(".$url.")";
        }else{
            $url = "location.href='".$url."'";
        }
        echo  sprintf($str,$msg,$url);
    }
    public function checkParam($data,$rule)
    {
        $error=array();
        //遍历判断是否传递必须参数
        foreach ($rule as $key => $value) {
            // 判断数据是否为空
                if ($data[$key]=='') {
                    $error = array('mark' => 1 , 'msg' => $value['name']."不能为空");
                    break;
                }else{
                    if(isset($value['type'])){
                        // 判断数据类型是否正确
                        $ze=$this->checkZe($data[$key],$value['type']);
                        if($ze==0){
                            $error = array('mark' => 2 , 'msg' => $value['msg']);
                            break;
                        }
                    }
                }
        }
        return $error;
    }
    public function checkZe($str,$t)
    {
        $result = (int)0;
        switch (strtoupper($t)){
            case 'ZN' : $result=preg_match('/^[\x{4e00}-\x{9fa5}]{2,10}$/u',$str)?1:0;break;
            case 'DATE' : $result=strtotime($str)?1:0;break;
            case 'I' : $result=preg_match('/^[0-9]{1,}$/u',$str)?1:0;break;
        }
        return $result;
    }

}
