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
        $data = array();
        //所有一级分类
        $classify = $this -> actionClassify();
        if( $classify['status'] == 0 )
        {
            $data['classify'] = $classify['data'];
        }
        else
        {
            $data['classify'] = array();
        }

        //限量秒杀活动
        $seckill = $this -> actionSeckil();
        if( $seckill['status'] == 0 )
        {
            $data['seckill'] = $seckill['data'];
        }
        else
        {
            $data['seckill'] = array();
        }
        $kill = $this -> actionKill();
        if( !empty( $kill ) )
        {
            $end_time = $this->timediff( time() , strtotime( $kill[0]['end_time'] ) );
            $data['kill'] = $kill;
            $data['kill_end_time'] = $end_time;
        }
        else
        {
            $data['kill'] = array();
            $data['kill_end_time'] = array( 'day'=>0,'hour'=>00,'min'=>00,'sec'=>00 );
        }

        //精选活动
        $choiceness = $this -> actionChoiceness();
        if( $choiceness['status'] == 0 )
        {
            $data['choiceness'] = $choiceness['data'];
        }
        else
        {
            $data['choiceness'] = array();
        }

        //品牌活动
        $brand = $this -> actionBrand();
        if( !empty($brand) )
        {
            $data['brand'] = $brand;
        }
        else
        {
            $data['brand'] = array();
        }

        //即将售罄
        $sellout = $this -> actionSellout();
        if( !empty( $sellout ) )
        {
            $data['sellout'] = $sellout;
        }
        else
        {
            $data['sellout'] = array();
        }

        //即将上线
        $online = $this -> actionOnline();
        if( !empty( $online ) )
        {
            $date1 = date( 'Y-m-d' , time()+60*60*24 );
            $date2 = date( 'Y-m-d' , time()+60*60*24*2 );
            $weekarray=array("日","一","二","三","四","五","六");
            $week1 = "星期".$weekarray[date("w", time()+60*60*24 )];
            $week2 = "星期".$weekarray[date("w", time()+60*60*24*2 )];
            foreach( $online as $key => $val )
            {
                if( date("Y-m-d",strtotime( $val['start_time'] ) ) == $date1 )
                {
                    $data['online'][$date1.'/'.$week1][] = $val;
                }
                else
                {
                    $data['online'][$date2.'/'.$week2][] = $val;
                }
            }
        }
        else
        {
            $data['online'] = array();
        }
        // print_r($data);die;
        return $this -> render('index.html' , $data);
    }

    public function actionDetails()
    {
        $common = yii::$app->request;
        $data['act_id'] = $common->get('act_id')?:"";
        $data['bra_id'] = $common->get('bra_id')?:"";
        $data['goods_id'] = $common->get('goods_id')?:"";
        // echo $data['bra_id'];die;
        if($data['act_id'] != ""){
            $result = $this->actionActget($data['act_id']);
        }else if($data['bra_id']  != ""){
            $result = $this->actionBrashop($data['bra_id']);
        }else if($data['goods_id'] != ""){
            $result = $this->actionGoodsget($data['goods_id']);
        }
        if($result['status'] == 0){
            return $this -> render('details.html',['result'=>$result['data']]);
        }else{
            echo "<script>alert('请刷新页面');window.history.go(-1)</script>";
        }
    }
    /**
     * 根据act_id活动id返回商品
     * @param  string $act_id [活动id]
     * @return array $arr [返回数据]
     */
    public function actionActget($act_id = '')
    {
        if ( preg_match('/^[0-9]{1,}$/', $act_id) ) {
            $arr = $this->databasesSelect('fanli_goods', '*', "act_id=$act_id" );
        } else {
            $arr =[
                'status' => 1,
                'msg'    => 'act_id参数错误!',
                'data'   => array()
            ];
       }
        return $arr;
    }
    /**
     * 根据商品id获取该品牌下的商品
     * @param  string $goods_id [description]
     * @return array $arr           [description]
     */
    public function actionGoodsget($goods_id='')
    {
        if ( preg_match('/^[0-9]{1,}$/', $goods_id) ) {
            $goods_info = $this->databasesSelect('fanli_goods', 0,"goods_id=$goods_id" , 'bra_id');
            $bra_id = $goods_info['data'][0]['bra_id'];
            $arr = $this->databasesSelect('fanli_goods', 0,"bra_id=$bra_id");
        } else {
            $arr =[
                'status' => 1,
                'msg'    => 'goods_id参数错误!',
                'data'   => array()
            ];
       }
        return $arr;
    }
    public function actionClass()
    {
        $cla_id  = Yii::$app->request->get('cla_id');
        $arr = $this->actionClass_activity($cla_id);
        if(empty($arr['data'])){
            echo "<script>alert('客官稍等......');history.go(-1)</script>";
        }
//        print_r($arr);die;
        return $this -> render('classify.html',['arr'=>$arr]);
    }
    public function actionSeckill()
    {
        $kill = $this -> actionKill();
        if( !empty( $kill ) )
        {
            $end_time = $this->timediff( time() , strtotime( $kill[0]['end_time'] ) );
            $data['kill'] = $kill;
            $data['kill_end_time'] = $end_time;
        }
        else
        {
            $data['kill'] = array();
            $data['kill_end_time'] = array( 'day'=>0,'hour'=>00,'min'=>00,'sec'=>00 );
        }
        return $this -> render('seckill.html' , $data);
    }

    /**
     * 查询一级分类
     * @return mixed
     */
    public function actionClassify()
    {
        $table = 'fanli_classify';
        $arr = $this -> databasesSelect($table , 0 ,'pid = 0');
        return $arr;
    }




    /* 即将售罄 查询正在进行活动的商品中剩余库存低于总库存50%的商品（按库存百分比升序排列）
     */
    public function actionSellout()
    {
        $field = '*';
        $table = [['table1' => 'fanli_activity' , 'table2' => 'fanli_goods' , 'join' => 
        'act_id']];
        $now_time = date( 'Y-m-d H:i:s' ,time() );
        $order = '(surplus_stock / goods_stock)';
        $result = $this->databasesSelect( $table , $num = 0 , 1, $field, $order);
        $arr = array();
        foreach( $result['data'] as $key => $val )
        {
            if( ($val['surplus_stock'] / $val['goods_stock'] ) < 0.5 )
            {
                if( ( $val['start_time'] < $now_time && $val['end_time'] > $now_time ) )
                {
                    $val[ 'goods_rebate' ] = $val[ 'goods_price' ] * ( $val[ 'goods_rebate' ] / 100 );
                    $end_time = $this -> timediff( strtotime( $now_time ) , strtotime( $val['end_time'] ) );
                    $val['end_time'] = $end_time;
                    $arr[] = $val;
                }
            }
        }
        return $arr;
    }

	  //根据分类id查询该分类下所有正在进行促销活动的品牌
    public function actionClass_activity($cla_id = 1)
    {
        $table = array(['table1' => 'fanli_goods', 'table2' => 'fanli_classify', 'join' => 'cla_id'], ['table1' => 'fanli_goods', 'table2' => 'fanli_activity', 'join' => 'act_id']);
        $arr = $this->databasesSelect($table, 0, 'fanli_classify.cla_id = ' . $cla_id);
        // print_r($arr);die;
        return $arr;
    }

  //限量秒杀
    public function actionSeckil(){
        $table1 = "fanli_acttype";
        $table2 = "fanli_activity";
        $join = "type_id";
        $table = array( ['table1' => $table1 , 'table2' => $table2 ,  'join' => $join]);
        $arr = $this->databasesSelect($table , 0 , 'fanli_acttype.type_id = 1');
        return $arr;
    }

    //精选活动
    public function actionChoiceness(){
        $table1 = "fanli_acttype";
        $table2 = "fanli_activity";
        $join = "type_id";
        $table = array( ['table1' => $table1 , 'table2' => $table2 ,  'join' => $join]);
        $arr = $this->databasesSelect($table , 0 , 'fanli_acttype.type_id = 2');
        return $arr;
    }

 /**
     * 根据id查询二级分类
     * @param string $pid
     * @return mixed
     */
    function actionType( $pid = '' )
    {
        if ( preg_match('/^[0-9]{1,}$/', $pid) ) {
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
        // echo $bra_id;
        if ( preg_match('/^[0-9]{1,}$/' , $bra_id ) )
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
	    /*
     *  查询正在进行中的品牌活动
     */
    public function actionBrand(){
        $table = 'fanli_activity';
        $now_date = date("Y-m-d H:i:s",time());
        $where = 'type_id=3';
        $list = $this -> databasesSelect($table ,0,$where );
        $arr = array();
        $i = 0;
        foreach( $list['data'] as $key => $val )
        {
            if( $val['start_time'] <= $now_date && $val['end_time'] >= $now_date )
            {
                $arr[] = $val;
                if( $i < 9 )
                {
                    $i ++;
                }
                else
                {
                    break;
                }
            }
        }
        return $arr;
    }
    /*
     *  查询当天的秒杀活动
     */
    public function actionKill(){
        $table = array( [ 'table1' => 'fanli_activity' , 'table2' => 'fanli_goods' , 'join' => 'act_id' ] );
        $now_date = date("Y-m-d H:i:s",time());
        $where = 'type_id=1';
        $list = $this -> databasesSelect($table ,0,$where );
        //获取当天的年份
        $y = date("Y");
        //获取当天的月份
        $m = date("m");
        //获取当天的号数
        $d = date("d");
        //将今天开始的年月日时分秒，转换成unix时间戳(开始示例：2015-10-12 00:00:00)
        $tobegintime = date('Y-m-d H:i:s',mktime(0,0,0,$m,$d,$y));
        $todayendtime = date('Y-m-d H:i:s',mktime(23,59,59,$m,$d,$y));
        $arr = array();
        foreach( $list['data'] as $key => $val )
        {
            if(
                ( $val['start_time'] > $tobegintime && $val['start_time'] < $todayendtime  ) ||
                ( $val['end_time'] > $tobegintime && $val['end_time'] < $todayendtime ) ||
                ( $val['start_time'] < $tobegintime && $val['end_time'] > $todayendtime )
            )
            {
                if( $val['start_time'] < date( 'Y-m-d H:i:s' , time() ) && $val['end_time'] > date( 'Y-m-d H:i:s' , time() ) ){
                    $val[ 'goods_rebate' ] = $val[ 'goods_price' ] * ( $val[ 'goods_rebate' ] / 100 );
                    $arr[] = $val;
                }
            }
        }

        return $arr;
    }

    /**
     * 即将上线 查询所有未来两天开始的品牌活动（按开始时间查询）
     */
    public function actionOnline()
    {
        $table = 'fanli_activity';
        $where = "type_id=3";
        $result = $this->databasesSelect( $table , $num = 0 , $where);
        $arr = array();
        foreach($result['data'] as $key => $val)
        {
            if( $val['start_time'] > date('Y-m-d 00:00:00',strtotime('+1day')) && $val['start_time'] < date('Y-m-d 23:59:59',strtotime('+2day')) )
            {
                $arr[] = $val;
            }
        }
        return $arr;
    }

    public function actionReduce()
    {
        $goods_id  = Yii::$app->request->get('goods_id');
        $url = $this -> apiUrl( 'Public' ,'reduce' );
        $data = array( 'goods_id' => $goods_id );
        $arr = $this -> CurlPost( $url , $data );
        if( is_array( $arr ) )
        {
            if( $arr['status'] == 0 )
            {
                echo $arr['data'];
            }
            else
            {
                echo 0;
            }
        }
        else
        {
            echo 0;
        }
    }

    //功能：计算两个时间戳之间相差的日时分秒
    //$begin_time 开始时间戳
    //$end_time 结束时间戳
    function timediff($begin_time,$end_time)
    {
        if($begin_time < $end_time){
            $starttime = $begin_time;
            $endtime = $end_time;
        }else{
            $starttime = $end_time;
            $endtime = $begin_time;
        }

        //计算天数
        $timediff = $endtime-$starttime;
        $days = intval($timediff/86400);
        //计算小时数
        $remain = $timediff%86400;
        $hours = intval($remain/3600);
        //计算分钟数
        $remain = $remain%3600;
        $mins = intval($remain/60);
        //计算秒数
        $secs = $remain%60;
        $res = array("day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs);
        return $res;
    }
}

