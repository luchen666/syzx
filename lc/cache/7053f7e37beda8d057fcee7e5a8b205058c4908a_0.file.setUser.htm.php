<?php
/* Smarty version 3.1.30, created on 2017-03-21 08:54:11
  from "F:\wwwroot\lc\application\views\setUser.htm" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58d079b3eeff39_68565728',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7053f7e37beda8d057fcee7e5a8b205058c4908a' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\views\\setUser.htm',
      1 => 1489049404,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58d079b3eeff39_68565728 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html xmlns:ng="http://angularjs.org">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="max-age=7200" />
<meta name="renderer" content="webkit|ie-comp|ie-stand" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Expires" content="0" />
<title>水运在线</title>
<meta name="keywords" content="水运在线、船期、内贸船、散货船、航运、海运、查船期、找货、船东、货主、超级" />
<meta name="description" content="水运在线、船期、内贸船、散货船、航运、海运、查船期、找货、船东、货主、超级" />
<link rel="stylesheet" type="text/css" href="/css/common.css" />
<link rel="stylesheet" type="text/css" href="/css/ucenter.css" />
<link href="/images/logo.ico" rel="icon" type="image/x-ico" />
</head>
<body ng-app="index" ng-controller="dataCtrl">

<div class="pageloading"><div>载入中 。。。</div></div>
<!-- 头部菜单 -->
<site-menu menu-index=100></site-menu>

<div class="contentbox"><table width="100%" class="body"><tr><td class="left"><ucenter-guide></ucenter-guide></td><td width="10"></td><td>
    
	<div class="content-right">
        <h4>修改资料</h4>
        <form name="content" class="contentStyle setUserContent" enctype="multipart/form-data" >
            <div class="col-md-12"><label class='col-sm-4'>姓 名<i></i><span>*</span></label><input type="text" class='col-sm-8 name' ng-model="setUser.Name" ></div>
            <div class="col-md-12">
                <label class='col-sm-4'>性 别<i></i></label>
                <input id="man" type="radio" ng-checked="setUser.Sex==0?true:false;" name="gender" value="0" /><span for="man">男</span>
                <input id="woman" type="radio"ng-checked="setUser.Sex==1?true:false;"  name="gender" value="1"/><span for="woman">女</span>
            </div>
            <div class="col-md-12"><label class='col-sm-4'>手机号码<i></i></label><input type="text" class='col-sm-8' ng-model="setUser.MobilePhone"></div>
            <div class="col-md-12"><label class='col-sm-4'>电话号码<i></i></label><input type="text" class='col-sm-8' ng-model="setUser.TelePhone"></div>
            <div class="col-md-12"><label class='col-sm-4'>邮 箱<i></i></label><input type="text" type="text" class='col-sm-8' ng-model="setUser.Email"></div>
            <div class="col-md-12"><label class='col-sm-4'>职 位<i></i></label><input type="text" type="text" class='col-sm-8' ng-model="setUser.Duty"></div>
            <div class="col-md-12"><label class='col-sm-4'>头 像<i></i></label>
                <a href="javascript:;" class="file">选择头像<input type="file" ></a>
                <span class="showFileName" style="vertical-align: super;"></span>
            </div>
            <button class="btn btn-primary btn-lg col-sm-5" ng-click="setUserSave();">保存</button>
        </form>
    </div>

</td></tr></table></div>

<!-- 底栏 -->
<site-footer></site-footer>
</body>
<!--- ES5兼容库 --->
<?php echo '<script'; ?>
 src="http://cdn.bootcss.com/es5-shim/4.0.5/es5-shim.min.js"><?php echo '</script'; ?>
>

<!--- JQuery --->
<?php echo '<script'; ?>
 src="http://cdn.bootcss.com/jquery/1.10.2/jquery.min.js"><?php echo '</script'; ?>
>
<link  href="http://cdn.bootcss.com/jqueryui/1.11.1/jquery-ui.min.css" rel="stylesheet">
<?php echo '<script'; ?>
 src="http://cdn.bootcss.com/jqueryui/1.10.2/jquery-ui.min.js"><?php echo '</script'; ?>
>

<!--- bootstrap --->
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css">
<?php echo '<script'; ?>
 src="http://cdn.bootcss.com/bootstrap/3.3.4/js/bootstrap.min.js"><?php echo '</script'; ?>
>

<!--- angularJS --->
<?php echo '<script'; ?>
 src="http://cdn.bootcss.com/angular.js/1.2.2/angular.min.js"><?php echo '</script'; ?>
>

<link href="/css/zh-msg.css" rel="stylesheet">
<?php echo '<script'; ?>
 src="/js/utils.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="/js/syzxModule.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
var currUser = <?php echo $_smarty_tpl->tpl_vars['user']->value;?>
;
if(currUser.UserType == "CD")	currUser.UserTypeText = "船东";
if(currUser.UserType == "HZ")	currUser.UserTypeText = "货主";
if(currUser.UserType == "DL")	currUser.UserTypeText = "代理";

if(typeof(currUser.IsLogin) == "undefined" || currUser.IsLogin != 1)	location.href = "/";

angular.module("index",["utils","syzx"]).controller("dataCtrl",function($scope,$http,$timeout,dlgPort,dlgLogin) 
{
	$scope.user = currUser != null ? currUser : {IsLogin: false};
	postURL("/Ucenter/getUser",$scope.user.UserID).then(function(data) {   $scope.setUser = data[0];   })

	//点击保存按钮
	$scope.setUserSave = function()
	{   if($scope.setUser.Name == "")		msgBox("姓名不能为空!");
		else
		{   var data = {
				UserID		:	$scope.user.UserID,
				Name		:	$scope.setUser.Name,
				Sex			:	$(':radio[name="gender"]:checked').val(),
				MobilePhone	:	$scope.setUser.MobilePhone,
				TelePhone	:	$scope.setUser.TelePhone,
				Email		:	$scope.setUser.Email,
				Duty		:	$scope.setUser.Duty,
				Avatar		:	"/images/uploadfiles/"+$('.showFileName').text()
			}
			postURL("/Ucenter/upInfo",data).then(function(data){msgBox("保存完成。") },function(err){ console.log(err)})
		}
	}

	//显示图片名和限制图片类型
	$(".file").on("change","input[type='file']",function(event) {
		var filePath = $(this).val();
		var ext = filePath.substring(filePath.lastIndexOf("."), filePath.length).toUpperCase();
		if (ext != ".BMP" && ext != ".PNG" && ext != ".GIF" && ext != ".JPG" && ext != ".JPEG") {
			msgBox("图片限于png,gif,jpeg,jpg格式");
			$(".showFileName").html("");
			return false
		}
		else {
			var arr = filePath.split('\\');
			var fileName = arr[arr.length - 1];
			$(".showFileName").html(fileName);
		}
	})

})
<?php echo '</script'; ?>
>
</html><?php }
}
