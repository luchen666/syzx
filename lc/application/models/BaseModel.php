<?php
include_once(MODELS_PATH . "/db.php");

class BaseModel
{
	public static function Get($ID,$tbname = "")
	{
		if($tbname == "")	$tbname = get_called_class();

		$rs = ExecSQL("SELECT * FROM $tbname WHERE ID = :ID",Array("ID"=>$ID));
		
		return ($rs->RecordCount > 0) ? $rs->AsArray()[0] : null;
	}
	
	public static function GetAllList($param = null,$pageIndex = 0,$pageRows = 0,$tbname = "")
	{
		if($tbname == "")	$tbname = get_called_class();

		$sql = "SELECT * FROM $tbname WHERE 1 = 1";
		
		//加入参数
		if($param != null)
		{
			foreach($param as $key => $value)
			{
				if(strtoupper($key) != "ORDERKEY" && strtoupper($key) != "ORDERTYPE")
				{	if($key == "(")			$sql .= "(";
					else if($key == ")")	$sql .= ")";
					else					$sql .= " AND $key = :$key";
				}
			}
			
			$OrderKey = IsSet($param["OrderKey"]) ? $param["OrderKey"] : "" ;
			$OrderType = IsSet($param["OrderType"]) ? $param["OrderType"] : "";
			if($OrderKey != "")		$sql .= " ORDER BY $OrderKey $OrderType";
		}

		//执行
		$rs = ExecSQL($sql,$param,$pageIndex,$pageRows);
		if($rs->RecordCount == 0)
			return null;
		else
			return $rs->AsArray();
	}
	
	public static function GetList($act,$id,$rows=20,$where = array(),$tbname)
	{
		if($tbname == "")	$tbname = get_called_class();
		
		if($act == "NEW" || $act == "GT")	$opr = ">";
		if($act == "OLD" || $act == "LT")	$opr = "<";
		
		$OrderKey = "ID";
		$OrderType = "DESC";
		
		$sql =  "SELECT * FROM $tbname WHERE ID $opr :ID";
		
		$parm = array("ID" => $id);
		
		//加入参数
		if($where != null)
		{
			foreach($where as $key => $value)
			{
				if(strtoupper($key) != "ORDERKEY" && strtoupper($key) != "ORDERTYPE")
				{	if($key == "(")			$sql .= "(";
					else if($key == ")")	$sql .= ")";
					else					$sql .= " AND $key = :$key";
				}
				$parm[$key] = $value;
			}
			
			$OrderKey = IsSet($where["OrderKey"]) ? $where["OrderKey"] : "" ;
			$OrderType = IsSet($where["OrderType"]) ? $where["OrderType"] : "";
		}
		if($OrderKey != "")		$sql .= " ORDER BY $OrderKey $OrderType";
		$sql .= " LIMIT $rows";
		
		//执行
		$rs = ExecSQL($sql,$parm);
		if($rs->RecordCount == 0)
			return null;
		else
			return $rs->AsArray();
	}
	
	//删除记录
	public static function Delete($parms = null,$tbname = "")
	{
		if($tbname == "")	$tbname = get_called_class();
		$sql = "UPDATE IsDeleted = 1 FROM $tbname WHERE 1 = 0 ";
		
		//加入参数
		if($parms != null)
		{
			if(Is_Numeric($parms))
			{
				$sql .= " OR ID = $parms";
			}
			else if(Is_Array($parms))
			{
				$sql .= " OR (";
				foreach($parms as $key => $value)
				{
					if($key == "(")			$sql .= "(";
					else if($key == ")")	$sql .= ")";
					else					$sql .= " AND $key = :$value";
				}
				$sql .= ")";
			}
		}
		
		$rs = ExecSQL($sql,$parms);
	}
	
	public static function Update($keyValues,$where,$tbname = "")
	{
		if($tbname == "")	$tbname = get_called_class();
		
		$parms = array();
		
		$sql = "UPDATE $tbname SET ";
		foreach($keyValues as $key => $value)
		{
			$sql .= " $key = :$key,";
			$parms["$key"] = $value;
		}
		
		$sql = substr($sql,0,strlen($sql)-1);
		$sql .= " WHERE 1 = 1";
		
		foreach($where as $key => $value)
		{
			$sql .= " AND $key = :$key";
			
			if(!array_key_exists($key,$parms))	$parms["$key"] = $value;
		}
		
		try
		{
			ExecSQL($sql,$parms);
			return true;
		}
		catch(exception $e)
		{
			return $e->getMessage();
		}
	}
	
	//根据数组Key与Value对值，保存到数据库。字段名就是数组Key，字段值是数组Value。
	public static function Save($parms,$tbname = "",$rs = null)
	{	
		if($tbname == "")	$tbname = get_called_class();
		
		//如果数组包含ID，且值不为0，执行Update，否则，执行Insert
		if(IsSet($parms["ID"]) && Is_Numeric($parms["ID"]) && $parms["ID"] > 0)
			$method = "UPDATE";
		else
			$method = "INSERT";
		
		$keys = array();
		
		//提取字段列表
		foreach($parms as $key => $value)	if(strtoupper($key) != "ID")	$keys[] = $key;
			
		//组装sql语句	
		if($method == "INSERT")
		{	
			unset($parms["ID"]);
			
			$sql = "INSERT INTO $tbname (";
			
			foreach($keys as $key)	$sql .= "`$key`,";
				
			$sql = substr($sql,0,strlen($sql)-1) . ") VALUES (";
			
			foreach($keys as $key)	$sql .= ":$key,";
			
			$sql = substr($sql,0,strlen($sql)-1) . ")";
		}
		else
		{
			$sql = "UPDATE $tbname SET ";

			foreach($keys as $key)
			{
				$ukey = strtoupper($key);
				
				//排除字段
				if($ukey != "CREATEUSERID" || $ukey != "CREATEUSERNAME" || $ukey != "CREATEDATE")
					$sql .= "`$key` = :$key,";
				else
					unset($parms[$key]);
			}
			$sql = substr($sql,0,strlen($sql)-1) . " WHERE ID = :ID";
		}	

		try
		{
			if($rs == null)	$rs = new RecordSets();
		
			$rs->ExecSQL($sql,$parms);
			return true;
		}
		catch(exception $e)
		{
			return $e->getMessage();
		}
	}	
		
}
?>
