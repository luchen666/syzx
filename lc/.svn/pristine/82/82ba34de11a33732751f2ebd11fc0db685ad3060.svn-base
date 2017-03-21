<?php
include_once("common.php");
include_once(MODELS_PATH . "/admin/Supply.php");

class SupplyController extends APIBaseController
{
//	public function indexAction()
//	{
//		if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
//		{	$result = new ResponseResult(false,"参数有误");
//			echo $result->AsJSon();
//			return;
//		}
//
//		$ret = Supply::GetoneSupply($_GET["ID"]);
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
            $ret = Supply::updateSupply(
                $_POST["ID"],
                $_POST["UserName"],
                $_POST["GoodsName"],
                $_POST["PackageMethod"],
                $_POST["Qty"],
                $_POST["QtyDeviation"],
                $_POST["SeaOrRiver"],
                $_POST["Price"],
                $_POST["Deposit"],
                $_POST["FromPortName"],
                $_POST["LoadDateFrom"],
                $_POST["LoadDateTo"],
                $_POST["ToPortName"],
                $_POST["PaymentMethod"],
                $_POST["NeedAgent"],
                $_POST["State"],
                $_POST["Memo"]
                //$_POST["State"]
            );
        }else if($_POST["oper"]=='del'){
            //删除
            $ret = Supply::deleteSupply(
              //ID拿不到 可以拿到的是行号id
              $_POST["id"]
            );
        }else{
            return null;
        };
    }
    //=========================================================
    //    保存添加或编辑
    public function savesAction()
    {	$post = $this->Post;
        if(!IsSet($post["ID"]) || !Is_Numeric($post["ID"]) || !IsSet($post["UserName"])|| !IsSet($post["GoodsName"]) || !IsSet($post["PackageMethod"]) || !IsSet($post["Qty"]) || !IsSet($post["QtyDeviation"]) || !IsSet($post["SeaOrRiver"]) || !IsSet($post["Price"]) || !IsSet($post["FromPortName"]) || !IsSet($post["ToPortName"]) || !IsSet($post["LoadDateFrom"]) || !IsSet($post["LoadDateTo"]) || !IsSet($post["PaymentMethod"]) || !IsSet($post["NeedAgent"]) || !IsSet($post["State"]))
        {	$result = new ResponseResult(false,"参数有误");
            echo $result->AsJSon();
            return;
        }

        $ret = Supply::Saves($post);
        echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
    }

    public function getCountAction()
    {
        $ret = Supply::getCount();
        echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
    }
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

        $ret =Supply::deleteSupply($_GET["ID"]);
        echo (new ResponseResult($ret === true,$ret,0,null))->AsJson();
    }

    //获取用户名和手机号码
    public function getUserInfoAction()
    {
        if(!IsSet($_GET["INPUT"]))
        {	echo (new ResponseResult(false,"参数错误",0,null))->AsJson();
            return;
        }
        $ret = Supply::getUserInfo($_GET["INPUT"]);
        echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
    }

    //查看货主名是否存在
    public function userExistAction()
    {
        if(!IsSet($_GET["TYPE"]) || !IsSet($_GET["USER"]))
        {	echo (new ResponseResult(false,"参数错误",0,null))->AsJson();
            return;
        }
        $ret = Supply::userExist($_GET["TYPE"],$_GET["USER"]);
        echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
    }


}
?>