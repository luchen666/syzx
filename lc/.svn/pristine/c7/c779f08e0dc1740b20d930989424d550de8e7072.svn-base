/*
 *	原生JS与angularjs工具箱
 *
 *	编写：谢忠杰
 *
 */
String.prototype.trim = function()					{ return this.replace(/(^\s*)|(\s*$)/g, ""); }
String.prototype.left = function(len)				{ return this.substr(0,len); }
String.prototype.right = function(len)				{ return this.substring(this.length-len,this.length); }
String.prototype.repeat = function(m)				{ for(var o=[];m>0;o[--m]=this); return o.join(''); }
String.prototype.isEmpty = function()				{ return (this == null || this == "" || this.trim() == ""); }
String.prototype.parseDate = function()				{ return (new Date(this.replace(/-/g,"/"))); }
String.prototype.isDate = function()				{ return !isNaN(new Date(this.replace(/-/g,"/")).getDate());	}
String.prototype.listLen = function(str)			{ if(typeof(str) == "undefined") str = ","; var arr = this.split(str); return (this == null || this == "") ? 0 : arr.length;	}
String.prototype.listGetAt = function(i,str)		{ if(this == null || this == "") return "";if(typeof(str) == "undefined") str = ","; var arr = this.split(str); return (i<=0 ||i>arr.length) ? "" : arr[i-1]; }
String.prototype.listIndexOf = function(item,str)	{ for(i=0;i<this.listLen(str);i++) if(this.listGetAt(i+1,str) == String(item)) return i+1; return 0; }

Array.prototype.clone = function()	{ return this.slice(0); }
Array.prototype.findBy = function(key,value)
{
	if(key == "ID")
	{	for(var i=0;i<this.length;i++)	if(parseInt(this[i][key]) == parseInt(value))	return i;
	}
	else
	{	for(var i=0;i<this.length;i++)	if(this[i][key] == value)	return i;
	}
	return -1;
}

Date.prototype.format = function(format)
{
	var o = {
		"m+" : this.getMonth()+1,					//month
		"d+" : this.getDate(),						//day
		"h+" : this.getHours(),						//hour
		"n+" : this.getMinutes(),					//minute
		"s+" : this.getSeconds(),					//second
		"q+" : Math.floor((this.getMonth()+3)/3),	//quarter
		"S"	 : this.getMilliseconds()				//millisecond
	}

	if(/(y+)/.test(format))	format = format.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));

	for(var k in o)
	{
		if(new RegExp("("+ k +")").test(format))
		{
			format = format.replace(RegExp.$1, RegExp.$1.length==1 ? o[k] : ("00"+ o[k]).substr((""+ o[k]).length));
		}
	}
	return format;
}

Date.prototype.add = function(interval,number)
{
	var d = this;
	var k={"y":"FullYear", "q":"Month", "m":"Month", "w":"Date", "d":"Date", "h":"Hours", "n":"Minutes", "s":"Seconds", "ms":"MilliSeconds"};
	var n={"q":3, "w":7};
	eval("d.set"+k[interval]+"(d.get"+k[interval]+"()+"+((n[interval]||1)*number)+")");
	return d;
}


window.utils = window.utils || {};

utils.isDefined = function(o)	{ return (typeof(o) != "undefined");	}

utils.getTimeStamp = function()	{ return (new Date()).getTime() / 1000; }

utils.isEmpty = function(o)		{ var a = String(o);	return a.isEmpty();	}

//浏览器是否小于IE8
utils.isIE = function()
{	var BROWSER_NAME = navigator.appName ;
	var BROWSER_VERSION = navigator.appVersion.split(";")[1].replace(/[ ]/g,""); 

	return (BROWSER_NAME=="Microsoft Internet Explorer" && BROWSER_VERSION<="MSIE9.0");
}

//浮动提示层，带自动关闭时间
utils.showToast = function(msg,duration)
{
	duration = isNaN(duration) ? 3000 : duration;

	var m = document.createElement("div");
	m.innerHTML = msg;
	m.style.cssText = "min-width:150px; background:#000; opacity:0.5; height:40px; color:#fff; line-height:40px; text-align:center; border-radius:5px; position:fixed; top:70%; left:50%;margin-left:-75px; z-index:999999; font-weight:bold;";
	document.body.appendChild(m);
	setTimeout(function()
	{	var d = 0.5;
		m.style.webkitTransition = "-webkit-transform " + d	+ "s ease-in, opacity " + d + "s ease-in";
		m.style.opacity = "0";
		setTimeout(function(){ document.body.removeChild(m) }, d * 1000);
	}, duration);
}

utils.sprintf = function()
{
	var i = 0, a, f = arguments[i++], o = [], m, p, c, x, s = '';
	while(f)
	{
		if (m = /^[^\x25]+/.exec(f))
		{
			o.push(m[0]);
		}
		else if (m = /^\x25{2}/.exec(f))
		{
			o.push('%');
		}
		else if (m = /^\x25(?:(\d+)\$)?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?([b-fosuxX])/.exec(f))
		{
			if (((a = arguments[m[1] || i++]) == null) || (a == undefined))
			{
				console.log(arguments);
				throw('Too few arguments: ');
			}

			/*
			 if (/[^s]/.test(m[7]) && (typeof(a) != 'number'))
			 {
			 throw('Expecting number but found ' + typeof(a));
			 }
			 */
			switch (m[7])
			{
				case 'b': a = a.toString(2); break;
				case 'c': a = String.fromCharCode(a); break;
				case 'd': a = parseInt(a); break;
				case 'e': a = m[6] ? a.toExponential(m[6]) : a.toExponential(); break;
				case 'f': a = m[6] ? parseFloat(a).toFixed(m[6]) : parseFloat(a); break;
				case 'o': a = a.toString(8); break;
				case 's': a = ((a = String(a)) && m[6] ? a.substring(0, m[6]) : a); break;
				case 'u': a = Math.abs(a); break;
				case 'x': a = a.toString(16); break;
				case 'X': a = a.toString(16).toUpperCase(); break;
			}

			a = (/[def]/.test(m[7]) && m[2] && a >= 0 ? '+'+ a : a);
			c = m[3] ? m[3] == '0' ? '0' : m[3].charAt(1) : ' ';
			x = m[5] - String(a).length - s.length;
			p = m[5] ? c.repeat(x) : '';
			o.push(s + (m[4] ? a + p : p + a));
		}
		else
		{	console.log(arguments);
			throw("sprintf error");
		}
		f = f.substring(m[0].length);
	}
	return o.join('');
}

//对像克隆
utils.clone = function(obj)
{
	//返回传递给他的任意对象的类
	function isClass(o)
	{
		if(o === null)			return "null";
		if(o === undefined)		return "undefined";

		return Object.prototype.toString.call(o).slice(8,-1);
	}

	var result,oClass=isClass(obj);

	//确定result的类型
	if(oClass === "Object")		result = {};
	else if(oClass === "Array")	result = [];
	else						return obj;

	for(key in obj)
	{
		var copy = obj[key];

		if(isClass(copy)=="Object")		result[key] = arguments.callee(copy);		//递归调用

		else if(isClass(copy)=="Array")	result[key] = arguments.callee(copy);

		else								result[key] = obj[key];
	}
	return result;
}

utils.idToText = function(id,text)
{
	if(text.length > 50)	return id;

	for(var i=0;i<text.length;i++)
	{
		if(text[i].ID == parseInt(id))	return text[i].Text;
	}
	return id;
}

//手机号校验
utils.verifyMobilePhone = function(num)
{
	num = String(num);

	if(num.length != 11)	return false;

	var myreg = /^1(3|4|5|7|8|)\d{9}$/;

	return myreg.test(num);
}

//银行帐号合法性校验
utils.luhmCheck = function(bankno)
{
	var lastNum=bankno.substr(bankno.length-1,1);//取出最后一位（与luhm进行比较）

	var first15Num=bankno.substr(0,bankno.length-1);//前15或18位
	var newArr=new Array();

	//前15或18位倒序存进数组
	for(var i=first15Num.length-1;i>-1;i--)
	{
		newArr.push(first15Num.substr(i,1));
	}
	var arrJiShu=new Array();  //奇数位*2的积 <9
	var arrJiShu2=new Array(); //奇数位*2的积 >9

	var arrOuShu=new Array();  //偶数位数组
	for(var j=0;j<newArr.length;j++)
	{
		//奇数位
		if((j+1)%2==1)
		{
			if(parseInt(newArr[j])*2<9)	arrJiShu.push(parseInt(newArr[j])*2);
			else							arrJiShu2.push(parseInt(newArr[j])*2);
		}
		//偶数位
		else    arrOuShu.push(newArr[j]);
	}

	var jishu_child1=new Array();//奇数位*2 >9 的分割之后的数组个位数
	var jishu_child2=new Array();//奇数位*2 >9 的分割之后的数组十位数

	for(var h=0;h<arrJiShu2.length;h++)
	{
		jishu_child1.push(parseInt(arrJiShu2[h])%10);
		jishu_child2.push(parseInt(arrJiShu2[h])/10);
	}

	var sumJiShu=0; //奇数位*2 < 9 的数组之和
	var sumOuShu=0; //偶数位数组之和
	var sumJiShuChild1=0; //奇数位*2 >9 的分割之后的数组个位数之和
	var sumJiShuChild2=0; //奇数位*2 >9 的分割之后的数组十位数之和
	var sumTotal=0;
	for(var m=0;m<arrJiShu.length;m++)
	{
		sumJiShu=sumJiShu+parseInt(arrJiShu[m]);
	}

	for(var n=0;n<arrOuShu.length;n++)
	{
		sumOuShu=sumOuShu+parseInt(arrOuShu[n]);
	}

	for(var p=0;p<jishu_child1.length;p++)
	{
		sumJiShuChild1=sumJiShuChild1+parseInt(jishu_child1[p]);
		sumJiShuChild2=sumJiShuChild2+parseInt(jishu_child2[p]);
	}
	//计算总和
	sumTotal = parseInt(sumJiShu)+parseInt(sumOuShu)+parseInt(sumJiShuChild1)+parseInt(sumJiShuChild2);

	//计算Luhm值
	var k = parseInt(sumTotal)%10 == 0 ? 10 : parseInt(sumTotal) % 10;
	var luhm = 10-k;

	return lastNum == luhm;
}

//根据Key过滤数组重复元素
utils.uniArray = function(arr, key)
{
	var tempArr = arr;

	for(var i = 0;i < tempArr.length;i++)
	{   for(var j = 0;j< tempArr.length;j++)
	{
		if(tempArr[i][key] == tempArr[j][key])   arr.splice(j,1);
	}
	}
	return arr;
}

//写cookies
utils.setCookie = function(name,value)
{	var Days = 30;
	var exp = new Date();
	exp.setTime(exp.getTime() + Days*24*60*60*1000);
	document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}

//读取cookies
utils.getCookie = function(name)
{	var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
	if(arr=document.cookie.match(reg))
		return unescape(arr[2]);
	else
		return null;
}

//删除cookies
utils.delCookie = function(name)
{	var exp = new Date();
	exp.setTime(exp.getTime() - 1);
	var cval=getCookie(name);
	if(cval != null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}

//获取url参数
utils.getQueryString = function(name)
{
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	return (r!=null) ? unescape(r[2]) : null;
}

//非对称加密
var __PUBLIC_KEY__ = "";
utils.RSAEncrypt = function(input,key)
{
	if(typeof(key) == "undefined") key = __PUBLIC_KEY__;

	var encrypt = new JSEncrypt();
	encrypt.setPublicKey(key);
	return encrypt.encrypt(input);
}

utils.preventDefault = function()
{	if(document.all)	window.event.returnValue = false;
	else				event.preventDefault();
}

utils.stopPropagation = function()
{
	if(document.all)	window.event.cancelBubble = true;
    else				event.stopPropagation();
}

//============================ JQuery扩展 ==============================
//JQuery DatePicker 汉化
if($.datepicker)
{	$.datepicker.regional['zh-CN'] = {
		closeText		: '关闭',
		prevText		: '<上月',
		nextText		: '下月>',
		currentText		: '今天',
		monthNames		: ['一月','二月','三月','四月','五月','六月', '七月','八月','九月','十月','十一月','十二月'],
		monthNamesShort	: ['一','二','三','四','五','六','七','八','九','十','十一','十二'],
		dayNames		: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
		dayNamesShort	: ['周日','周一','周二','周三','周四','周五','周六'],
		dayNamesMin		: ['日','一','二','三','四','五','六'],
		weekHeader		: '周',
		dateFormat		: 'yy-mm-dd',
		firstDay		: 1,
		isRTL			: false,
		yearSuffix: 	'年',
		showMonthAfterYear	: true
	};
	$.datepicker.setDefaults($.datepicker.regional['zh-CN']);
}

//遇顶固定，其它浮动
if($.fn)
{	$.fn.smartFloat = function()
	{	var position = function(element)
		{	var top = element.position().top, pos = element.css("position");
			$(window).scroll(function()
			{	var scrolls = $(this).scrollTop();
				if (scrolls > top)
				{	if (window.XMLHttpRequest)	element.css({position: "fixed",top: 0});
				else						element.css({top: scrolls});
				}
				else
				{	element.css({position: pos,top: top});
				}
			});
		};
		return $(this).each(function() { position($(this)); });
	};
}

//解决ie浏览器不兼容placeholder的问题
$(function()
{
	//解决ie浏览器不兼容console.log
	window.console = window.console || (function()
	{	var c = {}; c.log = c.warn = c.debug = c.info = c.error = c.time = c.dir = c.profile = c.clear = c.exception = c.trace = c.assert = function(x){}; 
		return c; 
	})();
	
	// 判断浏览器是否支持 placeholder
	if(!placeholderSupport())
	{ 
		$('[placeholder]').focus(function()
		{	var input = $(this);
			if (input.val() == input.attr('placeholder'))
			{	input.val('');
				input.removeClass('placeholder');
			}
		}).blur(function()
		{	var input = $(this);
			if (input.val() == '' || input.val() == input.attr('placeholder'))
			{	input.addClass('placeholder');
				input.val(input.attr('placeholder'));
			}
		}).blur();
	};
	function placeholderSupport()
	{	return 'placeholder' in document.createElement('input');
	}
})

//============================ AngularJS 工具箱 =====================================

var agQ = null;
var agHttp = null;
var agMsg = null;
var agSpinner = null;

//全局http函数，getURL与postURL
function http(parm,isPost,callback)
{
	var deferred = agQ.defer();

	if(isPost && isPost == true)
		var o = agHttp(parm);
	else
		var o = agHttp.get(parm);

	o.success(function(response)
	{	if(response.success)
	{	var data = response.data;
		//用回调函数处理数据
		if(callback)	data = callback(response.data);
		deferred.resolve(data);
	}
	else
	{	deferred.reject(response);
		console.log(response.message ? response.message : response);
	}
	});

	o.error(function(response, status, headers, config)
	{
		deferred.reject(response);
	});

	return deferred.promise;
}

function postURL(url,data,callback)
{
	for(var p in data )	if(typeof(data[p]) != "function" )	if(data[p] == null)	data[p] = "";

	var o = {	method	: "post",
		url		: url,
		data	: data,
		timeout	: 5000,
		headers : { "Content-Type":"application/x-www-form-urlencoded"}
	};
	return http(o,true,callback);
}

function getURL(parm,callback)
{
	return http(parm,false,callback);
}

//============ 是/隐 进度条 ==============
function showSpinner(time,text)
{
	if(agSpinner != null)	agSpinner.show(time,text);
}
function hideSpinner(time,text)
{
	if(agSpinner != null)	agSpinner.hide();
}
//==================== 全局通用对话框 =====================
/*
 *	显示对话框或警告框
 *  
 *  参数：	msg		信息内容
 *			type	信息类型，默认为提示框
 *			btn		显示哪些类型，默认为一个确认按钮
 *
 *  说明：该函数依赖于AngularJS。
 *	-------------------------------------------------
 *	var MSG_INFORMATION	= 0;			//用在type参数，代表提示图标，用在btn参数，代表信息框，只有一个确认按钮
 *	var MSG_WARNNING	= 1;			//警告图标
 *	var MSG_ERROR		= 2;			//错误图标
 *	var MSG_CONFIRM		= 3;			//用在btn参数，确认框，一个确认一个取消按钮
 *
 *	示例：	msgBox("有事发生");	
 *
 *			msgBox("有事发生",MSG_ERROR);
 *
 *			msgBox("有事发生，是否现在去解决？",MSG_ERROR,MSG_CONFIRM)).then(
 *				function() 	{	//点击了“确定”按钮	},
 *				function() 	{	//点击了“取消”按钮	}
 *			);
 */
var MSG_INFORMATION	= 0;		//提示图标或信息框
var MSG_WARNNING	= 1;		//警告图标
var MSG_ERROR		= 2;		//错误图标
var MSG_CONFIRM		= 3;		//确证框
function msgBox(msg,type,btn)
{
	if(!utils.isDefined(type))	type = MSG_INFORMATION;
	if(!utils.isDefined(btn))	btn = MSG_INFORMATION;

	var temp = ["<div style='width:100%%%%;height:60px;text-align:left;'>",
		"<div style='height:60px;line-height:60px;float:left;width:50px;font-size:32px;'>",
		"<i class='glyphicon glyphicon-%s-sign' style='color:%s;font-size:32px;'></i></div><div style='display:table;float:left;'>",
		"<div style='height:60px;width:190px;display:table-cell;vertical-align:middle;'>%s</div></div></div>"
	].join("");

	if(type == MSG_WARNNING)		temp = utils.sprintf(temp,"warning","#F63","%s");	//<div style='width:100%'><i class='glyphicon glyphicon-warning-sign' style='color:#F63'></i> %s</div>"; 
	else if(type == MSG_ERROR)		temp = utils.sprintf(temp,"exclamation","red","%s");	//temp = "<div style='width:100%'><i class='glyphicon glyphicon-exclamation-sign' style='color:red'></i> %s</div>";
	else							temp = utils.sprintf(temp,"info","blue","%s");	//temp = "<div style='width:100%'><i class='glyphicon glyphicon-info-sign' style='color:blue'></i> %s</div>";

	temp = utils.sprintf(temp,msg);

	if(btn == MSG_CONFIRM)	return agMsg.confirm(temp);
	else					agMsg.alert(temp);
}

//================================= 用以注入到其它angular.module的工具箱 =================================
angular.module("utils", []).run(function($http,$q,zhMsg,spinner) { agQ = $q; agHttp = $http; agMsg = zhMsg;agSpinner = spinner; })
/*
 * 数值格式化，共三个参数
 * precision	小数位数
 * split		整数分隔位数
 * separator	整数分隔符
 *
 */
.filter("numberformat",function($filter)
{
	return function(input,precision,split,separator)
	{
		if(input == "" || input == null)	return "";

		//默认值：不保留小数，千分位，逗号分隔
		if(!utils.isDefined(precision))	precision = 0;
		if(!utils.isDefined(split))		split = 0;
		if(!utils.isDefined(separator))	separator = ",";

		//整数与小数分割
		var num = String(input).replace(/,/g,"").split(".");
		var integer = parseInt(input) > 0 ? num[0] : "0";
		var decimal = (num.length == 2 && precision > 0) ? num[1] : "0".repeat(precision);

		//小数处理
		if(precision > 0)
		{
			if(decimal.length > precision)
			{
				//多留一位，四舍五入
				decimal = decimal.substr(0,precision + 1);
				decimal = parseFloat("0." + decimal).toFixed(precision);

				//有进位,加整数
				if(decimal == 1)
				{	integer = (parseInt(integer)+1) + "";
					decimal =  "0".repeat(precision);
				}
				else
				{	decimal = decimal.substr(2,precision);
				}
			}

			//小数位补0
			else if(decimal.length < precision)
			{	decimal += "0".repeat(precision - decimal.length);
			}
		}

		//整数处理
		if(split > 0 && separator != "")
		{	var intstr = "";
			integer = integer.split("").reverse().join("");
			for(var i=1;i<=integer.length;i++)
			{
				intstr += integer[i-1];
				if(i % split == 0)	intstr += separator;
			}
			integer = intstr.split("").reverse().join("");

			//除去最后的分隔符
			if(integer[0] == separator)	integer = integer.substr(1,integer.length-1);
		}
		return (precision > 0) ? integer + "." + decimal : integer;
	}
})

//时间格式化
.filter("dateformat",function($filter)
{
	return function(input,format)
	{
		if(input == "" || input == null || input == "" || (typeof(input) == "string" && input.parseDate() == "Invalid Date"))	return "";

		if(utils.isDefined(format))
		{
			if(typeof(input) == "string")	input = input.parseDate();
			return $filter("date")(input,format);
		}
		else
		{	var tmp;

			var timeNow = parseInt(new Date().getTime()/1000);

			if(typeof(input) == "string" && input.isDate())	tmp = input.parseDate();
			else if(typeof(input) == "object")					tmp = input;
			else												return input;

			var timeNew = parseInt(tmp.getTime()/1000);

			var d = timeNow - timeNew;

			var days = parseInt(d/86400);
			var hours = parseInt(d/3600);
			var minutes = parseInt(d/60);

			if(days>0 && days<4)			return days + " 天前";
			else if(days==0 && hours>0)	return hours + " 小时前";
			else if(hours==0 && minutes>0)	return minutes + " 分钟前";
			else if(minutes==0)				return "刚刚";
			else							return $filter("date")(tmp,"MM-dd");
		}
	}
})

//重复输出
.filter("repeat",function()
{
	return function(input,char)
	{
		if(typeof(char) == "undefined")	return input;

		input = parseInt(input);

		var output = "";

		for(var i=0;i<input;i++)	output += "" + char;

		return output;
	}
})

//解决json中数值变为字符的问题
.directive("stringInt", function()
{
	return {
		require: "ngModel",

		link: function(scope, element, attrs, ngModel)
		{
			ngModel.$parsers.push(function(value)
			{
				return "" + value;
			});

			ngModel.$formatters.push(function(value)
			{
				return parseInt(value);
			});
		}
	}
})

.directive("stringFloat", function()
{
	return {
		require: "ngModel",

		link: function(scope, element, attrs, ngModel)
		{
			ngModel.$parsers.push(function(value)
			{
				return "" + value;
			});

			ngModel.$formatters.push(function(value)
			{	return parseFloat(value);
			});
		}
	}
})

//解决json中数值变为字符的问题
.directive("stringDate", function()
{
	return {
		require: "ngModel",

		link: function(scope, element, attrs, ngModel)
		{
			ngModel.$parsers.push(function(value)
			{
				return "" + value;
			});

			ngModel.$formatters.push(function(value)
			{
				return String(value).parseDate();
			});
		}
	}
})

//img标签的err-src参数，当src图片不存在，就用本参数代替
.directive("errSrc", function()
{
	return {
		link: function(scope, element, attrs)
		{
			var orgWidth = attrs.$attr.width ? attrs.width : "";
			var orgHeight = attrs.$attr.height ? attrs.height : "";
			var orgStyle = attrs.$attr.style ? attrs.style : "";

			element.bind("error", function()
			{	if(attrs.src != attrs.errSrc)
			{	attrs.$set("src", attrs.errSrc);

				if(attrs.$attr.errWidth)	attrs.$set("width", attrs.errWidth);
				if(attrs.$attr.errHeight)	attrs.$set("height", attrs.errHeight);
				if(attrs.$attr.errStyle)	attrs.$set("style", attrs.errStyle);
			}
			});

			element.bind("complete", function()
			{	if(orgWidth != "")	attrs.$set("width",orgWidth);
				if(orgHeight != "")	attrs.$set("height",orgHeight);
				if(orgStyle != "")	attrs.$set("style",orgStyle);
			});
		}
	}
})

//通用对话框
.directive('zhMsg', function()
{
	return {
		restrict	: "AE",
		replace		: true,
		template	: ["<div class='xzj-msg-overlay' ng-cloak>",
			"<div class='xzj-msg'>",
			"<div class='xzj-msg-inner' ng-class='{\"xzj-msg-inner-remind\": !buttons || !buttons.length}'>",
			"<div class='xzj-msg-title' ng-if='title'>{{ title }}</div>",
			"<div class='xzj-msg-text' ng-bind-html='renderHtml(text)' ng-if='text'></div>",
			"<input autofocus class='xzj-msg-text-input' type='{{ inputType }}' placeholder='{{ inputPlaceholder }}' value='{{inputValue}}' ng-if='input' />",
			"</div>",
			(utils.isIE() ? "<div class='xzj-msg-buttons-ie'>" +
			"<span class='xzj-msg-button-ie' ng-repeat='button in buttons' ng-click='onClick($event, button, $index)' ng-class='{\"xzj-msg-button-leftborder\":$index>0}' ng-class='{\"xzj-msg-button-bold\": button.bold}'>{{ button.text }}</span>" +
			"</div>" : 
			"<div class='xzj-msg-buttons' ng-if='buttons.length' ng-class='{\"xzj-msg-buttons-horizontal\": buttons.length <= 2}'>" +
			"<span class='xzj-msg-button' ng-class='{\"xzj-msg-button-bold\": button.bold}' ng-repeat='button in buttons' ng-click='onClick($event, button, $index)'>{{ button.text }}</span>" +
			"</div>"),
			"</div></div>"
		].join(""),
		controller	: ["$scope", "$sce",function($scope, $sce) { $scope.renderHtml = function(html_code) { return $sce.trustAsHtml(html_code); }}]
	};
})

.provider("zhMsg", function ()
{
	var defaults = {
		title			: null,
		text			: null,
		input			: false,
		inputType		: "text",
		inputPlaceholder: "",
		cancelText		: "取消",
		okText			: "确定",
		remindTime		: 250,
		defaultOption	: "text"
	};
	var keys = Object.keys(defaults);
	var self = this;

	self.set = function(key, value)
	{
		if(angular.isObject(key))
			for(var name in key)	self.set(name, key[name]);
		else
			if(key && (keys.indexOf(key) > -1) && value)	defaults[key] = value;
	};

	this.$get = [
		"$rootScope",
		"$compile",
		"$animate",
		"$q",
		"$document",
		"$timeout",
		"$log",
		function($rootScope, $compile, $animate, $q, $document, $timeout, $log)
		{
			function zhMsg(option)
			{
				// expect option is object
				if(!angular.isObject(option))
				{	$log.error("zhMsg expect object option");
					return $q.when();
				}

				var deferred = $q.defer();
				var $scope = $rootScope.$new(true);
				angular.extend($scope, defaults, option, {form: {}});
				var $element = $compile("<div zh-msg></div>")($scope);

				//debug
				angular.element(document.body).append($element);

				//if(option.inputValue) $(".xzj-msg-text-input").val(option.inputValue);
				
				$scope.onClick = function(e,button, $index)
				{	
					utils.preventDefault();
					utils.stopPropagation();
					
					var inputValue = $(".xzj-msg-text-input").val(); //$scope.inputValue;

					var cbkData = {
						index		: $index,
						button		: button,
						inputValue	: inputValue
					};

					//确认框要用到
					if(angular.isFunction(button.onClick))	button.onClick(cbkData);

					//输入框要用到
					if($index == 1 && utils.isDefined(option.onClick) && angular.isFunction(option.onClick))
					{	if(!option.onClick(inputValue))	return;
					}

					//========
					$($element).remove();
					deferred.resolve(cbkData);
				};

				if(!$scope.buttons || !$scope.buttons.length)
				{
					// if no buttons, remove modal in 650ms
					$timeout(function()
					{	$animate.leave($element).then(function()
					{	deferred.resolve();
					});
					}, 450 + 1 * $scope.remindTime);
				}

				return deferred.promise;
			}

			function objectify(option)
			{	if(angular.isObject(option))	return option;
				var opt = {};
				if(angular.isString(option))	opt[defaults.defaultOption] = option;
				else							$log.error("expect a string or an object");
				return opt;
			}

			function alert(option)
			{	var deferred = $q.defer();
				option = objectify(option);
				option = angular.extend({}, defaults, option);
				option = angular.extend(option,{ buttons: [{text: option.okText,onClick: deferred.resolve,bold: true}] });

				zhMsg(option).then(deferred.resolve, deferred.reject);

				return deferred.promise;
			}

			function confirm(option)
			{
				var deferred = $q.defer();
				option = objectify(option);
				option = angular.extend({}, defaults, option);
				option = angular.extend(option,
					{
						buttons: [{text: option.cancelText,onClick: deferred.reject},
							{text: option.okText,onClick: deferred.resolve,bold: true}
						]
					});

				zhMsg(option).then(deferred.resolve, deferred.reject);
				return deferred.promise;
			}

			function prompt(option)
			{	var deferred = $q.defer();
				option = objectify(option);
				option = angular.extend({}, defaults, option);
				option = angular.extend(option,{
					input	: true,
					buttons	: [ {text: option.cancelText,onClick: deferred.reject},
						{text: option.okText,onClick: function(data) { deferred.resolve(data.inputValue); }, bold: true }
					]
				});

				zhMsg(option).then(function(data){ deferred.resolve(data.inputValue); }, deferred.reject);

				return deferred.promise;
			}

			function remind(option)
			{
				var deferred = $q.defer();
				option = objectify(option);
				option = angular.extend({}, defaults, option);
				zhMsg(option).then(deferred.resolve, deferred.reject);
				return deferred.promise;
			}

			zhMsg.alert = alert;
			zhMsg.confirm = confirm;
			zhMsg.prompt = prompt;
			zhMsg.remind = remind;

			return zhMsg;
		}
	];
})


//通用对话框
.directive("spinner", function()
{
	return {
		restrict	: "AE",
		replace		: true,
		template	: ["<div class='spinner'><div class='spinnerbody'>",
			"<span class='bounce1'></span><span class='bounce2'></span><span class='bounce3'></span>",
			"<!--[if lte IE 9]><b>{{spinnerText}}</b><![endif]-->",
			"</div></div>"
		].join(""),
		//controller	: ["$scope", "$sce",function($scope, $sce) { $scope.renderHtml = function(html_code) { return $sce.trustAsHtml(html_code); }}]
	};
})

.provider("spinner", function ()
{
	var self = this;
	var oSpinner = null;

	this.$get = [
		"$rootScope",
		"$compile",
		"$timeout",
		function($rootScope, $compile,$timeout)
		{
			function spinner(time,text)
			{
				if(!text)	text = "载入中";
				if(!time)	time = 0;

				var $scope = $rootScope.$new(true);
				$scope.spinnerText = text;

				if(oSpinner == null)
				{	oSpinner = $compile("<div spinner></div>")($scope);
					angular.element(document.body).append(oSpinner);
				}
				else
				{	$(oSpinner).show();
				}
				if(time > 0)	$timeout(function() { $($element).hide(); },time*1000);
			}

			spinner.show = function(time,text) { spinner(time,text);	}
			spinner.hide = function() { if(oSpinner != null) $(oSpinner).hide(); }

			return spinner;
		}
	];
});


