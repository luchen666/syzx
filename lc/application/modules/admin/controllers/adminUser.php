<?php
include_once("common.php");
include_once(ADMIN_MODEL_PATH . "adminUser.php");

class adminUserController extends APIBaseController
{
	public function indexAction()
    {
		$this->getView()->assign("ROWS_PER_PAGE",ROWS_PER_PAGE);
        $this->getView()->Display(ADMIN_VIEW_PATH ."adminUser.html");
    }
	
	public function getAction()
	{
		if(!IsSet($_GET["id"]) || !Is_Numeric($_GET["id"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		
		$ret = adminUser::get($_GET["id"]);
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
	}
	
	public function saveAction()
	{	global $CurrentUserID;
		$post = $this->Post;
		if(!IsSet($post["UserID"]) || !Is_Numeric($post["UserID"]) || !IsSet($post["Account"]) || !IsSet($post["Password"]) || !IsSet($post["Name"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$post["CreateUserID"] = $CurrentUserID;
		
		$ret = adminUser::save($post);
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
	}
	
    public function listAction()
    {	
		$page = IsSet($_GET["page"]) && Is_Numeric($_GET["page"]) ? $_GET["page"] : 1;
		
        $ret = adminUser::all($page,ROWS_PER_PAGE);
		
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
    }
	
	public function countAction()
    {	
		$ret = adminUser::count();
		
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
    }
	
	public function delAction()
	{
		$checked = true;
		if(!IsSet($_GET["id"]))
		{	$checked = false;
		}
		else
		{	$ids = Explode(",",$_GET["id"]);
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
		
		$ret = adminUser::del($_GET["id"]);
		echo (new ResponseResult($ret === true,$ret,0,null))->AsJson();
	}
}	
?>