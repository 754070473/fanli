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
header("content-type:text/html;charset=utf-8");
class CommonController extends Controller
{
    /**
     * 防非法登录
     */
    public function init()
    {
        $url = \Yii::$app->requestedRoute;
        $controller = substr( $url , 0 , strpos( $url , '/' ) );
        if( !in_array( $controller , array('login') ) )
        {
            $session = Yii::$app->session;
            if (!isset($session['admin']))
            {
                echo "<script>alert('请先登录!');location.href='index.php?r=login/index'</script>";
                exit;
            }
        }
    }
    /**
     * 引入function.php
     * @return \CommonFunction
     */
    public function commonFunction()
    {
        require_once (__DIR__.'/../../common/common/function.php');
        return new \CommonFunction();
    }
    
    /**
     * 拼接接口地址
     * @param        $controller
     * @param        $function
     * @param string $module
     *
     * @return string
     */
    public function apiUrl( $controller , $function , $module = 'admin' )
    {
        return 'http://api.fanli.com/'.$module.'/'.$controller.'/'.$function;
    }

    /**
     * 请求接口
     * @param $url
     * @param array $data
     * @return mixed
     */
    protected function CurlPost( $url , $data = array() )
    {
        if( empty( $data ) )
        {
            $arr = $this -> commonFunction() -> CurlPost( $url );
        }
        else
        {
            if ( is_array( $data ) )
            {
                $sign = $this -> setSign( $data );
            }
            else
            {
                $data = array( 'val' => $data );
                $sign = $this -> setSign( $data );
            }

            //添加token
            $session = Yii::$app->session;
            if (isset($session['admin']))
            {
                $token = $session['admin']['token'];
                $data['token'] = $token;
            }

            $data = array_merge( $data , array( 'sign' => $sign ) );
            $arr = $this -> commonFunction() -> CurlPost( $url , $data );
        }
        return $arr;
    }

    private function setSign( $data )
    {
        $num = count( $data );

        // 对数组的值按key排序
        ksort($data);

        // 生成url的形式
        $params = http_build_query($data);

        // 拼接密钥 且 md5
        $signAll = md5($params);

        // 计算截取长度
        $len = $num%32+6;

        // 得到数据签名
        $sign = substr($signAll,0,$len);

        return $sign;
    }
    
    /**
     * @param $table
     * @param int $where
     * @param int $num
     * @param string $field
     * @param int $order
     * @param int $p
     * @return mixed
     */
    protected function databasesSelect( $table , $where = 1 , $num = 0 , $field = '*' , $order = 1 , $p = 1 )
    {
        if( is_array( $table ) )
        {
            $sql = '`' . $table[ 0 ][ 'table1' ] . '`';
            foreach ( $table as $key => $val )
            {
                $sql .= ' inner join `' . $val[ 'table2' ] . '` on ' . '`' . $val[ 'table1' ] . '`.`' . $val[ 'join' ] . '`=' . '`' . $val[ 'table2' ] . '`.`' . $val[ 'join' ] . '`';
            }
        }
        else
        {
            $sql = $table;
        }
        $url = $this -> apiUrl( 'Public' , 'index' );
        $data = array( 'table' => $sql , 'field' => $field , 'where' => $where , 'num' => $num , 'order' => $order , 'p' => $p );
        $api_data = $this -> CurlPost( $url , $data);
        if( empty( $api_data['status'] ) )
        {
            print_r($api_data);
        }
        else
        {
            if( $api_data['status'] == 0 )
            {
                if( $num > 1 )
                {
                    $data['num'] = 0;
                    $api_data_all = $this -> CurlPost( $url , $data );
                    if( empty( $api_data_all['status'] ) )
                    {
                        print_r($api_data_all);
                    }
                    else
                    {
                        if( $api_data_all['status'] == 0 )
                        {
                            $count = count( $api_data_all['data'] );
                            $page_count = ceil( $count / $num ) ;
                            $page = $this -> ajaxPage( $count , $url , $page_count , $p );
                            $api_data['data'] = array_merge( $api_data['data'] , $page );
                        }
                    }
                }
            }
            return $api_data;
        }
    }

    private function ajaxPage( $count , $url , $page , $p){
        if($count == 0){
            //搜索样式
            $sel='<div class="cfD" style="float: right;margin-top: 42px;">
                    <input class="addUser" type="text" id="search" value="" placeholder="请输入要搜索的内容" />
                    <input class="button" type="button" onclick="ckPage('."'$url'".',1)"  value="搜索"/>
                </div>';
            $str = '';
            $str.="<div class='pagin'><div class='message'>共<i class='blue'>$count</i>条记录<ul class='paginList'></div>";
            $data['page'] = $str;
            $data['sel'] = $sel;
            return $data;
        }else{
            //搜索样式
            $sel='<div class="cfD" style="float: right;margin-top: 42px;">
                    <input class="addUser" type="text" id="search" value="" placeholder="请输入要搜索的内容" />
                    <input class="button" type="button" onclick="ckPage('."'$url'".',1)"  value="搜索"/>
                </div>';
            //分页样式
            $str='<link rel="stylesheet" type="text/css" href="page/page.css"/>
            <script src="page/page.js"></script>
            ';
            $str.="<div class='pagin'><div class='message'>共<i class='blue'>$count</i>条记录，当前显示第<i class='blue'>$p</i>页</div><ul class='paginList'><li class='paginItem'><a href='javascript:;' onclick=ckPage('".$url."',1)><span class='pagepre'><<</span></a></li>";
            for($i=1;$i<=$page;$i++){
                if($i==$p){
                    $str.="<li class='paginItem current'><a href='javascript:;' onclick=ckPage('".$url."',$i)>$i</a></li>";
                }else{
                    if($i<$p-4){
                        $str.="<li class='paginItem more'><a href='javascript:;'>...</a></li>";
                        $i=$p-5;
                    }elseif($i>$p+4){
                        $str.="<li class='paginItem more'><a href='javascript:;'>...</a></li>";
                        $i=$i+3;
                    }else{
                        $str.="<li class='paginItem'><a href='javascript:;' onclick=ckPage('".$url."',$i)>$i</a></li>";
                    }
                }
            }
            $str.="<li class='paginItem'><a href='javascript:;' onclick=ckPage('".$url."',$page)><span class='pagenxt'>>></span></a></li></ul></div>";
            $data['page']=$str;
            $data['sel']=$sel;
            $data['pageNum']=$page;
            $data['count']=$count;
            $data['p']=$p;
            return $data;
        }
    }
}