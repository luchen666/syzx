<?php

// _init开头
//这些方法都接受一个参数：Yaf_Dispatcher $dispatcher
//调用顺序按照声明顺序

class Bootstrap extends Yaf_Bootstrap_Abstract
{
	public function _initConfig()
	{
		$config = Yaf_Application::app()->getConfig();
		Yaf_Registry::set("config",$config);

		Header("Access-Control-Allow-Origin:*");
	}
	
	//设置网站信息
	public function _initSiteOptions(Yaf_Dispatcher $dispatcher)
	{
		//网站配置
		$config = Yaf_Registry::get("config")->site;
		define("SITE_TITLE",$config->title);
		define("SITE_KEYWORDS",$config->keywords);
		define("SITE_DESCRIPTION",$config->description);
		define("SITE_SHORTNAME",$config->shortname);
		define("SITE_HOMEROWS",$config->homerows);
		define("SITE_VCODE_LENGTH",$config->vcode_length);
		define("SITE_VCODE_EXPIRED",$config->vcode_expired);
		define("SITE_SUPPORT_TELEPHONE",$config->support_telephone);
		
		define("SITE_ADDSCH_MUSTCERTIFIED",$config->addsch_mustcertified);
		define("SITE_ADDSUPPLY_MUSTCERTIFIED",$config->addsupply_mustcertified);
		
		define("SITE_UPLOAD_DIR","images/uploadfiles");		//上传文件保存目录
		define("NEWS_UPLOAD_DIR","images/news");

		define("VCODE_TYPE_REGISTER",10);						//验证码类型：用户注册
		define("VCODE_TYPE_FORGETPWD",20);						//验证码类型：忘记密码
		define("VCODE_TYPE_CHECK",30);							//验证码类型：验证码检查
		
		//安全配置
		$config = Yaf_Registry::get("config")->security;
		define("SECURITY_LIMITLOGINTIME",$config->limitlogintime);
		define("SECURITY_LIMITLOGINTIMES",$config->limitlogintimes);
		define("SECURITY_LOGININTERVAL",$config->logininterval);
		define("SECURITY_ACCESSTOKEN_LIFETIME",$config->accesstokenlifetime);
		
		//APP配置
		$config = Yaf_Registry::get("config")->app;
		
		define("APP_MIN_VERSION",$config->version_min);		//V1.1保留
		
		define("APP_VERSION_MIN",$config->version_min);
		define("APP_VERSION_NOW",$config->version_now);
		define("APP_DOWNURL_ANDROID",$config->downurl_android);
		define("APP_DOWNURL_IOS",$config->downurl_ios);
		define("APP_RECOMMEND_ROWS",$config->recommend_rows);
		define("APP_ROWS_PER_PAGE",$config->rows_per_page);
		define("APP_REFRESH_INTERVAL",$config->refresh_interval);	
		
		//运单状态
		define("INQ_STATE_NONE",0);
		define("INQ_STATE_ORDER",10);
		define("INQ_STATE_DEPOSIT",20);
		define("INQ_STATE_REFUND",30);
		define("INQ_STATE_DONE",50);
		define("INQ_STATE_INVALID",99);
		
		//订单默认支付方式　０：线下支付  １：在线支付
		define("INQ_ONLINEPAY_DEFAULT",0);
		
		//货源与船期的状态
		define("SUPSCH_STATE_NONE",0);
		define("SUPSCH_STATE_ORDER",10);
		define("SUPSCH_STATE_INVALID",99);
		
		//认证状态
		define("CERTIFY_STATE_NONE",0);			//未认证
		define("CERTIFY_STATE_WAIT",10);			//等待认证
		define("CERTIFY_STATE_REJECT",20);			//未通过认证
		define("CERTIFY_STATE_PASS",30); 			//通过认证
		
		//错误编号
		define("ERR_VERSION_NOT_SUPPORT",100);		//版本不支持
		define("ERR_VERSION_UPDATE",101);			//版本可更新
		define("ERR_ACCOUNT_EXISTS",102);			//帐号已存在
		define("ERR_ACCOUNT_NOT_EXISTS",103);		//帐号不存在
		define("ERR_VCODE_ERROR",104);				//验证码错误
		
		define("ERR_SHIPSCH_NOT_EXISTS",201);		//船期不存在
		define("ERR_SUPPLY_NOT_EXISTS",301);		//货源不存在
		define("ERR_INQUIRY_NOT_EXISTS",401);		//订单不存在
		define("ERR_INQUIRY_STATE_CHANGED",402);	//订单状态已改变
		define("ERR_INQUIRY_HAVE_CREATED",403);	//订单已创建
		define("ERR_INQUIRY_NOT_MATCHED",404);		//订单不匹配
		define("ERR_INQUIRY_IN_PAYING",405);		//订单正在支付中
		
		define("ERR_PAY_GETWAY_ERROR",501);		//支付网关错误
		define("ERR_PAY_PASSWORD_ERROR",502);		//支付密码错误
		define("ERR_PAY_PASSWORD_EMPTY",503);		//支付密码不能为空
		define("ERR_PAY_AMOUNT_ERROR",504);		//金额错误
		define("ERR_PAY_BANKACCOUNT_ERROR",505);	//银行帐户错误
		define("ERR_PAY_PARAM_INVALID",506);		//支付过程参数错误
		define("ERR_PAY_BALANCE_INSUFFICIENT",507);//用户余额不足
		define("ERR_PAY_MONEY_ILLEGAL",508);		//金额错误
	}

	//设置smarty引擎
	public function _initSmarty(Yaf_Dispatcher $dispatcher)
	{
		$smarty = new Smarty_Adapter(NULL, Yaf_Application::app()->getConfig()->smarty);
		Yaf_Dispatcher::getInstance()->setView($smarty);
	}
	
	public function _initCloseView(Yaf_Dispatcher $dispatcher)
	{
		Yaf_Dispatcher::getInstance()->disableView();		//关闭自身的自动渲染
	}
		
	//添加配置中的路由 
	public function _initRoute(Yaf_Dispatcher $dispatcher)
	{
		//$config = new Yaf_Config_Ini(APP_PATH . "/conf/route.ini","ROUTES");
		//Yaf_Dispatcher::getInstance()->getRouter()->addConfig($config);
	}
	
	//注册用户插件
	public function _initPlugin(Yaf_Dispatcher $dispatcher)
	{
		//$user = new UserPlugin();
		//$dispatcher->registerPlugin($user);
	}
	
	public function _initDefaultName(Yaf_Dispatcher $dispatcher)
	{
		//$dispatcher->setDefaultModule("Index")->setDefaultController("Index")->setDefaultAction("Index");
	}
}

?>