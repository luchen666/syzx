<?php
include_once(APP_PATH . "/utils/ResponseResult.php");
include_once(MODELS_PATH . "/api2.0/Supply.php");
include_once(MODELS_PATH . "/api2.0/Account.php");

class SupplyController extends APIBaseController
{

	//获取货源列表
	public function ListAction()
	{
		//拉取哪屏数据
		$act = IsSet($_GET["Action"]) ? $_GET["Action"] : "GT";
		$id =  (IsSet($_GET["LastID"]) && Is_Numeric($_GET["LastID"])) ? $_GET["LastID"] : 0;
		$rows =  (IsSet($_GET["Rows"]) && Is_Numeric($_GET["Rows"])) ? $_GET["Rows"] : 10;
		
		//货源
		$rs = Supply::apiGetList($act,$id,$rows,$this->Post);

		$result = new ResponseResult($rs !== null,"查无数据",0,$rs);
		echo $result->AsJson();
	}
	
	//根据ID获取货源信息
	public function GetAction()
	{
		//参数检查
		if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]) || !IsSet($_GET["IgnoreHit"]) || !Is_Numeric($_GET["IgnoreHit"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$ret = Supply::apiGetByID($_GET["ID"],$_GET["IgnoreHit"]);
		$result = new ResponseResult($ret !== null,"查无数据",0,$ret);
		echo $result->AsJSon();
	}
	
	//根据船期获取用户匹配货源
	public function MatchAction()
	{
		//参数与登录检查
		if(!(IsSet($this->Post["PortID"]) && Is_Numeric($this->Post["PortID"])) ||
		   !(IsSet($this->Post["UserID"]) && Is_Numeric($this->Post["UserID"])) ||
		   !(IsSet($this->Post["SeaOrRiver"]) && Is_Numeric($this->Post["SeaOrRiver"])) ||
		   !IsSet($this->Post["Date"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($this->Post["UserID"]);
		
		//do
		$ret = Supply::apiGetMatch($this->Post);
		$result = new ResponseResult($ret !== null,"查无数据",0,$ret);
		echo $result->AsJson();
	}
	
	//推荐的货源
	public function RecommendAction()
	{	
		include_once(APP_PATH . "/utils/utils.php");
		include_once(MODELS_PATH . "/api2.0/User.php");
		
		//登录用户使用他自己的RegionID，减轻服务器压力
		if(IsSet($_SESSION["User"]["IsLogin"]) && $_SESSION["User"]["IsLogin"] == 1)
		{
			$regionID = $_SESSION["User"]["RegionID"];
		}
		else
		{
			$city = getClientCity();
			$ret = User::apiGetRegionIDByCity($city);
			$regionID = $ret["RegionID"];
		}
		
		$ret = Supply::apiGetRecommend($regionID);
		$result = new ResponseResult($ret !== null,"查无数据",0,$ret);
		echo $result->AsJson();
	}
	
	//我最后的货源
	public function MyLastAction()
	{
		if(!IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
		
		$ret = Supply::apiMyLast($_GET["UserID"]);
		$result = new ResponseResult($ret !== null,"查无数据",0,$ret);
		echo $result->AsJSon();
	}
	
	//我的货源列表
	public function MyListAction()
	{
		if(!IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
		
		//拉取哪屏数据
		$act = IsSet($_GET["Action"]) ? $_GET["Action"] : "GT";
		$id =  (IsSet($_GET["LastID"]) && Is_Numeric($_GET["LastID"])) ? $_GET["LastID"] : 0;
		$rows =  (IsSet($_GET["Rows"]) && Is_Numeric($_GET["Rows"])) ? $_GET["Rows"] : 10;
		
		//货源
		$rs = Supply::apiGetList($act,$id,$rows,Array("UserID" => $_GET["UserID"]));
		$result = new ResponseResult($rs !== null,"查无数据",0,$rs);
		echo $result->AsJson();
	}
	
	//增改货源
	public function SaveAction()
	{	$parm = $this->Post;
		//参数检查
		if( !IsSet($parm["ID"]) || !Is_Numeric($parm["ID"]) || !IsSet($parm["UserID"]) || !Is_Numeric($parm["UserID"]) || !IsSet($parm["SeaOrRiver"]) || !Is_Numeric($parm["SeaOrRiver"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($parm["UserID"]);
		
		$p = array(
			"ID"			=> $parm["ID"],
			"UserID"		=> $this->LoginUser["UserID"],
			"UserName"		=> $this->LoginUser["Name"],
			"SeaOrRiver"	=> $parm["SeaOrRiver"],
			"FromPortID"	=> $parm["FromPortID"],
			"FromPortName"	=> $parm["FromPortName"],
			"ToPortID"		=> $parm["ToPortID"],
			"ToPortName"	=> $parm["ToPortName"],
			"LoadDateFrom"	=> $parm["LoadDateFrom"],
			"LoadDateTo"	=> $parm["LoadDateTo"],
			"Qty"			=> $parm["Qty"],
			"QtyDeviation"	=> $parm["QtyDeviation"],
			"GoodsName"		=> $parm["GoodsName"],
			"PackageMethod"	=> $parm["PackageMethod"],
			"PaymentMethod"	=> $parm["PaymentMethod"],
			"Price"			=> $parm["Price"],
			"TaxInclusive"	=> $parm["TaxInclusive"],
			"Deposit"		=> $parm["Deposit"],
			"LoadRatio"		=> $parm["LoadRatio"],
			"NeedAgent"		=> $parm["NeedAgent"],
			"Memo"			=> $parm["Memo"]
		);
		if(IsSet($parm["ShipSchID"]) && Is_Numeric($parm["ShipSchID"]) && $parm["ShipSchID"] > 0)	$p["ShipSchID"] = $parm["ShipSchID"];
		
		$ret = Supply::apiSave($p);
		$result = new ResponseResult(Is_Numeric($ret),$ret,0,$ret);
		echo $result->AsJson();
	}
	
	//删除货源
	public function DeleteAction()
	{
		//$ID参数检查
		$IDValid = true;
		if(IsSet($_GET["ID"]))
		{
			$aID = explode(",",$_GET["ID"]);
			foreach($aID as $v)		if(!Is_Numeric($v))	$IDValid = false;
		}
		if( !IsSet($_GET["ID"]) || !$IDValid || !IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
		
		$ret = Supply::apiDelete($_GET["UserID"],$this->LoginUser["Name"],$_GET["ID"]);
		$result = new ResponseResult($ret === true,$ret);
		echo $result->AsJson();
	}
}
?>