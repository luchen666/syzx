angular.module("syzxApp",["utils","syzx"]).controller("dataCtrl",function($scope,$timeout,dlgPort,dlgLogin)
{
	
	//船舶信息栏，遇顶固定
	$(".shipInfoBox").smartFloat();	
	
	$scope.user = currUser != null ? currUser : {IsLogin:false};
	
	$scope.list = (schList) ? schList : [];
	$scope.ship = $scope.list[0];
	
	//最小ID
	var minId = $scope.list.length == 0 ? -1 : $scope.list[$scope.list.length-1].ID;
	
	//是否显示more按钮
	$scope.showMoreButton = $scope.list.length >= 20;
	
	//初始化日期选择框
	$("#searchDateFrom").datepicker();
	$("#searchDateTo").datepicker();
	$("#addDateFrom").datepicker();
	$("#addDateTo").datepicker();
	
	//搜索参数
	$scope.search = {
		ClearPortRegionID	: null,
		ClearPortID			: null,
		ClearDateFrom		: "",
		ClearDateTo			: "",
		TonnageFrom			: null,
		TonnageTo			: null,
		ShipName			: null
	};
	
	$scope.PAYMENT_METHOD_TEXT = PAYMENT_METHOD_TEXT;
	$scope.PACKAGE_METHOD = PACKAGE_METHOD;
	
	$scope.supply = {};
	
	//=============================================================================================
	 
	//点击船期行，显示船舶信息
	$scope.showShip = function(idx) { $scope.ship = $scope.list[idx]; }
	
	//数据载入
	$scope.more = function(all)
	{	
		var d = utils.clone($scope.search);
		
		d.ID = all ? -1 : minId;
		d.ACT = "OLD";
		
		utils.showToast("获取中。。。",1000);
		
		postURL(URL_GET_SCHLIST,d).then(function(data)
		{
			//保存最小的id
			minId = data[data.length-1].ID;
			
			//是否显示more按钮
			$scope.showMoreButton = (data.length >= 20);
			
			//添加到数组的末尾
			if(all)	$scope.list = data;
			else	Array.prototype.push.apply($scope.list,data);
			
			$scope.ship = $scope.list[0];
		},
		function(err)
		{	$scope.list = [];
			$scope.showMoreButton = false;
		});
	}
	
	//发布：始发港
	$scope.pickFromPort = function()
	{	dlgPort.show($("#fromPortText"),{unLimited:0}).then(function(data)
		{	$scope.supply.FromPortID = data.portID;
			$scope.supply.FromPortName = data.portName;
			if(data.regionName == data.portName)	$("#fromPortText").val(data.portName);
			else									$("#fromPortText").val(data.regionName + " " + data.portName);
		});
	}
	//发布：抵达港
	$scope.pickToPort = function()
	{	dlgPort.show($("#toPortText"),{unLimited:0}).then(function(data)
		{	$scope.supply.ToPortID = data.portID;
			$scope.supply.ToPortName = data.portName;
			if(data.regionName == data.portName)	$("#toPortText").val(data.portName);
			else									$("#toPortText").val(data.regionName + " " + data.portName);
		});
	}
	
	//搜索：空船港口选择对话框
	$scope.showPortDlg = function()
	{
		dlgPort.show(event.srcElement).then(function(data)
		{	$scope.search.ClearPortRegionID = data.regionID;
			$scope.search.ClearPortID = data.portID;
			
			if(data.provinceName == data.regionName)
				$("#portText").val(data.provinceName + " " + data.portName);
			else	
				$("#portText").val(data.provinceName + " " + data.regionName + " " + data.portName);
		});
	}
	
	//搜索：点击“清空”按钮
	$scope.clear = function()
	{	$scope.search = {
			ClearPortRegionID	: null,
			ClearPortID			: null,
			ClearDateFrom		: "",
			ClearDateTo			: "",
			TonnageFrom			: null,
			TonnageTo			: null,
			ShipName			: null
		};
		$("#portText").val("");
		$("#dateFrom").val("");
		$("#dateTo").val("");
		
		$scope.more(1);
	}
	
	//点击“搜索”按钮
	$scope.doSearch = function()
	{	
		$scope.search.ClearDateFrom = $("#dateFrom").val();
		$scope.search.ClearDateTo = $("#dateTo").val();
		$scope.search.ShipName = $("#shipName").val();
		
		if( $scope.search.ClearPortRegionID == null && $scope.search.ClearDateFrom == "" && $scope.search.TonnageFrom == null && $scope.search.TonnageTo == null && $scope.search.ShipName == "")
		{	msgBox("请至少输入一个条件。");
			return;
		}
		
		utils.showToast("获取中。。。",1000);
		
		$scope.more(1);
	}
	
	//报盘
	$scope.addSupply = function(idx)
	{
		var fn = function()
		{	$scope.listRowIndex	= -1;
			
			var item = {};
			if(idx != -1)
			{	$scope.listRowIndex	= idx;
				item = $scope.list[idx];
			}
				
			$scope.supply = {
				ID				: 0,
				ShipSchID		: (idx == -1) ? "": item.ID,
				SeaOrRiver		: (idx == -1) ? 1 : item.SeaOrRiver,
				PackageMethod	: PACKAGE_METHOD[0].ID,
				FromPortID		: (idx == -1) ? "" : item.ClearPortID,
				FromPortName	: (idx == -1) ? "" : item.ClearPortName,
				LoadDateFrom	: ((idx == -1) ? (new Date()).add("d",3) : item.ClearDate.parseDate()).format("yyyy-mm-dd"),
				LoadDateTo		: ((idx == -1) ? (new Date()).add("d",7) : item.ClearDate.parseDate().add("d",3)).format("yyyy-mm-dd"),
				Qty				: (idx == -1) ? "" : parseFloat(item.Tonnage),
				QtyDeviation	: 10,
				GoodsName		: "",
				ToPortID		: "",
				ToPortName		: "",
				Price			: "",
				TaxInclusive	: "0",
				LoadRatio		: "",
				Deposit			: "",
				PaymentMethod	: $scope.PAYMENT_METHOD_TEXT[0].ID,
				Memo			: "",
				NeedAgent		: "0",
			};
			
			$("#addSupplyForm").modal("show");

			if(idx != -1)
			{	$("#fromPortText").val(item.ClearPortName);
			}
			else
			{	$("#fromPortText").val("");
				$("#toPortText").val("");
			}
		}

		if(!$scope.user.IsLogin)
		{	dlgLogin.show().then(function(data)
			{	$scope.user = data;
				fn();
			});
		} else  fn();
	}
	
	//保存货源
	$scope.save = function()
	{
		if($scope.supply.FromPortID == "" || $scope.supply.ToPortID == "" ) { msgBox("起运港与抵达港不能空。",MSG_ERROR); return; }
		if($scope.supply.LoadDateFrom == "" || $scope.supply.LoadDateTo == "" ) { msgBox("受载起始日期与受载截止日期不能为空。",MSG_ERROR); return; }
		if($scope.supply.Qty == "" || $scope.supply.QtyDeviation == "" ) { msgBox("运输数量与偏差数量不能为空。",MSG_ERROR); return; }
		if($scope.supply.GoodsName == "" ) { msgBox("货物名称不能为空。",MSG_ERROR); return; }
		
		postURL(URL_SET_SUPPLY,$scope.supply).then(function()
		{
			$("#addSupplyForm").modal("hide");
			if($scope.listRowIndex >= 0) $scope.list[$scope.listRowIndex].IpostSupply = 1;
			msgBox("货源已发布，请留意船主回复。")
		
		},function(err) { msgBox(utils.isDefined(err.message)?err.message:err,MSG_ERROR); });
	}
	
	//点击“显示历史船期”按钮
	$scope.showHistory = function(id)
	{
		$("#shipModal").modal("show");
	}
});