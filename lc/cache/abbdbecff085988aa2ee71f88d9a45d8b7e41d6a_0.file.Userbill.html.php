<?php
/* Smarty version 3.1.30, created on 2017-03-17 11:18:07
  from "F:\wwwroot\lc\application\modules\admin\views\Userbill.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58cb556f5c7020_80215801',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'abbdbecff085988aa2ee71f88d9a45d8b7e41d6a' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\modules\\admin\\views\\Userbill.html',
      1 => 1489717876,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58cb556f5c7020_80215801 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!doctype html>
<html ng-app="myapp">
<head>
    <meta charset="UTF-8">
    <title>客户流水</title>

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

</head>
<body ng-controller="mainCtrl" class="white-bg" style="overflow:hidden;padding:20px;">
<div class="animated fadeInRight">
    <div class="inquiry-btns">
        <a ng-click="add()" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i>新增</a>
        <a ng-click="del()" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i>删除</a>
        <a ng-click="refresh()" class="btn btn-info"><i class="glyphicon glyphicon-refresh"></i>刷新</a>
        <div class="input-group common-input-group">
            <input type="text" class="form-control search" ng-model="Name" onkeydown="if(event.keyCode==13) search.click()" placeholder="Enter键搜索用户">
            <span class="input-group-btn"><button ng-click="search()" id="search" class="btn btn-success">搜索</button></span>
        </div>
    </div>
    <ul class="pagination-sm page-ul"

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
        total-items="<?php echo $_smarty_tpl->tpl_vars['UserbillNum']->value;?>
">
    </ul>
</div>

<table class="datalist">
    <thead><tr>
        <td width="50">序号</td>
        <td width="30" ng-click="selectAll" title="选择全部/取消全部"><img src="/admin/images/checkbox.gif" alt=""></td>
        <td width="50">序列号</td>
        <td width="50">用户</td>
        <td width="50">交易内容</td>
        <td width="50">交易金额</td>
        <td width="50">交易后余额</td>
        <td width="50">流水来源</td>
        <td width="50">流水状态</td>
        <td width="50">发生时间</td>
        <td width="50">生效时间</td>
    </tr></thead>
    <tbody><tr ng-repeat="item in list track by $index" ng-click="showModal(item.ID,$event)">
        <td align="center">{{$index+1}}</td>
        <td align="center"><input type="checkbox" ng-check="item.checked ==1" ng-model="item.Checked"></td>
        <td align="center">{{item.RequestNo}}</td>
        <td align="center">{{item.Name}}</td>
        <td align="center">{{item.Subject}}</td>
        <td align="center">{{item.Amount}}</td>
        <td align="center">{{item.BeforeBalance}}</td>
        <td align="center">{{item.Source}}</td>
        <td align="center">{{item.State}}</td>
        <td align="center">{{item.CreateDate}}</td>
        <td align="center">{{item.UpdateDate}}</td>
    </tr></tbody>
</table>
</body>
<?php echo '<script'; ?>
>
    angular.module("myapp",["ui.bootstrap","ngAnimate","utils"]).controller("mainCtrl",function($scope,zhMsg)
    {
        $scope.pageChange = function()
        {   isSelectAll = false;
            getURL(utils.sprintf("/admin/home/getUserbill?page=%d&Name=%s",$scope.currentPage,$scope.Name.trim())).then(function(data)
            {   $scope.list = data;
                for(var i=0;i<data.length;i++)
                {
                    if(data[i].State == 0)  data[i].State = "等待确认";
                    if(data[i].State == 1)  data[i].State = "交易完成";
                    if(data[i].State == -1)  data[i].State = "交易失败";

                    if(data[i].Source == 10)    data[i].Source ="支付";
                    if(data[i].Source == 20)    data[i].Source ="退款";
                    if(data[i].Source == 30)    data[i].Source ="充值";
                }
            })
        }

        //================== 分页 ===================
        var isSelectAll = false;
        $scope.currentPage = 1;
        $scope.Name = "";
        $scope.pageChange();

        //搜索用户
        $scope.search = function()
        {   $scope.currentPage = 1;
            $scope.pageChange();
        }

        //刷新
        $scope.refresh = function() { window.location.reload();}

    })
<?php echo '</script'; ?>
>
</html><?php }
}
