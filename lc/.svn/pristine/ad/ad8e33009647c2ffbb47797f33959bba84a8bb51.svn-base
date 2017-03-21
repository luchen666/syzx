<?php
include_once(APP_PATH . "/utils/ResponseResult.php");
include_once(MODELS_PATH . "/common.php");

class DataController extends BaseController 
{
	//首页数据
	public function TodayCountAction()
	{
	   $ret = Common::TodayCount();
		
		$result = new ResponseResult(true,"",0,$ret);
		echo $result->AsJson();
	}
		
	//船期数据
	public function shipListAction()
	{
		$act = "OLD";
		$id = -1;
		$post = (array)$this->Post;
		
		if(IsSet($post["ACT"]))
		{	$act = $post["ACT"];
			UnSet($post["ACT"]);
		}
		if(IsSet($post["ID"]) && Is_Numeric($post["ID"]))
		{	$id = $post["ID"];
			UnSet($post["ID"]);
		}
		
		//登录用户
		session_start();
		$userID = IsSet($_SESSION["User"]["IsLogin"]) && $_SESSION["User"]["IsLogin"] == 1 ? $_SESSION["User"]["UserID"] : -1;

		$ret = Common::getShipSchList($act,$id,$userID,$post);
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
	}
		
	//港口对话框数据
	public function getRegionPortAction()
	{
		if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]) || !IsSet($_GET["GET"]))
		{	echo (new ResponseResult(false,"参数错误",0,null))->AsJson();
			return;
		}
		
		$ret = Common::getRegionPort($_GET["ID"],$_GET["GET"]);
	    echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
	}
	
	//登录
	public function loginCheckAction()
	{
		$post = $this->Post;
		if(!IsSet($post["Account"]) || !IsSet($post["Password"]) || !IsSet($post["VCode"]))
		{	echo (new ResponseResult(false,"参数错误",0,null))->AsJson();
			return;
		}
		
		session_start();
		if($_SESSION["VCode"] != $post["VCode"])
		{	echo (new ResponseResult(false,"验证码错误",0,null))->AsJson();
			return;
		}
		
		/*
		 *	登录错误检查，由以下参数控制，参数写在application.ini文件中
		 *
		 *	限定时间内如果错登超过限定次数，就必须隔一段时间才能继续登录
		 *	SECURITY_LIMITLOGINTIME			限定时间内，单位：分钟
		 *	SECURITY_LIMITLOGINTIMES		限定次数
		 *	SECURITY_LOGININTERVAL			隔多久，单位：分钟
		 *
		 */
		if(!IsSet($_SESSION["LoginFailTime"]))		$_SESSION["LoginFailTime"] = 0;			//上一次登错时间
		if(!IsSet($_SESSION["LoginFailTimes"]))	$_SESSION["LoginFailTimes"] = 0;		//指定时间内登错计数

		//超过限定错登次数，且还在禁止登录期内
		if($_SESSION["LoginFailTimes"] >= SECURITY_LIMITLOGINTIMES && (time() - $_SESSION["LoginFailTime"] < SECURITY_LOGININTERVAL * 60))
		{
			$msg = sprintf("请在 %d 秒之后再次登录。",time() - $_SESSION["LoginFailTime"]);

			$result = new ResponseResult(false,$msg);
			echo $result->AsJSon();
			return;
		}
		//用户验证
		$ret = Common::Login($post["Account"],$post["Password"],$_SERVER["REMOTE_ADDR"]);

		//错误码
		if(Is_Numeric($ret))
		{
			//-1 用户不存在
			//-2 密码错误
			//-3 用户被锁定
			if($ret === -3)	$msg = sprintf("用户已被锁定，请联系 %s 客服。",SITE_SHORTNAME);
			else			$msg = "用户名或密码错误。";

			//限定时间内多次错登
			if(time() - $_SESSION["LoginFailTime"] < SECURITY_LIMITLOGINTIME * 60)
			{	$_SESSION["LoginFailTimes"] = $_SESSION["LoginFailTimes"] + 1;
			}
			//超出限定时间登错，重新计算
			else
			{	$_SESSION["LoginFailTimes"] = 1;
				$_SESSION["LoginFailTime"] = time();
			}
			
			$result = new ResponseResult(false, $msg);
			echo $result->AsJSon();
			return;
		}
		
		//登录验证
		$_SESSION["LoginFailTime"]  = 0;
		$_SESSION["LoginFailTimes"] = 0;
		
		//返回的数据
		$user = $ret;
		
		//获取用户登录地区号，存入Session
		require_once("../utils/utils.php");
		require_once(MODELS_PATH . "/api2.0/user.php");
		$user["City"] = getClientCity();
		$ret = User::apiGetRegionIDByCity($user["City"]);
		$user["RegionID"] = $ret["RegionID"];
		$user["ProvinceID"] = $ret["ProvinceID"];
		
		//保存用户登录信息到SESSION
		$_SESSION["User"] = array(
			"IsLogin"				=> 1,
			"Account"				=> $user["Account"],
			"Name"					=> $user["Name"],
			"UserID"				=> $user["UserID"],
			"UserType"				=> $user["UserType"],
			"Avatar"				=> $user["Avatar"],
			"RegionID"				=> $user["RegionID"],
			"ProvinceID"			=> $user["ProvinceID"]
		);
		
		//返回数据处理
		unset($user["Password"]);
		unset($user["Salt"]);
		unset($user["IsDeleted"]);
		unset($user["_RecordCount_"]);
		
		$result = new ResponseResult(true,"",0,$user);
		
		echo $result->AsJSon();
	}
	
	//退出登录
	public function LogoutAction()
	{	session_start();
		session_destroy();
		unset($_SESSION);
	}

	//生成手机验证码
	public function VCodeAction()
	{	include_once(APP_PATH . "/Utils/Utils.php");
		//参数检查
		print_r($_GET["Account"]);
		if(!IsSet($_GET["Account"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}

		//生成验证码
		$vcode = MakeRand(SITE_VCODE_LENGTH,"NUMBER");
		//发送验证码短信
		$ret = common::sendVCode($_GET["Account"],$vcode);

		$result = new ResponseResult($ret === true,$ret,0,"");
		echo $result->AsJSon();
	}

	//注册用户
	public function RegisterAction()
	{	$post = $this->Post;
		//参数检查
		if(!IsSet($post["Account"]) || !IsSet($post["Password"]) || !IsSet($post["VCode"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		//==============检查验证码=============
		$ret = common::getVCode($post["Account"]);
		if($ret ===null || $ret["Code"] !=$post["VCode"] || $ret["Long"] > SITE_VCODE_EXPIRED)
		{
			$result = new ResponseResult(false,"错误的验证码。");
			echo $result->AsJSon();
			return;
		}

		//保存注册，返回用户编号
		$ret = common::register($post["Account"],$post["Password"]);
		$result = new ResponseResult(Is_Numeric($ret),$ret,0,$ret);
		echo $result->AsJSon();
	}

	//忘记密码
	public function ForgetPwdAction()
	{	$post = $this->Post;
		//参数检查
		print_r($post);
		if(!IsSet($post["Account"]) || !Is_Numeric($post["Account"]) ||
			!IsSet($post["Password"]) || !IsSet($post["VCode"]) || !Is_Numeric($post["VCode"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		//===============手机验证码检查===========
		$ret = common::getVCode($post["Account"]);
		if($ret ===null || $ret["Code"] !=$post["VCode"] || $ret["Long"] > SITE_VCODE_EXPIRED)
		{
			$result = new ResponseResult(false,"错误的验证码。");
			echo $result->AsJSon();
			return;
		}
		$ret = common::ForgetPwd($post["Account"],$post["Password"]);
		$result = new ResponseResult($ret === true ,$ret);
		echo $result->AsJSon();
	}

	//=================== 快速发布船期信息 =======================
	public function AddUserRequestAction()
	{
		if(!IsSet($this->Post["Content"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$ret = common::AddUserRequest($this->Post["Content"]);
		$result = new ResponseResult($ret === true,$ret);
		echo $result->AsJSon();
	}







}
?>
