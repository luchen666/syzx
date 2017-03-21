<?php

if(!function_exists("DB_INC"))
{
function DB_INC() {	return true; }

//数据连接
if(!IsSet($__DBCONN__))		$__DBCONN__	= 0;

$SQLTYPE	= "ACCESS";
$SQLISNULL	= "IIF(IsNull(%s),%s,%s)";
$SQLDT		= "#";
$SQLNOW		= "Now()";
$SQLDPMIN	= "'n'";
$SQLDPSEC	= "'s'";
$SQLDPDAY	= "'d'";
$SQLDPWEEK	= "'ww'";
$SQLDPMON	= "'m'";
$SQLDPQUART = "'q'";
$SQLDPYEAR	= "'yyyy'";


function SQLIsNull($AColumnName,$AValue)
{	global $SQLISNULL;
	return sprintf($SQLISNULL,$AColumnName,$AValue,$AColumnName);
}

class Object
{
	var $_properties = Array();

	public function __get($PropertyName)
	{
		if(isset($this->_properties[$PropertyName]))
			return $this->_properties[$PropertyName];
		else
			return "";
	}

	public function __set($PropertyName, $value)
	{	$FunName = "_set" . $PropertyName;

		if(method_exists($this,$FunName))
			$this->$FunName($value);
		else
			$this->_properties[$PropertyName] = $value;
	}
}


class ArrToObj extends Object
{
	public function __construct($arr)
	{	$this->_properties = $arr;
		
	}
	
	public function __get($PropertyName)
	{
		if(isset($this->_properties[$PropertyName]))
			return $this->_properties[$PropertyName];
		else
			return "";
	}
}

class RecordSets extends Object
{
	private $_rs				= null;			//PDOStatement对象
	private $_isFetched		= false;		//是否已Fetch过。
	private $_inTrans			= false;
	private $_pageRows		= 0;			//分页功能，一页行数
	private $_pageIndex		= 0;			//分布功能，页指针
	
	private $_fields			= array();		//字段名集合
	private $_row				= array();		//结果集
	
	private $_bof				= true;			//到记录头
	private $_eof				= true;			//到记录尾
	
	private $_recordCount		= 0;			//查询记录总数，用于分页

	private $_rowIndex		= 0;			//结果集指针
	private $_rowCount		= 0;			//结果集总数，与_recordCount的区别是，_recordCount是总的记录数，_rowCount是当前页的记录数
	
	private $_parms			= null;			//保存的参数
	
	private $_debug			= true;		//是否输出错误的sql
	
	//对象方法
	public function __construct($pageIndex=0,$pageRows=0)
	{	global $__DBCONN__,$__DBCONNCOUNT__;
		
		$this->_pageRows	= $pageRows;
		$this->_pageIndex	= $pageIndex;
		
		if($__DBCONN__ === 0)
		{	
			$config = Yaf_Registry::get("config")->database->master;
			$s = sprintf("mysql:host=%s:%d;dbname=%s;charset=%s",$config->host,$config->port,$config->dbname,$config->charset);
			$this->_debug = $config->debug;

			try
			{
				$__DBCONN__ = new PDO($s,$config->user,$config->pwd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));    
			}
			catch(Exception $e)
			{
				$__DBCONN__ = 0;
				
				$msg = sprintf("连接数据库错误：%s",$e->GetMessage());
				
				if($this->_debug)	print($msg);
				
				error_log($msg);
				
				exit;
			}
		}
	}
	
	//析构函数，释放资源
	public function __destruct()
	{	
		global $__DBCONN__;
		
		if($this->_inTrans)
		{
			$__DBCONN__->rollBack();
		}
		$this->_rs = null;
		$this->_rows = null;
	}
	
	//事务处理
	public function beginTrans()
	{	global $__DBCONN__;
		
		if(get_class($__DBCONN__) === "PDO")
		{	
			if($this->_inTrans)	return;
			
			$__DBCONN__->beginTransaction();
			$__DBCONN__->setAttribute(PDO::ATTR_AUTOCOMMIT,0);
			$this->_inTrans = true;
		}
	}
	public function commit()
	{	global $__DBCONN__;
		
		if(get_class($__DBCONN__) === "PDO")
		{
			$__DBCONN__->commit();
			$__DBCONN__->setAttribute(PDO::ATTR_AUTOCOMMIT,1);
			$this->_inTrans = false;
		}
	}
	public function rollBack()
	{	global $__DBCONN__;
		
		if(get_class($__DBCONN__) === "PDO")
		{
			$__DBCONN__->rollBack();
			$__DBCONN__->setAttribute(PDO::ATTR_AUTOCOMMIT,1);
			$this->_inTrans = false;
		}
	}
	
	/* 执行SQL
	 * $sql			sql语句
	 * $parms		参数
	 */
	public function ExecSQL($sql,$parms = null)
	{
		global $__DBCONN__;
		
		if(get_class($__DBCONN__) === false)	return false;
		
		$this->_parms = $parms;
		$this->_rowCount = 0;
		$this->_rowIndex = 0;
		$this->_isFetched = false;
		$this->_eof = true;
		$this->_bof = true;
		
		//SELECT语句，且需要分页
		if(StrToUpper(substr(trim($sql),0,6)) == "SELECT" && $this->_pageRows > 0)
		{
			//先根据条件获取总的记录数
			$p = strpos(str_replace("	"," ",strtoupper($sql))," FROM ");
			$s = "SELECT Count(*) AS RowCount " . substr($sql,$p);
			
			try
			{	$this->_rs = $__DBCONN__->prepare($s);
				$this->_rs->execute($parms);		
			}
			catch(Exception $e)
			{
				if($this->_inTrans)	$this->rollBack();
				
				$sparm = $parms == null ? "" : implode(",", $parms);
				$msg = sprintf("数据库错误 %s\r\n%s\r\n%s\r\n[%s]\r\n",$_SERVER['REQUEST_URI'],$e->GetMessage(),$sql,$sparm);
				error_Log($msg);

				if($this->_debug)	print("<pre>$msg");

				throw ($e);

				exit;
			}
			
			$row = $this->_rs->fetch(PDO::FETCH_ASSOC);
			$this->_recordCount = $row["RowCount"];
			$this->_rs->closeCursor();
			
			//如果当前行数超过总行数，记录为空
			if($this->_pageRows * $this->_pageIndex > $this->_recordCount)	return true;
			
			$sql .= sprintf(" LIMIT %d,%d", $this->_pageRows * $this->_pageIndex, $this->_pageRows);
		}

		try
		{	$this->_rs = $__DBCONN__->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));		//允许前后移动
			$this->_rs->execute($parms);		
		}
		catch(Exception $e)
		{
			if($this->_inTrans)	$this->rollBack();
			
			$sparm = $parms == null ? "" : implode(",", $parms);
			$msg = sprintf("数据库错误 %s\r\n%s\r\n%s\r\n[%s]\r\n",$_SERVER['REQUEST_URI'],$e->GetMessage(),$sql,$sparm);
			error_Log($msg);

			if($this->_debug)	print("<pre>$msg");

			throw ($e);

			exit;
		}
		
		if(StrToUpper(substr(trim($sql),0,6)) == "SELECT")
		{
			$this->_rowIndex = 0;
			$this->_rowCount = 0;
			
			$this->_recordCount = $this->_rs->rowCount();
			$this->_rowCount = $this->_recordCount;
			
			$this->_bof = true;
			$this->_eof = true;
			
			for($i=0; $i<$this->_rs->columnCount(); $i++) 	$this->_fields[] = $this->_rs->getColumnMeta($i)["name"];
		}
		
		return true;
	}

	public function Close()
	{
		if($this->_rs)	$this->_rs->closeCursor();
		
		$this->_isFetched = false;
		$this->_rowCount = 0;
	}

	public function __get($PropertyName)
	{	
		if(strtoupper($PropertyName) == "BOF")
		{
			//第一次读取BOF，需要先fetch。
			if($this->_isFetched === false && $this->_rowCount > 0)	$this->First();
			$this->_isFetched = true;

			return $this->_bof;
		}
		if(strtoupper($PropertyName) == "EOF")
		{	//第一次读取EOF，需要先fetch。
			if($this->_isFetched === false && $this->_rowCount > 0)	$this->First();
			$this->_isFetched = true;

			return ($this->_eof);
		}
		if(strtoupper($PropertyName) == "INTRANS")		return $this->_inTrans;
		if(strtoupper($PropertyName) == "RECORDCOUNT")	return $this->_recordCount;
		if(strtoupper($PropertyName) == "CURRENTROW")	return $this->_pageRows * $this->_pageIndex + $this->_rowIndex;
		
		if($this->_rowCount > 0 && in_array($PropertyName,$this->_fields))
		{
			//第一次读取BOF，需要先fetch。
			if($this->_isFetched === false && $this->_rowCount > 0)	$this->First();

			$this->_isFetched = true;
			
			return $this->_row[$PropertyName];
		}
		else
			return "";
	}
	
	public function __set($PropertyName, $value)
	{	$FunName = "_set" . $PropertyName;

		if(method_exists($this,$FunName))	$this->$FunName($value);
		
	}
	
	public function First()
	{	
		if($this->_rowCount == 0)	return;
		
		$this->_bof = true;
		$this->_eof = false;
		$this->_rowIndex = 0;

		$this->_row = $this->_rs->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_FIRST);
		
		$this->_isFetched = true;
	}
	
	public function Previous()
	{	
		if($this->_rowCount == 0)	return;

		if($this->_rowIndex > 0)	$this->_rowIndex--;

		$row = $this->_rs->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_PRIOR);
		
		$this->_isFetched = true;
					
		if($row === false)
		{
			$this->_bof = true;
			$this->_eof = fale;
		}
		else
		{
			$this->_row = $row;
			
			$this->_bof = false;
			$this->_eof = false;
		}
	}
	
	public function Next()
	{
		if($this->_rowCount == 0)	return;

		if($this->_rowIndex < $this->_rowCount - 1)	$this->_rowIndex++;

		$row = $this->_rs->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT);
		
		$this->_isFetched = true;
		
		if($row === false)
		{
			$this->_bof = false;
			$this->_eof = true;
		}
		else
		{
			$this->_row = $row;
			
			$this->_bof = false;
			$this->_eof = false;
		}
	}
	
	function Last()
	{	
		if($this->_rowCount == 0)	return;
		
		$this->_bof = false;
		$this->_eof = true;
		$this->_rowIndex = $this->_rowCount - 1;
			
		$this->_row = $this->_rs->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_LAST);
		
		$this->_isFetched = true;
	}
	
	public function AsArray()
	{
		if($this->_rowCount == 0)	return array();
		
		if($this->_isFetched)
		{
			$this->_rs->closeCursor();
			$this->_rs->execute($this->_parms);
		}
		
		$rows = $this->_rs->FetchAll(PDO::FETCH_ASSOC);

		$rows[0]["_RecordCount_"] = $this->_recordCount;
		return $rows;
	}
	
	public function JSon()
	{
		if($this->_rowCount == 0)	return "";
		
		if($this->_eof && $this->_bof)	$this->First();
		
		$row = $this->_row;
		$row["_RecordCount_"] = $this->_recordCount;
		
		return json_encode($row,JSON_UNESCAPED_UNICODE);
	}
	
	public function JSonAll()
	{
		if($this->_rowCount == 0)	return "[]";
		
		$rows = $this->_rs->FetchAll(PDO::FETCH_ASSOC);
		$rows[0]["_RecordCount_"] = $this->_recordCount;
		
		return json_encode($rows,JSON_UNESCAPED_UNICODE);
	}

	public function ValueList($Field,$Delimiter=",")
	{	$ret = "";

		if($this->_rowCount == 0)	return $ret;

		if($this->_isFetched)
		{
			$this->_rs->closeCursor();
			$this->_rs->execute($this->_parms);
		}

		$rows = $this->_rs->FetchAll(PDO::FETCH_ASSOC);

		for($i=0;$i<$this->_rowCount;$i++)
		{	
			if($rows[$i][$Field] != "")
			{	if($ret == "")	$ret = $rows[$i][$Field];
				else			$ret = $ret . $Delimiter . $rows[$i][$Field];
			}
		}
				
		return $ret;
	}
}

function ExecSQL($sql,$parms=null,$pageIndex=0,$pageRows=0)
{
	$rs = new RecordSets($pageIndex,$pageRows);
	$rs->ExecSQL($sql,$parms);
	return $rs;
}

}
?>