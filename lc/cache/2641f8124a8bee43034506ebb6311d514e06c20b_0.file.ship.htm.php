<?php
/* Smarty version 3.1.30, created on 2017-03-17 11:37:31
  from "F:\wwwroot\lc\application\modules\admin\views\ship.htm" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58cb59fb5cd2b1_86780736',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2641f8124a8bee43034506ebb6311d514e06c20b' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\modules\\admin\\views\\ship.htm',
      1 => 1489721845,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58cb59fb5cd2b1_86780736 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html ng-app="myapp">
<head>
	<title>船舶列表</title>

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
 src="http://cdn.bootcss.com/angular.js/1.4.4/angular.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="http://cdn.bootcss.com/angular-ui-bootstrap/1.3.3/ui-bootstrap.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="http://cdn.bootcss.com/angular-ui-bootstrap/1.3.3/ui-bootstrap-tpls.min.js"><?php echo '</script'; ?>
>

	<!--- 自定义文件 --->
	<link href="/css/zh-msg.css" rel="stylesheet">

	<!--- 后台公共样式表 --->
	<link href="/admin/css/style.css" rel="stylesheet">

	<?php echo '<script'; ?>
 src="/js/utils.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="/js/syzxModule.js"><?php echo '</script'; ?>
>
</head>

<body ng-controller="mainCtrl" class="white-bg" style="overflow:hidden;padding:20px;">

<div class="animated fadeInRight" id="fade"></div>

<div style="float:left;margin-bottom:10px;">
	<button ng-click="add()" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i> 新增</button>
	<button ng-click="del()" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i> 删除</button>　
	<input type="text" ng-model="shipName" onkeydown="if(event.keyCode == 13) search.click();" placeholder="回车查找"
		style="height:33px;border-radius:5px;border:1px solid #ddd;vertical-align:middle;padding-left:5px;">
	<button ng-click="search()" id="search" class="btn btn-default" style="vertical-align:middle"><i class="glyphicon glyphicon-search"></i> 查找</button>
</div>

<ul style="margin:0px;float:right;margin-bottom:10px;font-family:宋体;"
	class="pagination-sm" 
	
	uib-pagination
	boundary-links="true"
	
	previous-text="<"
	next-text=">"
	first-text="|<"
	last-text=">|"
	
	ng-change="pageChange();"
	ng-model="currentPage"
	
	force-ellipses="true"
	rotate="true"
	items-per-page="<?php echo $_smarty_tpl->tpl_vars['ROWS_PER_PAGE']->value;?>
"
	max-size="5"
	total-items="<?php echo $_smarty_tpl->tpl_vars['ShipNumber']->value;?>
">
</ul>

<table class="datalist" ng-cloak>
<thead><tr>
	<td width="30"></td>
	<td width="30" ng-click="selectAll()" title="选择全部/取消选择"><img src="/admin/images/checkbox.gif"></td>
	<td width="70">海/河</td>
	<td>船名</td>
	<td width="100">类型</td>
	<td width="100">吨位</td>
	<td width="60">星级</td>
	<td width="60">长</td>
	<td width="60">宽</td>
	<td width="60">深</td>
	<td width="80">状态</td>
	<td width="80">船东</td>
	<td width="100">手机</td>
	<td width="200">操作</td>
</tr></thead>
<tbody><tr ng-repeat="item in list track by $index" ng-click="show(item.ID)" ng-class="{'deleted':item.IsDeleted==1}">
	<td align="center">{{item.ID}}</td>
	<td align="center"><input type="checkbox" ng-checked="item.Checked == 1" ng-model="item.Checked"></td>
	<td align="center" class="{{item.SeaOrRiver==1?'color-blue':'color-calm'}}">{{item.SeaOrRiver==1?"海运":"河运"}}
	<td>{{item.ShipName}}</td>
	<td>{{item.ShipTypeName}}</td>
	<td align="right">{{item.Tonnage | numberformat:1}}</td>
	<td align="center">{{item.Star | repeat:"*"}}</td>
	<td align="right">{{item.Long | numberformat:1}}</td>
	<td align="right">{{item.Width | numberformat:1}}</td>
	<td align="right">{{item.Deep | numberformat:1}}</td>
	<td align="center" class="color-{{item.StateColor}}">{{item.StateText}}</td>
	<td>{{item.Name}}</td>
	<td>{{item.MobilePhone}}</td>
	<td align="center" class="actmenu">
		<a href="">编辑</a>
		<a href="">删除</a>
		<a href="http://www.shipxy.com/Monitor?kw={{item.ShipName}}" target="_blank">定位</a>
		<a href="" ng-click="openSchDlg($index)">加船期</a>
	</td>
	
</tr></tbody>
</table>

</div>

<!--添加或编辑-->
<div class="modal fade in" id="addSchDlg" style="margin-top:150px"	aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" style="width:350px;"><div class="modal-content">
		<div class="modal-header" style="height:30px;padding-top:0px;">
			<button class="close" type="button" data-dismiss="modal" style="margin-top:-5px;font-size:32px;">×</button>
			<h4>发布船期</h4>
		</div>
		<div class="modal-body bgcolor-light" style="height:200px">
		<ul class="editform">
			<li><label>船舶名称<i></i><sup>*</sup></label>
				<b>{{sch.ShipName }}</b>
			</li>
			<li>
				<label>空船港口<i></i><sup>*</sup></label>
				<input id="clearPortText" type="text" ng-click="openPortDlg(this);" readonly ng-model="sch.ClearPortName">
			</li>
			<li>
				<label>空船日期<i></i><sup>*</sup></label>
				<input id="clearDateText" type="text" ng-click="openClearDateDlg(this);"  readonly  ng-model="sch.ClearDate">
			</li>
			<li>
				<label>船期备注<i></i></label>
				<input id="MemoEdit" type="text" ng-model="sch.Memo">
			</li>
		</ul>
		</div>
		<div class="modal-footer dalign-center">
			<button ng-click="saveSch()" class="btn btn-success w75">保存</button>
			<button class="btn w75" data-dismiss="modal">关闭</button>
		</div>
	</div></div>
</div>

</div>
</body>
</html>
<?php echo '<script'; ?>
>
angular.module("myapp",["ui.bootstrap","utils","syzx"]).controller("mainCtrl",function($scope,dlgPort)
{
	//获取指定页
	$scope.pageChange = function()
	{	isSelectedAll = false;
		getURL(utils.sprintf("/admin/ship/shipList?page=%d&ShipName=%s",$scope.currentPage,$scope.shipName.trim())).then(function(data)
		{	for(var i=0;i<data.length;i++)
			{	data[i].StateText = utils.idToText(data[i].CertifyState,CERTIFY_STATE_TEXT);
				data[i].StateColor = utils.idToText(data[i].CertifyState,CERTIFY_STATE_COLOR);
			}
			$scope.list = data; 
		}); 
	}
	$scope.search = function()
	{	$scope.currentPage = 1;
		$scope.pageChange();
	}

	//选择全部/取消选择
	$scope.selectAll = function()
	{	isSelectedAll = !isSelectedAll;
		for(p in $scope.list)	$scope.list[p].Checked = isSelectedAll;
	}

	//检查选择，显示警告框
	function confirm(action,fn)
	{
		var ids = [];

		for(var i=0;i<$scope.list.length;i++)	if($scope.list[i].Checked == 1)	ids.push($scope.list[i].ID);// += $scope.list[i].ID + ",";

		if(ids.length == 0)
		{	msgBox("请先选择。");
			return;
		}

		msgBox(action + "指定的记录，是否确定？",MSG_INFORMATION,MSG_CONFIRM).then(function(){fn(ids);});
	}

	//点击“删除”按钮
	$scope.del = function()
	{
		confirm("删除",function(value)
		{	getURL("/admin/news/del/?ID=" + value.join(",")).then(function() { $scope.pageChange(); });
		});
	}

	//点击行打开对话框
	$scope.show = function(id)
	{
		if(event.srcElement.tagName == "INPUT" || event.srcElement.tagName == "A" || event.srcElement.cellIndex == 11) return;
	}

	//点击“新增”按钮
	$scope.add = function()
	{
		$scope.sch = {ID:0,ShipID:'',Body:"",Image:""};
		editor.html("");
		$("#myModal").modal("show");
	}

	//点击“保存”按钮
	$scope.save = function()
	{
		var data = {
			ID 		: $scope.news.ID,
			Image 	: $("#headerImage").attr("src"),
			Subject	: $scope.news.Subject,
			Body	: editor.html()
		};
		postURL("/admin/news/save",data).then(function(data)
		{
			if($scope.news.ID == 0)
			{	$scope.pageChange();
			}
			else
			{	var idx = $scope.list.findBy("ID",$scope.news.ID);
				$scope.list[idx].Subject = $scope.news.Subject;
			}

			$("#myModal").modal("hide");
			msgBox("保存完成。")
		},
		function(err) { msgBox(err.message?err.message:err); });
	}

	//================== 船期 ==================
	$("#clearDateText").datepicker();
	$scope.openSchDlg = function(idx)
	{
		var ship = $scope.list[idx];

		$scope.sch = {
			UserID			: ship.UserID,
			UserName		: ship.UserName,
			ShipID			: ship.ID,
			ShipName		: ship.ShipName,
			ShipType		: ship.ShipTypeName,
			ClearPortID		: 0,
			ClearPortName	: "",
			ClearDate		: (new Date()).add("d",3).format("yyyy-mm-dd"),
			Memo			: ""
		};

		$("#addSchDlg").modal("show");
	}
	//打开港口选择对话框
	$scope.openPortDlg = function()
	{
		dlgPort.show($("#clearPortText"),{unLimited:0}).then(function(data)
		{	$scope.sch.ClearPortID = data.portID;
			$scope.sch.ClearPortName = data.portName;
		});
	}
	//保存船期
	$scope.saveSch = function()
	{	postURL("/admin/ship/savesch",$scope.sch).then(
			function()		{ msgBox("船期发布完成。");$("#addSchDlg").modal("hide");},
			function(err)	{ msgBox(getErrorMessage(err),MSG_ERROR); }
		);
	}
	//===================== 开始 ==================
	var isSelectedAll = false;
	$scope.currentPage = 1;		//初始当前页
	$scope.shipName = "";

	//获取数据
	$scope.pageChange();
});
<?php echo '</script'; ?>
><?php }
}
