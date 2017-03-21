<?php
/* Smarty version 3.1.30, created on 2017-03-10 08:30:15
  from "F:\wwwroot\lc\application\modules\admin\views\shipSch.htm" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58c1f397cc1269_21194661',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'cb6f987abcf06f853597a9b83a7cba932d69c22b' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\modules\\admin\\views\\shipSch.htm',
      1 => 1489051252,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58c1f397cc1269_21194661 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!doctype html>
<html ng-app="myapp">
<head>
	<meta charset="utf-8" />
	<title>船期</title>
	<!---
		<?php echo '<script'; ?>
 src="/admin/js/jquery-2.1.1.min.js"><?php echo '</script'; ?>
>
		<link rel="stylesheet" href="http://apps.bdimg.com/libs/jqueryui/1.10.4/css/jquery-ui.min.css">
		<?php echo '<script'; ?>
 src="http://apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js"><?php echo '</script'; ?>
>
		--->
	<link rel="stylesheet" href="http://apps.bdimg.com/libs/jqueryui/1.10.4/css/jquery-ui.min.css">
	<?php echo '<script'; ?>
 src="http://apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="http://apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js"><?php echo '</script'; ?>
>

	<!-- angularjs -->
	<?php echo '<script'; ?>
 src="/admin/js/angular.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="/admin/js/angular-animate.min.js"><?php echo '</script'; ?>
>
	<!--分页插件-->
	<?php echo '<script'; ?>
 src="/admin/kindeditor-4.1.7/angular-ui-bootstrap.js"><?php echo '</script'; ?>
>

	<link href="/admin/css/animate.min.css" rel="stylesheet">

	<!--- bootstrap --->
	<?php echo '<script'; ?>
 src="/admin/js/bootstrap.min.js"><?php echo '</script'; ?>
>
	<link href="/admin/css/bootstrap.min.css" rel="stylesheet">

	<!--- 后台公共样式表 --->
	<link href="/admin/css/style.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/css/common.css" />

	<!--- 原生JS与angularJS工具箱,工具箱通用对话框CSS --->
	<?php echo '<script'; ?>
 src="/js/utils.js"><?php echo '</script'; ?>
>
	<link href="/css/zh-msg.css" rel="stylesheet">
	<?php echo '<script'; ?>
 src="/js/syzxModule.js"><?php echo '</script'; ?>
>

	<style>
		.newseditform
		{	position:absolute;
			top:50%;
			left:50%;
			width:800px;
			height:640px;
			margin-left:-300px;
			margin-top:-400px;
			border:1px solid #ddd;
			background:#eee;
			visibility:hidden;
		}
		.newseditform #headbar { padding-left:10px;height:40px;line-height:40px;background:#1E90FF;color:white;font-size:18px;}
		.newseditform #editform { padding-left:20px;padding-right:20px; }
		.newseditform input[type=text] { width:730px; margin-bottom:10px;}
		input[type=checkbox]{width: 16px;height: 16px;}
		.newseditform textarea{width:730px;height:460px;visibility:hidden;}

		.clearDateDlg ul{background-color: #ddd;}

		input{border: 1px solid #ddd;}
		/*编辑框样式*/
		.contentStyle,.contentStyle p ,.contentStyle label{font-size: 16px;font-family: "宋体","Microsoft YaHei",Arial,sans-serif;}
		.contentStyle > div{margin-bottom: 10px;margin-left: -15px;height: 35px;} /* margin-left 不同不能合并*/
		.contentStyle input{height: 35px;border-radius: 3px;}
		.modal-body{height:420px;padding-left:30px;}
		.modal-body select{height: 33px;}
		/* 船东搜索框*/
		#inputSelect{width: 100%;display: block;}
		#inputSelect ul{position: absolute;top: 35px;right:0;z-index: 111;width: 248px;border: 1px solid #ccc;display: none;}
		#inputSelect li{background-color: #fff;padding: 4px;text-indent: 5px;}
		#inputSelect .inputLiAct{background-color: #ddd;}

		/*添加/编辑中多行文字对齐*/
		.contentStyle label{height:24px;line-height:24px;width:99px;text-align:justify;display:inline-block;overflow:hidden;vertical-align:top;margin: 6px 8px;}
		.contentStyle label i{display:inline-block;width:100%;height:0;}
		.contentStyle label span{color: red;position: absolute;top: 0;right: 6px;}
	</style>
</head>

<body ng-controller="mainCtrl as cBox" class="white-bg" style="overflow:hidden;padding:20px;">

<div class="animated fadeInRight" id="fade">

	<div style="float:left;margin-bottom:10px">
		<button ng-click="add()" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i> 新增</button>
		<button ng-click="search()" class="btn btn-warning"><i class="glyphicon glyphicon-search"></i> 查找</button>
		<button ng-click="del()" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i> 删除</button>
		<button ng-click="refresh()" class="btn btn-info"><i class="glyphicon glyphicon-refresh"></i> 刷新</button>
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
		total-items="shipschNumber">
	</ul>

	<table class="datalist">
		<thead><tr>
			<td width="50">序号</td>
			<td width="30" ng-click="selectAll()" title="选择全部/取消选择"><img src="/admin/images/checkbox.gif"></td>
			<td width="50">船东</td>
			<td width="100">船名</td>
			<td width="80">船舶类型</td>
			<td width="70">海/内河</td>
			<td width="95">吨位（吨）</td>
			<td width="100">空船港</td>
			<td width="100">空船日期</td>
			<td width="95">浏览量（次）</td>
			<td width="100">船期状态</td>
			<td width="60">备注</td>
		</tr></thead>
		<tbody><tr ng-repeat="item in list track by $index" ng-click="show(item.ID,$event)">
			<td align="center">{{item.ID}}</td>
			<td align="center"><input type="checkbox" ng-checked="item.Checked == 1" ng-model="item.Checked"></td>
			<td>{{item.UserName}}</td>
			<td align="right">{{item.ShipName}}</td>
			<td align="right">{{item.ShipType}}</td>
			<td align="right">{{item.SeaOrRiver == 0 ? "海运" : "河运" }}</td>
			<td align="right">{{item.Tonnage}}</td>
			<td align="right">{{item.ClearPortName}}</td>
			<td align="right">{{item.ClearDate | dateformat}}</td>
			<td align="right">{{item.Hits}}</td>
			<td align="center"><div>{{item.State}}</div></td>
			<td>{{item.Memo}}</td>
		</tr></tbody>
	</table>

</div>

<!--添加或编辑-->
<div class="modal dhide fade in" id="myModal" style="margin-top:20px"	aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" style="width:450px;"><div class="modal-content">
		<div class="modal-header" style="height:60px;padding-top:0px;">
			<button class="close" type="button" data-dismiss="modal" style="margin-top:5px;font-size:48px;">×</button>
			<h3>发布船期</h3>
		</div>
		<div class="modal-body">
			<form name="content" class="contentStyle" >
				<div class="col-md-12"	>
					<label class='col-sm-4'>船名<i></i><span>*</span></label>
					<div class="input-group col-sm-8">
						<input type="text" placeholder="请输入船名" class="form-control shipName" id="userSelectText" ng-change="showUserDlg(shipsch.ShipName)" ng-model="shipsch.ShipName" >
						<div id="inputSelect">
							<ul id="inputSelectList">
								<li ng-repeat="item in inputSelect track by $index" ng-click="inputSelectList($index)" tabindex="1">{{item.ShipName}}</li>
							</ul>
						</div>
					</div>
				</div>
				
				<div class="col-md-12"><label class='col-sm-4'>船东<i></i></label><input class='col-sm-8 userName' ng-model="shipsch.UserName" disabled ></div>
				<div class="col-md-12"><label class='col-sm-4'>船舶类型<i></i></label><input class='col-sm-8 shipType' ng-model="shipsch.ShipType" disabled></div>
				<div class="col-md-12"><label class='col-sm-4'>海/内河<i></i></label><input class='col-sm-8 seaOrRiver' ng-model="shipsch.SeaOrRiver" disabled></div>
				<div class="col-md-12"><label class='col-sm-4'>吨位(吨)<i></i></label><input class='col-sm-8 tonnage' ng-model="shipsch.Tonnage" disabled></div>
				<!--空船港-->
				<div class="col-md-12" id="portBox">
					<label class='col-sm-4'>空船港<i></i><span>*</span></label>
					<input class='col-sm-8 portEdit' id="clearPortText" type="text" ng-click="showPortDlg();" ng-model="shipsch.ClearPortName">
				</div>

				<div class="col-md-12 input" style="position: relative;" >
					<label class='col-sm-4'>空船日期<i></i><span>*</span></label>
					<input ng-click="showClearDateDlg();" class='col-sm-8 dateEdit'	id="clearDateText" type="text" ng-model="shipsch.ClearDate" ng-model="startTime">
					<div style="position: absolute;top: 35px;right: 26px;z-index: 1000;" class="clearDateDlg" tabindex=2><!--- 防止CSS影响日期选择器 --->
						<ul><li><div id="dlgClearDateFrom"></div></li></ul>
					</div>
				</div>
				<div class="col-md-12"><label class='col-sm-4'>备注<i></i></label><input class='col-sm-8 MemoEdit' type="text" ng-model="shipsch.Memo"></div>
			</form>
		</div>
		<div class="modal-footer">
			<button ng-click="save()" class="btn btn-success">保存</button>
			<button class="btn" data-dismiss="modal">关闭</button>
		</div>
	</div></div></div>

<!--搜索 start-->
<div class="modal dhide fade in" id="searchModal" style="margin-top:20px"	aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" style="width:450px;margin-top: 240px;"><div class="modal-content">
		<div class="modal-header" style="height:60px;padding-top:0px;">
			<button class="close" type="button" data-dismiss="modal" style="margin-top:5px;font-size:48px;">×</button>
			<h3>搜索船期</h3>
		</div>
		<div class="modal-body" style="height:120px;padding-left:30px;">
			<form name="content" class="contentStyle" >
				<select name="select" id="select_s1" class="col-sm-4" style="height: 33px;">
					<option>序号</option>
					<option>船东</option>
					<option>船舶类型</option>
					<option>海/内河</option>
					<option>吨位（吨）</option>
					<option>空船港</option>
					<option>空船日期</option>
					<option>船期状态</option>
				</select>
				<select name="select" id="select_s2" class="col-sm-3" style="height: 33px;">
					<option>等于</option>
					<option>大于</option>
					<option>小于</option>
				</select>
				<input style="height: 33px;border: 2px solid #ccc;" class="col-sm-4 searchInput" type="text" value="">
			</form>
		</div>
		<div class="modal-footer">
			<!--<button ng-click="reset()" class="btn btn-warning pull-left">重置</button>-->
			<button ng-click="searchConfirm()" class="btn btn-success">搜索</button>
			<button class="btn" data-dismiss="modal">关闭</button>
		</div>
	</div></div></div>
<?php echo '<script'; ?>
>

	var APP = angular.module("myapp", ["ui.bootstrap","ngAnimate","utils","syzx"]);

	APP.controller("mainCtrl",function($scope,$http,$timeout,dlgPort,zhMsg)
	{
		//获取指定页
		$scope.pageChange = function()
		{	isSelectedAll = false;
			getURL("/admin/home/getShipsch?page="+$scope.currentPage).then(function(res)
			{	$scope.list = res;
				for(var i=0;i<res.length;i++)
				{	if(res[i].State == 0) res[i].State = "发布中";
					if(res[i].State == 10) res[i].State = "生成订单";
					if(res[i].State == 20) res[i].State = "失效";
					if(res[i].SeaOrRiver == 0) res[i].SeaOrRiver = "河运";
					if(res[i].SeaOrRiver == 1) res[i].SeaOrRiver = "海运";
				}
			});
		}

		//======================= 空船港口选择对话框
		$scope.showPortDlg = function()
		{	dlgPort.show($("#portBox")).then(function(data)
		{	$scope.search.ClearPortID = data.portID;
			if(data.provinceName == data.regionName)
				$("#clearPortText").val(data.provinceName + " " + data.portName);
			else
				$("#clearPortText").val(data.provinceName + " " + data.regionName + " " + data.portName);
		});
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

		//点击搜索按钮
		$scope.search = function()
		{	$("#searchModal").modal("show");
		}

		$scope.searchConfirm = function()
		{	var searchText = $(".searchInput").val();
			var oText1 = $("#select_s1").find("option:selected").text();
			var oText2 = $("#select_s2").find("option:selected").text();
			if(oText2 == "等于")	oText2 = "=";
			if(oText2 == "大于")	oText2 = ">";
			if(oText2 == "小于")	oText2 = "<";
			var data = {
				option1 			: oText1,
				option2 			: oText2,
				text 				: $(".searchInput").val()
			};
			console.log(data)
			postURL("/admin/Shipsch/searchConfirm",data).then(function(data)
					{
//						var idx = $scope.list.findBy("ID",$scope.shipsch.ID);
//						$scope.list[idx].Subject = $scope.shipsch.Subject;
						$("#myModal").modal("hide");
//						msgBox("保存完成。")
					},
					function(err) { zhMsg.alert(err.message?err.message:err); });
		}

		//点击“删除”按钮
		$scope.del = function()
		{
			confirm("删除",function(value)
			{
				getURL("/admin/ShipSch/del/?ID=" + value.join(",")).then(function() { $scope.pageChange(); });
			});
		}

		//刷新
		$scope.refresh = function()
		{	window.location.reload();
		};
		//点击行打开对话框
		var clearPortName = "";
		$scope.show = function(id,$event)
		{
			//判断是否为火狐浏览器
			if (navigator.userAgent.indexOf("Firefox") >= 0)
			{	if($event.target.tagName == "INPUT") return;
			}
			else
			{	if(event.srcElement.tagName == "INPUT")	return;
			}
			getURL("/admin/home/GetOneShipSch/?ID=" + id).then(function(data)
			{	$scope.shipsch = data[0];
				//状态选择select
				$scope.states = [{state:'发布中',id:"0"},{state:'生成订单',id:"10"},{state:'失效',id:"20"}];
				$scope.selected2 = data[0].State;	//id的值，区分类型

				if(data[0].State == 0) data[0].State = "发布中";
				if(data[0].State == 10) data[0].State = "生成订单";
				if(data[0].State == 20) data[0].State = "失效";
				if(data[0].SeaOrRiver == 0) data[0].SeaOrRiver = "河运";
				if(data[0].SeaOrRiver == 1) data[0].SeaOrRiver = "海运";

				$("#myModal").modal("show");
			});
		};

		//=========================船名搜索 开始
		$scope.blur = true;
		$scope.showUserDlg = function(input)
		{
			getURL("/admin/ship/getShipByName?INPUT="+input).then(function(data)
			{	$scope.inputSelect = data;
				if(input != "")	$("#inputSelectList").slideDown();
				else	 			$("#inputSelectList").slideUp();
			});

			$scope.inputSelectList = function(index)
			{	var text = $("#inputSelectList li").eq(index).html();
				$("#userSelectText").val(text);
				$scope.index = index;
				relation(index);
				$("#inputSelectList").slideUp();
			}
		}

		//当船名改变时船东，船舶类型，海/河运，吨位都改变
		var relation = function(index)
		{	$scope.ship = $scope.inputSelect[index];
			//if($scope.inputSelect[index].SeaOrRiver == 0)	$scope.inputSelect[index].SeaOrRiver = "河运";
			//if($scope.inputSelect[index].SeaOrRiver == 0)	$scope.inputSelect[index].SeaOrRiver = "海运";
			$(".userName").val($scope.inputSelect[index].UserName);
			$(".shipType").val($scope.inputSelect[index].ShipTypeName);
			$(".seaOrRiver").val($scope.inputSelect[index].SeaOrRiver==1 ? "海运" : "河运");
			$(".tonnage").val($scope.inputSelect[index].Tonnage);
		}
		//=========================船名搜索 结束

		//点击“新增”按钮
		$scope.add = function()
		{
			$("#myModal").modal("show");
		}

		//点击“保存”按钮
		$scope.save = function()
		{
			var data = {
				UserID			: $scope.ship.UserID,
				UserName		: $scope.ship.UserName,
				ShipType		: $scope.ship.ShipTypeName,
				ClearPortID		: $(".portEdit").val(),
				ClearPortName	: $(".portEdit").val(),
				ClearDate		: $(".dateEdit").val(),
				Memo			: $(".MemoEdit").val()
			};
			postURL("/admin/ship/saveSch",data).then(function(data)
			{
				if($scope.shipsch.ID == 0)
				{	$scope.pageChange();
				}
				else
				{	var idx = $scope.list.findBy("ID",$scope.shipsch.ID);
					$scope.list[idx].UserName = $scope.shipsch.UserName;
					$scope.list[idx].ClearPortName = $(".portEdit").val();
					$scope.list[idx].ClearDate = $(".dateEdit").val();
					$scope.list[idx].State = 0;
					$scope.list[idx].Memo = $scope.shipsch.Memo;
				}

				$("#myModal").modal("hide");
				msgBox("保存完成。")
			},
			function(err) { zhMsg.alert(err.message?err.message:err); });
		}

		//===================== 开始 ==================
		var isSelectedAll = false;
		$scope.currentPage = 1;		//初始当前页
		$scope.shipschNumber = 0;

		//总记录数
		getURL("/admin/Shipsch/getCount").then(function(data) { $scope.shipschNumber = data;});

		//获取数据
		$scope.pageChange();

		//======================= 空船时间选择对话框相关函数 --- 开始 ========================
		$(".clearDateDlg").hide("fast");
		var clearDateTimer = null;
		$scope.showClearDateDlg = function()
		{
			$("#dlgClearDateFrom").datepicker({"onSelect":function(value,obj)
			{	$(this).fadeIn();
				$scope.search.ClearDateFrom = value;
				$(".clearDateDlg").hide("fast");
				$("#clearDateText").val($scope.search.ClearDateFrom);
			}});

			$(".clearDateDlg").show("fast").focus().unbind().bind("blur",function()
			{	//失去焦点，如果是点击日期，不关闭选择框，否则就关闭它
				var oldFrom = $scope.search.ClearDateFrom;

				$timeout.cancel(clearDateTimer);
				clearDateTimer = $timeout(function()
				{	//是点击了datapicker
					if(oldFrom != $scope.search.ClearDateFrom)
						$(".clearDateDlg").focus();
					else
						$(".clearDateDlg").hide("fast");
				},200);
			});

			$("#dlgClearDateFrom").datepicker("setDate",$scope.search.ClearDateFrom);
			//showButtonPanel: true,
		}
		//======================= 空船时间选择对话框相关函数 --- 结束 ========================


		//=============键盘事件函数 开始========
		$(function () {
			var input = $("#userSelectText");
			var suggestions = $("#inputSelectList");
			var currentindex = -1;
			input.keyup(function (evn) {
				var keyBol = $("#inputSelectList").css("display") == "block";
				if (evn.keyCode == 38 && keyBol) {
					movethis(true);
				}
				else if (evn.keyCode == 40 && keyBol) {
					movethis(false);
				}
				else if (evn.keyCode == 13) {
					relation(currentindex);
					$("#inputSelectList").slideUp();
				}
			});
			var movethis = function(up)
			{
				var list = $("#inputSelectList li")
				var size = list.length;
				var textvalue = $(list[currentindex+1]).text();

				currentindex = currentindex + (up ? -1 : 1);
				if(currentindex >= size) currentindex = 0;
				if(currentindex < 0 ) currentindex = size;

				list.removeClass("inputLiAct");
				$(list[currentindex]).addClass("inputLiAct");
				$("#userSelectText").val(textvalue);
			}

			//鼠标滑过事件
			$("#inputSelectList").mouseover(function () {
				$("#inputSelectList li").mouseover(function () {
					$(this).addClass("inputLiAct");
					currentindex = $("#inputSelectList li").index(this);
				}).mouseout(function () {
					$(this).removeClass("inputLiAct");
				});
			});

		})
		//=============键盘事件函数 结束========

	});
<?php echo '</script'; ?>
>

</body>
</html>

<?php }
}
