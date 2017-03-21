<?php
include_once(APP_PATH . "/utils/Utils.php");
include_once(APP_PATH . "/utils/ResponseResult.php");
include_once(MODELS_PATH . "/admin/Login.php");

class LoginController extends APIBaseController
{
	//查一个Account能否登录 账号、密码都要匹配才能登录
	public function loginAction()
    {
        if(!IsSet($_POST["Account"]) || !IsSet($_POST["Password"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}

        //$ret 就是信息里的code
        $ret = Login::loginAccount($_POST["Account"],$_POST["Password"]);

        $result = new ResponseResult($ret == 0,"",$ret,"");
        echo $result->AsJSon();
        return;
    }

	public function codeAction()
	{
		function getCode($num, $w, $h)
		{
			$code = "";
			for ($i = 0; $i < $num; $i++) 	$code .= rand(0, 9);
			
			//4位验证码也可以用rand(1000,9999)直接生成
			//将生成的验证码写入session，备验证页面使用
			$_SESSION["VCode"] = $code;
			//创建图片，定义颜色值
			Header("Content-type: image/PNG");
			$im = imagecreate($w, $h);
			$black = imagecolorallocate($im, 0, 0, 0);
			$gray = imagecolorallocate($im, 200, 200, 200);
			$bgcolor = imagecolorallocate($im, 255, 255, 255);

			imagefill($im, 0, 0, $gray);

			//画边框
			//imagerectangle($im, 0, 0, $w-1, $h-1, $black);

			//随机绘制两条虚线，起干扰作用
			//	$style = array (
			//		$black,
			//		$black,
			//		$black,
			//		$black,
			//		$black,
			//		$gray,
			//		$gray,
			//		$gray,
			//		$gray,
			//		$gray
			//	);
			//	imagesetstyle($im, $style);
			//	$y1 = rand(0, $h);
			//	$y2 = rand(0, $h);
			//	$y3 = rand(0, $h);
			//	$y4 = rand(0, $h);
			//	imageline($im, 0, $y1, $w, $y3, IMG_COLOR_STYLED);
			//	imageline($im, 0, $y2, $w, $y4, IMG_COLOR_STYLED);

			//在画布上随机生成大量黑点，起干扰作用;
			//	for ($i = 0; $i < 80; $i++) {
			//		imagesetpixel($im, rand(0, $w), rand(0, $h), $black);
			//	}
			//将数字随机显示在画布上,字符的水平间距和位置都按一定波动范围随机生成
			$strx = rand(3, 8);
			for ($i = 0; $i < $num; $i++)
			{
				$strpos = rand(1, 6);
				imagestring($im, 5, $strx, $strpos, substr($code, $i, 1), $black);
				$strx += rand(8, 12);
			}
			imagepng($im);
			imagedestroy($im);
		}
		session_start();
		getCode(4, 60, 20);
	}
	public function CheckCodeAction()
	{
		#后台根据提交的验证码与保存在session中的验证码比对，完成验证
		session_start();
		$code = trim($_POST['code']);
		if($code==$_SESSION["VCode"]){
			echo '1';
		}
	}
}
?>