<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, CATALOG_CLASS_ADVANCEDID) == false)
{
	info("没有权限！");
}

$id		= trim($_GET["id"]);

$listUrl = "catalog_class_list.php";

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum		= (int)$_POST["sortnum"];
	$name			= htmlspecialchars(trim($_POST["name"]));
	$add_deny		= (int)$_POST["add_deny"];
	$delete_deny	= (int)$_POST["delete_deny"];

	if (empty($id))
	{
		$id = $db->getMax("catalog_class", "id") + 1;
		$sql = "insert into catalog_class(id, sortnum, name, add_deny, delete_deny) values('$id', $sortnum, '$name', $add_deny, $delete_deny)";
	}
	else
	{
		$sql = "update catalog_class set sortnum=$sortnum, name='$name', add_deny=$add_deny, delete_deny=$delete_deny where id='$id'";
	}
	$rst = $db->query($sql);
	$db->close();
	header("Location: $listUrl");
}
else
{
	if ($id == "")
	{
		$sortnum		= $db->getMax("catalog_class", "sortnum") + 10;
		$add_deny		= 0;
		$delete_deny	= 0;
	}
	else
	{
		$sql = "select id, sortnum, name, add_deny, delete_deny from catalog_class where id='$id'";
		$rst = $db->query($sql);
		if ($row = $db->fetch_array($rst))
		{
			$id				= $row["id"];
			$sortnum		= $row["sortnum"];
			$name			= $row["name"];
			$add_deny		= $row["add_deny"];
			$delete_deny	= $row["delete_deny"];
		}
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
	function check(form){
		if (form.name.value == "")
		{
			alert("分类名称不能为空！");
			form.name.focus();
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
			<ul class="page-breadcrumb breadcrumb">
				<li><i class="fa fa-home"></i><a href="index.php">首页</a></li>
				<li><i class="fa fa-angle-right"></i>栏目分类管理</li>
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
													<i class="fa fa-bar-chart-o tooltips" data-placement="top" data-original-title="显示顺序"></i>
													<input type="text" class="form-control" name="sortnum" value="<?=$sortnum?>" >
												</div>
											</div>
 										</div>
									</div>
									<div class="form-group">
                                        <label class="control-label">栏目分类</label>
                                        <div class="input-icon">
											<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="栏目分类"></i>
											<input type="text" name="name" value="<?=$name?>" size="50" maxlength="50" class="form-control" />
										</div>
                                    </div>
                                    <div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">禁止增加</label>
												<div class="checkbox-list">
		                                        	<label class="checkbox-inline">
													<input type="radio" class="checkbox" name="add_deny" value="1"<? if ($add_deny == 1) echo " checked"?>>拒绝
													</label>
													<label class="checkbox-inline">
						                            <input type="radio" class="checkbox" name="add_deny" value="0"<? if ($add_deny == 0) echo " checked"?>>允许
						                            </label>
												</div>
											</div>
											<div class="col-md-6">
												<label class="control-label">禁止删除</label>
												<div class="checkbox-list">
		                                        	<label class="checkbox-inline">
													<input type="radio" class="checkbox" name="delete_deny" value="1"<? if ($delete_deny == 1) echo " checked"?>>拒绝
													</label>
													<label class="checkbox-inline">
						                            <input type="radio" class="checkbox" name="delete_deny" value="0"<? if ($delete_deny == 0) echo " checked"?>>允许
						                            </label>
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