<?php
/* Smarty version 3.1.30, created on 2017-03-13 17:21:39
  from "F:\wwwroot\lc\application\views\index.htm" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58c664a30fe986_47337212',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'af4de211291f5d7672b63b27d3a383abedf68100' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\views\\index.htm',
      1 => 1489049404,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58c664a30fe986_47337212 (Smarty_Internal_Template $_smarty_tpl) {
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
	<link rel="stylesheet" type="text/css" href="/css/index.css" />
	<link href="new_images/logo.ico" rel="icon" type="image/x-ico" />

</head>
<body ng-app="index" ng-controller="dataCtrl">

<div class="pageloading"><div>载入中 。。。</div></div>

<!-- 头部菜单 -->
<site-menu menu-index=1></site-menu>

<!--- 轮播图 --->
<div class="align-center" id="banner" style="height:432px">
<ul>
	<li><img src="/images/site/banner2.jpg"></li>
	<li><img src="/images/site/banner3.jpg"></li>
	<li><img src="/images/site/banner1.jpg"></li>
</ul>
</div>

<!--- 轮播图工作区 --->
<div class="contentbox" style="position:relative;margin-top:-350px;height:350px;">

	<!-- 今日运价参考表 -->
	<div class="index-left-box1"></div>
	<div class="index-left-box2">
		<div class="title">今日运价</div>
		<ul class="header"><li>吨位</li><li class="row-li">航线</li><li>运价</li><li>货品</li></ul>
		<div style="border:0px solid red;height: 220px;overflow: hidden;" id="index-piceUl">
			<div style="border:0px solid red;">
				<ul class="row" ng-repeat="item in TodayTransportPrice">
					<li ng-bind="item.Qty"></li>
					<li class="row-li"><span ng-bind="item.FromRegion"></span>—<span ng-bind="item.ToRegion"></span></li>
					<li ng-bind="item.Price"></li>
					<li ng-bind="item.GoodsName"></li>
				</ul>
			</div>
		</div>

	</div>

	<!-- 免登录发布信息 -->
	<div class="index-right-box1"></div>
	<div class="index-right-box2">
		<div class="title">快速发布船期货源</div>
		<textarea id="publishValue" onfocus="this.select()">填写真实的找船、找货等需求，并留下联系方式，我们收到后会立即给您回电确认，并免费帮您匹配!</textarea>
		<button ng-click="publish()">提&nbsp;&nbsp;交</button>
	</div>
</div>

<div class="bgcolor-contrast align-center" style="overflow:hidden;padding-top:15px;padding-bottom:15px;">

	<!--- 快捷搜索，暂时不搜索找货 --->
	<div class="index-search-tab hide"><div>找船</div><div>找货</div></div>
	<div class="contentbox index-search-bar">
		<div class="input" ng-click="showPortDlg();" id="portBox">
			<input readonly type="text" placeholder="装货港" id="clearPortText">
			<button><i class="glyphicon glyphicon-map-marker"></i></button>
		</div>
		<div class="input" ng-click="showClearDateDlg();" >
			<input readonly type="text" placeholder="空船时间" id="clearDateText">
			<button><i class="glyphicon glyphicon-calendar"></i></button>
			<div class="clearDateDlg" tabindex=2><!--- 防止CSS影响日期选择器 --->
				<ul><li>开始时间</li><li><div id="dlgClearDateFrom"></div></li></ul>
				<ul><li>截止时间</li><li><div id="dlgClearDateTo"></div></li></ul>
			</div>
		</div>
		<div class="input" ng-click="showTonnageDlg()">
			<input readonly type="text" placeholder="吨位" id="tonnageText">
			<button><i class="glyphicon glyphicon-th-large"></i></button>
			<ul class="tonnageDlg" tabindex=3>
			<li>不限</li>
			<li>1000 吨以下</li>
			<li>1000 - 3000 吨</li>
			<li>3000 - 5000 吨</li>
			<li>5000 - 10000 吨</li>
			<li>10000 - 30000 吨</li>
			<li>30000 吨以上</li>
			</ul>
		</div>
		<button class="searchbutton" ng-click="doSearch();">搜 索</button>
	</div>

	<div class="h20"></div>

	<!--- 船期信息 --->
	<div class="contentbox">

		<!--- 左侧 --->
		<div class="index-left-bar">

			<!--- 今日统计 --->
			<div class="counter">
				<div>
					<label class="font-14px">今日船期</label><br>
					<label class="font-36px font-bold" ng-bind="TodaySchNumber"></label>
				</div>
				<div>
					<label class="font-14px">今日货源</label><br>
					<label class="font-36px font-bold" ng-bind="TodaySupplyNumber"></label>
				</div>
				<div>
					<label class="font-14px">累计船期</label><br>
					<label class="font-36px font-bold" ng-bind="SchTotal"></label>
				</div>
			</div>

			<div class="h20"></div>

			<!--- 船期 --->
			<div class="index-list">
				<table width="100%" cellpadding="0" cellspacing="0">
				<thead><tr>
					<td>类型</td>
					<td>船型</td>
					<td>吨位</td>
					<td>空船港</td>
					<td>空船日期</td>
					<td>船名</td>
					<td>发布</td>
					<td><a href="/home/ship">更多...</a></td>
				</tr></thead>
				<tbody><tr ng-repeat="item in schList">
					<td>{{item.SeaOrRiver == 1 ? "海运" : "河运"}}</td>
					<td>{{item.ShipType}}</td>
					<td>{{item.Tonnage}}</td>
					<td>{{item.Region}} {{ item.ClearPortName == item.Region ? "" : item.ClearPortName}}</td>
					<td>{{item.ClearDate}}</td>
					<td>{{item.ShipName}}</td>
					<td>{{item.CreateDate | dateformat}}</td>
					<td>{{item.more}}</td>
				</tr></tbody>
				</table>
			</div>
		</div>
		<div class="index-right-bar">
			<div><img src="/images/pay.png" width="300" height="164"></div>
			<div class="title">成交公告</div>
			<ul class="header"><li>航线</li><li>货物</li><li>数量</li><li>日期</li></ul>
			<div id="index-priceUl2" style="height:240px;overflow: hidden;font-size: 14px;">
				<div>
					<ul class="row" ng-repeat="keys in TodayDealOrder">
						<li class="address">{{keys.FromRegion}}-{{keys.ToRegion}}</li>
						<li>{{keys.GoodsName}}</li>
						<li>{{keys.Qty}}</li>
						<li>{{keys.DealDate }}</li>
					</ul>
				</div>
			</div>

		</div>
	</div>
</div>

<!--货船保险-->
<div class="center"><div class="insurance contentbox">
	<div class="title">货船保险</div>
	<div class="insu_left insu-com">
		<h4>订单动态</h4>
		<table cellspacing="12" cellpadding="0">
			<thead>
			<tr><th>保险类别</th><th>保险金额</th><th>投保对象</th><th>投保时间</th></tr>
			</thead>
			<tbody>
			<tr><td>货物险</td><td>2106元</td><td>煤炭</td><td>17-02-10</td></tr>
			<tr><td>货物险</td><td>1069元</td><td>石料</td><td>17-02-07</td></tr>
			<tr><td>货物险</td><td>5830元</td><td>PTA</td><td>17-02-06</td></tr>
			<tr><td>货物险</td><td>7205元</td><td>纸浆</td><td>17-02-05</td></tr>
			<tr><td>货物险</td><td>3156元</td><td>钢材</td><td>17-02-05</td></tr>
			</tbody>
		</table>
	</div>
	<div class="insu_center insu-com">
		<h4>海运货物综合险</h4>
		<div>
			<p>7x24小时随时随地购买。<span>保险费率最低仅需0.15‰</span>。</p>
			<p class="insuP2">中国人保财险太平洋保险</p>
			<a href="" class="button"><span>></span>我要投保</a>
		</div>
	</div>
	<div class="insu_right insu-com">
		<h4>海运船舶保险</h4>
		<div>
			<p>提供上年保单，直享10-20%优惠，<span>还有超值0利息0手续活动，并且免一期保费，</span>开创全国首创。</p>
			<p class="insuP2">中国人保财险太平洋保险</p>
			<a href="" class="button"><span>></span>我要投保</a>
		</div>
	</div>
</div></div>

<!-- 页脚 -->
<site-footer></site-footer>

</body>

<?php echo '<script'; ?>
>
var data = <?php echo $_smarty_tpl->tpl_vars['todayData']->value;?>
;
var currUser = <?php echo $_smarty_tpl->tpl_vars['user']->value;?>
;
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
 src="/js/index.js"><?php echo '</script'; ?>
>

</html><?php }
}
