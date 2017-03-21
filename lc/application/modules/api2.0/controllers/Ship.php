<?php
include_once(APP_PATH . "/utils/ResponseResult.php");
include_once(MODELS_PATH . "/api2.0/ship.php");
include_once(MODELS_PATH . "/api2.0/Inquiry.php");

class shipController extends APIBaseController
{
	//==================================================================//
	//							船期操作								//
	//==================================================================//
	//船期列表
	public function SchListAction()
	{	//拉取哪屏数据
		$act = IsSet($_GET["Action"]) ? $_GET["Action"] : "GT";
		$id = (IsSet($_GET["LastID"]) && Is_Numeric($_GET["LastID"])) ? $_GET["LastID"] : 0;
		$rows = (IsSet($_GET["Rows"]) && Is_Numeric($_GET["Rows"])) ? $_GET["Rows"] : 10;
		
		$rs = Ship::apiGetSchList($act, $id, $rows,$this->Post);
		$result = new ResponseResult($rs !== null,"查无数据",0,$rs);
		echo $result->AsJson();
	}
	//船期详情
	public function GetSchByIDAction()
	{	if( !IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]) || !IsSet($_GET["IgnoreHit"]) || !Is_Numeric($_GET["IgnoreHit"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$ret = Ship::apiGetSchByID($_GET["ID"],$_GET["IgnoreHit"]);
		$result = new ResponseResult($ret !== null,"查无数据",0,$ret);
		echo $result->AsJson();
	}
	//增加点击量
	public function HitAction()
	{	if(!(IsSet($_GET["ID"]) && Is_Numeric($_GET["ID"])))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		Ship::apiHit($_GET["ID"]);
	}
	//获取用户的船期
	public function MySchListAction()
	{	if(!(IsSet($_GET["UserID"]) && Is_Numeric($_GET["UserID"])))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
		
		$act = IsSet($_GET["Action"]) ? $_GET["Action"] : "GT";
		$id = (IsSet($_GET["LastID"]) && Is_Numeric($_GET["LastID"])) ? $_GET["LastID"] : 0;
		$rows = (IsSet($_GET["Rows"]) && Is_Numeric($_GET["Rows"])) ? $_GET["Rows"] : 10;
		//船期
		$ret = Ship::apiGetSchList($act,$id,$rows,Array("UserID" => $_GET["UserID"]));
		$result = new ResponseResult($ret !== null, "查无数据",0,$ret);
		echo $result->AsJson();
		return;
	}
	//根据货源获取用户匹配船期
	public function MatchSchAction()
	{	if(!IsSet($this->Post["PortID"]) || !Is_Numeric($this->Post["PortID"]) ||
		   !IsSet($this->Post["UserID"]) || !Is_Numeric($this->Post["UserID"]) ||
		   !IsSet($this->Post["Qty"]) || !Is_Numeric($this->Post["Qty"]) ||
		   !IsSet($this->Post["SeaOrRiver"]) || !Is_Numeric($this->Post["SeaOrRiver"]) ||
		   !IsSet($this->Post["DateFrom"]) || !IsSet($this->Post["DateTo"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($this->Post["UserID"]);	//登录检查
		
		$ret = Ship::apiGetMatchSch($this->Post);
		$result = new ResponseResult($ret !== null,"查无数据",0,$ret);
		echo $result->AsJson();
	}
	//增改船期
	public function SaveSchAction()
	{	$parm = $this->Post;
		if( !(IsSet($parm["ID"]) && Is_Numeric($parm["ID"])) ||
			!(IsSet($parm["UserID"]) && Is_Numeric($parm["UserID"])) ||
			!(IsSet($parm["ShipID"]) && Is_Numeric($parm["ShipID"])) ||
			!(IsSet($parm["ClearPortID"]) && Is_Numeric($parm["ClearPortID"])) ||
			!IsSet($parm["UserName"]) || !IsSet($parm["ShipType"]) || !IsSet($parm["Memo"]) ||
			!IsSet($parm["ClearPortName"]) ||  !IsSet($parm["ClearDate"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($this->Post["UserID"]);	//登录检查
		
		$p = array(
			"ID"				=> $parm["ID"],
			"ShipID"			=> $parm["ShipID"],
			"ShipType"			=> $parm["ShipType"],
			"ClearPortID"		=> $parm["ClearPortID"],
			"ClearPortName"		=> $parm["ClearPortName"],
			"ClearDate"			=> $parm["ClearDate"],
			"Memo"				=> $parm["Memo"],
			"UserID"			=> $parm["UserID"],
			"UserName"			=> $parm["UserName"]
		);
		if(IsSet($parm["SupplyID"]) && Is_Numeric($parm["SupplyID"]) && $parm["SupplyID"] > 0)	$p["SupplyID"] = $parm["SupplyID"];
		
		$ret = Ship::apiSaveSch($p);
		$result = new ResponseResult(Is_Numeric($ret),$ret,0,$ret);
		echo $result->AsJson();
	}
	//删除船期
	public function DelSchAction()
	{	if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]) || !IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
		
		$ret = Ship::apiDelSch($_GET["UserID"],$this->LoginUser["Name"],$_GET["ID"]);
		$result = new ResponseResult($ret === true,$ret);
		echo $result->AsJson();
	}
	//船东抢单
	public function PostInquiryAction()
	{	$parm = $this->Post;
		if( !(IsSet($parm["SupplyID"]) && Is_Numeric($parm["SupplyID"])) || 
			!(IsSet($parm["UserID"]) && Is_Numeric($parm["UserID"])) || 
			!(IsSet($parm["SchID"]) && Is_Numeric($parm["SchID"])))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($parm["UserID"]);
		
		$ret = Inquiry::apiAddInquiry($parm["SupplyID"],$parm["SchID"],$parm["UserID"],$this->LoginUser["Name"]);
		$result = new ResponseResult($ret === true,$ret);
		echo $result->AsJson();
	}
	//客户端获取推荐船期
	public function RecommendAction()
	{	include_once(APP_PATH . "/utils/utils.php");
		include_once(MODELS_PATH . "/api2.0/User.php");
		
		//登录用户使用他自己的RegionID，减轻服务器压力
		if(IsSet($_SESSION["User"]["IsLogin"]) && $_SESSION["User"]["IsLogin"] == 1)
		{	$regionID = $_SESSION["User"]["RegionID"];
		}
		else
		{	$city = getClientCity();
			$ret = User::apiGetRegionIDByCity($city);
			$regionID = $ret["RegionID"];
		}
		
		$ret = Ship::apiGetRecommend($regionID);
		$result = new ResponseResult($ret !== null,"查无数据",1,$ret);
		echo $result->AsJson();
	}

	//==================================================================//
	//							船舶操作								//
	//==================================================================//
	
	//我的船舶
	public function GetMyShipAction()
	{	if(!IsSet($_GET["UserID"]) || !Is_Numeric( $_GET["UserID"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
		
		$parm = array("UserID" => $_GET["UserID"],"IsDeleted" => 0);
		$ret = Ship::GetAllList($parm);
		$result = new ResponseResult($ret !== null,"查无数据",0,$ret);
		echo $result->AsJson();
	}
	//船舶类型
	public function GetTypeAction()
	{	$ret = Ship::apiGetShipType();
		$result = new ResponseResult($ret !== null,"查无数据",0,$ret);
		echo $result->AsJson();
	}
	//删除船舶
	public function DelShipAction()
	{	if( !(IsSet($this->Post["UserID"]) && Is_Numeric( $this->Post["UserID"])) || !IsSet($this->Post["ShipID"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($this->Post["UserID"]);
		
		$ret = Ship::apiDelShip($this->Post["ShipID"],$this->Post["UserID"]);
		$result = new ResponseResult($ret === true,$ret);
		echo $result->AsJson();
	}
	//保存船舶信息
	public function SetShipAction()
	{	$parms = $this->Post;
		if(!IsSet($parms["UserID"]) || !Is_Numeric($parms["UserID"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($parms["UserID"]);
		
		if(IsSet($parms["ShipTypeName"]))		unset($parms["ShipTypeName"]);
		
		//if(IsSet($parms["MadeDate"]) && !Is_Numeric($parms["MadeDate"]))	$parms["MadeDate"] = left($parms["MadeDate"],4);
		if(!IsSet($parms["CertifyState"]) || $parms["CertifyState"] == "")	$parms["CertifyState"] = 0;
		if(!IsSet($parms["State"])  || $parms["State"] == "")				$parms["State"] = 0;
		if(!IsSet($parms["SortNo"]) || $parms["SortNo"] == "")				$parms["SortNo"] = 0;
		if(!IsSet($parms["Star"]) || $parms["Star"] == "")					$parms["Star"] = 1;
		if(!IsSet($parms["Long"]) || $parms["Long"] == "")					$parms["Long"] = 0;
		if(!IsSet($parms["Width"])|| $parms["Width"] == "")				$parms["Width"] = 0;
		if(!IsSet($parms["Deep"]) || $parms["Deep"] == "")					$parms["Deep"] = 0;
		if(!IsSet($parms["Capacity"]) || $parms["Capacity"] == "")			$parms["Capacity"] = 0;
		if(!IsSet($parms["FullDraught"])  || $parms["FullDraught"] == "")	$parms["FullDraught"] = 0;
		if(!IsSet($parms["EmptyDraught"]) || $parms["EmptyDraught"] == "")	$parms["EmptyDraught"] = 0;
		if(!IsSet($parms["HatchNum"]) || $parms["HatchNum"] == "")			$parms["HatchNum"] = 0;
		if(!IsSet($parms["IsDeleted"]))									$parms["IsDeleted"] = 0;
		if(!IsSet($parms["UpdateDate"]))									$parms["UpdateDate"] = Date("Y/m/d H:i:s",Time());
		if(!IsSet($parms["LocationUpdateTime"]) || $parms["LocationUpdateTime"] == "")		$parms["LocationUpdateTime"] = null;
		
		$ret = Ship::Save($parms);
		
		$result = new ResponseResult($ret === true,$ret);
		echo $result->AsJson();
	}
	
	//提交船证以求验证
	public function PostImageAction()
	{	if(!IsSet($this->Post["UserID"]) || !Is_Numeric($this->Post["UserID"]) || !IsSet($this->Post["ID"]) || !Is_Numeric($this->Post["ID"]) ||
			!IsSet($this->Post["LogoImage"]) || !IsSet($this->Post["InspectionCertificate"]) || !IsSet($this->Post["NationalityCertificate"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($this->Post["UserID"]);
		
		$p = array(
			"ID"		=> $this->Post["ID"],
			"UserID"	=> $this->Post["UserID"],
			"LogoImage"	=> $this->Post["LogoImage"],
			"InspectionCertificate"	 => $this->Post["InspectionCertificate"],
			"NationalityCertificate" => $this->Post["NationalityCertificate"]
		);
		$ret = Ship::apiPostImage($p);
		$b = Is_Array($ret);
		$result = new ResponseResult($b,$b ? "" : $ret,0,$b ? $ret : "");
		echo $result->AsJson();
	}
	
	//更新位置
	public function SetLocationAction()
	{
		//参数检查
		if( !IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]) ||
			!IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]) ||
			!IsSet($_GET["Address"]) || !IsSet($_GET["Lat"]) || !IsSet($_GET["Lan"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
		
		$ret = Ship::apiSetLocation($_GET["ID"],$_GET["Address"],$_GET["Lat"],$_GET["Lan"],$_GET["UserID"]);
		$result = new ResponseResult($ret === true,$ret);
		echo $result->AsJson();
	}
}
?>