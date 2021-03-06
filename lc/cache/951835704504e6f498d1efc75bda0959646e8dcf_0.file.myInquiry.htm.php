<?php
/* Smarty version 3.1.30, created on 2017-03-21 09:03:00
  from "F:\wwwroot\lc\application\views\myInquiry.htm" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58d07bc4c19be9_40840658',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '951835704504e6f498d1efc75bda0959646e8dcf' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\views\\myInquiry.htm',
      1 => 1489049404,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58d07bc4c19be9_40840658 (Smarty_Internal_Template $_smarty_tpl) {
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
	<ul class="inqmenu">
	<li class="inqmenu-act">全部</li>
	<li>询价中</li>
	<li>执行中</li>
	<li>已完成</li>
	<li>已关闭</li>
	</ul>
	<table width="100%" cellpadding="0" cellspacing="0" class="list">
	<thead><tr>
		<td>船舶</td>
		<td>装卸港口</td>
		<td>受载日期</td>
		<td>货物</td>
		<td>数量</td>
		<td>运价</td>
		<td>状态</td>
		<td>发布于</td>
		<td>操作</td>
	</tr></thead>
	<tbody><tr ng-repeat="item in list" ng-click="show($index)" class="{{item.SupplyUserReadDate==null?'unread':''}}">
		<td ng-bind="item.ShipName"></td>
		<td ng-bind="item.FromPortName + ' - ' + item.ToPortName"></td>
		<td><span ng-bind="item.LoadDateFrom | dateformat:'M月d日'"></span> - <span ng-bind="item.LoadDateTo | dateformat:'M月d日'"></span></td>
		<td ng-bind="item.GoodsName"></td>
		<td><span ng-bind="item.Qty | numberformat:1"></span>±<span ng-bind="item.QtyDeviation">T</td>
		<td ng-bind="item.Price"></td>
		<td class="color-{{item.StateColor}}" ng-bind="item.StateText"></td>
		<td ng-bind="item.CreateDate | dateformat"></td>
		<td><div ng-if="item.State == STATE.NONE">
				<a href="" ng-click="edit($index)" ng-if="item.SupplyUserID == CurrentUserID">编辑</a>
				<a href="" ng-click="close($index)">关闭</a>
				<a href="" ng-click="order($index)" ng-if="item.CreateUserID != CurrentUserID" class="color-green">接单</a>
			</div>
			<div ng-if="item.SupplyUserID == CurrentUserID">
				<div ng-if="item.NeedOnlinePay == 1">
				<a href="/ucenter/payInquiry?ID={{item.ID}}" target="_blank" ng-if="item.State == STATE.ORDER">付订金</a>
				<a href="/ucenter/payInquiry?ID={{item.ID}}" target="_blank" ng-if="item.State == STATE.DEPOSIT">付尾款</a>
				<a href="" ng-click="refund($index)" ng-if="item.State == STATE.DEPOSIT">请求退款</a>
				<a href="" ng-click="abortRefund($index)" ng-if="item.State == STATE.REFUND">撤销退款请求</a>
				</div>
				<a href="" ng-click="done($index)" ng-if="item.State == STATE.ORDER && item.NeedOnlinePay == 0">确认完成</a>
			</div>
		</td>
	</tr></tbody>
	</table>
	<div class="loadMoreButton" ng-if="showMoreButton" ng-click="more()">检索更多 >>> </div>
	<div class="loadMoreButton" ng-if="list.length==0" style="margin-top:100px">无 匹 配 记 录 ！</div>

</td></tr></table></div>

<!-- 底栏 -->
<site-footer></site-footer>
</body>

<!-- 订单编辑 -->
<div class="modal fade in" id="inqEditForm" style="top:50px;display:none;"  aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" style="width:310px;"><div class="modal-content">
	<div class="modal-header" style="height:30px;padding-top:0px;">
		<button class="close" type="button" data-dismiss="modal">×</button>
		<h4>订单修改</h4>
	</div>
	<div class="modal-body" style="background:#f7f7f7;padding:0px;">
		<ul class="inqedit">
		<li><span>船舶</span><b ng-bind="inq.ShipName"></b></li>
		<li><span>港口</span><b ng-bind="inq.FromPortName"></b> <i class="glyphicon glyphicon-arrow-right color-orange"></i> <b ng-bind="inq.ToPortName"></b></li>
		<li><span>受载</span><b ng-bind="inq.LoadDateFrom | dateformat:'M月d日'"></b> <i class="glyphicon glyphicon-arrow-right color-orange"></i> <b ng-bind="inq.LoadDateTo | dateformat:'M月d日'"></b></li>
		<li><span>货物</span><b ng-bind="inq.GoodsName"></b></li>
		<li class="bgcolor-yellow"><span>支付</span><select ng-model="inq.NeedOnlinePay">
			<option value="1" ng-selected="inq.NeedOnlinePay == 1">线上支付</option>
			<option value="0" ng-selected="inq.NeedOnlinePay == 0">线下支付</option>
			</select> <a href="/ucenter/help#PaymentMethod" target="_blank" style="position:relative;top:4px"><i 
				class="glyphicon glyphicon-question-sign font-18px color-blue"></i></a>
		</li>
		<li><span>数量</span><input type="number" ng-model="inq.Qty" ng-change="reCal();" style="width:100px"
			><span style="width:20px;text-align:center">±</span><input 
			type="number" ng-model="inq.QtyDeviation" style="width:80px"> 吨
		</li>
		<li><span>运价</span><input type="number" ng-model="inq.Price" style="width:120px" ng-change="reCal();"><select 
			ng-model="inq.TaxInclusive" style="width:80px;">
			<option value="1" ng-selected="inq.TaxInclusive == 1">含税</option>
			<option value="0" ng-selected="inq.TaxInclusive == 0">不含税</option>
			</select> 元
		</li>
		<li><span>运费</span><input type="number" ng-model="inq.TotalSum"> 元</li>
		<li style="border-bottom:0px;"><span>订金</span><input type="number" ng-model="inq.Deposit"> 元</li>
		</ul>
	</div>
	<div class="modal-footer" style="text-align:center;">
		<button ng-click="save()" class="btn btn-success" style="width:100px;">保存</button>
		<button data-dismiss="modal" class="btn btn-warnning" style="width:100px;">关闭</button>
	</div>
</div></div></div>

<!-- 订单详情 -->
<div class="modal fade in" id="inqShowForm" style="top:10px;display:none;"  aria-hidden="true" ddata-backdrop="static">
	<div class="modal-dialog" style="width:600px;"><div class="modal-content">
	<div class="modal-header" style="height:30px;padding-top:0px;">
		<button class="close" type="button" data-dismiss="modal">×</button>
		<h4>订单详情</h4>
	</div>
	<div class="modal-body" style="background:#f7f7f7;padding:0px;">
	
	<div class="inqshow">
		<div class="inqview">
			<div class="r1">
				<div>单号：{{ inq.OrderNo == null ? "-" : inq.OrderNo}}</div>
				<div class="float-right"><i class="icon ion-compose font-18px"></i> {{inq.CreateDate | dateformat }}</div>
			</div>

			<div class="r2"></div>

			<div class="r3">
				<div class="port align-left">{{inq.FromRegion}}<br>{{ inq.FromPortName }}</div>
				<div class="align-center">
					<label><span ng-if="inq.SeaOrRiver == 1" class="color-navy">海运</span>
						   <span ng-if="inq.SeaOrRiver == 0" class="color-calm">河运</span>
					</label>
					<label style="height:10px;"><img src="/images/arrow-r.png" style="width:100px;height:5px;position:relative;top:-10px;"></label>
					<label>{{ inq.GoodsName }} {{ inq.Qty | numberformat:1 }}±{{ inq.QtyDeviation}}T</label>
				</div>
				<div class="port align-right">{{inq.ToRegion}}<br>{{ inq.ToPortName }}</div>
			</div>

			<div class="r4">
				<div>
					<i class="icon ion-android-calendar font-18px top2px color-orange"></i> 受载 &nbsp;
					<label class="text-info">{{inq.LoadDateFrom | dateformat:"M月d日"}} - {{inq.LoadDateTo | dateformat:"M月d日"}}</label>
				</div>
				<div class="float-right" ng-if="inq.Price==null||inq.Price==0"><label class="text-info">运价待定</label></div>
				<div class="float-right" ng-if="inq.Price!=null&&inq.Price>0"><label class="text-info">￥{{inq.Price | numberformat:2 }}/吨</label>&nbsp; {{inq.TaxInclusive ? "含税" : "不含税"}}</div>
			</div>
		</div>
		
		<table class="inqtable">
		<tr>
			<td>运费总额</td><td>{{inq.TotalSum}} 元</td>
			<td>合同订金</td><td>{{inq.Deposit}} 元</td>
		</tr>
		<tr>
			<td>现场服务</td><td width="200">{{inq.NeedAgent ? "需要" : "不需要"}}</td>
			<td>包装方式</td><td>{{inq.PackageMethodText}} </td>
		</tr>
		<tr>
			<td width="80">支付手段</td><td class="color-orange">{{inq.NeedOnlinePay==1 ? "线上" : "线下"}}支付</td>
			<td width="80">付款方式</td><td>{{inq.PaymentMethodText}}</td>
		</tr>
		</table>
		
		<div class="inqstate" ng-if="inq.State == STATE.DEPOSIT || inq.State == STATE.REFUND">
			<div class="float-left center" style="padding-left:10px;padding-top:10px;width:70px">
				<i class="glyphicon glyphicon-usd font-32px color-green"></i>
			</div>
			<div class="float-left" style="line-height:26px;">
				已付订金：{{inq.Deposit | numberformat:2}} 元<br>
				支付时间：{{inq.DepositDate | dateformat:"yyyy-MM-dd HH:mm:ss" }}<br>
				<span ng-if="inq.State == STATE.REFUND" class="color-red">退款原因：{{inq.RefundReason }}</span>
			</div>
		</div>
		
		<table class="inqtable">
		<tr>
			<td width="80">运单状态</td><td width="200"><b class="color-{{inq.StateColor}}">{{ inq.StateText }}</b></td>
			<td width="80">接单时间</td><td>{{inq.OrderDate | dateformat:"yyyy-MM-dd HH:mm" }}</td>
		</tr>
		<tr>
			<td>下单用户</td><td>{{ inq.CreateUserName }}</td>
			<td>修改时间</td><td>{{inq.UpdateDate | dateformat:"yyyy-MM-dd HH:mm"}}</td>
		</tr>
		<tr>
			<td>完成时间</td><td>{{inq.DoneDate | dateformat:"yyyy-MM-dd HH:mm" }}</td>
			<td>创建时间</td><td>{{inq.CreateDate | dateformat:"yyyy-MM-dd HH:mm"}}</td>
		<tr>
		</table>
		
		<div class="inquserbox">
			<ul class="float-left">
				<li>货主：{{inq.SupplyUserName}}</li>
				<li>电话：{{inq.SupplyUserMobilePhone}}</li>
				<li>星级：{{inq.SupplyUserStar  | repeat:'☆'}}</li>
				<div id="supplytel" class="circlebutton-30px" ng-click="tel(inq.SupplyUserMobilePhone)"><i class="icon ion-ios-telephone"></i></div>
			</ul>
			<ul class="float-right">
				<li>船主：{{inq.ShipUserName}}</li>
				<li>电话：{{inq.ShipUserMobilePhone}}</li>
				<li>星级：{{inq.ShipUserStar  | repeat:'☆'}}</li>
				<div id="shiptel" class="circlebutton-30px" ng-click="tel(inq.ShipUserMobilePhone)"><i class="icon ion-ios-telephone"></i></div>
			</ul>
		</div>
		
		<div class="locationbar" ng-click="showLocation()">
			<span ng-if="inq.Location != '' && inq.Location != null">
				<div><img src="/images/map.png"></div>
				<div>位置：{{ inq.Location }}<br>
					 更新：{{ inq.LocationUpdateTime | dateformat:"yyyy-MM-dd HH:mm:ss" }}
				</div>
			</span>
			<span ng-if="inq.Location == '' || inq.Location == null">
				<div style="margin-top:5px;margin-left:10px;"><i class="glyphicon glyphicon-map-marker color-calm font-36px"></i></div>
				<div style="line-height:50px">等待船主更新位置</div>
			</span>
		</div>
		
		<table class="inqtable hand" ng-if="!showShipState" ng-click="toggleShip();">
		<tr>
			<td width="80">船舶名称</td><td width="200">{{inq.ShipName}}</td>
			<td width="80">船舶类型</td><td>{{inq.ShipTypeName}}<i class="glyphicon glyphicon-chevron-down font-18px pull-right" style="margin-top:5px"></i></td>
		</tr>
		</table>
		
		<table class="inqtable hand" ng-if="showShipState" ng-click="toggleShip();">
		<tr>
			<td width="80">船舶名称</td><td width="200">{{inq.ShipName}}</td>
			<td width="80">船舶类型</td><td>{{inq.ShipTypeName}}<i class="glyphicon glyphicon-chevron-up font-18px pull-right" style="margin-top:5px"></i></td>
		</tr>
		<tr>
			<td>ＭＭＳＩ</td><td>{{inq.MMSI}}</td>
			<td>建造日期</td><td>{{inq.MadeDate | dateformat:"y年M月d日" }}</td>
		</tr>
		<tr>
			<td>船　　长</td><td>{{inq.Long | numberformat:1 }} 米</td>
			<td>船　　宽</td><td>{{inq.Width | numberformat:1 }} 米</td>
		</tr>
		<tr>
			<td>型　　深</td><td>{{inq.Deep | numberformat:1 }} 米</td>
			<td>仓　　容</td><td>{{inq.Capacity | numberformat:1:0 }} 方</td>
		</tr>
		<tr>
			<td>满载吃水</td><td>{{inq.FullDraught | numberformat:1 }} 米</td>
			<td>空载吃水</td><td>{{inq.EmptyDraught | numberformat:1 }} 米</td>
		</tr>
		<tr>
			<td>舱口数量</td><td>{{inq.HatchNum}} 个</td>
			<td>　船籍港</td><td>{{inq.RegistryPort}}</td>
		</tr>
		</table>
		<div ng-if="showShipState && ship.LogoImage != ''" style="margin-top:5px"><img src="/{{ship.LogoImage}}" width="100%" err-src="/images/defaultBG.jpg"></div>
	
	</div>
	</div>
	<div class="modal-footer" style="text-align:center;">
		
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
 src="/js/myInquiry.js"><?php echo '</script'; ?>
>

</html><?php }
}
