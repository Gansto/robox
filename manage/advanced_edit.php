<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


$id = (int)$_GET["id"];


$listUrl = "advanced_list.php";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum		= (int)$_POST["sortnum"];
	$state			= (int)$_POST["state"];
	$name			= htmlspecialchars(trim($_POST["name"]));
	$default_file	= htmlspecialchars(trim($_POST["default_file"]));

	if (empty($name) || empty($default_file))
	{
		$db->close();
		info("填写的参数不完整！");
	}

	if ($id < 1)
	{
		$sql = "insert into advanced(id, sortnum, name, default_file, state) values(" . ($db->getMax("advanced", "id", "") + 1) . ", $sortnum, '$name', '$default_file', $state)";
	}
	else
	{
		$sql = "update advanced set sortnum=$sortnum, name='$name', default_file='$default_file', state=$state where id=$id";
	}

	$rst = $db->query($sql);
	$db->close();

	if ($rst)
	{
		header("Location: $listUrl");
		exit;
	}
	else
	{
		info("添加/编辑失败！");
	}
}

if ($id < 1)
{
	$sortnum = $db->getMax("advanced", "sortnum", "") + 10;
	$state	 = 1;
}
else
{
	$sql = "select sortnum, name, default_file, state from advanced where id=$id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$sortnum		= $row["sortnum"];
		$state			= $row["state"];
		$name			= $row["name"];
		$default_file	= $row["default_file"];
	}
	else
	{
		$db->close();
		info("指定的记录不存在！");
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
<script language="javascript">
	function check(form)
	{
		if (!/^[0-9]*$/.exec(form.sortnum.value))
		{
			alert("序号只能使用数字。");
			form.sortnum.focus();
			return false;
		}

		if (form.name.value == "")
		{
			alert("功能名称不能为空。");
			form.name.focus();
			return false;
		}

		if (form.default_file.value == "")
		{
			alert("链接地址不能为空！");
			form.default_file.focus();
			return false;
		}

		return true;
	}
</script>
<link href="themes/css/shy.css" rel="stylesheet" />
<link href="themes/css/shy-skin.css" rel="stylesheet" />
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
			<!--B 面包屑-->
			<ul class="page-breadcrumb breadcrumb">
				<li><i class="fa fa-home"></i><a href="index.php">首页</a></li>
				<li><i class="fa fa-angle-right"></i>隐藏管理</li>
				<li><i class="fa fa-angle-right"></i><span class="active">高级功能管理</span></li>
			</ul>
			<!--E 面包屑-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light edit-panel">
						<div class="portlet-title">
							<div class="row">
								<div class="col-md-6 col-sm-6">
		                            <div class="caption font-red-sunglo"> <i class="fa fa-edit"></i> <strong class="caption-subject uppercase">添加/编辑</strong></div>
	                            </div>
	                            <div class="col-md-6  col-sm-6 hidden-xs">
	                            	<div class="actions pull-right"> <i class="fa fa-angle-left"></i> <a href="<?=$listUrl?>">返回列表</a></div>
	                            </div>
	                         </div>
                        </div>
                        <div class="portlet-body">
 							<form class="form" name="editForm" method="post" onSubmit="return check(this);">
								<div class="form-body">
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">序号</label>
												<div class="input-icon">
 													<input type="text" class="form-control"  name="sortnum" value="<?=$sortnum?>" >
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">状态</label>
												<div class="checkbox-list">
													<label class="checkbox-inline"><input type="radio" class="checkbox" name="state" value="0" <?if($state == 0){echo "checked";}?> /> 不显示</label>
													<label class="checkbox-inline"><input type="radio" class="checkbox" name="state" value="1" <?if($state == 1){echo "checked";}?> /> 系统管理员</label>
													<label class="checkbox-inline"><input type="radio" class="checkbox" name="state" value="2" <?if($state == 2){echo "checked";}?> /> 隐藏管理员</label>
		                                        </div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">功能名称</label>
												<div class="input-icon">
 													<input type="text" class="form-control"  name="name" value="<?=$name?>" >
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">链接地址</label>
												<div class="input-icon">
 													<input type="text" class="form-control"  name="default_file" value="<?=$default_file?>" >
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">说明</label>
										<div class="input-icon">当新增了新功能，必须在config.php文件中新增自定义常量的值，否则无法正确判断权限。</div>
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