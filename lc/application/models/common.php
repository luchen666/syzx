<?php
include_once(MODELS_PATH . "/BaseModel.php");

define("ROWS_PER_RETRIEVE",20);

class Common Extends BaseModel
{
	//今日数据
	public static function TodayCount()
	{
		//今日运价
		$sql = "SELECT ROUND(SU.Qty / 100) * 100 AS Qty,
					   SU.GoodsName,
					   ROUND(SU.Price) AS Price,
					   SU.TaxInclusive,
					   FR.Name AS FromRegion,
					   TR.Name AS ToRegion
				  FROM (((	Supply AS SU 
							INNER JOIN Port AS FP ON SU.FromPortID = FP.ID 
					      )	INNER JOIN base_Region AS FR ON FP.RegionID = FR.ID
						) INNER JOIN Port AS TP ON SU.ToPortID = TP.ID 
					    ) INNER JOIN base_Region AS TR ON TP.RegionID = TR.ID
				 WHERE SU.IsDeleted = 0 AND SU.State <> :STATE_INVALID
			  ORDER BY SU.ID DESC
				 LIMIT 10";
				 
		$rs = ExecSQL($sql,array("STATE_INVALID"=>SUPSCH_STATE_INVALID));
		$ret = array();
		$ret["TodayTransportPrice"] = $rs->AsArray();
		
		//今日船期
		$rs->Close();
		$rs->ExecSQL("SELECT Count(ID) AS Cnt FROM ShipSch WHERE IsDeleted = 0 AND DATEDIFF(CreateDate,CURDATE()) = 0");
		$ret["TodaySchNumber"] = $rs->Cnt;
		
		//今日货源
		$rs->Close();
		$rs->ExecSQL("SELECT Count(ID) AS Cnt FROM Supply WHERE IsDeleted = 0 AND DATEDIFF(CreateDate,CURDATE()) = 0");
		$ret["TodaySupplyNumber"] = $rs->Cnt;
		
		//总船期
		$rs->Close();
		$rs->ExecSQL("SELECT Count(ID) AS Cnt FROM ShipSch WHERE IsDeleted = 0");
		$ret["SchTotal"] = $rs->Cnt;
		
		//成交公告
		$sql = "SELECT ROUND(IQ.Qty) AS Qty,
					   IQ.GoodsName,
					   FR.Name AS FromRegion,
					   TR.Name AS ToRegion,
					   DATE_FORMAT(IQ.OrderDate,'%m-%d') AS DealDate
				  FROM (((	Inquiry AS IQ
							INNER JOIN Port AS FP ON IQ.FromPortID = FP.ID 
					      )	INNER JOIN base_Region AS FR ON FP.RegionID = FR.ID
						) INNER JOIN Port AS TP ON IQ.ToPortID = TP.ID 
					    ) INNER JOIN base_Region AS TR ON TP.RegionID = TR.ID
				 WHERE IQ.State >= :INQ_STATE_ORDER AND 
					   IQ.State < :INQ_STATE_INVALID
			  ORDER BY IQ.ID DESC
				 LIMIT 10";
		$rs->Close();
		$rs->ExecSQL($sql,array("INQ_STATE_ORDER"=>INQ_STATE_ORDER,"INQ_STATE_INVALID"=>INQ_STATE_INVALID));
		$ret["TodayDealOrder"] = $rs->AsArray();
		
		//首页船期
		$sql =  "SELECT SS.ID,
						SS.ShipType,
						SH.ShipName,
						SH.SeaOrRiver,
						SH.Tonnage,
						FR.Name AS Region,
						SS.ClearPortName,
						SS.ClearDate,
						SS.CreateDate
				   FROM	(( ShipSch AS SS INNER JOIN Ship AS SH ON SS.ShipID = SH.ID
						) INNER JOIN Port AS FP ON SS.ClearPortID = FP.ID 
					    ) INNER JOIN base_Region AS FR ON FP.RegionID = FR.ID
				  WHERE	SS.IsDeleted = 0 AND SS.State = :STATE_NONE 
			   ORDER BY SS.ID DESC
				  LIMIT 8";		//AND SS.ClearDate > curdate()
		$rs->Close();
		$rs->ExecSQL($sql,Array("STATE_NONE"=>SUPSCH_STATE_NONE));
		$ret["ShipSch"] = $rs->AsArray();
		return $ret;
	}
	
	//港口列表
	public static function getRegionPort($regionID = 0,$get = "PORT")
	{	
		if($get == "PROVINCE")
		{	$rs = ExecSQL("SELECT ID,Name FROM base_Region WHERE Layer = 1 ORDER BY SortNo,ID");
			return $rs->RecordCount > 0 ? $rs->AsArray() : null;
		}
		if($get == "CITY")
		{	$rs = ExecSQL("SELECT ID,Name FROM base_Region WHERE ProvinceID = $regionID AND Layer = 2 ORDER BY SortNo,ID");
			return $rs->RecordCount > 0 ? $rs->AsArray() : null;
		}
		if($get == "PORT")
		{	$rs = ExecSQL("SELECT ID,PortName FROM Port WHERE RegionID = $regionID AND IsDeleted = 0 ORDER BY SortNo,PortName");
			return $rs->RecordCount > 0 ? $rs->AsArray() : null;
		}
	}
	
	//船期列表
	public static function getShipSchList($act,$id,$supplyUserID,$where = array())
	{
		if($act == "NEW")	$opr = ">";
		if($act == "OLD")	$opr = "<";
		
		$sql =  "SELECT SS.ID,
						SS.ShipType,
						FR.Name AS Region,
						SS.ClearPortID,
						SS.ClearPortName,
						SS.ClearDate,
						SS.CreateDate,
						SH.ShipName,
						SH.SeaOrRiver,
						SH.Tonnage,
						SH.Long,
						SH.Width,
						SH.Deep,
						SH.LogoImage,
						SH.Star,
						SH.CertifyState,
						SH.MadeDate,
						IfNull(IQ.ID,0) > 0 AS IpostSupply
				   FROM	((( ShipSch AS SS INNER JOIN Ship AS SH ON SS.ShipID = SH.ID
						) INNER JOIN Port AS FP ON SS.ClearPortID = FP.ID 
					    ) INNER JOIN base_Region AS FR ON FP.RegionID = FR.ID
						) LEFT JOIN Inquiry AS IQ ON SS.ID = IQ.ShipSchID AND IQ.SupplyUserID = :UserID
				  WHERE	SS.IsDeleted = 0 AND SS.State = :STATE_NONE AND %s
			   ORDER BY SS.ID DESC
				  LIMIT " . ROWS_PER_RETRIEVE;
			   
		$txt = ($id > -1) ? "SS.ID $opr $id" : "1 = 1";
		$p = array("STATE_NONE"=>SUPSCH_STATE_NONE,"UserID"=>$supplyUserID);
		
		//指定了港口，就不用管地区了
		if(IsSet($where["ClearPortID"]) && $where["ClearPortID"] != "" && $where["ClearPortID"] != 0)
		{	$txt .= " AND SS.ClearPortID = :ClearPortID";
			$p["ClearPortID"] = $where["ClearPortID"];
		}
		else if(IsSet($where["ClearPortRegionID"]) && $where["ClearPortRegionID"] != "" && $where["ClearPortRegionID"] > 0)
		{	$txt .= " AND FP.RegionID = :RegionID";
			$p["RegionID"] = $where["ClearPortRegionID"];
		}
		if(IsSet($where["ClearDateFrom"]) && $where["ClearDateFrom"] != "")
		{	$txt .= " AND SS.ClearDate >= :ClearDateFrom";
			$p["ClearDateFrom"] = $where["ClearDateFrom"];
		}
		if(IsSet($where["ClearDateTo"]) && $where["ClearDateTo"] != "")
		{	$txt .= " AND SS.ClearDate <= :ClearDateTo";
			$p["ClearDateTo"] = $where["ClearDateTo"];
		}
		if(IsSet($where["TonnageFrom"]) && $where["TonnageFrom"] != "" && $where["TonnageFrom"] > 0)
		{	$txt .= " AND SH.Tonnage >= :TonnageFrom";
			$p["TonnageFrom"] = $where["TonnageFrom"];
		}
		if(IsSet($where["TonnageTo"]) && $where["TonnageTo"] != "" && $where["TonnageTo"] > 0)
		{	$txt .= " AND SH.Tonnage <= :TonnageTo";
			$p["TonnageTo"] = $where["TonnageTo"];
		}
		if(IsSet($where["ShipName"]) && $where["ShipName"] != "")
		{	$txt .= " AND SH.ShipName LIKE :ShipName";
			$p["ShipName"] = "%" . $where["ShipName"] . "%";
		}
		if(IsSet($where["ShipID"]) && $where["ShipID"] != "")
		{	$txt .= " AND SS.ShipID = :ShipID";
			$p["ShipID"] = $where["ShipID"];
		}
		$sql = sprintf($sql,$txt);
		$rs = ExecSQL($sql,$p);
		return $rs->RecordCount > 0 ? $rs->AsArray() : null;
	}
	
	//获取包装方式
	public static function getPackageMehtodList()
	{	$rs = ExecSQL("SELECT * FROM base_PackageMethod ORDER BY SortNo,ID");
		return $rs->RecordCount > 0 ? $rs->AsArray() : null;
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
	public static function login($username,$password,$ip)
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
		$sql =  "UPDATE sys_Users SET LastLoginTime = Now(),LastLoginIP = :ip,LoginCount = IFNULL(LoginCount,0) + 1 WHERE UserID = :id";
		$rs->ExecSQL($sql,array("ip"=>$ip,"id"=>$user["UserID"]));
		return $user;
	}
	
	//自动登录
	public static function autoLogin($userid,$ip)
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
		$rs->ExecSQL($sql,array("ip"=>$ip,"UserID"=>$userid));
		
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
		
		$user["SupplyNumber"]	= $number["SupCnt"];
		$user["ShipSchNumber"]	= $number["SchCnt"];
		$user["InquiryNumber"]	= $number["InqCnt"];
		
		return $user;
	}
	
	//获取用户当前信息
	public static function getUser($userID)
	{
		$rs = ExecSQL("SELECT * FROM sys_users WHERE UserID = $userID");
		return $rs->AsArray();
	}
	
	//修改用户资料$CurrentUserID,$post["Name"],$post["Sex"],$post["MobilePhone"],$post["TelePhone"],$post["Email"],$post["Duty"],$post["Avatar"]
	public static function setUser($userID,$name,$sex,$mobilePhone,$telePhone,$email,$duty,$headImg)
	{
		$sql = "UPDATE sys_users
				SET Name = :Name,Sex=:Sex,MobilePhone=:MobilePhone,TelePhone=:TelePhone,Email=:Email,Duty=:Duty,Avatar=:Avatar
				WHERE UserID =:UserID";
		ExecSQL($sql,array("UserID"=>$userID,"Name"=>$name,"Sex"=>$sex,"MobilePhone"=>$mobilePhone,"TelePhone"=>$telePhone,"Email"=>$email,"Duty"=>$duty,"Avatar"=>$headImg));
		return true;
	}

	//修改密码changePwd
	public static function changePwd($userID,$password0,$password1)
	{
		$rs = ExecSQL("SELECT Password,Salt FROM sys_Users WHERE UserID = $userID");
		$user = $rs->asArray()[0];

		//-2 密码错误
		$pwd = md5($password0.$user["Salt"]);
		if($pwd != $user["Password"])	return -2;

		$pwd = md5($password1.$user["Salt"]);
		$sql = "UPDATE sys_users SET Password = :Password WHERE UserID = :UserID";
		$rs->ExecSQL($sql,array("Password"=>$pwd,"UserID"=>$userID));
		return true;
	}
	
	//忘记密码
	public static function forgetPwd($mobilePhone,$newPwd)
	{	include_once(APP_PATH . "/Utils/Utils.php");
		$salt = MakeRand(6);
		$pwd = md5($newPwd . $salt);
		$sql = "UPDATE sys_users SET Password = :Pwd,Salt = :Salt,UpdateDate=Now() WHERE Account =:MobilePhone";
		ExecSQL($sql,array("Pwd"=>$pwd,"Salt"=>$salt,"MobilePhone"=>$mobilePhone));
		return true;
	}
	
	public static function getSupplyByID($id)
	{
		$rs = ExecSQL("SELECT * FROM Supply WHERE IsDeleted = 0 AND ID = $id");
		return $rs->recordCount == 0 ? null : $rs->AsArray()[0];
	}
	
	public static function delSupplyByID($id,$userID)
	{
		ExecSQL("UPDATE Supply SET IsDeleted = 1,UpdateUserID = $userID,UpdateDate = Now() WHERE ID = $id AND UserID = $userID");
		return true;
	}
	
	//用户注册
	public static  function register($account,$password)
	{
		include_once(APP_PATH . "/Utils/Utils.php");

		$rs = new RecordSets();
		$rs->ExecSQL("SELECT Count(UserID) AS Cnt FROM sys_Users WHERE Account = :Account",Array("Account"=>$account));
		if($rs->Cnt > 0)	return 1;	//return ERR_ACCOUNT_EXISTS;

		$sql = "INSERT INTO sys_Users
						(	Account,
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
							CreateUserID,
							CreateUserName,
							CreateDate
						)
				VALUE  (	:Account,
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
							0,
							'注册',
							Now()
						)";
		$salt = MakeRand(6);
		$pwd = md5($password . $salt);

		$parm = array(	"Account"		=>	$account,
						 "Password"	=>	$pwd,
						 "Salt"			=>	$salt,
						 "MobilePhone"=>	$account,
						 "RegIP"		=>	getClientIP()
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

	//手机验证码
	public static function sendVCode($mobilePhone,$code)
	{
		include_once(APP_PATH . "/utils/sms.php");
		$ret = sendVerifyCode($mobilePhone,$code);

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
							Code,
							MakeTime,
							State,
							ErrorMessage
						)
				VALUES	(	:MobilePhone,
							:Code,
							Now(),
							:State,
							:Msg
						)";
		try
		{
			ExecSQL($sql,array("MobilePhone"=>$mobilePhone,"Code"=>$code,"State"=>$state,"Msg"=>$msg));
			return $ret;
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
	//提取最近的验证码
	public static function getVCode($mobilePhone)
	{
		$sql = "SELECT Code,MakeTime,TIMESTAMPDIFF(SECOND,MakeTime,CURRENT_TIMESTAMP()) AS `Long` FROM USR_SMS WHERE MobilePhone = :MobilePhone ORDER BY ID DESC LIMIT 1";
		$rs = ExecSQL($sql,array("MobilePhone"=>$mobilePhone));
		return ($rs->RecordCount > 0) ? $rs->AsArray()[0] : null;
	}
		
	//取用户支付记录明细
	public static function getUserBillList($act,$id,$userID,$dateFrom,$dateTo,$rows)
	{
		if($act == "NEW")		$act = ">";
		else if($act == "OLD")	$act = "<";
		else					$act = "=";
		
		$sql =  "SELECT * FROM pay_UserBill WHERE ID $act $id AND UserID = $userID AND TO_DAYS(CreateDate) BETWEEN TO_DAYS(:DateFrom) AND TO_DAYS(:DateTo) ORDER BY ID DESC LIMIT $rows";
		$rs = ExecSQL($sql,array("DateFrom"=>$dateFrom,"DateTo"=>$dateTo));
		return ($rs->RecordCount > 0) ? $rs->AsArray() : null;
	}
	
	//银行列表
	public static function getBankList()
	{
		$rs = ExecSQL("SELECT Name,Code FROM base_Bank WHERE State = 1 ORDER BY SortNo,ID");
		return ($rs->RecordCount > 0) ? $rs->AsArray() : null;
	}
	
	//首页需求
	public static function AddUserRequest($content)
	{
		include_once(APP_PATH . "/Utils/Utils.php");
		$rs = ExecSQL("INSERT INTO UserRequest(Content,State,IP,CreateDate)VALUES( :Content , 0 , :IP , Now())",array("Content"=>$content,"IP"	=>getClientIP()));
		return true;
	}
}
?>