<?php
/* Smarty version 3.1.30, created on 2017-03-13 17:21:42
  from "F:\wwwroot\lc\application\views\ucenter.htm" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58c664a6af2492_85827864',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '07f98820e4474e365776d30403162400ce8aeaf1' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\views\\ucenter.htm',
      1 => 1489049404,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58c664a6af2492_85827864 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html xmlns:v="urn:schemeas-microsoft-com:vml">
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

	<table width="100%" cellpadding="0" cellspacing="0" class="list">
	<thead><tr>
		<td>类型</td>
		<td>途径</td>
		<td>货物</td>
		<td>数量</td>
		<td>支付方式</td>
		<td>订金</td>
		<td>日期</td>
		<td>状态</td>
		<td>操作</td>
	</tr></thead>
	<tbody><tr ng-repeat="item in list" ng-click="showSupply($index)">
		<td align="center" ng-bind="item.SeaOrRiver == 1 ? '海运' : '河运'"></td>
		<td align="center" ng-bind="item.FromPlace + ' - ' + item.ToPlace"></td>
		<td ng-bind="item.GoodsName"></td>
		<td><span ng-bind="item.Qty | numberformat:1"></span>±<span ng-bind="item.QtyDeviation"></span>T</td>
		<td ng-bind="item.PaymentMethodText"></td>
		<td ng-bind="item.Deposit"></td>
		<td ng-bind="item.CreateDate | dateformat"></td>
		<td ng-bind="item.StateText"></td>
		<td><div ng-if="item.State == STATE.NONE"><a href="" ng-click="editSupply($index)">编辑</a>　<a href="" ng-click="del($index)">删除</a></div></td>
	</tr></tbody>
	</table>
	<div class="loadMoreButton" ng-if="showMoreButton" ng-click="more()">检索更多 >>> </div>
	<div class="loadMoreButton" ng-if="list.length==0" style="margin-top:100px">无 匹 配 记 录 ！</div>
	
</td></tr></table></div>

<!-- 底栏 -->
<site-footer></site-footer>
</body>

<div class="modal fade in" id="supForm" style="top:50%;margin-top:-250px;display:none;"  aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" style="width:600px;"><div class="modal-content">
	<div class="modal-header" style="height:30px;padding-top:0px;">
		<button class="close" type="button" data-dismiss="modal">×</button>
		<h4 ng-bind="readOnly == 1 ? '货源信息' : '发布货源'"></h4>
	</div>
	<div class="modal-body" style="background:#f7f7f7">
		<table class="edittable">
		<tr>
			<td width="70">港口　从<sup>*</sup></td>
			<td width="250">
				<input type="text" readonly placeholder="起运港口" id="fromPortText" ng-click="pickFromPort();"><span>到</span><input 
				type="text" readonly placeholder="抵达港口" id="toPortText" ng-click="pickToPort();">
			</td>
			<td width="70">运输类型<sup>*</sup></td>
			<td><select ng-model="supply.SeaOrRiver">
				<option value="1" ng-selected="supply.SeaOrRiver == 1">海运</option>
				<option value="0" ng-selected="supply.SeaOrRiver == 0">河运</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>受载　从<sup>*</sup></td>
			<td><input type="text" readonly placeholder="起始日期" id="addDateFrom" ng-model="supply.LoadDateFrom"><span>到</span><input 
				type="text" readonly placeholder="截止日期" id="addDateTo" ng-model="supply.LoadDateTo"></td>
			<td>付款方式<sup>*</sup></td>
			<td><select ng-model="supply.PaymentMethod">
				<option ng-repeat="p in PAYMENT_METHOD_TEXT" value="{{p.ID}}" ng-selected="supply.PaymentMethod == p.ID">{{p.Text}}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>发货数量<sup>*</sup></td>
			<td><input type="number" placeholder="发货数量" ng-model="supply.Qty"><span>±</span><input 
				type="number" placeholder="偏差数量" ng-model="supply.QtyDeviation"> 吨
			</td>
			<td>订金金额</td>
			<td><input type="number" string_float ng-model="supply.Deposit" placeholder="空，不付订金"> 元</td>
		</tr>
		<tr>
			<td>货物名称<sup>*</sup></td>
			<td><input type="text" placeholder="货特名称" ng-model="supply.GoodsName" id="GoodsName" style="width:104px"><select
				ng-model="supply.PackageMethod">
				<option ng-repeat="p in PACKAGE_METHOD" value="{{p.ID}}" ng-selected="item.PackageMethod == p.ID">{{p.Text}}</option>
				</select>
			</td>
			<td>现场服务</td>
			<td><select  ng-model="supply.NeedAgent">
				<option value="1" ng-selected="supply.NeedAgent == 1">需要</option>
				<option value="0" ng-selected="supply.NeedAgent == 0">不需要</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>运输价格</td>
			<td><input type="number"  placeholder="不填代表商议" string_float ng-model="supply.Price" 
				style="width:104px;"><select ng-model="supply.TaxInclusive">
				<option value="0" ng-selected="supply.TaxInclusive == 0">不含税</option>
				<option value="1" ng-selected="supply.TaxInclusive == 1">含税</option>
				</select> 元
			</td>
			<td>机 载 率</td>
			<td><input type="number" string_float ng-model="supply.LoadRatio" placeholder="机载率"></td>
		</tr>
		<tr>
			<td>备注事项</td>
			<td colspan="3"><input type="text" placeholder="其它事项"  ng-model="supply.Memo" style="width:400px;"></td>
		</tr>
		</table>
	</div>
	<div class="modal-footer" style="text-align:center;">
		<button ng-if="readOnly == 0" ng-click="save()" class="btn btn-success" style="width:100px;">保存</button> 
		<button ng-if="readOnly == 1" data-dismiss="modal" class="btn" style="width:100px;">关闭</button> 
	</div>
</div></div></div>

<?php echo '<script'; ?>
>
var currUser = <?php echo $_smarty_tpl->tpl_vars['user']->value;?>
;
if(typeof(currUser.IsLogin) == "undefined" || currUser.IsLogin != 1)	location.href = "/";
if(currUser.UserType == "CD")	currUser.UserTypeText = "船东";
if(currUser.UserType == "HZ")	currUser.UserTypeText = "货主";
if(currUser.UserType == "DL")	currUser.UserTypeText = "代理";

var list = <?php echo $_smarty_tpl->tpl_vars['list']->value;?>
;
var PACKAGE_METHOD = <?php echo $_smarty_tpl->tpl_vars['packageMethod']->value;?>

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
 src="/js/utils.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="/js/syzxModule.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="/js/Ucenter.js"><?php echo '</script'; ?>
>

</html><?php }
}
