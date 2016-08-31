<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="public/Css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="public/Css/bootstrap-responsive.css" />
    <link rel="stylesheet" type="text/css" href="public/Css/style.css" />
    <script type="text/javascript" src="public/Js/jquery.js"></script>
    <script type="text/javascript" src="public/Js/jquery.sorted.js"></script>
    <script type="text/javascript" src="public/Js/bootstrap.js"></script>
    <script type="text/javascript" src="public/Js/ckform.js"></script>
    <script type="text/javascript" src="public/Js/common.js"></script>

    <style type="text/css">
        body {
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }

        @media (max-width: 980px) {
            /* Enable use of floated navbar text */
            .navbar-text.pull-right {
                float: none;
                padding-left: 5px;
                padding-right: 5px;
            }
        }

        .divcss5{ border:1px solid #000; width:300px; height:100px;overflow:hidden}
        .divcss5 img{max-width:300px;_width:expression(this.width > 300 ? "300px" : this.width);}
    </style>
</head>
<body>
<form class="form-inline definewidth m20" action="index.html" method="get">  
<!--    商品名称：-->
<!--    <input type="text" name="rolename" id="rolename"class="abc input-default" placeholder="" value="">&nbsp;&nbsp;  -->
<!--    <button type="submit" class="btn btn-primary">查询</button>&nbsp;&nbsp; <button type="button" class="btn btn-success" id="addnew">新增商品</button>-->
<!--</form>-->
<table class="table table-bordered table-hover definewidth m10" >
<!--    <thead>-->
<!--    <tr>-->
<!--        <th>编号</th>-->
<!--        <th>分类</th>-->
<!--        <th>名称</th>-->
<!--        <th>价格</th>-->
<!--        <th>返利</th>-->
<!--        <th>图片</th>-->
<!--<!--        <th>返利</th>-->
<!--        <th>图片</th>-->
<!--    </tr>-->
<!--    </thead>-->
</table>
<a href="" > </a>
<center><div id='dd'></div></center>
<input type="hidden" id="num" value='0'/>
<div class="inline pull-right page">
<!--         10122 条记录 1/507 页-->
</div>
</body>
</html>
<script>
    $(function () {
		$('#addnew').click(function(){
				window.location.href="add.html";
		 });
    });
	function del(id)
	{
		if(confirm("确定要删除吗？"))
		{
			var url = "index.html";
			window.location.href=url;
		}
	}
    function postlist()
    {
        var a = $('#num').val();

        if(a=='false')
        {

        }else{
            var num = parseInt($('#num').val())+1;
            $.post('index.php?r=goods/shoplist',{page:num}, function (msg)
            {
                var obj = eval("("+msg+")");
                var str= null;
                str+='<tr >';
                $.each(obj, function (n, value) {

                    str+='<td><a href="'+value['goods_url']+'" target="_blank"><img  src="http://'+value['goods_photo']+'" /></a></td>';
                    str+='<td>' +
                    '<dl>' +
                    '<dt>名称 : <a href="'+value['goods_url']+'" target="_blank">'+value['goods_name']+'</a></dt>' +
                    '<dt>价格 ：<a href="'+value['goods_url']+'" target="_blank">'+value['goods_price']+'</a></dt>' +
                    '<dt>库存 ：<a href="'+value['goods_url']+'" target="_blank">'+value['goods_stock']+'</a></dt>' +
                    '<dt>返利 ：￥<a href="'+value['goods_url']+'" target="_blank">'+value['goods_rebate']+'</a></dt>' +
                    '<dt>添加时间：<a href="'+value['goods_url']+'" target="_blank">'+value['goods_date']+'</a></dt>' +

                    '<dt>访问连接<a href="'+value['goods_url']+'" target="_blank">'+value['goods_url']+'</a></dt>' +
                    '<dl>' +
                    '</td>';
//                    str+='<td><a href="'+value['goods_url']+'" target="_blank">'+value['goods_id']+'</a></td>';
//                    str+='<td><a href="'+value['goods_url']+'" target="_blank">'+value['goods_name']+'</a></td>';
//                    str+='<td><a href="'+value['goods_url']+'" target="_blank">'+value['goods_price']+'</a></td>';
//                    str+='<td><a href="'+value['goods_url']+'" target="_blank">'+value['goods_stock']+'</a></td>';
//                    str+='<td><a href="'+value['goods_url']+'" target="_blank">'+value['goods_rebate']+'</a></td>';
//                    str+='<td><a href="'+value['goods_url']+'" target="_blank">'+value['goods_date']+'</a></td>';
//                    str+='<td class="divcss5"><a href="'+value['goods_url']+'" target="_blank"><img  src="http://'+value['goods_photo']+'" /></a></td>';

                    if(n==1||n==3||n==5||n==7||n==9){
                        str+='</tr>';
                    }
                    if(n>=9){
                        $('#dd').html('<input type="button" value="点击加载" id="buttun" />')
                        $('#num').val(num)
                    }else{
                        $('#dd').html('已经加载完啦')
                        $('#num').val('false')
                    }
                });
                $('table').append(str)
            })
        }

    }
    postlist()
    $('#dd').click(function(){
        postlist()

    });
    }
</script>