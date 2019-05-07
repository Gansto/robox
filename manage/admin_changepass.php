<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$oldpass = trim($_POST["oldpass"]);
	$newpass = trim($_POST["newpass"]);

	if (empty($oldpass) || empty($newpass))
	{
		info("参数填写不正确！");
	}
	else
	{
		$oldpass = md5($oldpass);
		$newpass = md5($newpass);
	}


	//连接数据库
	$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


	$sql = "select pass from admin where id=$session_admin_id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		if ($oldpass != $row["pass"])
		{
			$db->close();
			info("原密码不正确！");
		}
	}
	else
	{
		$db->close();
		info("当前帐号不存在！");
	}

	$sql = "update admin set pass='$newpass' where id=$session_admin_id";
	$rst = $db->query($sql);
	$db->close();

	if($rst)
	{
		info("修改密码成功！");
	}
	else
	{
		info("修改密码失败，可能是原密码不正确！");
	}
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title>网站管理中心 v4.0</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<meta content="" name="description" />
<meta content="" name="author" />
<link rel="shortcut icon" href="favicon.ico" />
<link href="themes/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
<link href="themes/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<link href="themes/plugins/uniform/css/uniform.default.css" rel="stylesheet" />
<script src="js/jquery.js"></script>
<script src="js/common.js"></script>
<script src="js/edit.js"></script>
<link href="themes/css/shy.css" rel="stylesheet" />
<link href="themes/css/shy-skin.css" rel="stylesheet" />
<script>
	function check(form){
		if (form.oldpass.value == "")
		{
			alert("原密码不能为空！");
			form.oldpass.focus();
			return false;
		}

		if (form.newpass.value == "")
		{
			alert("新密码不能为空！");
			form.newpass.focus();
			return false;
		}

		if (form.newpass.value.length < 8)
		{
			alert("密码长度不能少于8位！");
			form.newpass.focus();
			return false;
		}

		if (form.newpass.value == form.oldpass.value)
		{
			alert("新密码不能和原密码相同！");
			form.newpass.focus();
			return false;
		}

		if (form.newpass2.value != form.newpass.value)
		{
			alert("两次输入的密码不一致！");
			form.newpass2.focus();
			return false;
		}
		return true;
	}
</script>
</head>
<body>
<?
	require_once("header.php");
?>
<div class="clearfix"></div>
<div class="page-container clearfix">
	<div class="page-content-wrapper">
		<?
		require_once("menu.php");
		?>
		<div class="page-content">
			<ul class="page-breadcrumb breadcrumb">
				<li><i class="fa fa-home"></i><a href="index.php">首页</a></li>
				<li><i class="fa fa-angle-right"></i>个人管理</li>
				<li><i class="fa fa-angle-right"></i><span class="active">修改口令</span></li>
			</ul>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light edit-panel">
						<div class="portlet-title">
							<div class="row">
								<div class="col-md-6 col-sm-6">
		                            <div class="caption font-red-sunglo"> <i class="fa fa-edit"></i> <strong class="caption-subject uppercase">添加/编辑</strong></div>
	                            </div>
	                            <div class="col-md-6  col-sm-6 hidden-xs">
	                            	<div class="actions pull-right"> <i class="fa fa-angle-left"></i> <a href="javascript:void(0);" onclick="history.back(-1);">返回列表</a></div>
	                            </div>
	                         </div>
                        </div>
                        <div class="portlet-body">
 							<form  class="form" name="editForm" method="post" onSubmit="return check(this);">
								<div class="form-body">
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">登陆帐号</label>
												<div class="input-icon">
 													<input type="text" class="form-control"  name="session_admin_name" value="<?=$session_admin_name?>"  readonly>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">原密码</label>
												<div class="input-icon">
 													<input type="password" class="form-control"  name="oldpass" value="<?=$oldpass?>" >
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">新密码</label>
												<div class="input-icon">
 													<input type="password" class="form-control"  name="newpass" value="<?=$newpass?>" >
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">确认新密码</label>
												<div class="input-icon">
 													<input type="password" class="form-control"  name="newpass2" value="<?=$newpass2?>" >
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-actions">
                                    <button type="submit" class="btn blue">提交</button>
                                    <button type="button" class="btn default">重置</button>
                                </div>
							</form>
						</div>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>
<?
	require_once("foot.php");
?>
</body>
</html>
