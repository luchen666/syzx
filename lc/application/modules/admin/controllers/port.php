<?php
include_once("common.php");
include_once(ADMIN_MODEL_PATH . "Port.php");
class PortController extends APIBaseController
{
//	public function saveAction()
//	{
//		//print_r($_POST);
//        if($_POST["oper"]=='add'){
//            //插入
//            $ret = Port::insertPort(
//                $_POST["PortName"],
//                $_POST["Spell"],
//                $_POST["ShortSpell"],
//                $_POST["FirstLetter"],
//                $_POST["RegionId"],
//                $_POST["Description"]
//            );
//        }else if($_POST["oper"]=='edit'){
//            //修改
//			$_POST["ID"]=$_POST["id"];
//            $ret = Port::updatePort(
//                $_POST["ID"],
//                $_POST["PortName"],
//                $_POST["Spell"],
//                $_POST["ShortSpell"],
//                $_POST["FirstLetter"],
//                $_POST["RegionId"],
//                $_POST["Description"]
//            );
//        }else if($_POST["oper"]=='del'){
//            //删除
//            $ret = Port::deletePort(
//              $_POST["id"]
//            );
//        }else{
//            return null;
//        };
//	}

    //获取指定页面
    public function indexAction()
    {   $v = $this->getView();
        $v->assign("portNum",Port::getCountCompanies());
        $v->assign("ROWS_PER_PAGE",ROWS_PER_PAGE);
        $v->Display(ADMIN_VIEW_PATH ."port.html");
    }

    //获取数据
    public function getPortListAction()
    {
        $page = IsSet($_GET["page"]) && Is_Numeric($_GET["page"])? $_GET["page"]:1;
        $ret = Port::getPortList($page,ROWS_PER_PAGE,$_GET);
        echo (new ResponseResult($ret !==null,"查无数据",0,$ret))->AsJSon();
    }
    //删除选中记录
    public function delAction()
    {   $checked = true;
        if(!IsSet($_GET["ID"]))    $checked = false;
        else
        {   $ids = Explode(",",$_GET["ID"]);
            foreach($ids as $val)   if(!Is_Numeric($val))
            {   $checked = false;
                break;
            }
        }
        if(!$checked)
        {   $result = new ResponseResult(false,"参数有误");
            echo $result->AsJSon();
            return;
        }
        $ret = Port::del($_GET["ID"]);
        echo (new ResponseResult($ret===true,$ret,0,null))->AsJSon();
    }

    //从port表中读取一条数据
    public function getOnePortAction()
    {   if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
        {   $result = new ResponseResult(false,"参数有误");
            echo $result->AsJSon();
            return;
        }

        $rs = Port::getonePort($_GET["ID"]);
        $result = new ResponseResult($rs !== null,"查无数据",0,$rs);
        echo $result->AsJSon();
    }
}
?>