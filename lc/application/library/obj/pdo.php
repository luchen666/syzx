<?php
class Db_Pdo
{
	protected static $instance = null;
	
	public static $TIMESTAMP_WRITES = false;
	
	public static function getInstance()
	{
		if(!isset(self::$instance)) 	self::$instance = new self();
		return self::$instance;
	}
	
	public function loadConfig($db)
	{
		$this->configMaster($db["master"]["charset"],$db["master"]["host"], $db["master"]["name"], $db["master"]["user"], $db["master"]["pwd"], $db["master"]["port"]);
		$this->configSlave($db["slave"]["charset"],$db["slave"]["host"], $db["slave"]["name"], $db["slave"]["user"], $db["slave"]["pwd"], $db["slave"]["port"]);
	}
	
	public function insert($table, $params = array(), $timestamp_this = null, $break = false)
	{
		if(is_null($timestamp_this))
		{
			$timestamp_this = self::$TIMESTAMP_WRITES;
		}
		
		// first we build the sql query string
		$columns_str = "(";
		$values_str = "VALUES (";
		$add_comma = false;
		
		// add each parameter into the query string
		foreach ($params as $key => $val)
		{
			// only add comma after the first parameter has been appended
			if($add_comma)
			{
				$columns_str .= ", ";
				$values_str .= ", ";
			}
			else
			{
				$add_comma = true;
			}
			
			// now append the parameter
			$columns_str .= "$key";
			$values_str .= ":$key";
		}
		
		// add the timestamp columns if neccessary
		if($timestamp_this === true)
		{
			$columns_str .= ($add_comma ? ", " : "") . "date_created, date_modified";
			$values_str .= ($add_comma ? ", " : "") . time() . ", " . time();
		}
		
		// close the builder strings
		$columns_str .= ") ";
		$values_str .= ")";
		
		// build final insert string
		$sql_str = "INSERT INTO $table $columns_str $values_str";
		
		if($break)		return $sql_str;
		
		// now we attempt to write this row into the database
		try
		{
			$pstmt = $this->getMaster()->prepare($sql_str);
			
			// bind each parameter in the array
			foreach ($params as $key => $val) 
			{
				$pstmt->bindValue(":" . $key, $val);
			}
			
			$pstmt->execute();
			$newID = $this->getMaster()->lastInsertId();
			
			// return the new id
			return $newID;
		}
		catch (PDOException $e)
		{
			if(self::$SHOW_ERR == true) throw new Exception($e);
			
			$this->pdo_exception = $e;
			
			return false;
		}
		catch (Exception $e)
		{
			if(self::$SHOW_ERR == true)		throw new Exception($e);
			
			$this->pdo_exception = $e;
			
			return false;
		}
	}

}