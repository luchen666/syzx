<?php
include_once(MODELS_PATH . "/BaseModel.php");
class adminUser Extends BaseModel
{
    //查所有新闻
    public static function all($page = 1,$rows = 10)
    {
		$from = ($page-1)*$rows;
		$sql =  "SELECT UserID,Account,Dept,Name,Level,LastLoginIP,LastLoginDate,LoginCount,SortNo,State,CreateUserID,CreateDate FROM sys_Admin ORDER BY SortNo,UserID LIMIT $from,$rows";
		$rs = ExecSQL($sql);
        return $rs->RecordCount > 0 ? $rs->AsArray() : null;
    }
	
	public static function count()
	{
		$rs = ExecSQL("SELECT Count(UserID) AS Cnt FROM sys_Admin");
		return $rs->Cnt;
	}

	public static function get($id,$tbname = '')
	{
		$rs = ExecSQL("SELECT *,'' AS Password,'' AS Salt FROM sys_Admin WHERE UserID = $id");
		return ($rs->RecordCount > 0) ? $rs->AsArray()[0] : null;
	}
	
	public static function del($id)
	{	ExecSQL("DELETE FROM sys_Admin WHERE UserID IN ($id)");
		return true;
	}
	
	public static function save($parm, $tbname = '', $rs = NULL)
	{
		$data = array(
			"Account"	=> $parm["Account"],
			"Name"		=> $parm["Name"],
			"Dept"		=> $parm["Dept"],
			"Level"		=> $parm["Level"],
			"SortNo"	=> $parm["SortNo"],
			"State"		=> $parm["State"]
		);
		
		if($parm["UserID"] == 0)
		{	
			$data["CreateUserID"]	= $parm["CreateUserID"];
			$data["Salt"] 			= MakeRand();
			$data["Password"] 		= MD5($parm["Password"] . $data["Salt"]);
			
			$sql = "INSERT INTO sys_Admin
							(	Account,
								Password,
								Salt,
								Name,
								Dept,
								Level,
								SortNo,
								LoginCount,
								State,
								CreateUserID,
								CreateDate
							)
					VALUES	(	:Account,
								:Password,
								:Salt,
								:Name,
								:Dept,
								:Level,
								:SortNo,
								0,
								:State,
								:CreateUserID,
								Now()
							)";
		} else {
			
			$sql =  "UPDATE	sys_Admin 
						SET Account	= :Account,%s
							Name	= :Name,
							Dept	= :Dept,
							Level	= :Level,
							SortNo	= :SortNo,
							State	= :State
					  WHERE UserID = " . $parm["UserID"];
			if($parm["Password"] != "********")
			{	$data["Salt"] = MakeRand();
				$data["Password"] = MD5($parm["Password"] . $data["Salt"]);
				$sql = sprintf($sql,"Password = :Password,Salt=:Salt,");
			}
			else
			{	$sql = sprintf($sql,""); 
			}
		}
		//echo $sql;
		//print_r($data);
		ExecSQL($sql,$data);
		return true;
	}
}