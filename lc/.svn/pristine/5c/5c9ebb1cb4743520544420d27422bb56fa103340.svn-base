<?php
include_once(MODELS_PATH . "/BaseModel.php");
class News Extends BaseModel
{
    //查所有新闻
    public static function all($page = 1,$rows = 10)
    {
		$from = ($page-1)*$rows;
		$sql =  "SELECT N.ID,
						N.Subject,
						N.Hits,
						N.CreateDate,
						N.State,
						0 AS Checked,
						A.Name
				   FROM News AS N LEFT JOIN sys_Admin AS A ON N.CreateUserID = A.UserID
			   ORDER BY ID DESC
				  LIMIT $from,$rows";
		$rs = ExecSQL($sql);
        return $rs->RecordCount > 0 ? $rs->AsArray() : null;
    }
	
	public static function getCount()
	{
		$rs = ExecSQL("SELECT Count(ID) AS Cnt FROM News");
		return $rs->Cnt;
	}
	
	public static function setState($id,$state)
	{
		ExecSQL("UPDATE News SET State = $state WHERE ID IN ($id)");
		return true;
	}
	
	public static function del($id)
	{	ExecSQL("DELETE FROM News WHERE ID IN ($id)");
		return true;
	}
	
	public static function save($parm,$tbname = '', $rs = NULL)
	{
		if($parm["ID"] == 0)
		{	$sql = "INSERT INTO News
							(	Subject,
								Image,
								Body,
								Hits,
								State,
								CreateUserID,
								CreateDate
							)
					VALUES	(	:Subject,
								:Image,
								:Body,
								0,
								1,
								:UserID,
								Now()
							)";
			unset($parm["ID"]);
		}
		else
		{	$sql =  "UPDATE	News SET Subject = :Subject,Image = :Image,Body = :Body WHERE ID = :ID";
			unset($parm["UserID"]);
		}
		ExecSQL($sql,$parm);
		return true;
	}
}