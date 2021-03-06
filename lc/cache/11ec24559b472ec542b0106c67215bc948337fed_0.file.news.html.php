<?php
/* Smarty version 3.1.30, created on 2017-03-14 17:08:05
  from "F:\wwwroot\lc\application\modules\admin\views\news.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58c7b2f56c14d9_23776239',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '11ec24559b472ec542b0106c67215bc948337fed' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\modules\\admin\\views\\news.html',
      1 => 1489482205,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58c7b2f56c14d9_23776239 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!doctype html>
<html ng-app="myapp">
<head>
<meta charset="utf-8" />
<title>航线新闻</title>

<?php echo '<script'; ?>
 src="/admin/js/jquery-2.1.1.min.js"><?php echo '</script'; ?>
>

<!-- angularjs -->
<?php echo '<script'; ?>
 src="/admin/js/angular.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="/admin/js/angular-animate.min.js"><?php echo '</script'; ?>
>

<link rel="stylesheet" href="/admin/kindeditor-4.1.7/themes/default/default.css" />
<link rel="stylesheet" href="/admin/kindeditor-4.1.7/plugins/code/prettify.css" />
<?php echo '<script'; ?>
 src="/admin/kindeditor-4.1.7/kindeditor.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="/admin/kindeditor-4.1.7/lang/zh_CN.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="/admin/kindeditor-4.1.7/plugins/code/prettify.js"><?php echo '</script'; ?>
>
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


<!--- 原生JS与angularJS工具箱,工具箱通用对话框CSS --->
<?php echo '<script'; ?>
 src="/js/utils.js"><?php echo '</script'; ?>
>
<link href="/css/zh-msg.css" rel="stylesheet">

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
.newseditform textarea{width:730px;height:460px;visibility:hidden;}
</style>
</head>

<body ng-controller="mainCtrl" class="white-bg" style="overflow:hidden;padding:20px;">

<div class="animated fadeInRight" id="fade">

<div style="float:left;margin-bottom:10px">
	<button ng-click="add()" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i> 新增</button>
	<button ng-click="set(1)" class="btn btn-warning"><i class="glyphicon glyphicon-ok"></i> 上线</button>
	<button ng-click="set(0)" class="btn btn-warning"><i class="glyphicon glyphicon-remove"></i> 下线</button>
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
	total-items="newsNumber">
</ul>

<table class="datalist">
<thead><tr>
	<td width="30"></td>
	<td width="30" ng-click="selectAll()" title="选择全部/取消选择"><img src="/admin/images/checkbox.gif"></td>
	<td>标题</td>
	<td width="50">预览</td>
	<td width="70">阅读数</td>
	<td width="100">发布人</td>
	<td width="100">时间</td>
	<td width="60">状态</td>
</tr></thead>
<tbody><tr ng-repeat="item in list track by $index" ng-click="show(item.ID)">
	<td align="center">{{$index+1}}</td>
	<td align="center"><input type="checkbox" ng-checked="item.Checked == 1" ng-model="item.Checked"></td>
	<td>{{item.Subject}}</td>
	<td align="center"><i class="glyphicon glyphicon-play color-orange"></i></td>
	<td align="right">{{item.Hits}}</td>
	<td>{{item.Name}}</td>
	<td>{{item.CreateDate | dateformat}}</td>
	<td align="center"><div class="{{item.State==1?'ok':'cancel'}}"></div></td>
</tr></tbody>
</table>

</div>

<div class="modal fade in" id="myModal" style="margin-top:-10px"  aria-hidden="true" data-backdrop="static">
<div class="modal-dialog" style="width:800px;"><div class="modal-content">
	<div class="modal-header" style="height:60px;padding-top:0px;">
		<button class="close" type="button" data-dismiss="modal" style="margin-top:5px;font-size:48px;">×</button>
		<h3>{{news.ID == 0 ? "添加" : "编辑"}}新闻</h3>
	</div>
	<div class="modal-body" style="height:500px;padding-left:30px;">
		<form name="content">
		<input type="text" placeholder="请输入新闻标题" ng-model="news.Subject" style="width:740px;margin-bottom:10px;">
		<textarea id="newsBody" name="content" style="width:740px;height:430px">{{ news.Body }}</textarea>
		</form>
	</div>
	<div class="modal-footer">
		<img id="headerImage" style="float:left;width:50px;height:50px" src="{{news.Image}}" err-src="/admin/images/defaultNewsImage.png">
		<button class="btn" style="float:left;margin-left:20px" id="btnImage">选压题图</button> 
		<button ng-click="save()" class="btn btn-success">保存</button> 
		<button class="btn" data-dismiss="modal">关闭</button> 
	</div>
</div></div></div>

<?php echo '<script'; ?>
>
var editor = null;
KindEditor.ready(function(K)
{
	editor = K.create("textarea[name=content]",
	{	urlType			: "domain",
		cssPath			: "/admin/kindeditor-4.1.7/plugins/code/prettify.css",
		resizeType		: 0,
		uploadJson		: "/admin/news/upload",
		fileManagerJson : "/admin/news/fileManager",
		allowFileManager: true,
		/*
		afterCreate		: function()
						{	//var self = this;
							//K.ctrl(document, 13, function(){ this.sync(); K("form[name=content]")[0].submit(); });
							//K.ctrl(self.edit.doc, 13, function() { this.sync(); K("form[name=content]")[0].submit(); });
						},
		afterBlur		: function(){ this.sync(); }
		*/
	});

	prettyPrint();
	
	//压题图选择
	K("#btnImage").click(function()
	{
		editor.loadPlugin('image', function()
		{
			editor.plugin.imageDialog(
			{	imageUrl :	K("#headerImage").attr("src"),
				clickFn	 :	function(url, title, width, height, border, align)
							{	K("#headerImage").attr("src",url);
								editor.hideDialog();
							}
			});
		});
	});
});

var APP = angular.module("myapp", ["ui.bootstrap","ngAnimate","utils"]);

APP.controller("mainCtrl",function($scope,zhMsg)
{
	//获取指定页
	$scope.pageChange = function()
	{	isSelectedAll = false;
		getURL("/admin/news/list?page="+$scope.currentPage).then(function(data) { $scope.list = data; }); 
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
		{	zhMsg.alert("请先选择。");
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
	
	//点击“上线、下线”按钮
	$scope.set = function(state)
	{
		confirm(state?"上线":"下线",function(value)
		{
			getURL(utils.sprintf("/admin/news/setState/?ID=%s&State=%d",value.join(","),state)).then(function()
			{	for(var i=0;i<value.length;i++)
				{	var p = $scope.list.findBy("ID",value[i]);
					if(p > -1)
					{	$scope.list[p].State = state;
						$scope.list[p].Checked = false;
					}
				}
			});
		});
	}
	
	//点击行打开对话框
	$scope.show = function(id)
	{
		if(event.srcElement.tagName == "INPUT")	return;
		
		if(event.srcElement.tagName == "I" || event.srcElement.cellIndex == 3)
		{
			window.open("/news/show?IgnoreHit=1&ID="+id);
			return;
		}
		
		getURL("/admin/news/get/?ID=" + id).then(function(data)
		{	$scope.news = data; 
			editor.html(data.Body);
			prettyPrint();
			$("#myModal").modal("show");
		});
	}
	
	//点击“新增”按钮
	$scope.add = function()
	{
		$scope.news = {ID:0,Suject:"",Body:"",Image:""};
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
		function(err) { zhMsg.alert(err.message?err.message:err); });
	}
	
	//===================== 开始 ==================
	var isSelectedAll = false;
	$scope.currentPage = 1;		//初始当前页
	$scope.newsNumber = 0;
	
	//总记录数
	getURL("/admin/news/getCount").then(function(data) { $scope.newsNumber = data; }); 
	
	//获取数据
	$scope.pageChange();
});
<?php echo '</script'; ?>
>

</body>
</html>

<?php }
}
