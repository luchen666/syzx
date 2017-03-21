<?php
include_once(APP_PATH . "/utils/ResponseResult.php");
include_once(MODELS_PATH . "/api2.0/Inquiry.php");

class InquiryController extends APIBaseController
{
	//获取一个运单
	public function GetAction()
	{
		if(!(IsSet($_GET["ID"]) && Is_Numeric($_GET["ID"])) || !(IsSet($_GET["UserID"]) && Is_Numeric($_GET["UserID"])))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
		
		$parms = array("ID" => $_GET["ID"]);
		$ret = Inquiry::apiGetInquiryByID($_GET["ID"],$_GET["UserID"]);
		$result = new ResponseResult($ret !== null,"查无数据",0,$ret);
		echo $result->AsJson();
	}
	
	//获取船期的报价单
	public function ListBySchAction()
	{
		if(!(IsSet($_GET["SchID"]) && Is_Numeric($_GET["SchID"])) || !(IsSet($_GET["UserID"]) && Is_Numeric($_GET["UserID"])))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
		
		$parms = array("SchID" => $_GET["SchID"]);//,"ExcludeUserID"=>$_GET["UserID"]);
		
		$ret = Inquiry::apiGetList("GT",0,-1,$parms);
		$result = new ResponseResult($ret !== null,"查无数据",0,$ret);
		echo $result->AsJson();
	}
	
	//获取货源的报价单
	public function ListBySupplyAction()
	{
		if( !(IsSet($_GET["SupplyID"]) && Is_Numeric($_GET["SupplyID"])) ||
			!(IsSet($_GET["UserID"]) && Is_Numeric($_GET["UserID"])))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
		
		$parms = array("SupplyID" => $_GET["SupplyID"]);	//,"ExcludeUserID"=>$_GET["UserID"]);
		
		$ret = Inquiry::apiGetList("GT",0,-1,$parms);
		$result = new ResponseResult($ret !== null,"查无数据",0,$ret);
		echo $result->AsJson();
	}
	
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
		
		$p = array("SUPSCHUserID"=>$_GET["UserID"]);
		
		if(IsSet($this->Post["StateFrom"]) && $this->Post["StateFrom"] != null)	$p["StateFrom"] = $this->Post["StateFrom"];
		if(IsSet($this->Post["StateTo"]) && $this->Post["StateTo"] != null)		$p["StateTo"] = $this->Post["StateTo"];
		if(IsSet($this->Post["Date"]) && $this->Post["Date"] != null)				$p["Date"] = $this->Post["Date"];
		
		$ret = Inquiry::apiGetList($act,$id,$rows,$p);
		
		$result = new ResponseResult($ret !== null,"查无数据",0,$ret);
		echo $result->AsJson();
	}
	
	//下单
	public function PostOrderAction()
	{
		$parms = $this->Post;
		
		//参数检查
		if( !IsSet($parms["InqID"]) || !Is_Numeric($parms["InqID"]) || !IsSet($parms["UserID"]) || !Is_Numeric($parms["UserID"]) )
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($parms["UserID"]);
		
		//下单号，船期改状态，其它船期的询价都作废
		$ret = Inquiry::apiOrder($parms["InqID"],$parms["UserID"]);
		$result = new ResponseResult($ret === true,$ret);
		echo $result->AsJson();
	}
	
	public function SetReadAction()
	{
		if( !IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]) || !IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
		
		$ret = Inquiry::apiSetRead($_GET["ID"],$_GET["UserID"]);
		$result = new ResponseResult($ret === true,$ret);
		echo $result->AsJson();
		
		/*
		if(!IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
		
		$ret = Inquiry::apiSetRead($_GET);
		$result = new ResponseResult($ret === true,$ret);
		echo $result->AsJson();
		*/
	}
	
	//关闭运单
	public function CloseAction()
	{
		//参数检查
		if( !IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]) ||
			!IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]) )
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
		
		$ret = Inquiry::apiSetState($_GET["ID"],$_GET["UserID"],INQ_STATE_INVALID);
		$result = new ResponseResult($ret === true,$ret);
		echo $result->AsJson();
	}
	
	//订单修改保存
	public function SaveAction()
	{
		//参数检查
		if( !IsSet($this->Post["ID"])  || !Is_Numeric($this->Post["ID"]) ||
			!IsSet($this->Post["Qty"]) || !Is_Numeric($this->Post["Qty"]) ||
			!IsSet($this->Post["UserID"]) || !Is_Numeric($this->Post["UserID"]) ||
			!IsSet($this->Post["Price"])  || !Is_Numeric($this->Post["Price"])  ||
			!IsSet($this->Post["TotalSum"]) || !Is_Numeric($this->Post["TotalSum"]) ||
			!IsSet($this->Post["Deposit"])  || !Is_Numeric($this->Post["Deposit"])  ||
			!IsSet($this->Post["TaxInclusive"])  || !Is_Numeric($this->Post["TaxInclusive"])  ||
			!IsSet($this->Post["NeedOnlinePay"]) || !Is_Numeric($this->Post["NeedOnlinePay"]) ||
			!IsSet($this->Post["QtyDeviation"])  || !Is_Numeric($this->Post["QtyDeviation"])
			)
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($this->Post["UserID"]);

		$this->Post["UserName"] = $_SESSION["User"]["Name"];

		$ret = Inquiry::apiUpdate($this->Post);
		$result = new ResponseResult($ret === true,$ret);
		echo $result->AsJson();
	}
	
	//申请退款
	public function RequestRefundAction()
	{
		//参数检查
		if( !IsSet($this->Post["ID"]) || !Is_Numeric($this->Post["ID"]) || !IsSet($this->Post["Reason"]) ||
			!IsSet($this->Post["UserID"]) || !Is_Numeric($this->Post["UserID"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($this->Post["UserID"]);
		
		$ret = Inquiry::apiRequestRefund($this->Post["ID"],$this->Post["UserID"],$this->Post["Reason"]);
		$result = new ResponseResult($ret === true,$ret);
		echo $result->AsJson();
	}
	
	//撤消退款申请
	public function AbortRefundAction()
	{
		//参数检查
		if( !IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]) ||
			!IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
		
		$ret = Inquiry::apiSetState($_GET["ID"],$_GET["UserID"],INQ_STATE_DEPOSIT);
		$result = new ResponseResult($ret === true,$ret);
		echo $result->AsJson();
	}
		
	//同意退款
	public function RefundAction()
	{
		//参数检查
		if( !IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]) || 
			!IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]) ||
			!IsSet($_GET["Act"]) || ($_GET["Act"] != "DONE" && $_GET["Act"] != "REFUSE"))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($_GET["UserID"]);
		
		$ret = Inquiry::apiRefund($_GET["ID"],$_GET["UserID"],$_GET["Act"]);
		$result = new ResponseResult($ret === true,$ret);
		echo $result->AsJson();
	}
	
	//付订金
	public function DepositAction()
	{
		//参数检查
		if( !IsSet($this->Post["ID"]) || !Is_Numeric($this->Post["ID"]) || 
			!IsSet($this->Post["UserID"]) || !Is_Numeric($this->Post["UserID"]) ||
			!IsSet($this->Post["Deposit"]) || !Is_Numeric($this->Post["Deposit"]) ||
			!IsSet($this->Post["PayType"]) ||  !IsSet($this->Post["Password"]) ||
			($this->Post["PayType"] != "BALANCE" && $this->Post["PayType"] != "BANK"))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($this->Post["UserID"]);
		
		//支付，如果余额不足，将返回一个收银台网址，客户端打开网址付款
		$ret = Inquiry::apiDeposit($this->Post["ID"],$this->Post["UserID"],$this->Post["Deposit"],$this->Post["Password"],$this->Post["PayType"]);
		
		$msg = "";	$retArr = "";
		if($ret["result"] == false)	$msg = IsSet($ret["error_message"]) ? $ret["error_message"] : $ret["error_code"];
		else							$retArr = array("url"=>(IsSet($ret["url"]) ? $ret["url"] : ""));
		
		$result = new ResponseResult($ret["result"],$msg,0,$retArr);
		
		echo $result->AsJson();
	}
	
	//不通过在线支付，完成运单
	public function DoneWithoutPayAction()
	{
		//参数检查
		if(!IsSet($this->Post["ID"]) || !Is_Numeric($this->Post["ID"]) || !IsSet($this->Post["PayPassword"]) ||
			!IsSet($this->Post["UserID"]) || !Is_Numeric($this->Post["UserID"]))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($this->Post["UserID"]);
		
		$encodePwd = base64_decode($this->Post["PayPassword"]);
		
		//验证支付密码
		if(!Account::apiVerifyPayPassword($this->Post["UserID"],$encodePwd))
		{
			$result = new ResponseResult(false,"",ERR_PAY_PASSWORD_ERROR);
			echo $result->AsJSon();
			return;
		}
		
		$ret = Inquiry::apiDoneWithoutPay($this->Post["ID"],$this->Post["UserID"]);
		$result = new ResponseResult($ret === true,$ret);
		echo $result->AsJson();
	}
	
	
	//通过在线支付完成运单
	public function DoneAction()
	{
		//参数检查
		if( !IsSet($this->Post["ID"]) || !Is_Numeric($this->Post["ID"]) || 
			!IsSet($this->Post["UserID"]) || !Is_Numeric($this->Post["UserID"]) ||
			!IsSet($this->Post["PayType"]) || !IsSet($this->Post["Password"]) ||
			($this->Post["PayType"] != "BALANCE" && $this->Post["PayType"] != "BANK"))
		{
			$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$this->verifyUser($this->Post["UserID"]);
		
		//支付，如果余额不足，将返回一个收银台网址，客户端打开网址付款
		$ret = Inquiry::apiDone($this->Post["ID"],$this->Post["UserID"],$this->Post["Password"],$this->Post["PayType"]);
		
		$msg = "";
		$retArr = "";
		if($ret["result"] == false)
			$msg = IsSet($ret["error_message"]) ? $ret["error_message"] : $ret["error_code"];
		else
			$retArr = array("url"=>(IsSet($ret["url"]) ? $ret["url"] : ""));
		
		$result = new ResponseResult($ret["result"],$msg,0,$retArr);
		
		echo $result->AsJson();
	}
}
?>