//jQuery(document).ready(function(){
//
//    $('.page-container form').submit(function(){
//        var username = $(this).find('.username').val();
//        var password = $(this).find('.password').val();
//        if(username == '') {
//            $(this).find('.error').fadeOut('fast', function(){
//                $(this).css('top', '27px');
//            });
//            $(this).find('.error').fadeIn('fast', function(){
//                $(this).parent().find('.username').focus();
//            });
//            return false;
//        }
//        if(password == '') {
//            $(this).find('.error').fadeOut('fast', function(){
//                $(this).css('top', '96px');
//            });
//            $(this).find('.error').fadeIn('fast', function(){
//                $(this).parent().find('.password').focus();
//            });
//            return false;
//        }
//    });
//
//    $('.page-container form .username, .page-container form .password').keyup(function(){
//        $(this).parent().find('.error').fadeOut('fast');
//    });
//
//});
//点击刷新验证码 带上随机参数防止缓存
$("#codeImg").click(function(){
    $(this).attr("src","/admin/login/code");
});
//用户点击登录框
$("#loginSubmit").click(function(){
    //验证验证码是否正确
    var code_num = $("#Code").val();
    $.post("/admin/login/CheckCode?act=num",{code:code_num},function(msg){
        if(msg==1){
            //验证码正确！
            //读取用户的输入——表单序列化
            var inputData = $('#login-form').serialize();
            console.log("表单序列化结果"+inputData);
            //异步提交请求，进行验证
            $.ajax({
                type:'POST',
                url: '/admin/Login/Login',
                data:inputData,
                success: function(res){
                    var result= JSON.parse(res);
                    //console.log(result);
                    if(result.code==1){
                        window.location.assign("/admin/home/index");
                    }else{
                        if(result.code==-1){
                            $("#loginMsg").html("账户不存在");
                        }else if(result.code==-2){
                            $("#loginMsg").html("密码错误");
                        }else if(result.code==-4){
                            $("#loginMsg").html("账户被删除");
                        }
                    }

                }
            });
        }else{
            //验证码错误！
            $("#loginMsg").html("验证码错误");
        }
    });
});
