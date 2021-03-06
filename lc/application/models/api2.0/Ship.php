<?php
include_once(MODELS_PATH . "/BaseModel.php");
include_once(MODELS_PATH . "/api2.0/Inquiry.php");

class Ship Extends BaseModel
{	//点击量
	public static function apiHit($id)
	{	$sql = "UPDATE ShipSch SET Hits = IFNULL(Hits,0) + 1 WHERE ID = :id";
		$parm = array("id" => $id);
		ExecSQL($sql,$parm);
	}
	//获取船期列表
	public static function apiGetSchList($act,$id,$rows=20,$where = array())
	{
		if($act == "NEW")	$opr = ">";
		if($act == "OLD")	$opr = "<";
		$sql =  "SELECT SS.ID,
						SS.ShipType,
						SS.ClearDate,
						SS.ClearPortID,
						SS.ClearPortName,
						SS.Memo,
						SS.State,
						SS.CreateDate,
						SH.ShipName,
						SH.Tonnage,
						SH.SeaOrRiver,
						SH.Long,
						SH.Width,
						SH.Star,
						SH.LogoImage,
						SH.CertifyState
				   FROM ShipSch AS SS INNER JOIN Ship AS SH ON SS.ShipID = SH.ID
				  WHERE SS.IsDeleted = 0 AND 
						SH.IsDeleted = 0 AND
						SS.ID $opr $id ";
		$parm = array();
		
		//查询参数
		if(IsSet($where["PortID"]) && Is_Numeric($where["PortID"]))
		{	$sql .= " AND SS.ClearPortID = :PortID";
			$parm["PortID"] = $where["PortID"];
		}
		if(IsSet($where["FromDate"]) && $where["FromDate"] != "")
		{	$sql .= " AND SS.ClearDate >= :FromDate";
			$parm["FromDate"] = $where["FromDate"];
		}
		if(IsSet($where["ToDate"]) && $where["ToDate"] != "")
		{	$sql .= " AND SS.ClearDate <= :ToDate";
			$parm["ToDate"] = $where["ToDate"];
		}
		if(IsSet($where["FromQty"]) && Is_Numeric($where["FromQty"]))
		{	$sql .= " AND SH.Tonnage >= :FromQty";
			$parm["FromQty"] = $where["FromQty"];
		}
		if(IsSet($where["ToQty"]) && Is_Numeric($where["ToQty"]))
		{	$sql .= " AND SH.Tonnage <= :ToQty";
			$parm["ToQty"] = $where["ToQty"];
		}
		if(IsSet($where["UserID"]) && Is_Numeric($where["UserID"]))
		{	$sql .= " AND SS.UserID = :UserID";
			$parm["UserID"] = $where["UserID"];
		}
		$sql .= " ORDER BY SS.ID DESC LIMIT $rows";
		//执行SQL
		$rs = ExecSQL($sql,$parm);
		
		if($rs->RecordCount > 0)
		{
			//读取船期记录的下单人
			$arr = $rs->asArray();
			$len = Count($arr);
			
			//注意：后期在这里可以一次性取出所有对应的运单，然后一条条找，以缩减数据库操作
			for($i=0;$i<$len;$i++)
			{
				$sql = sprintf("SELECT SupplyUserID,IF(CreateUserID <> ShipUserID AND IsNull(ShipUserReadDate),1,0) AS UnRead FROM Inquiry WHERE IsDeleted = 0 AND ShipSchID = %d AND State <> %d",$arr[$i]["ID"],INQ_STATE_INVALID);
				$rs->ExecSQL($sql);
				
				$t = $rs->AsArray();
				$InqUsers = "";
				$Unread = 0;
				
				foreach($t as $v)
				{	$InqUsers .= $v["SupplyUserID"] . ",";
					$Unread += $v["UnRead"];
				}
				
				$arr[$i]["InqUsers"] = ($InqUsers != "") ? substr($InqUsers,0,strlen($InqUsers)-1) : "";
				$arr[$i]["InqUnread"] = $Unread;
			}
			return $arr;
		}
		return null;
	}
	
	//船期详情
	public static function apiGetSchByID($id,$ignoreHit = 1)
	{
		$sql =  "SELECT SS.*,
						SH.*,
						SS.ID,
						SS.State,
						US.Avatar,
						US.Star,
						US.MobilePhone,
						SH.CertifyState AS ShipCertifyState
				   FROM ( ShipSch AS SS
						  INNER JOIN Ship AS SH ON SS.ShipID = SH.ID
						) INNER JOIN SYS_Users AS US ON SS.UserID = US.UserID
				  WHERE SS.IsDeleted = 0 AND 
						SH.IsDeleted = 0 AND
						US.IsDeleted = 0 AND
						SS.ID = $id";
		//执行SQL
		$rs = ExecSQL($sql);
		if($rs->RecordCount == 0)	return null;
		
		$ret = $rs->AsArray()[0];
		
		if(!$ignoreHit)	self::apiHit($id);
		
		$sql = sprintf("SELECT SupplyUserID,IF(CreateUserID <> ShipUserID AND IsNull(ShipUserReadDate),1,0) AS UnRead FROM Inquiry WHERE IsDeleted = 0 AND ShipSchID = %d AND State <> %d",$id,INQ_STATE_INVALID);
		$rs->Close();
		$rs->ExecSQL($sql);
		
		$t = $rs->AsArray();
		$InqUsers = "";
		$Unread = 0;
		
		foreach($t as $v)
		{	$InqUsers .= $v["SupplyUserID"] . ",";
			$Unread += $v["UnRead"];
		}
		
		$ret["InqUsers"] = ($InqUsers != "") ? substr($InqUsers,0,strlen($InqUsers)-1) : "";
		$ret["InqUnread"] = $Unread;
		
		return $ret;
	}
		
	//获取推荐船期
	public static function apiGetRecommend($RegionID)
	{
		$sql =  "SELECT SS.*,
						SH.ShipName,
						SH.Tonnage,
						SH.SeaOrRiver,
						SH.Long,
						SH.Width,
						SH.Star,
						SH.LogoImage,
						SH.CertifyState
				   FROM ( ShipSch AS SS INNER JOIN Ship AS SH ON SS.ShipID = SH.ID
						) INNER JOIN Port AS PO ON SS.ClearPortID = PO.ID
				  WHERE SS.IsDeleted = 0 AND 
						SH.IsDeleted = 0 AND
						SS.State = :State AND
						PO.RegionID = :RegionID 
			  ORDER BY ID DESC LIMIT " . APP_RECOMMEND_ROWS;
			
		$parm = Array("RegionID" => $RegionID,"State"=>SUPSCH_STATE_NONE);
		
		$rs = new RecordSets();
		$rs->ExecSQL($sql,$parm);
		
		if($rs->RecordCount > 0)
		{
			//读取货源记录的抢单人
			$arr = $rs->asArray();
			$len = Count($arr);
			for($i=0;$i<$len;$i++)
			{	$rs->Close();
				$rs->ExecSQL("SELECT DISTINCT SupplyUserID FROM Inquiry WHERE IsDeleted = 0 AND ShipSchID = " . $arr[$i]["ID"]);
				
				$arr[$i]["InqUsers"] = $rs->ValueList("SupplyUserID");
			}
			return $arr;
		}
		return null;
	}
	
	//获取指定用户的匹配船期列表，数组参数有三个：UserID，PortID,Date,Qty,SeaOrRiver
	public static function apiGetMatchSch($parm)
	{
		$sql =	"SELECT SS.*,
						SH.ShipName,
						SH.SeaOrRiver,
						SH.Tonnage,
						SH.Long,
						SH.Width,
						SH.LogoImage,
						SH.CertifyState
				   FROM ShipSch AS SS INNER JOIN Ship AS SH ON SS.ShipID = SH.ID
				  WHERE SH.IsDeleted = 0 AND 
						SH.Tonnage >= :Qty AND
						SH.SeaOrRiver = :SeaOrRiver AND
						SS.IsDeleted = 0 AND 
						SS.State = :State AND 
						SS.UserID = :UserID AND 
						SS.ClearPortID = :PortID AND 
						SS.ClearDate BETWEEN :DateFrom AND :DateTo";
		
		$parm["State"] = SUPSCH_STATE_NONE;
		
		$rs = ExecSQL($sql,$parm);
		
		return ($rs->RecordCount > 0) ? $rs->AsArray() : null;
	}
	//保存船期
	public static function apiSaveSch($parms)
	{	if($parms["ID"] == 0)
		{	$p = $parms;	unset($p["ID"]);
			if(isset($p["SupplyID"]))	unset($p["SupplyID"]);
			
			$sql = "INSERT INTO ShipSch
							(	UserID,
								UserName,
								ShipID,
								ShipType,
								ClearPortID,
								ClearPortName,
								ClearDate,
								Memo,
								State,
								IsDeleted,
								CreateDate
							)
					VALUES	(	:UserID,
								:UserName,
								:ShipID,
								:ShipType,
								:ClearPortID,
								:ClearPortName,
								:ClearDate,
								:Memo,
								0,
								0,
								Now()
							)";
			$rs = new RecordSets();
			$rs->beginTrans();
			try
			{	$rs->ExecSQL($sql,$p);
				$rs->Close();
				$rs->ExecSQL("SELECT Max(ID) AS ID FROM ShipSch");
				$maxID = $rs->ID;
				//如果定义了SupplyID参数，说明是抢单操作，需要添加询价记录
				if(IsSet($parms["SupplyID"]) && Is_Numeric($parms["SupplyID"]) && $parms["SupplyID"] > 0)
				{	Inquiry::apiAddInquiry($parms["SupplyID"],$maxID,$parms["UserID"],$parms["UserName"],$rs);
				}
				$rs->commit();	
			} catch (exception $e) {	return $e->getMessage(); }
			
			return $maxID;
		}
		else
		{	$sql = "UPDATE ShipSch
						SET ShipID			= :ShipID,
							Shiptype		= :ShipType,
							ClearPortID		= :ClearPortID,
							ClearPortName	= :ClearPortName,
							ClearDate		= :ClearDate,
							Memo			= :Memo,
							UpdateDate		= Now()
					  WHERE ID = :ID AND UserID = :UserID";
			if(isset($parms["UserName"]))	unset($parms["UserName"]);
			try
			{	ExecSQL($sql, $parms);
			}
			catch(exception $e)
			{	return $e->getMessage();
			}
			return $parms["ID"];
		}
	}

	//删除船期
	public static function apiDelSch($userID,$userName,$ID)
	{	
		$sql1 = "UPDATE ShipSch SET IsDeleted = 1,UpdateDate = Now() WHERE UserID = :UserID AND State <> :State AND ID = $ID";
		$p1 = array("UserID"=>$userID,"State"=>SUPSCH_STATE_ORDER);
		
		$sql2 = "UPDATE Inquiry
					SET State 			= :StateInvalid,
						UpdateUserID 	= :UserID,
						UpdateUserName	= :UserName,
						UpdateDate		= Now()
				  WHERE ShipSchID = $ID AND IsDeleted = 0 AND (State < :StateOrder AND State <> :StateInvalid)";
		$p2 = array("UserID"=>$userID,"UserName"=>$userName,"StateInvalid"=>INQ_STATE_INVALID,"StateOrder"=>INQ_STATE_ORDER);
		
		$rs = new RecordSets();
		$rs->beginTrans();
		try
		{
			$rs->ExecSQL($sql1,$p1);
			$rs->ExecSQL($sql2,$p2);
			$rs->commit();
			return true;
		} catch(Exception $e) {	return $e->getMessage(); }
	}
	
	//删除船舶，且一并删除关联的船期
	public static function apiDelShip($ids,$UserID)
	{
		$rs = new RecordSets();
		try
		{	$rs->beginTrans();
			$rs->ExecSQL("UPDATE Ship SET IsDeleted = 1 WHERE UserID = $UserID AND ID IN ($ids)");
			$rs->ExecSQL("UPDATE ShipSch SET IsDeleted = 1 WHERE UserID = $UserID AND ShipID IN ($ids)");
			$rs->ExecSQL("UPDATE Inquiry SET IsDeleted = 1 WHERE ShipID IN ($ids)");
			$rs->commit();
			return true;
		} catch(Exception $e) {  return $e->getMessage(); }
	}

	//船舶类型
	public static function apiGetShipType()
	{
		$rs = ExecSQL("SELECT ID,ShipTypeName FROM ShipType");

		if($rs->recordCount == 0)	return null;
		return $rs->AsArray();
	}
	
	//提交证件图片
	public static function  apiPostImage($parms)
	{
		include_once(APP_PATH . "/Utils/Utils.php");
		
		$sql = "UPDATE Ship SET UpdateUserID = :UserID,UpdateDate = Now(),CertifyState = :State";
		$ret = array();
		
		if(strlen($parms["LogoImage"]) > 500)
		{
			$fname = sprintf("%s/%d_ShipLogo.jpg",SITE_UPLOAD_DIR,$parms["ID"]);
			Base64ToFile($parms["LogoImage"],$fname);
			
			$sql .= ",LogoImage = '$fname'";
			$ret["LogoImage"] = $fname;
		}
		if(strlen($parms["InspectionCertificate"]) > 500)
		{
			$fname = sprintf("%s/%d_ShipInspection.jpg",SITE_UPLOAD_DIR,$parms["ID"]);
			Base64ToFile($parms["InspectionCertificate"],$fname);
			
			$sql .= ",InspectionCertificate = '$fname'";
			$ret["InspectionCertificate"] = $fname;
		}
		if(strlen($parms["NationalityCertificate"]) > 500)
		{
			$fname = sprintf("%s/%d_ShipNationality.jpg",SITE_UPLOAD_DIR,$parms["ID"]);
			Base64ToFile($parms["NationalityCertificate"],$fname);
			
			$sql .= ",NationalityCertificate = '$fname'";
			$ret["NationalityCertificate"] = $fname;
		}
		$sql .= " WHERE UserID = :UserID AND ID = :ID";
		
		try
		{	if(Count($ret) > 0)	
			{	ExecSQL($sql,array("ID"=>$parms["ID"],"UserID"=>$parms["UserID"],"State"=>CERTIFY_STATE_WAIT));
			}
			return $ret;
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
		
	}
	
	//更新船舶位置 
	public static function apiSetLocation($id,$addr,$lat,$lan,$userID)
	{
		$sql =  "UPDATE Ship SET Location = :Location,Latitude = :Lat,Longtitude = :Lan,LocationUpdateTime = Now() WHERE ID = :ID AND UserID = :UserID";
		try
		{
			ExecSQL($sql,array("Location"=>$addr,"Lat"=>$lat,"Lan"=>$lan,"ID"=>$id,"UserID"=>$userID));
			return true;
		}
		catch(exception $e)
		{
			return $e->getMessage();
		}	
	}
}
