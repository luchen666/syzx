<?php
include_once("common.php");
include_once(MODELS_PATH . "/admin/Shipsch.php");

class ShipschController extends APIBaseController
{
//	public function indexAction()
//	{
//		if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
//		{	$result = new ResponseResult(false,"参数有误");
//			echo $result->AsJSon();
//			return;
//		}
//
//		$ret = Shipsch::GetoneShipSch($_GET["ID"]);
//
//		print_r($ret);
//	}

	public function saveAction()
    {
        //print_r($_POST);
        //状态State暂时没有使用
        if($_POST["oper"]=='edit'){
            //修改
            $_POST["ID"]=$_POST["id"];
            $ret = Shipsch::updateShipsch(
                $_POST["ID"],
                $_POST["UserName"],
                $_POST["ShipName"],
                $_POST["ShipType"],
                $_POST["SeaOrRiver"],
                $_POST["Tonnage"],
                $_POST["ClearPortName"],
                $_POST["ClearDate"],
                $_POST["State"],
                $_POST["Memo"]
                //$_POST["State"]
            );
        }else if($_POST["oper"]=='del'){
            //删除
            $ret = Shipsch::deleteShipsch(
              //ID拿不到 可以拿到的是行号id
              $_POST["id"]
            );
        }else{
            return null;
        };
    }
    // ==================陆陈 添加========================
//    删除数据
    public function delAction()
    {
        $checked = true;
        if(!IsSet($_GET["ID"]))
        {	$checked = false;
        }
        else
        {	$ids = Explode(",",$_GET["ID"]);
            foreach($ids as $val) if(!Is_Numeric($val))
            {	$checked = false;
                break;
            }
        }
        if(!$checked)
        {	$result = new ResponseResult(false,"参数有误");
            echo $result->AsJSon();
            return;
        }

        $ret =Shipsch::deleteShipsch($_GET["ID"]);
        echo (new ResponseResult($ret === true,$ret,0,null))->AsJson();
    }
//  获取数据个数，用于计算总页数
    public function getCountAction()
    {
        $ret = Shipsch::getCount();
        echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
    }
//    分页
    public function listAction()
    {
        $page = IsSet($_GET["page"]) && Is_Numeric($_GET["page"]) ? $_GET["page"] : 1;

        $ret = News::all($page,ROWS_PER_PAGE);

        echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
    }

//    保存添加或编辑
    public function savesAction()
    {	$post = $this->Post;
        if(!IsSet($post["ID"]) || !Is_Numeric($post["ID"]) || !IsSet($post["UserName"])|| !IsSet($post["ShipType"]) || !IsSet($post["ClearPortName"]) || !IsSet($post["ClearDate"]) || !IsSet($post["State"]) || !IsSet($post["Memo"]))
        {	$result = new ResponseResult(false,"参数有误");
            echo $result->AsJSon();
            return;
        }
        $post["UserID"] = $_SESSION["UserID"];

        $ret = Shipsch::Saves($post);
        echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
    }

    //搜索船东
    public function getShipNameAction()
    {
        if(!IsSet($_GET["INPUT"]))
        {   echo (new ResponseResult(false,"参数错误",0,null))->AsJson();
            return;
        }
        $ret = Shipsch::getShipName($_GET["INPUT"]);
        echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
    }


}

?>