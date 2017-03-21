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
	
	private $_pageRows			= 0;			//分页功能，一页行数
	private $_pageIndex			= 0;			//分布功能，页指针
	
	private $_fields			= array();		//字段名集合
	private $_rows				= array();		//结果集
	
	private $_bof				= true;			//到记录头
	private $_eof				= true;			//到记录尾
	
	private $_recordCount		= 0;			//查询记录总数，用于分页

	private $_rowIndex			= 0;			//结果集指针
	private $_rowCount			= 0;			//结果集总数，与_recordCount的区别是，_recordCount是总的记录数，_rowCount是当前页的记录数
	
	private $_debug				= false;		//是否输出错误的sql
	
	//对象方法
	public function __construct($pageIndex=0,$pageRows=0)
	{	global $__DBCONN__,$__DBCONNCOUNT__;
		
		$this->_pageRows	= $pageIndex;
		$this->_pageIndex	= $pageRows;
		
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
		$this->_rs = null;
		$this->_rows = null;
	}
	
	//事务处理
	public function beginTrans()
	{	global $__DBCONN__;
		
		if(get_class($__DBCONN__) === "PDO")	$__DBCONN__->beginTransaction(); 
	}
	public function commit()
	{	global $__DBCONN__;
		
		if(get_class($__DBCONN__) === "PDO")	$__DBCONN__->commit(); 
	}
	public function rollBack()
	{	global $__DBCONN__;
		
		if(get_class($__DBCONN__) === "PDO")	$__DBCONN__->rollBack(); 
	}
	
	/* 执行SQL
	 * $sql			sql语句
	 * $parms		参数
	 */
	public function ExecSQL($sql,$parms = array())
	{
		global $__DBCONN__;
		
		if(get_class($__DBCONN__) === false)	return false;
		
		//SELECT语句，且需要分页
		if(StrToUpper(substr(trim($sql),0,6)) == "SELECT" && $this->_pageRows > 0 && $this->_pageIndex > 0)
		{
			//先根据条件获取总的记录数
			$p = strpos(str_replace("	"," ",strtoupper($sql))," FROM ");
			$s = "SELECT Count(*) AS RowCount " . substr($sql,$p);
			$this->_rs = $__DBCONN__->prepare($sql);
			$this->_rs->execute($parms);		
			$row = $this->_rs->fetch(PDO::FETCH_ASSOC);
			$this->_rs = null;
			$this->_recordCount = $row["RowCount"];
			
			//如果当前行数超过总行数，记录为空
			if($this->_pageRows * $this->_pageIndex > $this->_recordCount)	return true;
			
			$sql .= " LIMIT $this->_currentRow, $this->_pageRows";
		}
		
		try
		{	$this->_rs = $__DBCONN__->prepare($sql);
			$this->_rs->execute($parms);		
		}
		catch(Exception $e)
		{	
			$msg = sprintf("数据库错误 %s\r\n%s\r\n%s\r\n[%s]\r\n",$_SERVER['REQUEST_URI'],$e->GetMessage(),$sql,implode(",", $parms));
			
			if($this->_debug)	print("$msg");
			else				print("执行数据库错误");
			
			error_Log($msg);
			return false;
		}

		if(StrToUpper(substr(trim($sql),0,6)) == "SELECT")
		{
			$this->_rowIndex = 0;
			$this->_rowCount = 0;
			
			if($this->_rs->rowCount() == 0)
			{	$this->_bof = true;
				$this->_eof = true;
			}
			
			//获取字段名
			else
			{	$this->_bof = false;
				$this->_eof = false;
				
				$this->_rows = $this->_rs->fetchAll(PDO::FETCH_ASSOC);
				foreach($this->_rows[0] as $key => $value)	$this->_fields[] = $key;
				
				$this->_rowCount = Count($this->_rows);
			}
		}
		
		return true;
	}
	
	public function __get($PropertyName)
	{	if(strtoupper($PropertyName) == "BOF")			return $this->_bof;
		if(strtoupper($PropertyName) == "EOF")			return ($this->_eof);
		if(strtoupper($PropertyName) == "RECORDCOUNT")	return $this->_recordCount();
		if(strtoupper($PropertyName) == "CURRENTROW")	return $this->_pageRows * $this->_pageIndex + $this->_rowIndex;
			
		if($this->_rowCount > 0 && in_array($PropertyName,$this->_fields))
			return $this->_rows[$this->_rowIndex][$PropertyName];
		else
			return "";
	}
	
	public function __set($PropertyName, $value)
	{	$FunName = "_set" . $PropertyName;

		if(method_exists($this,$FunName))	$this->$FunName($value);
		
	}
	
	public function First()
	{	
		$this->_rowIndex = 0;
		$this->_bof = true;
		$this->_eof = $this->_rowCount == 0;
	}
	
	public function Previous()
	{	
		if($this->_rowCount > 0)
		{
			$this->_bof = false;
			
			if($this->_rowIndex > 0)	$this->_rowIndex--;
			else						$this->_bof = true;
			
			$this->_eof = false;
		}
		else
		{	$this->_bof = true;
			$this->_eof = true;
		}
		
	}
	
	public function Next()
	{
		if($this->_rowCount > 0)
		{
			$this->_eof = false;
			if($this->_rowIndex < $this->_rowCount-1)	$this->_rowIndex++;
			else										$this->_eof = true;
			
			$this->_bof = false;
		}
		else
		{	$this->_bof = true;
			$this->_eof = true;
		}
	}
	
	function Last()
	{	
		if($this->_rowCount > 0)	$this->_rowIndex = $this->_rowCount-1;
		
		$this->_bof = $this->_rowCount == 0;
		$this->_eof = true;
	}
	
	public function AsArray()
	{	return $this->_rows;
	}
	
	public function JSon()
	{
		return json_encode($this->_rows);
	}

	public function ValueList($Field,$Delimiter=",")
	{	$ret = "";

		if($this->_rowCount > 0)
		{
			$this->First();
			
			while(!$this->_eof)
			{	if($this->_rows[$this->_rowIndex][$Field] != "")
				{	if($ret == "")	$ret = $$this->_rows[$this->_rowIndex][$Field];
					else			$ret = $ret . $Delimiter . $this->_rows[$this->_rowIndex][$Field];
				}
				$this->Next();
			}
		}
				
		return $ret;
	}
}

function ExecSQL($sql,$parms=array(),$pageIndex=0,$pageRows=0)
{
	$rs = new RecordSets($pageIndex,$pageRows);
	$rs->ExecSQL($sql,$parms);
	return $rs;
}

}
?>