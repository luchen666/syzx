<?php
/* Smarty version 3.1.30, created on 2017-03-16 13:56:56
  from "F:\wwwroot\lc\application\modules\admin\views\Inquiry.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58ca29286f0b28_72025924',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '64f52b2b691287b98e9e7c136126aeb3cd01026a' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\modules\\admin\\views\\Inquiry.html',
      1 => 1489643810,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58ca29286f0b28_72025924 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!doctype html>
<html ng-app="myapp">
<head>
    <meta charset="utf-8" />
    <title>订单列表</title>

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

</head>

<body ng-controller="mainCtrl" class="white-bg" style="overflow:hidden;padding:20px;">

<div class="animated fadeInRight" id="fade">
    <div class="inquiry-btns">
        <button ng-click="add()" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i> 新增</button>
        <button ng-click="del()" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i> 删除</button>
        <button ng-click="refresh()" class="btn btn-info"><i class="glyphicon glyphicon-refresh"></i> 刷新</button>
        <div class="input-group common-input-group">
            <input type="text" class="form-control search">
            <span class="input-group-btn"><button class="btn btn-default" type="button">搜索</button></span>
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
        total-items="inquiryNumber">
    </ul>

    <table class="datalist">
        <thead><tr>
            <td width="50">序号</td>
            <td width="30" ng-click="selectAll()" title="选择全部/取消选择"><img src="/admin/images/checkbox.gif"></td>
            <td width="80">订单号</td>
            <td width="70">货主</td>
            <td width="70">货物</td>
            <td width="95">总量（吨）</td>
            <td width="95">偏差（吨）</td>
            <td width="70">海/内河</td>
            <td width="70">单价</td>
            <td width="90">总价</td>
            <td width="80">船东</td>
            <td width="100">船舶</td>
            <td width="100">出发港</td>
            <td width="100">到达港</td>
            <td width="100">最早起运期</td>
            <td width="100">最迟起运期</td>
        </tr></thead>
        <tbody><tr ng-repeat="item in list track by $index" ng-click="show(item.ID,$event)">
            <td align="center">{{item.ID}}</td>
            <td align="center"><input type="checkbox" ng-checked="item.Checked == 1" ng-model="item.Checked"></td>
            <td>{{item.OrderNo}}</td>
            <td align="right">{{item.SupplyUserName}}</td>
            <td align="right">{{item.GoodsName}}</td>
            <td align="right">{{item.Qty | numberformat:1}}</td>
            <td align="right">{{item.QtyDeviation}}</td>
            <td align="right">{{item.SeaOrRiver == 0 ? "内河": "海运";}}</td>
            <td align="right">{{item.Price}}</td>
            <td align="right">{{item.TotalSum}}</td>
            <td align="center"><div>{{item.ShipUserName}}</div></td>
            <td align="center">{{item.ShipName}}</td>
            <td align="center">{{item.FromPortName}}</td>
            <td align="center">{{item.ToPortName}}</td>
            <td align="center">{{item.LoadDateFrom}}</td>
            <td align="center">{{item.LoadDateTo}}</td>
        </tr></tbody>
    </table>

</div>

<!--添加或编辑-->
<!--<div class="modal  fade in inquiryModal" id="myModal" style="margin-top:20px"  aria-hidden="true" data-backdrop="static">-->
    <!--<div class="modal-dialog" style="width:450px;"><div class="modal-content">-->
        <!--<div class="modal-header" style="height:60px;padding-top:0px;">-->
            <!--<button class="close" type="button" data-dismiss="modal" style="margin-top:5px;font-size:48px;">×</button>-->
            <!--<h3>{{shipsch.ID == 0 ? "添加" : "编辑"}}订单</h3>-->
        <!--</div>-->
        <!--<div class="modal-body">-->
            <!--<form name="content" class="contentStyle" >-->
                <!--<div class="col-md-12"  >-->
                    <!--<label class='col-sm-4'>船名<i></i><span>*</span></label>-->
                    <!--<div class="input-group col-sm-8">-->
                        <!--<input type="text" placeholder="请输入船名" class="form-control shipName" id="userSelectText" ng-change="showUserDlg(shipsch.ShipName)" ng-model="shipsch.ShipName" >-->
                        <!--<div id="inputSelect">-->
                            <!--<ul id="inputSelectList">-->
                                <!--<li ng-repeat="item in inputSelect track by $index" ng-click="inputSelectList($index)" tabindex="1">{{item.ShipName}}</li>-->
                            <!--</ul>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->
                <!--<div class="col-md-12"><label class='col-sm-4'>船东<i></i></label><input class='col-sm-8 userName' ng-model="shipsch.UserName" disabled ></div>-->
                <!--<div class="col-md-12"><label class='col-sm-4'>船舶类型<i></i></label><input class='col-sm-8 shipType' ng-model="shipsch.ShipType" disabled></div>-->
                <!--<div class="col-md-12"><label class='col-sm-4'>海/内河<i></i></label><input class='col-sm-8 seaOrRiver' ng-model="shipsch.SeaOrRiver" disabled></div>-->
                <!--<div class="col-md-12"><label class='col-sm-4'>吨位(吨)<i></i></label><input class='col-sm-8 tonnage' ng-model="shipsch.Tonnage" disabled></div>-->


                <!--<div class="col-md-12 input" style="position: relative;" >-->
                    <!--<label class='col-sm-4'>空船日期<i></i><span>*</span></label>-->
                    <!--<input ng-click="showClearDateDlg();" class='col-sm-8 dateEdit'  id="clearDateText" type="text" ng-model="shipsch.ClearDate" ng-model="startTime">-->
                    <!--<div style="position: absolute;top: 35px;right: 26px;z-index: 1000;" class="clearDateDlg" tabindex=2>-->
                        <!--<ul><li><div id="dlgClearDateFrom"></div></li></ul>-->
                    <!--</div>-->
                <!--</div>-->
                <!--<div class="col-md-12"><label class='col-sm-4'>船期状态<i></i></label>-->
                    <!--<select id="select_k2" class="width66" ng-model="selected2" ng-options="s.id as s.state for s in states">-->
                    <!--</select></div>-->
                <!--&lt;!&ndash;出发港&ndash;&gt;-->
                <!--<div class="col-md-12" id="portBoxFrom">-->
                    <!--<label class='col-sm-4'>出发港<i></i><span>*</span></label>-->
                    <!--<input class='col-sm-8 portEdit' id="fromPortText" type="text" ng-click="showPortDlg(0);" ng-model="shipsch.ClearPortName">-->
                <!--</div>-->
                <!--&lt;!&ndash;到达港&ndash;&gt;-->
                <!--<div class="col-md-12" id="portBoxTo">-->
                    <!--<label class='col-sm-4'>到达港<i></i><span>*</span></label>-->
                    <!--<input class='col-sm-8 portEdit' id="toPortText" type="text" ng-click="showPortDlg(1);" ng-model="shipsch.ClearPortName">-->
                <!--</div>-->
                <!--<div class="col-md-12"><label class='col-sm-4'>最早起运期<i></i></label><input class='col-sm-8 MemoEdit' type="text" ng-model="shipsch.Memo"></div>-->
                <!--<div class="col-md-12"><label class='col-sm-4'>最迟起运期<i></i></label><input class='col-sm-8 MemoEdit' type="text" ng-model="shipsch.Memo"></div>-->
            <!--</form>-->
        <!--</div>-->
        <!--<div class="modal-footer" style="clear: both;">-->
            <!--<button ng-click="save()" class="btn btn-success">保存</button>-->
            <!--<button class="btn" data-dismiss="modal">关闭</button>-->
        <!--</div>-->
    <!--</div></div></div>-->
<?php echo '<script'; ?>
>

    var APP = angular.module("myapp", ["ui.bootstrap","ngAnimate","utils","syzx"]);

    APP.controller("mainCtrl",function($scope,$http,$timeout,dlgPort,zhMsg)
    {
        //获取指定页
        $scope.pageChange = function()
        {	isSelectedAll = false;
            getURL("/admin/home/getInquiry?page="+$scope.currentPage).then(function(res)
            {   $scope.list = res;
                for(var i=0;i<res.length;i++)
                {   if(res[i].State == 0) res[i].State = "发布中";
                    if(res[i].State == 10) res[i].State = "生成订单";
                    if(res[i].State == 20) res[i].State = "失效";
                    if(res[i].SeaOrRiver == 0) res[i].SeaOrRiver = "河运";
                    if(res[i].SeaOrRiver == 1) res[i].SeaOrRiver = "海运";
                }
                console.log($scope.list)
            });
        }
        //======================= 空船港口选择对话框
        $scope.showPortDlg = function()
        {   dlgPort.show($("#portBox")).then(function(data)
        {   $scope.search.ClearPortID = data.portID;
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

        //点击“删除”按钮
        $scope.del = function()
        {
            confirm("删除",function(value)
            {   getURL("/admin/inquiry/del/?ID=" + value.join(",")).then(function() { $scope.pageChange(); });
            });
        }

        //刷新
        $scope.refresh = function() {   window.location.reload();   };

        //点击行打开对话框
        var clearPortName = "";
        $scope.show = function(id,$event)
        {
            //判断是否为火狐浏览器
            if (navigator.userAgent.indexOf("Firefox") >= 0)
            {   if($event.target.tagName == "INPUT") return;
            }
            else if(event.srcElement.tagName == "INPUT")	return;

            getURL("/admin/home/getoneInquiry/?ID=" + id).then(function(data)
            {	$scope.shipsch = data[0];
                $("#myModal").modal("show");
            });
        };

        //点击“新增”按钮
        $scope.add = function()
        {   $scope.shipsch = {ID:0,SeaOrRiver:"",State:"",ShipName:""};

            $scope.states = [{state:'发布中',id:"0"},{state:'生成订单',id:"10"},{state:'失效',id:"20"}];
            $scope.selected2 = -1;  //id的值，区分类型

            $("#myModal").modal("show");
        }


        //===================== 开始 ==================
        var isSelectedAll = false;
        $scope.currentPage = 1;		//初始当前页
        $scope.inquiryNumber = 0;

        //总记录数
        getURL("/admin/Inquiry/getCount").then(function(data) { $scope.inquiryNumber = data;});
        $scope.pageChange();



    });
<?php echo '</script'; ?>
>

</body>
</html>

<?php }
}
