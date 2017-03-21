<?php
include_once("common.php");
include_once(ADMIN_MODEL_PATH . "news.php");

class newsController extends APIBaseController
{
	public function indexAction()
    {
        $this->getView()->assign("ROWS_PER_PAGE",ROWS_PER_PAGE);
        $this->getView()->Display(ADMIN_VIEW_PATH ."news.html");
    }
	
	public function getAction()
	{
		if(!IsSet($_GET["ID"]) || !Is_Numeric($_GET["ID"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		
		$ret = News::Get($_GET["ID"]);
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
	}
	
	public function saveAction()
	{	global $CurrentUserID;
		$post = $this->Post;
		if(!IsSet($post["ID"]) || !Is_Numeric($post["ID"]) || !IsSet($post["Image"]) || !IsSet($post["Subject"]) || !IsSet($post["Body"]))
		{	$result = new ResponseResult(false,"参数有误");
			echo $result->AsJSon();
			return;
		}
		$post["UserID"] = $CurrentUserID;
		
		$ret = News::Save($post);
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
	}
	
    public function listAction()
    {	
		$page = IsSet($_GET["page"]) && Is_Numeric($_GET["page"]) ? $_GET["page"] : 1;
		
        $ret = News::all($page,ROWS_PER_PAGE);
		
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
    }
	
	public function getCountAction()
    {	
		$ret = News::getCount();
		
		echo (new ResponseResult($ret !== null,"查无数据",0,$ret))->AsJson();
    }
	
	public function setStateAction()
	{
		$checked = true;
		if(!IsSet($_GET["ID"]) || !IsSet($_GET["State"]) || ($_GET["State"] != 0 && $_GET["State"] != 1))
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
		
		$ret = News::setState($_GET["ID"],$_GET["State"]);
		echo (new ResponseResult($ret === true,$ret,0,null))->AsJson();
	}
	
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
		
		$ret = News::del($_GET["ID"]);
		echo (new ResponseResult($ret === true,$ret,0,null))->AsJson();
	}
	
	public function uploadAction()
	{
		function alert($msg)
		{	echo json_encode(array("error" => 1, "message" => $msg));
			exit;
		}
		
		$save_path = realpath(dirname(__FILE__) . "/../../../../public/" . NEWS_UPLOAD_DIR) . "\\";		//文件保存目录路径
		$save_url = "http://" . $_SERVER["HTTP_HOST"] . "/" . NEWS_UPLOAD_DIR . "/";						//文件保存目录URL
				
		//定义允许上传的文件扩展名
		$ext_arr = array(
			"image" => array("gif", "jpg", "jpeg", "png", "bmp"),
			"flash" => array("swf", "flv"),
			"media" => array("swf", "flv", "mp3", "wav", "wma", "wmv", "mid", "avi", "mpg", "asf", "rm", "rmvb"),
			"file" => array("doc", "docx", "xls", "xlsx", "ppt", "htm", "html", "txt", "zip", "rar", "gz", "bz2"),
		);
		
		//最大文件大小
		$max_size = 10000000;

		$save_path = realpath($save_path) . "/";

		//PHP上传失败
		if (!empty($_FILES["imgFile"]["error"]))
		{
			switch($_FILES["imgFile"]["error"])
			{
				case "1":	$error = "超过php.ini允许的大小。";		break;
				case "2":	$error = "超过表单允许的大小。";			break;
				case "3":	$error = "图片只有部分被上传。";			break;
				case "4":	$error = "请选择图片。";					break;
				case "6":	$error = "找不到临时目录。";				break;
				case "7":	$error = "写文件到硬盘出错。";			break;
				case "8":	$error = "文件上传停止。";				break;
				case "999":
				default:	$error = "未知错误。";
			}
			alert($error);
		}

		//有上传文件时
		if (empty($_FILES) === false)
		{
			//原文件名
			$file_name = $_FILES["imgFile"]["name"];
			//服务器上临时文件名
			$tmp_name = $_FILES["imgFile"]["tmp_name"];
			//文件大小
			$file_size = $_FILES["imgFile"]["size"];
			
			//检查目录名
			$dir_name = empty($_GET["dir"]) ? "image" : trim($_GET["dir"]);
			if(empty($ext_arr[$dir_name])) 			alert("目录名不正确。");
			
			//检查文件名
			if (!$file_name) 							alert("请选择文件。");
			
			//检查目录
			if (@is_dir($save_path) === false) 		alert("上传目录不存在。");
			
			//检查目录写权限
			if (@is_writable($save_path) === false) 	alert("上传目录没有写权限。");
			
			//检查是否已上传
			if (@is_uploaded_file($tmp_name) === false)	alert("上传失败。");
			
			//检查文件大小
			if ($file_size > $max_size) 					alert("上传文件大小超过限制。");
			
			//获得文件扩展名
			$temp_arr = explode(".", $file_name);
			$file_ext = array_pop($temp_arr);
			$file_ext = trim($file_ext);
			$file_ext = strtolower($file_ext);
			
			//检查扩展名
			if(in_array($file_ext, $ext_arr[$dir_name]) === false)
			{	alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
			}
			
			//创建文件夹
			if ($dir_name !== "")
			{	$save_path .= $dir_name . "/";
				$save_url .= $dir_name . "/";
				if (!file_exists($save_path)) mkdir($save_path);
			}
			$ymd = date("Ymd");
			$save_path .= $ymd . "/";
			$save_url .= $ymd . "/";
			if(!file_exists($save_path))	mkdir($save_path);
			
			//新文件名
			$new_file_name = date("YmdHis") . "_" . rand(10000, 99999) . "." . $file_ext;
			
			//移动文件
			$file_path = $save_path . $new_file_name;
			if(move_uploaded_file($tmp_name, $file_path) === false) 	alert("上传文件失败。");
			
			@chmod($file_path, 0644);
			$file_url = $save_url . $new_file_name;
			echo json_encode(array("error" => 0, "url" => $file_url));
		}
	}
	
	public function fileManagerAction()
	{
		$root_path = realpath(dirname(__FILE__) . "/../../../../public/" . NEWS_UPLOAD_DIR) . "\\";		//文件保存目录路径
		$root_url = "http://" . $_SERVER["HTTP_HOST"] . "/" . NEWS_UPLOAD_DIR . "/";						//文件保存目录URL
		
		//图片扩展名
		$ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');

		//目录名
		$dir_name = empty($_GET['dir']) ? '' : trim($_GET['dir']);
		if(!in_array($dir_name, array('', 'image', 'flash', 'media', 'file')))
		{	echo "Invalid Directory name.";
			exit;
		}
		if($dir_name !== '')
		{	$root_path .= $dir_name . "\\";
			$root_url .= $dir_name . "/";
			if (!file_exists($root_path))	mkdir($root_path);
		}
		
		//根据path参数，设置各路径和URL
		if (empty($_GET['path']))
		{	$current_path = realpath($root_path) . "\\";
			$current_url = $root_url;
			$current_dir_path = '';
			$moveup_dir_path = '';
		}
		else
		{	$current_path = realpath($root_path) . '\\' . $_GET['path'];
			$current_url = $root_url . $_GET['path'];
			$current_dir_path = $_GET['path'];
			$moveup_dir_path = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
		}
		
		//排序形式，name or size or type
		$order = empty($_GET['order']) ? 'name' : strtolower($_GET['order']);

		//不允许使用..移动到上一级目录
		if (preg_match('/\.\./', $current_path))
		{	echo 'Access is not allowed.';
			exit;
		}
		
		//最后一个字符不是/
		/*
		if (!preg_match('/\/$/', $current_path))
		{	echo 'Parameter is not valid.';
			exit;
		}
		*/
		
		//目录不存在或不是目录
		if (!file_exists($current_path) || !is_dir($current_path))
		{	echo 'Directory does not exist.';
			exit;
		}
		
		//遍历目录取得文件信息
		$file_list = array();
		if($handle = opendir($current_path))
		{	$i = 0;
			while (false !== ($filename = readdir($handle)))
			{	if ($filename{0} == '.') continue;
				$file = $current_path . $filename;
				
				if (is_dir($file)) {
					$file_list[$i]['is_dir'] = true; //是否文件夹
					$file_list[$i]['has_file'] = (count(scandir($file)) > 2); //文件夹是否包含文件
					$file_list[$i]['filesize'] = 0; //文件大小
					$file_list[$i]['is_photo'] = false; //是否图片
					$file_list[$i]['filetype'] = ''; //文件类别，用扩展名判断
				} else {
					$file_list[$i]['is_dir'] = false;
					$file_list[$i]['has_file'] = false;
					$file_list[$i]['filesize'] = filesize($file);
					$file_list[$i]['dir_path'] = '';
					$file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
					$file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
					$file_list[$i]['filetype'] = $file_ext;
				}
				$file_list[$i]['filename'] = $filename; //文件名，包含扩展名
				$file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file)); //文件最后修改时间
				$i++;
			}
			closedir($handle);
		}
		
		//排序
		function cmp_func($a, $b)
		{
			global $order;
			if($a['is_dir'] && !$b['is_dir'])				return -1;
			else if (!$a['is_dir'] && $b['is_dir'])		return 1;
			else
			{	if ($order == 'size')
				{
					if ($a['filesize'] > $b['filesize'])		return 1;
					else if ($a['filesize'] < $b['filesize']) 	return -1;
					else										return 0;
				}
				else if ($order == 'type')						return strcmp($a['filetype'], $b['filetype']);
				else 											return strcmp($a['filename'], $b['filename']);
			}
		}
		usort($file_list, 'cmp_func');

		$result = array();
		$result['moveup_dir_path'] = $moveup_dir_path;			//相对于根目录的上一级目录
		$result['current_dir_path'] = $current_dir_path;		//相对于根目录的当前目录
		$result['current_url'] = $current_url;					//当前目录的URL
		$result['total_count'] = count($file_list);				//文件数
		$result['file_list'] = $file_list;						//文件列表数组
		
		echo json_encode($result);
	}
}
?>