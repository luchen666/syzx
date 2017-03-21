var ROWS_PER_PAGE	= 20;

//错误代码
var ERROR = {
	ACCOUNT_EXISTS			: {CODE:102,TEXT:"帐号已存在。"},
	ACCOUNT_NOT_EXISTS		: {CODE:103,TEXT:"帐号不存在。"},
	VCODE_ERROR				: {CODE:104,TEXT:"验证码错误。"},
	SHIPSCH_NOT_EXISTS		: {CODE:201,TEXT:"船期不存在或已删除。"},
	SUPPLY_NOT_EXISTS		: {CODE:301,TEXT:"货源不存在或已删除。"},
	INQUIRY_NOT_EXISTS		: {CODE:401,TEXT:"订单不存在或已关闭。"},
	INQUIRY_STATE_CHANGED	: {CODE:402,TEXT:"订单状态已被修改。"},
	INQUIRY_HAVE_CREATED	: {CODE:403,TEXT:"对应船期或货源的订单已经生成，不能接单。"},
	INQUIRY_NOT_MATCHED		: {CODE:404,TEXT:"订单信息不匹配。"},
	INQUIRY_IN_PAYING		: {CODE:405,TEXT:"订单处于支付锁定中，请一分钟后再试。"},
	PAY_GETWAY_ERROR		: {CODE:501,TEXT:"支付网关连接错误。"},
	PAY_PASSWORD_ERROR		: {CODE:502,TEXT:"支付密码错误。"},
	PAY_PASSWORD_EMPTY		: {CODE:503,TEXT:"支付密码不能为空。"},
	PAY_AMOUNT_ERROR		: {CODE:504,TEXT:"金额错误。"},
	PAY_BANKACCOUNT_ERROR	: {CODE:505,TEXT:"银行帐户错误。"},
	PAY_PARAM_INVALID		: {CODE:506,TEXT:"支付过程参数错误。"},
	PAY_BALANCE_INSUFFICIENT: {CODE:507,TEXT:"用户余额不足，无法完成支付。"},
	PAY_MONEY_ILLEGAL		: {CODE:508,TEXT:"金额错误。"},
}

function getErrorMessage(err)
{
	var msg = utils.isDefined(err.message)?err.message:err;
	if(utils.isDefined(err.code))
	{	for(p in ERROR) if(err.code == ERROR[p].CODE)
		{	msg =  ERROR[p].TEXT;
			break;
		}
	}
	return msg;
}
//用户状态
var USER_STATE_NONE		= 0;	//正常
var USER_STATE_BLOCK	= 10;	//锁定

//认证状态
var CERTIFY_STATE = {
	NONE	: 0,				//未认证
	WAIT	: 10,				//等待认证
	REJECT	: 20,				//未通过认证
	PASS	: 30				//通过认证
};
var CERTIFY_STATE_TEXT = [
	{ID: 0,		Text: "未认证"},
	{ID: 10,	Text: "等待认证"},
	{ID: 20,	Text: "未通过认证"},
	{ID: 30,	Text: "通过认证"}
];
var CERTIFY_STATE_COLOR = [
	{ID: 0,		Text: "dark"},
	{ID: 10,	Text: "orange"},
	{ID: 20,	Text: "red"},
	{ID: 30,	Text: "green"}
];
//付款方式
var PAYMENT_METHOD = {
	BEFOREUNLOAD	: 10,
	AFTERUNLOAD		: 20,
	EACHMONTH		: 30
};
var PAYMENT_METHOD_TEXT = [
	{ID: 10,	Text: "卸前付清"},
	{ID: 20,	Text: "卸后付清"},
	{ID: 30,	Text: "月结"}
];

//货源与船期状态
var SUPSCH_STATE = {
	NONE		: 0,			//正常
	ORDER		: 10,			//已成
	INVALID		: 99,			//失效
};
var SUPSCH_STATE_TEXT = [
	{ID: 0,		Text: "进行中"},
	{ID: 10,	Text: "已成交"},
	{ID: 99,	Text: "已关闭"}
];
var SUPSCH_STATE_COLOR = [
	{ID: 0,		Text: "orange"},
	{ID: 10,	Text: "blue"},
	{ID: 99,	Text: "gray"}
];
var INQ_STATE = {
	NONE		: 0,
	ORDER		: 10,
	DEPOSIT		: 20,
	REFUND		: 30,
	DONE		: 50,
	INVALID		: 99
};

var INQ_STATE_TEXT = [
	{ID: 0,		Text: "询价"},
	{ID: 10,	Text: "订单"},
	{ID: 20,	Text: "已付订金"},
	{ID: 30,	Text: "退款中"},
	{ID: 50,	Text: "完成"},
	{ID: 99,	Text: "关闭"}
];

var INQ_STATE_COLOR = [
	{ID: 0,		Text: "orange"},
	{ID: 10,	Text: "navy"},
	{ID: 20,	Text: "green"},
	{ID: 30,	Text: "red"},
	{ID: 50,	Text: "blue"},
	{ID: 99,	Text: "gray"},
];

var PAY_TYPE = {
	BALANCE	: 10,
	BANK	: 20
};
var PAY_TYPE_TEXT = [
	{ID	: 10,	Text: "余额"},
	{ID	: 20,	Text: "银行帐号"}
];

var BILL_STATE = {
	NONE 	: 0,
	VALID	: 1,
	INVALID	: -1
};
var BILL_STATE_TEXT = [
	{ID: 0,		Text: "待处理"},
	{ID: 1,		Text: "有效"},
	{ID: -1,	Text: "无效"},
];
var BILL_STATE_COLOR = [
	{ID: 0,		Text: "gray"},
	{ID: 1,		Text: "black"},
	{ID: -1,	Text: "red"}
];
var BILL_SOURCE = {
	PAY 	: 10,
	REFUND	: 20,
	RECHARGE: 30,
	CASH	: 40,
	SETTLE	: 50,
	ROLLBACK: 60
};
var BILL_SOURCE_TEXT = [
	{ID: 10,	Text: "支付"},
	{ID: 20,	Text: "退款"},
	{ID: 30,	Text: "充值"},
	{ID: 40,	Text: "提现"},
	{ID: 50,	Text: "结算"},
	{ID: 60,	Text: "回滚"},
];
var BILL_SOURCE_COLOR = [
	{ID: 10,	Text: "orange"},
	{ID: 20,	Text: "orange"},
	{ID: 30,	Text: "orange"},
	{ID: 40,	Text: "orange"},
	{ID: 50,	Text: "orange"},
	{ID: 60,	Text: "orange"},
];

var URL_LOGIN		= "/data/logincheck";
var URL_LOGOUT		= "/data/logout";

var URL_GET_SCHLIST	= "/data/shipList";

var URL_GET_SUPLIST	= "/ucenter/getSupplyList";
var URL_GET_SUPPLY	= "/ucenter/getSupplyByID?ID=%d";
var URL_SET_SUPPLY	= "/ucenter/setSupply";
var URL_DEL_SUPPLY	= "/ucenter/delSupplyByID?ID=%d";

var URL_GET_INQLIST		= "/ucenter/getInquiryList";
var URL_GET_INQUIRY 	= "/ucenter/getInquiryByID?ID=%d";
var URL_SET_INQUIRY		= "/ucenter/setInquiry";
var URL_CLOSE_INQUIRY	= "/ucenter/closeInquiry?ID=%d";
var URL_ORDER_INQUIRY	= "/ucenter/orderInquiry?ID=%d";
var URL_DONE_INQUIRY	= "/ucenter/doneInquiry?ID=%d&Pay=%d";
var URL_PAY_INQUIRY		= "/ucenter/payInquiryAct";
var URL_ASK_REFUND		= "/ucenter/askRefund";
var URL_ABORT_REFUND	= "/ucenter/abortRefund?ID=%d";

var URL_GET_PUBLICKEY	= "/ucenter/getPublicKey";
var URL_GET_BALANCE		= "/ucenter/getBalance";
var URL_GET_USERBILL	= "/ucenter/getUserBill";
var URL_GET_BANKLIST	= "/ucenter/getBankList";
var URL_SEND_VCODE		= "/ucenter/sendVCode";
var URL_BIND_BANKACCOUNT	= "/ucenter/bindAccount";
var URL_UNBIND_BANKACCOUNT	= "/ucenter/unbindAccount";
var URL_SET_PAYPASSWORD		= "/ucenter/setPayPassword";
var URL_ACC_RECHARGE		= "/ucenter/recharge";
var URL_ACC_TAKECASH		= "/ucenter/takeCash";


angular.module("syzx", []).directive("siteMenu", function()
{
	return {
		restrict	: "AE",
		replace		: true,
		template	: [	"<div class='outline-bottom'><div class='h100 contentbox'>",
			"<div class='top_logo'>",
			"<img src='/images/logo.png' height='100'>",
			"</div>",
			"<div class='top_tel'>",
			"<div class='font-24px'>服务热线</div>",
			"<div class='font-32px' style='line-height:60px;'>400 000 6680</div>",
			"</div>",
			"<div class='top_menu'>",
			"<div ng-if='user.IsLogin == 1' class='top_title'>",
			"{{user.Name}} 您好,水运在线欢迎您！　",
			"[<a href='/Ucenter/index' >我的办公室</a>]　",
			"[<a href='###' ng-click='logout();'>退出登录</a>]",
			"</div>",
			"<div ng-if='user.IsLogin == 0'  class='top_title'>",
			"[<a href='###' ng-click='login();'>登录</a>]　",
			"[<a href='/home/register' class='button'>注册</a>]",
			"</div>",
			"<div class='menu_item'>",
			"<a target='_top'   href='/' class='{{menuIndex == 1 ? \"menu_act\" : \"\"}}'>首 页</a>",
			"<a href='/home/ship' class='{{menuIndex == 2 ? \"menu_act\" : \"\"}}'>找 船</a>",
			"<a target='_blank' href='' class='{{menuIndex == 3 ? \"menu_act\" : \"\"}}'>保 险</a>",
			//"<a target='_blank' href='' class='menu_act3'>找 货</a>",
			"<a target='_blank' href='' class='{{menuIndex == 3 ? \"menu_act\" : \"\"}}'>保 理</a>",
			"<a target='_blank' href='' class='{{menuIndex == 4 ? \"menu_act\" : \"\"}}'>供 油</a>",
			//"<a target='_blank' href='' class='{{menuIndex == 5 ? \"menu_act\" : \"\"}}'>金 融</a>",
			"</div>",
			"</div>",
			"</div></div>"
		].join(""),
		controller	: ["$scope","dlgLogin","$element","$attrs",function($scope,dlgLogin,$element, $attrs)
		{	//移除遮罩层

			$(".pageloading").css("visibility","hidden");
			//注意，必须用 menu-index，此处才能使用menuIndex
			$scope.menuIndex = $attrs.menuIndex;
			//点击“登录”
			$scope.login  = function(){ dlgLogin.show(URL_LOGIN).then(function(data){$scope.user = data;});}
			//点击“退出登录”
			$scope.logout = function(){ msgBox("退出登录，是否确定？",MSG_INFORMATION,MSG_CONFIRM).then(function(){getURL(URL_LOGOUT);dlgLogin.hide(); $scope.user = {IsLogin:0}; });}
		}]
	};
})

.directive("siteFooter", function()
{
	return {
		restrict	: "AE",
		replace		: true,
		template	: ["<div class='footer'><div class='contentbox' style='text-align:center'>",
			"<div class='app'>",
			"<label>手机APP下载</label>",
			"<label>关注公众号</label>",
			"<div><img src='/images/qr-android.png'><br>ANDROID</div>",
			"<div><img src='/images/qr-ios.png'><br>IOS</div>",
			"<div><img src='/images/qr-mp.jpg'><br>卓越航运</div>",
			"</div>",
			"<div class='copyright'>",
			"COPYRIGHTⓇ 2014-2017 SYZX56 ALL RIGHT RESERVED<br>",
			"版权所有 水运在线 浙ICP备123456789",
			"</div>",
			"</div></div>"
		].join("")
	};
})

.directive("ucenterGuide", function()
{
	return {
		restrict	: "AE",
		replace		: true,
		template	: ["<div><img src='/{{user.Avatar}}' err-src='/images/defaultAvatar.jpg' />",
			"<hr />",
			"<p ng-if='user.IsLogin == 1'>{{user.Name}} ({{user.UserTypeText }})</p>",
			"<button ng-click='addSupply()' ng-if='subIndex==1'><i class='glyphicon glyphicon-plus color-white'></i>　发布货源</button>",
			"<ul class='left-ul'>",
			"<li class='{{subIndex==1?\"left-li-act\":\"\"}}'><a href='/ucenter/index'><i class='glyphicon glyphicon-inbox'></i>我的货源</a></li>",
			"<li class='{{subIndex==2?\"left-li-act\":\"\"}}'><a href='/ucenter/inquiry'><i class='glyphicon glyphicon-list-alt'></i>我的订单</a></li>",
			"<li class='{{subIndex==3?\"left-li-act\":\"\"}}'><a href='/ucenter/account'><i class='glyphicon glyphicon glyphicon-usd'></i>我的帐户</a></li>",
			"</ul>",
			"<ul class='left-ul'>",
			"<li class='titlebar' style='color:white'>我的资料</li>",
			"<!--- li><a href=''><i class='glyphicon glyphicon-envelope'></i>我的消息</a></li --->",
			"<li class='{{subIndex==4?\"left-li-act\":\"\"}}'><a href='/ucenter/setUser'><i class='glyphicon glyphicon-user'></i>修改资料</a></li>",
			"<li class='{{subIndex==5?\"left-li-act\":\"\"}}'><a href='/ucenter/setPass'><i class='glyphicon glyphicon-lock'></i>修改密码</a></li>",
			"</ul></div>",
		].join(""),
		controller	: ["$scope","$attrs",function($scope,$attrs)
		{
			var url = window.location.href;
			if(url.indexOf("/index") > -1)			$scope.subIndex = 1;
			else if(url.indexOf("/inquiry") > -1)	$scope.subIndex = 2;
			else if(url.indexOf("/account") > -1)	$scope.subIndex = 3;
			else if(url.indexOf("/setUser") > -1)	$scope.subIndex = 4;
			else if(url.indexOf("/setPass") > -1)	$scope.subIndex = 5;

			$scope.addSupply = function()
			{	 $scope.readOnly = 0;
				 $scope.order();
			}
		}]
	};
})

.directive("dlgPort", function()
{
	return {
		restrict	: "AE",
		replace		: true,
		template	: [	"<table class='xzj_dlgport'><tr><td class='xzj_dlgort_privince'><ul>",
			"<li ng-repeat='item in provList' ng-click='provClick($index);'  class='{{selectPort.provinceID == item.ID ? \"xzj_dlgport_curr\" : \"\"}}'>{{item.Name}}</li>",
			"</ul></td><td class='xzj_dlgport_city'><ul>",
			"<li ng-repeat='item in cityList' ng-click='cityClick($index);' class='{{selectPort.regionID == item.ID ? \"xzj_dlgport_curr\" : \"\"}}'>{{item.Name}}</li>",
			"</ul></td><td class='xzj_dlgport_port'><ul>",
			"<li ng-if='options.unLimited' ng-click='portClick(-1);' class='{{selectPort.portID == -1 ? \"xzj_dlgport_curr\" : \"\"}}'>不限</li>",
			"<li ng-repeat='item in portList' ng-click='portClick($index);' class='{{selectPort.portID == item.ID ? \"xzj_dlgport_curr\" : \"\"}}'>{{item.PortName}}</li>",
			"</ul></td></tr></table>"
		].join(""),
		controller	: ["$scope", "$sce",function($scope, $sce) { $scope.renderHtml = function(html_code) { return $sce.trustAsHtml(html_code); }}]
	};
})

.provider("dlgPort", function ()
{
	var defaults = {
		title			: "请选择港口",
		showTitle		: false,
		hideAfterSelect	: true,
		unLimited		: true,
		topOffset		: utils.isIE() ? 35 : -15
	};

	var dlgElement = null;
	var self = this;

	this.$get = [
		"$rootScope",
		"$compile",
		"$animate",
		"$q",
		"$http",
		"$timeout",
		function($rootScope,$compile,$animate,$q,$http,$timeout)
		{
			var $scope = $rootScope.$new(true);
			
			function dlgPort(obj,option)
			{
				var deferred = $q.defer();
				
				$(dlgElement).remove();
				
				if(option && !angular.isObject(option))
				{	console.log("dlgPort的第一个参数必须是json格式。");
					return $q.when();
				}

				//把参数合并到$scope的options中
				$scope.options = {};
				angular.extend($scope.options,defaults,option);

				//动态创建对话框
				dlgElement = $compile("<div dlg-port tabindex=100 ng-blur='blur()' ng-mouseleave='selectPort.inPortDlg = false;' ng-mouseenter='selectPort.inPortDlg = true;'></div>")($scope);
				angular.element(document.body).append(dlgElement);

				//指定对像，就显示在它下面，否则显示在页面中央
				if(obj)
				{	var p = {top : $(obj).offset().top + $(window).scrollTop() + $scope.options.topOffset, left : $(obj).offset().left + $(window).scrollLeft()};
					if(!utils.isIE())	$(dlgElement).css("margin-left","-50px").css("margin-top","0").offset(p);	
					else				$(dlgElement).offset(p);
				}
				$(dlgElement).show("fast").focus();

				$scope.provList = [];
				$scope.cityList = [];
				$scope.portList = [];

				//返回数据
				$scope.selectPort = {
					provinceID		: 0,
					provinceName	: "",
					regionID		: 0,
					regionName		: "",
					portID			: 0,
					portName		: "",
					inPortDlg		: false
				}

				//先获取省份列表
				getURL("/index/data/getRegionPort?GET=PROVINCE&ID=0").then(function(data)
				{	$scope.provList = data;
					$scope.provClick(0);
				});

				//======= 各列点击事件 ==========

				//点击“省份”
				$scope.provClick = function(idx)
				{	$scope.selectPort.provinceID = $scope.provList[idx].ID;
					$scope.selectPort.provinceName = $scope.provList[idx].Name;
					$scope.selectPort.regionID = 0;
					$scope.selectPort.regionName = "";
					$scope.selectPort.portID = 0;
					$scope.selectPort.portName = "";

					getURL("/index/data/getRegionPort?GET=CITY&ID="+$scope.provList[idx].ID).then(function(data)
					{	$scope.cityList = data;
						$scope.cityClick(0);
					},
					function(err)
					{	$scope.cityList = [];
						$scope.portList = [];
					});
				}

				//点击“城市”
				$scope.cityClick = function(idx)
				{	$scope.selectPort.regionID = $scope.cityList[idx].ID;
					$scope.selectPort.regionName = $scope.cityList[idx].Name;
					$scope.selectPort.portID = 0;
					$scope.selectPort.portName = "";

					getURL("/index/data/getRegionPort?GET=PORT&ID="+$scope.cityList[idx].ID).then(
						function(data) { $scope.portList = data; },
						function(err) { $scope.portList = [];}
					);
				}

				//点击港口
				$scope.portClick = function(idx)
				{	if($scope.options.hideAfterSelect)
					{	$("body").unbind("click");
						$(dlgElement).hide("fast");
					}
					
					$scope.selectPort.portID = idx == -1 ? 0 : $scope.portList[idx].ID;
					$scope.selectPort.portName = idx == -1 ? "" : $scope.portList[idx].PortName;
					
					deferred.resolve($scope.selectPort);
				};
				return deferred.promise;
			}

			dlgPort.show = function(obj,val)
			{	
				//防止一打开就关闭
				setTimeout(function()
				{	$("body").bind("click",function()
					{	if(!$scope.selectPort.inPortDlg)
						{	$(dlgElement).hide("fast");
							$("body").unbind("click");
						}
					});
				},2000);
				
				var deferred = $q.defer();
				dlgPort(obj,val).then(deferred.resolve, deferred.reject);
				return deferred.promise;
			}

			dlgPort.hide = function()
			{	$("body").unbind("click");
				$(dlgElement).hide("fast"); 
			}

			return dlgPort;
		}
	];
})

//============================ 以下是弹出式登录框 ==========================
//需要引用bootstrap.js
.directive("dlgLogin", function()
{
	return {
		restrict	: "AE",
		replace		: true,
		template	: [	"<div class='modal fade in' id='loginForm' style='top:50%;margin-top:-250px'  aria-hidden='true' ddata-backdrop='static'>",
			"<div class='modal-dialog' style='width:360px;margin: auto;'><div class='modal-content'>",
			"<div class='modal-header' style='height:30px;padding-top:0px;'>",
			"<button class='close' type='button' data-dismiss='modal'>×</button>",
			"<h4>登录</h4>",
			"</div>",
			"<div class='modal-body float-login-form'>",
			"<ul>",
			"<li><label>帐　号：</label><input type='text' placeholder='登录帐号' id='login_account'></li>",
			"<li><label>密　码：</label><input type='password' placeholder='密码' autocomplete='off' id='login_password'></li>",
			"<li><label>验证码：</label><input type='text' placeholder='验证码' id='login_vcode' style='width: '>",
			"<img style='' id='codeImg' src='/admin/login/code' alt='验证码' title='看不清，点击换一张'></li>",
			"</ul>",
			"<a href='/home/register'>注册帐号</a>",
			"<a href='/home/findPwd'>忘记密码</a>",
			"</div>",
			"<div class='modal-footer' style='text-align:center;'>",
			"<button ng-click='login()' class='btn btn-success' style='width:100px;'>登录</button> ",
			"</div>",
			"</div></div></div>"
		].join(""),
		controller	: ["$scope", function($scope) { }]
	};
})

.provider("dlgLogin", function()
{
	var dlgElement = null;
	var self = this;

	this.$get = [
		"$rootScope",
		"$compile",
		"$q",
		"$http",
		"$timeout",
		function($rootScope,$compile,$q,$http,$timeout)
		{
			function dlgLogin(url)
			{
				if(!utils.isDefined(url))	url = URL_LOGIN;

				var deferred = $q.defer();
				var $scope = $rootScope.$new(true);

				if(dlgElement != null)
				{	$("#loginForm").modal("show");
					return deferred.promise;
				}

				//动态创建对话框
				dlgElement = $compile("<div dlg-login></div>")($scope);
				angular.element(document.body).append(dlgElement);

				$("#login_account").val(utils.getCookie("Account"));
				$("#loginForm").modal("show");

				//点击“登录”按钮
				$scope.login = function()
				{
					if(document.all){ //判断浏览器IE上为true，火狐上为false
						window.event.returnValue = false;
						window.event.cancelBubble = true;
					}
					else{
						event.preventDefault();
						event.stopPropagation();
					};

					var data = {
						Account	 : $("#login_account").val(),
						Password : $("#login_password").val(),
						VCode	 : $("#login_vcode").val()
					}
					if(data.Account == "")	{	msgBox("帐号不能为空。");$("#login_account").focus(); return; }
					if(data.Password == "")	{	msgBox("密码不能为空。");$("#login_password").focus(); return; }
					if(data.VCode == "")	{	msgBox("验证码不能为空。");$("#login_vcode").focus(); return; }

					utils.showToast("登录中。。。",1500);

					postURL(url,data).then(function(ret)
						{
							utils.setCookie("Account",ret.Account);
							utils.setCookie("UseriD",ret.UserID);
							ret.IsLogin = 1;
							deferred.resolve(ret)
							$("#loginForm").modal("hide");
						},
						function(err)
						{	msgBox(utils.isDefined(err.message)?err.message:err,MSG_ERROR);
							deferred.reject(err);
						});
				}
				return deferred.promise;
			}

			dlgLogin.show = function(url)
			{	var deferred = $q.defer();
				dlgLogin(url).then(deferred.resolve,deferred.reject);
				return deferred.promise;
			}

			dlgLogin.hide = function()
			{	$("#loginForm").modal("hide");
				if(dlgElement != null)
				{	$(dlgElement).remove();
					dlgElement = null;
				}
			}

			return dlgLogin;
		}
	];
});