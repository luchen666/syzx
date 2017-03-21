<?php
include_once(MODELS_PATH . "/BaseModel.php");

class Shipsch Extends BaseModel
{
	//船期表
	public static function getShipSch($page = 1,$rows = 10)
	{
		$from = ($page - 1) * $rows;
		$sql =	"SELECT ship.ShipName,
						ship.SeaOrRiver,
						ship.Tonnage,
						Shipsch.*
				FROM Shipsch
						INNER JOIN ship ON Shipsch.ShipID=ship.ID
				WHERE Shipsch.IsDeleted=0
				ORDER BY Shipsch.ID DESC
				LIMIT $from,$rows";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}

	//从船期表中读取一条数据 依据ID
	public static function getOneShipSch($id)
	{
		$sql =	" SELECT ship.ShipName,
						 ship.SeaOrRiver,
						 ship.Tonnage,
						 Shipsch.*
					FROM Shipsch
						 INNER JOIN ship ON Shipsch.ShipID=ship.ID
					WHERE Shipsch.IsDeleted=0 AND
							Shipsch.ID=$id";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}
	//总船期
	public static function getAllShipschCount()
	{
		$sql =	"SELECT Count(ID) AS TotalShipschNumber FROM Shipsch WHERE IsDeleted=0";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}
	//新增船期 CreateDate数据类型也是datetime等于今天的船期
	public static function getnewShipschCount()
	{
		$sql = "SELECT	COUNT(ID) as NewShipschNumber	FROM	Shipsch WHERE IsDeleted=0 and CreateDate > DATE_FORMAT(NOW(),'%Y-%m-%d 00:00:00')";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}
	//可抢船期 ClearDate大于等于今天且State=0 状态发布的船期
	public static function getShipschCount()
	{	$tody=date("Y-m-d");
		$sql =	"SELECT Count(ID) AS GetShipschNumber FROM Shipsch WHERE IsDeleted=0 and State=0 and ClearDate >= $tody";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}
	
	public static function getCount()
	{
		$rs = ExecSQL("SELECT Count(ID) AS Cnt FROM shipsch");
		return $rs->Cnt;
	}
	
	//在船期表Shipsch中修改一条记录
	public static function updateShipsch($ID,$UserName,$ShipName,$ShipType,$SeaOrRiver,$Tonnage,$ClearPortName,$ClearDate,$State,$Memo)
	{
		$sql="UPDATE Shipsch
				SET	UserName		= '$UserName',
					ShipName		= '$ShipName',
					ShipType		= '$ShipType',
					SeaOrRiver		= '$SeaOrRiver',
					Tonnage			= '$Tonnage',
					ClearPortName	= '$ClearPortName',
					ClearDate		= '$ClearDate',
					State			= '$State',
					Memo			= '$Memo',
					UpdateDate		= now()
				WHERE ID = $ID";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}
	
	//在船期表Shipsch中删除记录
	public static function deleteSch($id)
	{	
		ExecSQL("UPDATE ShipSch SET IsDeleted = 1 WHERE ID IN($id)");
	}

	//保存添加或编辑数据
	public static function saveSch($parm)
	{
		$sql = "INSERT INTO ShipSch
						(	UserID,
							UserName,
							ShipType,
							ShipName,
							ClearPortID,
							ClearPortName,
							ClearDate,
							State,
							Memeo,
							IsDeleted,
							CreateDate
						)
				VALUES	(	:UserID,
							:UserName,
							:ShipType,
							:ShipName,
							:ClearPortName,
							:ClearPortID,
							:ClearDate,
							:STATE_NONE,
							:Memeo,
							0,
							Now()
						)";
		ExecSQL($sql,$parm);
		return true;
	}

	public static function del($id)
	{	ExecSQL("DELETE FROM shipSch WHERE ID IN ($id)");
		return true;
	}
	//查看船名是否存在
	public static function getShipName($input)
	{
		$input = "$input%";
		$sql = "SELECT DISTINCT(ship.ShipName),
						ship.UserName,
						ship.Tonnage,
						ship.SeaOrRiver,
						shiptype.ShipTypeName
					FROM shiptype INNER JOIN ship ON shiptype.ID = ship.ShipTypeID
					WHERE ShipName LIKE :input LIMIT 10";
		$rs = ExecSQL($sql,array("input"=>$input));
		return $rs->RecordCount > 0 ? $rs->AsArray() : null;
	}
}
?>
