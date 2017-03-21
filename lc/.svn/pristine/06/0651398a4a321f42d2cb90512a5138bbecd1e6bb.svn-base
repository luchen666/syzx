<?php
include_once(APP_PATH . "/utils/ResponseResult.php");
include_once(MODELS_PATH . "/common.php");

//登录用户
session_start();
$user = IsSet($_SESSION["User"]) ? $_SESSION["User"] : array("IsLogin"=>0);
$userID = IsSet($_SESSION["User"]["IsLogin"]) && $_SESSION["User"]["IsLogin"] == 1 ? $_SESSION["User"]["UserID"] : -1;

class HomeController extends BaseController 
{
	//首页
	public function indexAction()
	{	global $user;
		$ret = Common::TodayCount();
		
		$data = ($ret == null) ? "[]" : json_encode($ret,JSON_UNESCAPED_UNICODE);
		$this->getView()->assign("user",json_encode($user));
		$this->getView()->assign("todayData",$data);
		$this->getView()->Display("index.htm");
	}
	
	//找船
	public function shipAction()
	{	global $user,$userID;
	
		//船期列表
		$act = "NEW";
		$id = 0;
		if(IsSet($_GET["ACT"]))
		{	$act = $_GET["ACT"];
			UnSet($_GET["ACT"]);
		}
		if(IsSet($_GET["ID"]))
		{	$id = $_GET["ID"];
			UnSet($_GET["ID"]);
		}
		
		//RegionID=%d&PortID=%d&DateFrom=%s&DateTo=%s&TonnageFrom=%d&TonnageTo=%d
		
		//船期列表
		$ret = Common::getShipSchList($act,$id,$userID,$_GET);
		$list = ($ret == null) ? "[]" : json_encode($ret,JSON_UNESCAPED_UNICODE);
		
		//包装方式
		$packageMethod = Common::getPackageMehtodList();
		
		//填充到模板变量
		$this->getView()->assign("schlist",$list);
		$this->getView()->assign("user",json_encode($user));
		$this->getView()->assign("packageMethod",json_encode($packageMethod));
		
		//载入并输出视图
		$this->getView()->Display("ship.htm");
	}


	//用户登录
	public function loginAction()
	{
		$this->getView()->Display("login.htm");
	}

	//用户注册
	public function registerAction()
	{
		$this->getView()->Display("register.htm");
	}
	//找回密码
	public function findPwdAction()
	{
		$this->getView()->Display("findPwd.htm");
	}




}
?>
