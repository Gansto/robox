<?php
/*
 * 检查是否已经登陆，是否有操作当前文件的权限
*/

session_start();

define("ADMIN_COMMON", 5);		//普通管理员
define("ADMIN_CONFIRM", 6);		//审核管理员
define("ADMIN_ADVANCED", 7);	//高级管理员
define("ADMIN_SYSTEM", 8);		//系统管理员
define("ADMIN_HIDDEN", 9);		//隐藏管理员

$session_admin_id		= $_SESSION["ADMIN_ID"];
$session_admin_name		= $_SESSION["ADMIN_NAME"];
$session_admin_grade	= $_SESSION["ADMIN_GRADE"];
$session_admin_popedom	= isset($_SESSION["ADMIN_POPEDOM"]) ? $_SESSION["ADMIN_POPEDOM"] : "";
$session_admin_advanced	= isset($_SESSION["ADMIN_ADVANCED"]) ? $_SESSION["ADMIN_ADVANCED"] : "";


if ($session_admin_name == "" || ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && $session_admin_grade != ADMIN_ADVANCED && $session_admin_grade != ADMIN_CONFIRM && $session_admin_grade != ADMIN_COMMON))
{
	header("Location: login.php");
	exit;
}


//当前文件
$php_self = $_SERVER["PHP_SELF"];

//检查当前管理员是否有打开、使用当前文件的权限
$current_file_name = basename($php_self);
$allow = true;
switch($current_file_name)
{
	case "base_class_list.php":
	case "base_class_edit.php":
	case "advanced_list.php":
	case "advanced_edit.php":
		if ($session_admin_grade != ADMIN_HIDDEN) $allow = false;
		break;
	case "second_class_list.php":
	case "second_class_edit.php":
	case "third_class_list.php":
	case "third_class.edit.php":
	case "admin_list.php":
	case "admin_edit.php":
	case "admin_login_list.php":
	case "manage_info.php":
		if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM) $allow = false;
		break;
}

if (!$allow)
{
	header("Location: login.php");
	exit;
}
?>