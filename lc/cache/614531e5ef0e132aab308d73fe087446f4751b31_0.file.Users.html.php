<?php
/* Smarty version 3.1.30, created on 2017-03-21 08:53:41
  from "F:\wwwroot\lc\application\modules\admin\views\Users.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58d07995e7bde2_72511886',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '614531e5ef0e132aab308d73fe087446f4751b31' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\modules\\admin\\views\\Users.html',
      1 => 1490057619,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58d07995e7bde2_72511886 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!doctype html>
<html ng-app="myapp">
<head>
    <meta charset="UTF-8">
    <title>用户列表</title>
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
    <style>
        .editform .select{height: 26px;margin-left: -4px;}
    </style>
</head>
<body ng-controller="mainCtrl" class="white-bg" style="overflow: hidden;padding:20px;">
<div class="animated fadeInRight">
    <div class="inquiry-btns">
        <button ng-click="add()" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i>新增</button>
        <button ng-click="del()" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i>删除</button>
        <button ng-click="refresh()" class="btn btn-info"><i class="glyphicon glyphicon-refresh"></i>刷新</button>
        <div class="input-group common-input-group">
            <input type="text" class="form-control search" ng-model="Name" onkeydown="if(event.keyCode == 13) search.click()" placeholder="按Enter键搜索">
            <span class="input-group-btn"><button ng-click="search()" id="search" class="btn btn-success">搜索</button></span>
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
        total-items="<?php echo $_smarty_tpl->tpl_vars['UsersNum']->value;?>
"
    ></ul>
</div>

<table class="datalist">
    <thead><tr>
        <td width="50">序号</td>
        <td width="30" ng-click="selectAll" title="选择全部/取消全部"><img src="/admin/images/checkbox.gif" ></td>
        <td width="80">用户账号</td>
        <td width="100">用户类型</td>
        <td width="100">用户名</td>
        <td width="80">用户手机号</td>
        <td width="100">用户性别</td>
        <td width="100">所属公司</td>
        <td width="60">职位</td>
        <td width="60">认证状态</td>
    </tr></thead>
    <tbody><tr ng-repeat="item in list track by $index" ng-click="showModal(item.UserID,$event)">
        <td align="center">{{item.UserID}}</td>
        <td align="center"><input type="checkbox" ng-check="item.Checked ==1" ng-model="item.Checked"></td>
        <td align="center">{{item.Account}}</td>
        <td align="center">{{item.UserType}}</td>
        <td align="center">{{item.Name}}</td>
        <td align="center">{{item.MobilePhone}}</td>
        <td align="center">{{item.Sex == 0 ? "男":"女";}}</td>
        <td align="center">{{item.companyName}}</td>
        <td align="center">{{item.Duty}}</td>
        <td align="center">{{item.CertifyState}}</td>
    </tr></tbody>
</table>

<div class="modal fade in" id="companyModal" style="" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 400px;margin: 130px auto;">
            <div class="modal-header" style="height: 60px;">
                <button class="close" type="button" data-dismiss="modal" style="margin-top: -12px;font-size: 48px;">×</button>
                <h4>{{user.UserID==0 ? "增加":"编辑";}}用户</h4>
            </div>
            <div class="modal-body bgcolor-light" style="padding: 0 45px;">
                <ul class="editform" style="margin: auto;">
                    <li ng-if="user.UserID !=0 "><label>用户账号 <sup>*</sup></label><b>{{user.Account}}</b></li>
                    <li ng-if="user.UserID == 0"><label>用户账号 <sup>*</sup></label><input type="text" ng-blur="checkName()" ng-model="user.Account"></li>
                    <li><label>用户类型</label>
                        <select class="select" id="s_UserType" ng-model="s_UserType" ng-options="s.id as s.UserType for s in UserType"></select>
                    </li>
                    <li><label>用户姓名 </label><input type="text" ng-model="user.Name"></li>
                    <li><label>手机号码 <sup>*</sup></label><input type="text" ng-model="user.MobilePhone"></li>
                    <li><label>性 &nbsp; &nbsp; &nbsp; 别</label>
                        <input type="radio" value="0" id="man" ><label for="man">男</label>
                        <input type="radio" value="1" id="woman" ><label for="woman">女</label>
                    </li>
                    <li><label>所属公司</label><input type="text" ng-model="user.companyName"></li>
                    <li><label>职 &nbsp; &nbsp; &nbsp; 位</label><input type="text" ng-model="user.Duty"></li>
                    <li><label>认证状态</label>
                        <select class="select" id="s_state" ng-model="s_State" ng-options="s.id as s.certify for s in CertifyState"></select>
                    </li>
                </ul>
            </div>
            <div class="modal-footer" style="padding: 8px;">
                <button ng-click="saveCompany()" class="btn btn-success">保存</button>
                <button class="btn" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
</body>
<?php echo '<script'; ?>
>
    var APP = angular.module("myapp",["ui.bootstrap","ngAnimate","utils"]);
    APP.controller("mainCtrl",function($scope,$http,$timeout,zhMsg)
    {
        $scope.pageChange = function()
        {   isSelectAll = false;
            getURL(utils.sprintf("/admin/user/getUsers?page=%d&Name=%s",$scope.currentPage,$scope.Name.trim())).then(function(data)
            {   $scope.list = data;
                for(var i=0;i<data.length;i++)
                {
                    if(data[i].UserType == "HZ")   data[i].UserType = "货主";
                    if(data[i].UserType == "CD")   data[i].UserType = "船东";
                    if(data[i].UserType == "DL")   data[i].UserType = "代理";

                    if(data[i].CertifyState == 0)   data[i].CertifyState = "未经审核";
                    if(data[i].CertifyState == 10)   data[i].CertifyState = "提交审核";
                    if(data[i].CertifyState == 20)   data[i].CertifyState = "审核未过";
                    if(data[i].CertifyState == 30)   data[i].CertifyState = "审核通过";
                }
            })
        }

        //============分页==============
        var isSelectAll = false;
        $scope.currentPage = 1;
        //必须加，否则会报trim undefined错
        $scope.Name = "";
        $scope.pageChange();

        //=========全选、反选
        $scope.selectAll = function()
        {   isSelectAll = !isSelectAll;
            for(p in $scope.list) $scope.list[p].Checked = isSelectAll;
        }

        //搜索
        $scope.search = function()
        {   $scope.currentPage = 1;
            $scope.pageChange();
        }
        //刷新
        $scope.refresh = function() {window.location.reload();}

        //检查选择，显示警告框
        function confirm(action,fn)
        {
            var ids = [];
            for(var i=0;i<$scope.list.length;i++)	if($scope.list[i].Checked == 1)	ids.push($scope.list[i].UserID);
            if(ids.length == 0)
            {	msgBox("请先选择。");
                return;
            }
            msgBox(action + "指定的记录，是否确定？",MSG_INFORMATION,MSG_CONFIRM).then(function(){fn(ids);});
        }
        //删除
        $scope.del = function()
        {   confirm("删除",function(value)
            {   getURL("/admin/user/delUsers/?UserID="+value.join(",")).then(function(data){$scope.pageChange();})
            })
        }

        //点击打开对话框
        $scope.showModal = function(id,$event)
        {   //判断是否为火狐浏览器
            if (navigator.userAgent.indexOf("Firefox") >= 0)
            {   if($event.target.tagName == "INPUT") return;
            }
            else if(event.srcElement.tagName == "INPUT")	return;

            getURL("/admin/user/getOneUser?UserID="+id).then(function(data)
            {   $scope.user = data[0];

                if(data[0].Sex == 0)    $scope.user.Sex = "男";
                if(data[0].Sex == 1)    $scope.user.Sex = "女";
                $scope.UserType = [{UserType:"货主",id:"HZ"},{UserType:"船东",id:"CD"},{UserType:"代理",id:"DL"}];
                $scope.s_UserType = data[0].UserType;
                $scope.CertifyState = [{certify:"未经审核",id:"0"},{certify:"提交审核",id:"10"},{certify:"审核未过",id:"20"},{certify:"审核通过",id:"30"}];
                $scope.s_State = data[0].CertifyState;
                $("#companyModal").modal("show");
            })
        }

        //检查公司是否已经注册
        $scope.checkName = function()
        {
            getURL("/admin/company/checkName?Name="+$scope.user.Name).then(function(data)
            {if(data == 1)  {msgBox("该公司已注册")}
            })
        }
        //保存数据
        $scope.saveCompany = function()
        {
            data = {
                UserID      :   $scope.user.UserID,
                UserType    :   $scope.user.UserType,
                Name         :  $scope.user.Name ,
                MobilePhone :  $scope.user.MobilePhone ,
                Sex          :  $scope.user.Sex ,
                companyName :  $scope.user.companyName ,
                Duty        :  $scope.user.Duty ,
                CertifyState: $scope.s_State
            }
            console.log(data);
            return;
            if(data["Name"] == "")  {msgBox("请您填写公司名称");return;}
            if(data["Address"] == "")  {msgBox("请您填写公司地址");return;}
            if(data["VIP"] == -1)  {msgBox("请您选择客户类型");return;}

            postURL("/admin/user/save",data).then(function(data)
            {
                $scope.pageChange();
                $("#companyModal").modal("hide");
                msgBox("保存完成");
            },function(err){ zhMsg.alert(err.message?err.message:err);})
        }
    })
<?php echo '</script'; ?>
>
</html><?php }
}
