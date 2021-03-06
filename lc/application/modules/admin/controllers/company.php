<?php
include_once("common.php");
include_once(ADMIN_MODEL_PATH . "Company.php");

class CompanyController extends APIBaseController
{
//	public function saveAction()
//    {
//        //print_r($_POST);
//        if($_POST["oper"]=='add'){
//        //插入
//        $ret = Company::insertCompany(
//            $_POST["Name"],
//            $_POST["Address"],
//            $_POST["Description"],
//            $_POST["VIP"],
//            $_POST["Code"],
//            $_POST["CertifyState"]
//        );
//        }else if($_POST["oper"]=='edit'){
//        //修改
//            $_POST["ID"]=$_POST["id"];
//        $ret = Company::updateCompany(
//            $_POST["ID"],
//            $_POST["Name"],
//            $_POST["Address"],
//            $_POST["Description"],
//            $_POST["VIP"],
//            $_POST["Code"],
//            $_POST["CertifyState"]
//            //$_POST["State"]
//        );
//        }else if($_POST["oper"]=='del'){
//        //删除
//        $ret = Company::deleteCompany(
//          //ID拿不到 可以拿到的是行号id
//          $_POST["id"]
//        );
//        }else{
//        return null;
//        };
//    }
//===============================================
    //从company表中获取数据
    public function getCompaniesAction()
    {
        $page = IsSet($_GET["page"]) && Is_Numeric($_GET["page"])?$_GET["page"]:1;
        $ret = Company::getCompanies($page,ROWS_PER_PAGE,$_GET);
        echo (new ResponseResult($ret !==null,"查无数据",0,$ret))->AsJSon();
    }

    //获取一个公司的信息
    public function getOneCompanyAction()
    {   if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
        {
            $result = new ResponseResult(false,"参数有误");
            echo $result->AsJSon;
            return;
        }
        $rs = Company::getCompany($_GET["ID"]);
        $result = new ResponseResult($rs !== null,"查无数据",0,$rs);
        echo $result->AsJSon();
    }

    //删除数据
    public function delCompanyAction()
    {   $checked = true;
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
        $ret = Company::deleteCompany($_GET["ID"]);
        echo (new ResponseResult($ret === true,$ret,0,null))->AsJSon();
    }

    //保存添加或编辑的数据
    public function saveAction()
    {   global $CurrentUser;
        $post = $this->Post;
        if(!IsSet($post["ID"]) || !Is_Numeric($post["ID"]) || !IsSet($post["Name"]) || !IsSet($post["Name"]) ||
            !IsSet($post["Name"]) || !IsSet($post["Name"]) || !IsSet($post["Name"]) || !IsSet($post["Name"]))
        {
            $result = new ResponseResult(false,"参数有误");
            echo $result->AsJSon();
            return;
        }
        $ret = Company::checkName($post["Name"]);
        if($ret == 1)
        {   $result = new ResponseResult(false,"该用户以注册");
            echo $result->AsJSon();
            return;
        }
        $post["CreateUserID"] =$CurrentUser["UserID"];
        $post["CreateUserName"] =$CurrentUser["Name"];
        $ret = Company::save($post);
        echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJSon();
    }

    //检查公司是否已经存在
    public function checkNameAction()
    {
        $ret = Company::checkName($_GET["Name"]);
        echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJSon();
    }







}
?>