<?php
include_once(MODELS_PATH . "/BaseModel.php");
include_once(MODELS_PATH . "/api2.0/Account.php");
include_once(APP_PATH . "/utils/Utils.php");

class Inquiry Extends BaseModel
{
	//获取列表
	public static function apiGetList($act,$id,$rows=20,$where = array())
	{
		if($act == "GT" || $act == "NEW")	$opr = ">";
		if($act == "LT" || $act == "OLD")	$opr = "<";
		
		$sql =  "SELECT * FROM Inquiry WHERE IsDeleted = 0 " . (($id > -1) ? "AND ID $opr $id " : " ");
				  
		$parm = array();
		
		if(IsSet($where["SupplyID"]) && $where["SupplyID"] != "")
		{	$sql .= " AND SupplyID = :SupplyID";
			$parm["SupplyID"] = $where["SupplyID"];
		}
		if(IsSet($where["SchID"]) && $where["SchID"] != "")
		{	$sql .= " AND ShipSchID = :ShipSchID";
			$parm["ShipSchID"] = $where["SchID"];
		}
		if(IsSet($where["UserID"]) && $where["UserID"] != "")
		{	$sql .= " AND CreateUserID = :UserID";
			$parm["UserID"] = $where["UserID"];
		}
		if(IsSet($where["ExcludeUserID"]) && $where["ExcludeUserID"] != "")
		{	$sql .= " AND CreateUserID <> :ExcludeUserID";
			$parm["ExcludeUserID"] = $where["ExcludeUserID"];
		}
		if(IsSet($where["StateFrom"]) && Is_Numeric($where["StateFrom"]) && $where["StateFrom"] >= 0)
		{	$sql .= " AND State >= :StateFrom";
			$parm["StateFrom"] = $where["StateFrom"];
		}
		if(IsSet($where["StateTo"]) && Is_Numeric($where["StateTo"]) && $where["StateTo"] >= 0)
		{	$sql .= " AND State <= :StateTo";
			$parm["StateTo"] = $where["StateTo"];
		}
		if(IsSet($where["Date"]) && $where["Date"] != "")
		{	$sql .= " AND DateDiff(CreateDate,:Date) = 0";
			$parm["Date"] = $where["Date"];
		}
		if(IsSet($where["SUPSCHUserID"]) && $where["SUPSCHUserID"] != "")
		{	$sql .= " AND (SupplyUserID = :SUPSCHUserID OR ShipUserID = :SUPSCHUserID)";
			$parm["SUPSCHUserID"] = $where["SUPSCHUserID"];
		}
		if(IsSet($where["ID"]) && $where["ID"] != "")
		{	$sql .= " AND ID = :ID";
			$parm["ID"] = $where["ID"];
		}
		
		$sql .= " ORDER BY ID DESC";
		if($rows != -1)		$sql .= " LIMIT $rows";
		
		//执行SQL
		$rs = ExecSQL($sql,$parm);
		
		return ($rs->RecordCount > 0) ? $rs->AsArray() : null;
	}
	
	//根据ID获取订单详情
	public static function apiGetInquiryByID($id,$userID)
	{
		$sql =  "SELECT IQ.*,
						FR.Name AS FromRegion,
						TR.Name AS ToRegion,
						FU.MobilePhone AS SupplyUserMobilePhone,
						FU.Star AS SupplyUserStar,
						TU.MobilePhone AS ShipUserMobilePhone,
						TU.Star AS ShipUserStar,
						ST.ShipTypeName,
						SH.MMSI,
						SH.MadeDate,
						SH.Long,
						SH.Width,
						SH.Deep,
						SH.Capacity,
						SH.FullDraught,
						SH.EmptyDraught,
						SH.HatchNum,
						SH.RegistryPort,
						SH.CertifyState,
						SH.Location,
						SH.Latitude,
						SH.Longtitude,
						SH.LocationUpdateTime
				   FROM ((((((( Inquiry AS IQ INNER JOIN Ship AS SH ON IQ.ShipID = SH.ID
						) LEFT JOIN ShipType AS ST ON SH.ShipTypeID = ST.ID
						) LEFT JOIN PORT AS FP ON IQ.FromPortID = FP.ID 
						) LEFT JOIN PORT AS TP ON IQ.ToPortID = TP.ID 
						) LEFT JOIN base_Region AS FR ON FP.RegionID = FR.ID
						) LEFT JOIN base_Region AS TR ON TP.RegionID = TR.ID
						) LEFT JOIN sys_Users AS FU ON IQ.SupplyUserID = FU.UserID
						) LEFT JOIN sys_Users AS TU ON IQ.ShipUserID = TU.UserID
				  WHERE	(IQ.SupplyUserID = $userID OR IQ.ShipUserID = $userID) AND
						IQ.IsDeleted = 0 AND IQ.ID = $id";
		$rs = ExecSQL($sql);
		return ($rs->RecordCount > 0) ? $rs->AsArray()[0] : null;
	}
	
	//转订单
	public static function apiOrder($inqID,$userID)
	{
		//检查当前询价单是否还有效
		$sql1 = "SELECT SupplyID,ShipSchID,SupplyUserID,ShipUserID,State,IsDeleted FROM Inquiry WHERE ID = :InqID";
		
		//检查船期是否已被抢走
		$sql2 = "SELECT Count(ID) AS N FROM Inquiry WHERE (ShipSchID = :SchID OR SupplyID = :SupplyID) AND State = :STATE_ORDER";
		
		//今天船东订单数
		$sql3 = "SELECT Count(ID) AS N FROM Inquiry WHERE ShipUserID = :ShipUserID AND date(OrderDate) = curdate()";
		
		//---- 做订单 ----
		$sql4 ="UPDATE Inquiry
					SET State		= :STATE_ORDER,
						OrderNo		= :OrderNo,
						OrderDate	= Now(),
						SupplyUserReadDate	= IF(SupplyUserID = :UserID,SupplyUserReadDate,NULL),
						ShipUserReadDate	= IF(ShipUserID = :UserID,ShipUserReadDate,NULL)
				  WHERE ID = :InqID";

		//其它船期货源询价单作废
		$sql5 = "UPDATE Inquiry SET State = :STATE_INVALID WHERE ID <> :InqID AND (ShipSchID = :SchID OR SupplyID = :SupplyID) AND State = :STATE_NONE";
		
		//对应船期货源改状态
		$sql6 = "UPDATE ShipSch SET State = :STATE_ORDER WHERE ID = :SchID";
		$sql7 = "UPDATE Supply  SET State = :STATE_ORDER WHERE ID = :SupplyID";
		
		$rs = new RecordSets();
		$rs->beginTrans();
		try
		{	//STEP 1: 询价单状态检查
			$rs->ExecSQL($sql1,array("InqID"=>$inqID));
			$arrInq = $rs->AsArray()[0];
			if($arrInq["State"] != INQ_STATE_NONE && $arrInq["IsDeleted"] != 0)
			{	$rs->rollBack();
				return ERR_INQUIRY_STATE_CHANGED;
			}
			$schID = $arrInq["ShipSchID"];
			$supplyID = $arrInq["SupplyID"];
			
			//STEP 2: 船期与货源状态检查
			$rs->Close();
			$rs->ExecSQL($sql2,array("SchID"=>$schID,"SupplyID"=>$supplyID,"STATE_ORDER"=>INQ_STATE_ORDER));
			if($rs->N > 0)
			{	$rs->rollback();
				return ERR_INQUIRY_HAVE_CREATED;
			}
			
			//STEP 3: 生成订单号
			$rs->ExecSQL($sql3,array("ShipUserID"=>$arrInq["ShipUserID"]));
			$orderNo = MakeOrderNo($arrInq["ShipUserID"],$rs->N+1);	//在Utils.php中
			$rs->Close();
			
			//STEP 4: 询价单转订单
			$rs->ExecSQL($sql4,array("InqID"=>$inqID,"OrderNo"=>$orderNo,"UserID"=>$userID,"STATE_ORDER"=>INQ_STATE_ORDER));
			
			//STEP 5: 关闭没有接受的询价单
			$rs->ExecSQL($sql5,array("InqID"=>$inqID,"SchID"=>$schID,"SupplyID"=>$supplyID,"STATE_INVALID"=>INQ_STATE_INVALID,"STATE_NONE"=>INQ_STATE_NONE));	
			
			//STEP 6: 货源，船期状态转订单
			$rs->ExecSQL($sql6,array("SchID"=>$schID,"STATE_ORDER"=>SUPSCH_STATE_ORDER));
			$rs->ExecSQL($sql7,array("SupplyID"=>$supplyID,"STATE_ORDER"=>SUPSCH_STATE_ORDER));
			
			$rs->commit();
			
			return true;
		}
		catch(exception $e)
		{
			return $e->getMessage();
		}
	}

	/*
	 *	添加查询单
	 *
	 *	$SupplyID	货源编号
	 *	$SchID		船期编号
	 *	$UserID		谁在加
	 *	$UserName	名字
	 *	$rs			数据库连接，其它函数调用时可复用
	 *
	 */
	public static function apiAddInquiry($SupplyID,$SchID,$UserID,$UserName,$rs = null)
	{
		if($rs == null)	$rs = new RecordSets();
		
		$rs->ExecSQL("SELECT * FROM Supply WHERE ID = $SupplyID");
		if($rs->RecordCount == 0) return ERR_SUPPLY_NOT_EXISTS;
		$arrSup = $rs->AsArray()[0];
		$rs->Close();
		$rs->ExecSQL("SELECT SS.*,SH.ShipName FROM ShipSch AS SS INNER JOIN Ship AS SH ON SS.ShipID = SH.ID WHERE SS.ID = $SchID");
		if($rs->RecordCount == 0) return ERR_SHIPSCH_NOT_EXISTS;
		$arrSch = $rs->AsArray()[0];
		
		$sql = "INSERT INTO Inquiry
						(	SupplyUserID,
							SupplyUserName,
							SupplyUserReadDate,
							SupplyID,
							GoodsName,
							PackageMethod,
							Qty,
							QtyDeviation,
							Price,
							TaxInclusive,
							Deposit,
							TotalSum,
							PaymentMethod,
							FromPortID,
							FromPortName,
							ToPortID,
							ToPortName,
							LoadDateFrom,
							LoadDateTo,
							SeaOrRiver,
							ShipUserID,
							ShipUserName,
							ShipUserReadDate,
							ShipSchID,
							ShipID,
							ShipName,
							`State`,
							NeedOnlinePay,
							IsDeleted,
							CreateUserID,
							CreateUserName,
							CreateDate
						)
				VALUES	(	:SupplyUserID,
							:SupplyUserName,
							:SupplyUserReadDate,
							:SupplyID,
							:GoodsName,
							:PackageMethod,
							:Qty,
							:QtyDeviation,
							:Price,
							:TaxInclusive,
							:Deposit,
							:Qty * :Price,
							:PaymentMethod,
							:FromPortID,
							:FromPortName,
							:ToPortID,
							:ToPortName,
							:LoadDateFrom,
							:LoadDateTo,
							:SeaOrRiver,
							:ShipUserID,
							:ShipUserName,
							:ShipUserReadDate,
							:ShipSchID,
							:ShipID,
							:ShipName,
							:State,
							:NeedOnlinePay,
							0,
							:CreateUserID,
							:CreateUserName,
							Now()
						)";
		$p = array(
			"SupplyUserID"		=> $arrSup["UserID"],
			"SupplyUserName"	=> $arrSup["UserName"],
			"SupplyUserReadDate"=> ($arrSup["UserID"] == $UserID ? date("Y-m-d H:i:s",time()) : null),
			"SupplyID"			=> $SupplyID,
			"GoodsName"			=> $arrSup["GoodsName"],
			"PackageMethod"		=> $arrSup["PackageMethod"],
			"Qty"				=> $arrSup["Qty"],
			"QtyDeviation"		=> $arrSup["QtyDeviation"],
			"Price"				=> $arrSup["Price"],
			"TaxInclusive"		=> $arrSup["TaxInclusive"],
			"Deposit"			=> $arrSup["Deposit"],
			"TotalSum"			=> $arrSup["Qty"] * $arrSup["Price"],
			"PaymentMethod"		=> $arrSup["PaymentMethod"],
			"FromPortID"		=> $arrSup["FromPortID"],
			"FromPortName"		=> $arrSup["FromPortName"],
			"ToPortID"			=> $arrSup["ToPortID"],
			"ToPortName"		=> $arrSup["ToPortName"],
			"LoadDateFrom"		=> $arrSup["LoadDateFrom"],
			"LoadDateTo"		=> $arrSup["LoadDateTo"],
			"SeaOrRiver"		=> $arrSup["SeaOrRiver"],
			"ShipUserID"		=> $arrSch["UserID"],
			"ShipUserName"		=> $arrSch["UserName"],
			"ShipUserReadDate"	=> ($arrSch["UserID"] == $UserID ? date("Y-m-d H:i:s",time()) : null),
			"ShipSchID"			=> $SchID,
			"ShipID"			=> $arrSch["ShipID"],
			"ShipName"			=> $arrSch["ShipName"],
			"State"				=> INQ_STATE_NONE,
			"NeedOnlinePay"		=> INQ_ONLINEPAY_DEFAULT,
			"CreateUserID"		=> $UserID,
			"CreateUserName"	=> $UserName
		);
		
		try
		{	$rs->ExecSQL($sql,$p);
			return true;
		}
		catch(Exception $e)
		{
			if($rs->InTrans)	throw($e);
			else				return $e->getMessage();
		}
	}
	
	//设置询价单已读标志
	public static function apiSetRead($ID,$UserID)
	{
		$sql = "UPDATE Inquiry SET SupplyUserReadDate = IF(SupplyUserID = :UserID, Now(),SupplyUserReadDate),ShipUserReadDate = IF(ShipUserID = :UserID, Now(),ShipUserReadDate) WHERE ID = :ID";
		
		try
		{
			ExecSQL($sql,array("ID"=>$ID,"UserID"=>$UserID));
			return true;
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
	
	//运单状态
	public static function apiSetState($ID,$UserID,$State,$rs = null)
	{
		$sql =  "UPDATE Inquiry SET State = :State,";
		
		if($State == INQ_STATE_DONE) $sql .= "DoneDate = Now(),";
		
		$sql .= "SupplyUserReadDate = IF(SupplyUserID = :UserID,SupplyUserReadDate,NULL),
				 ShipUserReadDate = IF(ShipUserID = :UserID,ShipUserReadDate,NULL)
				 WHERE ID = :ID AND (SupplyUserID = :UserID OR ShipUserID = :UserID)";
			 
		if($State != INQ_STATE_NONE && $State != INQ_STATE_ORDER && $State != INQ_STATE_DEPOSIT && $State != INQ_STATE_REFUND && $State != INQ_STATE_DONE && $State != INQ_STATE_INVALID)
		{	throw(new Exception("状态值错误，不能为 [ $State ]。"));
			return;
		}
		try
		{	if($rs == null)	$rs = new RecordSets();
			$rs->ExecSQL($sql,array("State"=>$State,"ID"=>$ID,"UserID"=>$UserID));
			return true;
		}
		catch(Exception $e)
		{	return $e->getMessage();
		}
	}
	
	//修改订单
	public static function apiUpdate($parm)
	{
		$sql =  "UPDATE Inquiry
					SET Qty				= :Qty,
						QtyDeviation	= :QtyDeviation,
						Price			= :Price,
						TaxInclusive	= :TaxInclusive,
						TotalSum		= :TotalSum,
						Deposit			= :Deposit,
						NeedOnlinePay	= :NeedOnlinePay,
						UpdateUserID	= :UserID,
						UpdateUserName	= :UserName,
						UpdateDate		= Now()
				  WHERE ID = :ID";
		try
		{
			ExecSQL($sql,$parm);
			return true;
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
	
	/*
	 *	付订金
	 *	$id 运单ID $userid 付款用户  $deposit 定金金额  $payType 余额付还是网银付(BALANCE/BANK)  
	 *  这两个参数，网银支付时使用  $BC 企业网银还是个人网银付或是手机支付(B/C/H)  $bankCode 银行代码
	 *
	 *	返回：ret["pay_type"]		支付类型，BALANCE 余额付  BANK 网银付
	 *		  ret["result"]			支付结果，true: 余额付表示付完了， 网银付表示已提交到平台
	 *		  ret["error_message"]	错误信息。result为false才返回此字段。
	 *		  ret["error_code"]		错误代码
	 *		  ret["url"]			在线网银支付网址，仅在网银付提交成功后返回此字段。
	 */
	public static function apiDeposit($inquiryID,$userID,$deposit,$encodePwd,$payType,$BC="H",$bankCode="")
	{
		$rs = new RecordSets();
		
		//运单信息
		$rs->ExecSQL("SELECT *,IfNull(timestampdiff(SECOND,PayDate,Now()),9999) AS PayPassSeconds FROM Inquiry WHERE ID = $inquiryID");
		if($rs->RecordCount == 0 || $userID != $rs->SupplyUserID)		return array("result"=>false,"error_code"=>ERR_INQUIRY_NOT_MATCHED);
		if($rs->State != INQ_STATE_ORDER)								return array("result"=>false,"error_code"=>ERR_INQUIRY_STATE_CHANGED);
		if($rs->IsPaying && $rs->PayPassSeconds < PAY_KEEP_INTERVAL)	return array("result"=>false,"error_code"=>ERR_INQUIRY_IN_PAYING);
		
		$orderNo = $rs->OrderNo;
		$supplyUserID = $rs->SupplyUserID;
		$supplyUserName = $rs->SupplyUserName;
		$shipUserID = $rs->ShipUserID;
		$shipUserName = $rs->ShipUserName;
		$subject = "付订金，单号：$rs->OrderNo";
		$rs->Close();
		
		//货主余额
		$balance = Account::apiGetUserBalance($userID,$rs);
		if(!Is_Numeric($balance))								return array("result"=>false,"error_message"=>$balance);
		if($payType == "BALANCE" && $balance < $deposit)		return array("result"=>false,"error_code"=>ERR_PAY_BALANCE_INSUFFICIENT);
		
		//余额不足，用银行卡支付
		if($payType == "BANK")
		{
			//提交到网关，在网关异步通知中再处理
			$ret = Account::apiPayToPlatform($userID,$subject,$deposit,$inquiryID,$BC,$bankCode);
			
			//提交到网关，网关正在处理，设置运单为“付款中”，防止运单再次被付款
			if($ret["result"] == true)	$rs->ExecSQL("UPDATE Inquiry SET IsPaying = 1,PayDate = Now() WHERE ID = $inquiryID");
			
			return $ret;
		}
		
		//余额支付
		else
		{
			if($encodePwd == "")	return array("result"=>false,"error_code"=>ERR_PAY_PASSWORD_EMPTY);
			$encodePwd = base64_decode($encodePwd);
			
			//支付密码解密
			$pwd = "";
			$key = openssl_get_privatekey(RSA_PRIVATE_KEY);
			openssl_private_decrypt($encodePwd,$pwd,$key);
			openssl_free_key($key);
			
			//验证支付密码
			$rs->ExecSQL("SELECT PayPassword,PaySalt FROM sys_Users WHERE UserID = $userID");
			$s1 = md5($pwd . $rs->PaySalt);
			if($rs->PayPassword != $s1)		return array("result"=>false,"error_code"=>ERR_PAY_PASSWORD_ERROR);
			
			//开始支付
			$parm = array(
				"UserID"	=> $userID,
				"UserName"	=> $supplyUserName,
				"InquiryID"	=> $inquiryID,
				"Subject"	=> $subject,
				"Amount"	=> -$deposit,
				"Source"	=> BILL_SOURCE_PAY,
				"IP"		=> getClientIP(),
				"RequestNo"	=> "",
			);
			
			$rs->beginTrans();
			
			try
			{	
				//添加用户流水
				$id = Account::apiUserBillAdd($parm,$rs);
				if(!Is_Numeric($id))
				{	if($rs->InTrans)	$rs->rollBack();
					return array("result"=>false,"error_message"=>$id);
				}
				
				$ret = Account::apiUserBillSet($id,null,BILL_STATE_VALID,$userID,$rs);
				if($ret !== true)
				{	if($rs->InTrans)	$rs->rollBack();
					return array("result"=>false,"error_message"=>$ret);
				}
				
				//支付完成，修改运单与流水状态
				$sql =  "UPDATE Inquiry 
							SET Deposit				= :Deposit,
								State				= :State,
								DepositDate			= Now(),
								ShipUserReadDate	= NULL,
								IsPaying			= 0
						  WHERE ID = $inquiryID";
						  
				$rs->ExecSQL($sql,array("State"=>INQ_STATE_DEPOSIT,"Deposit"=>$deposit));
			
				$rs->commit();
				
				return array("result"=>true,"error_message"=>"");
			}
			catch(exception $e)
			{
				return array("result"=>false,"error_message"=> $e->getMessage());
			}
		}
	}
	
	//请求退款
	public static function apiRequestRefund($id,$uid,$reason)
	{	$sql = "UPDATE Inquiry SET State = :State,RefundReason = :Reason,ShipUserReadDate = NULL,RefundDate = Now() WHERE ID = :ID AND SupplyUserID = :UserID";
		try
		{	ExecSQL($sql,array("ID"=>$id,"State"=>INQ_STATE_REFUND,"Reason"=>$reason,"UserID"=>$uid));
			return true;
		}
		catch(exception $e)
		{	return $e->getMessage();
		}
	}
	
	//退款，仅限船主操作
	public static function apiRefund($inquiryID,$userID,$act)
	{	
		$rs = new RecordSets();
		
		//同意退款
		if($act == "DONE")
		{
			$rs->ExecSQL("SELECT * FROM Inquiry WHERE ID = $inquiryID");
		
			if($rs->RecordCount == 0 || $userID != $rs->ShipUserID)		return ERR_INQUIRY_NOT_MATCHED;
			if($rs->State != INQ_STATE_REFUND)							return ERR_INQUIRY_STATE_CHANGED;
			$supplyUserID = $rs->SupplyUserID;
			
			//货源方余额增加
			$parm = array(
				"UserID"	=> $rs->SupplyUserID,
				"UserName"	=> $rs->SupplyUserName,
				"InquiryID"	=> $inquiryID,
				"Subject"	=> "退订金，单号：$rs->OrderNo",
				"Amount"	=> $rs->Deposit,
				"Source"	=> BILL_SOURCE_REFUND,
				"IP"		=> getClientIP(),
				"RequestNo"	=> "",
			);
			$rs->Close();
			
			$rs->beginTrans();
			try
			{	
				//添加退款流水
				$id = Account::apiUserBillAdd($parm,$rs);
				if(!Is_Numeric($id))
				{	if($rs->InTrans)	$rs->rollBack();
					return $id;
				}
				$ret = Account::apiUserBillSet($id,null,BILL_STATE_VALID,$supplyUserID,$rs);
				if($ret !== true)
				{	if($rs->InTrans)	$rs->rollBack();
					return $ret;
				}
				//修改状态
				$sql = "UPDATE Inquiry SET State = :STATE_INVALID, RefundDate = Now(),SupplyUserReadDate = NULL WHERE ID = $inquiryID AND State = :STATE_REFUND";
				$rs->ExecSQL($sql,array("STATE_INVALID"=>INQ_STATE_INVALID,"STATE_REFUND"=>INQ_STATE_REFUND));
				
				$rs->commit();
				return true;
				
			}	catch(Exception $e)	{	return $e->getMessage();	}
		}
		//拒绝退款
		else if($act == "REFUSE")
		{	//修改状态
			$sql = "UPDATE Inquiry SET State = :STATE_DEPOSIT, RefundDate = Now(),SupplyUserReadDate = NULL WHERE ID = $inquiryID AND State = :STATE_REFUND";
			$rs->ExecSQL($sql,array("STATE_DEPOSIT"=>INQ_STATE_DEPOSIT,"STATE_REFUND"=>INQ_STATE_REFUND));
			return true;
		}
	}
	
	//不通过在线支付完成运单
	public static function apiDoneWithoutPay($inquiryID,$userID)
	{	$sql = "UPDATE Inquiry SET ShipUserReadDate = NULL,DoneDate = Now(),State = :STATE_DONE WHERE ID = :ID AND SupplyUserID = :UserID";
		ExecSQL($sql,Array("STATE_DONE"=>INQ_STATE_DONE,"ID"=>$inquiryID,"UserID"=>$userID));
		return true;
	}
	
	//通过在线支付完成运单
	public static function apiDone($inquiryID,$userID,$encodePwd,$payType,$cardType="H",$bankCode="")
	{
		$rs = new RecordSets();
		
		//运单信息
		$rs->ExecSQL("SELECT *,IFNULL(timestampdiff(SECOND,PayDate,Now()),9999) AS PayPassSeconds FROM Inquiry WHERE ID = $inquiryID");
		if($rs->RecordCount == 0 || $userID != $rs->SupplyUserID)		return array("result"=>false,"error_code"=>ERR_INQUIRY_NOT_MATCHED);
		if($rs->State != INQ_STATE_DEPOSIT)								return array("result"=>false,"error_code"=>ERR_INQUIRY_STATE_CHANGED);
		if($rs->IsPaying && $rs->PayPassSeconds < PAY_KEEP_INTERVAL)	return array("result"=>false,"error_code"=>ERR_INQUIRY_IN_PAYING);
		
		//需要支付的金额
		$paySum = $rs->TotalSum - $rs->Deposit;
		$totalSum = $rs->TotalSum;
		
		$orderNo = $rs->OrderNo;
		$supplyUserID = $rs->SupplyUserID;
		$supplyUserName = $rs->SupplyUserName;
		$shipUserID = $rs->ShipUserID;
		$shipUserName = $rs->ShipUserName;
		$subject = "付尾款，单号：$rs->OrderNo";
		$shipSubject = "收款，单号：$rs->OrderNo";
		$rs->Close();

		//货主余额
		$balance = (float)Account::apiGetUserBalance($userID,$rs);
		if(!Is_Numeric($balance))								return array("result"=>false,"error_message"=>$balance);
		if($payType == "BALANCE" && $balance < $paySum)			return array("result"=>false,"error_code"=>ERR_PAY_BALANCE_INSUFFICIENT);

		//余额不足，用银行卡支付
		if($payType == "BANK")
		{
			//提交到网关，在网关异步通知中再处理
			$ret = Account::apiPayToPlatform($userID,$subject,$paySum,$inquiryID,$cardType,$bankCode);
			
			//提交到网关，网关正在处理，设置运单为“付款中”，防止运单再次被付款
			if($ret["result"] == true)	$rs->ExecSQL("UPDATE Inquiry SET IsPaying = 1,PayDate = Now() WHERE ID = $inquiryID");
			
			return $ret;
		}
		
		//余额支付
		else
		{
			if($encodePwd == "")	return array("result"=>false,"error_code"=>ERR_PAYPASSWORD_EMPTY);
			$encodePwd = base64_decode($encodePwd);
			
			//验证支付密码
			if(!Account::apiVerifyPayPassword($userID,$encodePwd,$rs))	return array("result"=>false,"error_code"=>ERR_PAY_PASSWORD_ERROR);
			
			//货源方余额减少
			$parm1 = array(
				"UserID"	=> $userID,
				"UserName"	=> $supplyUserName,
				"InquiryID"	=> $inquiryID,
				"Subject"	=> $subject,
				"Amount"	=> -$paySum,
				"Source"	=> BILL_SOURCE_PAY,
				"IP"		=> getClientIP(),
				"RequestNo"	=> "",
			);
			
			//船工东余额增加
			$parm2 = array(
				"UserID"	=> $shipUserID,
				"UserName"	=> $shipUserName,
				"InquiryID"	=> $inquiryID,
				"Subject"	=> $shipSubject,
				"Amount"	=> $totalSum,
				"Source"	=> BILL_SOURCE_PAY,
				"IP"		=> getClientIP(),
				"RequestNo"	=> "",
			);
			
			$rs->beginTrans();
			
			try
			{	
				//添加货主流水
				$id = Account::apiUserBillAdd($parm1,$rs);
				if(!Is_Numeric($id))
				{	if($rs->InTrans)	$rs->rollBack();
					return array("result"=>false,"error_message"=>$id);
				}
				$ret = Account::apiUserBillSet($id,null,BILL_STATE_VALID,$userID,$rs);
				if($ret !== true)
				{	if($rs->InTrans)	$rs->rollBack();
					return array("result"=>false,"error_message"=>$ret);
				}
				
				//添加船东流水
				$id = Account::apiUserBillAdd($parm2,$rs);
				if(!Is_Numeric($id))
				{	if($rs->InTrans)	$rs->rollBack();
					return array("result"=>false,"error_message"=>$id);
				}
				$ret = Account::apiUserBillSet($id,null,BILL_STATE_VALID,$shipUserID,$rs);
				if($ret !== true)
				{	if($rs->InTrans)	$rs->rollBack();
					return array("result"=>false,"error_message"=>$ret);
				}
				
				//支付完成，修改运单与流水状态
				$sql =  "UPDATE Inquiry SET State = :State,ShipUserReadDate	= NULL,IsPaying = 0,DoneDate = Now() WHERE ID = $inquiryID";
				$rs->ExecSQL($sql,array("State"=>INQ_STATE_DONE));
			
				$rs->commit();
				
				return array("result"=>true,"error_message"=>"");
			}
			catch(exception $e)
			{
				return array("result"=>false,"error_message"=> $e->getMessage());
			}
		}
	}
	
}
?>