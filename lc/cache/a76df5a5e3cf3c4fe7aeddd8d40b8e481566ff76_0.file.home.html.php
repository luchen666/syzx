<?php
/* Smarty version 3.1.30, created on 2017-03-21 08:33:19
  from "F:\wwwroot\lc\application\modules\admin\views\home.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58d074cf5642e9_36983678',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a76df5a5e3cf3c4fe7aeddd8d40b8e481566ff76' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\modules\\admin\\views\\home.html',
      1 => 1490056265,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58d074cf5642e9_36983678 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html ng-app="myapp">
<head lang="en">
    <meta charset="UTF-8">
    <title>首页</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"/>
    <link href="/admin/css/font-awesome.min.css" rel="stylesheet" type='text/css'>
    <link href="/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admin/css/animate.min.css" rel="stylesheet">
    <link href="/admin/css/style.min.css" rel="stylesheet">
    <link href="/admin/css/ionic.min.css" rel="stylesheet">
    <!-- 全局js -->
    <?php echo '<script'; ?>
 src="/admin/js/jquery-2.1.1.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/admin/js/angular.js"><?php echo '</script'; ?>
>
</head>
<body ng-controller="mainCtrl" style="overflow:hidden">
<div class="container-fluid animated fadeInRight">
    <div class="row"><div class="col-sm-12"><h2 class="title">待办事项</h2></div></div>
    <div class="row">
        <!--待审核的都是提交审核的数据-->
        <div class="col-sm-12 col-md-3">
            <div class="list">
                <a class="item item-avatar" href="Reply">
                    <img class="img-circle" src="/admin/images/Icon/FirstpageIcon_comment.png">
                    <p style="hight:40px;line-height:40px">待回复评论&nbsp;<b>{{CommentNumber}}</b>&nbsp;条</p>
                </a>
            </div>
        </div>
        <div class="col-sm-12 col-md-3">
            <div class="list">
                <a class="item item-avatar" href="Ships">
                    <!--图片最大40*40-->
                    <img class="img-circle" src="/admin/images/Icon/FirstpageIcon_ship.png">
                    <p style="hight:40px;line-height:40px">待审核船舶&nbsp;<b>{{PostcheckShip}}</b>&nbsp;条</p>
                </a>
            </div>
        </div>
        <div class="col-sm-12 col-md-3">
            <div class="list">
                <a class="item item-avatar" href="Users">
                    <img class="img-circle" src="/admin/images/Icon/FirstpageIcon_user.png">
                    <p style="hight:40px;line-height:40px">待审核用户&nbsp;<b>{{PostcheckUser}}</b>&nbsp;人</p>
                </a>
            </div>
        </div>
        <div class="col-sm-12 col-md-3">
            <div class="list">
                <a class="item item-avatar" href="Companies">
                    <img class="img-circle" src="/admin/images/Icon/FirstpageIcon_company.png">
                    <p style="hight:40px;line-height:40px">待审核公司&nbsp;<b>{{PostcheckCompany}}</b>&nbsp;家</p>
                </a>
            </div>
        </div>
    </div>
    <div class="row"><div class="col-sm-12"><h2 class="title">最新动态</h2></div></div>
    <div class="row">
        <div class="col-sm-12">
            <table class="table">
                <thead></thead>
                <tbody>
                <tr><td colspan="6" class="bg-success"><h4 class="title">订单统计</h4></td></tr>
                <tr>
                    <td>总订单</td>
                    <td>新增订单</td>
                    <td>待确认</td>
                    <td>进行中</td>
                    <td>已完成</td>
                    <td>已关闭</td>
                </tr>
                <tr>
                    <td>{{totalInquiry}}</td>
                    <td>{{newInquiryNumber}}</td>
                    <td>{{UncheckedInquiry}}</td>
                    <td>{{checkingInquiry}}</td>
                    <td>{{okInquiry}}</td>
                    <td>{{closeInquiry}}</td>
                </tr>
                <tr><td colspan="6" class="bg-success"><h4 class="title">用户统计</h4></td></tr>
                <tr>
                    <td>总用户</td>
                    <td>新增用户</td>
                    <td>未经审核</td>
                    <td>提交审核</td>
                    <td>审核通过</td>
                    <td>审核未过</td>
                </tr>
                <tr>
                    <td>{{totalUser}}</td>
                    <td>{{newUserNumber}}</td>
                    <td>{{UncheckedUser}}</td>
                    <td>{{PostcheckUser}}</td>
                    <td>{{checkedUser}}</td>
                    <td>{{checkFailureUser}}</td>
                </tr>
                <tr><td colspan="6" class="bg-success"><h4 class="title">船舶统计</h4></td></tr>
                <tr>
                    <td>总船舶</td>
                    <td>新增船舶</td>
                    <td>未经审核</td>
                    <td>提交审核</td>
                    <td>审核通过</td>
                    <td>审核未过</td>
                </tr>
                <tr>
                    <td>{{totalShip}}</td>
                    <td>{{newShipNumber}}</td>
                    <td>{{UncheckedShip}}</td>
                    <td>{{PostcheckShip}}</td>
                    <td>{{checkedShip}}</td>
                    <td>{{checkFailureShip}}</td>
                </tr>
                <tr><td colspan="6" class="bg-success"><h4 class="title">公司统计</h4></td></tr>
                <tr>
                    <td>总公司</td>
                    <td>新增公司</td>
                    <td>未经审核</td>
                    <td>提交审核</td>
                    <td>审核通过</td>
                    <td>审核未过</td>
                </tr>
                <tr>
                    <td>{{totalCompany}}</td>
                    <td>{{newCompanyNumber}}</td>
                    <td>{{UncheckedCompany}}</td>
                    <td>{{PostcheckCompany}}</td>
                    <td>{{checkedCompany}}</td>
                    <td>{{checkFailureCompany}}</td>
                </tr>
                <tr><td colspan="6" class="bg-success"><h4 class="title">船期货源统计</h4></td></tr>
                <tr>
                    <td>总船期</td>
                    <td>新增船期</td>
                    <td>可抢船期</td>
                    <td>总货源</td>
                    <td>新增货源</td>
                    <td>可抢货源</td>
                </tr>
                <tr>
                    <td>{{TotalShipschNumber}}</td>
                    <td>{{NewShipschNumber}}</td>
                    <td>{{GetShipschNumber}}</td>
                    <td>{{TotalSupplyNumber}}</td>
                    <td>{{NewSupplyNumber}}</td>
                    <td>{{GetSupplyNumber}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php echo '<script'; ?>
>
    var app = angular.module('myapp',['ng']);
    app.controller('mainCtrl',['$scope','$interval','$http',function($scope,$interval,$http){
        //定时刷新首页
        datas();
        $interval(function(){
            datas();
        },100000);
        function datas()
        {
            //查待回复评论数量
            $http.get('/admin/home/getNoAnsweredCommentsCount').success(function(data){
                $scope.CommentNumber=data[0].Cnumber;
            });
            //新增船舶
            $http.get('/admin/home/getNewShip').success(function(data){
                $scope.newShipNumber=data[0].newShipNumber;
            });
            //各状态船舶数量
            //数据初始化
            $scope.UncheckedShip=0;
            $scope.PostcheckShip=0;
            $scope.checkFailureShip=0;
            $scope.checkedShip=0;
            $scope.totalShip=0;
            $http.get('/admin/home/getShipNumber').success(function(data){
                var len=data.length;
                for(var i=0;i<len;i++){
                    // 0 :未经审核, 10:提交审核, 20:审核未过, 30:通过审核
                    if(data[i].CertifyState==0)    $scope.UncheckedShip=data[i].Snumber;
                    else if(data[i].CertifyState==10)   $scope.PostcheckShip=data[i].Snumber;
                    else if(data[i].CertifyState==20)   $scope.checkFailureShip=data[i].Snumber;
                    else if(data[i].CertifyState==30)   $scope.checkedShip=data[i].Snumber;
                }
                //总计
                $scope.totalShip=data[len-1].Snumber;
            });
            //新增用户
            $http.get('/admin/home/getNewUser').success(function(data){
                $scope.newUserNumber=data[0].newUserNumber;
            });
            //各状态用户数量
            //数据初始化
            $scope.UncheckedUser=0;
            $scope.PostcheckUser=0;
            $scope.checkFailureUser=0;
            $scope.checkedUser=0;
            $scope.totalUser=0;
            $http.get('/admin/home/getUserNumber').success(function(data){
                var len=data.length;
                for(var i=0;i<len;i++){
                    // 0 :未经审核, 10:提交审核, 20:审核未过, 30:通过审核
                    if(data[i].CertifyState==0) $scope.UncheckedUser=data[i].Unumber;
                    else if(data[i].CertifyState==10)   $scope.PostcheckUser=data[i].Unumber;
                    else if(data[i].CertifyState==20)  $scope.checkFailureUser=data[i].Unumber;
                    else if(data[i].CertifyState==30)  $scope.checkedUser=data[i].Unumber;
                }
                //总计
                $scope.totalUser=data[len-1].Unumber;
            });
            //新增公司
            $http.get('/admin/home/getNewCompany').success(function(data){
                $scope.newCompanyNumber=data[0].newCompanyNumber;
            });
            //各状态公司数量
            //初始化数据
            $scope.UncheckedCompany=0;
            $scope.PostcheckCompany=0;
            $scope.checkFailureCompany=0;
            $scope.checkedCompany=0;
            $scope.totalCompany=0;
            $http.get('/admin/home/getCompanyNumber').success(function(data){
                var len=data.length;
                for(var i=0;i<len;i++){
                    if(data[i].CertifyState==0) $scope.UncheckedCompany=data[i].Cnumber;
                    else if(data[i].CertifyState==10)  $scope.PostcheckCompany=data[i].Cnumber;
                    else if(data[i].CertifyState==20)  $scope.checkFailureCompany=data[i].Cnumber;
                    else if(data[i].CertifyState==30)   $scope.checkedCompany=data[i].Cnumber;
                }
                //总计
                $scope.totalCompany=data[len-1].Cnumber;
            });
            //新增订单
            $http.get('/admin/home/getNewInquiry').success(function(data){
                //console.log(data);
                $scope.newInquiryNumber=data[0].newInquiryNumber;
            });
            //各状态订单数量
            //数据初始化
            $scope.UncheckedInquiry=0;
            $scope.OrderInquiry=0;
            $scope.DepositInquiry=0;
            $scope.RefundInquiry=0;
            $scope.okInquiry=0;
            $scope.checkingInquiry=0;
            $scope.closeInquiry=0;
            $scope.totalInquiry=0;
            $http.get('/admin/home/getInquiryNumber').success(function(data){
                var len=data.length;
                for(var i=0;i<len;i++){
                    //0待确认,10生成订单,20已付定金,30退款中,50已完成,99已关闭 订单不能抢
                    if(data[i].State==0)    $scope.UncheckedInquiry=data[i].Onumber;
                    else if(data[i].State==10) $scope.OrderInquiry=data[i].Onumber;
                    else if(data[i].State==20)  $scope.DepositInquiry=data[i].Onumber;
                    else if(data[i].State==30) $scope.RefundInquiry=data[i].Onumber;
                    else if(data[i].State==50) $scope.okInquiry=data[i].Onumber;
                    else if(data[i].State==99) $scope.closeInquiry=data[i].Onumber;
                }
                //订单进行中
                $scope.checkingInquiry=Number($scope.OrderInquiry)+Number($scope.DepositInquiry)+Number($scope.RefundInquiry);
                //总计
                $scope.totalInquiry=data[len-1].Onumber;
            });
            //船期
            //数据初始化
            $scope.TotalShipschNumber=0;
            $scope.NewShipschNumber=0;
            $scope.GetShipschNumber=0;
            //总船期
            $http.get('/admin/home/getAllShipschCount').success(function(data){
                $scope.TotalShipschNumber=data[0].TotalShipschNumber;
            });
            // 新增船期
            $http.get('/admin/home/getnewShipschCount').success(function(data){
                $scope.NewShipschNumber=data[0].NewShipschNumber;
            });
            //可抢船期
            $http.get('/admin/home/getShipschCount').success(function(data){
                $scope.GetShipschNumber=data[0].GetShipschNumber;
            });
            //货源
            //数据初始化
            $scope.TotalSupplyNumber=0;
            $scope.NewSupplyNumber=0;
            $scope.GetSupplyNumber=0;
            //总货盘
            $http.get('/admin/home/getAllSupplyCount').success(function(data){
                $scope.TotalSupplyNumber=data[0].TotalSupplyNumber;
            });
            //新增货源
            $http.get('/admin/home/getnewSupplyCount').success(function(data){
                $scope.NewSupplyNumber=data[0].NewSupplyNumber;
            });
            //可抢货源
            $http.get('/admin/home/getSupplyCount').success(function(data){
                console.log(data);
                $scope.GetSupplyNumber=data[0].GetSupplyNumber;
            });
        }
    }]);
<?php echo '</script'; ?>
>
</body>
</html><?php }
}
