<?php
/* Smarty version 3.1.30, created on 2017-03-21 09:03:09
  from "F:\wwwroot\lc\application\views\account.htm" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58d07bcd9fcc10_60039801',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '78ff62c7b77f1a2df3f5035f92333b79632af6c8' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\views\\account.htm',
      1 => 1489049404,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58d07bcd9fcc10_60039801 (Smarty_Internal_Template $_smarty_tpl) {
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

	<div class="searchbox">
		日期 从　<input type="text" id="dateFrom" value="<?php echo $_smarty_tpl->tpl_vars['dateFrom']->value;?>
">　到　<input type="text" id="dateTo" value="<?php echo $_smarty_tpl->tpl_vars['dateTo']->value;?>
">
		<button ng-click="more('ALL')">搜索</button>
	</div>
	<table width="100%" cellpadding="0" cellspacing="0" class="billlist">
	<thead><tr>
		<td width="100">日期</td>
		<td>摘要</td>
		<td>金额</td>
		<td width="100">状态</td>
	</tr></thead>
	<tbody><tr ng-repeat="item in list" ng-click="show($index)">
		<td>{{item.CreateDate | dateformat:"yyyy.MM.dd"}}</td>
		<td>{{item.Subject }} </td>
		<td>{{item.Amount }}</td>
		<td class="color-{{item.StateColor}}">{{item.StateText}}</td>
	</tr></tbody>
	</table>
	
	<div class="loadMoreButton" ng-if="showMoreButton" ng-click="more()">载入更多 >>> </div>
	<div class="loadMoreButton" ng-if="list.length==0" style="margin-top:100px">无 匹 配 记 录 ！</div>
	
</td><td width="8"></td><td width="300">
	
		<div class="infobox bgcolor-orange">
			<div class="first"><i class="glyphicon glyphicon-usd"></i></div>
			<div class="second">&nbsp;余额<br><label class="font-24px">￥{{bankAcc.Balance}}</label> 元</div>
			<div class="buttonbox">
				<button ng-click="showSetPwdForm()" style="width:120px;margin-right:10px;">修改支付密码</button>
				<button ng-click="showRechargeForm()">充值</button>
				<button ng-click="showTakeForm()">提现</button>
			</div>
		</div>

		<div class="infobox bgcolor-calm">
			<div class="first"><i class="glyphicon glyphicon-credit-card"></i></div>
			<div class="second" ng-if="bankAcc.CardNo != ''">&nbsp;{{bankAcc.BankName}}　({{bankAcc.CardType == "B" ? "对公" : "私卡"}})<br><label class="font-18px">{{bankAcc.CardNo}}</label></div>
			<div class="second" ng-if="bankAcc.CardNo == ''">&nbsp;未绑帐户<br><label>XXXX XXXX XXXX XXXX</label></div>
			<div class="buttonbox"><button ng-click="bindCard()">绑卡</button><button ng-click="unbindCard()">解绑</button></div>
		</div>
	
</td></tr></table></div>

<!-- 底栏 -->
<site-footer></site-footer>
</body>

<!-- 绑定银行帐户 -->
<div class="modal fade in" id="bindForm" style="top:50%;margin-top:-200px;"  aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" style="width:380px;"><div class="modal-content">
	<div class="modal-header" style="height:30px;padding-top:0px;">
		<button class="close" type="button" data-dismiss="modal">×</button>
		<h4>绑定银行帐户</h4>
	</div>
	<div class="modal-body" style="background:#f7f7f7;padding:0px;">
		<ul class="inqedit" style="margin-left:20px;">
		<li><span>开户行</span><select ng-model="myBank.BankCode">
			<option ng-repeat="item in bankList" value="{{item.Code}}" ng-selected="myBank.BankCode == item.Code">{{item.Name}}</option>
			</select>
		</li>
		<li><span>帐　号</span><input type="text" ng-model="myBank.CardNo"></li>
		<li><span>户　名</span><input type="text" ng-model="myBank.CardName" id="bankCardName"></li>
		<li><span>类　型</span><select ng-model="myBank.CardType">
			<option value="C" ng-selected="myBank.CardType == 'C'">人个卡号</option>
			<option value="B" ng-selected="myBank.CardType == 'B'">对公帐号</option>
			</select>
		</li>
		<!---
		<li><span>验证码</span><input type="number" ng-model="myBank.VCode" style="width:120px"><button
			 style="width:80px" ng-click="sendVCode();" id="VCodeBtn" >发送</button>
		</li>
		--->
		</ul>
	</div>
	<div class="modal-footer" style="text-align:center;">
		<button ng-click="saveBind()" class="btn btn-success" style="width:100px;">绑定</button>
		<button data-dismiss="modal" class="btn btn-warnning" style="width:100px;">关闭</button>
	</div>
</div></div></div>

<!-- 设置支付密码 -->
<div class="modal fade in" id="setPayPwdForm" style="top:50%;margin-top:-200px;"  aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" style="width:380px;"><div class="modal-content">
	<div class="modal-header" style="height:30px;padding-top:0px;">
		<button class="close" type="button" data-dismiss="modal">×</button>
		<h4>设置支付密码</h4>
	</div>
	<div class="modal-body" style="background:#f7f7f7;padding:0px;">
		<ul class="inqedit">
		<li><span style="width:80px">支付密码</span><input type="password" ng-model="payPwd.PayPwd"></li>
		<li><span style="width:80px">确认密码</span><input type="password" ng-model="payPwd.RePayPwd" ng-blur="setPwdCheck()"></li>
		<li style="height:50px"><span style="float:left;width:80px;height:50px;"></span>
			<span  style="float:left;width:220px;line-height:24px;">提示：支付密码必须是数字与字母的组合，且不能与登录密码一致。</span>
		</li>
		<li><span style="width:80px">验证码</span><input type="text " ng-model="payPwd.VCode" style="width:120px"><button
			 style="width:80px" ng-click="sendVCode();" id="VCodeBtn" >发送</button>
		</li>
		</ul>
	</div>
	<div class="modal-footer" style="text-align:center;">
		<button ng-click="setPayPwd()" class="btn btn-success" style="width:100px;">设置</button>
		<button data-dismiss="modal" class="btn btn-warnning" style="width:100px;">关闭</button>
	</div>
</div></div></div>

<!-- 充值 -->
<div class="modal fade in" id="rechargeForm" style="top:50%;margin-top:-200px;"  aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" style="width:380px;"><div class="modal-content">
	<div class="modal-header" style="height:30px;padding-top:0px;">
		<button class="close" type="button" data-dismiss="modal">×</button>
		<h4>充值</h4>
	</div>
	<div class="modal-body" style="background:#f7f7f7;padding:0px;">
		<ul class="editul">
		<li><span>充值金额</span>
			<input type="number" ng-model="rechargeData.Money" placeholder="请输入充值金额">
		</li>
		<li><span>开户银行</span>
			<select name="BankCode" ng-model="rechargeData.BankCode">
				<option ng-repeat="b in bank" value="{{b.Code}}" ng-selected="rechargeData.BankCode == b.Code">{{b.Name}}</option>
			</select>
		</li>
		<li><span>帐号类型</span>
			<select name="CardType" ng-model="rechargeData.CardType">
				<option value="C" ng-selected="rechargeData.CardType == 'C'">私人卡号</option>
				<option value="B" ng-selected="rechargeData.CardType == 'B'">对公帐号</option>
			</select>
		</li>
		</ul>
	</div>
	<div class="modal-footer" style="text-align:center;">
		<button id="payButton" ng-click="doRecharge()" class="btn btn-success" style="width:100px;">去支付</button>
		<button data-dismiss="modal" class="btn btn-warnning" style="width:100px;">关闭</button>
	</div>
</div></div></div>

<!-- 提现 -->
<div class="modal fade in" id="takeForm" style="top:50%;margin-top:-200px;"  aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" style="width:380px;"><div class="modal-content">
	<div class="modal-header" style="height:30px;padding-top:0px;">
		<button class="close" type="button" data-dismiss="modal">×</button>
		<h4>提现</h4>
	</div>
	<div class="modal-body" style="background:#f7f7f7;padding:0px;">
		<ul class="editul">
		<li><span>提现金额</span>
			<input type="number" ng-model="takeCash.Money" placeholder="不能大于帐户余额">
		</li>
		<li><span>提现密码</span>
			<input type="password" ng-model="takeCash.PayPwd" placeholder="用支付密码验证">
		</li>
		</ul>
	</div>
	<div class="modal-footer" style="text-align:center;">
		<button ng-click="doTakeCash()" class="btn btn-success" style="width:100px;">提现</button>
		<button data-dismiss="modal" class="btn btn-warnning" style="width:100px;">关闭</button>
	</div>
</div></div></div>

<form id="submitForm" method="post" target="_blank" action=""></form>
<?php echo '<script'; ?>
>
var currUser = <?php echo $_smarty_tpl->tpl_vars['user']->value;?>
;
if(currUser.UserType == "CD")	currUser.UserTypeText = "船东";
if(currUser.UserType == "HZ")	currUser.UserTypeText = "货主";
if(currUser.UserType == "DL")	currUser.UserTypeText = "代理";
var list = <?php echo $_smarty_tpl->tpl_vars['list']->value;?>
;
var bankAcc = <?php echo $_smarty_tpl->tpl_vars['bankAcc']->value;?>
;
if(bankAcc.CardNo == null) bankAcc.CardNo = "";
<?php echo '</script'; ?>
>
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
 src="/plugins/JSEncrypt 2.3.1/jsencrypt.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="/js/utils.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="/js/syzxModule.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="/js/account.js"><?php echo '</script'; ?>
>

</html><?php }
}
