<?php
//header("Content-type: text/html; charset=utf-8");
include_once(APP_PATH . "/utils/Utils.php");
include_once(APP_PATH . "/utils/ResponseResult.php");
include_once(MODELS_PATH . "/admin/Account.php");
include_once(MODELS_PATH . "/admin/User.php");
include_once(MODELS_PATH . "/admin/Ship.php");
include_once(MODELS_PATH . "/admin/Shipsch.php");
include_once(MODELS_PATH . "/admin/Company.php");
include_once(MODELS_PATH . "/admin/Supply.php");
include_once(MODELS_PATH . "/admin/Port.php");
include_once(MODELS_PATH . "/admin/Login.php");
include_once(MODELS_PATH . "/admin/Comment.php");
include_once(MODELS_PATH . "/admin/Inquiry.php");
include_once(MODELS_PATH . "/admin/News.php");



include_once("common.php");

class homeController extends BaseController
{
	public function indexAction()
	{
		global $CurrentUser;
		$this->getView()->assign("User",json_encode($CurrentUser));
		$this->getView()->Display(ADMIN_VIEW_PATH  . "index.html");
	}

	//首页
	public function homeAction()
    {	$this->getView()->Display(ADMIN_VIEW_PATH  . "home.html");
    }

	//////////////////////////////////交易管理/////////////////////////////////////////////////
	//订单列表
	public function InquiryAction()
	{
		$Inquiry=Inquiry::getInquiry();
		$Inquiry = json_encode($Inquiry);

		$this->getView()->assign("Inquiry", $Inquiry);
		$this->getView()->assign("ROWS_PER_PAGE",ROWS_PER_PAGE);
		$this->getView()->Display(ADMIN_VIEW_PATH  . "Inquiry.html");
	}
	public function getInquiryAction()
	{
		$page = IsSet($_GET["page"]) && Is_Numeric($_GET["page"]) ? $_GET["page"] : 1;
		$ret=Inquiry::getInquiry($page,ROWS_PER_PAGE);
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
	}
	//查看新订单数量
	public function getNewInquiryAction()
	{
		$Inquiry=Inquiry::getNewInquiry();
		$Inquiry = json_encode($Inquiry);
		echo $Inquiry;
	}
	//查各状态订单数量
	public function getInquiryNumberAction()
	{
		$Inquiry=Inquiry::getInquiryNumber();
		$Inquiry = json_encode($Inquiry);
		echo $Inquiry;
	}
	//根据ID获取对应的数据
	public function getoneInquiryAction()
	{
		if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$ret = Inquiry::getoneInquiry($_GET["ID"]);

		$rs = Account::apiGetoneInquiry($_GET["ID"]);

		$result = new ResponseResult($rs !== null,"查无数据",0,$rs);

		echo $result->AsJSon();
	}

	//===================资金池流水视图管理==================
	public function poolbillAction()
	{	$v = $this->getView();
		$v->Assign("poolbillNum",Inquiry::getUserbillCount());
		$v->Assign("ROWS_PER_PAGE",ROWS_PER_PAGE);
		$v->Display(ADMIN_VIEW_PATH . "Poolbill.html");
	}
	//从pay_poolbill 表中获取数据
	public function getpoolbillAction()
	{
		$page = IsSet($_GET["page"]) && Is_Numeric($_GET["page"]) ? $_GET["page"]: 1;
		$ret = Inquiry::getPoolbill($page,ROWS_PER_PAGE,$_GET);
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJSon();
	}

//	//资金流水列表
//	public function poolbillAction()
//	{
//		$poolbill=Inquiry::getpoolbill();
//		$poolbill = json_encode($poolbill);
//
//		$this->getView()->assign("poolbill", $poolbill);
//		$this->getView()->Display(ADMIN_VIEW_PATH  . "poolbill.html");
//	}
//	public function getpoolbillAction()
//	{
//		$poolbill=Inquiry::getpoolbill();
//		$poolbill = json_encode($poolbill);
//		echo $poolbill;
//	}
	//====================客户流水====================
	public function UserbillAction()
	{
		$v = $this->getView();
		$v->Assign("UserbillNum",Inquiry::getUserbillCount());
		$v->Assign("ROWS_PER_PAGE",ROWS_PER_PAGE);
		$v->Display(ADMIN_VIEW_PATH . "Userbill.html");
	}
	//从pay_poolbill 表中获取数据
	public function getUserbillAction()
	{
		$page = IsSet($_GET["page"]) && Is_Numeric($_GET["page"]) ? $_GET["page"]: 1;
		$ret = Inquiry::getUserbill($page,ROWS_PER_PAGE,$_GET);
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJSon();
	}
	//根据ID获取对应的数据
//	public function getonepoolbillAction()
//	{
//		if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
//		{	$result = new ResponseResult(false,"参数有误");
//			echo $result->AsJSon();
//			return;
//		}
//		$ret = poolbill::getonepoolbill($_GET["ID"]);
//
//		$rs = Account::apiGetonepoolbill($_GET["ID"]);
//
//		$result = new ResponseResult($rs !== null,"查无数据",0,$rs);
//
//		echo $result->AsJSon();
//	}
	//客户流水
//	public function UserbillAction()
//	{
//		$Userbill=Inquiry::getUserbill();
//		$Userbill = json_encode($Userbill);
//
//		$this->getView()->assign("Userbill", $Userbill);
//		$this->getView()->Display(ADMIN_VIEW_PATH  . "Userbill.html");
//	}
//	public function getUserbillAction()
//	{
//		$Userbill=Inquiry::getUserbill();
//		$Userbill = json_encode($Userbill);
//		echo $Userbill;
//	}
	//=====================网关日志====================
	public function RequestlogAction()
	{
		$v = $this->getView();
		$v->Assign("logNum",Inquiry::getReqLogCount());
		$v->Assign("ROWS_PER_PAGE",ROWS_PER_PAGE);
		$v->Display(ADMIN_VIEW_PATH . "Requestlog.html");
	}
	public function getRequestlogAction()
	{
		$page = IsSet($_GET["page"]) && Is_Numeric($_GET["page"]) ? $_GET["page"]: 1;
		$ret = Inquiry::getRequestlog($page,ROWS_PER_PAGE,$_GET);
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJSon();
	}
//	//网关日志
//	public function RequestlogAction()
//	{
//		$Requestlog=Inquiry::getRequestlog();
//		$Requestlog = json_encode($Requestlog);
//
//		$this->getView()->assign("Requestlog", $Requestlog);
//		$this->getView()->Display(ADMIN_VIEW_PATH  . "Requestlog.html");
//	}
//	public function getRequestlogAction()
//	{
//		$Requestlog=Inquiry::getRequestlog();
//		$Requestlog = json_encode($Requestlog);
//		echo $Requestlog;
//	}
	//============交易通知===============
	public function ResponselogAction()
	{
		$v = $this->getView();
		$v->Assign("reslogNum",Inquiry::getReslogCount());
		$v->Assign("ROWS_PER_PAGE",ROWS_PER_PAGE);
		$v->Display(ADMIN_VIEW_PATH . "Responselog.html");
	}
	public function getReslogAction()
	{
		$page = IsSet($_GET["page"]) && Is_Numeric($_GET["page"]) ? $_GET["page"]: 1;
		$ret = Inquiry::getReslog($page,ROWS_PER_PAGE);
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJSon();
	}
    ///////////////////////////////////////用户管理/////////////////////////////////////////
	//用户列表
	//================= 用户列表视图管理 ======================
	public function UsersAction()
	{   $v = $this->getView();
		$v->Assign("UsersNum",User::getAllUsersNum());
		$v->Assign("ROWS_PER_PAGE",ROWS_PER_PAGE);
		$v->Display(ADMIN_VIEW_PATH . "Users.html");
	}
//	public function UsersAction()
//	{
//	    //echo "IsLogin的值为：".$_SESSION["IsLogin"];
//
//		$user=User::getUsers();
//		$user = json_encode($user);
//
//		$this->getView()->assign("user", $user);
//		$this->getView()->Display(ADMIN_VIEW_PATH  . "Users.html");
//	}

//	public function getUsersAction()
//    {
//        $user=User::getUsers();
//        $user = json_encode($user);
//        echo $user;
//    }
	//查新增用户数量
	public function getNewUserAction()
	{
		$user=User::getNewUser();
		$user = json_encode($user);
		echo $user;
	}
	//查各状态用户数量
	public function getUserNumberAction()
	{
		$UserCount=User::getUserNumber();
		$UserCount = json_encode($UserCount);
		echo $UserCount;
	}
	//根据UserID获取对应的数据
	public function GetUserAction()
	{
		if(!IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$ret = User::getUser($_GET["UserID"]);

		$rs = Account::apiGetUser($_GET["UserID"]);

		$result = new ResponseResult($rs !== null,"查无数据",0,$rs);

		echo $result->AsJSon();
	}
	//船舶列表 
	public function getShipAction()
	{
		$shiplist=Ship::getShip();
		$shiplist = json_encode($shiplist);
		echo $shiplist;
	}
	//查新增船舶数量
	public function getNewShipAction()
	{
		$shiplist=Ship::getNewShip();
		$shiplist = json_encode($shiplist);
		echo $shiplist;
	}
	//查各状态船舶数量
	public function getShipNumberAction()
	{
		$shipCount=Ship::getShipNumber();
		$shipCount = json_encode($shipCount);
		echo $shipCount;
	}
	//==========================船舶管理视图=======================
	public function ShipsAction()
	{
		$v = $this->getView();
		$v->Assign("ShipNumber",Ship::getAllShipNumber());
		$v->Assign("ROWS_PER_PAGE",ROWS_PER_PAGE);
		$v->Display(ADMIN_VIEW_PATH  . "ship.htm");
	}

	//根据船舶ID获取对应数据
	public function getoneShipAction()
	{
		if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}

		$ret = Ship::getoneShip($_GET["ID"]);

		$rs = Account::apiGetoneShip($_GET["ID"]);

		$result = new ResponseResult($rs !== null,"查无数据",0,$rs);

		echo $result->AsJSon();
	}
	//==================公司列表视图管理===============
	public function CompaniesAction()
	{   $v = $this->getView();
		$v->Assign("companyNum",Company::getAllCompaniesNum());
		$v->Assign("ROWS_PER_PAGE",ROWS_PER_PAGE);
		$v->Display(ADMIN_VIEW_PATH . "Companies.html");
	}

	//查新增公司数量
	public function getNewCompanyAction()
	{
		$companylist=Company::getNewCompany();
		$companylist = json_encode($companylist);
		echo $companylist;
	}
	//查各状态公司数量
	public function getCompanyNumberAction()
	{
		$CompanyCount=Company::getCompanyNumber();
		$CompanyCount = json_encode($CompanyCount);
		echo $CompanyCount;
	}
	//从公司列表中选取一家公司的记录
	public function getCompanyAction()
	{
		if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$ret = Company::getCompany($_GET["ID"]);

		$rs = Account::apiGetCompany($_GET["ID"]);

		$result = new ResponseResult($rs !== null,"查无数据",0,$rs);

		echo $result->AsJSon();
	}

	///////////////////////////////////////船舶管理/////////////////////////////////////////
	//船期ShipSch管理
//	public function ShipschAction()
//	{
//		$shipschlist=Shipsch::getShipSch();
//		$shipschlist = json_encode($shipschlist);
//
//		$this->getView()->assign("shipschlist", $shipschlist);
//		$this->getView()->Display(ADMIN_VIEW_PATH  . "Shipsch.html");
//	}
	public function ShipschAction()
	{
		$shipschlist=Shipsch::getShipSch();
		$shipschlist = json_encode($shipschlist);

		$this->getView()->assign("shipschlist", $shipschlist);
		$this->getView()->assign("ROWS_PER_PAGE",ROWS_PER_PAGE);
		$this->getView()->Display(ADMIN_VIEW_PATH  . "shipSch.htm");
	}
	public function getShipschAction()
    {
		$page = IsSet($_GET["page"]) && Is_Numeric($_GET["page"]) ? $_GET["page"] : 1;
        $ret=Shipsch::getShipSch($page,ROWS_PER_PAGE);
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
        //$shipschlist = json_encode($shipschlist);
        //echo $shipschlist;
    }
	//总船期
	public function getAllShipschCountAction()
	{
		$shipschlist=Shipsch::getAllShipschCount();
		$shipschlist = json_encode($shipschlist);
		echo $shipschlist;
	}
	// 新增船期
	public function getnewShipschCountAction()
	{
		$shipschlist=Shipsch::getnewShipschCount();
		$shipschlist = json_encode($shipschlist);
		echo $shipschlist;
	}
	// 可抢船期
	public function getShipschCountAction()
	{
		$shipschlist=Shipsch::getShipschCount();
		$shipschlist = json_encode($shipschlist);
		echo $shipschlist;
	}
	//从船期表ShipSch中读取一条数据显示
    public function GetOneShipSchAction()
    {
        if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
        {	$result = new ResponseResult(false,"参数有误");
            echo $result->AsJSon();
            return;
        }

        $ret = Shipsch::getOneShipSch($_GET["ID"]);

        //$rs = Account::apiGetoneShipSch($_GET["ID"]);

        $result = new ResponseResult($ret !== null,"查无数据",0,$ret);

        echo $result->AsJSon();
    }
	//从港口port表中获取信息
//	public function PortAction()
//	{
//		$Port=Port::getPort();
//		$Port = json_encode($Port);
//
//		$this->getView()->assign("Port", $Port);
//		$this->getView()->Display(ADMIN_VIEW_PATH  . "Port.html");
//	}
//	public function getPortAction()
//    {
//        $Port=Port::getPort();
//        $Port = json_encode($Port);
//        echo $Port;
//    }

    //从港口port表中获取一条详细信息
    public function getonePortAction()
    {
        if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
        {	$result = new ResponseResult(false,"参数有误");
            echo $result->AsJSon();
            return;
        }
        $ret = Port::getonePort($_GET["ID"]);

        $rs = Account::apigetonePort($_GET["ID"]);

        $result = new ResponseResult($rs !== null,"查无数据",0,$rs);

        echo $result->AsJSon();
    }

	//从Supply2表中获取数据
	public function supplyAction()
	{
		$Supply=Supply::getSupply();
		$Supply = json_encode($Supply);

		$this->getView()->assign("Supply", $Supply);
		$this->getView()->assign("ROWS_PER_PAGE",ROWS_PER_PAGE);
		$this->getView()->Display(ADMIN_VIEW_PATH  . "Supply.html");
	}
	public function getSupplyAction()
	{
		$page = IsSet($_GET["page"]) && Is_Numeric($_GET["page"]) ? $_GET["page"] : 1;
		$ret=Supply::getSupply($page,ROWS_PER_PAGE);
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
	}
//    public function getSupplyAction()
//    {
//        $Supply=Supply::getSupply();
//        $Supply = json_encode($Supply);
//        echo $Supply;
//    }
	//总货盘数量
	public function getAllSupplyCountAction()
	{
		$Supply=Supply::getAllSupplyCount();
		$Supply = json_encode($Supply);
		echo $Supply;
	}
	//新增货盘数量
	public function getNewSupplyCountAction()
	{
		$Supply=Supply::getNewSupplyCount();
		$Supply = json_encode($Supply);
		echo $Supply;
	}
	//可抢单货盘数量
	public function getSupplyCountAction()
	{
		$Supply=Supply::getSupplyCount();
		$Supply = json_encode($Supply);
		echo $Supply;
	}
    //从表Supply中读取一条数据显示
    public function GetoneSupplyAction()
    {
        if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
        {	$result = new ResponseResult(false,"参数有误");
            echo $result->AsJSon();
            return;
        }

        $ret = Supply::GetoneSupply($_GET["ID"]);

        $rs = Account::apiGetoneSupply($_GET["ID"]);

        $result = new ResponseResult($rs !== null,"查无数据",0,$rs);

        echo $result->AsJSon();
    }
	//////////////////////////////////////网站管理/////////////////////////////////////////
	//回复列表
	public function ReplyAction()
	{
		$this->getView()->Display(ADMIN_VIEW_PATH  . "Reply.html");
	}
	//查看所有评论
	public function getCommentsAction()
	{
		$commentlist=Comment::getComments();
		$commentlist = json_encode($commentlist);
		echo $commentlist;
	}
	//查看未回复的评论
	public function getNoAnsweredCommentsAction()
	{
		$commentlist=Comment::getNoAnsweredComments();
		$commentlist = json_encode($commentlist);
		echo $commentlist;
	}
	//查看未回复的评论数量
	public function getNoAnsweredCommentsCountAction()
	{
		$commentCount=Comment::getNoAnsweredCommentsCount();
		$commentCount = json_encode($commentCount);
		echo $commentCount;
	}
	//查看一个人的评论记录
	public function getonedialogAction()
	{
		$commentlist=Comment::getonedialog($_GET["UserID"]);
		$commentlist = json_encode($commentlist);
		echo $commentlist;
	}
	///////////////////////////////////////登出/////////////////////////////////////////
	//安全退出
	public function loginAction()
	{
		unset($_SESSION['IsLogin']);
	    //不能出现 一出现就报错$this->getView()->assign("Login", $Login);
		$this->getView()->Display(ADMIN_VIEW_PATH  . "login.html");
	}

}
?>