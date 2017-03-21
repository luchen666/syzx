<?php
/* Smarty version 3.1.30, created on 2017-03-21 09:03:14
  from "F:\wwwroot\lc\application\views\setPass.htm" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58d07bd2eebaa6_79447019',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e66d0cb9c81ab0dbdaf6ccd6d574937f12063374' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\views\\setPass.htm',
      1 => 1489049404,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58d07bd2eebaa6_79447019 (Smarty_Internal_Template $_smarty_tpl) {
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
        <h4>修改密码</h4>
        <form name="content" class="contentStyle setPassContent" enctype="multipart/form-data">
            <div class="col-md-12"><label class='col-sm-4'>原始密码<i></i></label><input type="password" class='col-sm-8 password0'></div>
            <div class="col-md-12"><label class='col-sm-4'>新密码<i></i></label><input type="password" class='col-sm-8 password1'></div>
            <div class="col-md-12"><label class='col-sm-4'>确认密码<i></i></label><input type="password" class='col-sm-8 password2'></div>
            <button class="btn btn-primary btn-lg col-sm-5" ng-click="passSave()" >保存</button>
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
    if(typeof(currUser.IsLogin) == "undefined" || currUser.IsLogin != 1)	location.href = "/";

    angular.module("index",["utils","syzx"]).controller("dataCtrl",function($scope,$http,$timeout,dlgPort,dlgLogin) {
        $scope.user = currUser != null ? currUser : {IsLogin: false};

        //点击保存按钮
        $scope.passSave = function()
        {
            if($(".password0").val()=="" || $(".password1").val() =="" || $(".password2").val()=="")	msgBox("密码不能为空!");
            else
            {   if($(".password1").val() != $(".password2").val())	msgBox("两次输入的密码不相同!");
                else {
                    var data = {
                        Password0	:	$(".password0").val(),
                        Password1	:	$ (".password1").val()
                    }
                    postURL("/Ucenter/changePwd",data).then(function(data)
                    {   if(data == -2 )
                        {	msgBox("原始密码错误,请重新输入!");
                            return;
                        }
                        else
                        {	$("#myModal").modal("hide");
                            msgBox("保存完成。");
                        }
                    })
                }
            }
        }
    })
<?php echo '</script'; ?>
>

</html><?php }
}
