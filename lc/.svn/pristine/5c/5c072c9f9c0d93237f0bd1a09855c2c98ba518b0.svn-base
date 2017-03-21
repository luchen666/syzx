<?php
include_once(APP_PATH . "/utils/Utils.php");
include_once(APP_PATH . "/utils/ResponseResult.php");
include_once(MODELS_PATH . "/admin/Inquiry.php");
class InquiryController extends APIBaseController
{
//	public function indexAction()
//	{
//		if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
//		{	$result = new ResponseResult(false,"参数有误");
//			echo $result->AsJSon();
//			return;
//		}
//		$ret = Inquiry::getoneInquiry($_GET["ID"]);
//		print_r($ret);
//	}
    //删除选中数据
    public function delAction()
    {
        $checked = true;
        if(!IsSet($_GET["ID"])) $checked = false;
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
        $ret = Inquiry::del($_GET["ID"]);
        echo (new ResponseResult($ret === true,$ret,0,null))->AsJSon();
    }

    //获取数据总记录
    public function getCountAction()
    {
        $ret = inquiry::getCount();
        echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJSon();
    }
}
?>