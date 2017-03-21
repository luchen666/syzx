<?php
/* Smarty version 3.1.30, created on 2017-03-15 15:52:36
  from "F:\wwwroot\lc\application\views\ship.htm" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58c8f2c4901c81_40480664',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '25f816adf1335394125cc3520f244b2c93dfe8d6' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\views\\ship.htm',
      1 => 1489049404,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58c8f2c4901c81_40480664 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
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
	<link rel="stylesheet" type="text/css" href="/font_icon/iconfont.css" />
	<link rel="stylesheet" type="text/css" href="/css/ship.css" />
</head>
<body ng-app="syzxApp" ng-controller="dataCtrl">

<div class="pageloading"><div>载入中 。。。</div></div>
<site-menu menu-index=2></site-menu>

<!--contentbox start-->
<div class="contentbox"><table width="100%" class="body"><tr>

	<td class="left">
		<img src="/{{user.Avatar}}" err-src="/images/defaultAvatar.jpg">
		<hr />
		<p>{{user.Name}}</p>
		<button ng-click="addSupply(-1)"><i class="glyphicon glyphicon-plus color-white"></i>　发布货源</button>
		<!---
		<ul class="left-ul">
			<li><i class="iconfont">&#xe69b;</i><a href="">用户认证</a></li>
			<li><i class="iconfont">&#xe600;</i><a href="">找船定位</a></li>
		</ul>
		--->
	</td>

	<td width="10"></td>

	<td class="center">
		<!--- 搜索栏 --->
		<div class="search-box">
			<ul>
				<li>
					<label>空船港口</label><input type="text" placeholder="请输入港口" ng-click="showPortDlg();" id="portText">
					<label>船名</label><input type="text" placeholder="请输入船名" ng-model="search.ShipName" id="shipName">
				</li>
				<li>
					<label>空船日期</label><input type="text" placeholder="开始日期" ng-model="search.DateFrom" id="searchDateFrom" ><label>—</label>
					<input type="text" placeholder="截止日期" ng-model="search.DateTo" id="searchDateTo">
				</li>
				<li>
					<label>吨位区间</label><input type="number" placeholder="下限吨数" ng-model="search.TonnageFrom"><label>—</label>
					<input type="number" placeholder="上限吨数" ng-model="search.TonnageTo">
				</li>
			</ul>

			<button ng-click="doSearch();"><i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;搜索</button>
			<button class="ieBtn" ng-click="clear();"><i class="glyphicon glyphicon-trash"></i>&nbsp;&nbsp;清空</button>
		</div>

		<!--- 船期列表 --->
		<div class="search-list">

			<table width="100%" cellpadding="0" cellspacing="0">
				<thead><tr>
					<td>类型</td>
					<td>船型</td>
					<td>吨位</td>
					<td>空船港</td>
					<td>空船日期</td>
					<td>船名</td>
					<td>发布</td>
					<td>发盘</td>
				</tr></thead>
				<tbody><tr ng-repeat="item in list" ng-click="showShip($index)">
					<td>{{item.SeaOrRiver == 1 ? "海运" : "河运"}}</td>
					<td>{{item.ShipType}}</td>
					<td>{{item.Tonnage}}</td>
					<td>{{item.Region}} {{ item.ClearPortName == item.Region ? "" : item.ClearPortName}}</td>
					<td>{{item.ClearDate | dateformat:"MM-dd"}}</td>
					<td>{{item.ShipName}}</td>
					<td>{{item.CreateDate | dateformat}}</td>
					<td><button ng-click="addSupply($index)" ng-if="item.IpostSupply != 1">发货盘</button>
						<div ng-if="item.IpostSupply == 1"><i class="glyphicon glyphicon-ok color-green font-24px"></i></div>
					</td>
				</tr></tbody>
			</table>
			<div class="loadMoreButton" ng-if="showMoreButton" ng-click="more()">检索更多 >>> </div>
		</div>
	</td>
	<!-- center end -->
	<td width="10"></td>
	<!-- right start -->
	<td class="right">
		<div class="shipInfoBox">
			<div class="shipname">{{ship.ShipName}} <span></span></div>
			<img src="/images/uploadfiles/{{ship.LogoImage}}?ID={{ship.ID}}" err-src="/images/defaultShip.jpg">
			<ul><li>认证： {{ship.CertifyState == 1 ? "已" : "未"}}认证</li>
				<li>载重： {{ship.Tonnage}} 吨</li>
				<li>船长： {{ship.Long | numberformat:1}} 米</li>
				<li>船宽： {{ship.Width | numberformat:1}} 米</li>
				<li>型深： {{ship.Deep | numberformat:1}} 米</li>
				<li>类型： {{ship.ShipType}}</li>
				<li>建造： {{ship.MadeDate | dateformat:"yyyy"}} 年</li>
			</ul>
			<button ng-click="showHistory(ship.ID)">查看历史船期</button>
		</div>
	</td>
	<!--right end-->
</tr></td></table></div>

<site-footer></site-footer>
</body>

<div class="modal fade in" id="addSupplyForm" style="top:50%;margin-top:-250px"  aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" style="width:600px;"><div class="modal-content">
	<div class="modal-header" style="height:30px;padding-top:0px;">
		<button class="close" type="button" data-dismiss="modal">×</button>
		<h4>发布货源</h4>
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
				<td><input type="text" placeholder="货特名称" ng-model="supply.GoodsName" style="width:104px"><select
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
		<button ng-click="save()" class="btn btn-success" style="width:100px;">保存</button>
	</div>
</div></div></div>

	<!--查看历史船期-->
<div class="modal dhide fade in" id="shipModal" style="margin-top:20px;"  aria-hidden="true" data-backdrop="static">
<div class="modal-dialog" style="width:660px;margin: 10px auto;"><div class="modal-content">
	<div class="modal-header" style="padding:0 0 0 15px;">
		<button class="close" type="button" data-dismiss="modal" style="margin:-10px 10px 0 0;font-size:48px;">×</button>
		<h3>历史船期</h3>
	</div>
	<div class="modal-body" style="padding: 0;height: 682px;overflow-x: auto;width: 100%;">
		<form name="content" class="contentStyle" >
			<div class="search-list hs_shipment">

				<table width="100%" cellpadding="0" cellspacing="0">
					<thead><tr>
						<td width="80">类型</td>
						<td>船型</td>
						<td>吨位</td>
						<td>空船港</td>
						<td>空船日期</td>
						<td>船名</td>
						<td>发布</td>
					</tr></thead>
					<tbody><tr ng-repeat="item in list" ng-click="showShip($index)">
						<td>{{item.SeaOrRiver == 1 ? "海运" : "河运"}}</td>
						<td>{{item.ShipType}}</td>
						<td>{{item.Tonnage}}</td>
						<td>{{item.Region}} {{ item.ClearPortName == item.Region ? "" : item.ClearPortName}}</td>
						<td>{{item.ClearDate | dateformat:"MM-dd"}}</td>
						<td>{{item.ShipName}}</td>
						<td>{{item.CreateDate | dateformat}}</td>
					</tr></tbody>
				</table>
			</div>
		</form>
	</div>
	<div class="modal-footer">
		<div class="hs_loadMore" ng-if="showMoreButton" ng-click="more()">检索更多 >>> </div>
	</div>
</div></div></div>

<?php echo '<script'; ?>
>
var schList = <?php echo $_smarty_tpl->tpl_vars['schlist']->value;?>
;
var currUser = <?php echo $_smarty_tpl->tpl_vars['user']->value;?>
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

<!--- 自定义文件 --->
<link href="/css/zh-msg.css" rel="stylesheet">
<?php echo '<script'; ?>
 src="/js/utils.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="/js/syzxModule.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="/js/ship.js"><?php echo '</script'; ?>
>
</html><?php }
}
