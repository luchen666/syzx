<?php
include_once(MODELS_PATH . "/BaseModel.php");
include_once(APP_PATH . "/utils/Utils.php");

define("BUILD_FOR_RELASE",0);

//生产环境
if(BUILD_FOR_RELASE)
{	
	define("PAY_PARTNER_ACCOUNT","sk@syzx56.com");											//平台帐户
	define("PAY_PARTNER_ID","200002347546");												//签约商户号
	define("PAY_PARTNER_KEY","AD792402FF45D8A2057913C9BDCA26EE");							//商户KEY
	define("PAY_GATEWAY_URL","http://mag.kjtpay.com/mag/gateway/receiveOrder.do");			//支付网关
	define("PAY_GETWAY_IP","183.136.222.222,121.52.242.222");								//网关IP,用于异步通知来源验证
	define("PAY_NOTIFY_URL","http://www.syzx56.com/api2.0/account/PaidAsyncNotify");		//异步通知URL
	define("PAY_RETURN_URL","http://www.syzx56.com/api2.0/account/PaidSyncNotify");		//页面跳转同步返回页面路径，可空
	define("ENCRYPT_ACCOUNT_URL","http://www.syzx56.com:8998/encrypt/fetch");					//绑定银行卡加密网址
}

//准生产环境
else
{
	define("PAY_PARTNER_ACCOUNT","kjt2015070948@kjtpay.com.cn");							//平台帐户
	define("PAY_PARTNER_ID","200000056673");												//签约商户号
	define("PAY_PARTNER_KEY","7E07CBAF6E39375A40FCC7C15911B802");							//商户KEY
	define("PAY_GATEWAY_URL","https://zmag.kjtpay.com/mag/gateway/receiveOrder.do");		//支付网关
	define("PAY_GETWAY_IP","183.136.222.142,121.52.242.206");								//网关IP,用于异步通知来源验证
	define("PAY_NOTIFY_URL","http://122.225.197.102:8000/api2.0/account/PaidAsyncNotify");	//异步通知URL
	define("PAY_RETURN_URL","http://122.225.197.102:8000/api2.0/account/PaidSyncNotify");	//页面跳转同步返回页面路径，可空
	define("ENCRYPT_ACCOUNT_URL","http://www.syzx56.com:8998/encrypt/fetch");				//绑定银行卡加密网址
}

define("PAY_KEEP_INTERVAL",60);		//两次支付相隔间距，单位：秒，预防不同设备上同时支付

//支付方式
define("BILL_PAY_BALANCE",10);			//余额付
define("BILL_PAY_BANK",20);			//银行帐号付

//流水状态
define("BILL_STATE_NONE",0);			//未处理
define("BILL_STATE_VALID",1);			//有效
define("BILL_STATE_INVALID",-1);		//无效

//流水帐来源
define("BILL_SOURCE_PAY",10);			//支付
define("BILL_SOURCE_REFUND",20);		//退款
define("BILL_SOURCE_RECHARGE",30);		//充值
define("BILL_SOURCE_TAKECASH",40);		//提现
define("BILL_SOURCE_SETTLE",50);		//结算
define("BILL_SOURCE_ROLLBACK",60);		//回滚

//公钥
define("RSA_PUBLIC_KEY","MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDWL8akfHLEQajubFOcO7PX4cYxRetiANwNCQF5i+3K65LVp2xIlqETu+tbi+HM66O+zqbqCnIFemIhGBEuumybiIVFfHAdy5mN+FEzLUuyN+DE2NwTGS/SuQ7i9q1+bMO+3MusQHeSqIYsXy42P4PIxYTj3gmn1WJobCEHwItOFQIDAQAB");

//私钥
define("RSA_PRIVATE_KEY","-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQDWL8akfHLEQajubFOcO7PX4cYxRetiANwNCQF5i+3K65LVp2xI
lqETu+tbi+HM66O+zqbqCnIFemIhGBEuumybiIVFfHAdy5mN+FEzLUuyN+DE2NwT
GS/SuQ7i9q1+bMO+3MusQHeSqIYsXy42P4PIxYTj3gmn1WJobCEHwItOFQIDAQAB
AoGAFaX+jeGGOA/q4y5PxRrDsq4/Ofu3LtCnbSnb3E0oW7ozvSSO7UdMQJJd14Lr
76phgoegl0c2/xa/lFi6Y96z21U2IXf6PC2JJum+RPKfRAp0e5elvMXXjU4Vc1Kv
kc/Dkhb6EKSA4D7p+EwgSgJLe4vlO69FRrKYQq3sE16YhoECQQDxE1+H0eQHQwSm
5oV84PW5H1yTwk3XwCVCI3eepGTsoGQpzjWKirLSxenhb/FGCrlRDt/0AAYlswfT
k3GZAdpnAkEA43JCKfZ9fGcPY8RkEfRfXacz2+2yQeMwVqiVE+eI1KUjQfqyINeq
qhI4+vAeiVaEChLI/wZPI/n+qA9kRf6+IwJBAOkH5xItC5URbU/ACUz7T8uQyzZx
XjmtnNZf3mNVWdF6ARiAIjU3eUBQ25F1We5ws03taQI9e7O5aVlVbzuOFQUCQEGH
r+xrKihngRWbhcFuiPNwsw5QY6V8EXdGRv1fHhs2IPxngF7aBD/P1D7oY8Pp+0zz
sJlZdf/FjUtac+d+x8UCQQDN2K1T3Eoc6C5OGh3OVyZJX30nxPHEIcn0OTHfW2Bq
RJDiMUETxgvnz83xYXUID4h8XPZVKtL3QXfD7nAiAl2i
-----END RSA PRIVATE KEY-----");

class Account Extends BaseModel
{
	//=================================== 日志操作 ===========================================
	//添加错误日志
	public static function apiErrorLogAdd($type,$memo,$rs = null)
	{
		if($rs == null)	$rs = new RecordSets();
		
		$sql = "INSERT INTO sys_ErrorLog (Type,Memo,CreateDate) VALUES (:Type,:Memo,Now())";
		$rs->ExecSQL($sql,array("Type"=>"PAY_RESPONSE","Memo"=>$memo));
	}
	
	//添加网关异步通知日志
	public static function apiResponseLogAdd($parm,$rs = null)
	{
		if(IsSet($parm["_input_charset"]))	UnSet($parm["_input_charset"]);
		//先添加到日志
		$field = "CreateDate";
		$value = "Now()";
		
		foreach($parm as $key => $val)
		{
			$field .= ",$key";
			$value .= ",:$key";
		}
		$sql = "INSERT INTO pay_ResponseLog ($field) VALUES ($value)";
		
		if($rs == null)	$rs = new RecordSets();
		
		$rs->beginTrans();
		$rs->ExecSQL($sql,$parm);
		$rs->ExecSQL("SELECT Max(ID) AS ID FROM pay_ResponseLog");
		$id = $rs->ID;
		$rs->commit();
		
		return $id;
	}
	
	//设置网关处理结果
	public static function apiResponseLogSet($id,$ret,$rs = null)
	{
		if($rs == null)	$rs = new RecordSets();
		$rs->ExecSQL("UPDATE pay_ResponseLog SET result = :ret WHERE ID = $id",array("ret"=>$ret));
	}
	
	//添加网关请求日志
	public static function apiRequestLogAdd($parm,$rs = null)
	{
		if($rs == null)	$rs = new RecordSets();
		
		if(IsSet($parm["partner_id"]))		unset($parm["partner_id"]);
		if(IsSet($parm["_input_charset"]))	unset($parm["_input_charset"]);
		if(IsSet($parm["identity_type"]))	unset($parm["identity_type"]);
		if(IsSet($parm["return_url"]))		unset($parm["return_url"]);
		if(IsSet($parm["identity_no"]))	unset($parm["identity_no"]);
		if(IsSet($parm["branch_name"]))	unset($parm["branch_name"]);
		if(IsSet($parm["bank_line_no"]))	unset($parm["bank_line_no"]);
		if(IsSet($parm["bank_prov"]))		unset($parm["bank_prov"]);
		if(IsSet($parm["bank_city"]))		unset($parm["bank_city"]);
		
		if(!IsSet($parm["CreateDate"]))	$parm["CreateDate"] = Date("Y-m-d H:i:s");
		
		self::Save($parm,"pay_RequestLog",$rs);
	}
	
	//设置网关日志
	public static function apiRequestLogSet($requestNo,$parm,$rs = null)
	{
		if($rs == null)	$rs = new RecordSets();
		
		$sql =  "UPDATE pay_RequestLog
					SET	RET_Is_success		= :RET_Is_success,
						RET_Error_code		= :RET_Error_code,
						RET_Error_message	= :RET_Error_message,
						RET_Memo			= :RET_Memo,
						RET_Result			= :RET_Result,
						RET_IsForwardCashier= :RET_IsForwardCashier,
						RET_CashierURL		= :RET_CashierURL
				  WHERE request_no = :RequestNo OR 
						outer_trade_no = :RequestNo";
				  
		$parm["RequestNo"] = $requestNo;
		$rs->ExecSQL($sql,$parm);
	}
	
	//=================================== 支付基础数据 ===========================================
	//取所有用户余额
	public static function apiAllUserBalance()
	{
		$sql = "SELECT UserID,Balance FROM (SELECT UserID,Balance FROM pay_UserBill WHERE State = :Valid ORDER BY UserID ASC,UpdateSN DESC) AS T1 GROUP BY UserID";
	}

	//获取取用户的银行帐户信息
	public static function apiGetBankAccount($userID,$rs = null)
	{
		if($rs == null)	$rs = new RecordSets();
		
		$sql = "SELECT BankCode,BankName,CardNo,CardName,CardType,EncodeCardNo,EncodeCardName FROM sys_Users WHERE UserID = $userID";
		
		$rs->ExecSQL($sql);
		
		return ($rs->RecordCount == 0) ? null : $rs->AsArray()[0];
	}
	
	//清除用户绑定的银行帐户信息
	public static function apiUnbindBankAccount($userID,$encodePayPwd,$rs = null)
	{	if($rs == null)	$rs = new RecordSets();
		
		//先验证支付密码
		if(!self::apiVerifyPayPassword($userID,base64_decode($encodePayPwd),$rs))	return ERR_PAY_PASSWORD_ERROR;
		
		$sql = "UPDATE sys_Users SET BankCode = '',BankName = '',CardNo = '',CardName = '',CardType = '',EncodeCardNo = '',EncodeCardName = '' WHERE UserID = $userID";
		$rs->ExecSQL($sql);
		return true;
	}
	//设置用户的银行帐户信息
	public static function apiSetBankAccount($userID,$encodePayPwd,$bankName,$bankCode,$encodeCardNo,$encodeCardName,$cardType,$rs = null)
	{
		if($rs == null)	$rs = new RecordSets();
		
		//先验证支付密码
		if(!self::apiVerifyPayPassword($userID,base64_decode($encodePayPwd),$rs))	return ERR_PAY_PASSWORD_ERROR;
		
		//解密帐号与户名
		$cardNo = RSADecrypt(base64_decode($encodeCardNo),RSA_PRIVATE_KEY);
		$cardName = RSADecrypt(base64_decode($encodeCardName),RSA_PRIVATE_KEY);
		$content = postURL(ENCRYPT_ACCOUNT_URL,"accountName=$cardName&cardNo=$cardNo");

		$a = json_decode($content,TRUE);
		if(!IsSet($a["cardNo"]) || !IsSet($a["accountName"]) || $a["cardNo"] == null || $a["accountName"] == null)
		{	return ERR_PAY_BANKACCOUNT_ERROR;
		}
		$encodeCardNo = str_replace("\n","",$a["cardNo"]);
		$encodeCardName = str_replace("\n","",$a["accountName"]);
		
		//密文存储
		$sql = "UPDATE sys_Users SET
					BankCode	= :BankCode,
					BankName	= :BankName,
					CardNo		= :CardNo,
					CardName	= :CardName,
					CardType		= :CardType,
					EncodeCardNo	= :EncodeCardNo,
					EncodeCardName	= :EncodeCardName
			  WHERE UserID = :UserID";
		$parm = array(
			"BankCode"		=> $bankCode,
			"BankName"		=> $bankName,
			"CardNo"		=> $cardNo,
			"CardName"		=> $cardName,
			"CardType"		=> $cardType,
			"EncodeCardNo"	=> $encodeCardNo,
			"EncodeCardName"=> $encodeCardName,
			"UserID"		=> $userID
		);
		$rs->ExecSQL($sql,$parm);
		
		return true;
	}
	
	public static function apiSetPayPassword($userID,$encodePwd)
	{
		$newPwd = RSADecrypt(base64_decode($encodePwd),RSA_PRIVATE_KEY);
		
		$salt = MakeRand(6);
		$pwd = md5($newPwd . $salt);
		
		$sql = "UPDATE sys_Users SET PayPassword = :Pwd,PaySalt = :Salt WHERE UserID = :UserID";
		ExecSQL($sql,array("Pwd"=>$pwd,"Salt"=>$salt,"UserID"=>$userID));
		
		return true;
	}
	
	//=================================== 用户相关 ===========================================
	//验证支付密码解密
	public static function apiVerifyPayPassword($userID,$encodePwd,$rs = null)
	{
		$pwd = RSADecrypt($encodePwd,RSA_PRIVATE_KEY);
		
		//验证支付密码
		if($rs == null)	$rs = new RecordSets();
		$rs->ExecSQL("SELECT PayPassword,PaySalt FROM sys_Users WHERE UserID = $userID");
		$s1 = md5($pwd . $rs->PaySalt);
		
		return $rs->PayPassword == $s1;
	}
			
	//从资金池流水中取用户最后的付款token，使得付款时不需要再次输入银行卡号
	public static function apiGetUserToken($userID,$rs = null)
	{
		if($rs == null)	$rs = new RecordSets();
		$sql = "SELECT IfNull(Token,'') AS Token FROM pay_PoolBill WHERE State = :Valid AND UserID = :UserID ORDER BY UpdateSN DESC LIMIT 1";
		$rs->ExecSQL($sql,array("Valid"=>BILL_STATE_VALID,"UserID"=>$userID));
		
		return ($rs->RecordCount > 0) ? $rs->Token : "";
	}
	
	//取用户余额
	public static function apiGetUserBalance($UserID,$rs = null)
	{
		if($rs == null)	$rs = new RecordSets();
		
		$sql =  "SELECT Balance FROM pay_UserBill WHERE UserID = :UserID AND State = :Valid ORDER BY UpdateSN DESC LIMIT 1";
		
		try
		{
			$rs->ExecSQL($sql,array("Valid"=>BILL_STATE_VALID,"UserID"=>$UserID));
			if($rs->RecordCount == 0)	return 0;
			else						return $rs->Balance;
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
	
	//取用户明细
	public static function apiUserBillList($act,$id,$userID,$dateFrom,$dateTo,$rows)
	{
		if($act == "GT" || $act == "NEW")	$act = ">";
		if($act == "LT" || $act == "OLD")	$act = "<";
		
		$sql =  "SELECT * FROM pay_UserBill WHERE ID $act $id AND UserID = $userID AND State = :Valid AND " .
				"TO_DAYS(CreateDate) BETWEEN TO_DAYS(:DateFrom) AND TO_DAYS(:DateTo) ORDER BY UpdateSN DESC,ID DESC LIMIT $rows";
		$rs = ExecSQL($sql,array("Valid"=>BILL_STATE_VALID,"DateFrom"=>$dateFrom,"DateTo"=>$dateTo));
		return ($rs->RecordCount > 0) ? $rs->AsArray() : null;
	}
	
	//================================ 用户流水与平台流水 =====================================
	//添加客户流水
	public static function apiUserBillAdd($parm,$rs = null)
	{
		if($rs == null)	$rs = new RecordSets();
		
		$sql = "INSERT INTO pay_UserBill
				(	UserID,
					UserName,
					InquiryID,
					Subject,
					Amount,
					Source,
					IP,
					RequestNo,
					State,
					CreateDate
				)	VALUES	(	
					:UserID,
					:UserName,
					:InquiryID,
					:Subject,
					:Amount,
					:Source,
					:IP,
					:RequestNo,
					:StateNone,
					Now()
				)";
		
		$parm["StateNone"] = BILL_STATE_NONE;
		
		$beginTran = false;
		if(!$rs->InTrans)
		{	$beginTran = true;
			$rs->beginTrans();
		}
		try
		{	$rs->ExecSQL($sql,$parm);
			$rs->ExecSQL("SELECT Max(ID) AS ID FROM pay_UserBill");
			$id = $rs->ID;
			if($beginTran)	$rs->commit();
			
			return $id;
		}
		catch(exception $e)
		{
			return $e->getMessage();
		}
	}
	
	/*
	 * 设置客户流水状态
	 * $id 与 $requestNo 代表某条流水记录，只取其一
	 * 根据$state参数，如果是设置成有效，需要计算余额
	 */
	public static function apiUserBillSet($id,$requestNo,$state,$userID = null,$rs = null)
	{
		if($rs == null)	$rs = new RecordSets();
		
		//不需要计算余额
		if($state != BILL_STATE_VALID)
		{
			if($id != null)	$rs->ExecSQL("UPDATE pay_UserBill SET State = :State WHERE ID = :ID AND State <> :Valid",array("State"=>$state,"ID"=>$id,"Valid"=>BILL_STATE_VALID));
			else				$rs->ExecSQL("UPDATE pay_UserBill SET State = :State WHERE RequestNo = :RequestNo AND State <> :Valid",array("State"=>$state,"RequestNo"=>$requestNo,"Valid"=>BILL_STATE_VALID));
			
			return true;
		}
		
		//需要计算余额
		else if($state == BILL_STATE_VALID && $userID != null)
		{	
			$beginTran = false;
			if(!$rs->InTrans)
			{	$beginTran = true;
				$rs->beginTrans();
			}
			
			//取余额
			$balance = self::apiGetUserBalance($userID,$rs);
			if(!Is_Numeric($balance))
			{	if($beginTran)	$rs->rollback();
				return $balance;
			}
			
			//修改余额，只修改待处理的流水
			$sql = "UPDATE pay_UserBill SET Balance = :Balance + Amount,BeforeBalance = :Balance,State = :State,UpdateDate = Now(),UpdateSN = :SN WHERE State = :None AND ";
			$parm = array("SN"=>microtime(true),"Balance"=>$balance,"State"=>$state,"None"=>BILL_STATE_NONE);
			
			if($id != null)
			{	$sql .= " ID = :ID";
				$parm["ID"] = $id;
			}
			else
			{	$sql .= " RequestNo = :RequestNo";
				$parm["RequestNo"] = $requestNo;
			}
			
			$rs->ExecSQL($sql,$parm);

			if($beginTran)	$rs->commit();
			
			return true;
		}
		else
		{
			return ERR_PAY_PARAM_INVALID;
		}
	}
	
	//取资金池余额
	public static function apiGetPoolBalance($rs = null)
	{
		if($rs == null)	$rs = new RecordSets();
		
		$rs->ExecSQL("SELECT Balance FROM pay_PoolBill WHERE State = :Valid ORDER BY UpdateSN DESC LIMIT 1",array("Valid"=>BILL_STATE_VALID));
		
		if($rs->RecordCount == 0)	return 0;
		else						return $rs->Balance;
	}
	
	//添加资金池流水
	public static function apiPoolBillAdd($parm,$rs = null)
	{
		if($rs == null)	$rs = new RecordSets();
		
		$sql = "INSERT INTO pay_PoolBill
				(	UserID,
					UserName,
					Amount,
					RequestNo,
					State,
					CreateDate
				) VALUES (
					:UserID,
					:UserName,
					:Amount,
					:RequestNo,
					:StateNone,
					Now()
				)";
		$parm["StateNone"] = BILL_STATE_NONE;
		
		
		$beginTran = false;
		if(!$rs->InTrans)
		{	$beginTran = true;
			$rs->beginTrans();
		}
		
		try
		{	$rs->ExecSQL($sql,$parm);
			$rs->ExecSQL("SELECT Max(ID) AS ID FROM pay_PoolBill");
			$id = $rs->ID;
			if($beginTran)	$rs->commit();
			
			return $id;
		}
		catch(exception $e)
		{
			return $e->getMessage();
		}
	}
	
	//设置资金池流水
	public static function apiPoolBillSet($id,$requestNo,$state,$rs = null,$token = "")
	{
		if($rs == null)	$rs = new RecordSets();
		
		if($state == BILL_STATE_INVALID)
		{
			if($id != null)	$rs->ExecSQL("UPDATE pay_PoolBill SET State = :State WHERE ID = :ID",array("ID"=>$id,"State"=>$state));
			else				$rs->ExecSQL("UPDATE pay_PoolBill SET State = :State WHERE RequestNo = :RequestNo",array("RequestNo"=>$requestNo,"State"=>$state));
			
			return true;
		}
		else if($state == BILL_STATE_VALID)
		{
			$beginTran = false;
			if(!$rs->InTrans)
			{	$beginTran = true;
				$rs->beginTrans();
			}
			
			//取余额
			$balance = self::apiGetPoolBalance($rs);
			if(!Is_Numeric($balance))
			{	if($beginTran)	$rs->rollback();
				return $balance;
			}
			
			$parm = array(
				"Valid"		=> BILL_STATE_VALID,
				"None"		=> BILL_STATE_NONE,
				"SN"		=> microtime(true),
				"Balance"	=> $balance
			);
			
			$tokenText = "";
			if($token != "") 
			{	$tokenText = "Token	= :Token,";
				$parm["Token"] = $token;
			}
			
			$sql="UPDATE pay_PoolBill
					SET $tokenText
						BeforeBalance = :Balance,
						Balance 	= :Balance + Amount,
						State		= :Valid,
						UpdateSN	= :SN,
						UpdateDate	= Now()
				  WHERE State = :None AND ";
			
			if($id != null)
			{	$sql .= "ID = :ID";
				$parm["ID"] = $id;
			}
			else
			{	$sql .= "RequestNo = :RequestNo";
				$parm["RequestNo"] = $requestNo;
			}
			
			$rs->ExecSQL($sql,$parm);
			
			if($beginTran)	$rs->commit();
			
			return true;
		}
		else
		{
			return ERR_PAY_PARAM_INVALID;
		}
	}
	
	//========================================== 实际支付行为 ==========================================
	
	//用户提现
	public static function apiTakeCash($userID,$amount,$encodePayPwd)
	{
		//提现金额对不对
		$amount = RSADecrypt(base64_decode($amount),RSA_PRIVATE_KEY);
		if($amount == "" || $amount == 0 || !Is_Numeric($amount))		return array("result"=>false,"error_code"=>ERR_PAY_AMOUNT_ERROR);
		
		//验证用户
		$rs = new RecordSets();
		$rs->ExecSQL("SELECT Name,EncodeCardNo,EncodeCardName,CardType,BankCode,BankName,PayPassword,PaySalt FROM sys_Users WHERE UserID = $userID");
		if($rs->RecordCount == 0)			return array("result"=>false,"error_code"=>ERR_ACCOUNT_NOT_EXISTS);
		
		//验证支付密码
		$encodePwd = base64_decode($encodePayPwd);
		$pwd = RSADecrypt($encodePwd,RSA_PRIVATE_KEY);
		$s1 = md5($pwd . $rs->PaySalt);
		if($rs->PayPassword != $s1)			return array("result"=>false,"error_code"=>ERR_PAY_PASSWORD_ERROR);
		
		$userName = $rs->Name;
		$cardNo   = $rs->EncodeCardNo;
		$cardName = $rs->EncodeCardName;
		$cardType = $rs->CardType;
		$bankCode = $rs->BankCode;
		$bankName = $rs->BankName;
		
		//验证余额
		$balance = self::apiGetUserBalance($userID,$rs);
		if(!Is_Numeric($balance))			return array("result"=>false,"error_message"=>$balance);
		if($amount > $balance)				return array("result"=>false,"error_code"=>ERR_PAY_BALANCE_INSUFFICIENT);
		
		$requestNo = date("ymdHis").substr(microtime(),2,4);		//快捷通合作商户请求号（确保是唯一，建议用时间戳）
		
		$parm= array();
		$parm["service"]		= "create_payment_to_card";
		$parm["version"]		= "1.0";
		$parm["partner_id"]		= PAY_PARTNER_ID;
		$parm["_input_charset"] = "utf-8";
		$parm["return_url"]		= PAY_RETURN_URL;
		$parm["memo"]			= "";
		$parm["outer_trade_no"]	= $requestNo;
		$parm["identity_no"]	= PAY_PARTNER_ACCOUNT;
		$parm["identity_type"]	= 1;
		$parm["payable_amount"]	= $amount;
		$parm["account_type"]	= "";
		$parm["card_no"]		= $cardNo;
		$parm["account_name"]	= $cardName;
		$parm["bank_code"]		= $bankCode;
		$parm["bank_name"]		= $bankName;
		$parm["company_or_personal"]	= $cardType;
		$parm["branch_name"]	= "";
		$parm["bank_line_no"]	= "";
		$parm["bank_prov"]		= "";
		$parm["bank_city"]		= "";
		$parm["fundout_grade"]	= "1";
		$parm["purpose"]		= "提现";
		$parm["submit_time"]	= Date("YmdHis");
		$parm["notify_url"]		= PAY_NOTIFY_URL;
		
		//组装参数
		ksort($parm);	$str = "";	$data = "";
		foreach($parm as $key => $val)
		{	$data .= "$key=" . rawurlencode(str_replace("+","%2B",$val)) . "&";
			if($val != "")	$str .= "$key=$val&";
		}
		$str = substr($str,0,strlen($str)-1);
		$data = substr($data,0,strlen($data)-1);
		
		//签名
		$sign = md5($str.PAY_PARTNER_KEY);
		$data .= "&sign=$sign&sign_type=MD5";
		
		$parm["PostData"]	= $data;
		$parm["UserID"]		= $userID;
		$parm["UserName"]	= $userName;
		
		//资金池流水参数
		$parm2 = array(
			"UserID"	=> $userID,
			"UserName"	=> $userName,
			"Amount"	=> -$amount,
			"RequestNo"	=> $requestNo,
		);
		
		//用户流水参数
		$parm3 = array(
			"UserID"	=> $userID,
			"UserName"	=> $userName,
			"InquiryID"	=> 0,
			"Subject"	=> "提现",
			"Amount"	=> -$amount,
			"Source"	=> BILL_SOURCE_TAKECASH,
			"IP"		=> getClientIP(),
			"RequestNo"	=> $requestNo
		);

		try
		{
			$rs->beginTrans();
			
			//添加网关日志
			self::apiRequestLogAdd($parm,$rs);
			
			//记录到资金池流水，并生效，在网关异步通知中如果转帐失败，再回滚
			self::apiPoolBillAdd($parm2,$rs);
			
			//记录到用户流水，并生效，在网关异步通知中如果转帐失败，再回滚。
			self::apiUserBillAdd($parm3,$rs);
			
			$rs->commit();
		}
		catch(exception $e)
		{
			return array("result"=>false,"error_message"=>$e->getMessage());
		}
		
		//提交到网关
		try
		{	$content = postURL(PAY_GATEWAY_URL,$data,10);
			parse_str($content,$ret);
		}
		catch(exception $e)
		{
			$ret = array(
				"is_success" => "F",
				"error_code" => "CATCH_EXCEPTION",
				"error_message" => $e->getMessage,
				"memo" => $e->getMessage
			);
		}
		
		//获取返回值，记录到日志
		$parm = array(
			"RET_Is_success"	=> IsSet($ret["is_success"]) ? $ret["is_success"] : "F",
			"RET_Error_code"	=> IsSet($ret["error_code"]) ? $ret["error_code"] : "",
			"RET_Error_message"	=> IsSet($ret["error_message"]) ? $ret["error_message"] : "",
			"RET_Memo"			=> IsSet($ret["memo"]) ? $ret["memo"] : "",
			"RET_Result"		=> IsSet($ret["result"]) ? $ret["result"] : "",
			"RET_CashierURL"	=> IsSet($ret["cashierUrl"]) ? $ret["cashierUrl"] : "",
			"RET_IsForwardCashier"	=> IsSet($ret["isForwardCashier"]) ? $ret["isForwardCashier"] : ""
		);	
		self::apiRequestLogSet($requestNo,$parm,$rs);
		
		//提现请求未受理，需要把流水设为无效
		if(!IsSet($ret["is_success"]) || $ret["is_success"] == "F" || (IsSet($ret["result"]) && $ret["result"] == "false"))
		{
			self::apiPoolBillSet(null,$requestNo,BILL_STATE_INVALID,$rs);
			self::apiUserBillSet(null,$requestNo,BILL_STATE_INVALID,$userID,$rs);
			
			if(IsSet($ret["error_message"]))		return array("result"=>false,"error_message"=>$ret["error_message"]);
			else									return array("result"=>false,"error_code"=>ERR_PAY_GETWAY_ERROR);
		}
		
		//提现请求已受理，先生效流水，避免多次提现资金安全。最后结果需要在异步通知中进行确认或回滚。
		if(IsSet($ret["result"]) && $ret["result"] == "true")
		{	
			self::apiPoolBillSet(null,$requestNo,BILL_STATE_VALID,$rs);
			self::apiUserBillSet(null,$requestNo,BILL_STATE_VALID,$userID,$rs);

			return array("result"=>true);
		}
		else if(IsSet($ret["error_message"]))					return array("result"=>false,"error_message"=>$ret["error_message"]);
		else													return array("result"=>false,"error_code"=>ERR_PAY_GETWAY_ERROR);
	}
	
	/*
	 *	个人银行卡支付到平台
	 *	参数：	$userID		用户编号
	 *			$subject	描述
	 *			$amount		金额
	 *			$inqueryID	运单编号
	 *			$BC			类型 B：企业帐号  C：私人帐号  H：互金接口
	 *			$bankCode	银行代码
	 */
	public static function apiPayToPlatform($userID,$subject,$amount,$inquiryID=0,$BC="H",$bankCode="")
	{
		if($amount <= 0)	return array("result"=>false,"error_code"=>ERR_PAY_MONEY_ILLEGAL);;
		
		$requestNo 		= date("ymdHis").substr(microtime(),2,4);		//快捷通合作商户请求号（确保是唯一，建议用时间戳）
		$platform		= PAY_PARTNER_ACCOUNT;	//平台帐号
		$royalty		= "";						//分润集
		$goodsDesc		= "";						//商品描述
		$goodsURL		= "";						//商品描述URL
		$submitTime		= date("YmdHis");			//提交时间
		$asyncNotifyURL	= PAY_NOTIFY_URL;			// . "?RequestNo=$requestNo&InquiryID=$inquiryID";		//异步通知URL
		$storeName		= "";						//店铺名称
		
		//异步通知只能返回outer_trade_no，无法对应到request_no，在这里需要把request_no也加进去，以便在异步通知中获取这个request_no
		$orderNo = $inquiryID . "_" . $requestNo;
		
		//参数按表中的顺序，商品参数间用”~”间隔，每批商品用”$”间隔。如果有中间的参数为空，需要用”~”占位。建议商品种类不超过5个。
		$tradeList = "$orderNo~$subject~$amount~1~$amount~$royalty~$platform~1~$orderNo~$goodsDesc~$goodsURL~0~0~~$submitTime~$asyncNotifyURL~$storeName";
		
		$parm = array();
		$parm["service"]		= $BC=="H"?"p2p_investment":"create_instant_trade";		//接口名称
		$parm["version"]		= "1.0";												//版本号：固定
		$parm["partner_id"]		= PAY_PARTNER_ID;										//商户号，签约后获取
		$parm["_input_charset"]	= "utf-8";												//参数编码字符集
		$parm["return_url"]		= PAY_RETURN_URL;										//页面跳转同步返回页面路径，可空
		$parm["memo"]			= "";													//说明信息，可空
		
		$payMethod = ($BC == "H" ? "" : ("online_bank^$amount^$bankCode,$BC,".($BC=="C"?"GC":"DC")));
		$parm["request_no"]		= $requestNo;											//唯一订单号
		$parm["trade_list"]		= $tradeList;											//商品列表
		$parm["operator_id"]	= "";													//快捷通平台商户的操作者登录Id，可空
		$parm["buyer_id"]		= "anonymous";											//网银支付固定值：anonymous
		$parm["buyer_id_type"]	= "1";													//平台ID，固定值“1”
		$parm["buyer_ip"]		= "122.225.197.102";									//debug：getClientIP();			//用户在商户平台下单的时候的ip地址，非商户服务器的ip地址，公网IP，用于风控校验
		$parm["access_channel"]	= $BC=="H"?"HJSDK":"WEB";								//支付终端类型 HJSDK（互金SDK收银台）、WEB（WEB收银台）、H5（H5收银台）
		$parm["pay_method"]		= $payMethod;											//支付方式
		$parm["go_cashier"]		= "Y";													//是否转收银台标识，固定值：Y

		//组装参数
		ksort($parm);	$str = "";	$data = "";
		foreach($parm as $key => $val)
		{	$data .= "$key=$val&";
			if($val != "")	$str .= "$key=$val&";
		}
		$str = substr($str,0,strlen($str)-1);
		$data = substr($data,0,strlen($data)-1);
		
		//签名
		$sign = md5($str.PAY_PARTNER_KEY);
		$data .= "&sign=$sign&sign_type=MD5";
		
		//用户名
		$userName = "";
		
		$parm["PostData"]	= $data;
		$parm["UserID"]		= $userID;
		$parm["UserName"]	= $userName;
		
		//资金池流水参数
		$parm2 = array(
			"UserID"	=> $userID,
			"UserName"	=> $userName,
			"Amount"	=> $amount,
			"RequestNo"	=> $requestNo,
		);
		
		//用户流水参数
		$parm3 = array(
			"UserID"	=> $userID,
			"UserName"	=> $userName,
			"InquiryID"	=> $inquiryID,
			"Subject"	=> $subject,
			"Amount"	=> $amount,
			"Source"	=> BILL_SOURCE_RECHARGE,
			"IP"		=> getClientIP(),
			"RequestNo"	=> $requestNo
		);
		
		$rs = new RecordSets();
		$rs->beginTrans();
		
		try
		{	//添加网关日志
			self::apiRequestLogAdd($parm,$rs);
			
			//记录到资金池流水，在网关异步通知中，再根据request_no设置流水状态
			self::apiPoolBillAdd($parm2,$rs);
			
			//记录到用户流水，在网关异步通知中，再根据request_no设置流水状态
			self::apiUserBillAdd($parm3,$rs);
			
			//获取上次支付的token
			$lastToken = self::apiGetUserToken($userID,$rs);
			
			$rs->commit();
		}
		catch(exception $e)
		{
			return array("result"=>false,"error_message"=>$e->getMessage());
		}
		
		//提交到网关
		try
		{	$content = postURL(PAY_GATEWAY_URL,$data,10);	file_put_contents(sprintf("d:\\%s.htm",Date("YmdHis")),$content);
			
			//parse_str($content,$ret);
			
			//根据返回内容填充数组
			if(strpos($content,"<form id='frmBankID'") === false)
				parse_str($content,$ret);
			else
				$ret = array("is_success" => "T","cashierUrl" => $content);
		}
		catch(exception $e)
		{
			$ret = array(
				"is_success" => "F",
				"error_code" => "CATCH_EXCEPTION",
				"error_message" => $e->getMessage,
				"memo" => $e->getMessage
			);
		}
		
		//获取返回值，记录到日志
		$parm = array(
			"RET_Is_success"	=> IsSet($ret["is_success"]) ? $ret["is_success"] : "F",
			"RET_Error_code"	=> IsSet($ret["error_code"]) ? $ret["error_code"] : "",
			"RET_Error_message"	=> IsSet($ret["error_message"]) ? $ret["error_message"] : "",
			"RET_Memo"			=> IsSet($ret["memo"]) ? $ret["memo"] : "",
			"RET_Result"		=> IsSet($ret["result"]) ? $ret["result"] : "",
			"RET_CashierURL"	=> IsSet($ret["cashierUrl"]) ? $ret["cashierUrl"] : "",
			"RET_IsForwardCashier"	=> IsSet($ret["isForwardCashier"]) ? $ret["isForwardCashier"] : ""
		);	
		self::apiRequestLogSet($requestNo,$parm,$rs);
		
		//支付请求未受理，需要把流水设为无效
		if(!IsSet($ret["is_success"]) || $ret["is_success"] == "F" || (IsSet($ret["result"]) && $ret["result"] == "false"))
		{
			self::apiPoolBillSet(null,$requestNo,BILL_STATE_INVALID,$rs);
			self::apiUserBillSet(null,$requestNo,BILL_STATE_INVALID,$userID,$rs);
			
			if(IsSet($ret["error_message"]))		return array("result"=>false,"error_message"=>$ret["error_message"]);
			else									return array("result"=>false,"error_code"=>ERR_PAY_GETWAY_ERROR);
		}
		
		if(IsSet($ret["cashierUrl"]) && $ret["cashierUrl"] != "")	return array("result"=>true,"url"=>$ret["cashierUrl"] . "&signToken=$lastToken");
		else if(IsSet($ret["error_message"]))						return array("result"=>false,"error_message"=>$ret["error_message"]);
		else														return array("result"=>false,"error_code"=>ERR_PAY_GETWAY_ERROR);
	}
			
	//网关异步通知
	public static function apiPaidAsyncNotify($parm)
	{
		$rs = new RecordSets();
		
		if(!IsSet($parm["notify_type"]))		return "notify_type illegal";
		
		//付款到卡
		if($parm["notify_type"] == "withdrawal_status_sync")
		{	$amount = $parm["withdrawal_amount"];
		}
		//用卡支付
		else if($parm["notify_type"] == "trade_status_sync")
		{	$amount = $parm["trade_amount"];
		}
				
		//解析inquiryID与requestNo
		$inquiryID = 0;
		$requestNo = "";
		$a = Explode("_",$parm["outer_trade_no"]);
		//充值、提现不附加InquiryID
		if(Count($a) == 1)
		{	$requestNo = $parm["outer_trade_no"];
		}
		else
		{	$inquiryID = $a[0];
			$requestNo = $a[1];
		}
		
		//平台付款到用户银行卡，只需要记录付款失败的回滚流水
		if($parm["notify_type"] == "withdrawal_status_sync" && IsSet($parm["withdrawal_status"]))
		{
			if($parm["withdrawal_status"] == "WITHDRAWAL_SUCCESS")
			{
				return true;
			}
			else if($parm["withdrawal_status"] == "WITHDRAWAL_FAIL")
			{
				//=========================== 资金池流水操作 =============================
				$rs->ExecSQL("SELECT * FROM pay_PoolBill WHERE RequestNo = :RequestNo",array("RequestNo"=>$requestNo));
				if($rs->RecordCount == 0)
				{	$msg = "当 request_no = $requestNo 时 pay_PoolBill 没有对应记录";
					self::apiErrorLogAdd("PAY_RESPONSE",$msg,$rs);
					return $msg;
				}
				if(abs($rs->Amount) != $amount)
				{	self::apiErrorLogAdd("PAY_RESPONSE","当 request_no = $requestNo 时 pay_PoolBill 的Amount != $amount",$rs);
				}
				$rs->ExecSQL("SELECT * FROM pay_PoolBill WHERE RequestNo = :RequestNo",array("RequestNo"=>$requestNo."+"));
				if($rs->RecordCount > 0)
				{	$msg = "当 request_no = $requestNo+ 时 pay_PoolBill 已有对应记录";
					self::apiErrorLogAdd("PAY_RESPONSE",$msg,$rs);
					return $msg;
				}
				
				//=========================== 用户流水操作 =============================
				$rs->ExecSQL("SELECT * FROM pay_UserBill WHERE RequestNo = :RequestNo",array("RequestNo"=>$requestNo));
				//记录错误日志
				if($rs->RecordCount == 0)
				{	$msg = "当 request_no = $requestNo 时 pay_UserBill 没有对应记录";
					self::apiErrorLogAdd("PAY_RESPONSE",$msg,$rs);
					return $msg;
				}
				$userID = $rs->UserID;
				$userName = $rs->UserName;
				if(abs($rs->Amount) != $amount)
				{	self::apiErrorLogAdd("PAY_RESPONSE","当 request_no = $requestNo 时 pay_UserBill 的Amount != $amount",$rs);
				}
				
				//是针对此$requestNo的附加回滚操作
				$subject = "回滚：$requestNo\r\n" . $parm["fail_reason"];
				$requestNo = $requestNo . "+";
				
				//资金池流水参数
				$parm2 = array(
					"UserID"	=> $userID,
					"UserName"	=> $userName,
					"Amount"	=> $amount,
					"RequestNo"	=> $requestNo,
				);
				
				//用户流水参数
				$parm3 = array(
					"UserID"	=> $userID,
					"UserName"	=> $userName,
					"InquiryID"	=> 0,
					"Subject"	=> $subject,
					"Amount"	=> $amount,
					"Source"	=> BILL_SOURCE_ROLLBACK,
					"IP"		=> getClientIP(),
					"RequestNo"	=> $requestNo
				);
				
				//添加回滚流水
				try
				{
					$rs->beginTrans();
					
					$id2 = self::apiPoolBillAdd($parm2,$rs);
					self::apiPoolBillSet($id2,null,BILL_STATE_VALID,$rs);
				
					$id3 = self::apiUserBillAdd($parm3,$rs);
					self::apiUserBillSet($id3,null,BILL_STATE_VALID,$userID,$rs);
					
					$rs->commit();
					
					return true;
				}
				catch(exception $e)
				{
					$msg = $e->getMessage();
					self::apiErrorLogAdd("PAY_RESPONSE",$msg,$rs);
					return $msg;
				}
			}
			
			return "收到无效参数";
		}
		
		//用户付款到平台，需要添加流水，并处理运单
		else if($parm["notify_type"] == "trade_status_sync")
		{
			//=========================== 资金池流水操作 =============================
			$rs->ExecSQL("SELECT * FROM pay_PoolBill WHERE RequestNo = :RequestNo",array("RequestNo"=>$requestNo));
			if($rs->RecordCount == 0)
			{	$msg = "当 request_no = $requestNo 时 pay_PoolBill 没有对应记录";
				self::apiErrorLogAdd("PAY_RESPONSE",$msg,$rs);
				return $msg;
			}
			if($rs->State != BILL_STATE_NONE)
			{	$msg = "当 request_no = $requestNo 时 pay_PoolBill 的状态不为NONE";
				self::apiErrorLogAdd("PAY_RESPONSE",$msg,$rs);
				return $msg ;
			}
			if(abs($rs->Amount) != $amount)
			{	self::apiErrorLogAdd("PAY_RESPONSE","当 request_no = $requestNo 时 pay_PoolBill 的Amount != $amount",$rs);
			}
			$rs->Close();
			
			//修改资金池流水余额与状态
			$ret = self::apiPoolBillSet(0,$requestNo,BILL_STATE_VALID,$rs,(IsSet($parm["signToken"]) ? $parm["signToken"] : ""));
			if($ret !== true)
			{	$msg = "SetPoolBill(request_no = $requestNo)　返回：$ret";
				self::apiErrorLogAdd("PAY_RESPONSE",$msg,$rs);
				return $msg;
			}
			
			//=========================== 用户流水操作 =============================
			$rs->ExecSQL("SELECT * FROM pay_UserBill WHERE RequestNo = :RequestNo",array("RequestNo"=>$requestNo));
			//记录错误日志
			if($rs->RecordCount == 0)
			{	$msg = "当 request_no = $requestNo 时 pay_UserBill 没有对应记录";
				self::apiErrorLogAdd("PAY_RESPONSE",$msg,$rs);
				return $msg;
			}
			if($rs->State != BILL_STATE_NONE)
			{	$msg = "当 request_no = $requestNo 时 pay_UserBill 的状态不为NONE";
				self::apiErrorLogAdd("PAY_RESPONSE",$msg,$rs);
				return $msg;
			}
			$userID = $rs->UserID;
			$subject = $rs->Subject;

			if(abs($rs->Amount) != $amount)
			{	self::apiErrorLogAdd("PAY_RESPONSE","当 request_no = $requestNo 时 pay_UserBill 的Amount != $amount",$rs);
			}
			
			
			//修改用户流水余额与状态
			$ret = self::apiUserBillSet(0,$requestNo,BILL_STATE_VALID,$userID,$rs);
			if($ret !== true)
			{	$msg = "SetUserlBill(request_no = $requestNo)　返回：$ret";
				self::apiErrorLogAdd("PAY_RESPONSE",$msg,$rs);
				return $msg;
			}
		
			
			//=========================== 运单操作 ============================
			if($inquiryID != 0)
			{
				$rs->beginTrans();
				
				$rs->ExecSQL("SELECT * FROM Inquiry WHERE ID = :InquiryID",array("InquiryID"=>$inquiryID));
				if($rs->RecordCount == 0)
				{	$rs->rollback();
					$msg = "当(requestNo == $request && inquiryID == $inquiryID) 时 Inquiry 没有对应记录";
					self::apiErrorLogAdd("PAY_RESPONSE",$msg,$rs);
					return $msg;
				}
				if($rs->InPaying != 0)
				{	self::apiErrorLogAdd("PAY_RESPONSE","当(requestNo == $request && inquiryID == $inquiryID) 时 Inquiry.InPaying = 0",$rs);
				}
				
				$doneAmount = $rs->TotalSum;
				$inquiryState = $rs->State;
				$supplyUserID = $rs->SupplyUserID;
				$supplyUserName = $rs->SupplyUserName;
				$shipUserID = $rs->ShipUserID;
				$shipUserName = $rs->ShipUserName;
				$shipSubject = sprintf("收款，单号：%s",$rs->OrderNo);
				
				$sqlParm = array("ID"=>$inquiryID);
				$sql = "UPDATE Inquiry SET IsPaying = 0,State = :State";
				
				//如果是订单状态，改为已付定金状态
				if($rs->State == INQ_STATE_ORDER)
				{	$sql .= ",Deposit = :Deposit,DepositDate = Now()";
					$sqlParm["Deposit"] = $amount;
					$sqlParm["State"] = INQ_STATE_DEPOSIT;
				}
				
				//如果是已付定金状态，改为完成状态
				else if($rs->State == INQ_STATE_DEPOSIT)
				{	$sql .= ",DoneDate = Now()";
					$sqlParm["State"] = INQ_STATE_DONE;
				}
				
				//不在这两个状态，说明出错了
				else
				{	$rs->rollback();
					$msg = "当(requestNo == $request && inquiryID == $inquiryID) 时 Inquiry.State = $rs->State 状态不合法";
					self::apiErrorLogAdd("PAY_RESPONSE",$msg,$rs);
					return $msg;
				}
				
				//先添加货主流水，把支付的金额转移到平台
				$ubParm = array(
					"UserID"	=> $supplyUserID,
					"UserName"	=> $supplyUserName,
					"InquiryID"	=> $inquiryID,
					"Subject"	=> $subject,
					"Amount"	=> -$amount,
					"Source"	=> BILL_SOURCE_PAY,
					"IP"		=> getClientIP(),
					"RequestNo"	=> ""
				);
				$idUserBill = self::apiUserBillAdd($ubParm,$rs);
				
				
				//------------------- 修改运单状态 --------------------
				$sql .= " WHERE ID = :ID";
				$rs->Close();
				$rs->ExecSQL($sql,$sqlParm);
				//----------------------------------------------------
				
				
				//生效货主流水
				$ret = self::apiUserBillSet($idUserBill,null,BILL_STATE_VALID,$userID,$rs);
				if($ret !== true)
				{	$rs->rollback();
					$msg = "SetUserBill(ID = $idUserBill) 返回：$ret";
					self::apiErrorLogAdd("PAY_RESPONSE",$msg,$rs);
					return $msg;
				}
				
				//原来是订金状态，那么现在就是确定收货操作
				//确认收货后，需要把运单金额支付到船东帐户
				if($inquiryState == INQ_STATE_DEPOSIT)
				{
					//先添加货主流水，把支付的金额转移到平台
					$ubParm = array(
						"UserID"	=> $shipUserID,
						"UserName"	=> $shipUserName,
						"InquiryID"	=> $inquiryID,
						"Subject"	=> $shipSubject,
						"Amount"	=> $doneAmount,
						"Source"	=> BILL_SOURCE_PAY,
						"IP"		=> getClientIP(),
						"RequestNo"	=> ""
					);
					
					$idUserBill = self::apiUserBillAdd($ubParm,$rs);
					$ret = self::apiUserBillSet($idUserBill,"",BILL_STATE_VALID,$shipUserID,$rs);

					if($ret !== true)
					{	$rs->rollback();
						$msg = "SetUserBill(ID = $idUserBill) 返回：$ret";
						self::apiErrorLogAdd("PAY_RESPONSE",$msg,$rs);
						return $msg;
					}
				}
				
				$rs->commit();
			}
			
			return true;
		}
		
		return "收到无效参数";
	}
}
?>