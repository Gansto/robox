<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, LINK_CLASS_ADVANCEDID) == false)
{
	info("没有权限！");
}

$id		= trim($_GET["id"]);

$listUrl = "link_class_list.php";

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum		= (int)$_POST["sortnum"];
	$name			= htmlspecialchars(trim($_POST["name"]));
	$haspic			= (int)$_POST["haspic"];

	if (empty($id))
	{
		$id = $db->getMax("link_class", "id") + 1;
		$sql = "insert into link_class(id, sortnum, name, haspic) values('$id', $sortnum, '$name', $haspic)";
	}
	else
	{
		$sql = "update link_class set sortnum=$sortnum, name='$name', haspic=$haspic where id='$id'";
	}
	$rst = $db->query($sql);
	$db->close();
	header("Location: $listUrl");
}
else
{
	if ($id == "")
	{
		$sortnum 	= $db->getMax("link_class", "sortnum") + 10;
		$haspic		= 1;
	}
	else
	{
		$sql = "select id, sortnum, name, haspic from link_class where id='$id'";
		$rst = $db->query($sql);
		if ($row = $db->fetch_array($rst))
		{
			$id				= $row["id"];
			$sortnum		= $row["sortnum"];
			$name			= $row["name"];
			$haspic			= $row["haspic"];
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
<script src="js/jquery-1.11.3.min.js"></script>
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
				<li><i class="fa fa-angle-right"></i><span class="active">链接分类管理</span></li>
			</ul>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light edit-panel">
						<div class="portlet-title">
							<div class="row">
								<div class="col-md-6 col-sm-6">
		                            <div class="caption font-red-sunglo"> <i class="fa fa-edit"></i> <strong class="caption-subject uppercase">发布/编辑</strong></div>
	                            </div>
	                            <div class="col-md-6  col-sm-6 hidden-xs">
	                            	<div class="actions pull-right"> <i class="fa fa-angle-left"></i> <a href="<?=$listUrl?>">返回列表</a></div>
	                            </div>
	                         </div>
                        </div>
                        <div class="portlet-body">
 							<form class="form" name="editForm" action="<?=$editUrl?>" method="post" enctype="multipart/form-data" onSubmit="return check(this);">
								<div class="form-body">
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">序号</label>
												<div class="input-icon">
													<i class="fa fa-bar-chart-o tooltips" data-placement="top" data-original-title="显示顺序"></i>
 													<input type="text" name="sortnum" value="<?=$sortnum?>" size="10" maxlength="8" class="form-control" />
												</div>
											</div>
											<div class="col-md-6">
												<label class="control-label">分类名称</label>
												<div class="input-icon">
													<i class="fa fa-folder-open-o tooltips" data-placement="top" data-original-title="分类名称"></i>
													<input class="form-control" type="text" name="name" value="<?=$name?>" />
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
                                        <label class="control-label">链接模式</label>
                                        <div class="checkbox-list">
											<label class="checkbox-inline"><input type="radio" name="haspic" value="1"<? if ($haspic == 1) echo " checked"?>>图片</label>
											<label class="checkbox-inline"><input type="radio" name="haspic" value="0"<? if ($haspic == 0) echo " checked"?>>文字</label>&nbsp;&nbsp;&nbsp;&nbsp;非图片链接没有上传图片功能。
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