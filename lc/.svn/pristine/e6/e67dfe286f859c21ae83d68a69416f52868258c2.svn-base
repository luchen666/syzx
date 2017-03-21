angular.module("index",["utils","syzx"]).controller("dataCtrl",function($scope,$http,$timeout,dlgPort,dlgLogin)
{   
	$scope.user = currUser != null ? currUser : {IsLogin:false};
	
	if(data)
	{	$scope.TodayTransportPrice = data.TodayTransportPrice;		//今日运价
		$scope.TodaySchNumber = data.TodaySchNumber;				//今日船期
		$scope.TodaySupplyNumber = data.TodaySupplyNumber;			//今日货源	
		$scope.SchTotal = data.SchTotal;							//总船期
		$scope.TodayDealOrder = data.TodayDealOrder;				//成交公告
		$scope.schList = data.ShipSch;								//船期列表
	}
	//======================= 空船港口选择对话框 =============================
	$scope.showPortDlg = function()
	{
		dlgPort.show($("#portBox")).then(function(data)
		{
			$scope.search.ClearPortRegionID = data.regionID;
			$scope.search.ClearPortID = data.portID;
		
			if(data.provinceName == data.regionName)
				$("#clearPortText").val(data.provinceName + " " + data.portName);
			else	
				$("#clearPortText").val(data.provinceName + " " + data.regionName + " " + data.portName);
		});
	}
	
	//======================= 空船时间选择对话框 ==============================
	var makeCelarDateText = function()
	{	if($scope.search.ClearDateFrom.parseDate() > $scope.search.ClearDateTo.parseDate())
		{	var temp = $scope.search.ClearDateFrom;
			$scope.search.ClearDateFrom = $scope.search.ClearDateTo;
			$scope.search.ClearDateTo = temp;
		}
		$("#clearDateText").val($scope.search.ClearDateFrom + " 到 " + $scope.search.ClearDateTo);
	}
	
	$("#dlgClearDateFrom").datepicker({"onSelect":function(value,obj) 
	{	$scope.search.ClearDateFrom = value; 
		if($scope.search.ClearDateTo != "")	
		{	$(".clearDateDlg").hide("fast");
			makeCelarDateText();
		}
	}});
	$("#dlgClearDateTo").datepicker({"onSelect":function(value,obj) 
	{	$scope.search.ClearDateTo = value; 
		if($scope.search.ClearDateFrom != "")	
		{	$(".clearDateDlg").hide("fast");
			makeCelarDateText();
		}
	}});
	$("#dlgClearDateFrom").datepicker("setDate","");
	$("#dlgClearDateTo").datepicker("setDate","+7d");
	
	var clearDateTimer = null;
	$scope.showClearDateDlg = function()
	{	
		$(".clearDateDlg").show("fast").focus().unbind().bind("blur",function()
		{	//失去焦点，如果是点击日期，不关闭选择框，否则就关闭它
			var oldFrom = $scope.search.ClearDateFrom;
			var oldTo =  $scope.search.ClearDateTo;
			
			$timeout.cancel(clearDateTimer);
			clearDateTimer = $timeout(function()
			{	//是点击了datapicker
				if(oldFrom != $scope.search.ClearDateFrom || oldTo !=  $scope.search.ClearDateTo)
					$(".clearDateDlg").focus();
				else
					$(".clearDateDlg").hide("fast");
			},200);
		});
	}
	
	//======================= 吨位选择 ========================
	$scope.showTonnageDlg = function()
	{	
		$(".tonnageDlg").show("fast").focus().unbind().bind("blur",function() { $(this).hide("fast"); });
		
		$(".tonnageDlg li").bind("click",function(e)
		{	
			$("#tonnageText").val($(this).index()==0?"":this.innerText);
			$(".tonnageDlg").hide("fast");
			
			switch($(this).index())
			{
				case 0:	$scope.search.TonnageFrom = 0;
						$scope.search.TonnageTo = 0;
						break;
				case 1:	$scope.search.TonnageFrom = 0;
						$scope.search.TonnageTo = 1000;
						break;
				case 2:	$scope.search.TonnageFrom = 1001;
						$scope.search.TonnageTo = 3000;
						break;
				case 3:	$scope.search.TonnageFrom = 3001;
						$scope.search.TonnageTo = 5000;
						break;
				case 4:	$scope.search.TonnageFrom = 5001;
						$scope.search.TonnageTo = 10000;
						break;
				case 5:	$scope.search.TonnageFrom = 10001;
						$scope.search.TonnageTo = 30000;
						break;
				case 6:	$scope.search.TonnageFrom = 30000;
						$scope.search.TonnageTo = 0;
						break;
			}
			if(document.all){
				window.event.returnValue = false;
				window.event.cancelBubble = true;
			}
			else{
				var event = e || event;
				event.preventDefault();
				event.stopPropagation();
			};
		});
	}
	
	$scope.doSearch = function()
	{
		if($scope.search.ClearPortRegionID == 0 && $scope.search.ClearDateFrom == "" && $scope.search.TonnageFrom == 0 && $scope.search.TonnageTo ==0)
		{	msgBox("请至少输入一个条件。");
			return;
		}
		window.location.href = utils.sprintf("home/ship/?ClearPortRegionID=%d&ClearPortID=%d&ClearDateFrom=%s&ClearDateTo=%s&TonnageFrom=%d&TonnageTo=%d",
								$scope.search.ClearPortRegionID,$scope.search.ClearPortID,
								$scope.search.ClearDateFrom,$scope.search.ClearDateTo,
								$scope.search.TonnageFrom,$scope.search.TonnageTo);
	}
	//msgBox("至少需要选择一个条件。");
	$scope.search = {
		ClearPortRegionID	: 0,
		ClearPortID			: 0,
		ClearDateFrom		: "",//(new Date()).format("yyyy-mm-dd"),
		ClearDateTo			: "",//(new Date()).add("d",5).format("yyyy-mm-dd"),
		TonnageFrom			: 0,
		TonnageTo			: 0
	};
	fnSelectTonnage = $scope.tonnageSelected;

	//发布船期货源	填写真实的找船、找货等需求，并留下联系方式，我们收到后会立即给您回电确认，并免费帮您匹配!
	$scope.publish = function()
	{	var	publishValue = $("#publishValue").val();
		if(publishValue == "" || publishValue.substring(0,13) == "填写真实的找船、找货等需求" || publishValue == " ")	return;
		var data = {	Content	:	publishValue	}
		postURL("/data/AddUserRequest",data).then(function(data) {	msgBox("发布成功!") })
	}
});

$(function(){ $(".pageloading").css("visibility","hidden");}); 

//数据滚动 start（今日运价参考表、成交公告）
var scrollTimer;

$("#index-piceUl").hover(
	function() { clearInterval(scrollTimer);  },
	function() { scrollTimer = setInterval(function() { scrollNews($("#index-piceUl"),"div","ul:first"); }, 2000); }
).trigger("mouseleave");

var scrollTimer2;

$("#index-priceUl2").hover(
	function() { clearInterval(scrollTimer2); },
	function() { scrollTimer2 = setInterval(function() { scrollNews($("#index-priceUl2"),"div","ul:first"); }, 2000); }
).trigger("mouseleave");

function scrollNews(obj,objChild,objFirstChild)
{
    var $objChild = obj.find(objChild);
	var lineHeight = $objChild.find(objFirstChild).height();
    $objChild.animate(	{"marginTop": -lineHeight + "px"},600,function() {	$objChild.css({marginTop: 0}).find("ul:first").appendTo($objChild);  });
}
//数据滚动 end

//轮播图start
var bannerTimer;
bannerTimer = setInterval(function()
{	$("#banner ul").animate({marginTop:"448px"},1500,function()
	{	$("#banner ul>li").eq(2).prependTo($("#banner ul"));
		$("#banner ul").css("marginTop","0px");
	});
},8000);
//轮播图end