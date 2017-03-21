<?php
include_once(MODELS_PATH . "/BaseModel.php");

class Login Extends BaseModel
{
    //查一个Account能否登录 账号、密码都要匹配才能登录
    public static function loginAccount($Account,$Password)
    {
        $sql =  "SELECT * FROM  sys_admin WHERE Account = :Account";
        $rs = ExecSQL($sql,array("Account" => $Account));

		//-1 用户不存在
		if($rs->recordCount == 0)			return -1;

		$user = $rs->AsArray()[0];

		//-2 密码错误
		$pwd = md5($Password . $user["Salt"]);
		if($pwd != $user["Password"])		return -2;

		//-3 用户被锁定
		if($user["State"] == 10)			return -3;

        //保存会话
        session_start();
        $_SESSION["User"] = array("IsLogin"	=>true,"UserID"	=>$user["UserID"],"Name"=>$user["Name"],"Level"=>$user["Level"]);

        //更新登录信息
		$sql =  "UPDATE sys_admin SET LastLoginDate = Now(),LastLoginIP = :ip,LoginCount = IFNULL(LoginCount,0) + 1 WHERE UserID = :ID";
		$rs->ExecSQL($sql,array("ip"=>$_SERVER["REMOTE_ADDR"],"ID"=>$user["UserID"]));

		return 1;
    }
}
?>