<?php
/*
 * UserModel
 *
 * 编写：谢忠杰
 *
 */

include_once(MODELS_PATH . "/BaseModel.php");
class Account Extends BaseModel
{
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
		
		//船期数，货源数，运单数
		$sql = "SELECT SUM(SupCnt) AS SupCnt,SUM(SchCnt) AS SchCnt,SUM(InqCnt) AS InqCnt FROM (
				SELECT COUNT(ID) AS SupCnt,0 AS SchCnt,0 AS InqCnt FROM Supply WHERE UserID = :UserID AND IsDeleted = 0
				UNION
				SELECT 0,COUNT(ID),0 FROM ShipSch WHERE UserID = :UserID AND IsDeleted = 0
				UNION
				SELECT 0,0,COUNT(ID) FROM Inquiry WHERE (SupplyUserID = :UserID OR ShipUserID = :UserID) AND IsDeleted = 0
				) AS T1";
		$rs->ExecSQL($sql,array("UserID"=>$user["UserID"]));
		$number = $rs->AsArray()[0];
		$rs->Close();
		$user["SupplyNumber"]	= $number["SupCnt"];
		$user["ShipSchNumber"]	= $number["SchCnt"];
		$user["InquiryNumber"]	= $number["InqCnt"];
		
		/*
		//如果是代理或船东，还需要返回船舶信息
		$ships = array();
		if($user["UserType"] == "CD" || $user["UserType"] == "DL")
		{	$sql =  "SELECT SH.ID,
							SH.ShipName,
							SH.ShipTypeID,
							ST.ShipTypeName,
							SH.SeaOrRiver,
							SH.Tonnage,
							SH.MMSI,
							SH.MadeDate,
							SH.Long,
							SH.Width,
							SH.Deep,
							SH.Capacity,
							SH.FullDraught,
							SH.EmptyDraught,
							SH.HatchNum,
							SH.LogoImage,
							SH.Star,
							SH.RegistryPort,
							SH.InspectionCertificate,
							SH.NationalityCertificate,
							SH.CertifyState,
							SH.State
					   FROM Ship AS SH LEFT JOIN ShipType AS ST ON SH.ShipTypeID = ST.ID
					  WHERE SH.UserID = :UserID AND SH.IsDeleted = 0";
	
			$rs->ExecSQL($sql,array("UserID"=>$user["UserID"]));
			$ships = $rs->AsArray();
		}
		$ret = array("USER" => $user,"SHIP" => $ships);
		return $ret;
		*/	
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
		
		/*
		//如果是代理或船东，还需要返回船舶信息
		$ships = array();
		if($user["UserType"] == "CD" || $user["UserType"] == "DL")
		{	$sql =  "SELECT SH.ID,
							SH.ShipName,
							SH.ShipTypeID,
							ST.ShipTypeName,
							SH.SeaOrRiver,
							SH.Tonnage,
							SH.MMSI,
							SH.MadeDate,
							SH.Long,
							SH.Width,
							SH.Deep,
							SH.Capacity,
							SH.FullDraught,
							SH.EmptyDraught,
							SH.HatchNum,
							SH.LogoImage,
							SH.Star,
							SH.RegistryPort,
							SH.InspectionCertificate,
							SH.NationalityCertificate,
							SH.CertifyState,
							SH.State
					   FROM Ship AS SH LEFT JOIN ShipType AS ST ON SH.ShipTypeID = ST.ID
					  WHERE SH.UserID = :UserID AND SH.IsDeleted = 0";
			$rs->ExecSQL($sql,array("UserID"=>$userid));
			$ships = $rs->AsArray();
		}
			
		$ret = array("USER" => $user,"SHIP" => $ships);
		return $ret;
		*/
		return $user;
	}
	
	//登出操作
	public static function apiLogout($userid)
	{	ExecSQL("UPDATE sys_Users SET RememberKey = '' WHERE UserID = :UserID",array("UserID"=>$userid));
	}
	//拿到一个用户的详细信息
	// public static function apiGetUser($id)
	// {
			
	// 	$sql =  "SELECT US.UserID,
	// 					US.Name,
	// 					US.UserType,
	// 					US.MobilePhone,
	// 					US.Avatar,
	// 					US.Star,
	// 					US.CertifyState,
	// 					US.State,
	// 					US.IsDeleted,
	// 					CM.VIP,
	// 					CM.Name AS CompanyName,
	// 					CM.CertifyState AS CompanyCertifyState
	// 			   FROM sys_Users AS US
	// 					LEFT JOIN Company AS CM ON US.CompanyID = CM.ID
	// 			  WHERE US.UserID = :id";

	// 	$rs = ExecSQL($sql,array("id" => $id));

	// 	if($rs->recordCount == 0)	return null;

	// 	$a =  $rs->asArray();
	// 	return $a[0];
	// }

	public static function apiGetUser($id)
	{
		$sql =  "SELECT * FROM sys_Users WHERE UserID = :id";
		$rs = ExecSQL($sql,array("id" => $id));

		if($rs->recordCount == 0)	return null;

		$a =  $rs->asArray();
		return $a[0];
	}

	public static function apiGetSlideImage($type,$ids)
	{
		$sql = "SELECT * FROM Banner WHERE IsShow = 1 AND (Now() BETWEEN FromDate AND ToDate) AND (UserType IS NULL OR UserType = '' OR UserType = :type) AND ClientType <> 2 AND ID NOT IN ($ids) ORDER BY SortNo,ID";

		$rs = ExecSQL($sql,array("type" => $type));

		return ($rs->recordCount == 0) ? null : $rs->asArray();
	}

	public static function getShips($userID)
	{
		$sql = "SELECT * FROM Ship WHERE UserID = :userID AND IsDeleted = 0 AND CertifyState = 8 ORDER BY SortNo,ID";
		$rs = ExecSQL($sql,array("userID"=>$userID));

		return ($rs->recordCount == 0) ? null : $rs->asArray();
	}
	//拿到一条船的详细信息
	public static function apiGetoneShip($id)
	{
		$sql =  "SELECT * FROM Ship WHERE ID = :id";

		$rs = ExecSQL($sql,array("id" => $id));

		if($rs->recordCount == 0)	return null;

		$a =  $rs->asArray();
		return $a[0];
	}

	//拿到一个公司的详细信息
	public static function apiGetCompany($id)
	{
		$sql =  "SELECT * FROM Company WHERE ID = :id";

		$rs = ExecSQL($sql,array("id" => $id));

		if($rs->recordCount == 0)	return null;

		$a =  $rs->asArray();
		return $a[0];
	}

	//拿到一条船期的详细信息
	public static function apiGetoneShipSch($id)
	{
		$sql =  "SELECT * FROM Shipsch WHERE ID = :id";

		$rs = ExecSQL($sql,array("id" => $id));

		if($rs->recordCount == 0)	return null;

		$a =  $rs->asArray();
		return $a[0];
	}

    //拿到一条货盘supply的详细信息
	public static function apiGetoneSupply($id)
	{
		$sql =  "SELECT * FROM Supply WHERE ID = :id";

		$rs = ExecSQL($sql,array("id" => $id));

		if($rs->recordCount == 0)	return null;

		$a =  $rs->asArray();
		return $a[0];
	}

    //拿到一个港口port的详细信息
	public static function apigetonePort($id)
	{
		$sql =  "SELECT * FROM Port WHERE ID = :id";

		$rs = ExecSQL($sql,array("id" => $id));

		if($rs->recordCount == 0)	return null;

		$a =  $rs->asArray();
		return $a[0];
	}

    //拿到账户表sys_admin中一个Account的详细信息
	public static function apiGetoneAccount($id)
	{
		$sql =  "SELECT * FROM sys_admin WHERE Account = :Account";

		$rs = ExecSQL($sql,array("id" => $id));

		if($rs->recordCount == 0)	return null;

		$a =  $rs->asArray();
		return $a[0];
	}

	//拿到Inquiry中一个Inquiry的详细信息
	public static function apiGetoneInquiry($id)
	{
		$sql =  "SELECT * FROM inquiry WHERE ID = :id";

		$rs = ExecSQL($sql,array("id" => $id));

		if($rs->recordCount == 0)	return null;

		$a =  $rs->asArray();
		return $a[0];
	}
	//拿到poolbill中一个poolbill的详细信息
	public static function apiGetonepoolbill($id)
	{
		$sql =  "SELECT * FROM pay_poolbill WHERE ID = :id";

		$rs = ExecSQL($sql,array("id" => $id));

		if($rs->recordCount == 0)	return null;

		$a =  $rs->asArray();
		return $a[0];
	}

	//拿到一个对话的详细信息
	public static function apiGetComment($id)
	{
		$sql =  "SELECT * FROM usr_suggest WHERE UserID = :UserID ";

		$rs = ExecSQL($sql,array("id" => $id));

		if($rs->recordCount == 0)	return null;

		$a =  $rs->asArray();
		return $a[0];
	}

	//根据省份获取城市，若省份号为0，获取省份列表
	public static function apiGetRegions($id)
	{
		if($id == 0)
			$sql = "SELECT ID,Name,Layer,ProvinceID FROM Region WHERE Layer = 1 ORDER BY ID";
		else
			$sql = "SELECT ID,Name,Layer,ProvinceID FROM Region WHERE Layer = 2 AND ProvinceID = :id ORDER BY ID";

		$rs = ExecSQL($sql,array("id" => $id));

		return ($rs->recordCount == 0) ? null : $rs->asArray();
	}

	//根据地区号获取港口列表
	public static function apiGetPorts($id)
	{
		$sql = "SELECT ID,PortName,RegionID FROM POrt WHERE RegionID = :id AND IsDeleted = 0 ORDER BY SortNo,PortName";

		$rs = ExecSQL($sql,array("id" => $id));

		return ($rs->recordCount == 0) ? null : $rs->asArray();
	}

	public static function apiSetPort($parms)
	{
		include_once(APP_PATH . "/utils/Functions.php");

		$ShortName = GetPinyin($parms["PortName"]);
		$FirstLetter = substr($ShortName,0,1);
		$Spell = GetPinyin($parms["PortName"],false);

		$parms["ShortSpell"]  = $ShortName;
		$parms["FirstLetter"] = $FirstLetter;
		$parms["Spell"]	    = $Spell;

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
			$rs->ExecSQL("SELECT Max(ID) AS ID FROM Port");
			$id = $rs->ID;
			$rs->commit();
		}
		catch(Exception $e)
		{
			$rs->rollBack();
			$id = 0;
		}
		$rs = null;
		return $id;
	}
	
	//根据城市编号获取RegionID
	public static function apiGetRegionIDByCity($city)
	{
		if($city == "")	return 0;
		
		$rs = ExecSQL("SELECT ID FROM Region WHERE Name LIKE '$city%'");
		
		return  ($rs->recordCount > 0) ? $rs->ID : 0;
	}
	
	//我的运单
	public static function apiGetInquiryList($ACT,$userID,$ID,$Rows = 10,$where)
	{
		if($ACT == "GT")	$opr = ">";
		if($ACT == "LT")	$opr = "<";
		$sql = "SELECT * FROM Inquiry WHERE IsDeleted = 0 AND ID $opr :ID AND (SupplyUseriD = :UserID OR ShipUserID = :UserID)";
		
		//条件
		$parms = array("ID" => $ID,"UserID" => $userID);
		
		if(IsSet($where["StateFrom"]) && Is_Numeric($where["StateFrom"]) && $where["StateFrom"] > 0)
		{
			$sql .= " AND State >= :StateFrom";
			$parms["StateFrom"] = $where["StateFrom"];
		}
		if(IsSet($where["StateTo"]) && Is_Numeric($where["StateTo"]) && $where["StateTo"] > 0)
		{
			$sql .= " AND State <= :StateTo";
			$parms["StateTo"] = $where["StateTo"];
		}
		if(IsSet($where["Date"]) && $where["Date"] != "")
		{
			$sql .= " AND DateDiff(CreateDate,:Date) = 0";
			$parms["Date"] = $where["Date"];
		}
		
		$sql .= " ORDER BY ID DESC LIMIT $Rows";
		
		//查询SQL
		$rs = ExecSQL($sql,$parms);
		
		return $rs->RecordCount > 0 ? $rs->AsArray() : null;
	}
	
	//删除运单
	public static function apiDelInquiry($userID,$ids)
	{
		$sql = "UPDATE Inquiry SET IsDeleted = 1 WHERE (SupplyUseriD = $userID OR ShipUserID = $userID) AND ID IN ($ids)";
		try
		{
			ExecSQL($sql);
			return true;
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
	
	//修改运单状态
	public static function apiSetInquiryState($userID,$ID,$state,$supplyID,$shipSchID)
	{
		$sql1 = "UPDATE Inquiry SET State = $state,UpdateUserID = $userID,UpdateDate = Now() WHERE ID = $ID AND (SupplyUserID = $userID OR ShipUserID = $userID)";
		
		$n = 0;
		switch($state)
		{
			case INQ_STATE_ORDER:	$n = 10;	break;
			
		}
		$sql2 = "UPDATE Supply SET State = $n WHERE ID = $supplyID";
		
		$sql3 = "UPDATE ShipSch SET State = $n WHERE ID = $shipSchID";
		
		$rs = new RecordSets();
		try
		{
			$rs->beginTrans();
			
			$rs->ExecSQL($sql1);
			
			if($n > 0)
			{	$rs->ExecSQL($sql2);
				$rs->ExecSQL($sql3);
			}
			
			$rs->commit();
			
			return true;
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
}