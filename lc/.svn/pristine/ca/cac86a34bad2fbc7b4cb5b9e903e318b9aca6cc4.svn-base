<?php
include_once(MODELS_PATH . "/BaseModel.php");

class Port extends BaseModel
{
	//港口
	public static function getPort()
	{
		$sql =  "SELECT	*  FROM  Port WHERE isDeleted=0 ";
		$rs = ExecSQL($sql);
		return $rs->AsArray();
	}
	//查一个港口
	public static function getonePort($id)
    {
        $sql =  "SELECT	*  FROM  Port WHERE ID = :ID ";
        $rs = ExecSQL($sql,Array("ID"=>$id));
        return $rs->AsArray();
    }
    //插入
    public static function insertPort($PortName,$Spell,$ShortSpell,$FirstLetter,$RegionId,$Description)
    {
        $sql =  "INSERT INTO Port(PortName,Spell,ShortSpell,FirstLetter,RegionId,Description,CreateDate,UpdateDate)VALUES(:PortName,:Spell,:ShortSpell,:FirstLetter,:RegionId,:Description,now(),now())";
        $rs = ExecSQL($sql,Array("PortName"=>$PortName,"Spell"=>$Spell,"ShortSpell"=>$ShortSpell,"FirstLetter"=>$FirstLetter,"RegionId"=>$RegionId,"Description"=>$Description));
        return $rs->AsArray();
    }
    //改
    public static function updatePort($ID,$PortName,$Spell,$ShortSpell,$FirstLetter,$RegionId,$Description)
    {
        $sql =  "UPDATE Port SET PortName='$PortName',Spell='$Spell',ShortSpell='$ShortSpell',FirstLetter='$FirstLetter',RegionId='$RegionId',Description='$Description',UpdateDate=now() WHERE ID='$ID'";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }

//=======================================================
    //从port表中获取数据
    public static function getPortList($page=1,$rows=20,$where = array())
    {
        $from = ($page - 1)*$rows;
        $sql = "SELECT port.* ,BG.ProvinceName,BG.CityName
                FROM port LEFT JOIN base_region AS BG ON port.RegionID = BG.ID %s LIMIT $from,$rows";
        if(IsSet($where["PortName"]) && $where["PortName"] !="")
        {   $text = "WHERE PortName LIKE '%" . $where["PortName"] ."%'";
            $sql = sprintf($sql,$text);
        }
        else    $sql = sprintf($sql, " ");

        $rs = ExecSQL($sql);
        return $rs->RecordCount > 0 ? $rs->AsArray() : null;
    }

    //查找港口总数
    public static function getCountCompanies()
    {
        $rs = ExecSQL("SELECT Count(ID) AS N FROM port");
        return $rs->N;
    }
    //删
    public static function del($id)
    {
        $id=explode(",",$id);
        $count=count($id);
        for($i=0;$i<$count;$i++){
            ExecSQL("UPDATE Port SET IsDeleted = 1 WHERE ID='$id[$i]'");
        };
        return true;
    }


}
?>