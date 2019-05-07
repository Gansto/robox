<?
ob_start();											//使用缓冲
//session_start();

//装载必须的文件
require("../config.php");
require("../include/onlyDB.php");
require("../include/onlyException.php");
require("../include/page.php");
require("../include/functions.php");
require("../include/webscan.php");

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
	$config_watermark 		= $row["watermark"];
    $config_waterpic  		= $row["waterpic"];
    $config_waterpos  		= $row["waterpos"];
}

if($config_watermark==0){
	$config_waterpic  	= "";
}
?>