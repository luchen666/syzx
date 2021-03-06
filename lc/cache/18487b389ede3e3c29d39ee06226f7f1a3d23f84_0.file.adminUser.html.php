<?php
/* Smarty version 3.1.30, created on 2017-03-09 17:09:54
  from "F:\wwwroot\lc\application\modules\admin\views\adminUser.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58c11be22b4ac6_47895996',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '18487b389ede3e3c29d39ee06226f7f1a3d23f84' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\modules\\admin\\views\\adminUser.html',
      1 => 1489049403,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58c11be22b4ac6_47895996 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!doctype html>
<html ng-app="myapp">
<head>
<meta charset="utf-8" />
<title>后台用户管理</title>

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

<div class="animated fadeInRight" id="fade">

<div style="float:left;margin-bottom:10px">
	<button ng-click="add()" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i> 新增</button>
	<button ng-click="del()" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i> 删除</button>
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
	total-items="totalNumber">
</ul>

<table class="datalist">
<thead><tr>
	<td width="30"></td>
	<td width="30" style="padding:0px" ng-click="selectAll()" title="选择全部/取消选择"><img src="/admin/images/checkbox.gif"></td>
	<td width="120">帐号</td>
	<td width="200">姓名</td>
	<td width="150">部门</td>
	<td width="60">级别</td>
	<td width="180">最近登录</td>
	<td width="120">登录IP</td>
	<td width="100">次数</td>
	<td width="100">序号</td>
	<td width="180">创建时间</td>
	<td width="60">状态</td>
	<td>操作</td>
</tr></thead>
<tbody><tr ng-repeat="item in list track by $index" ng-click="show(item.UserID)">
	<td align="center">{{$index+1}}</td>
	<td align="center"><input type="checkbox" ng-checked="item.Checked == 1" ng-model="item.Checked"></td>
	<td>{{item.Account}}</td>
	<td>{{item.Name}}</td>
	<td>{{item.Dept}}</td>
	<td align="center">{{item.Level}}</td>
	<td>{{item.LastLoginDate | dateformat:"yyyy-MM-dd HH:mm:ss" }}</td>
	<td>{{item.LastLoginIP }}</td>
	<td align="right">{{item.LoginCount }}</td>
	<td align="right">{{item.SortNo }}</td>
	<td>{{item.CreateDate | dateformat:"yyyy-MM-dd HH:mm:ss" }}</td>
	<td align="center"><div class="{{item.State==1?'ok':'cancel'}}"></div></td>
	<td></td>
</tr></tbody>
</table>
</div>

<div class="modal fade in" id="editForm" style="margin-top:100px"  aria-hidden="true" data-backdrop="static">
<div class="modal-dialog" style="width:400px;"><div class="modal-content">
	<div class="modal-header" style="height:60px;padding-top:0px;">
		<button class="close" type="button" data-dismiss="modal" style="margin-top:5px;font-size:48px;">×</button>
		<h3>{{news.ID == 0 ? "添加" : "编辑"}}管理员</h3>
	</div>
	<div class="modal-body" style="height:450px;padding-left:30px;">
		<ul class="editform">
		<li><label>登录帐号</label>
			<input type="text" ng-model="user.Account">
		</li>
		<li><label>登录密码</label>
			<input type="password" ng-model="user.Password">
		</li>
		<li><label>重复密码</label>
			<input type="password" ng-model="user.RePWD">
		</li>
		<li><label>用户姓名</label>
			<input type="text" ng-model="user.Name">
		</li>
		<li><label>所属部门</label>
			<input type="text" ng-model="user.Dept">
		</li>
		<li><label>后台级别</label>
			<select ng-model="user.Level">
			<option value="1" ng-selected="user.Level==1">1-操作</option>
			<option value="2" ng-selected="user.Level==2">2-业务</option>
			<option value="3" ng-selected="user.Level==3">3-财务</option>
			<option value="4" ng-selected="user.Level==4">4-经理</option>
			<option value="5" ng-selected="user.Level==5">5-管理员</option>
			</select>
		</li>
		<li><label>序　　号</label>
			<input type="number" ng-model="user.SortNo">
		</li>
		<li><label>帐号状态</label>
			<select ng-model="user.State">
			<option value="1" ng-selected="user.State==1">有效</option>
			<option value="0" ng-selected="user.State==0">无效</option>
			</select>
		</li>
		</ul>
		
	</div>
	<div class="modal-footer">
		<button ng-click="save()" class="btn btn-success">保存</button> 
		<button class="btn" data-dismiss="modal">关闭</button> 
	</div>
</div></div></div>


<?php echo '<script'; ?>
>
var URL_LIST = "/admin/adminUser/list?page=";
var URL_GET = "/admin/adminUser/get/?id=";
var URL_DEL = "/admin/adminUser/del/?id=";
var URL_SAVE = "/admin/adminUser/save";
var URL_COUNT = "/admin/adminUser/count";

angular.module("myapp",["ui.bootstrap","utils"]).controller("mainCtrl",function($scope)
{
	//获取指定页
	$scope.pageChange = function()
	{	isSelectedAll = false;
		getURL(URL_LIST+$scope.currentPage).then(function(data) { $scope.list = data; }); 
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
		
		for(var i=0;i<$scope.list.length;i++)	if($scope.list[i].Checked == 1)	ids.push($scope.list[i].UserID);// += $scope.list[i].ID + ",";
		
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
		{	getURL(URL_DEL + value.join(",")).then(function() { $scope.pageChange(); });
		});
	}
	
	//点击行打开对话框
	$scope.show = function(id)
	{	
		if(event.srcElement.tagName == "INPUT" || event.srcElement.cellIndex == 11)		return;
		
		getURL(URL_GET + id).then(function(data) 
		{	data.Password = "********";
			data.RePWD = "********";
			data.SortNo = parseInt(data.SortNo);
			$scope.user = data; 
			$("#editForm").modal("show"); 
		});
	}
	
	//点击“新增”按钮
	$scope.add = function()
	{	$scope.user = {UserID:0,State:1,Level:0,SortNo:0};
		$("#editForm").modal("show");
	}
	
	//点击“保存”按钮
	$scope.save = function()
	{
		console.log($scope.user);
		
		if(!$scope.user.Name || $scope.user.Name == "" || !$scope.user.Dept || $scope.user.Dept == "")
		{	msgBox("请填写完整帐号资料。",MSG_ERROR);
			return;
		}
		
		if(!$scope.user.Account || $scope.user.Account.length < 3)
		{	msgBox("帐号不能少于3个字符",MSG_ERROR);
			return;
		}

		if(!($scope.user && $scope.user.Password && $scope.user.RePWD && $scope.user.Password != "" && 
			$scope.user.Password.length >= 4 && $scope.user.Password == $scope.user.RePWD))
		{
			msgBox("登录密码与确认密码不符，请重输。",MSG_ERROR);
			return;
		}
		
		postURL(URL_SAVE,$scope.user).then(function(data)
		{
			if($scope.user.UserID == 0)
			{	$scope.pageChange();
			}
			else
			{	var idx = $scope.list.findBy("UserID",$scope.user.UserID);
				$scope.list[idx] = $scope.user;
			}
			
			$("#editForm").modal("hide");
			msgBox("保存完成。")
		},
		function(err) { msgBox(getErrorMessage(err),MSG_ERROR); });
	}
	
	//===================== 开始 ==================
	var isSelectedAll = false;
	$scope.currentPage = 1;		//初始当前页
	$scope.totalNumber = 0;
	
	//总记录数
	getURL(URL_COUNT).then(function(data) { $scope.totalNumber = data; }); 
	
	//获取数据
	$scope.pageChange();
	
});
<?php echo '</script'; ?>
>

</body>
</html><?php }
}
