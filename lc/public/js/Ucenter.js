angular.module("index",["utils","syzx"]).controller("dataCtrl",function($scope,$http,$timeout,dlgPort,dlgLogin)
{   
	$scope.user = currUser != null ? currUser : {IsLogin:false};
	$scope.list = (list) ? list : [];
	$scope.STATE = SUPSCH_STATE;
	$scope.PACKAGE_METHOD = PACKAGE_METHOD;
	$scope.PAYMENT_METHOD_TEXT = PAYMENT_METHOD_TEXT;
	
	//是否显示more按钮
	$scope.showMoreButton = $scope.list.length >= ROWS_PER_PAGE;
	
	for(var i=0;i<$scope.list.length;i++)
	{	if($scope.list[i].FromRegion != $scope.list[i].FromPortName) 
			$scope.list[i].FromPlace = $scope.list[i].FromRegion + " " + $scope.list[i].FromPortName;
		else
			$scope.list[i].FromPlace = $scope.list[i].FromRegion;
		
		if($scope.list[i].ToRegion != $scope.list[i].ToPortName) 
			$scope.list[i].ToPlace = $scope.list[i].ToRegion + " " + $scope.list[i].ToPortName;
		else
			$scope.list[i].ToPlace = $scope.list[i].ToRegion;
		
		$scope.list[i].PaymentMethodText = utils.idToText($scope.list[i].PaymentMethod,PAYMENT_METHOD_TEXT);
		$scope.list[i].StateText = utils.idToText($scope.list[i].State,SUPSCH_STATE_TEXT);
		$scope.list[i].StateColor = utils.idToText($scope.list[i].State,SUPSCH_STATE_COLOR);
		
		if($scope.list[i].Deposit == 0)	$scope.list[i].Deposit = "";
	}
	
	//==============================================
	//数据载入
	var minId = $scope.list.length > 0 ? $scope.list[$scope.list.length-1].ID : -1;
	$scope.more = function(all)
	{	var d = {ID:all?-1:minId,ACT:all?"NEW":"OLD"};
		utils.showToast("获取中。。。",1000);
		postURL(URL_GET_SUPLIST,d).then(function(data)
		{
			//保存最小的id
			minId = data[data.length-1].ID;
			
			//是否显示more按钮
			$scope.showMoreButton = (data.length >= ROWS_PER_PAGE);
			for(var i=0;i<data.length;i++)
			{	if(data[i].FromRegion != data[i].FromPortName) 
					data[i].FromPlace = data[i].FromRegion + " " + data[i].FromPortName;
				else
					data[i].FromPlace = data[i].FromRegion;
				
				if(data[i].ToRegion != data[i].ToPortName) 
					data[i].ToPlace = data[i].ToRegion + " " + data[i].ToPortName;
				else
					data[i].ToPlace = data[i].ToRegion;
				
				data[i].PaymentMethodText = utils.idToText(data[i].PaymentMethod,PAYMENT_METHOD_TEXT);
				data[i].StateText = utils.idToText(data[i].State,SUPSCH_STATE_TEXT);
				data[i].StateColor = utils.idToText(data[i].State,SUPSCH_STATE_COLOR);
				
				if(data[i].Deposit == 0)	data[i].Deposit = "";
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
	
		//发布：始发港
	$scope.pickFromPort = function()
	{	dlgPort.show($("#fromPortText"),{topOffset:-25,unLimited:0}).then(function(data)
		{	$scope.supply.FromPortID = data.portID;
			$scope.supply.FromPortName = data.portName;
			if(data.regionName == data.portName)	$("#fromPortText").val(data.portName);
			else									$("#fromPortText").val(data.regionName + " " + data.portName);
		});
	}
	//发布：抵达港
	$scope.pickToPort = function()
	{	dlgPort.show($("#toPortText"),{topOffset:-25,unLimited:0}).then(function(data)
		{	$scope.supply.ToPortID = data.portID;
			$scope.supply.ToPortName = data.portName;
			if(data.regionName == data.portName)	$("#toPortText").val(data.portName);
			else									$("#toPortText").val(data.regionName + " " + data.portName);
		});
	}
	
	//编辑或查看货源
	var rowIndex = -1;
	$scope.editSupply = function(idx) {  $scope.readOnly = 0;$scope.order(idx); }
	$scope.showSupply = function(idx)
	{	var theEvent = window.event || arguments.callee.caller.arguments[0];
		var obj = theEvent.srcElement ? theEvent.srcElement : theEvent.target;
		
		if(obj.tagName == "A" || $(obj).index() == 8)	return;
		
		$scope.readOnly = 1;
		$scope.order(idx);
	}
	
	$scope.order = function(idx)
	{
		if(!utils.isDefined(idx))	idx = -1;
		
		rowIndex = idx;
		
		if(idx == -1)
		{	$scope.supply = {
				ID				: 0,
				SeaOrRiver		: 1,
				PackageMethod	: PACKAGE_METHOD[0].ID,
				FromPortID		: "",
				FromPortName	: "",
				LoadDateFrom	: (new Date()).add("d",3).format("yyyy-mm-dd"),
				LoadDateTo		: (new Date()).add("d",7).format("yyyy-mm-dd"),
				Qty				: "",
				QtyDeviation	: 10,
				GoodsName		: "",
				ToPortID		: "",
				ToPortName		: "",
				Price			: "",
				TaxInclusive	: "0",
				LoadRatio		: "",
				Deposit			: "",
				PaymentMethod	: PAYMENT_METHOD_TEXT[0].ID,
				Memo			: "",
				NeedAgent		: "0",
			};
			
			$("#supForm").on("shown.bs.modal",function (e)
			{	if(!utils.isIE())	return;
				var obj =  $("#supForm .modal-dialog");
				var left = $(window).width() / 2 - obj.width() / 2; 
				obj.css("margin-left",left); 
			}).modal("show");
			$("#addDateFrom").datepicker();
			$("#addDateTo").datepicker();
			$("#fromPortText").val("");
			$("#toPortText").val("");
			
			$("#supForm input").each(function() { $(this).attr("disabled",$scope.readOnly==1);  }); 
			$("#supForm select").each(function() { $(this).attr("disabled",$scope.readOnly==1);  });
		
		} else {  getURL(utils.sprintf(URL_GET_SUPPLY,$scope.list[idx].ID)).then(function(data)
		{
			$scope.supply = {
				ID				: $scope.list[idx].ID,
				SeaOrRiver		: data.SeaOrRiver,
				PackageMethod	: data.PackageMethod,
				FromPortID		: data.FromPortID,
				FromPortName	: data.FromPortName,
				LoadDateFrom	: data.LoadDateFrom,
				LoadDateTo		: data.LoadDateTo,
				Qty				: parseFloat(data.Qty),
				QtyDeviation	: parseFloat(data.QtyDeviation),
				GoodsName		: data.GoodsName,
				ToPortID		: data.ToPortID,
				ToPortName		: data.ToPortName,
				Price			: parseFloat(data.Price),
				TaxInclusive	: data.TaxInclusive,
				LoadRatio		: parseFloat(data.LoadRatio),
				Deposit			: parseFloat(data.Deposit),
				PaymentMethod	: data.PaymentMethod,
				Memo			: data.Memo,
				NeedAgent		: data.NeedAgent,
			};
			
			$("#supForm").on("shown.bs.modal",function (e)
			{	if(!utils.isIE())	return;
				var obj =  $("#supForm .modal-dialog");
				var left = $(window).width() / 2 - obj.width() / 2; 
				obj.css("margin-left",left); 
			}).modal("show");
			
			$("#addDateFrom").datepicker();
			$("#addDateTo").datepicker();
			$("#fromPortText").val(data.FromPortName);
			$("#toPortText").val(data.ToPortName);
			
			$("#supForm input").each(function() { $(this).attr("disabled",$scope.readOnly==1);  }); 
			$("#supForm select").each(function() { $(this).attr("disabled",$scope.readOnly==1);  });
			
		},function(err) { msgBox(err.message?err.message:err,MSG_ERROR); })}
	}
	
	//保存
	$scope.save = function()
	{	
		$scope.supply.GoodsName = $("#GoodsName").val();
		
		if($scope.supply.FromPortID == "" || $scope.supply.ToPortID == "" ) 		{ msgBox("起运港与抵达港不能空。",MSG_ERROR); return; }
		if($scope.supply.LoadDateFrom == "" || $scope.supply.LoadDateTo == "" )	{ msgBox("受载起始日期与受载截止日期不能为空。",MSG_ERROR); return; }
		if($scope.supply.Qty == "" || $scope.supply.QtyDeviation == "" )			{ msgBox("运输数量与偏差数量不能为空。",MSG_ERROR); return; }
		if($scope.supply.GoodsName == "" )											{ msgBox("货物名称不能为空。",MSG_ERROR); return; }
		
		postURL(URL_SET_SUPPLY,$scope.supply).then(function()
		{
			$("#supForm").modal("hide");
			
			if($scope.supply.ID == 0)
			{	msgBox("货源已发布，请留意船主回复。")
			}
			else
			{	msgBox("保存完成。");
				
				$scope.supply.FromPlace = $scope.supply.FromPortName;
				$scope.supply.ToPlace = $scope.supply.ToPortName;
				$scope.supply.PaymentMethodText = utils.idToText($scope.supply.PaymentMethod,PAYMENT_METHOD_TEXT);
				$scope.supply.State = $scope.list[rowIndex].State;
				$scope.supply.StateText = $scope.list[rowIndex].StateText;
				$scope.supply.StateColor = $scope.list[rowIndex].StateColor;
				$scope.supply.CreateDate = $scope.list[rowIndex].CreateDate;
				
				$scope.list[rowIndex] = $scope.supply;
			}
		
		},function(err) { msgBox(utils.isDefined(err.message)?err.message:err,MSG_ERROR); });
	}
	
	//删除
	$scope.del = function(idx)
	{	var item = $scope.list[idx];
		var msg = utils.sprintf("%s - %s<br>%s %0.1f 吨<br>即将删除该货源，是否确定？",item.FromPlace,item.ToPlace,item.GoodsName,item.Qty);
		msgBox(msg,MSG_WARNNING,MSG_CONFIRM).then(function()
		{	getURL(utils.sprintf(URL_DELSUPPLY,item.ID)).then(function()
			{	$scope.list.splice(idx,1);
			});
		});
	}
});