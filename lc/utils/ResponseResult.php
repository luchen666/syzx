<?php
/*
 *	类名：ResponseResult
 *
 *	功用：返回到客户端的Json结构体
 *	编写：谢忠杰
 */

class ResponseResult
{
	// 结果状态,默认值false
	public $success;

	/// 消息 默认值空字符串
	public $message;

	/// 详细消息 默认值空字符串
	public $messagedetail;

	/// 代码 默认值0
	public $code;

	/// 返回结果对象,默认值Null
	public $data;

	function __construct($success = false, $message = "", $code = 0,$data = null)
	{
		//switch(function_num_args())
		$this->success = $success;
		$this->message = ($code > 0 || !Is_Numeric($message)) ? $message : "";
		$this->code = ($code == 0 && Is_Numeric($message)) ? $message : $code;
		$this->data = $data;
	}
	
	public function AsJSon()
	{
		$ret = sprintf("{\"success\":%s,\"message\":\"$this->message\",\"code\":$this->code,\"data\":%s}",$this->success ? "true" : "false",json_encode($this->data,JSON_UNESCAPED_UNICODE));
		return $ret;
	}
}

class BaseController extends Yaf_Controller_Abstract
{
	protected $Post;
	protected $LoginUser;

	//由于Yaf_Controller_Abstract的构造函数不能重载，init方法是Yaf_Controller_Abstract的隐含成员函数，用于初始化时会自动执行
	public function init()
	{
		$this->Post = (array)json_decode(file_get_contents("php://input"));
	}
	
	/*
	//检查参数的合法性与完整性
	public function verifyParam($matchs,$parms)
	{
		$pass = true;
		
		foreach($matchs as $item)
		{
			if(($item["Required"] && !isset($parms[$item.name])) || ($item["Type"] == "Number" && isset($parms[$item.name]) && Is_Numeric($parms[$item.name]))）
			{	$pass = false;
				break;
			}
		}
		
		if(!$pass)
		{	$result = new ResponseResult(false,"不能这样操作。");
			echo $result->AsJSon();
			exit;
		}
	}
	*/
	
	//检查操作的用户是否已登录。$ErrStop=true，未登示用户停止执行
	public function checkLogin()
	{	session_start();
		return (!IsSet($_SESSION["User"]) || !IsSet($_SESSION["User"]["IsLogin"]) || $_SESSION["User"]["IsLogin"] != 1) == false;
	}
	
	//检查操作的用户是否已登录。$ErrStop=true，未登示用户停止执行
	public function verifyUser($UserID = 0,$ErrStop = true)
	{	if(!IsSet($_SESSION["User"]) || !IsSet($_SESSION["User"]["IsLogin"]) || $_SESSION["User"]["IsLogin"] == 0 || ($UserID != 0 && $UserID != $_SESSION["User"]["UserID"]))
		{	if($ErrStop)
			{	$result = new ResponseResult(false,"不能这样操作");
				echo $result->AsJSon();
				exit;
			}
			return false;	
		}
		if(IsSet($_SESSION["User"]))	$this->LoginUser =  $_SESSION["User"];
		return true;
	}
}

//API Controller基类放在这里的原因是这个文件每个controller都要引用，不必再多引用一个文件
class APIBaseController extends BaseController
{
	//自动恢复Session
	public function init()
	{
		$this->Post = json_decode(file_get_contents("php://input"),true);

		if(!IsSet($_GET["RememberKey"]) || !IsSet($_GET["AccessToken"]))	return;
		
		//session不一样，销毁
		if (IsSet($_SESSION) && Session_ID() != $_GET["RememberKey"])
		{
			session_destroy();
			unset($_SESSION);
			return;
		}
		
		//先恢复Session,再从SESSION中检查AccessTokey;
		Session_ID($_GET["RememberKey"]);
		Session_Start();
		
		/*
		//自动登录时，不检查AccessToken
		$url = explode("?",strtolower($_SERVER['REQUEST_URI']))[0];
		if($url == "/api/account/autologin")	return;
		*/
		
		//如果AccessToken不符，不能恢复Session
		if(!IsSet($_SESSION["User"]["AccessToken"]) ||$_GET["AccessToken"] != $_SESSION["User"]["AccessToken"] || 
			 !IsSet($_SESSION["User"]["AccessTokenCreateAt"]) || time() - $_SESSION["User"]["AccessTokenCreateAt"] >= SECURITY_ACCESSTOKEN_LIFETIME * 3600)
		{
			session_destroy();
			unset($_SESSION);
			return;
		}

		$this->LoginUser = $_SESSION["User"];
	}
}
?>