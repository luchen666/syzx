<?php
/* Smarty version 3.1.30, created on 2017-03-10 15:32:57
  from "F:\wwwroot\lc\application\modules\admin\views\Port.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58c256a99ef5a8_24659245',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f4d47fe7c6c69777bd3ab20f126f8576ecd7481f' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\modules\\admin\\views\\Port.html',
      1 => 1489131171,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58c256a99ef5a8_24659245 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!doctype html>
<html ng-app="myapp">
<head>
    <meta charset="UTF-8">
    <title>港口列表</title>

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
        .bodyStyle{overflow:hidden;padding:20px;}

    </style>
</head>
<body ng-controller="myCtrl" class="white-bg bodyStyle">
<div class="animated fadeInRight">
    <div>
        <button ng-click="add()" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i>新增</button>
        <button ng-click="del()" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i>删除</button>
        <button ng-click="refresh()" class="btn btn-info"><i class="glyphicon glyphicon-refresh"></i>刷新</button>
        <div class="input-group common-input-group">
            <input type="text" class="form-control search" ng-model="PortName" onkeydown="if(event.keyCode == 13) search.click();" placeholder="按Enter键搜索">
            <span class="input-group-btn"><button ng-click="search()" id="search" class="btn btn-default" type="button">搜索</button></span>
        </div>
    </div>
    <ul class="pagination page-ul"
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
        total-items="portNum"
    ></ul>

    <table class="datalist">
        <thead><tr>
            <td width="50">序号</td>
            <td width="30" ng-click="selectAll()" title="选择全部/取消全部"><img src="/admin/images" /></i></td>
            <td width="80">港口名称</td>
            <td width="80">拼音</td>
            <td width="100">描述</td>
        </tr></thead>
        <tbody><tr ng-repeat="item in list track by $index" ng-click="show(item.ID,$event)">
            <td align="center">{{item.ID}}</td>
            <td align="center"><input type="checkbox" ng-check="item.Checked ==1" ng-model="item.Checked"></td>
            <td>{{item.PortName}}</td>
            <td>{{ item.Spell }}</td>
            <td>{{item.Description}}</td>
        </tr></tbody>
    </table>

</div>
</body>
<?php echo '<script'; ?>
>
    var APP = angular.module("myapp",["ui.bootstrap","ngAnimate","utils","syzx"]);
    APP.controller("myCtrl",function($scope,$http,$timeout,dlgPort,zhMsg)
    {
        //获取指定页
        $scope.pageChange = function()
        {   isSelectedAll = false;
            getURL("/admin/port/getPortList?page="+$scope.currentPage).then(function(res)
            {   $scope.list = res;
            })
        }
        //============== 页面 ==================
        var isSelectAll = false;
        $scope.currentPage = 1;
        $scope.portNum = 0;
        $scope.pageChange();

        //==========全选、反选
        $scope.selectAll = function()
        {   isSelectAll = !isSelectAll;
            for(p in $scope.list)   $scope.list[p].Checked = isSelectAll;
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

        //点击 删除 按钮
        $scope.del = function()
        {   confirm("删除",function(value)
        {   getURL("/admin/port/del/?ID="+value.join(",")).then(function(){ $scope.pageChange();})  })
        }

        //刷新
        $scope.refresh = function() {window.location.reload();}

        //港口搜索
        $scope.search = function()
        {   $scope.currentPage = 1;
            $scope.pageChange();
        }




    })
<?php echo '</script'; ?>
>
</html><?php }
}
