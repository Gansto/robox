<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");
require(dirname(__FILE__) . "/uploadImg.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, ADVER_ADVANCEDID) == false)
{
	info("没有权限！");
}


$id = (int)$_GET["id"];


$listUrl = "adver_list.php";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//提交
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$title		= htmlspecialchars(trim($_POST["title"]));
	$mode		= trim($_POST["mode"]);
	$url		= htmlspecialchars(trim($_POST["url"]));
	$width		= (int)$_POST["width"];
	$height		= (int)$_POST["height"];
	$time		= (int)$_POST["time"];
	$pic_file	= &$_FILES["pic"];
	$state		= (int)$_POST["state"];

	if (empty($title) || ($mode != "popup" && $mode != "float" && $mode != "hangL" && $mode != "hangR" && $mode != "hangLR" && $mode != "bigScreen"))
	{
		$db->close();
		info("填写的参数不完整！");
	}

	//上传图片 uploadImg(文件对象, 允许的文件格式,以逗号隔开)
	$pic = uploadImg($pic_file, "gif,jpg,png,swf",$config_waterpic,$config_waterpos);

	if ($id < 1)
	{
		$sql = "insert into adver(id, title, url, pic, width, height, time, mode, state) values(" . ($db->getMax("adver", "id", "") + 1) . ", '$title', '$url', '$pic', $width, $height, $time, '$mode', $state)";
	}
	else
	{
		if ((int)$_POST["del_pic"] == 1 || !empty($pic))
		{
			$oldPic	= $db->getTableFieldValue("adver", "pic", "where id=$id");
			$sql	= "update adver set title='$title', url='$url', pic='$pic', width=$width, height=$height, time=$time, mode='$mode', state=$state where id=$id";
		}
		else
		{
			$sql = "update adver set title='$title', url='$url', width=$width, height=$height, time=$time, mode='$mode', state=$state where id=$id";
		}
	}

	$rst = $db->query($sql);
	$db->close();

	if($rst)
	{
		//删除老图片
		deleteFile($oldPic, 1);
		header("Location: $listUrl");
		exit();
	}
	else
	{
		info("添加/编辑广告失败！");
	}
}

if ($id < 1)
{
	$state	= 1;
	$mode	= "popup";
	$width	= 100;
	$height	= 100;
}
else
{
	$sql = "select title, mode, url, width, height, time, pic, state from adver where id=$id";
	$rst = $db->query($sql);
	if($row = $db->fetch_array($rst))
	{
		$title	= $row["title"];
		$mode	= $row["mode"];
		$url	= $row["url"];
		$width	= $row["width"];
		$height	= $row["height"];
		$time	= $row["time"];
		$pic	= $row["pic"];
		$state	= $row["state"];
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
<link href="themes/css/shy.css" rel="stylesheet" />
<link href="themes/css/shy-skin.css" rel="stylesheet" />
<script language="javascript">
function check(form)
{
	if (form.title.value == "")
	{
		alert("请输入广告标题。");
		form.title.focus();
		return false;
	}

	if (form.pic.value != "")
	{
		var ext = form.pic.value.substr(form.pic.value.length - 3).toLowerCase();

		if (ext != "gif" && ext != "jpg" && ext != "png" && ext != "swf" && ext != "wmv")
		{
			alert("广告文件必须是GIF、JPG、PNG或SWF格式！");
			return false;
		}
	}

	if (form.width.value == "" || !/^[1-9][0-9]*$/.exec(form.width.value))
	{
		alert("请输入正确的宽度数值。");
		form.width.focus();
		return false;
	}
	if (form.height.value == "" || !/^[1-9][0-9]*$/.exec(form.height.value))
	{
		alert("请输入正确的高度数值。");
		form.height.focus();
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
				<li><i class="fa fa-angle-right"></i>高级管理</li>
				<li><i class="fa fa-angle-right"></i><span class="active">广告管理</span></li>
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
 							<form  class="form" name="editForm" method="post" enctype="multipart/form-data" onSubmit="return check(this);">
								<div class="form-body">
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">标题</label>
												<div class="input-icon">
 													<input type="text" class="form-control"  name="title" value="<?=$title?>" >
												</div>
											</div>
											<div class="col-md-6">
												<label class="control-label">链接地址</label>
												<div class="input-icon">
													<input type="text" class="form-control" name="url" value="<?=$url?>" >
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">是否显示</label>
										<div class="checkbox-list">
											<label class="checkbox-inline"><input type="radio" class="checkbox" name="state" value="0" <?if($state == 0){echo "checked";}?> /> 不显示</label>
											<label class="checkbox-inline"><input type="radio" class="checkbox" name="state" value="1" <?if($state == 1){echo "checked";}?> /> 显示</label>
                                        </div>
									</div>
									<div class="form-group">
										<label class="control-label">广告类型</label>
										<div class="checkbox-list">
											<label class="checkbox-inline"><input type="radio" class="checkbox" name="mode" value="popup" <? if ($mode == "popup") echo "checked";?> /> 弹出广告</label>
											<label class="checkbox-inline"><input type="radio" class="checkbox" name="mode" value="float" <? if ($mode == "float") echo "checked";?> /> 漂浮广告</label>
											<label class="checkbox-inline"><input type="radio" class="checkbox" name="mode" value="hangL" <? if ($mode == "hangL") echo "checked";?> /> 左侧门帘</label>
											<label class="checkbox-inline"><input type="radio" class="checkbox" name="mode" value="hangR" <? if ($mode == "hangR") echo "checked";?> /> 右侧门帘</label>
											<label class="checkbox-inline"><input type="radio" class="checkbox" name="mode" value="hangLR" <? if ($mode == "hangLR") echo "checked";?> /> 左右门帘</label>
											<label class="checkbox-inline"><input type="radio" class="checkbox" name="mode" value="bigScreen" <? if ($mode == "bigScreen") echo "checked";?> /> 拉屏广告</label>
                                        </div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">拉屏时间</label>
												<div class="input-icon">
 													<input type="text" class="form-control" name="time" value="<?=$time?>" placeholder="仅需输入数字：单位秒">
												</div>
											</div>
											<div class="col-md-6">
												<label class="control-label">广告尺寸</label>
												<div class="checkbox-list">
													<label class="checkbox-inline">
													宽度：<input type="text" name="width" value="<?=$width?>" size="4" maxlength="4" />&nbsp;&nbsp;
													</label>
													<label class="checkbox-inline">
													高度：<input type="text" name="height" value="<?=$height?>" size="4" maxlength="4" />&nbsp;&nbsp;
													</label>单位：像素。
		                                        </div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">广告文件</label>
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
