<?php
include_once(APP_PATH . "/application/library/aliyun-php-sdk-core/Config.php");
use Sms\Request\V20160927 as Sms;

function sendVerifyCode($tel,$code,$type = 10)
{
	if(!defined("SITE_VCODE_EXPIRED"))		define("SITE_VCODE_EXPIRED",300);
	if(!defined("VCODE_TYPE_REGISTER"))	define("VCODE_TYPE_REGISTER",10);
	if(!defined("VCODE_TYPE_FORGETPWD"))	define("VCODE_TYPE_FORGETPWD",20);
	
	if($type == VCODE_TYPE_REGISTER)
		$tempID = "SMS_38270039";
	else if($type == VCODE_TYPE_FORGETPWD)
		$tempID = "SMS_38255034";
	else
		return "ERR_VCODE_TYPE";
	
	$life = floor(SITE_VCODE_EXPIRED / 60);
	
	$iClientProfile = DefaultProfile::getProfile("cn-hangzhou", "LTAIwzhx2NGPJUZz", "Exorf2Hhsoxmdj9HsecKBDW2oECqeN");
	$client = new DefaultAcsClient($iClientProfile);
	$request = new Sms\SingleSendSmsRequest();
	$request->setSignName("水运在线");									//签名名称
	$request->setTemplateCode($tempID);									//模板code
	$request->setRecNum($tel);											//目标手机号
	$request->setParamString("{'code':'$code','long':'$life'}");		//模板变量，数字一定要转换为字符串
	try
	{	$response = $client->getAcsResponse($request);
	}
	catch (ClientException $e)
	{	return $e->getErrorMessage(); 
	}
	catch (ServerException $e)
	{	return $e->getErrorMessage();
	}
	return true;
}
?>