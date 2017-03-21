<?php
include_once(APP_PATH . "/utils/Utils.php");
include_once(APP_PATH . "/utils/ResponseResult.php");
include_once(MODELS_PATH . "/admin/Comment.php");

class CommentController extends APIBaseController
{
//    public function indexAction()
//    {
//        if(!IsSet($_GET["UserID"]) || !Is_Numeric($_GET["UserID"]))
//        {	$result = new ResponseResult(false,"参数有误");
//            echo $result->AsJSon();
//            return;
//        }
//
//        $ret = Comment::getonedialog($_GET["UserID"]);
//
//        print_r($ret);
//    }

    public function saveReplyAction()
    {
            print_r($_POST);
            //插入
            $ret = Comment::saveReply(
                $_POST["UserID"],
                $_POST["Body"]
            );
    }
}
?>