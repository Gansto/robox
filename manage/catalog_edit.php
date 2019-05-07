<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, CATALOG_ADVANCEDID) == false)
{
	info("没有权限！");
}

$id				= (int)$_GET["id"];
$class_id		= trim($_GET["class_id"]);

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

if (!empty($class_id))
{
	$sql = "select id, name, add_deny, delete_deny from catalog_class where id=$class_id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$class_name		= $row["name"];
		$add_deny		= $row["add_deny"];
		$delete_deny	= $row["delete_deny"];
	}
	if($add_deny == 1)
	{
		info("此分类下不允许增加信息！");
	}
}
else
{
	info("指定了错误的分类！");
}

$listUrl = "catalog_list.php?class_id=$class_id";
$editUrl = "catalog_edit.php?class_id=$class_id&id=$id";

//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum		= (int)$_POST["sortnum"];
	$title			= htmlspecialchars(trim($_POST["title"]));
	$state			= (int)$_POST["state"];

	if (empty($title))
	{
		$db->close();
		info("填写的参数错误！");
	}

	if ($id < 1)
	{
		//$sortnum = $db->getMax("catalog", "sortnum", "class_id='$class_id'") + 10;
		$sql = "insert into catalog (id, class_id, sortnum, title, state) values(" . ($db->getMax("catalog", "id", "") + 1) . ", $class_id, $sortnum, '$title', $state)";
		echo $sql;
	}
	else
	{
		$sql = "update catalog set sortnum=$sortnum, title='$title', state=$state where id=$id";
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
		$db->close();
		info("操作失败！");
	}
}

if ($id < 1)
{
	$sortnum		= $db->getMax("catalog", "sortnum", "class_id=$class_id") + 10;
	$state			= 1;
}
else
{
	$sql = "select sortnum, title, state from catalog where id=$id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$sortnum		= $row["sortnum"];
		$title			= $row["title"];
		$state			= $row["state"];
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
		if (form.title.value == "")
		{
			alert("请输入标题！");
			form.title.focus();
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
				<li><i class="fa fa-angle-right"></i><span class="active">栏目管理</span></li>
				<li><i class="fa fa-angle-right"></i><?echo $class_name?></li>
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
 							<form class="form" name="editForm" method="post" enctype="multipart/form-data" onSubmit="return check(this);">
								<div class="form-body">
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">序号</label>
												<div class="input-icon">
													<i class="fa fa-bar-chart-o tooltips" data-placement="top" data-original-title="显示顺序"></i>
													<input type="text" class="form-control" name="sortnum" value="<?=$sortnum?>" >
												</div>
											</div>
 										</div>
									</div>
									<div class="form-group">
										<label class="control-label">标题</label>
										<div class="input-icon">
											<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="标题"></i>
											<input type="text" class="form-control" name="title" value="<?=$title?>" >
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">状态</label>
										<div class="checkbox-list">
                                            <label class="checkbox-inline">
												<input type="radio" class="checkbox" name="state" value="1" <?if($state == 1){echo "checked";}?> /> 显示
											</label>
											<label class="checkbox-inline">
												<input type="radio" class="checkbox" name="state" value="0" <?if($state == 0){echo "checked";}?> /> 不显示
											</label>
										</div>
									</div>
								</div>
								<div class="form-actions">
                                    <button type="submit" class="btn blue">提交</button>
                                    <button type="reset" class="btn default">重置</button>
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