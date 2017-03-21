<?php
include_once(APP_PATH . "/utils/Utils.php");
include_once(APP_PATH . "/utils/ResponseResult.php");
include_once(MODELS_PATH . "/api2.0/User.php");

//恢复Session在APIBaseController.init中
//$this->Post记录了 json_decode(file_get_contents("php://input"),true);
//$this->LoginUser 记录了当前登录用户，如果未登录，此值为[]

class UserController extends APIBaseController
{
	
	//初始化
	public function InitAction()
	{
		//版本太低，必须升级
		if(!IsSet($_GET["Version"]) || $_GET["Version"] < APP_VERSION_MIN)
		{	
			$result = new ResponseResult(false,"ERR_VERSION_NOT_SUPPORT",ERR_VERSION_NOT_SUPPORT,array("android"=>APP_DOWNURL_ANDROID,"ios"=>APP_DOWNURL_IOS));
			echo $result->AsJSon();
			return;
		}
		
		$ret = User::apiInit();
		
		//添加版本更新信息
		if($_GET["Version"] < APP_VERSION_NOW)	$ret["NEWVERSION"] = array("version"=>APP_VERSION_NOW,"android"=>APP_DOWNURL_ANDROID,"ios"=>APP_DOWNURL_IOS);
		
		$result = new ResponseResult(true,"",0,$ret);
		
		echo $result->AsJSon();
	}

	//根据用户类别获取广告图片
	public function GetSlideImgAction()
	{
		//参数检查
		if(!IsSet($_GET["UserType"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}

		//客户端显示
		if(IsSet($_GET["MySlideID"]) && $_GET["MySlideID"] != "")
			$ids = $_GET["MySlideID"];
		else
			$ids = "0";

		$rs = User::apiGetSlideImage($_GET["UserType"],$ids);
		
		$result = new ResponseResult($rs !== null,"查无数据",0,$rs);
		
		echo $result->AsJSon();
	}
	
	public function GetNewsListAction()
	{
		//拉取哪屏数据
		$act = IsSet($_GET["Action"]) ? $_GET["Action"] : "GT";
		$id =  (IsSet($_GET["LastID"]) && Is_Numeric($_GET["LastID"])) ? $_GET["LastID"] : 0;
		$rows =  (IsSet($_GET["Rows"]) && Is_Numeric($_GET["Rows"])) ? $_GET["Rows"] : 10;
		
		$ret = User::apiGetNewsList($_GET["Action"],$_GET["LastID"],$_GET["Rows"]);
		$result = new ResponseResult($ret !== null,"查无数据",0,$ret);
		echo $result->AsJSon();
	}
	
	public function GetNewsByIDAction()
	{
		if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		
		$ret = User::apiGetNewsByID($_GET["ID"]);
		
		$result = new ResponseResult($ret !== null,"查无数据",0,$ret);
		echo $result->AsJSon();
	}
	
	//客户端自动登录，APP必须先使用这个函数，否则会导致退出登录
	//返回值：用户信息、AccessToken
	public function AutoLoginAction()
	{
		//参数检查
		if( !IsSet($this->Post["RememberKey"]) || $this->Post["RememberKey"] == null || 
			!IsSet($this->Post["UserID"]) || !Is_Numeric($this->Post["UserID"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		
		//与默认恢复的Session不符，清除SESSION
		if(IsSet($_SESSION))
		{	if(Session_ID() != $this->Post["RememberKey"])
			{
				//销毁session，删除session文件，以后不能恢复
				Session_Destroy();
				unset($_SESSION);
			}
		}
		else
		{
			Session_ID($this->Post["RememberKey"]);
			Session_Start();
		}
		
		//已存在已经登录过的SESSION,就自动登录，否则登录失效（可设置Session过期时间控制自动登录时效）
		if(IsSet($_SESSION["User"]["IsLogin"]) && $_SESSION["User"]["IsLogin"] && IsSet($_SESSION["User"]["UserID"]) && $_SESSION["User"]["UserID"] == $this->Post["UserID"])
		{	
			//保存RememberKey（SessionID）的作用是防止用户在一个设备上修改密码或者退出登录后，其它设备还能自动登录的情况。
			//用户修改密码或退出登录后，用户表的RememberKey必须清空，那就不能自动登录了。
			//RememberKey在在LoginAction中当用户使用帐号密码登录时保存				
			$ret = User::apiAutoLogin($this->Post["UserID"],$_SERVER["REMOTE_ADDR"]);
			
			if(Is_Numeric($ret))
			{
				//销毁session，删除session文件，以后不能恢复
				Session_Destroy();
				unset($_SESSION);
				
				$result = new ResponseResult(false,"无效的用户");
				echo $result->AsJSon();
				
				return;
			}
			
			$user = $ret;
			
			if($user["RememberKey"] == $this->Post["RememberKey"])
			{
				//生成AccessToken与Token生成时间
				//APP每四小时用SessionID更新并保存AccessToken
				$_SESSION["User"]["AccessToken"] = makeAccessToken($this->Post["RememberKey"]);
				$_SESSION["User"]["AccessTokenCreateAt"] = time();
				
				$_SESSION["User"]["MobilePhone"] = $user["MobilePhone"];
				
				//获取用户登录地区号，存入Session
				$user["City"] = getClientCity();
				$ret = User::apiGetRegionIDByCity($user["City"]);;
				$user["RegionID"] = $ret["RegionID"];
				$user["ProvinceID"] = $ret["ProvinceID"];
				$_SESSION["User"]["RegionID"] = $ret["RegionID"];
				$_SESSION["User"]["ProvinceID"] = $ret["ProvinceID"];
				
				$user["AccessToken"] = $_SESSION["User"]["AccessToken"];
				
				unset($user["IsDeleted"]);
				unset($user["_RecordCount_"]);
				
				$result = new ResponseResult(true, "", 0, $user);
				echo $result->AsJSon();
				
				return;
			}
		}
		
		$result = new ResponseResult(false,"未发现登录的会话");
		echo $result->AsJSon();
	}

	//生成新的AccessToken后，旧的作废
	public function MakeAccessTokenAction()
	{
		//已登录
		if(IsSet($_SESSION["User"]["IsLogin"]) && $_SESSION["User"]["IsLogin"] && $this->Post["AccessToken"] ==  $_SESSION["User"]["AccessToken"])
		{
			$_SESSION["User"]["AccessToken"] = makeAccessToken($_SESSION["User"]["RememberKey"]);
			$_SESSION["User"]["AccessTokenCreateAt"] = time();

			$result = new ResponseResult(true,"",0,$_SESSION["User"]["AccessToken"]);
			echo $result->AsJSon();
		}
		else
		{
			$result = new ResponseResult(false,"请先登录");
			echo $result->AsJSon();
		}
	}

	//客户端登录，登录后需要保存Session，以便于后续处理
	public function LoginAction()
	{
		$parm = $this->Post;

		//参数检查
		if(!IsSet($parm["UserName"]) || $parm["UserName"] == "" || 
		   !IsSet($parm["Password"]) || $parm["Password"] == "" ||
		   !IsSet($this->Post["Version"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
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

		//如果有session就先清空
		if(IsSet($_SESSION))
		{	session_destroy();
			unset($_SESSION);
			session_start();
		}
		else
		{
			session_start();
		}

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
		
		$rememberKey = session_id();
		$accessTokey = makeAccessToken($rememberKey);
		
		//用户验证
		$ret = User::apiLogin($parm["UserName"],$parm["Password"],$rememberKey,$_SERVER["REMOTE_ADDR"]);

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
			{
				$_SESSION["LoginFailTimes"] = $_SESSION["LoginFailTimes"] + 1;
			}
			//超出限定时间登错，重新计算
			else
			{
				$_SESSION["LoginFailTimes"] = 1;
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
		$user["City"] = getClientCity();
		$ret = User::apiGetRegionIDByCity($user["City"]);
		$user["RegionID"] = $ret["RegionID"];
		$user["ProvinceID"] = $ret["ProvinceID"];
		
		//保存用户登录信息到SESSION
		$_SESSION["User"] = array(
			"IsLogin"				=> 1,
			"UserID"				=> $user["UserID"],
			"Name"					=> $user["Name"],
			"VIP"					=> $user["VIP"],
			"MobilePhone"			=> $user["MobilePhone"],
			"State"					=> $user["State"],
			"CertifyState"			=> $user["CertifyState"],
			"RegionID"				=> $user["RegionID"],
			"ProvinceID"			=> $user["ProvinceID"],
			"AccessToken"			=> $accessTokey,
			"AccessTokenCreateAt"	=> time()
		);
		
		//返回数据处理
		$user["RememberKey"]	= $rememberKey;
		$user["AccessToken"]	= $accessTokey;
		$user["CurrentIP"]		= getClientIP();
		unset($user["Password"]);
		unset($user["Salt"]);
		unset($user["IsDeleted"]);
		unset($user["_RecordCount_"]);
		
		$result = new ResponseResult(true,"",0,$user);
		
		echo $result->AsJSon();
	}
	
	//退出登录
	public function LogoutAction()
	{
		if(IsSet($_SESSION["User"]["UserID"]))	User::apiLogout($_SESSION["User"]["UserID"]);
		
		if(IsSet($_SESSION))
		{	session_destroy();
			unset($_SESSION);
		}
		
		$result = new ResponseResult(true,"");
		echo $result->AsJSon();
	}
	
	//获取用户信息
	public function GetUserAction()
	{
		//参数检查
		if( !IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]) ||
			!IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}

		$rs = User::apiGetUser($_GET["ID"]);
		
		if(IsSet($rs["Password"]))			unset($rs["Password"]);
		if(IsSet($rs["Salt"]))				unset($rs["Salt"]);
		if(IsSet($rs["IsDeleted"]))		unset($rs["IsDeleted"]);
		if(IsSet($rs["_RecordCount_"]))	unset($rs["_RecordCount_"]);
		
		//不是当前登录用户查自己的详细信息，避免信息泄漏
		if(!$this->verifyUser($_GET["UserID"],false) || $_GET["ID"] != $_GET["UserID"])
		{
			if(IsSet($rs["IDImage"]))				unset($rs["IDImage"]);
			if(IsSet($rs["CodeCertificate"]))		unset($rs["CodeCertificate"]);
			if(IsSet($rs["BusinessLicenses"]))		unset($rs["BusinessLicenses"]);
			if(IsSet($rs["TaxRegistration"]))		unset($rs["TaxRegistration"]);
		}	

		$result = new ResponseResult($rs !== null,"查无数据",0,$rs);
		echo $result->AsJSon();
	}
	
	//保存用户信息
	public function SetUserAction()
	{
		$parms = $this->Post;
		
		//参数检查
		if( !IsSet($parms["UserID"]) || !Is_Numeric($parms["UserID"]) || 
			!IsSet($parms["Name"]) || !IsSet($parms["Sex"]) || 
			!IsSet($parms["CompanyID"]) || !IsSet($parms["CompanyName"]) || 
			!IsSet($parms["Duty"]) || !IsSet($parms["Address"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($parms["UserID"]);
		
		$ret = User::apiSetUser($parms);
		$result = new ResponseResult($ret === true,$ret,0,$ret);
		echo $result->AsJSon();
	}
	
	//用户修改密码
	public function ChangePwdAction()
	{
		//参数检查
		if(!(IsSet($this->Post["UserID"]) && Is_Numeric( $this->Post["UserID"])) || 
		   !IsSet($this->Post["OldPwd"]) || !IsSet($this->Post["NewPwd"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($this->Post["UserID"]);
		
		$userID = $this->Post["UserID"];
		$oldPwd = $this->Post["OldPwd"];
		$newPwd = $this->Post["NewPwd"];
		
		//校验旧密码
		$rs = User::apiGetUser($userID);
		$pwd = md5($oldPwd . $rs["Salt"]);
		if($rs["Password"] != $pwd)
		{
			$result = new ResponseResult(false,"旧密码错误");
			echo $result->AsJSon();
			return;
		}
		
		$ret = User::apiChangePwd($userID,"",$newPwd);
		$result = new ResponseResult($ret === true ,$ret);
		echo $result->AsJSon();
	}
	
	//忘记密码
	public function ForgetPwdAction()
	{
		//参数检查
		if(!IsSet($this->Post["MobilePhone"]) || !Is_Numeric( $this->Post["MobilePhone"]) || 
		   !IsSet($this->Post["VCode"]) || !Is_Numeric( $this->Post["VCode"]) || !IsSet($this->Post["Password"])) 
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		
		//================== 验证码检查 ===================
		$ret = User::apiGetVCode($this->Post["MobilePhone"]);
		if($ret === null || $ret["Code"] != $this->Post["VCode"] || $ret["Long"] > SITE_VCODE_EXPIRED)
		{
			$result = new ResponseResult(false,"错误的验证码。");
			echo $result->AsJSon();
			return;
		}
		
		$ret = User::apiChangePwd(0,$this->Post["MobilePhone"],$this->Post["Password"]);
		$result = new ResponseResult($ret === true ,$ret);
		echo $result->AsJSon();
	}
	
	
	//判断用户帐号是否存在
	public function IsExistsAction()
	{
		//参数检查
		if(!IsSet($_GET["Account"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$ret = User::apiGetUser(0,$_GET["Account"]);
		$result = new ResponseResult(true,"",0,$ret !== null);
		echo $result->AsJSon();
	}
	
	//生成验证码
	public function VCodeAction()
	{
		//参数检查
		if(!IsSet($_GET["Tel"]) || !IsSet($_GET["Type"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		
		//生成验证码
		$vcode = MakeRand(SITE_VCODE_LENGTH,"NUMBER");
		
		//发送验证码短信
		$ret = User::apiSendVCode($_GET["Tel"],$vcode,$_GET["Type"]);
		
		//如果存在session，就把验证码保存到session中
		//if($ret)	if(IsSet($_SESSION))	$_SESSION["VCode"] = Array("Code" => $vcode,"Time" => getTickCount());
			
		$result = new ResponseResult($ret === true,$ret,0,"");
		echo $result->AsJSon();
	}
	
	//注册用户
	public function RegisterAction()
	{	
		
		//参数检查
		if(!IsSet($this->Post["MobilePhone"]) || !IsSet($this->Post["Password"]) || !IsSet($this->Post["VCode"]) ||
			!IsSet($this->Post["UserType"]) || !in_array($this->Post["UserType"],array("CD","HZ","DL"))
		  )
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		
		//================== 验证码检查 ===================
		$ret = User::apiGetVCode($this->Post["MobilePhone"]);
		if($ret === null || $ret["Code"] != $this->Post["VCode"] || $ret["Long"] > SITE_VCODE_EXPIRED)
		{
			$result = new ResponseResult(false,"错误的验证码。");
			echo $result->AsJSon();
			return;
		}
		
		//保存注册，返回用户编号
		$ret = User::apiRegister($this->Post["MobilePhone"],$this->Post["Password"],$this->Post["UserType"]);
		$result = new ResponseResult(is_Numeric($ret),$ret,0,$ret);
		echo $result->AsJSon();
	}

	//获取地区与港口
	public function GetRegionPortAction()
	{
		//参数检查
		if( (!IsSet($_GET["Need"]) || $_GET["Need"] == "" || 
			($_GET["Need"] != "PROVINCE" && $_GET["Need"] != "CITY" && $_GET["Need"] != "PORT")) ||
			!IsSet($_GET["ID"])  || !Is_Numeric($_GET["ID"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}

		$rs = null;

		//获取省份
		if($_GET["Need"] == "PROVINCE")		$rs = User::apiGetRegions(0);
		else if($_GET["Need"] == "CITY")	$rs = User::apiGetRegions($_GET["ID"]);
		else if($_GET["Need"] == "PORT")	$rs = User::apiGetPorts($_GET["ID"],IsSet($_GET["Range"]) ? $_GET["Range"] : "CITY");

		$result = new ResponseResult($rs !== null,"查无数据",0,$rs);
		echo $result->AsJSon();
	}

	//保存港口
	public function SetPortAction()
	{
		$parm = $this->Post;

		//参数检查
		if( !IsSet($parm["PortName"]) || !IsSet($parm["UserName"])      ||
			!IsSet($parm["UserID"]) || !Is_Numeric($parm["UserID"])     ||
			!IsSet($parm["RegionID"]) || !Is_Numeric($parm["RegionID"]) ||
			!IsSet($parm["ProvinceID"]) || !Is_Numeric($parm["ProvinceID"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}

		$id = User::apiSetPort($parm);
		$result = new ResponseResult(Is_Numeric($id),$id,0,$id);
		echo $result->AsJSon();
	}
	
	//意见列表
	public function GetSuggestAction()
	{
		if(!IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		//登录检查
		$this->verifyUser($_GET["UserID"]);
		
		//拉取哪屏数据
		$act = IsSet($_GET["Action"]) ? $_GET["Action"] : "GT";
		$id = (IsSet($_GET["LastID"]) && Is_Numeric($_GET["LastID"])) ? $_GET["LastID"] : 0;
		$rows = (IsSet($_GET["Rows"]) && Is_Numeric($_GET["Rows"])) ? $_GET["Rows"] : 10;
		
		//货源
		$rs = User::GetList($act, $id, $rows,null,"USR_Suggest");
		$result = new ResponseResult($rs !== null,"查无数据",0,$rs);
		echo $result->AsJson();
	}
	
	//保存意见
	public function SetSuggestAction()
	{
		if(!IsSet($this->Post["UserID"]) || !Is_Numeric($this->Post["UserID"]) ||
			!IsSet($this->Post["Body"]) || $this->Post["Body"] == null)
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($this->Post["UserID"]);
		
		$parms = array(
			"Body"	 		=> $this->Post["Body"],
			"UserID"		=> $this->Post["UserID"],
			"IsRead"		=> 0,
			"CreateUserID"	=> $this->Post["UserID"],
			"CreateDate"	=> Date("y-m-d H:i:s",Time())
		);
		$ret = User::Save($parms,"usr_Suggest");
		$result = new ResponseResult($ret === true,"不能添加");
		echo $result->AsJSon();
	}
	
	//保存意见
	public function SetSuggestReadAction()
	{
		if(!IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
		
		$ret = User::apiSetSuggestRead($_GET["UserID"]);
		$result = new ResponseResult($ret === true,$ret);
		echo $result->AsJSon();
	}
	
	//获取用户未读信息
	public function GetUnReadAction()
	{
		//参数检查
		if(!IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
		
		$ret = User::apiGetUnRead($_GET["UserID"]);
		$result = new ResponseResult($ret !== null,"查无数据",0,$ret);
		echo $result->AsJSon();
	}
}
?>