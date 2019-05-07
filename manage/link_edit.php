<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");
require(dirname(__FILE__) . "/uploadImg.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, LINK_ADVANCEDID) == false)
{
	info("没有权限！");
}

$config_iswatermark = false;

$id				= (int)$_GET["id"];
$class_id		= trim($_GET["class_id"]);

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

if (!empty($class_id))
{
	$sql = "select id, name, haspic from link_class where id=$class_id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$class_name		= $row["name"];
		$class_haspic	= $row["haspic"];
	}
}
else
{
	info("指定了错误的分类！");
}

$listUrl = "link_list.php?class_id=$class_id";
$editUrl = "link_edit.php?class_id=$class_id&id=$id";

//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum	= (int)$_POST["sortnum"];
	$name		= htmlspecialchars(trim($_POST["name"]));
	$url 	= htmlspecialchars(trim($_POST["url"]));

	$pic_file	= &$_FILES["pic"];
	$pic		= uploadImg($pic_file, "gif,jpg,png,swf,fla,wmv",$config_waterpic,$config_waterpos,$config_iswatermark );			//上传图片
	$del_pic	= (int)$_POST["del_pic"];

	$state		= (int)$_POST["state"];

	if (empty($name))
	{
		$db->close();
		info("填写的参数错误！");
	}

	if ($id < 1)
	{
		//$sortnum = $db->getMax("link", "sortnum", "class_id='$class_id'") + 10;
		$sql = "insert into link (id, class_id, sortnum, name, url, pic, state) values(" . ($db->getMax("link", "id", "") + 1) . ", $class_id, $sortnum, '$name', '$url', '$pic', $state)";
		echo $sql;
	}
	else
	{
		if ((!empty($pic) || $del_pic == 1))
		{
			$oldPic		= $db->getTableFieldValue("link", "pic", "where id=$id");
			$sql = "update link set sortnum=$sortnum, name='$name', url='$url', pic='$pic', state=$state where id=$id";
		}
		else
		{
			$sql = "update link set sortnum=$sortnum, name='$name', url='$url', state=$state where id=$id";
		}
	}

	$rst = $db->query($sql);
	$db->close();

	if ($rst)
	{
		//修改成功后删除老图片、附件
		if ($id > 0)
		{
			deleteFile($oldPic, 1);
		}

		header("Location: $listUrl");
		exit;
	}
	else
	{
		//添加或修改失败后 删除上传的图片、附件
		deleteFile($pic, 1);
	}
}


if ($id < 1)
{
	$sortnum	 = $db->getMax("link", "sortnum", "class_id=$class_id") + 10;
	$state		 = 1;
}
else
{
	$sql = "select sortnum, name, url, pic, state from link where id=$id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$sortnum		= $row["sortnum"];
		$name			= $row["name"];
		$url			= $row["url"];
		$pic			= $row["pic"];
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
	function check(form){
		if (form.name.value == "")
		{
			alert("链接名称不能为空！");
			form.name.focus();
			return false;
		}
		<?
		if ($class_haspic == 1){
		?>
			if (form.pic.value != ""){
				var ext = form.pic.value.substr(form.pic.value.length - 3).toLowerCase();
				if (ext != "gif" && ext != "jpg" && ext != "png" && ext != "swf" && ext != "wmv"){
					alert("图片必须是GIF、JPG或PNG格式！");
					return false;
				}
			}
		<?
		}
		?>
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
 							<form class="form" name="editForm" method="post" enctype="multipart/form-data" onSubmit="return check(this);">
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
												<label class="control-label">状态</label>
		                                        <div class="input-icon">
													<label class="checkbox-inline"><input type="radio" name="state" value="1"<? if ($state == 1) echo " checked"?>>显示</label>
													<label class="checkbox-inline"><input type="radio" name="state" value="0"<? if ($state == 0) echo " checked"?>>不显示</label>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">链接名称</label>
												<div class="input-icon">
													<i class="fa fa-folder-open-o tooltips" data-placement="top" data-original-title="链接名称"></i>
													<input class="form-control" type="text" name="name" value="<?=$name?>" />
												</div>
											</div>
											<div class="col-md-6">
												<label class="control-label">链接网址</label>
												<div class="input-icon">
													<i class="fa fa-folder-open-o tooltips" data-placement="top" data-original-title="链接网址"></i>
													<input class="form-control" type="text" name="url" value="<?=$url?>" />
												</div>
											</div>
										</div>
									</div>
									<?
									if ($class_haspic == 1){
									?>
									<div class="form-group">
										<label class="control-label">缩略图</label>
                                        <div class="checkbox-list">
											<label class="checkbox-inline"><input class="form-control form-file" type="file" name="pic" size="40"></label>
											<?
						                    if ($pic != ""){
						                    ?>
						                        <label class="checkbox-inline"><input type="checkbox" name="del_pic" value="1"> 删除现有图片</label>
						                    <?
						                    }
							                ?>
										</div>
									</div>
					                <?
					                }
									?>
									<div class="form-actions">
	                                    <button type="submit" class="btn blue">提交</button>
	                                    <button type="reset" class="btn default">重置</button>
	                                </div>
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