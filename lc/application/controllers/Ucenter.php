<?php
include_once(APP_PATH . "/utils/ResponseResult.php");
include_once(APP_PATH . "/utils/Utils.php");
include_once(MODELS_PATH . "/common.php");

//登录用户
session_start();
if(!IsSet($_SESSION["User"]) || !IsSet($_SESSION["User"]["IsLogin"]) || $_SESSION["User"]["IsLogin"] != 1)
{	header("location:/home/login/");
	exit;
}

$CurrentUser = $_SESSION["User"];
$CurrentUserID = $_SESSION["User"]["UserID"];

class UCenterController extends BaseController 
{
	//==================================== 视图页面 =======================================
	//首页，我的货源
	public function indexAction()
	{	
		global $CurrentUser,$CurrentUserID;
		require_once(MODELS_PATH . "/api2.0/Supply.php");
	
		//货源列表
		$ret = Supply::apiGetList("NEW",-1,ROWS_PER_RETRIEVE,array("UserID"=>$CurrentUserID));
		$list = ($ret == null) ? "[]" : json_encode($ret,JSON_UNESCAPED_UNICODE);
		
		//包装方式
		$packageMethod = Common::getPackageMehtodList();
		
		//填充到模板变量
		$this->getView()->assign("list",$list);
		$this->getView()->assign("user",json_encode($CurrentUser));
		$this->getView()->assign("packageMethod",json_encode($packageMethod));
		
		$this->getView()->Display("ucenter.htm");
	}
	
	//我的订单
	public function inquiryAction()
	{	
		global $CurrentUser,$CurrentUserID;
		require_once(MODELS_PATH . "/api2.0/Inquiry.php");
		
		//货源列表
		$ret = Inquiry::apiGetList("NEW",-1,ROWS_PER_RETRIEVE,array("SUPSCHUserID"=>$CurrentUserID));
		$list = ($ret == null) ? "[]" : json_encode($ret,JSON_UNESCAPED_UNICODE);
		
		//包装方式
		$packageMethod = Common::getPackageMehtodList();
		
		//填充到模板变量
		$this->getView()->assign("list",$list);
		$this->getView()->assign("user",json_encode($CurrentUser));
		$this->getView()->assign("packageMethod",json_encode($packageMethod));
		
		$this->getView()->Display("myInquiry.htm");
	}
	
	//我的帐户
	public function accountAction()
	{	global $CurrentUser,$CurrentUserID;
		require_once(MODELS_PATH . "/api2.0/account.php");
		
		$dateFrom = Date("Y-m-d",strtotime(Date("Y-m-d") ." -3 month"));
		$dateTo = Date("Y-m-d");
		
		//用户流水
		$ret = common::getUserBillList("NEW",-1,$CurrentUserID,$dateFrom,$dateTo,ROWS_PER_RETRIEVE);
		$list = ($ret == null) ? "[]" : json_encode($ret,JSON_UNESCAPED_UNICODE);
		
		$bankAcc = Account::apiGetBankAccount($CurrentUserID);
		
		if($bankAcc == null)
		{	$bankAcc = array("CardNo"=>"");
		}
		else if($bankAcc["CardNo"] != "")
		{	unset($bankAcc["EncodeCardNo"]);
			unset($bankAcc["EncodeCardName"]);
			$bankAcc["CardNo"] = left($bankAcc["CardNo"],4) . " **** **** " . right($bankAcc["CardNo"],4);
		}
		$bankAcc["Balance"] = Account::apiGetUserBalance($CurrentUserID);
		
		//填充到模板变量
		$v = $this->getView();
		$v->assign("dateFrom",$dateFrom);
		$v->assign("dateTo",$dateTo);
		$v->assign("user",json_encode($CurrentUser));
		$v->assign("bankAcc",json_encode($bankAcc));
		$v->assign("list",$list);
		
		$this->getView()->Display("account.htm");
	}

	//===========我的资料==============
	//我的消息

	//修改资料
	public function setUserAction()
	{	global $CurrentUser,$CurrentUserID;
		require_once(MODELS_PATH . "/api2.0/Supply.php");

		//填充到模板变量
		$this->getView()->assign("user",json_encode($CurrentUser));
		$this->getView()->Display("setUser.htm");
	}

	//修改密码页面
	public function setPassAction()
	{	global $CurrentUser,$CurrentUserID;
		require_once(MODELS_PATH . "/api2.0/Supply.php");

		//填充到模板变量
		$this->getView()->assign("user",json_encode($CurrentUser));
		$this->getView()->Display("setPass.htm");
	}
	//修改资料页面
	public function upInfoAction()
	{	global $CurrentUser,$CurrentUserID;
		$post = $this->Post;
		if(!IsSet($post["UserID"]) || !Is_Numeric($post["UserID"]) || !IsSet($post["Name"])|| !IsSet($post["Sex"])|| !IsSet($post["MobilePhone"])|| !IsSet($post["TelePhone"])|| !IsSet($post["Email"])|| !IsSet($post["Duty"])|| !IsSet($post["Avatar"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}

		$ret = common::setUser($post["UserID"],$post["Name"],$post["Sex"],$post["MobilePhone"],$post["TelePhone"],$post["Email"],$post["Duty"],$post["Avatar"]);
		echo (new ResponseResult($ret == true,"查无数据",0,$ret))->AsJson();
	}

	//修改密码
	public function changePwdAction()
	{	global $CurrentUser,$CurrentUserID;
		$post = $this->Post;
		if(!IsSet($post["Password0"])|| !IsSet($post["Password1"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$ret = common::changePwd($CurrentUserID,$post["Password0"],$post["Password1"]);
		if($ret == -2)	echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
		else echo (new ResponseResult($ret === true,"查无数据",0,$ret))->AsJson();
	}

	//获取当前用户资料
	public function getUserAction()
	{	global $CurrentUser,$CurrentUserID;
		$ret = common::getUser($CurrentUserID);
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
	}

	//支付
	public function payInquiryAction()
	{
		if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
		{	echo "<script>window.close();</script>";
			return;
		}
		
		global $CurrentUser,$CurrentUserID;
		require_once(MODELS_PATH . "/api2.0/Inquiry.php");
		require_once(MODELS_PATH . "/api2.0/Account.php");
		
		//订单详情,如果不需要在线支付，不用打开该页
		$item = Inquiry::apiGetInquiryByID($_GET["ID"],$CurrentUserID);
		if($item == null || $item["NeedOnlinePay"] == 0 || ($item["State"] != INQ_STATE_ORDER && $item["State"] != INQ_STATE_DEPOSIT))
		{	echo "<script>window.close();</script>";
			return;
		}
		
		//绑定银行卡号
		$bankAcc = Account::apiGetBankAccount($CurrentUserID);
		if($bankAcc == null)	$bankAcc = array("BankName"=>"","BankCode" => "");
		
		//用户余额
		$balance = Account::apiGetUserBalance($CurrentUserID);
		
		//银行列表
		$bank = Common::getBankList();
		
		$acc = array(
			"BankName"	=>$bankAcc["BankName"],
			"BankCode"	=>$bankAcc["BankCode"],
			"CardType"	=>$bankAcc["CardType"],
			"Balance"	=>$balance
		);
		
		$this->getView()->assign("item",json_encode($item));
		$this->getView()->assign("acc",json_encode($acc));
		$this->getView()->assign("bank",json_encode($bank));
		$this->getView()->assign("user",json_encode($CurrentUser));
		
		$this->getView()->Display("payInquiry.htm");
	}
	
	//=============================== 货源相关 ====================================
	
	//货源列表
	public function getSupplyListAction()
	{	global $CurrentUserID;
		require_once(MODELS_PATH . "/api2.0/Supply.php");
	
		$act = "OLD";
		$id = -1;
		$post = $this->Post;
		
		if(IsSet($post["ACT"]))
		{	$act = $post["ACT"];
			UnSet($post["ACT"]);
		}
		if(IsSet($post["ID"]) && Is_Numeric($post["ID"]))
		{	$id = $post["ID"];
			UnSet($post["ID"]);
		}
		$post["UserID"] = $CurrentUserID;

		//货源列表
		$ret = Supply::apiGetList($act,$id,ROWS_PER_RETRIEVE,$post);
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
	}
	
	//获取货源
	public function getSupplyByIDAction()
	{
		if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
		{	echo (new ResponseResult(false,"参数错误",0,null))->AsJson();
			return;
		}
		
		$ret = Common::getSupplyByID($_GET["ID"]);
	    echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
	}
	
	//删除货源
	public function delSupplyAction()
	{	global $CurrentUserID;
	
		if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
		{	echo (new ResponseResult(false,"参数错误",0,null))->AsJson();
			return;
		}
		
		$ret = Common::delSupplyByID($_GET["ID"],$CurrentUserID);
	    echo (new ResponseResult($ret === true,"查无数据",0,null))->AsJson();
	}
	
	//保存货源
	public function setSupplyAction()
	{	require_once(MODELS_PATH . "/api2.0/Supply.php");
		
		$this->Post["UserID"] = $_SESSION["User"]["UserID"];
		$this->Post["UserName"] = $_SESSION["User"]["Name"];
		$ret = Supply::apiSave($this->Post);
		$result = new ResponseResult(Is_Numeric($ret),$ret,0,null);
		echo $result->AsJSon();
	}
	
	//=============================== 订单相关 ====================================
	public function getInquiryByIDAction()
	{	
		if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
		{	echo (new ResponseResult(false,"参数错误",0,null))->AsJson();
			return;
		}
		
		global $CurrentUserID;
		require_once(MODELS_PATH . "/api2.0/Inquiry.php");
		
		Inquiry::apiSetRead($_GET["ID"],$CurrentUserID);
		$ret = Inquiry::apiGetInquiryByID($_GET["ID"],$CurrentUserID);
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
	}
	
	//订单列表
	public function getInquiryListAction()
	{	global $CurrentUserID;
		require_once(MODELS_PATH . "/api2.0/Inquiry.php");
	
		$act = "OLD";
		$id = -1;
		$post = $this->Post;
		
		if(IsSet($post["ACT"]))
		{	$act = $post["ACT"];
			UnSet($post["ACT"]);
		}
		if(IsSet($post["ID"]) && Is_Numeric($post["ID"]))
		{	$id = $post["ID"];
			UnSet($post["ID"]);
		}
		$post["SUPSCHUserID"] = $CurrentUserID;
				
		//货源列表
		$ret = Inquiry::apiGetList($act,$id,ROWS_PER_RETRIEVE,$post);
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
	}
	
	//保存订单
	public function setInquiryAction()
	{	global $CurrentUser,$CurrentUserID;
		require_once(MODELS_PATH . "/api2.0/Inquiry.php");
		
		$parm = array(
			"UserID"		=> $CurrentUserID,
			"UserName"		=> $CurrentUser["Name"],
			"Qty"			=> $this->Post["Qty"],
			"QtyDeviation"	=> $this->Post["QtyDeviation"],
			"Price"			=> $this->Post["Price"],
			"TaxInclusive"	=> $this->Post["TaxInclusive"],
			"TotalSum"		=> $this->Post["TotalSum"],
			"Deposit"		=> $this->Post["Deposit"],
			"NeedOnlinePay"	=> $this->Post["NeedOnlinePay"],
			"ID"			=> $this->Post["ID"]
		);
		$ret = Inquiry::apiUpdate($parm);
		echo (new ResponseResult($ret === true,$ret,0,null))->AsJson();
	}
		
	//关闭订单
	public function closeInquiryAction()
	{	if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
		{	echo (new ResponseResult(false,"参数错误",0,null))->AsJson();
			return;
		}
		global $CurrentUser,$CurrentUserID;
		require_once(MODELS_PATH . "/api2.0/Inquiry.php");
		
		$ret = Inquiry::apiSetState($_GET["ID"],$CurrentUserID,INQ_STATE_INVALID);
	    echo (new ResponseResult($ret === true,"查无数据",0,null))->AsJson();
	}
	
	//接单
	public function orderInquiryAction()
	{	if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
		{	echo (new ResponseResult(false,"参数错误",0,null))->AsJson();
			return;
		}
		global $CurrentUser,$CurrentUserID;
		require_once(MODELS_PATH . "/api2.0/Inquiry.php");
		
		$ret = Inquiry::apiOrder($_GET["ID"],$CurrentUserID);
	    echo (new ResponseResult($ret === true,"查无数据",0,null))->AsJson();
	}
	
	//在线支付，付订金或尾款
	public function payInquiryActAction()
	{	global $CurrentUserID;
		$post = $this->Post;
		
		//参数检查
		if( !IsSet($post["ID"]) || !Is_Numeric($post["ID"]) || !IsSet($post["Money"]) || !Is_Numeric($post["Money"]) ||
			!IsSet($post["PayType"]) ||  !IsSet($post["Password"]) || ($post["PayType"] != "BALANCE" && $post["PayType"] != "BANK") ||
			!IsSet($post["CardType"]) || !IsSet($post["BankCode"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		
		//获取订单详情
		require_once(MODELS_PATH . "/api2.0/Inquiry.php");
		$inq = Inquiry::apiGetInquiryByID($post["ID"],$CurrentUserID);
		if($inq == null || $inq["NeedOnlinePay"] == 0 || ($inq["State"] != INQ_STATE_ORDER && $inq["State"] != INQ_STATE_DEPOSIT))
		{	echo (new ResponseResult(false,"参数有误"))->AsJSon();
			return;
		}

		//支付订金
		if($inq["State"] == INQ_STATE_ORDER)			
		{	$ret = Inquiry::apiDeposit($post["ID"],$CurrentUserID,$post["Money"],$post["Password"],$post["PayType"],$post["CardType"],$post["BankCode"]);
		}
		//支付尾款
		else if($inq["State"] == INQ_STATE_DEPOSIT)
		{	$ret = Inquiry::apiDone($post["ID"],$CurrentUserID,$post["Password"],$post["PayType"],$post["CardType"],$post["BankCode"]);
		}
		
		//如果余额不足，将返回一个收银台网址，客户端打开网址付款
		$msg = ""; 	$retArr = "";
		if($ret["result"] == false)	$msg = IsSet($ret["error_message"]) ? $ret["error_message"] : $ret["error_code"];
		else							$retArr = array("url"=>base64_encode(IsSet($ret["url"]) ? $ret["url"] : ""));
		echo (new ResponseResult($ret["result"],$msg,0,$retArr))->AsJson();
	}
	
	//线下支付，完成订单
	public function doneInquiryAction()
	{
		if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]) || !IsSet($_GET["Pay"]) || !Is_Numeric($_GET["Pay"]))
		{	echo (new ResponseResult(false,"参数错误",0,null))->AsJson();
			return;
		}
		global $CurrentUser,$CurrentUserID;
		require_once(MODELS_PATH . "/api2.0/Inquiry.php");
		
		$ret = Inquiry::apiDoneWithoutPay($_GET["ID"],$CurrentUserID);
		echo (new ResponseResult($ret === true,"查无数据",0,null))->AsJson();
	}
	
	//申请退款
	public function AskRefundAction()
	{	
		//参数检查
		if( !IsSet($this->Post["ID"]) || !Is_Numeric($this->Post["ID"]) || !IsSet($this->Post["Reason"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		global $CurrentUser,$CurrentUserID;
		require_once(MODELS_PATH . "/api2.0/Inquiry.php");
		
		$ret = Inquiry::apiRequestRefund($this->Post["ID"],$CurrentUserID,$this->Post["Reason"]);
		$result = new ResponseResult($ret === true,$ret);
		echo $result->AsJson();
	}
	
	//撤消退款申请
	public function AbortRefundAction()
	{
		//参数检查
		if( !IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		global $CurrentUser,$CurrentUserID;
		require_once(MODELS_PATH . "/api2.0/Inquiry.php");
		$ret = Inquiry::apiSetState($_GET["ID"],$CurrentUserID,INQ_STATE_DEPOSIT);
		$result = new ResponseResult($ret === true,$ret);
		echo $result->AsJson();
	}	

	//==================================== 支付相关 =====================================
	public function getPublicKeyAction()
	{	require_once(MODELS_PATH . "/api2.0/Account.php");
		echo (new ResponseResult(true,"",0,RSA_PUBLIC_KEY))->AsJson();
	}
	
	//余额
	public function getBalanceAction()
	{
		global $CurrentUser,$CurrentUserID;
		
		require_once(MODELS_PATH . "/api2.0/Account.php");
		$ret = Account::apiGetUserBalance($CurrentUserID);
		echo (new ResponseResult(Is_Numeric($ret),$ret,0,$ret))->AsJson();
	}
	
	//我的流水清单
	public function getUserBillAction()
	{	global $CurrentUserID;
		$post = $this->Post;
		if(!IsSet($post["dateFrom"]) || !IsSet($post["dateTo"]) || !IsSet($post["ID"]) || !IsSet($post["ACT"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		
		//用户流水
		$ret = common::getUserBillList($post["ACT"],$post["ID"],$CurrentUserID,$post["dateFrom"],$post["dateTo"],ROWS_PER_RETRIEVE);
		
		echo (new ResponseResult($ret!==null,"查无数据",0,$ret))->AsJSon();
	}
	
	//获取银行列表
	public function getBankListAction()
	{	$ret = Common::getBankList();
		echo (new ResponseResult($ret!==null,"查无数据",0,$ret))->AsJSon();
	}
	
	//发验证码
	public function sendVCodeAction()
	{	global $CurrentUser;
		require_once(MODELS_PATH . "/api2.0/User.php");
		
		//生成验证码
		$vcode = MakeRand(SITE_VCODE_LENGTH,"NUMBER");
		$ret = User::apiSendVCode($CurrentUser["Account"],$vcode,VCODE_TYPE_FORGETPWD);
		echo (new ResponseResult($ret===true,"查无数据",0,$ret))->AsJSon();
	}
	
	//绑定银行卡号
	public function bindAccountAction()
	{	global $CurrentUserID;
		$post = $this->Post;
		if( !IsSet($post["BankCode"]) || !IsSet($post["BankName"]) || !IsSet($post["CardNo"]) || 
			!IsSet($post["CardName"]) || !IsSet($post["CardType"]) || !IsSet($post["PayPwd"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		
		require_once(MODELS_PATH . "/api2.0/Account.php");
		$ret = Account::apiSetBankAccount($CurrentUserID,$post["PayPwd"],$post["BankName"],$post["BankCode"],$post["CardNo"],$post["CardName"],$post["CardType"]);
		echo (new ResponseResult($ret===true,$ret,0,null))->AsJSon();
	}
	//解绑银行卡
	public function unbindAccountAction()
	{	if(!IsSet($this->Post["PayPwd"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		
		global $CurrentUserID;
		require_once(MODELS_PATH . "/api2.0/Account.php");
		
		$ret = Account::apiUnbindBankAccount($CurrentUserID,$this->Post["PayPwd"]);
		echo (new ResponseResult($ret===true,$ret,0,null))->AsJSon();
	}
	
	//重设支付密码
	public function setPayPasswordAction()
	{
		if(!IsSet($this->Post["PayPwd"]) || !IsSet($this->Post["VCode"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		
		global $CurrentUser,$CurrentUserID;
		require_once(MODELS_PATH . "/api2.0/account.php");
		require_once(MODELS_PATH . "/api2.0/User.php");
		
		//================== 验证码检查 ===================
		$ret = User::apiGetVCode($CurrentUser["Account"]);
		if($ret === null || $ret["Code"] != $this->Post["VCode"])// || $ret["Long"] > SITE_VCODE_EXPIRED)
		{	$result = new ResponseResult(false,ERR_VCODE_ERROR,ERR_VCODE_ERROR,null);
			echo $result->AsJSon();
			return;
		}
		
		$ret = Account::apiSetPayPassword($CurrentUserID,$this->Post["PayPwd"]);
		echo (new ResponseResult($ret===true,$ret,0,null))->AsJSon();
	}
	
	//充值
	public function rechargeAction()
	{
		if(!IsSet($_POST["Money"]) || !Is_Numeric($_POST["Money"]) || !IsSet($_POST["BankCode"]) || !IsSet($_POST["CardType"]))
		{	echo "<script>window.close();</script>";
			return;
		}
		global $CurrentUser,$CurrentUserID;
		require_once(MODELS_PATH . "/api2.0/Account.php");
		
		$ret = Account::apiPayToPlatform($CurrentUserID,"充值",$_POST["Money"],0,$_POST["CardType"],$_POST["BankCode"]);
		
		
		if($ret["result"] == false)
			echo "错误：" . (IsSet($ret["error_message"]) ? $ret["error_message"] : $ret["error_code"]);
		else
			echo $ret["url"];
	}
	
	//提现
	public function TakeCashAction()
	{
		if( !IsSet($this->Post["Money"]) || !IsSet($this->Post["PayPwd"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		
		global $CurrentUser,$CurrentUserID;
		require_once(MODELS_PATH . "/api2.0/Account.php");
		$ret = Account::apiTakeCash($CurrentUserID,$this->Post["Money"],$this->Post["PayPwd"]);
		$msg = "";
		if($ret["result"] == false)	$msg = IsSet($ret["error_message"]) ? $ret["error_message"] : $ret["error_code"];
		
		echo (new ResponseResult($ret["result"],$msg))->AsJson();
	}
}
?>