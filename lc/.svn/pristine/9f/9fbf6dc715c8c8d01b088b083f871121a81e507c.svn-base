angular.module("index",["utils","syzx"]).controller("dataCtrl",function($scope,$http,$timeout,dlgPort,dlgLogin)
{   
	$scope.user = currUser != null ? currUser : {IsLogin:false};
	$scope.CurrentUserID = currUser.UserID;
	
	$scope.list = (list) ? list : [];
	$scope.STATE = INQ_STATE;
	$scope.PACKAGE_METHOD = PACKAGE_METHOD;
	$scope.PAYMENT_METHOD_TEXT = PAYMENT_METHOD_TEXT;
	$scope.StateFrom = 0;
	$scope.StateTo = 100;
	
	//是否显示more按钮
	$scope.showMoreButton = $scope.list.length >= ROWS_PER_PAGE;
	$scope.showShipState = false;
	$scope.toggleShip = function()
	{	$scope.showShipState = !$scope.showShipState;
		if($scope.showShipState)	
		{	setTimeout(function(){ $(".inqshow").scrollTop($(".inqshow").height()); },100);
		}
	}
	
	for(var i=0;i<$scope.list.length;i++)
	{	
		$scope.list[i].PaymentMethodText = utils.idToText($scope.list[i].PaymentMethod,PAYMENT_METHOD_TEXT);
		$scope.list[i].StateText = utils.idToText($scope.list[i].State,INQ_STATE_TEXT);
		$scope.list[i].StateColor = utils.idToText($scope.list[i].State,INQ_STATE_COLOR);
		
		if($scope.list[i].Price == 0)	$scope.list[i].Price = "";
		if($scope.list[i].Deposit == 0)	$scope.list[i].Deposit = "";
		if($scope.list[i].TotalSum == 0)	$scope.list[i].TotalSum = "";
	}
	
	//上部状态菜单点击
	$(".inqmenu li").bind("click",function()
	{
		$(".inqmenu li").attr("class","");
		$(this).attr("class","inqmenu-act");
		
		var idx = $(this).index();
		if(idx == 0)		{ $scope.StateFrom = 0; $scope.StateTo = 99;		}
		else if(idx == 1)	{ $scope.StateFrom = INQ_STATE.NONE; $scope.StateTo = INQ_STATE.NONE;			}
		else if(idx == 2)	{ $scope.StateFrom = INQ_STATE.ORDER; $scope.StateTo = INQ_STATE.DONE-1;		}
		else if(idx == 3)	{ $scope.StateFrom = INQ_STATE.DONE; $scope.StateTo = INQ_STATE.DONE; 			}
		else if(idx == 4)	{ $scope.StateFrom = INQ_STATE.INVALID; $scope.StateTo = INQ_STATE.INVALID;	}
		
		$scope.more(true);
	});
	
	//===========================================================================
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
	
	//===========================================================================
	//数据载入
	var minId = $scope.list.length ? $scope.list[$scope.list.length-1].ID : -1;
	$scope.more = function(all)
	{	var d = {
			ID			: all ? -1 : minId,
			ACT			: "OLD",
			StateFrom	: $scope.StateFrom,
			StateTo		: $scope.StateTo
		};
		
		utils.showToast("获取中。。。",1000);
		postURL(URL_GET_INQLIST,d).then(function(data)
		{
			//保存最小的id
			minId = data[data.length-1].ID;
			
			//是否显示more按钮
			$scope.showMoreButton = (data.length >= 20);
			
			for(var i=0;i<data.length;i++)
			{	
				data[i].PaymentMethodText = utils.idToText(data[i].PaymentMethod,PAYMENT_METHOD_TEXT);
				data[i].StateText = utils.idToText(data[i].State,INQ_STATE_TEXT);
				data[i].StateColor = utils.idToText(data[i].State,INQ_STATE_COLOR);
				
				if(data[i].Deposit == 0)	data[i].Deposit = "";
				if(data[i].TotalSum == 0)	data[i].TotalSum = "";
				if(data.Price == 0)			data[i].Price = "";
			}
			
			//添加到数组的末尾
			if(all)	$scope.list = data;
			else	Array.prototype.push.apply($scope.list,data);
		},
		function(err)
		{	if(all)	$scope.list = [];
			$scope.showMoreButton = false;
		});
	}
	
	//查看订单详情
	$scope.show = function(idx)
	{	var theEvent = window.event || arguments.callee.caller.arguments[0];
		var obj = theEvent.srcElement ? theEvent.srcElement : theEvent.target;
		
		if(obj.tagName == "A" || obj.tagName == "ANY" || $(obj).index() == 8)	return;
		
		getURL(utils.sprintf(URL_GET_INQUIRY,$scope.list[idx].ID)).then(function(data)
		{	
			data.PackageMethodText = utils.idToText(data.PackageMethod,PACKAGE_METHOD);
			data.PaymentMethodText = utils.idToText(data.PaymentMethod,PAYMENT_METHOD_TEXT);
			data.StateText = utils.idToText(data.State,INQ_STATE_TEXT);
			data.StateColor = utils.idToText(data.State,INQ_STATE_COLOR);
			
			$scope.list[idx].SupplyUserReadDate = 1;
			
			$scope.inq = data;
			$("#inqShowForm").on("shown.bs.modal",function (e)
			{	if(!utils.isIE())	return;
				var obj =  $("#inqShowForm .modal-dialog");
				var left = $(window).width() / 2 - obj.width() / 2; 
				obj.css("margin-left",left); 
			}).modal("show");
		});
	}
	
	//编辑订单
	var rowIndex = -1;
	$scope.edit = function(idx)
	{	rowIndex = idx;
		$scope.inq = utils.clone($scope.list[idx]);
		$scope.inq.Qty = parseFloat($scope.inq.Qty);
		$scope.inq.QtyDeviation = parseFloat($scope.inq.QtyDeviation);
		$scope.inq.Price = $scope.inq.Price != "" ? parseFloat($scope.inq.Price) : 0;
		$scope.inq.TotalSum = $scope.inq.TotalSum != "" ? parseFloat($scope.inq.TotalSum) : 0;
		$scope.inq.Deposit = $scope.inq.Deposit != "" ? parseFloat($scope.inq.Deposit) : 0;
		
		$("#inqEditForm").on("shown.bs.modal",function (e)
		{	if(!utils.isIE())	return;
			var obj =  $("#inqEditForm .modal-dialog");
			var left = $(window).width() / 2 - obj.width() / 2; 
			obj.css("margin-left",left); 
		}).modal("show");
	}
	$scope.reCal = function() {	 $scope.inq.TotalSum = $scope.inq.Qty * $scope.inq.Price; }
	
	//保存订单
	$scope.save = function()
	{	
		if($scope.inq.NeedOnlinePay === "" || $scope.inq.NeedOnlinePay == null || $scope.inq.Qty === "" || $scope.inq.Qty == null || 
			$scope.inq.QtyDeviation === "" || $scope.inq.QtyDeviation == null || $scope.inq.Price === "" || $scope.inq.Price == null ||
			$scope.inq.TotalSum === "" || $scope.inq.TotalSum == null)
		{	msgBox("订单信息还未填写完整。",MSG_ERROR); 
			return; 
		}
		if($scope.inq.Qty == 0 || $scope.inq.Price == 0 || $scope.inq.TotalSum == 0)
		{	msgBox("数量、运价、运费不能为零。",MSG_ERROR); 
			return; 
		}
		
		postURL(URL_SET_INQUIRY,$scope.inq).then(function()
		{	$scope.list[rowIndex] = $scope.inq;
			$("#inqEditForm").modal("hide");
			msgBox("保存完成。");
			
		},function(err) { msgBox(utils.isDefined(err.message)?err.message:err,MSG_ERROR); });
	}
	
	//关闭
	$scope.close = function(idx)
	{	var item = $scope.list[idx];
		var msg = utils.sprintf("%s - %s<br>%s %0.1f 吨<br>由 %s 承运<br>即将关闭该报单，是否确定？",item.FromPortName,item.ToPortName,item.GoodsName,item.Qty,item.ShipName);
		msgBox(msg,MSG_WARNNING,MSG_CONFIRM).then(function()
		{	getURL(utils.sprintf(URL_CLOSE_INQUIRY,item.ID)).then(function()
			{	$scope.list[idx].State = INQ_STATE.INVALID;
				$scope.list[idx].StateText = utils.idToText($scope.list[idx].State,INQ_STATE_TEXT);
				$scope.list[idx].StateColor = utils.idToText($scope.list[idx].State,INQ_STATE_COLOR);
			});
		});
	}
	
	//接单
	$scope.order = function(idx)
	{	var item = $scope.list[idx];
		if(item.Price == 0 || item.Price === "")
		{	msgBox(utils.sprintf("运价还未确定！<br>请先联系%s，<br>商定并修改运价后再接单。",(item.SupplyUserID == $scope.CurrentUserID) ? "船东" : "货主"));
			return;
		}
		
		var msg = utils.sprintf("由 <b>%s</b> 承运<br> %s %0.1f±%d吨<br>运费 %0.2f元 <b class='color-orange'>%s</b><br>是否确认接单？",
								item.ShipName,item.GoodsName,item.Qty,item.QtyDeviation,item.TotalSum,(item.NeedOnlinePay == 1 ? "在线支付" : "线下支付"));
		msgBox(msg,MSG_WARNNING,MSG_CONFIRM).then(function()
		{	getURL(utils.sprintf(URL_ORDER_INQUIRY,item.ID)).then(function()
			{	$scope.list[idx].State = INQ_STATE.ORDER;
				$scope.list[idx].StateText = utils.idToText($scope.list[idx].State,INQ_STATE_TEXT);
				$scope.list[idx].StateColor = utils.idToText($scope.list[idx].State,INQ_STATE_COLOR);

				msgBox("接单完成。<br>订单已被移到“执行中”分组。");
			});
		});
	}
	
	//线下支付，完成订单
	$scope.done = function(idx)
	{	var item = $scope.list[idx];
		var msg = utils.sprintf("由 <b>%s</b> 承运<br> %s %0.1f±%d吨<br>运费 %0.2f元<br>是否确认完成该订单？",item.ShipName,item.GoodsName,item.Qty,item.QtyDeviation,item.TotalSum);
		
		if(item.NeedOnlinePay == 1) { msgBox("本单需要在线支付运费，不能这样操作。",MSG_ERROR); return; }
		
		msgBox(msg,MSG_WARNNING,MSG_CONFIRM).then(function()
		{	getURL(utils.sprintf(URL_DONE_INQUIRY,item.ID,item.NeedOnlinePay)).then(function()
			{	$scope.list[idx].State = INQ_STATE.DONE;
				$scope.list[idx].StateText = utils.idToText($scope.list[idx].State,INQ_STATE_TEXT);
				$scope.list[idx].StateColor = utils.idToText($scope.list[idx].State,INQ_STATE_COLOR);

				msgBox("订单已完成。");
			});
		});
	}
	
	//请求退款
	$scope.refund = function(idx)
	{
		var item = $scope.list[idx];
		
		var check = function(val)
		{	if(val == "")
			{	msgBox("退款原因不能为空。",MSG_ERROR);
				return false;
			}
			return true;
		}
		agMsg.prompt({title:"退款原因",text:"请输入退款原因",onClick:check}).then(function(val)
		{	postURL(URL_ASK_REFUND,{ID:item.ID,Reason:val}).then(function()
			{	$scope.list[idx].State = INQ_STATE.REFUND;
				$scope.list[idx].StateText = utils.idToText($scope.list[idx].State,INQ_STATE_TEXT);
				$scope.list[idx].StateColor = utils.idToText($scope.list[idx].State,INQ_STATE_COLOR);
			},function(err) { msgBox(getErrorMessage(err),MSG_ERROR); });
		});
	}
	
	//撤消请求退款
	$scope.abortRefund = function(idx)
	{
		var item = $scope.list[idx];
		
		msgBox("撤消当前订单的退款请求吗？",MSG_INFORMATION,MSG_CONFIRM).then(function()
		{	postURL(utils.sprintf(URL_ABORT_REFUND,item.ID)).then(function()
			{	$scope.list[idx].State = INQ_STATE.DEPOSIT;
				$scope.list[idx].StateText = utils.idToText($scope.list[idx].State,INQ_STATE_TEXT);
				$scope.list[idx].StateColor = utils.idToText($scope.list[idx].State,INQ_STATE_COLOR);
			},function(err) { msgBox(getErrorMessage(err),MSG_ERROR); });
		});
	}
});