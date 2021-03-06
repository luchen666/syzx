<?php
include_once(APP_PATH . "/utils/ResponseResult.php");
include_once(MODELS_PATH . "/api2.0/Account.php");

class AccountController extends APIBaseController
{
	//银行列表
	public function GetBankListAction()
	{
		$ret = Account::GetAllList(Array("OrderKey"=>"ID,SortNo"),0,0,"base_Bank");
		$result = new ResponseResult($ret !== null,"查无数据",0,$ret);
		echo $result->AsJson();
	}
			
	//用户余额
	public function BalanceAction()
	{
		if(!IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
		
		$ret = Account::apiGetUserBalance($_GET["UserID"]);
		$result = new ResponseResult(Is_Numeric($ret),$ret,0,$ret);
		echo $result->AsJson();
	}
	
	//帐户明细
	public function BillListAction()
	{	if(!IsSet($this->Post["UserID"]) || !Is_Numeric($this->Post["UserID"]) ||
		   !IsSet($this->Post["LastID"]) || !Is_Numeric($this->Post["LastID"]) ||
		   !IsSet($this->Post["Rows"]) || !Is_Numeric($this->Post["Rows"]) ||
		   !IsSet($this->Post["Action"]) || !IsSet($this->Post["DateFrom"]) || !IsSet($this->Post["DateTo"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($this->Post["UserID"]);
		
		$ret = Account::apiUserBillList($this->Post["Action"],$this->Post["LastID"],$this->Post["UserID"],$this->Post["DateFrom"],$this->Post["DateTo"],$this->Post["Rows"]);
		
		$result = new ResponseResult($ret !== null,"查无数据",0,$ret);
		echo $result->AsJson();
	}
	
	//获取用户银行帐户信息
	public function GetBankAccountAction()
	{
		if(!IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
		
		$a  = null;
		$ret = Account::apiGetBankAccount($_GET["UserID"]);
		if($ret !== null)
		{
			$bankName = "";
			$cardNo = "";
			
			if($ret["CardNo"] != "")
			{	$cardNo = left($ret["CardNo"],4) . " **** **** " . right($ret["CardNo"],4);
				$bankName = $ret["BankName"] . ($ret["CardType"] == "C" ? "(对私)" : "(对公)");
			}
			
			$a = array("BankName"=>$bankName,"CardNo"=>$cardNo);
		}
		
		$result = new ResponseResult($ret !== null,"查无数据",0,$a);
		echo $result->AsJson();
	}
	
	//绑定用户银行卡
	public function SetBankAccountAction()
	{
		if(!IsSet($this->Post["UserID"]) || !Is_Numeric($this->Post["UserID"]) ||
			!IsSet($this->Post["PayPassword"]) || !IsSet($this->Post["BankName"]) ||
			!IsSet($this->Post["BankCode"]) || !IsSet($this->Post["CardNo"]) ||
			!IsSet($this->Post["CardName"]) || !IsSet($this->Post["CardType"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($this->Post["UserID"]);
		
		$ret = Account::apiSetBankAccount($this->Post["UserID"],$this->Post["PayPassword"],$this->Post["BankName"],$this->Post["BankCode"],$this->Post["CardNo"],$this->Post["CardName"],$this->Post["CardType"]);
		
		$result = new ResponseResult($ret === true,$ret);
		
		echo $result->AsJson();
	}
	
	//设置用户支付密码
	public function SetPayPasswordAction()
	{
		if(!IsSet($this->Post["UserID"]) || !Is_Numeric($this->Post["UserID"]) ||
			!IsSet($this->Post["Password"]) || !IsSet($this->Post["VCode"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($this->Post["UserID"]);
		
		//================== 验证码检查 ===================
		include_once(MODELS_PATH . "/api2.0/User.php");
		
		$ret = User::apiGetVCode($_SESSION["User"]["MobilePhone"]);
		
		if($ret === null || $ret["Code"] != $this->Post["VCode"] || $ret["Long"] > SITE_VCODE_EXPIRED)
		{	$result = new ResponseResult(false,"错误的验证码。");
			echo $result->AsJSon();
			return;
		}
		
		$ret = Account::apiSetPayPassword($this->Post["UserID"],$this->Post["Password"]);
		
		$result = new ResponseResult($ret === true,$ret);
		
		echo $result->AsJson();
	}
	
	//提现
	public function TakeCashAction()
	{
		if( !IsSet($this->Post["Amount"]) || !IsSet($this->Post["PayPassword"]) ||
			!IsSet($this->Post["UserID"]) || !Is_Numeric($this->Post["UserID"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($this->Post["UserID"]);
	
		$ret = Account::apiTakeCash($this->Post["UserID"],$this->Post["Amount"],$this->Post["PayPassword"]);
		
		$msg = "";
		$url = "";
		if($ret["result"] == false)	$msg = IsSet($ret["error_message"]) ? $ret["error_message"] : $ret["error_code"];
		//else							$url = IsSet($ret["url"]) ? $ret["url"] : "";
		
		$result = new ResponseResult($ret["result"],$msg,0,$url);
		
		echo $result->AsJson();
	}
	
	//充值
	public function RechargeAction()
	{
		if( !IsSet($_GET["Money"]) || !Is_Numeric($_GET["Money"]) ||
			!IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
	
		$ret = Account::apiPayToPlatform($_GET["UserID"],"充值",$_GET["Money"],0);
		
		$msg = "";
		$retArr = "";
		if($ret["result"] == false)
			$msg = IsSet($ret["error_message"]) ? $ret["error_message"] : $ret["error_code"];
		else
			$retArr = array("url"=>(IsSet($ret["url"]) ? $ret["url"] : ""));
		
		$result = new ResponseResult($ret["result"],$msg,0,$retArr);
		
		echo $result->AsJson();
	}
	
	//支付平台异步通知
	public function PaidAsyncNotifyAction()
	{	
		//============= 保存异步通知内容到文件 ==========
		$path = "d:/PaidAsyncNotify";
		if(!is_dir($path))	mkdir($path);
		
		$body = "";
		foreach($_POST as $key => $val) $body .= "$key=$val\r\n";
		$fname = sprintf("$path/%s.txt",date("ymdHis"));
		file_put_contents($fname,$body);
		//==============================================
		
		if(!IsSet($_POST["sign"]) && !IsSet($_POST["sign_type"]))	return;
		
		//来源验证
		$ipGateway	= Explode(",",PAY_GETWAY_IP);
		$ipFrom		= $_SERVER["REMOTE_ADDR"];
		$ipValid 	= false;
		
		foreach($ipGateway as $v) if($ipFrom == $v) $ipValid = true;
		if($ipValid == false)
		{	Account::apiErrorLogAdd("PaidAsyncNotify","IP来源不合法：$ipFrom");
			echo "IP来源不合法";
			return;
		}
		
		//签名验证
		$sign0 = $_POST["sign"];
		
		$parm = $_POST;
		unset($parm["sign"]);
		unset($parm["sign_type"]);
		ksort($parm);
		$str = "";
		foreach($parm as $key => $val)	$str .= "$key=$val&";
		$str = substr($str,0,strlen($str)-1);
		
	  	$sign1 = md5($str.PAY_PARTNER_KEY);
		if($sign1 != $sign0)
		{	Account::apiErrorLogAdd("PaidAsyncNotify","签名不附：$str");
			echo "签名不附";
			return;
		}
		
		$ret = "success";
		
		//添加日志
		$id = Account::apiResponseLogAdd($_POST);
		
		//交易成功，处理流水
		if( (IsSet($parm["trade_status"]) && $parm["trade_status"] == "TRADE_SUCCESS") ||
			(IsSet($parm["withdrawal_status"]) && ( $parm["withdrawal_status"] == "WITHDRAWAL_SUCCESS" || $parm["withdrawal_status"] == "WITHDRAWAL_FAIL"))
		  )
		{	$post = $_POST;
			if(IsSet($_GET["RequestNo"]))	$post["RequestNo"] = $_GET["RequestNo"];
			if(IsSet($_GET["InquiryID"]))	$post["InquiryID"] = $_GET["InquiryID"];
			$ret = Account::apiPaidAsyncNotify($post);
		}
		if($ret === true)	$ret = "success";
		
		echo $ret;
		
		Account::apiResponseLogSet($id,$ret);
	}
}
?>