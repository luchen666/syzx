<?php
include_once(APP_PATH . "/utils/ResponseResult.php");
include_once(APP_PATH . "/utils/Utils.php");

//���峣�� ��Сд����
define("ADMIN_MODEL_PATH",realpath(dirname(__FILE__) . "/../../../models/admin/") . "/");
define("ADMIN_VIEW_PATH",realpath(dirname(__FILE__) . "/../views/"). "/");

define("ROWS_PER_PAGE",15);				//�б�ҳÿҳ��ʾ��¼��

//============= ��¼��� ==============
session_start();

if((!isSet($_SESSION["User"]) || !isSet($_SESSION["User"]["IsLogin"]) || $_SESSION["User"]["IsLogin"] == 0) && ($_SERVER["REQUEST_URI"] != "/admin/home/login/"))
{	header('LOCATION:/admin/home/login/');
	exit;
}
if($_SERVER["REQUEST_URI"] != "/admin/home/login/")
{
	$CurrentUser = $_SESSION["User"];
	$CurrentUserID = $CurrentUser["UserID"];
}
?>