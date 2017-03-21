<?php
include_once("common.php");
include_once(ADMIN_MODEL_PATH . "ship.php");

class ShipController extends BaseController
{
	//船期管理视图
	public function schAction()
    {	$v = $this->getView();
		$v->Assign("ShipNumber",Ship::getAllShipSchNumber());
		$v->Assign("ROWS_PER_PAGE",ROWS_PER_PAGE);
        $v->Display(ADMIN_VIEW_PATH  . "shipSch.htm");
    }

//	//船舶管理视图
//	public function shipAction()
//    {
//		$v = $this->getView();
//		$v->Assign("ShipNumber",Ship::getAllShipNumber());
//		$v->Assign("ROWS_PER_PAGE",ROWS_PER_PAGE);
//        $v->Display(ADMIN_VIEW_PATH  . "ship.htm");
//    }

	//==========================  船期 ================================
	//保存船期
	public function saveSchAction()
    {	$ret = Ship::SaveSch($this->Post);
		echo (new ResponseResult($ret === true,$ret))->AsJson();
    }
	
    //搜索船东
    public function getShipByNameAction()
    {
        if(!IsSet($_GET["INPUT"]))
        {   echo (new ResponseResult(false,"参数错误",0,null))->AsJson();
            return;
        }
        $ret = Ship::getShipByName($_GET["INPUT"]);
        echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
    }
	
	//==========================  船舶 ================================
	
	//船舶列表
	public function shipListAction()
	{	
		$page = IsSet($_GET["page"]) && Is_Numeric($_GET["page"]) ? $_GET["page"] : 1;
		$ret = Ship::allShip($page,ROWS_PER_PAGE,$_GET);
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
	}
	//船舶总数
	public function getShipNumberAction()
	{
		$ret = Ship::getAllShipNumber();
		echo (new ResponseResult(true,"",0,$ret))->AsJson();
	}

	public function saveShipAction()
    	{
        //print_r($_POST);
        if($_POST["oper"]=='add'){
            //插入
            $ret = Ship::insertShip(
                $_POST["ShipName"],
                $_POST["UserName"],
                $_POST["ShipTypeID"],
                $_POST["SeaOrRiver"],
                $_POST["Tonnage"],
                $_POST["MadeDate"],
                $_POST["Shiplong"],
                $_POST["Shipwidth"],
                $_POST["Deep"],
                $_POST["Star"],
                $_POST["RegistryPort"],
                $_POST["CertifyState"]
            );
        }else if($_POST["oper"]=='edit'){
            //修改
            $_POST["ID"]=$_POST["id"];
            $ret = Ship::updateShip(
                $_POST["ID"],
                $_POST["ShipName"],
                $_POST["UserName"],
                $_POST["ShipTypeID"],
                $_POST["SeaOrRiver"],
                $_POST["Tonnage"],
                $_POST["MadeDate"],
                $_POST["Shiplong"],
                $_POST["Shipwidth"],
                $_POST["Deep"],
                $_POST["Star"],
                $_POST["RegistryPort"],
                $_POST["CertifyState"]
            );
        }else if($_POST["oper"]=='del'){
            //删除
            $ret = Ship::deleteShip(
              //ID拿不到 可以拿到的是行号id
              $_POST["id"]
            );
        }else{
            return null;
        };
    }
}
?>