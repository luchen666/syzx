<?php
/*
 * UserModel
 *
 * 编写：谢忠杰
 *
 */
include_once(MODELS_PATH . "/BaseModel.php");

class Supply extends BaseModel
{
	//$act	 动作，GT 获取大于$id的数据  LT 获取小于$id的数据
	//$rows	 获取行数
	public static function apiGetList($act,$id,$rows = 20,$where = array())
	{
		// AND State = 1 AND TO_DAYS(StartDateMin) >= TO_DAYS(Now())
		if($act == "GT")	$opr = ">";
		if($act == "LT")	$opr = "<";
		$sql = "SELECT P.*,
						IFNULL(OG.VIP,0) AS VIP
				  FROM Supply AS P
						INNER JOIN sys_Users AS US ON P.UserID = US.UserID
						INNER JOIN Company AS OG ON US.CompanyID = OG.ID
				 WHERE P.IsDeleted = 0 AND P.ID $opr :id";
		
		$parm = array("id" => $id);
		
		//查询参数
		if(IsSet($where["FromPortID"]))
		{	$sql .= " AND P.FromPortID = :FromPortID";
			$parm["FromPortID"] = $where["FromPortID"];
		}
		if(IsSet($where["ToPortID"]))
		{	$sql .= " AND P.ToPortID = :ToPortID";
			$parm["ToPortID"] = $where["ToPortID"];
		}
		if(IsSet($where["FromDate"]) && IsSet($where["ToDate"]))
		{
			$sql .= " AND P.FromDate BETWEEN :FromDate AND :ToDate";
			$parm["FromDate"] = $where["FromDate"];
			$parm["ToDate"] = $where["ToDate"];
		}
		else if(IsSet($where["FromDate"]))
		{
			$sql .= " AND P.FromDate >= :FromDate";
			$parm["FromDate"] = $where["FromDate"];
		}
		else if(IsSet($where["ToDate"]))
		{
			$sql .= " AND P.FromDate <= :ToDate";
			$parm["ToDate"] = $where["ToDate"];
		}
		if(IsSet($where["FromQty"]))
		{
			$sql .= " AND P.QtyTon >= :FromQty";
			$parm["FromQty"] = $where["FromQty"];
		}
		if(IsSet($where["ToQty"]))
		{
			$sql .= " AND P.QtyTon <= :ToQty";
			$parm["ToQty"] = $where["ToQty"];
		}
		
		$sql .= " ORDER BY P.ID DESC LIMIT $rows";
		
		//查询SQL
		$rs = ExecSQL($sql,$parm);

		//读取货源记录的抢单人
		$arr = $rs->asArray();
		$len = Count($arr);
		for($i=0;$i<$len;$i++)
		{
			$supplyID = $arr[$i]["ID"];

			$rs = null;
			$rs = ExecSQL("SELECT ShipUserID FROM Inquiry WHERE IsDeleted = 0 AND SupplyID = $supplyID");

			$arr[$i]["InqUsers"] = $rs->ValueList("ShipUserID");
		}
		return $arr;
	}
	
	//增加点击量
	public static function apiHit($id)
	{
		$sql = "UPDATE Supply SET Hits = IFNULL(Hits,0) + 1 WHERE ID = :id";
		$parm = array("id" => $id);

		ExecSQL($sql,$parm);
	}
	
	//推荐货源
	public static function apiGetRecommend($RegionID)
	{
		//State 0 发布中  10 生成订单  20 失效
		$sql = "SELECT SU.*
				  FROM Supply AS SU INNER JOIN Port AS PO ON SU.FromPortID = PO.ID
				 WHERE SU.IsDeleted = 0 AND SU.State = 0 AND PO.RegionID = :RegionID
			  ORDER BY ID DESC LIMIT 5";
		$rs = new RecordSets();
		$rs->ExecSQL($sql,Array("RegionID" => $RegionID));
		
		//读取货源记录的抢单人
		$arr = $rs->asArray();
		$len = Count($arr);
		for($i=0;$i<$len;$i++)
		{
			$supplyID = $arr[$i]["ID"];
			$rs->Close();
			$rs->ExecSQL("SELECT ShipUserID FROM Inquiry WHERE SupplyID = $supplyID");
			$arr[$i]["InqUsers"] = $rs->ValueList("ShipUserID");
		}
		return $arr;
	}
	//货盘 从Supply表中获取数据
	public static function getSupply($page = 1,$rows = 10)
	{
		$from = ($page - 1) * $rows;
		$sql =  "SELECT	*  FROM  Supply WHERE isDeleted=0 LIMIT $from,$rows";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}
	//总货源数量
	public static function getAllSupplyCount()
	{
		$sql =  "SELECT	count(ID) AS TotalSupplyNumber  FROM  Supply WHERE isDeleted=0 ";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}
	//新增货源
	public static function getnewSupplyCount()
	{
		$sql = "SELECT	COUNT(ID) as NewSupplyNumber  FROM  Supply WHERE IsDeleted=0 and CreateDate > DATE_FORMAT(NOW(),'%Y-%m-%d 00:00:00')";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}
	//可抢货源 发货日期大于等于今天且State=0状态发布中
	public static function getSupplyCount()
	{
		$sql =  "SELECT	count(ID) AS GetSupplyNumber  FROM  Supply WHERE isDeleted=0 and State=0 and CreateDate > DATE_FORMAT(NOW(),'%Y-%m-%d 00:00:00')";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}

	//从Supply表中读取一条数据 依据ID
    public static function GetoneSupply($id)
    {
        $sql =  "SELECT	*  FROM  Supply WHERE ID=:ID ";
        $rs = ExecSQL($sql,Array("ID"=>$id));
        return $rs->AsArray();
    }
    //在表Supply中修改一条记录
    public static function updateSupply($ID,$UserName,$GoodsName,$PackageMethod,$Qty,$QtyDeviation,$SeaOrRiver,$Price,$Deposit,$FromPortName,$LoadDateFrom,$ToPortName,$LoadDateTo,$PaymentMethod,$NeedAgent,$State,$Memo)
    {
        $sql="UPDATE Supply SET UserName='$UserName',GoodsName='$GoodsName',PackageMethod='$PackageMethod',Qty='$Qty',QtyDeviation='$QtyDeviation',SeaOrRiver='$SeaOrRiver',Price='$Price',Deposit='$Deposit',FromPortName='$FromPortName',LoadDateFrom='$LoadDateFrom',ToPortName='$ToPortName',LoadDateTo='$LoadDateTo',PaymentMethod='$PaymentMethod',NeedAgent='$NeedAgent',State='$State',Memo='$Memo',UpdateDate=now() WHERE ID='$ID'";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }
    //在表Supply中删除记录
    public static function deleteSupply($id)
    {
        $id=explode(",",$id);
        $count=count($id);
        for($i=0;$i<$count;$i++){
            ExecSQL("UPDATE Supply SET IsDeleted = 1 WHERE ID='$id[$i]'");
        };
    }
	//======================================================================================
	//保存添加或编辑数据
	public static function saves($parm,$tbname = '', $rs = NULL)
	{
		if($parm["ID"] == 0)
		{
			$sql = "INSERT INTO supply
							(	UserName,
								UserID,
								GoodsName,
                				PackageMethod,
                				Qty,
                				QtyDeviation,
                				SeaOrRiver,
                				Price,
                				FromPortName,
                				ToPortName,
                				LoadDateFrom,
                				LoadDateTo,
                				PaymentMethod,
                				NeedAgent,
                				State
							)
					VALUES	(	:UserName,
								:UserID,
								:GoodsName,
								:PackageMethod,
								:Qty,
								:QtyDeviation,
								:SeaOrRiver,
								:Price,
								:FromPortName,
								:ToPortName,
								:LoadDateFrom,
								:LoadDateTo,
								:PaymentMethod,
								:NeedAgent,
								:State
							)";
			unset($parm["ID"]);
		}
		else
		{	$sql =  "UPDATE	supply
                    SET UserName = :UserName,GoodsName= :GoodsName,PackageMethod= :PackageMethod,Qty= :Qty,QtyDeviation= :QtyDeviation,SeaOrRiver= :SeaOrRiver,Price= :Price,
                    FromPortName= :FromPortName,ToPortName= :ToPortName,LoadDateFrom= :LoadDateFrom,LoadDateTo= :LoadDateTo,PaymentMethod= :PaymentMethod,NeedAgent= :NeedAgent,State=:State
                    WHERE ID = :ID";
			unset($parm["UserID"]);
		}
		ExecSQL($sql,$parm);
		return true;
	}
	public static function getCount()
	{
		$rs = ExecSQL("SELECT Count(ID) AS Cnt FROM supply");
		return $rs->Cnt;
	}

	//查找用户名和手机号
	public static function getUserInfo($input)
	{
		$input = "$input%";
		$rs = ExecSQL("SELECT Name,MobilePhone,UserID FROM sys_users WHERE Name LIKE :input lIMIT 10",array("input"=>$input));
		return $rs->RecordCount > 0 ? $rs->AsArray() : null;
	}

	//查看货主名是否存在
	public static function userExist ($type = 0,$user)
	{
		if($type == 0)
		{	$rs = ExecSQL("SELECT Name FROM sys_users WHERE Name =:user",array("user"=>$user));
			return $rs->RecordCount > 0 ? $rs->AsArray() : null;
		}
		if($type == 1)
		{	$rs = ExecSQL("SELECT MobilePhone FROM sys_users WHERE MobilePhone =:user",array("user"=>$user));
			return $rs->RecordCount > 0 ? $rs->AsArray() : null;
		}
	}

}
?>