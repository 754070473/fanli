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
        $url = $this -> apiUrl('Goods','type');
        $data = array('type' => 'return');
        $type = $this -> CurlPost($url , $data);
        return $this->render('add.html',['type'=>$type['data']]);
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
     * 超级反数据
     */
    public function actionAdd11(){


        $content = file_get_contents('http://m.api.fanli.com/app/v1/sf/productRecommends.htm?src=2&v=5.2.0.34&abtest=17454_a-30115_b-72_c-138_b-112_b-6_b-9874_a-2_a-326b&bid=921');
        $content2= json_decode($content,true);
        print_r($content2);die;
       $content = file_get_contents('http://m.api.fanli.com/app/v3/sf/cats.htm?src=2&v=5.2.0.34&abtest=17454_a-32579_b-72_c-138_b-112_b-6_b-9874_a-2_a-326b');
        $content2= json_decode($content,true);
//        print_r($content2);die;
        $arr =  $content2['data']['cats'];
        $shop_new=[];
        $sql = "insert into fanli_classify(cla_id,cla_name,cla_order,pid,cla_date,cla_status) VALUES ";
        foreach($arr as $k=>$v){
            if(empty($v['sort'])){
                $sql.="(";
                $sql.="'".$v['id']."',";
                $sql.="'".$v['name']."',";
                $sql.="'1',";
                $sql.="'0',";
                $sql.="'".time()."',";
                $sql.="'1'),";
            }else{
                $sql.="(";
                $sql.="'".$v['id']."',";
                $sql.="'".$v['name']."',";
                $sql.="'".$v['sort']."',";
                $sql.="'".$v['parentId']."',";
                $sql.="'".time()."',";
                $sql.="'1'),";
            }

//            $shop_new[$k]['cla_id']=$v['id'];
//            $shop_new[$k]['cla_name']=$v['name'];
//            $shop_new[$k]['pid']=$content2['data']['totalCount'];
//            $shop_new[$k]['cla_date']=time();
//            $shop_new[$k]['cla_status']=1;
//            if(!empty($v['id'])){
//                $shop_new[]=json_decode($this->AndroidCurl('http://m.api.fanli.com/app/v1/sf/productRecommends.htm?src=2&v=5.2.0.34&abtest=17454_a-32579_b-72_c-138_b-112_b-6_b-9874_a-2_a-326b&bid='.$v['id']),true);
//            if($k>=3)break;
//            }
        }
   echo     substr($sql , 0 ,-1);

//        foreach(){
//
//        }
//        print_r($shop_new);
    }

    /**
     * 商品/品牌
     */
    function cjType(){
        $content = file_get_contents('http://m.api.fanli.com/app/v2/sf/todayNew.htm?src=2&v=5.2.0.34&abtest=17454_a-32579_b-72_c-138_b-112_b-6_b-9874_a-2_a-326b&pidx=5&psize=40');
        $content2= json_decode($content,true);
        $arr =  $content2['data']['dateGroup'][0]['brands'];
        $shop_new=[];
        $sql = "insert into fanli_classify(cla_id,cla_name,pid,cla_date,cla_status) VALUES ";
        foreach($arr as $k=>$v){
            $sql.="(";
            $sql.="'".$v['id']."',";
            $sql.="'".$v['name']."',";
            $sql.="'".$content2['data']['totalCount']."',";
            $sql.="'".time()."',";
            $sql.="'1'),";
        }
        echo   substr($sql , 0 ,-1);
    }
//    public function actionAdd()
//    {
     //   http://super.fanli.com/h5/brand/?bid=6761&lc=super_women_brand&spm=super_woman.h5.pty-click%7Ebid-6761%7Eindex-3
     //   http://fun.fanli.com/goshop/go?id=712&go=http%3A%2F%2Fm.api.51fanli.com%2Fapp%2Fitem.htm%3Fid%3D41929831091%26s%3Dtaobao.com&pid=41929831091&wp=1

        //商品
        //http://fun.fanli.com/mobileapi/v2/shop/getFanliRule?shopid=712&pid=536778999138&sellernick=&ci=super&gsn=8&abtest=17454_a-32579_b-72_c-138_b-112_b-6_b-9874_a-2_a-326b

   //     https://detail.tmall.com/item.htm?id=41929831091

   //     $shop = file_get_contents('http://m.api.fanli.com/app/v3/sf/cats.htm?src=2&v=5.2.0.34&abtest=17454_a-32579_b-72_c-138_b-112_b-6_b-9874_a-2_a-326b');
   //     print_r(json_decode($shop,true));
        //分类下商品
    //    http://m.api.fanli.com/app/v1/sf/productRecommends.htm?src=2&v=5.2.0.34&abtest=17454_a-32579_b-72_c-138_b-112_b-6_b-9874_a-2_a-326b&bid=1205
        //也可能感兴趣的
      //  http://m.api.fanli.com/app/v3/sf/brandRecommends.htm?src=2&v=5.2.0.34&abtest=17454_a-32579_b-72_c-138_b-112_b-6_b-9874_a-2_a-326b&bid=1205
      //  return $this->render('add.html');
     //   echo       json_decode(json_encode('ifanli://m.51fanli.com/app/show/web?url=http%3A%2F%2Fsuper.fanli.com%2Fh5%2Fchannel%3Fid%3D17%26lc%3Dand_cats&nologin=1&lc=and_cats'),true);
//        htmlentities() 转义


//     echo   $this->_str('http%3A%2F%2Fsuper.fanli.com%2Fh5%2Fchannel%3Fid%3D17%26lc%3Dand_cats&nologin=1&lc=and_cats');
//    }

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

//            print_r($co);die;
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

    function  curlGet($url){
        #初始化curl
        $ch=curl_init();
        #请求url地址
        $params[CURLOPT_URL]=$url;
        #是否返回响应头信息
        $params[CURLOPT_HEADER] = true;
        #是否将结果返回
        $params[CURLOPT_RETURNTRANSFER] = true;
        #是否重定向
        $params[CURLOPT_FOLLOWLOCATION] = false;
        #伪造浏览器
        $params[CURLOPT_USERAGENT] = 'Mozilla/5.0 (Windows NT 5.1; rv:9.0.1) Gecko/20100101 Firefox/9.0.1';
        #开始发送请求，传入curl参数
        curl_setopt_array($ch, $params);
        $content=curl_exec($ch);
        return $content;
    }
}
