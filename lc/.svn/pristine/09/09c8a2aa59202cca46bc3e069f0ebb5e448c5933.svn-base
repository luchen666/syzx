<?php
include_once("common.php");
include_once(ADMIN_MODEL_PATH . "User.php");

//恢复Session在APIBaseController.init中
//$this->Post记录了 json_decode(file_get_contents("php://input"),true);
//$this->LoginUser 记录了当前登录用户，如果未登录，此值为[]

class UserController extends APIBaseController
{
//    //增删改
//	public function saveAction()
//	{
//		//print_r($_POST);
//        if($_POST["oper"]=='add'){
//            //插入 序号自增
//            $ret = User::insertUsers(
//                $_POST["Account"],
//                $_POST["Password"],
//                $_POST["UserType"],
//                $_POST["Name"],
//                $_POST["MobilePhone"],
//                $_POST["TelePhone"],
//                $_POST["Email"],
//                $_POST["Duty"],
//                $_POST["Sex"],
//                $_POST["Star"],
//                $_POST["Point"],
//                $_POST["CertifyState"]
//            );
//        }else if($_POST["oper"]=='edit'){
//            //修改
//            $_POST["UserID"]=$_POST["id"];
//            $ret = User::updateUsers(
//                $_POST["UserID"],
//                $_POST["Account"],
//                $_POST["Password"],
//                $_POST["UserType"],
//                $_POST["Name"],
//                $_POST["MobilePhone"],
//                $_POST["TelePhone"],
//                $_POST["Email"],
//                $_POST["Duty"],
//                $_POST["Sex"],
//                $_POST["Star"],
//                $_POST["Point"],
//                $_POST["CertifyState"]
//            );
//        }else if($_POST["oper"]=='del'){
//            //删除
//            $ret = User::deleteUsers(
//              $_POST["id"]
//            );
//        }else{
//            return null;
//        };
//	}



    //从sys_users表中获取数据
    public function getUsersAction()
    {
        $page = IsSet($_GET["page"]) && Is_Numeric($_GET["page"]) ? $_GET["page"] : 1;
        $ret = User::getUsers2($page,ROWS_PER_PAGE,$_GET);
        echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJSon();
    }

    //从sys_users表中删除选中数据
    public function delUsersAction()
    {   $checked = true;
        if(!IsSet($_GET["UserID"])) $checked = false;
        else
        {   $ids = Explode(",",$_GET["UserID"]);
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
        $ret = User::deleteUsers($_GET["UserID"]);
        echo (new ResponseResult($ret === true,$ret,0,null))->AsJSon();
    }

    //在sys_users表中获取一个用户信息
    public function getOneUserAction()
    {   if(!IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]))
        {   $result = new ResponseResult(false,"参数有误");
            echo $result->AsJSon();
            return;
        }
        $rs = User::getUser($_GET["UserID"]);
        $result = new ResponseResult($rs !== null,"查无数据",0,$rs);
        echo $result->AsJSon();
    }

    //在sys_users保存添加或修改的数据
    public function saveAction()
    {   global $CurrentUser;
        $post = $this->Post;
        if(!IsSet($post["UserID"]) || !Is_Numeric($post["ID"]))
        {

        }
    }

























}
?>