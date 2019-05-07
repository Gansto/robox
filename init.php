<?
ob_start();											//使用缓冲
session_start();

//装载必须的文件
require("config.php");
require("include/onlyDB.php");
require("include/onlyException.php");
require("include/page.php");
require("include/functions.php");
require("include/webscan.php");

header("Content-Type:text/html;charset=utf-8;");	//指定字符集

//输入检查
function process_variables(&$val, $key)
{
	if (is_array($val))
	{
		foreach ($val as $k => $v)
		{
			process_variables($v, $k);
		}
	}
	else
	{
		$val = addslashes($val);
	}
}

if (!get_magic_quotes_gpc())
{
	array_walk($_GET, "process_variables");
	array_walk($_POST, "process_variables");
	array_walk($_FILES, "process_variables");
	array_walk($_COOKIE, "process_variables");
	if (is_array(@$_SESSION))
		array_walk($_SESSION, "process_variables");
}

//建立数据库连接对象
$db	= new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

//查询基本信息
$sql = "select * from config_base where id=1";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst))
{
	$config_name			= $row["name"];
	$config_title			= $row["title"];
	$config_icp				= $row["icp"];
	$config_copyright		= $row["copyright"];
	$config_keyword			= $row["keyword"];
	$config_description		= $row["description"];
	$config_contact			= $row["contact"];
	$config_hotline			= $row["hotline"];
	$config_phone			= $row["phone"];
	$config_map				= $row["map"];
	$config_rightButton		= $row["rightButton"];
	$config_mobilejump		= $row["mobilejump"];
	$config_javascriptHead	= $row["javascriptHead"];
	$config_javascriptFoot	= $row["javascriptFoot"];
	$config_webJavascriptHead= $row["webJavascriptHead"];
	$config_webJavascriptFoot= $row["webJavascriptFoot"];
	$config_webcopyright	 = $row["webcopyright"];
	$config_watermark 		 = $row["watermark"];
}

if($config_watermark==0){
	$config_waterpic  	= "";
	$config_waterpos    = 5;
}else{
	$config_waterpic  	= $row["waterpic"];
	$config_waterpos    = $row["waterpos"];
}

//常规设置
$content_width	= 950; // 内容模式图片允许最大宽度

//获取基本栏目
$sql = "select id, name from info_class where id like '___' order by sortnum asc";
$rst = $db->query($sql);
while ($row = $db->fetch_array($rst)) {
	$baseClassArray[]	= $row;
}

$baseClassCnt = count($baseClassArray);
if ($baseClassCnt < 1) {
	$baseClassCnt = -1;
}

for ($j = 0; $j < $baseClassCnt; $j++) {
	$sql = "select id, name, has_sub from info_class where id like '".$baseClassArray[$j]['id']."___' order by sortnum asc";
	$rst = $db->query($sql);
	$bid = $baseClassArray[$j]['id'];
	while ($row = $db->fetch_array($rst)) {
		$secondClassArray[$bid][] = $row;
	}
}

// 测试访问密码,网站上线后请删除
$domain     = "snowe";
$vcodeConst = "clearfix";

function antiEmptyUA(){
    //获取UA信息
	$ua = $_SERVER['HTTP_USER_AGENT'];
	//将恶意USER_AGENT存入数组
	$now_ua = array('FeedDemon ','BOT/0.1 (BOT for JCE)','CrawlDaddy ','Java','Feedly','UniversalFeedParser','ApacheBench','Swiftbot','ZmEu','Indy Library','oBot','jaunty','YandexBot','AhrefsBot','YisouSpider','jikeSpider','MJ12bot','WinHttp','EasouSpider','HttpClient','Microsoft URL Control','YYSpider','jaunty','Python-urllib','lightDeckReports Bot');//禁止空USER_AGENT，dedecms等主流采集程序都是空USER_AGENT，部分sql注入工具也是空USER_AGENT
	if(!$ua) {
		header("Content-type: textml; charset=utf-8");
		wp_die('网新科技！');
	}else{
		foreach($now_ua as $value )
		//判断是否是数组中存在的UA
		if(eregi($value,$ua)) {
			header("Content-type: textml; charset=utf-8");
			wp_die('网新科技！');
		}
	}
}

antiEmptyUA();
?>