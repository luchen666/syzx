<?php
/* Smarty version 3.1.30, created on 2017-03-21 09:45:27
  from "F:\wwwroot\lc\application\views\register.htm" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58d085b79095a4_81000056',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6bed362fbb497a3fd3e4a014b77a820121deef27' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\views\\register.htm',
      1 => 1490060723,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58d085b79095a4_81000056 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html xmlns:ng="http://angularjs.org">
<head>
    <meta charset="utf-8">
    <title>注册水运在线账号</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/css/common.css" />
    <link rel="stylesheet" href="/admin/assets/css/reset.css">
    <link rel="stylesheet" href="/admin/assets/css/style.css">
    <link href="/admin/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <?php echo '<script'; ?>
 src="http://html5shim.googlecode.com/svn/trunk/html5.js"><?php echo '</script'; ?>
>
    <style>
        input{background-color: #fff;}
    </style>
    <![endif]-->

</head>
<body ng-app="index" ng-controller="dataCtrl" class="registerBody" >
<div class="header">
    <div class="header-content">
        <a href="/home/index" class="logo"><img src="/images/logo.png" height="100" alt="" /></a>
        <div class="logo-title">欢迎注册</div>
        <div class="have-account">已有账号？<a href="" ng-click="haveLogin();">请登录</a></div>
    </div>

</div>
<div class="row rowStyle">
    <form id="login-form" class="contentStyle">
        <div class="col-md-12">
            <label class="col-md-4">手 机 号<i></i><span>*</span></label>
            <input class="col-md-8" type="text" id="Account" name="Account" placeholder="手机号码" />
            <span class="font12">请输入手机号码</span>
            <span class="font12">号码格式有误</span>
        </div>
        <div class="col-md-12"><label class="col-md-4">密  码<i></i><span>*</span></label>
            <input class="col-md-8" type="password" id="Password" name="Password" placeholder="密码" />
            <span class="font12">请输入密码</span>
        </div>
        <div class="col-md-12"><label class="col-md-4">确 认 密 码<i></i><span>*</span></label>
            <input class="col-md-8" type="password" id="Passwords" name="Password" placeholder="确认密码" />
            <span class="font12">请确认密码</span>
            <span class="font12">两次密码不一致</span>
        </div>
        <div class="col-md-12" style="">
            <label  class="col-md-4">手机验证码<i></i></label>

            <input type="text" placeholder="请输入验证码" id="VCode" name="Code"/>
            <input type="button" class="VCodeBtn" id="VCodeSpan" value="获取验证码" ng-click="getCode()" />
        </div>
        <button type="button" id="loginSubmit" class="button" ng-click="register()">注册</button>
        <div id="loginMsg" style="color:#d9534f;font-weight:bold;margin-top:10px;"></div>
        <p class="point">船东请下载手机App注册</p>
    </form>
</div>
    <!--  登录对话框  -->
        <div class='modal fade in' id='loginForm' style='top:50%;margin-top:-250px;'  aria-hidden='true' ddata-backdrop='static'>
            <div class='modal-dialog' style='width:360px;margin: auto;'><div class='modal-content'>
                <div class='modal-header' style='height:45px;padding-top:0px;'>
                    <button class='close' type='button' data-dismiss='modal' style="font-size: 30px;matgin-top:8px;">×</button>
                    <h4>登录</h4>
                    </div>
                <div class='modal-body float-login-form'>
                    <ul>
                        <li><label>帐　号：</label><input type='text' placeholder='登录帐号' id='login_account'></li>
                        <li><label>密　码：</label><input type='password' placeholder='密码' id='login_password'></li>
                        <li><label>验证码：</label><input type='text' placeholder='验证码' id='login_vcode'>
                            <img id='codeImg' src='/admin/login/code' alt='验证码' title='看不清，点击换一张'></li>
                        </ul>
                    <a href='/home/register'>注册帐号</a>
                    <a href='/home/findPwd'>忘记密码</a>
                </div>
                <div class='modal-footer' style='text-align:center;'>
                    <button ng-click='login()' class='btn btn-success' style='width:100px;'>登录</button>
                </div>

            </div></div></div>

<div style="color: #999;">Copyright ©2017 syzx56.com All Rights Reserved 浙江陆航网络科技有限公司 版权所有</div>
</body>
<!--- ES5兼容库 --->
<?php echo '<script'; ?>
 src="http://cdn.bootcss.com/es5-shim/4.0.5/es5-shim.min.js"><?php echo '</script'; ?>
>

<!--- JQuery --->
<?php echo '<script'; ?>
 src="http://cdn.bootcss.com/jquery/1.10.2/jquery.min.js"><?php echo '</script'; ?>
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
>
    angular.module("index",["utils","syzx"]).controller("dataCtrl",function($scope,$http,$timeout,dlgLogin)
    {
        //已有账号，点击弹出登录框
        $scope.haveLogin = function() {$("#loginForm").modal("show");}

        //点击登录
        $scope.login = function()
        {   var data = {
                Account	 : $("#login_account").val(),
                Password : $("#login_password").val(),
                VCode	 : $("#login_vcode").val()
            }
            if(data.Account == "")	{	msgBox("帐号不能为空。");$("#login_account").focus(); return; }
            if(data.Password == "")	{	msgBox("密码不能为空。");$("#login_password").focus(); return; }
            if(data.VCode == "")	{	msgBox("验证码不能为空。");$("#login_vcode").focus(); return; }

            postURL("/data/logincheck",data).then(function(ret)
                    {
                        window.location.href = "/Ucenter/index";
                        utils.setCookie("Account",ret.Account);
                        utils.setCookie("UseriD",ret.UserID);
                        ret.IsLogin = 1;
                        $("#loginForm").modal("hide");
                    },
                    function(err)
                    {	msgBox(err);
                    });
        }

        //改变input框内容，提示消失
        $("input").change(function () { $(this).siblings(".font12").fadeOut();})

        //点击刷新验证码 带上随机参数防止缓存
        $("#codeImg").click(function () {   $(this).attr("src", "/admin/login/code");   });

        //获取手机验证码
        $scope.getCode = function() {
            console.log(isNaN($("#Account").val()))
            if(!(/^1[3|4|5|8][0-9]\d{4,8}$/.test($("#Account").val())))
            {
                msgBox("请输入手机号")
                return;
            }
            var countdown=299;
            var timer = setInterval(function() {
                if (countdown == 0) {
                    $("#VCodeSpan").removeAttr("disabled");
                    $("#VCodeSpan").val("获取验证码");
                    countdown = 299;
                    clearInterval(timer);
                } else {
                    $("#VCodeSpan").attr("disabled",true);
                    $("#VCodeSpan").val("重新发送(" + countdown + ")");
                    countdown--;
                }
            },1000)

            getURL("/data/VCode?Account="+$("#Account").val()).then(function(data) {

            })
        }
        //点击注册按钮
        $scope.register = function()
        {
            //判断各个必填框是否有值
            if ($("#Account").val() == "")
            {   $("#Account").next().fadeIn();
                return;
            }
            else if(!(/^1[3|4|5|8][0-9]\d{4,8}$/.test($("#Account").val())))
            {   $("#mobilePhone").parent().children().eq(3).fadeIn();
                return;
            }
            else if ($("#Password").val() == "")
            {$("#Password").next().fadeIn();
                return;
            }
            else if ($("#Passwords").val() == "")
            {   $("#Passwords").next(1).fadeIn();
                return;
            }
            else if ($("#Passwords").val() != $("#Password").val())
            {   $("#Passwords").parent().children().eq(3).fadeIn();
                return;
            }
            var data = {
                Account     : $("#Account").val(),
                Password    : $("#Password").val(),
                VCode       : $("#VCode").val()
            }
            postURL("/data/register",data).then(function(data)
            {
                if(data == 1)
                {   msgBox("该用户已经注册!");
                    return;
                }
                else    msgBox("恭喜注册成功！");

            },function(err) {   console.log(err); })
        }


    });

<?php echo '</script'; ?>
>
</html>


<?php }
}
