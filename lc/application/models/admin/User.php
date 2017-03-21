<?php
/*
 * UserModel
 *
 * 编写：谢忠杰
 *
 */

include_once(MODELS_PATH . "/BaseModel.php");

class User Extends BaseModel
{
    //查
    public static function getUsers()
    {
        $sql =  "SELECT * FROM  sys_Users WHERE IsDeleted=0";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }
    //查新增用户数量
    public static function getNewUser(){
        $tody=date("Y-m-d");
        //echo $tody;2017-01-20
        $sql = "SELECT	COUNT(*) as newUserNumber  FROM  sys_Users WHERE IsDeleted=0 and CreateDate=$tody";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }
    //查用户各状态数量
    public static function getUserNumber()
    {
        $sql =  "SELECT CertifyState,Count(UserID) AS Unumber FROM sys_Users WHERE IsDeleted=0 GROUP BY CertifyState WITH ROLLUP";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }

    //查一个人
    public static function getUser($id)
    {
        $sql =  "SELECT US.* , company.Name AS companyName FROM  sys_Users AS US LEFT JOIN company ON US.companyID = company.ID WHERE UserID = :UserID ";
        $rs = ExecSQL($sql,Array("UserID"=>$id));
        return $rs->AsArray();
    }

    //插入数据 密码加密
    public static function insertUsers($Account,$Password,$UserType,$Name,$MobilePhone,$TelePhone,$Email,$Duty,$Sex,$Star,$Point,$CertifyState)
    {
        $Salt = MakeRand(6);
        $Password = md5($Password . $Salt);

        $sql =  "INSERT INTO sys_Users(Account,Password,Salt,UserType,Name,MobilePhone,TelePhone,Email,Duty,Sex,Star,Point,CertifyState,CreateDate,UpdateDate)VALUES(:Account,:Password,:Salt,:UserType,:Name,:MobilePhone,:TelePhone,:Email,:Duty,:Sex,:Star,:Point,:CertifyState,now(),now())";

        $rs = ExecSQL($sql,Array("Account"=>$Account,"Password"=>$Password,"Salt"=>$Salt,"UserType"=>$UserType,"Name"=>$Name,"MobilePhone"=>$MobilePhone,"TelePhone"=>$TelePhone,"Email"=>$Email,"Duty"=>$Duty,"Sex"=>$Sex,"Star"=>$Star,"Point"=>$Point,"CertifyState"=>$CertifyState));
        return $rs->AsArray();
    }
    //改 密码加密
    public static function updateUsers($UserID,$Account,$Password,$UserType,$Name,$MobilePhone,$TelePhone,$Email,$Duty,$Sex,$Star,$Point,$CertifyState)
    {
        $Salt = MakeRand(6);
        $Password = md5($Password . $Salt);

        $sql =  "UPDATE sys_Users SET Account='$Account',Password='$Password',Salt='$Salt',UserType='$UserType',Name='$Name',MobilePhone='$MobilePhone',TelePhone='$TelePhone',Email='$Email',Duty='$Duty',Sex='$Sex',Star='$Star',Point='$Point',CertifyState='$CertifyState',UpdateDate=now() WHERE UserID='$UserID'";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }
    //修改用户表图像 --身份证（正面）
    public static function updateIDImage1($UserID,$IDImage1)
    {
        $sql =  "UPDATE sys_Users SET IDImage1='$IDImage1',UpdateDate=now() WHERE UserID='$UserID'";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }
    //修改用户表图像 --身份证（反面）
    public static function updateIDImage2($UserID,$IDImage2)
    {
        $sql =  "UPDATE sys_Users SET IDImage2='$IDImage2',UpdateDate=now() WHERE UserID='$UserID'";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }
    //修改用户表图像 --Avatar
    public static function updateAvatar($UserID,$Avatar)
    {
        $sql =  "UPDATE sys_Users SET Avatar='$Avatar',UpdateDate=now() WHERE UserID='$UserID'";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }
    //删
    public static function deleteUsers($id)
    {
        //把字符串打散为数组
        $id=explode(",",$id);
        $count=count($id);
        for($i=0;$i<$count;$i++){
            ExecSQL("UPDATE sys_Users SET IsDeleted = 1 WHERE UserID='$id[$i]'");
        };
        return true;
    }

    //===============================================
    //获取用户总数
    public static function getAllUsersNum()
    {   $rs = ExecSQL("SELECT COUNT(UserID) AS N FROM sys_users");
        return $rs->N;
    }
    //查询用户信息
    public static function getUsers2($page=1,$rows=20,$where=array())
    {   $from = ($page-1)*$rows;
        $sql = "SELECT US.* , company.Name AS companyName FROM sys_users AS US LEFT JOIN company ON US.companyID = company.ID %s LIMIT $from,$rows";
        if(IsSet($where["Name"]) && $where["Name"] != "")
        {   $text = "WHERE US.IsDeleted = 0 AND US.Name LIKE '%" . $where["Name"] . "%'";
            $sql = sprintf($sql,$text);
        }
        else    $sql = sprintf($sql,"WHERE US.IsDeleted = 0");
        $rs = ExecSQL($sql);
        return $rs->RecordCount > 0 ? $rs->AsArray() : null;
    }



















}