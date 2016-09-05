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
class GoodsController extends CommonController
{
    /**
     * 商品管理
     * @var bool
     */
    public $layout=false;
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        return $this->render('index');
    }
    /**
     * 商品添加表单
     * @return string
     */
    public function actionAdd()
    {
        //分类
        $url = $this -> apiUrl('Goods','type');
        $data = array('type' => 'return');
        $type = $this -> CurlPost($url , $data);
        //品牌
        $url = $this -> apiUrl('Goods','brand');
        $data = array('type' => 'return');
        $brand = $this -> CurlPost($url , $data);
        return $this->render('add.html',['type'=>$type['data'],'brand'=>$brand['data']]);
    }
    /**
     *  商品添加返回淘宝ajax数据
     */
    public function actionAddshop(){
        $data = Yii::$app->request->post();
        $data['goods_date']=date('Y-m-d',time());
        $url = $this -> apiUrl('Goods','shopadd');
        $data['goods_url'] = $this ->url($data['goods_url']);;
        $b= $this -> CurlPost($url , $data);
        if($b['status']=='0'){
            echo '<script>alert("添加成功");location.href="index.php?r=goods/add"</script>';
        }else{
            echo '<script>alert("错误");location.href="index.php?r=goods/add"</script>';
        }
    }
    function url($shopurl){
        if(preg_match('#taobao.com#isU',$shopurl)){
         return 'https://item.taobao.com/item.htm?id='.substr($shopurl,strrpos($shopurl,'=')+1);// strpos('i',$shopurl);//substr($shopurl,strrpos('id=',$shopurl));
        }
    }
    /**
     *  商品添加返回淘宝ajax数据
     */
    public function actionCurl(){
        $url = Yii::$app->request->post('url');
        if(!empty($url)){
            $arr = $this->caiji($url);
            echo json_encode($arr);
        }else{
            echo 'error';
        }

    }

    /**
     * 处理url
     * @param $str
     * @return mixed
     * %3F   ?
     * %3D   =
     * %26  &
     * %3A  /
     */
    function _str($str) {
        $str = str_replace('ifanli://m.51fanli.com/app/show/web?url=','',$str);
        $str = str_replace('%3A',':',$str);
        $str = str_replace('%2F','/',$str);
        $str = str_replace('%3F','?',$str);
        $str = str_replace('%3D','=',$str);
        $str = str_replace('%3F','?',$str);
        $str = str_replace('%26','&',$str);
        return $str;
    }
    function AndroidCurl($url){
        $ch = curl_init($url); //初始化
        curl_setopt($ch, CURLOPT_HEADER, 0); // 不返回header部分
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 返回字符串，而非直接输出
        curl_setopt($ch, CURLOPT_USERAGENT, "Dalvik/1.6.0 (Linux; U; Android 4.1.2; DROID RAZR HD Build/9.8.1Q-62_VQW_MR-2)");
        curl_setopt($ch, CURLOPT_REFERER, "-");
        $response = curl_exec($ch);
        curl_close($ch);
       return $response;
    }

    /**
     * 商品ajax查询处理采集数据
     * @param $url
     * @return mixed
     */
    function caiji($url){
       $co =  file_get_contents($url);
        if(preg_match('#taobao.com#isU',$url)){
           $co  =  iconv('GB2312','UTF-8',$co);
            $reg_tb = '#<div class="tb-item-info-r">(.*)<div class="tb-property tb-property-x">(.*)<div class="tb-wrap tb-wrap-newshop">(.*)<div id="J_Title" class="tb-title" shortcut-key="t" shortcut-label="(.*)" shortcut-effect="focus">(.*)<h3 class="tb-main-title" data-title="(.*)">(.*)</h3>(.*)<p class="tb-subtitle"></p>(.*)<div id="J_TEditItem" class="tb-editor-menu"></div>(.*)</div>(.*)<div id="J_Banner" class="tb-banner"></div>(.*)<ul class="tb-meta">(.*)<li id="J_StrPriceModBox" class="tb-detail-price tb-clear" shortcut-key="p" shortcut-label="(.*)" shortcut-effect="focus">(.*)<span class="tb-property-type">(.*)</span>(.*)<div class="tb-property-cont">(.*)<strong id="J_StrPrice"><em class="tb-rmb">(.*)</em><em class="tb-rmb-num">(.*)</em></strong>(.*)</div>(.*)</li>#isU';
            preg_match($reg_tb,$co,$tb_arr);
            $ku = '#<span id="J_SpanStock" class="tb-count">(.*)</span>#isU';
            preg_match($ku,$co,$tb_ku);
            $img = '#<img id="J_ImgBooth" src="//(.*)" data-hasZoom="700" data-size="400x400"/>#isU';
            preg_match($img,$co,$tb_img);
            $bra_name= "#sellerNick       : '(.*)',#isU";
            preg_match($bra_name,$co,$bra_name2);
             $arr['title']   = empty($tb_arr[6])?'':$tb_arr[6];;//标题
             $arr['img']     = empty($tb_img[0])?'':$tb_img[0];;//显示图片
             $arr['imgUrl']  = empty($tb_img[1])?'':$tb_img[1];;//图片url
             $arr['price']   = empty($tb_arr[20])?'':$tb_arr[20]; ;//价格
             $arr['kucun']   = empty($tb_ku[1])?'':$tb_ku[1];;//库存
             $arr['bre_name']   = empty($bra_name2[1])?'':$bra_name2[1];;//
             return  $arr;
        }
        else if(preg_match('#jd.com#isU',$url))
        {

        }
    }

    /**
     * @param $url
     * @return mixed
     */
    function  actionAddimg(){


        $url = $this -> apiUrl('Goods','add');
        $data['num'] ='10';
       $this -> CurlPost($url , $data);;

    }

    function actionShoplist(){
        $page=Yii::$app->request->post('page');
        $url = $this -> apiUrl('Goods','shopSelect');
        $data['page'] =$page;
        $data['num'] ='10';
        $arr = $this -> CurlPost($url , $data);;
        if(!empty($arr['data'])){
            echo json_encode($arr['data']);
        }else{
            echo json_encode(array());
        }
    }


    /**
     * 采集路径/数据源
     * @return bool|string
     */
    function  a(){
        return  file_get_contents('http://m.api.fanli.com/app/v1/sf/productRecommends.htm?src=2&v=5.2.0.34&abtest=17454_a-32579_b-72_c-138_b-118_b-9874_a-2_a-37c7&bid=10865');
    }
    /**
     * 处理
     */
    function b(){
        $arr =json_decode($this->a(),true);
        $da=[];
        foreach($arr['data']['products'] as $k=>$v ){
            $da[$k]['goods_name']             = $v['name'];                 //商品名字
            $da[$k]['goods_price']            = $v['price'];                //价格
            $da[$k]['goods_rebate']           = $v['fanli'];                //返利
            $da[$k]['goods_url']              = $v['action']['link'];       //商品链接
            $da[$k]['goods_photo']            = substr( $v['mainImgs'][0]['url'],7);   //预览图片
            $da[$k]['info'][0]['startTime']   = $v['timeInfo']['startTime'];//开始时间
            $da[$k]['info'][0]['endTime']     = $v['timeInfo']['endTime'];  //结束时间
            $da[$k]['info'][1]                = $v['shop']['name'];         //商品品牌
        }
       return   $da;
    }
    function actionRuku(){
       $arr = $this->b();
        $info=[];
        $connection = new \yii\db\Connection([
            'dsn' => 'mysql:host=localhost;dbname=fanli',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ]);
        $connection->open();
        foreach($arr as $k=>$v){
            $a = $v['info'][1];
            $res = $connection->createCommand("SELECT bra_id from fanli.fanli_brand WHERE bra_name='$a'")->queryOne();
            if($res ==""){
                $connection->createCommand()->insert('fanli_brand', [
                    'bra_name' => $a,
                    'bra_date' => date("Y-m-d",time()),
                    'bra_status' => 0,
                ])->execute();
                $arr[$k]['info'][1] =$connection->getLastInsertId();
            }else{
                $arr[$k]['info'][1] = $res['bra_id'];
            }
            $connection->createCommand()->insert('fanli_goods', [
                'bra_id' => $arr[$k]['info'][1],
                'goods_name' => $v['goods_name'],
                'goods_price' =>$v['goods_price'],
                'goods_rebate' =>$v['goods_rebate']/$v['goods_price'],
                'goods_url' =>$v['goods_url'],
                'goods_photo' =>$v['goods_photo'],
                'goods_date' =>date("Y-m-d",time()),
                'goods_stock' =>1000,
                'goods_status' =>1,
            ])->execute();
        }
//        print_r($arr);
        $bra_name = array_unique($info);//获取所有商品的品牌
//        $url = $this -> apiUrl('Goods','sall');
//        $data['arr'] =$bra_name;
//        $arrs = $this -> CurlPost($url , $data);;
//        print_r($bra_name);

    }

}
