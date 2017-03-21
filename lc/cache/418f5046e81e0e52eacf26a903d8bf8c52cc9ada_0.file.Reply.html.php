<?php
/* Smarty version 3.1.30, created on 2017-03-09 17:46:02
  from "F:\wwwroot\lc\application\modules\admin\views\Reply.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58c1245a5be126_97062618',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '418f5046e81e0e52eacf26a903d8bf8c52cc9ada' => 
    array (
      0 => 'F:\\wwwroot\\lc\\application\\modules\\admin\\views\\Reply.html',
      1 => 1489052760,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58c1245a5be126_97062618 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html ng-app="myapp">
<head lang="en">
    <meta charset="UTF-8">
    <title>评论回复</title>
    <link href="/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admin/css/animate.min.css" rel="stylesheet">
    <link href="/admin/css/style.min.css" rel="stylesheet">
    <!-- 全局js -->
    <?php echo '<script'; ?>
 src="/admin/js/jquery-2.1.1.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/admin/js/angular.js"><?php echo '</script'; ?>
>
</head>
<body ng-controller="mainCtrl" class="gray-bg" style="overflow:hidden">
<div class="animated fadeInRight">
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="ibox">
                <div class="ibox-content">
                    <h4 class="m-t">用户评论</h4>
                    <div class="list-group" id="selectComment" style="height:730px;margin:0 auto;overflow-y: scroll">
                        <!--相同用户只展示一条数据-->
                        <a class="list-group-item" ng-repeat="Comment in allClist track by $index" alt={{Comment.UserID}}>
                            <div class="media">
                                <div class="media-body">
                                    <p><b>{{Comment.Name}}</b><br/>最新消息：{{Comment.Body}}</p>
                                    <span style="float:right">{{Comment.CreateDate}}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="list" style="padding:10px;background-color:#f5f5f5">
                        <button class="btn btn-primary" style="margin:0 20px 0 280px;" id="refreshData">刷新数据</button>
                        <button class="btn btn-primary" id="NoAnsweredComment">待回复评论</button>
                    </div>
                </div>
            </div>
        </div>
        <!--单击展示与之相关的所有对话记录的对话框-->
        <div class="col-sm-12 col-md-6">
            <div class="ibox">
                <div class="ibox-content">
                    <h4 class="m-t">聊天记录</h4>
                    <div class="list-group" style="height:720px;margin:0 auto;overflow-y:scroll;">
                        <a class="list-group-item" ng-repeat="dialog in dialoglist track by dialog.ID">
                            <div class="media">
                                <div class="media-body">
                                    <p>{{dialog.Body}}</p>
                                    <span style="display:inline-block;float:right">{{dialog.CreateDate}}</span>
                                </div>
                            </div>
                            <form class="form-inline" id="replyForm">
                                <input type="hidden" name="UserID" id="UserID" value={{dialog.UserID}}/>
                            </form>
                        </a>
                    </div>
                    <!--回复表单-->
                    <div class="input-group inquiry-input-group list col-sm-10" style="margin: auto;">
                        <input type="text" form="replyForm" type="text" name="Body" id="Body" class="well form-control search"  placeholder="回复内容">
                        <span class="input-group-btn" id="saveReply"><button class="btn btn-default"  form="replyForm" type="submit">发送</button></span>
                    </div>
                    <!--<div class="list" style="padding:10px;background-color:#f5f5f5">-->
                        <!--<input form="replyForm" type="text" name="Body" id="Body" class="well" style="display:inline-block;height:30px;width:400px;margin:0 10px 0 120PX;background-color:#fff" placeholder="回复内容"/>-->
                        <!--<input form="replyForm" type="submit" value="发送" class="btn btn-success" id="saveReply"/>-->
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo '<script'; ?>
>
    var app = angular.module('myapp',['ng']);
    app.controller('mainCtrl',['$scope','$http',function($scope,$http){
        //默认展示所有评论 相同用户只展示一条数据
        $http.get('/admin/home/getComments').success(function(data){
            $scope.allClist = data;});
        //刷新数据
        $("#refreshData").click(function(){
            window.location.reload();
        });
        //查看未回复评论
        $("#NoAnsweredComment").click(function(){
            $http.get('/admin/home/getNoAnsweredComments').success(function(data){
                if(data[0]==undefined){
                    $("#selectComment").html("<h1 class='well'>没有待回复评论</h1>");
                }else{
                    $scope.allClist = data;
                }
            });
        });
        //表单 回复评论
        $("#saveReply").click(function(){
            var inputData=$("#replyForm").serialize();
            console.log("表单序列化结果"+inputData);
            $.ajax({
                type:'POST',
                url:'/admin/Comment/saveReply',
                //async: false,
                data:inputData,
                success:function(){
                    //console.log("回复成功！");
                    $("#replyForm")[0].reset();
                }
            });
        });
        //单击展示与之相关的所有对话记录
        $("#selectComment").on('click','a',function(){
            var UserID=$(this).attr("alt");
            $http.get('/admin/home/getonedialog?UserID='+UserID).success(function(data){
                $scope.dialoglist = data;
            });
        });
    }]);
<?php echo '</script'; ?>
>
</body>
</html><?php }
}
