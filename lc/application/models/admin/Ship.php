<?php
include_once(MODELS_PATH . "/BaseModel.php");

class Ship Extends BaseModel
{
	//船舶列表
	public static function allShip($page = 1,$rows = 20,$where = array())
	{
		$from = ($page-1)*$rows;
		$sql =  "SELECT SH.*,
						ST.ShipTypeName,
						US.Name,
						US.MobilePhone
				   FROM ( Ship AS SH LEFT JOIN ShipType AS ST ON SH.ShipTypeID = ST.ID
						) LEFT JOIN sys_Users AS US ON SH.UserID = US.UserID %s 
			   ORDER BY ID DESC LIMIT $from,$rows";
		
		if(IsSet($where["ShipName"]) && $where["ShipName"] != "")
		{	$text = "WHERE SH.IsDeleted = 0 AND SH.ShipName LIKE '%" . $where["ShipName"] . "%'";
			$sql = sprintf($sql,$text);
		}
		else	$sql = sprintf($sql,"WHERE SH.IsDeleted = 0");
		
		$rs = ExecSQL($sql);
        return $rs->RecordCount > 0 ? $rs->AsArray() : null;
	}
	
	//所有船的总数
	public static function getAllShipNumber()
	{
		$rs = ExecSQL("SELECT Count(ID) AS N FROM Ship");
		return $rs->N;
	}
	
	//查船舶各状态数量
	public static function getShipNumberByState()
	{
		$sql =  "SELECT CertifyState,Count(ID) AS Snumber FROM ship WHERE IsDeleted=0 GROUP BY CertifyState WITH ROLLUP";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}
	
	//===================================== 船期 ========================================
	//所有船期总数
	public static function getAllShipSchNumber()
	{
		$rs = ExecSQL("SELECT Count(ID) AS N FROM ShipSch");
		return $rs->N;
	}
	
	//保存添加或编辑数据
	public static function saveSch($parm)
	{
		if(IsSet($parm["ShipName"]))	unset($parm["ShipName"]);
		
		$sql = "INSERT INTO ShipSch
						(	UserID,
							UserName,
							ShipID,
							ShipType,
							ClearPortID,
							ClearPortName,
							ClearDate,
							State,
							Memo,
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
							:STATE_NONE,
							:Memo,
							0,
							Now()
						)";
		$parm["STATE_NONE"] = SUPSCH_STATE_NONE;
		ExecSQL($sql,$parm);
		return true;
	}

	//查看船名是否存在
	public static function getShipByName($input)
	{
		$input = "$input%";
		$sql =  "SELECT SH.ID,
						SH.ShipName,
						SH.UserID,
						SH.UserName,
						SH.Tonnage,
						SH.SeaOrRiver,
						ST.ShipTypeName
				   FROM Ship AS SH LEFT JOIN ShipType AS ST ON SH.ShipTypeID = ST.ID
				  WHERE SH.ShipName LIKE :input LIMIT 10";
		$rs = ExecSQL($sql,array("input"=>$input));
		return $rs->RecordCount > 0 ? $rs->AsArray() : null;
	}
	//=========================
	//船期表
	public static function getShipSch()
	{
		$sql =  "SELECT	*  FROM  Shipsch WHERE IsDeleted=0 ";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}
	//从船期表中读取一条数据 依据ID
	public static function GetoneShipSch($id)
    	{
    		$sql =  "SELECT	*  FROM  Ship WHERE ID=:ID ";
    		$rs = ExecSQL($sql,Array("ID"=>$id));
    		return $rs->AsArray();
    	}
	//船舶表Ship
	public static function getShip()
	{
		$sql =  "SELECT	*  FROM  Ship WHERE IsDeleted=0 ";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}
	//查新增船舶数量
	public static function getNewShip()
	{
		$tody=date("Y-m-d");
		//echo $tody;2017-01-20
		$sql = "SELECT	COUNT(*) as newShipNumber  FROM  Ship WHERE IsDeleted=0 and CreateDate=$tody";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}
	
	//从船舶表Ship中查找一条船（展示详情）
	public static function getoneShip($id)
	{
		$sql =  "SELECT	*  FROM  Ship WHERE ID=:ID ";
		$rs = ExecSQL($sql,Array("ID"=>$id));
		return $rs->AsArray();
	}
	//在船舶表Ship中增加一条记录
    public static function insertShip($ShipName,$UserName,$ShipTypeID,$SeaOrRiver,$Tonnage,$MadeDate,$Shiplong,$Shipwidth,$Deep,$Star,$RegistryPort,$CertifyState)
    {
        $sql="INSERT INTO Ship(ShipName,UserName,ShipTypeID,SeaOrRiver,Tonnage,MadeDate,Shiplong,Shipwidth,Deep,Star,RegistryPort,CertifyState,CreateDate,UpdateDate)VALUES(:ShipName,:UserName,:ShipTypeID,:SeaOrRiver,:Tonnage,:MadeDate,:Shiplong,:Shipwidth,:Deep,:Star,:RegistryPort,:CertifyState,now(),now())";
        $rs = ExecSQL($sql,Array("ShipName"=>$ShipName,"UserName"=>$UserName,"ShipTypeID"=>$ShipTypeID,"SeaOrRiver"=>$SeaOrRiver,"Tonnage"=>$Tonnage,"MadeDate"=>$MadeDate,"Shiplong"=>$Shiplong,"Shipwidth"=>$Shipwidth,"Deep"=>$Deep,"Star"=>$Star,"RegistryPort"=>$RegistryPort,"CertifyState"=>$CertifyState));
        return $rs->AsArray();
    }

	//在船舶表Ship中修改LogoImage
	public static function updateLogoImage($ID,$LogoImage)
	{
		$sql="UPDATE Ship SET LogoImage='$LogoImage',UpdateDate=now() WHERE ID='$ID'";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}
	//在船舶表Ship中修改NationalityCertificate
	public static function updateNationalityCertificate($ID,$NationalityCertificate)
	{
		$sql="UPDATE Ship SET NationalityCertificate='$NationalityCertificate',UpdateDate=now() WHERE ID='$ID'";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}
	//在船舶表Ship中修改InspectionCertificate
	public static function updateInspectionCertificate($ID,$InspectionCertificate)
	{
		$sql="UPDATE Ship SET InspectionCertificate='$InspectionCertificate',UpdateDate=now() WHERE ID='$ID'";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}
    //在船舶表Ship中删除记录
    public static function deleteShip($id)
    {
        $id=explode(",",$id);
        $count=count($id);
        for($i=0;$i<$count;$i++){
            ExecSQL("UPDATE Ship SET IsDeleted = 1 WHERE ID='$id[$i]'");
        };
        //$rs = ExecSQL($sql);
        //return $rs->AsArray();
    }

	public static function GetScheduleList($param = null,$pageIndex = 0,$pageRows = 0)
	{
		$sql =  "SELECT s.ShipId,
						s.`Name`,
						ss.AgentName,		s.LoadTon,
						s.Star,
						t.ShipTypeId,
						t.ShipTypeName, 
						ss.UserId,
						ss.ShipScheduleId,
						ss.EmptyPortId,
						ss.EmptyPortName,
						ss.ScheduledStartDateTime,
						ss.ScheduledEndDateTime,
						ss.StartDateTime,
						ss.EndDateTime,
						ss.PreTonnage,
						ss.EmptyDateTime 
				   FROM shipschedule ss
						INNER JOIN ship s ON s.ShipId = ss.ShipId and s.IsDelete = :isDelete 
						LEFT JOIN shiptype t ON t.ShipTypeId = s.ShipTypeId  
				  WHERE 1 = 1 AND ss.IsDelete = :isDelete ";
				
		//stringBuilder.Append(" AND ss.State = 0
		
		if(!IsSet($param["isDelete"]))	$param["isDelete"] = 0;
		
		if(IsSet($param["EmptyPortId"]) && $param["EmptyPortId"] != "")		$sql .= " AND ss.EmptyPortId = :EmptyPortId"; 
		if(IsSet($param["EmptyPortName"]) && $param["EmptyPortName"] != "")	$sql .= " AND ss.EmptyPortName like ?EmptyPortName";
		if(IsSet($param["Name"]) && $param["Name"] != "")					$sql .= " AND s.Name like ?Name";
		if(IsSet($param["LoadTon"]) && $param["LoadTon"] != "")				$sql .= " AND s.LoadTon = :LoadTon";
		if(IsSet($param["LoadTonMin"]) && $param["LoadTonMin"] != "")		$sql .= " AND ss.PreTonnage >= :LoadTonMin";
		if(IsSet($param["LoadTonMax"]) && $param["LoadTonMax"] != "")		$sql .= " AND ss.PreTonnage <= :LoadTonMax";
		if(IsSet($param["ScheduledStartDateTimeMin"]) && $param["ScheduledStartDateTimeMin"] != "")	
			$sql .= " AND ss.EmptyDateTime >= :ScheduledStartDateTimeMin";
		
		if(IsSet($param["ScheduledStartDateTimeMax"]) && $param["ScheduledStartDateTimeMax"] != "")
			$sql .= "AND ss.EmptyDateTime <= :ScheduledStartDateTimeMax";
		
		if(IsSet($param["OrderByWay"]))
		{
			switch($param["OrderByWay"])
			{
				case "LastUpdatedDateTime":	
					$sql .= " ORDER BY ss.DisplayOrder DESC,ss.LastUpdatedDateTime Desc";
					break;
				case "EmptyDateTime":
					$sql .= " ORDER BY ss.EmptyDateTime DESC";
					break;
				case "PreTonnage":
					$sql .= "ORDER BY ss.PreTonnage DESC";
					break;
				default:
					$sql .= "ORDER BY ss.LastUpdatedDateTime DESC";
					break;
			}
		}
		else
		{	$sql .= "ORDER BY ss.LastUpdatedDateTime DESC";
		}
		
		$rs = ExecSQL($sql,$param,$pageIndex,$pageRows);
		return $rs->AsArray();
	}

	//点击量
	public static function apiHit($id)
	{
		$sql = "UPDATE ShipSch SET Hits = IFNULL(Hits,0) + 1 WHERE ID = :id";
		$parm = array("id" => $id);

		ExecSQL($sql,$parm);
	}



	//获取船期列表
	public static function apiGetSchList($act,$id,$rows = 20)
	{
		if($act == "GT")	$opr = ">";
		if($act == "LT")	$opr = "<";
		$sql = "SELECT * FROM ShipSch WHERE IsDeleted = 0 AND ID $opr :id ORDER BY ID DESC LIMIT $rows";
		$parm = array("id" => $id);
		$rs = ExecSQL($sql,$parm);

		//读取船期记录的抢单人
		$arr = $rs->asArray();
		$len = Count($arr);
		for($i=0;$i<$len;$i++)
		{
			$rs->Close();
			$rs->ExecSQL("SELECT Count(ID) AS Cnt FROM Inquiry WHERE ShipSchID = " . $arr[$i]["ID"]);
			$arr[$i]["InqCnt"] = $rs->Cnt;
		}
		return $arr;
	}
	
	//获取指定用户的船期列表
	public static function apiGetSchListByUserID($act,$id,$rows,$uid)
	{
		if($act == "GT")	$opr = ">";
		if($act == "LT")	$opr = "<";
		$sql =   "SELECT ID,
						  ShipID,
						  ShipName,
						  SeaOrRiver,
						  ClearPortID,
						  ClearPortName,
						  ClearDate,
						  Tonnage,
						  Memo,
						  State,
						  CreateDate
					FROM ShipSch
				   WHERE IsDeleted = 0 AND UserID = :uid AND ID $opr :id
				ORDER BY ID DESC LIMIT $rows";
		$parm = array("id" => $id,"uid" => $uid);

		//为减小资源消耗，加快速度，此处只使用一个RecordSet对象
		$rs = new RecordSets();
		$rs->ExecSQL($sql,$parm);

		//查询该船期有几个询价
		$arr = $rs->asArray();
		$len = Count($arr);
		for($i=0;$i<$len;$i++)
		{
			$rs->Close();
			$rs->ExecSQL("SELECT Count(ID) AS Cnt FROM Inquiry WHERE ShipSchID = " . $arr[$i]["ID"]);
			$arr[$i]["InqCnt"] = $rs->Cnt;
		}
		$rs = null;
		return $arr;
	}
	
	//获取指定用户的匹配船期列表，数组参数有三个：UserID，PortID,Date
	public static function apiGetMachSch($parm)
	{
		$sql =	"SELECT ID,
						ShipID,
						ShipName,
						SeaOrRiver,
						ClearPortID,
						ClearPortName,
						ClearDate,
						Tonnage,
						Memo,
						State,
						CreateDate
				   FROM ShipSch 
				  WHERE IsDeleted = 0 AND State = 0 AND UserID = :UserID AND ClearPortID = :PortID AND SeaOrRiver = :SeaOrRiver AND 
						ClearDate BETWEEN date_sub(:Date,interval 1 day) AND date_add(:Date,interval 1 day)";
		$rs = ExecSQL($sql,$parm);
		
		return ($rs->RecordCount > 0) ? $rs->AsArray() : null;
	}

	//保存船期
	public static function apiSaveSch($parms)
	{
		if ($parms["ID"] == 0)
		{
			$sql = "INSERT INTO ShipSch
							(	UserID,
								UserName,
								ShipID,
								ShipName,
								SeaOrRiver,
								ClearPortID,
								ClearPortName,
								ClearDate,
								Tonnage,
								Memo,
								State,
								IsDeleted,
								CreateUserID,
								CreateUserName,
								CreateDate,
								UpdateUserID,
								UpdateUserName,
								UpdateDate
							)
					VALUES	(	:UserID,
								:UserName,
								:ShipID,
								:ShipName,
								:SeaOrRiver,
								:ClearPortID,
								:ClearPortName,
								:ClearDate,
								:Tonnage,
								:Memo,
								0,
								0,
								:UserID,
								:UserName,
								Now(),
								:UserID,
								:UserName,
								Now()
							)";
			$rs = new RecordSets();
			$rs->beginTrans();
			try
			{
				$rs->ExecSQL($sql,$parms);
				$rs->Close();
				$rs->ExecSQL("SELECT Max(ID) AS ID FROM ShipSch");
				$maxID = $rs->ID;
				
				//如果定义了SupplyID参数，说明是抢单操作，需要添加询价记录
				if(IsSet($parms["SupplyID"]) && Is_Numeric($parms["SupplyID"]))
				{	Ship::addInquery($parms["SupplyID"],$maxID,10,$rs);
				}
				
			}
			catch (exception $e)
			{	return $e->getMessage();
			}
			$rs->commit();
			return $maxID;
		}
		else
		{
			$sql = "UPDATE ShipSch
						SET ShipID			= :ShipID,
							ShipName		= :ShipName,
							SeaOrRiver		= :SeaOrRiver,
							ClearPortID		= :ClearPortID,
							ClearPortName	= :ClearPortName,
							ClearDate		= :ClearDate,
							Tonnage			= :Tonnage,
							Memo			= :Memo,
							UpdateUserID	= :UserID,
							UpdateUserName	= :UserName,
							UpdateDate		= Now()
					  WHERE ID = :ID AND UserID = :UserID";

			try
			{
				ExecSQL($sql, $parms);
			} catch (exception $e)
			{
				return $e->getMessage();
			}
			return $parms["ID"];
		}
	}

	//删除船期
	public static function apiDelSch($ids,$UserID)
	{	
		//删除船期需要一并删除所有相关的报价单
		try
		{
			ExecSQL("UPDATE ShipSch SET IsDeleted = 1 WHERE UserID = $UserID AND (State = 0 OR State = 10) AND ID IN ($ids)");
			ExecSQL("UPDATE Inquiry SET IsDeleted = 1 WHERE ShipSchID IN ($ids)");
			return true;
		}
		catch(Exception $e)
		{	return $e->getMessage();
		}
	}
	
	//删除船舶，且一并删除关联的船期
	public static function apiDelShip($ids,$UserID)
	{
		$rs = new RecordSets();
		try
		{
			$rs->beginTrans();
			$rs->ExecSQL("UPDATE Ship SET IsDeleted = 1 WHERE UserID = $UserID AND ID IN ($ids)");
			$rs->ExecSQL("UPDATE ShipSch SET IsDeleted = 1 WHERE UserID = $UserID AND ShipID IN ($ids)");
			$rs->ExecSQL("UPDATE Inquiry SET IsDeleted = 1 WHERE ShipID IN ($ids)");
			$rs->commit();
			return true;
		}
		catch(Exception $e)
		{	return $e->getMessage();
		}
	}

	public static function apiGrabSupply($parms)
	{
		/*
			船期状态 State
			0  公开发布
			10 私下发布
			20 生成订单
			30 失效
		*/

		$sql = "INSERT INTO ShipSch
						(	UserID,
							UserName,
							ShipID,
							ShipName,
							ClearPortID,
							ClearPortName,
							ClearDate,
							Tonnage,
							State,
							Memo,
							SortNo,
							IsDeleted,
							CreateUserID,
							CreateUserName,
							CreateDate,
							UpdateUserID,
							UpdateUserName,
							UpdateDate
						)
				VALUES	(	:UserID,
							:UserName,
							:ShipID,
							:ShipName,
							:ClearPortID,
							:ClearPortName,
							:ClearDate,
							:Tonnage,
							:State,
							'',
							0,
							0,
							:UserID,
							:UserName,
							Now(),
							:UserID,
							:UserName,
							Now()
						)";

		$rs = new RecordSets();
		$rs->beginTrans();

		try
		{
			$rs->ExecSQL("SELECT State FROM Supply WHERE ID = :ID",Array("ID"=>$parms["SupplyID"]));
			$state = $rs->State;
			$rs->Close();

			//还未生成订单，可以给他报价
			if($state < 20)
			{
				$rs->ExecSQL($sql, $parms);
				$ret = true;
			}
			else
			{
				$ret = -20;
			}

			$rs->commit();
		}
		catch(Exception $e)
		{
			$ret = $e->getMessage();
		}

		return $ret;
	}
	
	public static function apiGetShipType()
	{
		$rs = ExecSQL("SELECT ID,ShipTypeName FROM ShipType");

		if($rs->recordCount == 0)	return null;
		return $rs->AsArray();
	}
	
	/*
	 *	添加查询单
	 *
	 *	$SupplyID	货源编号
	 *	$SchID		船期编号
	 *	$Type		类别
	 *	  10	船东的询价单，UserID取自船期表
	 *	  20	货主的询价单，UserID取自货源表
	 *
	 *	$rs			数据库连接，其它函数调用时可复用
	 *
	 */
	public static function addInquery($SupplyID,$SchID,$Type,$rs = null)
	{
		if($rs == null)	$rs = new RecordSets();
		
		$rs->ExecSQL("SELECT * FROM Supply WHERE ID = $SupplyID");
		if($rs->RecordCount == 0) return false;
		$supply = $rs->AsArray()[0];
		$rs->Close();
		$rs->ExecSQL("SELECT * FROM ShipSch WHERE ID = $SchID");
		if($rs->RecordCount == 0) return false;
		$sch = $rs->AsArray()[0];
		
		//Type = 10  船东发起询价
		$sql = "INSERT INTO Inquiry
						(	`Type`,
							SupplyUserID,
							SupplyUserName,
							SupplyID,
							GoodsName,
							Price,
							PriceCurrency,
							QtyTon,
							QtyPiece,
							ShipUserID,
							ShipUserName,
							ShipSchID,
							ShipID,
							ShipName,
							Tonnage,
							`State`,
							IsDeleted,
							CreateUserID,
							CreateUserName,
							CreateDate,
							UpdateUserID,
							UpdateUserName,
							UpdateDate
						)
				VALUES	(	:Type,
							:SupplyUserID,
							:SupplyUserName,
							:SupplyID,
							:GoodsName,
							:Price,
							:PriceCurrency,
							:QtyTon,
							:QtyPiece,
							:ShipUserID,
							:ShipUserName,
							:ShipSchID,
							:ShipID,
							:ShipName,
							:Tonnage,
							0,
							0,
							:UserID,
							:UserName,
							Now(),
							:UserID,
							:UserName,
							Now()
						)";
		$p = array(
			"Type"			=> $Type,
			"SupplyUserID"	=> $supply["UserID"],
			"SupplyUserName"=> $supply["UserName"],
			"SupplyID"		=> $SupplyID,
			"GoodsName"		=> $supply["GoodsName"],
			"Price"			=> $supply["Price"],
			"PriceCurrency"	=> $supply["PriceCurrency"],
			"QtyTon"		=> $supply["QtyTon"],
			"QtyPiece"		=> $supply["QtyPiece"],
			"ShipUserID"	=> $sch["UserID"],
			"ShipUserName"	=> $sch["UserName"],
			"ShipSchID"		=> $SchID,
			"ShipID"		=> $sch["ShipID"],
			"ShipName"		=> $sch["ShipName"],
			"Tonnage"		=> $sch["Tonnage"],
			"UserID"		=> $Type == 10 ? $sch["UserID"] : $supply["UserID"],
			"UserName"		=> $Type == 10 ? $sch["UserName"] : $supply["UserName"]
		);
		
		try
		{	$rs->ExecSQL($sql,$p);
			return true;
		}
		catch(Exception $e)
		{
			throw($e);
		}
	}
	
	/*
	public static function apiSaveShip($parm)
	{
		if($parm["ID"] == 0)
			$sql = "INSERT INTO Ship
							(	UserID,
								UserName,
								ShipName,
								ShipTypeID,
								SeaOrRiver,
								Tonnage,
								MMSI,
								MadeDate,
								Long,
								Width,
								Deep,
								Capacity,
								FullDraught,
								EmptyDraught,
								HatchNum,
								RegistryPort,
								
							)
					VALUES	(	:UserID,
								:UserName,
								:ShipName,
								:ShipTypeID,
								:SeaOrRiver,
								:Tonnage,
								:MMSI,
								:MadeDate,
								:Long,
								:Width,
								:Deep,
								:Capacity,
								:FullDraught,
								:EmptyDraught,
								:HatchNum,
								:RegistryPort,
								0,
								0,
								0,
								0,
								:UserID,
								:UserName,
								Now(),
								:UserID,
								:UserName,
								Now()
							)";
		else
			$sql =  "UPDATE Ship
						SET	ShipName		= :ShipName,
							ShipTypeID		= :ShipTypeID,
							SeaOrRiver		= :SeaOrRiver,
							Tonnage			= :Tonnage,
							MMSI			= :MMSI,
							MadeDate		= :MadeDate,
							Long			= :Long,
							Width			= :Width,
							Deep			= :Deep,
							Capacity		= :Capacity,
							FullDraught		= :FullDraught,
							EmptyDraught	= :EmptyDraught,
							HatchNum		= :HatchNum,
							RegistryPort	= :RegistryPort,
							UpdateUserID	= :UserID,
							UpdateUserName	= :UserName,
							UpdateDate		= Now()
					  WHERE	ID = :ID";
		try
		{	ExecSQL($sql,$parm);
			return true;
		}
		catch(Exception $e)
		{	return $e->Message;
		}
	}
	*/
}
