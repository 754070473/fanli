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


    </style>
</head>
<body>
<form class="form-inline definewidth m20" action="index.html" method="get">  
    商品名称：
    <input type="text" name="rolename" id="rolename"class="abc input-default" placeholder="" value="">&nbsp;&nbsp;  
    <button type="submit" class="btn btn-primary">查询</button>&nbsp;&nbsp; <button type="button" class="btn btn-success" id="addnew">新增商品</button>
</form>
<table class="table table-bordered table-hover definewidth m10" >
    <thead>
    <tr>
        <th>分类</th>
        <th>名称</th>
        <th>图片</th>
        <th>预览</th>
        <th>商品预览</th>
    </tr>
    </thead>
	     <tr>
            <td>5</td>
            <td>管理员</td>
            <td>1</td>
            <td>
                  <a href="edit.html">编辑</a>
            </td>
        </tr></table>
<div class="inline pull-right page">
         10122 条记录 1/507 页
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
</script>