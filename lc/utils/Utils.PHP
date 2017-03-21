<?php
/*
 * 工具箱
 */

function makeAccessToken($key)
{
	return md5($key);
}

//返回秒数
function getTickCount()
{
	return explode(" ",microtime())[1];
}

function DateDiff($part, $begin, $end)
{
	$diff = strtotime($end) - strtotime($begin);
	switch($part)
	{
		case "y": $retval = bcdiv($diff, (60 * 60 * 24 * 365)); break;
		case "m": $retval = bcdiv($diff, (60 * 60 * 24 * 30)); break;
		case "w": $retval = bcdiv($diff, (60 * 60 * 24 * 7)); break;
		case "d": $retval = bcdiv($diff, (60 * 60 * 24)); break;
		case "h": $retval = bcdiv($diff, (60 * 60)); break;
		case "n": $retval = bcdiv($diff, 60); break;
		case "s": $retval = $diff; break;
	}
	return $retval;
}
	
function DateAdd($part,$n,$date = null)
{
	if($date == null) $date = date("Y-m-d H:i:s",time());
	
	switch($part)
	{
		case "y": $val = date("Y-m-d H:i:s", strtotime($date ." $n year")); break;
		case "m": $val = date("Y-m-d H:i:s", strtotime($date ." $n month")); break;
		case "w": $val = date("Y-m-d H:i:s", strtotime($date ." $n week")); break;
		case "d": $val = date("Y-m-d H:i:s", strtotime($date ." $n day")); break;
		case "h": $val = date("Y-m-d H:i:s", strtotime($date ." $n hour")); break;
		case "n": $val = date("Y-m-d H:i:s", strtotime($date ." $n minute")); break;
		case "s": $val = date("Y-m-d H:i:s", strtotime($date ." $n second")); break;
	}
	
	return strtotime($val);
}
function unescape($str)
{
	$str = str_replace("\\","%",$str);
	$str = rawurldecode($str);

	preg_match_all("/(?:%u.{4})|.{4};|&#\d+;|.+/U",$str,$r);

	$ar = $r[0];

	foreach($ar as $k=>$v)
	{
		if(substr($v,0,2) == "%u")
			$ar[$k] = iconv("UCS-2","GB2312",pack("H4",substr($v,-4)));
		
		elseif(substr($v,0,3) == "")
			$ar[$k] = iconv("UCS-2","GB2312",pack("H4",substr($v,3,-1)));
		
		elseif(substr($v,0,2) == "&#")
		{
			//echo substr($v,2,-1)."";
			$ar[$k] = iconv("UCS-2","GB2312",pack("n",substr($v,2,-1)));
		}
	}
	return join("",$ar);
}

function left($str,$len)
{
	return substr($str,0,$len);
}

function right($str,$len)
{
	return substr($str,-$len);
}

/*
 *	取客户端地区编号
 *
 *	参数：cityname － 引用传值，返回客户端城市名称
 *
 *	返回：城市编号，实际为邮政编码
 *
 */
function getClientIP()
{
	$ip = "";
	
	if(isset($_SERVER["HTTP_CLIENT_IP"]) && !empty($_SERVER["HTTP_CLIENT_IP"]))
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	else if(isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && !empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
		$ip = strtok($_SERVER["HTTP_X_FORWARDED_FOR"], ",");
	else if(isset($_SERVER["HTTP_PROXY_USER"]) && !empty($_SERVER["HTTP_PROXY_USER"]))
		$ip = $_SERVER["HTTP_PROXY_USER"];
	else if(isset($_SERVER["REMOTE_ADDR"]) && !empty($_SERVER["REMOTE_ADDR"]))
		$ip = $_SERVER["REMOTE_ADDR"];

	if($ip == "127.0.0.1")	$ip = "122.225.197.102";
	
	return $ip;
}

function getClientCity()
{
	$cityname = "";
	$ip = getClientIP();
	
	$opts = array("http"=>array("method"=>"GET","timeout"=>3));
	$context = stream_context_create($opts); 
	$ret = @file_get_contents("http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=$ip",false,$context);
		
	$ret = explode("= ",$ret);
	if(count($ret)>1)
	{
		$ret = trim($ret[1]);
		$ret = substr($ret,0,strlen($ret)-1);
		$ret = json_decode($ret,true);
		return IsSet($ret["city"]) ? $ret["city"] : "";
	}
	return "";
}


//获取汉字串的拼音缩写
function GetPinyin($txt,$getFirstLetter=true)
{
	static $dict;
	
	if(empty($dict))
	{
		$fn = APP_PATH . "/utils/pinyin_utf8.dat";
		foreach(file($fn) as $r)
		{
			$t = explode("`", trim($r));
			$dict[$t[0]] = $t[1];
		}
	}

	for ($i = 0; $i < iconv_strlen($txt, "UTF-8"); $i++)
	{
		$ch = iconv_substr($txt, $i, 1, "UTF-8");
		$res[] = isset($dict[$ch]) ? $dict[$ch] : $ch;
	}

	//仅获取汉字串的首字母
	if($getFirstLetter)
	{
		$ret = "";
		for($i=0;$i<Count($res);$i++)
		{
			$ret .= substr($res[$i],0,1);
		}

		return strtoupper($ret);
	}
	else
	{
		return strtoupper(join("",$res));
	}
}

//生成随机字符串
function MakeRand($len=8,$format="ALL")
{
	$is_abc = $is_numer = 0;
	$password = $tmp ="";  
	switch($format)
	{
		case "ALL":
		$chars="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		break;
		case "CHAR":
		$chars="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
		break;
		case "NUMBER":
		$chars="0123456789";
		break;
		default :
		$chars="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		break;
	}
	
	mt_srand((double)microtime()*1000000*getmypid());
	
	while(strlen($password)<$len)
	{
		$tmp = substr($chars,(mt_rand()%strlen($chars)),1);
	
		if(($is_numer <> 1 && is_numeric($tmp) && $tmp > 0 )|| $format == "CHAR")		$is_numer = 1;
		
		if(($is_abc <> 1 && preg_match("/[a-zA-Z]/",$tmp)) || $format == "NUMBER")		$is_abc = 1;
	
		$password.= $tmp;
	}
	
	if($is_numer <> 1 || $is_abc <> 1 || empty($password) )		$password = MakeRand($len,$format);
	
	return $password;
}

function Base64ToFile($body,$filename)
{
	$url = explode(",",$body);
	return file_put_contents($filename, base64_decode($url[1]));
}

//单号生成
function MakeOrderNo($uid,$number)
{
	$number = substr(100 + $number,-2);
	
	$uid = ($uid + 10000) % 10000;
	$s = floor($uid / 1000) + 2;	$uid %= 1000;
	$h = floor($uid / 100) + 0;	$uid %= 100;
	$t = floor($uid / 10) + 1;
	$g = $uid % 10 +6;
	
	if($s > 10)		$s -= 10;
	if($h > 10)		$h -= 10;
	if($t > 10)		$t -= 10;
	if($g > 10)		$g -= 10;
	
	$no = substr(Date("Ymd",time()),-6) . $number . "$s$h$t$g";
	
	return $no;
}

//postURL
function postURL($url,$post_data = '',$timeout = 10)
{	
	$context = array("http" => array(
		"method"	=> "POST",
		"timeout"	=> 5000,
		"header"	=> "Content-type: application/x-www-form-urlencoded;charset=UTF-8",
		"content"	=> $post_data));
		
	$context= stream_context_create($context);
	$file_contents = @file_get_contents($url,false,$context);

	return $file_contents;
}

function RSADecrypt($encode,$keyStr)
{	$decode = "";
	$key = openssl_get_privatekey($keyStr);
	openssl_private_decrypt($encode,$decode,$key);
	openssl_free_key($key);
	return $decode;
}

function SendSms($tel,$msg)
{
	$opts = array("http"=>array("method"=>"GET","timeout"=>5));
	$url = sprintf("http://wapi.c123.cn/tx/?uid=%s&pwd=%s&mobile=%s&content=%s&encode=utf8","50123520",md5("20@16#10^^"),$tel,urlencode($msg));
	return file_get_contents($url,false, stream_context_create($opts));
}
?>