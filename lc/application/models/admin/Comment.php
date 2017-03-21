<?php
/* 编写:穆金秋*/
include_once(MODELS_PATH . "/BaseModel.php");
class Comment Extends BaseModel
{
    //查所有评论 相同用户只展示最新的一条数据
    public static function getComments()
    {
        $sql =  "select T.*,su.Name from(
                        select
                            u.ID,
                            u.UserID,
                            u.Body,
                            u.CreateDate
                        from usr_suggest u
                        where id = (select max(id) from usr_suggest  where UserID=u.UserID )
                        order by u.CreateDate DESC
                        )T
                        inner join  sys_users su
                        ON T.UserID=su.UserID
        ";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }
    //查待回复评论
    public static function getNoAnsweredComments()
    {
        $sql =  "SELECT	*  FROM  usr_suggest WHERE IsRead =0 and IsDeleted=0 order by CreateDate DESC";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }
    //查待回复评论数量
    public static function getNoAnsweredCommentsCount()
    {
        $sql =  "SELECT	COUNT(*) as Cnumber  FROM  usr_suggest WHERE IsRead =0 and IsDeleted=0";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }
    //查一个UserID对话 按时间CreateDate排序
    public static function getonedialog($UserID)
    {
        $sql =  "SELECT *  FROM usr_suggest WHERE UserID = $UserID and IsDeleted=0 order by CreateDate";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }
    //处理回复 现在的问题是一个人有多条评论回复一条既默认回复所有
    public static function saveReply($UserID,$Body)
    {
        $sql =  "INSERT INTO usr_suggest(UserID,Body,CreateDate,CreateUserID) VALUES (:UserID,:Body,now(),-1)";
        $rs = ExecSQL($sql,Array("UserID"=>$UserID,"Body"=>$Body));
        $sql =  " UPDATE usr_suggest SET IsRead =1 WHERE UserID='$UserID'";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }
}