<?php
include_once(MODELS_PATH . "/BaseModel.php");
include_once(MODELS_PATH . "/api2.0/Inquiry.php");

class Supply extends BaseModel
{
	//$act	 动作，GT 获取大于$id的数据  LT 获取小于$id的数据
	//$rows	 获取行数
	public static function apiGetList($act,$id,$rows = 20,$where = array())
	{
		if($act == "NEW")	$opr = ">";
		if($act == "OLD")	$opr = "<";
		
						/*
						SU.Deposit,
						SU.PaymentMethod,
						SU.NeedAgent,
						SU.LoadRatio,
						SU.PackageMethod,
						SU.UserID,
						SU.UserName,
						SU.FromPortID,
						SU.ToPortID,
						*/
		$sql =  "SELECT SU.ID,
						SU.UserID,
						SU.State,
						SU.FromPortName,
						SU.ToPortName,
						SU.LoadDateFrom,
						SU.LoadDateTo,
						SU.SeaOrRiver,
						SU.GoodsName,
						SU.Qty,
						SU.QtyDeviation,
						SU.Price,
						SU.TaxInclusive,
						SU.CreateDate,
						FR.Name AS FromRegion,
						TR.Name AS ToRegion
				   FROM ((( Supply AS SU LEFT JOIN PORT AS FP ON SU.FromPortID = FP.ID 
						  ) LEFT JOIN base_Region AS FR ON FP.RegionID = FR.ID
						  ) LEFT JOIN PORT AS TP ON SU.ToPortID = TP.ID 
						) LEFT JOIN base_Region AS TR ON TP.RegionID = TR.ID
				 WHERE SU.IsDeleted = 0 AND SU.ID $opr $id";
		
		$parm = array();
		
		//查询参数
		if(IsSet($where["FromPortID"]) && Is_Numeric($where["FromPortID"]))
		{	$sql .= " AND SU.FromPortID = :FromPortID";
			$parm["FromPortID"] = $where["FromPortID"];
		}
		if(IsSet($where["ToPortID"]) && Is_Numeric($where["ToPortID"]))
		{	$sql .= " AND SU.ToPortID = :ToPortID";
			$parm["ToPortID"] = $where["ToPortID"];
		}
		if(IsSet($where["DateFrom"]) && $where["DateFrom"] != "")
		{	$sql .= " AND SU.LoadDateFrom >= :DateFrom";
			$parm["DateFrom"] = $where["DateFrom"];
		}
		if(IsSet($where["DateTo"]) && $where["DateTo"] != "")
		{	$sql .= " AND SU.LoadDateFrom <= :DateTo";
			$parm["DateTo"] = $where["DateTo"];
		}
		if(IsSet($where["QtyFrom"]) && Is_Numeric($where["QtyFrom"]))
		{	$sql .= " AND SU.Qty >= :QtyFrom";
			$parm["QtyFrom"] = $where["QtyFrom"];
		}
		if(IsSet($where["QtyTo"]) && Is_Numeric($where["QtyTo"]))
		{	$sql .= " AND SU.Qty <= :QtyTo";
			$parm["QtyTo"] = $where["QtyTo"];
		}
		if(IsSet($where["UserID"]) && Is_Numeric($where["UserID"]))
		{	$sql .= " AND SU.UserID = :UserID";
			$parm["UserID"] = $where["UserID"];
		}
		$sql .= " ORDER BY SU.ID DESC LIMIT $rows";
		
		//查询SQL
		$rs = ExecSQL($sql,$parm);
		
		if($rs->RecordCount > 0)
		{
			//读取货源记录的抢单人
			$arr = $rs->asArray();
			$len = Count($arr);
			
			//注意：后期在这里可以一次性取出所有对应的运单，然后一条条找，以缩减数据库操作
			for($i=0;$i<$len;$i++)
			{	
				$sql = sprintf("SELECT ShipUserID,IF(CreateUserID <> SupplyUserID AND IsNull(SupplyUserReadDate),1,0) AS UnRead FROM Inquiry WHERE IsDeleted = 0 AND SupplyID = %d AND State < %d",$arr[$i]["ID"],INQ_STATE_DONE);
				$rs->Close();
				$rs->ExecSQL($sql);
				//$arr[$i]["InqUsers"] = $rs->ValueList("ShipUserID");
				
				$t = $rs->AsArray();
				$InqUsers = "";
				$Unread = 0;
				
				foreach($t as $v)
				{	$InqUsers .= $v["ShipUserID"] . ",";
					$Unread += $v["UnRead"];
				}
				
				$arr[$i]["InqUsers"] = ($InqUsers != "") ? substr($InqUsers,0,strlen($InqUsers)-1) : "";
				$arr[$i]["InqUnread"] = $Unread;
			}
			return $arr;
		}
		return null;
	}
	
	//获取详情
	public static function apiGetByID($id,$ignoreHit = 0)
	{
		if(!$ignoreHit)	ExecSQL("UPDATE Supply SET Hits = IFNULL(Hits,0) + 1 WHERE ID = $id");
		
		$sql =  "SELECT SU.*,
						PM.Text AS PackageMethodText,
						US.Avatar,
						US.MobilePhone,
						US.Star,
						CM.Name AS CompanyName 
				   FROM (( Supply AS SU INNER JOIN sys_Users AS US ON SU.UserID = US.UserID
						) LEFT JOIN Company AS CM ON US.CompanyID = CM.ID
						) LEFT JOIN base_PackageMethod AS PM ON SU.PackageMethod = PM.ID
				  WHERE	SU.ID = $id";
		$rs = ExecSQL($sql);
		return ($rs->RecordCount == 0) ? null : $rs->AsArray()[0];
	}
	
	//我的最近的货源
	public static function apiMyLast($userID)
	{	$rs = ExecSQL("SELECT * FROM Supply ORDER BY ID DESC LIMIT 1");
		return ($rs->RecordCount == 0) ? null : $rs->AsArray()[0];
	}
	
	//推荐货源
	public static function apiGetRecommend($RegionID)
	{
		$sql = "SELECT SU.*
				  FROM Supply AS SU INNER JOIN Port AS PO ON SU.FromPortID = PO.ID
				 WHERE SU.IsDeleted = 0 AND SU.State = :State AND PO.RegionID = :RegionID
			  ORDER BY ID DESC LIMIT " . APP_RECOMMEND_ROWS;
			  
		$parm = Array("RegionID" => $RegionID,"State"=>SUPSCH_STATE_NONE);//,"RECOMMEND_ROWS"=>RECOMMEND_ROWS);
		
		$rs = new RecordSets();
		
		$rs->ExecSQL($sql,$parm);
		
		if($rs->RecordCount > 0)
		{
			//读取货源记录的抢单人
			$arr = $rs->asArray();
			$len = Count($arr);
			for($i=0;$i<$len;$i++)
			{	$rs->Close();
				$rs->ExecSQL("SELECT DISTINCT ShipUserID FROM Inquiry WHERE IsDeleted = 0 AND SupplyID = " . $arr[$i]["ID"]);
				
				$arr[$i]["InqUsers"] = $rs->ValueList("ShipUserID");
			}
			return $arr;
		}
		return null;
	}
	
	//获取指定用户的匹配货源列表，数组参数有三个：UserID，PortID,Date，SeaOrRiver
	public static function apiGetMatch($parm)
	{
		$sql =  "SELECT SU.ID,
						SU.UserID,
						SU.UserName,
						SU.CreateDate,
						SU.State,
						SU.FromPortName,
						SU.ToPortName,
						SU.LoadDateFrom,
						SU.LoadDateTo,
						SU.SeaOrRiver,
						SU.GoodsName,
						SU.Qty,
						SU.QtyDeviation,
						SU.Price,
						SU.TaxInclusive,
						FR.Name AS FromRegion,
						TR.Name AS ToRegion
				   FROM ((( Supply AS SU LEFT JOIN PORT AS FP ON SU.FromPortID = FP.ID 
						  ) LEFT JOIN base_Region AS FR ON FP.RegionID = FR.ID
						  ) LEFT JOIN PORT AS TP ON SU.ToPortID = TP.ID 
						) LEFT JOIN base_Region AS TR ON TP.RegionID = TR.ID
				  WHERE SU.IsDeleted = 0 AND 
						SU.State = :STATE_NONE AND 
						SU.UserID = :UserID AND 
						SU.FromPortID = :PortID AND 
						SeaOrRiver = :SeaOrRiver AND
						:Date BETWEEN SU.LoadDateFrom AND SU.LoadDateTo";
		
		$parm["STATE_NONE"] = SUPSCH_STATE_NONE;
		
		$rs = ExecSQL($sql,$parm);
		
		return ($rs->RecordCount > 0) ? $rs->AsArray() : null;
	}
	
	//删除货源
	public static function apiDelete($userID,$userName,$ID)
	{
		$sql1 = "UPDATE Supply 
					SET IsDeleted		= 1,
						UpdateDate		= Now()
				  WHERE UserID = :UserID AND State <> :State AND ID IN ($ID)";
		$p1 = array("UserID"=>$userID,"State"=>SUPSCH_STATE_ORDER);
		$sql2 = "UPDATE Inquiry
					SET State 			= :StateDelete,
						UpdateUserID 	= :UserID,
						UpdateUserName	= :UserName,
						UpdateDate		= Now()
				  WHERE SupplyID IN ($ID) AND IsDeleted = 0 AND State < :StateOrder";
		$p2 = array("UserID"=>$userID,"UserName"=>$userName,"StateDelete"=>INQ_STATE_INVALID,"StateOrder"=>INQ_STATE_ORDER);
		try
		{
			ExecSQL($sql1,$p1);
			ExecSQL($sql2,$p2);
			return true;
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
	
	//保存货源
	public static function apiSave($parms)
	{
		if($parms["Price"] == "" || $parms["Price"] == "null")			$parms["Price"] = 0;
		if($parms["Deposit"] == "" || $parms["Deposit"] == "null")		$parms["Deposit"] = 0;
		if($parms["LoadRatio"] == "" || $parms["LoadRatio"] == "null")	$parms["LoadRatio"] = 0;
		
		if($parms["ID"] == 0)
		{	$p = $parms;
			unset($p["ID"]);
			if(isset($p["ShipSchID"]))	unset($p["ShipSchID"]);
			$p["STATE_NONE"] = SUPSCH_STATE_NONE;
			
			$sql = "INSERT INTO Supply
							(	UserID,
								UserName,
								SeaOrRiver,
								FromPortID,
								FromPortName,
								ToPortID,
								ToPortName,
								LoadDateFrom,
								LoadDateTo,
								Qty,
								QtyDeviation,
								GoodsName,
								PackageMethod,
								Price,
								TaxInclusive,
								PaymentMethod,
								Deposit,
								LoadRatio,
								NeedAgent,
								Memo,
								State,
								Hits,
								IsDeleted,
								CreateDate
							)
					VALUES	(	:UserID,
								:UserName,
								:SeaOrRiver,
								:FromPortID,
								:FromPortName,
								:ToPortID,
								:ToPortName,
								:LoadDateFrom,
								:LoadDateTo,
								:Qty,
								:QtyDeviation,
								:GoodsName,
								:PackageMethod,
								:Price,
								:TaxInclusive,
								:PaymentMethod,
								:Deposit,
								:LoadRatio,
								:NeedAgent,
								:Memo,
								:STATE_NONE,
								0,
								0,
								Now()
							)";
			$rs = new RecordSets();
			$rs->beginTrans();
			try
			{	$rs->ExecSQL($sql,$p);
				$rs->ExecSQL("SELECT Max(ID) AS ID FROM Supply");
				$maxID = $rs->ID;
				
				//如果定义了ShipSchID参数，说明是下单操作，需要添加询价记录
				if(IsSet($parms["ShipSchID"]) && Is_Numeric($parms["ShipSchID"]) && $parms["ShipSchID"] > 0)
				{	Inquiry::apiAddInquiry($maxID,$parms["ShipSchID"],$parms["UserID"],$parms["UserName"],$rs);
				}
				$rs->commit();
				return $maxID;
			}
			catch (exception $e)
			{	return $e->getMessage();
			}
		}
		else
		{	if(isset($parms["UserName"]))	unset($parms["UserName"]);
			$sql =  "UPDATE Supply
						SET SeaOrRiver		= :SeaOrRiver,
							FromPortID		= :FromPortID,
							FromPortName	= :FromPortName,
							ToPortID		= :ToPortID,
							ToPortName		= :ToPortName,
							LoadDateFrom	= :LoadDateFrom,
							LoadDateTo		= :LoadDateTo,
							Qty				= :Qty,
							QtyDeviation	= :QtyDeviation,
							GoodsName		= :GoodsName,
							PackageMethod	= :PackageMethod,
							Price			= :Price,
							TaxInclusive	= :TaxInclusive,
							PaymentMethod	= :PaymentMethod,
							Deposit			= :Deposit,
							LoadRatio		= :LoadRatio,
							NeedAgent		= :NeedAgent,
							Memo			= :Memo,
							UpdateDate		= Now()
					  WHERE ID = :ID AND UserID = :UserID";
			try
			{	ExecSQL($sql,$parms);
			}
			catch (exception $e)
			{	return $e->getMessage();
			}
			return $parms["ID"];
		}
	}
}
?>