<?php
/*
 * UserModel
 *
 * 编写：谢忠杰
 *
 */

include_once(MODELS_PATH . "/BaseModel.php");

class User Extends BaseModel
{
	
	public static function apiInit()
	{
		include_once("Account.php");
		
		$appConfig = array(
			"PAY_PUBLIC_KEY"			=> RSA_PUBLIC_KEY,				//公钥
			"SUPPORT_TELEPHONE"			=> SITE_SUPPORT_TELEPHONE,		//服务电话
			"ADDSCH_MUSTCERTIFIED"		=> SITE_ADDSCH_MUSTCERTIFIED,	//1: 船舶必须通过验证才能发船期  0: 只要注册了船舶就可以发船期
			"ADDSUPPLY_MUSTCERTIFIED"	=> SITE_ADDSUPPLY_MUSTCERTIFIED,//1：用户必须通过验证才能发货源  0：注册即可发货源
			"VCODE_EXPIRE"				=> SITE_VCODE_EXPIRED,			//验证码获取间隔
			"VCODE_TYPE_REGISTER"		=> VCODE_TYPE_REGISTER,			//验证码类型：用户注册
			"VCODE_TYPE_FORGETPWD"		=> VCODE_TYPE_FORGETPWD,		//验证码类型：忘记密码
			"REFRESH_INTERVAL"			=> APP_REFRESH_INTERVAL,		//APP刷新通知间隔
			"ROWS_PER_PAGE"				=> APP_ROWS_PER_PAGE,			//列表页每页显示记录灵长
			"RECOMMEND_ROWS"			=> APP_RECOMMEND_ROWS,			//道页推荐列表数
			"BALANCE_MIN_RECHARGE"		=> 0.01,						//最小充值额
			"BALANCE_MAX_RECHARGE"		=> 0,							//最大充值额，0不限制
			"BALANCE_MIN_CASH"			=> 0.01,						//最小提现额
			"BALANCE_MAX_CASH"			=> 0,							//最大提现额，0不限制
			"PAY_DEPOSIT_RATIO"			=> 0.2							//预付订金比例默认值
			
		);
		
		$rs = ExecSQL("SELECT ID,Text FROM base_PackageMethod ORDER BY SortNo,ID");
		$packageMethod = $rs->AsArray();
		
		return array("APP_CONFIG"=>$appConfig,"PACKAGE_METHOD"=>$packageMethod);
	}
	
	//获取新闻 
	public static function apiGetNewsList($act="GT",$id=0,$rows=20)
	{
		if($act == "NEW")	$opr = ">";
		if($act == "OLD")	$opr = "<";
		
		$rs = ExecSQL("SELECT * FROM News WHERE State = 1 AND ID $opr $id  ORDER BY ID DESC LIMIT $rows");
		return ($rs->RecordCount == 0) ? null : $rs->AsArray();
	}
	
	//获取新闻 
	public static function apiGetNewsByID($id)
	{
		$rs = new RecordSets();
		$rs->ExecSQL("UPDATE News SET Hits = IFNULL(Hits,0) + 1 WHERE ID = $id");
		$rs->ExecSQL("SELECT * FROM News WHERE ID = $id");
		return ($rs->RecordCount == 0) ? null : $rs->AsArray()[0];
	}
	/*
	 * 用户名密码检查
	 *
	 * 参数：$username	- 帐号
	 *		 $password	- 密码
	 *		 $key		- 自动登录ID
	 *		 $ip		- IP地址
	 *
	 * 返回：-1		帐号不存
	 *		 -2		密码错误
	 *		 -3		帐号被锁定
	 *
	 */
	public static function apiLogin($username,$password,$key,$ip)
	{
		$rs = new RecordSets();
		
		$sql =  "SELECT US.UserID,
						US.Account,
						US.Password,
						US.Salt,
						US.Name,
						US.UserType,
						US.MobilePhone,
						US.Star,
						US.CertifyState,
						US.State,
						US.Avatar,
						US.PayPassword,
						US.EncodeCardNo,
						US.IsDeleted,
						CM.VIP,
						CM.Name AS CompanyName,
						CM.CertifyState AS CompanyCertifyState
				   FROM sys_Users AS US
				  		LEFT JOIN Company AS CM ON US.CompanyID = CM.ID
				  WHERE US.Account = :username";

		$parm = array("username" => $username);

		$rs->ExecSQL($sql,$parm);

		//-1 用户不存在
		if($rs->recordCount == 0)			return -1;

		$user = $rs->asArray()[0];
		
		$user["IsSetPayPassword"] = ($user["PayPassword"] == "" || $user["PayPassword"] == null)	 ? 0 : 1;
		$user["IsBindBankAccount"] = ($user["EncodeCardNo"] == "" || $user["EncodeCardNo"] == null)	 ? 0 : 1;
		unset($user["PayPassword"]);
		unset($user["EncodeCardNo"]);
		
		//-2 密码错误
		$pwd = md5($password . $user["Salt"]);
		if($pwd != $user["Password"])		return -2;

		//-3 用户被锁定
		if($user["State"] == 10)			return -3;

		//-4 用户被删除
		if($user["IsDeleted"] == 1)		return -4;

		//更新登录信息
		$sql =  "UPDATE sys_Users
					SET LastLoginTime = Now(),
					 	LastLoginIP	  = :ip,
					 	LoginCount	  = IFNULL(LoginCount,0) + 1,
					 	RememberKey	 = :key
				 WHERE UserID = :id";
		$rs->Close();
		$rs->ExecSQL($sql,array("ip"=>$ip,"key"=>$key,"id"=>$user["UserID"]));
		$rs->Close();
		return $user;
	}
	
	//自动登录
	public static function apiAutoLogin($userid,$ip)
	{
		$rs = new RecordSets();
		
			
		$sql =  "SELECT US.UserID,
						US.Name,
						US.UserType,
						US.MobilePhone,
						US.Star,
						US.CertifyState,
						US.State,
						US.Avatar,
						US.RememberKey,
						US.PayPassword,
						US.EncodeCardNo,
						US.IsDeleted,
						CM.VIP,
						CM.Name AS CompanyName,
						CM.CertifyState AS CompanyCertifyState
				   FROM sys_Users AS US
				  		LEFT JOIN Company AS CM ON US.CompanyID = CM.ID
				  WHERE US.UserID = :UserID";
		$parm = array("UserID" => $userid);

		$rs->ExecSQL($sql,$parm);

		//-1 用户不存在
		if($rs->recordCount == 0)		return -1;

		$user = $rs->asArray()[0];
		
		$user["IsSetPayPassword"] = ($user["PayPassword"] == "" || $user["PayPassword"] == null)	 ? 0 : 1;
		$user["IsBindBankAccount"] = ($user["EncodeCardNo"] == "" || $user["EncodeCardNo"] == null)	 ? 0 : 1;
		unset($user["PayPassword"]);
		unset($user["EncodeCardNo"]);
		
		//-3 用户被锁定
		if($user["State"] == 10)		return -3;

		//-4 用户被删除
		if($user["IsDeleted"] == 1)		return -4;

		//更新登录信息
		$sql = "UPDATE sys_Users
					SET LastLoginTime = Now(),
					 	LastLoginIP	  = :ip,
					 	LoginCount	  = IFNULL(LoginCount,0) + 1
				 WHERE UserID = :UserID";
		$rs->Close();
		$rs->ExecSQL($sql,array("ip"=>$ip,"UserID"=>$userid));
		$rs->Close();
		
		//船期数，货源数，运单数
		$sql = "SELECT SUM(SupCnt) AS SupCnt,SUM(SchCnt) AS SchCnt,SUM(InqCnt) AS InqCnt FROM (
				SELECT COUNT(ID) AS SupCnt,0 AS SchCnt,0 AS InqCnt FROM Supply WHERE UserID = :UserID AND IsDeleted = 0
				UNION
				SELECT 0,COUNT(ID),0 FROM ShipSch WHERE UserID = :UserID AND IsDeleted = 0
				UNION
				SELECT 0,0,COUNT(ID) FROM Inquiry WHERE (SupplyUserID = :UserID OR ShipUserID = :UserID) AND IsDeleted = 0
				) AS T1";
		$rs->ExecSQL($sql,array("UserID"=>$userid));
		$number = $rs->AsArray()[0];
		$rs->Close();
		
		$user["SupplyNumber"]	= $number["SupCnt"];
		$user["ShipSchNumber"]	= $number["SchCnt"];
		$user["InquiryNumber"]	= $number["InqCnt"];
		
		return $user;
	}
	
	//登出操作，清除登录数据
	public static function apiLogout($userid)
	{	ExecSQL("UPDATE sys_Users SET RememberKey = '' WHERE UserID = :UserID",array("UserID"=>$userid));
	}
	
	//获取用户数据
	public static function apiGetUser($id,$account="")
	{
			
		$sql =  "SELECT US.UserID,
						US.Name,
						US.Sex,
						US.Duty,
						US.Password,
						US.Salt,
						US.UserType,
						US.MobilePhone,
						US.Avatar,
						US.Star,
						US.IDImage1,
						US.IDImage2,
						US.CertifyState,
						US.State,
						US.IsDeleted,
						CM.ID AS CompanyID,
						CM.Name AS CompanyName,
						CM.VIP,
						CM.Address,
						CM.CodeCertificate,
						CM.BusinessLicenses,
						CM.TaxRegistration,
						CM.CertifyState AS CompanyCertifyState
				   FROM sys_Users AS US
						LEFT JOIN Company AS CM ON US.CompanyID = CM.ID
				  WHERE ";
				  
		if($account == "")
		{	$sql .= "US.UserID = :UserID";
			$parm = array("UserID" => $id);
		}
		else
		{	$sql .= "US.Account = :Account";
			$parm = array("Account" => $account);
		}
		$rs = ExecSQL($sql,$parm);

		if($rs->recordCount == 0)	return null;
		
		$a =  $rs->asArray();
		return $a[0];
	}
	
	//保存用户数据
	public static function apiSetUser($parms)
	{	
		$AvatarImage 			= "";
		$IDImageImage1			= "";
		$IDImageImage2			= "";
		$BusinessLicensesImage	= "";
		$CodeCertificateImage	= "";
		$TaxRegistrationImage	= "";
		
		$rs = new RecordSets();
		
		//=================  公司名为空，不修改资料 =================
		if($parms["CompanyName"] != "")	
		{	
			//用于生成单位证件临时文件名
			$preCompanyID = Is_Numeric($parms["CompanyID"]) ? $parms["CompanyID"] : 0;
			
			if(IsSet($parms["BusinessLicenses"]))
			{
				$BusinessLicensesImage = $parms["BusinessLicenses"];
				
				if(strlen($parms["BusinessLicenses"]) > 500)
				{	$BusinessLicensesImage = sprintf("%s/%d_BusinessLicenses.jpg",SITE_UPLOAD_DIR,$preCompanyID);
					Base64ToFile($parms["BusinessLicenses"],$BusinessLicensesImage);
				}
			}
			if(IsSet($parms["CodeCertificate"]))
			{
				$CodeCertificateImage = $parms["CodeCertificate"];
				
				if(strlen($parms["CodeCertificate"]) > 500)
				{	$CodeCertificateImage = sprintf("%s/%d_CodeCertificate.jpg",SITE_UPLOAD_DIR,$preCompanyID);
					Base64ToFile($parms["CodeCertificate"],$CodeCertificateImage);
				}
			}
			if(IsSet($parms["TaxRegistration"]))
			{
				$TaxRegistrationImage = $parms["TaxRegistration"];
				
				if(strlen($parms["TaxRegistration"]) > 500)
				{	$TaxRegistrationImage = sprintf("%s/%d_TaxRegistration.jpg",SITE_UPLOAD_DIR,$preCompanyID);
					Base64ToFile($parms["TaxRegistration"],$TaxRegistrationImage);
				}
			}
			
			//判断是添加公司信息，还是修改公司信息
			if(!Is_Numeric($parms["CompanyID"]))
			{
				$sql1 = "INSERT INTO Company
								(	Name,
									Address,
									VIP,
									CodeCertificate,
									BusinessLicenses,
									TaxRegistration,
									CertifyState,
									CreateUserID,
									CreateUserName,
									CreateDate
								)
						VALUES	(	:CompanyName,
									:Address,
									0,
									'',
									'',
									'',
									0,
									:UserID,
									:UserName,
									Now()
								)";
				$p1 = Array(
					"CompanyName"		=> $parms["CompanyName"],
					"Address"			=> $parms["Address"],
					"UserID"			=> $parms["UserID"],
					"UserName"			=> $parms["Name"]
				);
				
				$rs->beginTrans();
				try
				{	$rs->ExecSQL($sql1,$p1);
					$rs->ExecSQL("SELECT Max(ID) AS ID FROM Company");
					$parms["CompanyID"] = $rs->ID;
					$rs->commit();
				}
				catch(Exception $e)
				{
					return $e->getMessage();
				}
				
				//如果是新增单位，需要把临时文件名改为永久文件名
				if($BusinessLicensesImage != "")
				{
					$newFile = sprintf("%s/%d_BusinessLicenses.jpg",SITE_UPLOAD_DIR,$p2["CompanyID"]);
					rename($BusinessLicensesImage,$newFile);
					$BusinessLicensesImage = $newFile;
				}
				if($CodeCertificateImage != "")
				{
					$newFile = sprintf("%s/%d_CodeCertificate.jpg",SITE_UPLOAD_DIR,$p2["CompanyID"]);
					rename($CodeCertificateImage,$newFile);
					$BusinessLicensesImage = $newFile;
				}
				if($TaxRegistrationImage != "")
				{
					$newFile = sprintf("%s/%d_TaxRegistration.jpg",SITE_UPLOAD_DIR,$p2["CompanyID"]);
					rename($TaxRegistrationImage,$newFile);
					$TaxRegistrationImage = $newFile;
				}
			
			//是修改公司信息
			} else {	
			
				$sql1 = "UPDATE Company
							SET Name			= :CompanyName,
								Address			= :Address,
								CodeCertificate	= :CodeCertificate,
								BusinessLicenses= :BusinessLicenses,
								TaxRegistration	= :TaxRegistration
						WHERE ID = :CompanyID";
				$p1 = Array("CompanyName"		=> $parms["CompanyName"],
							"Address"			=> $parms["Address"],
							"CodeCertificate"	=> $CodeCertificateImage,
							"BusinessLicenses"	=> $BusinessLicensesImage,
							"TaxRegistration"	=> $TaxRegistrationImage,
							"CompanyID"			=> $parms["CompanyID"]
							);
				$rs->ExecSQL($sql1,$p1);
			}
		}
		
		//============================== 用户信息处理 ================================

		//用户图片处理
		if(IsSet($parms["Avatar"]))
		{	
			$AvatarImage = $parms["Avatar"];
			
			if(strlen($parms["Avatar"]) > 500)
			{	$AvatarImage = sprintf("%s/%d_Avatar.jpg",SITE_UPLOAD_DIR,$parms["UserID"]);
				Base64ToFile($parms["Avatar"],$AvatarImage);
			}
		}
		if(IsSet($parms["IDImage1"]))
		{
			$IDImage1 = $parms["IDImage1"];
			
			if(strlen($parms["IDImage1"]) > 500)
			{	$IDImage = sprintf("%s/%d_ID1.jpg",SITE_UPLOAD_DIR,$parms["UserID"]);
				Base64ToFile($parms["IDImage1"],$IDImage);
			}
		}
		if(IsSet($parms["IDImage2"]))
		{
			$IDImage2 = $parms["IDImage2"];
			
			if(strlen($parms["IDImage2"]) > 500)
			{	$IDImage = sprintf("%s/%d_ID2.jpg",SITE_UPLOAD_DIR,$parms["UserID"]);
				Base64ToFile($parms["IDImage2"],$IDImage2);
			}
		}
		
		//是否绑定公司资料
		if($parms["CompanyID"] == "" || $parms["CompanyName"] == "")	$parms["CompanyID"] = null;
		
		$sql2 = "UPDATE sys_Users
					SET Name		= :Name,
						Sex			= :Sex,
						Duty		= :Duty,
						Star		= :Star,
						Avatar		= :Avatar,
						IDImage1	= :IDImage1,
						IDImage2	= :IDImage2,
						CompanyID	= :CompanyID,
						UpdateDate	= Now()
				  WHERE UserID = :UserID";
		$p2 = array(
			"Name"		=> $parms["Name"],
			"Sex"		=> $parms["Sex"],
			"Duty"		=> $parms["Duty"],
			"Star"		=> $parms["Star"],
			"UserID"	=> $parms["UserID"],
			"CompanyID"	=> $parms["CompanyID"],
			"Avatar"	=> $AvatarImage,
			"IDImage1"	=> $IDImage1,
			"IDImage2"	=> $IDImage2,
		);
		
		//如果输入了单位信息，星级升到２
		if($parms["CompanyName"] != "" && $parms["Name"] != "" && $p2["Star"] == 1)		$p2["Star"] = 2;
		
		//用户资料
		$rs->ExecSQL($sql2,$p2);
		
		return true;
	}
	
	//修改密码
	public static function apiChangePwd($userID,$mobilePhone,$newPwd)
	{
		include_once(APP_PATH . "/Utils/Utils.php");
		
		$salt = MakeRand(6);
		$pwd = md5($newPwd . $salt);
		
		if($userID > 0)
			$sql = "UPDATE sys_Users SET Password = :Pwd,Salt = :Salt,UpdateDate = Now() WHERE UserID = :UserID";
		else
			$sql = "UPDATE sys_Users SET Password = :Pwd,Salt = :Salt,UserID = :UserID,UpdateDate = Now() WHERE Account = :MobilePhone";
		try
		{
			ExecSQL($sql,array("Pwd"=>$pwd,"Salt"=>$salt,"UserID"=>$userID,"MobilePhone"=>$mobilePhone));
			return true;
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
	
	//用户注册 
	public static function apiRegister($account,$password,$type)
	{
		include_once(APP_PATH . "/Utils/Utils.php");
		
		$rs = new RecordSets();
		$rs->ExecSQL("SELECT Count(UserID) AS Cnt FROM sys_Users WHERE Account = :Account",Array("Account"=>$account));
		if($rs->Cnt > 0)	return ERR_ACCOUNT_EXISTS;
		
		$sql = "INSERT INTO sys_Users
						(	Account,
							Sex,
							UserType,
							Password,
							Salt,
							MobilePhone,
							Star,
							RegIP,
							RegDate,
							State,
							CertifyState,
							IsDeleted,
							SortNo,
							UpdateDate
						)
				VALUES	(	:Account,
							'M',
							:UserType,
							:Password,
							:Salt,
							:MobilePhone,
							1,
							:RegIP,
							Now(),
							0,
							0,
							0,
							0,
							Now()
						)";
		$salt = MakeRand(6);
		$pwd = md5($password . $salt);
		$parm = array(	"Account"		=> $account,
						"UserType"		=> $type,
						"Password"		=> $pwd,
						"Salt"			=> $salt,
						"MobilePhone"	=> $account,
						"RegIP"			=> getClientIP()
					  );
		
		$rs->beginTrans();
		try
		{
			$rs->ExecSQL($sql,$parm);
			$rs->ExecSQL("SELECT MAX(UserID) AS UserID FROM sys_Users");
			$rs->commit();
			return $rs->UserID;
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
	
	
	/*
	 *	发短信
	 *	
	 *	参数：	mobilePhone		手机号
	 *			text			文本或验证码
	 *			type			发送类型，默为发文本
	 *							TEXT	发文本
	 *							VCODE	发验证码
	 * /
	public static function sendSMS($mobilePhone,$text,$type = "TEXT")
	{
		$config = Yaf_Registry::get("config")->sms;
		
		//格式化内容
		if($type == "VCODE")	$msg = sprintf($config->temp_vcode,$text);
		else					$msg = sprintf($config->temp_msg,$text);
		
		//发送
		//$ret = SendSms($mobilePhone,$msg);
		
		//存数据库
		$sql = "INSERT INTO USR_SMS
						(	MobilePhone,
							Type,
							Text,
							Code,
							MakeTime,
							State
						)
				VALUES	(	:MobilePhone,
							:Type,
							:Text,
							:Code,
							Now(),
							:State
						)";
		try
		{
			ExecSQL($sql,array("MobilePhone"=>$mobilePhone,"Type"=>($type == "TEXT" ? 10 : 20),"Text"=>$msg,"State"=>$ret,"Code"=>($type == "TEXT" ? "" : $text)));
			return true;
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
	*/
	public static function apiSendVCode($mobilePhone,$code,$type)
	{
		include_once(APP_PATH . "/utils/sms.php");
		
		$ret = sendVerifyCode($mobilePhone,$code,$type);
		
		if($ret === true)
		{	$state = 1;
			$msg = "";
		}
		else
		{	$state = 0;
			$msg = $ret;
		}
		$sql = "INSERT INTO usr_sms
						(	MobilePhone,
							Type,
							Code,
							MakeTime,
							State,
							ErrorMessage
						)
				VALUES	(	:MobilePhone,
							:Type,
							:Code,
							Now(),
							:State,
							:Msg
						)";
		try
		{
			ExecSQL($sql,array("MobilePhone"=>$mobilePhone,"Type"=>$type,"Code"=>$code,"State"=>$state,"Msg"=>$msg));
			
			return $ret;
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
	
	//提取最近的验证码
	public static function apiGetVCode($mobilePhone)
	{
		$sql = "SELECT Code,MakeTime,TIMESTAMPDIFF(SECOND,MakeTime,CURRENT_TIMESTAMP()) AS `Long` FROM USR_SMS WHERE MobilePhone = :MobilePhone ORDER BY ID DESC LIMIT 1";
		$rs = ExecSQL($sql,array("MobilePhone"=>$mobilePhone));
		return ($rs->RecordCount > 0) ? $rs->AsArray()[0] : null;
	}
	
	//滚动广告
	public static function apiGetSlideImage($type,$ids)
	{
		$sql = "SELECT Title,Image,Url FROM Banner WHERE IsShow = 1 AND (Now() BETWEEN FromDate AND ToDate) AND (UserType IS NULL OR UserType = '' OR UserType = :type) AND ClientType = 20 AND ID NOT IN ($ids) ORDER BY SortNo,ID";
		
		$rs = ExecSQL($sql,array("type" => $type));
		
		return ($rs->recordCount == 0) ? null : $rs->asArray();
	}
	
	//意见建议设置已读
	
	public static function apiSetSuggestRead($userID)
	{
		$sql = "UPDATE USR_Suggest SET IsRead = 1 WHERE UserID = :UserID AND CreateUserID <> :UserID";
		ExecSQL($sql,array("UserID"=>$userID));
		return true;
	}
	
	//获取用户的未读信息
	public static function apiGetUnRead($userID)
	{
		$rs = new RecordSets();
		$sql =  "SELECT State,Count(ID) AS N FROM Inquiry  WHERE (SupplyUserID = :UserID AND SupplyUserReadDate IS NULL) OR (ShipUserID = :UserID AND ShipUserReadDate IS NULL) GROUP BY State";
		$rs->ExecSQL($sql,array("UserID"=>$userID));
		if($rs->RecordCount > 0)	$inqA = $rs->AsArray();
		else						$inqA = Array();
		
		$sql = "SELECT Count(ID) AS N FROM USR_Suggest WHERE UserID = :UserID AND CreateUserID <> :UserID AND (IsRead = 0 OR IsRead IS NULL)";
		$rs->Close();
		$rs = ExecSQL($sql,array("UserID"=>$userID));
		$sugN = $rs->N;
		
		
		//船期数，货源数，运单数
		$sql = "SELECT COUNT(ID) AS N FROM Supply WHERE UserID = :UserID AND IsDeleted = 0";
		$rs->Close();
		$rs = ExecSQL($sql,array("UserID"=>$userID));
		$supN = $rs->N;
		
		$sql = "SELECT COUNT(ID) AS N FROM ShipSch WHERE UserID = :UserID AND IsDeleted = 0";
		$rs->Close();
		$rs = ExecSQL($sql,array("UserID"=>$userID));
		$schN = $rs->N;
		
		$sql = "SELECT COUNT(ID) AS N FROM Inquiry WHERE (SupplyUserID = :UserID OR ShipUserID = :UserID) AND IsDeleted = 0";
		$rs->Close();
		$rs = ExecSQL($sql,array("UserID"=>$userID));
		$inqN = $rs->N;

		$inq = Array(
			INQ_STATE_NONE		=> 0,
			INQ_STATE_ORDER		=> 0,
			INQ_STATE_DEPOSIT	=> 0,
			INQ_STATE_REFUND	=> 0,
			INQ_STATE_DONE		=> 0,
			INQ_STATE_INVALID	=> 0
		);
		
		foreach($inqA as $val)	$inq[$val["State"]] = $val["N"];

		return array(
			"Inquiry"		=> $inq,
			"SuggestUnread"	=> $sugN,
			"SupplyNumber"	=> $supN,
			"ShipSchNumber"	=> $schN,
			"InquiryNumber"	=> $inqN
		);
	}
	
	//根据省份获取城市，若省份号为0，获取省份列表
	public static function apiGetRegions($id)
	{
		if($id == 0)	$sql = "SELECT ID,Name,Layer,ProvinceID,HavePort FROM base_Region WHERE Layer = 1 ORDER BY ID";
		else			$sql = "SELECT ID,Name,Layer,ProvinceID,HavePort FROM base_Region WHERE Layer = 2 AND ProvinceID = $id ORDER BY ID";

		$rs = ExecSQL($sql);

		return ($rs->recordCount == 0) ? null : $rs->asArray();
	}

	//根据地区号获取港口列表
	public static function apiGetPorts($id,$range = "CITY")
	{
		if($range == "PROVINCE")
			$sql = "SELECT ID,PortName,RegionID,$id AS ProvinceID FROM Port WHERE RegionID IN (SELECT ID FROM base_Region WHERE ProvinceID = $id) ORDER BY RegionID";
		else
			$sql = "SELECT P.ID,PortName,RegionID,ProvinceID FROM Port AS P INNER JOIN base_Region AS R ON P.RegionID = R.ID WHERE P.IsDeleted = 0 AND P.RegionID = $id ORDER BY P.SortNo,P.PortName";

		$rs = ExecSQL($sql);

		return ($rs->recordCount == 0) ? null : $rs->asArray();
	}

	public static function apiSetPort($parms)
	{
		include_once(APP_PATH . "/utils/Utils.php");

		$ShortName = GetPinyin($parms["PortName"]);
		$FirstLetter = substr($ShortName,0,1);
		$Spell = GetPinyin($parms["PortName"],false);
		
		$parms["ShortSpell"]  = $ShortName;
		$parms["FirstLetter"] = $FirstLetter;
		$parms["Spell"]	    = $Spell;
		
		$provinceID = $parms["ProvinceID"];
		unset($parms["ProvinceID"]);
		
		$sql = "INSERT INTO Port
							(	PortName,
								ShortSpell,
								FirstLetter,
								Spell,
								RegionID,
								IsDeleted,
								State,
								SortNo,
								CreateUserID,
								CreateUserName,
								CreateDate
							)
					VALUES	(	:PortName,
								:ShortSpell,
								:FirstLetter,
								:Spell,
								:RegionID,
								0,
								0,
								0,
								:UserID,
								:UserName,
								Now()
							)";

		$rs = new RecordSets();
		$rs->beginTrans();
		try
		{
			$rs->ExecSQL($sql, $parms);
			$rs->ExecSQL("UPDATE base_Region SET HavePort = 1 WHERE ID = :PID",array("PID"=>$provinceID));
			$rs->ExecSQL("UPDATE base_Region SET HavePort = 1 WHERE ID = :PID",array("PID"=>$parms["RegionID"]));
			$rs->ExecSQL("SELECT Max(ID) AS ID FROM Port");
			$id = $rs->ID;
			$rs->commit();
			
			return $id;
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
	
	//根据城市编号获取RegionID
	public static function apiGetRegionIDByCity($city)
	{
		if($city == "")	return 0;
		
		$rs = ExecSQL("SELECT ID,ProvinceID FROM base_Region WHERE Name LIKE '$city%'");
		
		if($rs->recordCount > 0)	$ret = array("RegionID"=>$rs->ID,"ProvinceID"=>$rs->ProvinceID);
		else						$ret = array("RegionID"=>0,"ProvinceID"=>0);
		return $ret;
	}
}