<?php
/* Smarty version 3.1.30, created on 2017-03-20 12:26:16
  from "F:\wwwroot\lc\application\modules\admin\views\Companies.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58cf59e802ce80_55954805',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '79f6a0546067a857124d7152909431e54fa76397' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\modules\\admin\\views\\Companies.html',
      1 => 1489976128,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58cf59e802ce80_55954805 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!doctype html>
<html ng-app="myapp">
<head>
    <meta charset="UTF-8">
    <title>公司列表</title>
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
            total-items="<?php echo $_smarty_tpl->tpl_vars['companyNum']->value;?>
"
        ></ul>
    </div>

    <table class="datalist">
        <thead><tr>
            <td width="50">序号</td>
            <td width="30" ng-click="selectAll" title="选择全部/取消全部"><img src="/admin/images/checkbox.gif" ></td>
            <td width="80">公司名称</td>
            <td width="100">公司地址</td>
            <td width="80">客户类型</td>
            <td width="100">组织机构代码</td>
            <td width="100">认证状态</td>
        </tr></thead>
        <tbody><tr ng-repeat="item in list track by $index" ng-click="showModal(item.ID,$event)">
            <td align="center">{{item.ID}}</td>
            <td align="center"><input type="checkbox" ng-check="item.Checked ==1" ng-model="item.Checked"></td>
            <td align="center">{{item.Name}}</td>
            <td align="center">{{item.Address}}</td>
            <td align="center">{{item.VIP == 1 ? "VIP客户":"普通客户";}}</td>
            <td align="center">{{item.Code}}</td>
            <td align="center"><span style="padding: 3px 8px;border-radius: 3px;outline: none;" ng-class="{0:'btn-default',10:'btn-info',20:'btn-warning',30:'btn-success'}[{{item.CertifyState}}]">{{item.CertifyStateF}}</span></td>
        </tr></tbody>
    </table>

    <div class="modal fade in" id="companyModal" style="" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content" style="width: 400px;margin: 130px auto;">
                <div class="modal-header" style="height: 60px;">
                    <button class="close" type="button" data-dismiss="modal" style="margin-top: -12px;font-size: 48px;">×</button>
                    <h4>{{oneCompany.ID==0 ? "增加":"编辑";}}公司</h4>
                </div>
                <div class="modal-body bgcolor-light" style="padding: 0 45px;">
                    <ul class="editform" style="margin: auto;">
                        <li ng-if="oneCompany.ID !=0 "><label>公司名称 <sup>*</sup></label><b>{{oneCompany.Name}}</b></li>
                        <li ng-if="oneCompany.ID == 0"><label>公司名称 <sup>*</sup></label><input type="text" ng-blur="checkName()" ng-model="oneCompany.Name"></li>
                        <li><label>公司地址 <sup>*</sup></label><input type="text" ng-model="oneCompany.Address"></li>
                        <li><label>客户类型<sup>*</sup></label>
                            <select class="select" id="s_VIP" ng-model="selected_VIP" ng-options="s.id as s.vip for s in IsVIP"></select>
                        </li>
                        <li><label>公司描述 </label><input type="text" ng-model="oneCompany.Description"></li>
                        <li><label>公司代码 <sup>*</sup></label><input type="text" ng-model="oneCompany.Code"></li>
                        <li><label>认证状态</label>
                            <select class="select" id="s_State" ng-model="s_State" ng-options="s.id as s.certify for s in CertifyState"></select>
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
    var APP = angular.module("myapp",["ui.bootstrap","ngAnimate","utils","syzx"]);
    APP.controller("mainCtrl",function($scope,$http,$timeout,dlgPort,zhMsg)
    {
        $scope.pageChange = function()
        {   isSelectAll = false;
            getURL(utils.sprintf("/admin/company/getCompanies?page=%d&Name=%s",$scope.currentPage,$scope.Name.trim())).then(function(data)
            {   $scope.list = data;
                for(var i=0;i<data.length;i++)
                {
                    if(data[i].CertifyState == 0)   data[i].CertifyStateF = "未经审核";
                    if(data[i].CertifyState == 10)   data[i].CertifyStateF = "提交审核";
                    if(data[i].CertifyState == 20)   data[i].CertifyStateF = "审核未过";
                    if(data[i].CertifyState == 30)   data[i].CertifyStateF = "审核通过";
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
            for(var i=0;i<$scope.list.length;i++)	if($scope.list[i].Checked == 1)	ids.push($scope.list[i].ID);
            if(ids.length == 0)
            {	msgBox("请先选择。");
                return;
            }
            msgBox(action + "指定的记录，是否确定？",MSG_INFORMATION,MSG_CONFIRM).then(function(){fn(ids);});
        }
        //删除
        $scope.del = function()
        {   confirm("删除",function(value)
            {   getURL("/admin/company/delCompany/?ID="+value.join(",")).then(function(data){$scope.pageChange();})
            })
        }

        //点击新建
        $scope.add = function()
        {
            $scope.oneCompany = {ID : 0,Name:"",Address:"",Description:"",Code:""};

            $scope.IsVIP = [{vip:"VIP客户",id:"1"},{vip:"普通客户",id:"0"}];
            $scope.selected_VIP = -1;
            $scope.CertifyState = [{certify:"未经审核",id:"0"},{certify:"提交审核",id:"10"},{certify:"审核未过",id:"20"},{certify:"审核通过",id:"30"}];
            $scope.s_State =-1;

            $("#companyModal").modal("show");
        }
        //点击打开对话框
        $scope.showModal = function(id,$event)
        {   //判断是否为火狐浏览器
            if (navigator.userAgent.indexOf("Firefox") >= 0)
            {   if($event.target.tagName == "INPUT") return;
            }
            else if(event.srcElement.tagName == "INPUT")	return;

            getURL("/admin/company/getOneCompany?ID="+id).then(function(data)
            {
                $scope.oneCompany = data[0];

                $scope.IsVIP = [{vip:"VIP客户",id:"1"},{vip:"普通客户",id:"0"}];
                $scope.selected_VIP = data[0].VIP;

                $scope.CertifyState = [{certify:"未经审核",id:"0"},{certify:"提交审核",id:"10"},{certify:"审核未过",id:"20"},{certify:"审核通过",id:"30"}];
                $scope.s_State = data[0].CertifyState;

                $("#companyModal").modal("show");
            })
        }

        //检查公司是否已经注册
        $scope.checkName = function()
        {
            getURL("/admin/company/checkName?Name="+$scope.oneCompany.Name).then(function(data)
            {if(data == 1)  {msgBox("该公司已注册")}
            })
        }
        //保存数据
        $scope.saveCompany = function()
        {
            data = {
                ID          :   $scope.oneCompany.ID,
                Name        :  $scope.oneCompany.Name ,
                Address     :  $scope.oneCompany.Address ,
                Description :  $scope.oneCompany.Description ,
                VIP         :  $scope.selected_VIP ,
                Code        :  $scope.oneCompany.Code ,
                CertifyState: $scope.s_State
            }
            if(data["Name"] == "")  {msgBox("请您填写公司名称");return;}
            if(data["Address"] == "")  {msgBox("请您填写公司地址");return;}
            if(data["VIP"] == -1)  {msgBox("请您选择客户类型");return;}

            postURL("/admin/company/save",data).then(function(data)
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
