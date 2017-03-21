angular.module("index",["utils","syzx"]).controller("dataCtrl",function($scope,$http,$timeout,$interval,dlgPort,dlgLogin)
{   
	$scope.user = currUser != null ? currUser : {IsLogin:false};
	$scope.list = (list) ? list : [];
	$scope.CurrentUserID = currUser.UserID;
	$scope.bankAcc = bankAcc;
	$scope.showMoreButton = $scope.list.length >= ROWS_PER_PAGE;
	
	var STATE_TEXT = [
		{ID:-1, Text:"交易失败"},
		{ID: 0, Text:"等待确认"},
		{ID: 1, Text:"交易完成"}
	];
	var STATE_COLOR = [
		{ID:-1, Text:"red"},
		{ID: 0, Text:""},
		{ID: 1, Text:"green"}
	];
	
	for(var i=0;i<$scope.list.length;i++)
	{	
		$scope.list[i].StateText = utils.idToText($scope.list[i].State,STATE_TEXT);
		$scope.list[i].StateColor = utils.idToText($scope.list[i].State,STATE_COLOR);
	}
	
	$("#dateFrom").datepicker();
	$("#dateTo").datepicker();
	
	if(__PUBLIC_KEY__ == "" ) getURL(URL_GET_PUBLICKEY).then(function(data) { __PUBLIC_KEY__ = data; });
	
	//===========================================================================
	var minId = $scope.list.length > 0 ? $scope.list[$scope.list.length-1].ID : -1;
	$scope.more = function(all)
	{
		utils.showToast("获取中。。。",1000);
		
		$scope.dateFrom = $("#dateFrom").val();
		$scope.dateTo = $("#dateTo").val();
		
		var d = {ID:all?-1:minId,ACT:all?"NEW":"OLD",dateFrom:$scope.dateFrom,dateTo:$scope.dateTo};
		postURL(URL_GET_USERBILL,d).then(function(data)
		{
			//保存最小的id
			minId = data[data.length-1].ID;
			
			//是否显示more按钮
			$scope.showMoreButton = (data.length >= ROWS_PER_PAGE);
			
			for(var i=0;i<data.length;i++)
			{	data[i].StateText = utils.idToText(data[i].State,STATE_TEXT);
				data[i].StateColor = utils.idToText(data[i].State,STATE_COLOR);
			}
			
			//添加到数组的末尾
			if(all)	$scope.list = data;
			else	Array.prototype.push.apply($scope.list,data);
			
			$scope.ship = $scope.list[0];
		},
		function(err)
		{	if(all)	$scope.list = [];
			$scope.showMoreButton = false;
		});
	}
	
	//输入平台支付密码
	var showPayPassword = function(fn,title,subTitle)
	{
		var check = function(val)
		{	if(val == "" || val.length < 6)
			{	msgBox("支付密码不能为空，<br>且长度也不能小于6位。 ",MSG_ERROR);
				return false;
			}
			return true;
		}
		if(!utils.isDefined(title))	title = "支付密码";
		if(!utils.isDefined(subTitle))	subTitle = "支付密码不能少于6位！";
		agMsg.prompt({title:title,text:subTitle,inputType:"password",inputPlaceHolder:"请输入平台支付密码",onClick:check}).then(function(pwd) { if(fn != null) fn(pwd); });
	}
	
	//重设支付密码
	$scope.showSetPwdForm = function()
	{	$scope.payPwd = {PayPwd:"",RePayPwd:"",VCode:""};
	
		$("#setPayPwdForm").on("shown.bs.modal",function (e)
		{	if(!utils.isIE())	return;
			var obj =  $("#setPayPwdForm .modal-dialog");
			var left = $(window).width() / 2 - obj.width() / 2; 
			obj.css("margin-left",left); 
		}).modal("show");
	}
	$scope.setPwdCheck = function()
	{	if($scope.payPwd.PayPwd != $scope.payPwd.RePayPwd)
		{	msgBox("支付密码与确认密码不一致。",MSG_ERROR);
			return;
		}
		if($scope.payPwd.PayPwdlenght < 6 || !/[A-Za-z]/.test($scope.payPwd.PayPwd) || !/[0-9]/.test($scope.payPwd.PayPwd))
		{	msgBox("支付密码必须大于6位，同时含有字母与数字。",MSG_ERROR);
			return;
		}
	}
	$scope.setPayPwd = function()
	{	if($scope.payPwd.PayPwd == "" || $scope.payPwd.RePayPwd == "" || $scope.payPwd.VCode == "")
		{	msgBox("请填写密码与验证码。");
			return;
		}
		if($scope.payPwd.PayPwd != $scope.payPwd.RePayPwd)
		{	msgBox("支付密码与确认密码不一致。",MSG_ERROR);
			return;
		}
		if($scope.payPwd.PayPwdlenght < 6 || !/[A-Za-z]/.test($scope.payPwd.PayPwd) || !/[0-9]/.test($scope.payPwd.PayPwd))
		{	msgBox("支付密码必须大于6位，同时含有字母与数字。",MSG_ERROR);
			return;
		}
		postURL(URL_SET_PAYPASSWORD,{PayPwd:utils.RSAEncrypt($scope.payPwd.PayPwd),VCode:$scope.payPwd.VCode}).then(function() 	
		{	$("#setPayPwdForm").on("shown.bs.modal",function (e)
			{	if(!utils.isIE())	return;
				var obj =  $("#setPayPwdForm .modal-dialog");
				var left = $(window).width() / 2 - obj.width() / 2; 
				obj.css("margin-left",left); 
			}).modal("hide"); msgBox("支付密码设置完成。"); 
		},function(err) { msgBox(getErrorMessage(err),MSG_ERROR) });
	}
	
	//解绑
	$scope.unbindCard = function()
	{
		if($scope.bankAcc.CardNo == "") { msgBox("您还未绑定银行帐户，不需要解绑。"); return; }
		
		var fn = function(pwd)
		{	postURL(URL_UNBIND_BANKACCOUNT,{PayPwd:utils.RSAEncrypt(pwd)}).then(
				function() { msgBox("绑定的银行帐户信息已清除。");$scope.bankAcc.CardNo = ""; },
				function(err) {	msgBox(getErrorMessage(err),MSG_ERROR); }
			);
		}
		
		showPayPassword(fn,"支付密码","请输入支付密码以确认解绑银行帐户。");
	}
	
	//绑卡
	$scope.bindCard = function()
	{	
		var doBind = function()
		{	getURL(URL_GET_BANKLIST).then(function(data)
			{	$scope.bankList = data;
				$scope.myBank = utils.clone($scope.bankAcc);
				$scope.myBank.VCode = "";
				
				$("#bindForm").on("shown.bs.modal",function (e)
				{	if(!utils.isIE())	return;
					var obj =  $("#bindForm .modal-dialog");
					var left = $(window).width() / 2 - obj.width() / 2; 
					obj.css("margin-left",left); 
				}).modal("show");
			},function(err) { msgBox(getErrorMessage(err),MSG_ERROR) });
		}
		
		if($scope.bankAcc.CardNo != "")
			msgBox("您已绑定帐户，需要重新绑定吗？",MSG_WARNNING,MSG_CONFIRM).then(function(){doBind(); });
		else
			doBind();
	}
	//发验证码
	$scope.sendVCode = function()
	{
		var vCodeRemain = 300;
	
		//VCODE_EXPIRE			: 300,					//验证码获取间隔
		//VCODE_TYPE_REGISTER	: 10,					//验证码类型：注册
		//VCODE_TYPE_FORGETPWD	: 20,					//验证码类型：修改密码
		
		$("#VCodeBtn").text(vCodeRemain).attr("disabled",true);
		
		timePromise = $interval(function()
		{	if(vCodeRemain <= 1)
			{	$interval.cancel(timePromise);
				timePromise = null;
				
				$("#VCodeBtn").text("发送").attr("disabled",false);
			}
			else $("#VCodeBtn").text(vCodeRemain);
			
			vCodeRemain--;
			
		},1000,305);  
		
		getURL(URL_SEND_VCODE);
	}
	//绑定银行卡
	$scope.saveBind = function()
	{
		$scope.myBank.CardName = $("#bankCardName").val();
		if($scope.myBank.CardNo == "" || $scope.myBank.BankCode == "" || $scope.myBank.CardName == "" || $scope.myBank.CardType == "")
		{	msgBox("请填写完整帐户信息。",MSG_ERROR);
			return;
		}
		if(!utils.luhmCheck($scope.myBank.CardNo + ""))
		{	msgBox("请填写正确的帐号。",MSG_ERROR);
			return;
		}
		
		var fn = function(pwd)
		{
			var postBank = utils.clone($scope.myBank);
			postBank.PayPwd	  = utils.RSAEncrypt(pwd);
			postBank.CardNo   = utils.RSAEncrypt($scope.myBank.CardNo + "");
			postBank.CardName = utils.RSAEncrypt($scope.myBank.CardName);
			var i = $scope.bankList.findBy("Code",$scope.myBank.BankCode);
			postBank.BankName = $scope.bankList[i].Name;
			delete postBank._RecordCount_;
			delete postBank.Balance;
			
			utils.showToast("处理中。。。",1500);
			postURL(URL_BIND_BANKACCOUNT,postBank).then(function(data)
			{
				$("#bindForm").on("shown.bs.modal",function (e)
				{	if(!utils.isIE())	return;
					var obj =  $("#bindForm .modal-dialog");
					var left = $(window).width() / 2 - obj.width() / 2; 
					obj.css("margin-left",left); 
				}).modal("hide"); 
				
				$scope.bankAcc.BankName = postBank.BankName;
				$scope.bankAcc.CardType = postBank.CardType;
				$scope.bankAcc.CardNo =  $scope.myBank.CardNo + "";
				$scope.bankAcc.CardNo =  $scope.bankAcc.CardNo.left(4) + " **** **** " + $scope.bankAcc.CardNo.right(4);
				msgBox("银行帐户已绑定。");
				
			}, function(err) {	 msgBox(getErrorMessage(err),MSG_ERROR); });
		}
		showPayPassword(fn,"支付密码","请输入支付密码以确认绑定银行帐户");
	}
	
	//充值
	$scope.showRechargeForm = function()
	{
		$scope.rechargeData = {
			Money	: "",
			BankCode: bankAcc.BankCode,
			CardType: bankAcc.CardType
		}
		getURL(URL_GET_BANKLIST).then(function(data)
		{	$scope.bank = data;
			$("#payButton").text("去支付");
			$("#payButton").attr("disabled",false);
			
			$("#rechargeForm").on("shown.bs.modal",function (e)
			{	if(!utils.isIE())	return;
				var obj =  $("#rechargeForm .modal-dialog");
				var left = $(window).width() / 2 - obj.width() / 2; 
				obj.css("margin-left",left); 
			}).modal("show");
		});
	}
	//关闭支付窗口后，重新刷新状态。
	$("#rechargeForm").on("hide.bs.modal", function()
	{	setTimeout(function() 
		{	getURL(URL_GET_BALANCE).then(function(data) { $scope.bankAcc.Balance = data; });
		},3000);
	});
	//支付
	$scope.doRecharge = function()
	{	if($scope.rechargeData.Money == "" || $scope.rechargeData.Money == 0)
		{	msgBox("充值金额不能空。",MSG_ERROR);
			return;
		}
		if($scope.rechargeData.BankCode == "" || $scope.rechargeData.CardType == "")
		{	msgBox("开户行与帐户类型不能空。",MSG_ERROR);
			return;
		}
		var obj = $("#submitForm");
		obj.attr("action","/ucenter/recharge");
		obj.children().remove();
		obj.append(utils.sprintf("<input type=hidden name='Money' value='%s'>",$scope.rechargeData.Money));
		obj.append(utils.sprintf("<input type=hidden name='BankCode' value='%s'>",$scope.rechargeData.BankCode));
		obj.append(utils.sprintf("<input type=hidden name='CardType' value='%s'>",$scope.rechargeData.CardType));
		obj.submit();
		$("#payButton").text("支付中…");
		$("#payButton").attr("disabled",true);
	}
	
	//提现
	$scope.showTakeForm = function()
	{	$scope.takeCash = {Money:"",PayPwd:"",PayPassword:""};
		$("#takeForm").on("shown.bs.modal",function (e)
		{	if(!utils.isIE())	return;
			var obj =  $("#takeForm .modal-dialog");
			var left = $(window).width() / 2 - obj.width() / 2; 
			obj.css("margin-left",left); 
		}).modal("show");
	}
	$scope.doTakeCash = function()
	{	if($scope.takeCash.Money == "" || $scope.takeCash.PayPwd == "" || $scope.takeCash.Money == 0)
		{	msgBox("请填写完整提现金额与提现密码。",MSG_ERROR);
			return;
		}
		if($scope.takeCash.Money > $scope.bankAcc.Balance)
		{	msgBox("提现金额不能大于帐户余额。",MSG_ERROR);
			return;
		}
		utils.showToast("处理中。。。",1500);
		var data = {PayPwd : utils.RSAEncrypt($scope.takeCash.PayPwd),Money : utils.RSAEncrypt(String($scope.takeCash.Money)) };
		postURL(URL_ACC_TAKECASH,data).then(function()
		{	$("#takeForm").on("shown.bs.modal",function (e)
			{	if(!utils.isIE())	return;
				var obj =  $("#takeForm .modal-dialog");
				var left = $(window).width() / 2 - obj.width() / 2; 
				obj.css("margin-left",left); 
			}).modal("hide");
			
			msgBox("提现请求已提交到银行，部分金额已冻结，请过半小时到一小时后查询银行帐户。")
			setTimeout(function() 
			{	getURL(URL_GET_BALANCE).then(function(data) { $scope.bankAcc.Balance = data; });
			},3000);
		},function(err) { msgBox(getErrorMessage(err),MSG_ERROR); });
	}
	
});