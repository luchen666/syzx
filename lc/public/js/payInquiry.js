angular.module("index",["utils","syzx"]).controller("dataCtrl",function($scope,$http,$timeout,$interval)
{   
	$scope.user = currUser;
	$scope.bank = bank;
	$scope.acc = acc;
	$scope.CurrentUserID = currUser.UserID;
	$scope.STATE = INQ_STATE;
	
	item.Deposit = parseFloat(item.Deposit);
	item.TotalSum = parseFloat(item.TotalSum);
	
	var money = 0;
	if(item.State == INQ_STATE.ORDER)		money = item.Deposit>0 ? item.Deposit : item.TotalSum*0.1;
	if(item.State == INQ_STATE.DEPOSIT)	money = item.TotalSum - item.Deposit;
	$scope.input = {
		Money		: money,
		BankCode	: acc.BankCode ,
		CardType	: acc.CardType,
		Password	: ""
	};
	item.PaymentMethodText = utils.idToText(item.PaymentMethod,PAYMENT_METHOD_TEXT);
	item.StateText = utils.idToText(item.State,INQ_STATE_TEXT);
	item.StateColor = utils.idToText(item.State,INQ_STATE_COLOR);	
	$scope.inq = item;
	
	if(__PUBLIC_KEY__ == "" ) getURL(URL_GET_PUBLICKEY).then(function(data) { __PUBLIC_KEY__ = data; });
	
	//=========================================================================

	//支付
	$scope.pay = function()
	{
		if($scope.input.Money == "" || $scope.input.Money == 0)
		{	msgBox("支付金额不能为空或零。",MSG_ERROR);
			return false;
		}
		if(item.State == INQ_STATE.ORDER && $scope.input.Money > item.TotalSum)
		{	msgBox("订金金额不能大于总运费。",MSG_ERROR);
			return false;
		}
		if(item.State == INQ_STATE.DEPOSIT && $scope.input.Money != item.TotalSum - item.Deposit)
		{	msgBox("尾款金额与订单剩余金额不符。",MSG_ERROR);
			return false;
		}

		showSpinner();

		var postData = {
			ID		: item.ID,
			Money	: $scope.input.Money,
			Password: utils.RSAEncrypt($scope.input.Password),
			CardType: $scope.input.CardType,
			BankCode: $scope.input.BankCode,
			PayType	: $scope.input.Money > acc.Balance ? "BANK" : "BALANCE"
		};
		
		//提交到服务器
		postURL(URL_PAY_INQUIRY,postData).then(function(data)
		{	
			hideSpinner();
			
			//网银支付，需要打开收银台，输入卡号
			if(postData.PayType == "BANK")
			{	document.write(window.atob(data.url));
			}
			else
			{	if($scope.item.State == INQ_STATE.ORDER)
				{	$scope.item.State = INQ_STATE.DEPOSIT;
					$scope.item.Deposit = input.Money;
				}
				else if($scope.item.State == INQ_STATE.DEPOSIT)
				{	$scope.item.State = INQ_STATE.DONE;
				}
				$scope.item.UnRead = 0;
				$scope.item.StateText = utils.idToText($scope.item.State,INQ_STATE_TEXT);
				$scope.item.StateColor = utils.idToText($scope.item.State,INQ_STATE_COLOR);	
				
				msgBox("订金支付完成。");
			}
		},
		function(err)
		{	hideSpinner();
			var msg = "连接支付网站失败，请稍后再试！";
			if(err != null)	msg = getErrorMessage(err);
			msgBox(msg,MSG_ERROR);
		});
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
	
});