<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");
require(dirname(__FILE__) . "/uploadImg.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, BANNER_ADVANCEDID) == false)
{
	info("没有权限！");
}

$id				= (int)$_GET["id"];
$class_id		= trim($_GET["class_id"]);

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

if (!empty($class_id))
{
	$sql = "select id, name, add_deny, delete_deny, hasCon, hasPic1 from banner_class where id=$class_id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$class_name		= $row["name"];
		$add_deny		= $row["add_deny"];
		$delete_deny	= $row["delete_deny"];
		$hasCon			= $row["hasCon"];
		$hasPic1		= $row["hasPic1"];
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

$listUrl = "banner_list.php?class_id=$class_id";
$editUrl = "banner_edit.php?class_id=$class_id&id=$id";

//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum		= (int)$_POST["sortnum"];
	$title			= htmlspecialchars(trim($_POST["title"]));
	$url 			= htmlspecialchars(trim($_POST["url"]));
	$width			= (int)$_POST["width"];
	$height			= (int)$_POST["height"];
	$content 		= $_POST["content"];

	$pic_file		= &$_FILES["pic"];
	$pic			= uploadImg($pic_file, "gif,jpg,png,swf,fla,wmv",$config_waterpic,$config_waterpos);			//上传图片
	$del_pic		= (int)$_POST["del_pic"];

	$pic_file1		= &$_FILES["pic1"];
	$pic1			= uploadImg($pic_file1, "gif,jpg,png,swf,fla,wmv",$config_waterpic,$config_waterpos);			//上传图片
	$del_pic1		= (int)$_POST["del_pic1"];

	$state			= (int)$_POST["state"];

	if (empty($title))
	{
		$db->close();
		info("填写的参数错误！");
	}

	if ($id < 1)
	{
		//$sortnum = $db->getMax("banner", "sortnum", "class_id='$class_id'") + 10;
		$sql = "insert into banner (id, class_id, sortnum, title, url, width, height, pic, pic1, content, state) values(" . ($db->getMax("banner", "id", "") + 1) . ", $class_id, $sortnum, '$title', '$url', $width, $height, '$pic', '$pic1', '$content', $state)";
		echo $sql;
	}
	else
	{
		if ((!empty($pic) || $del_pic == 1) && (!empty($pic1) || $del_pic1 == 1))
		{
			$oldPic		= $db->getTableFieldValue("banner", "pic", "where id=$id");
			$oldPic1	= $db->getTableFieldValue("banner", "pic1", "where id=$id");
			$sql = "update banner set sortnum=$sortnum, title='$title', url='$url', width=$width, height=$height, pic='$pic', pic1='$pic1', content='$content', state=$state where id=$id";
		}
		else if (!empty($pic) || $del_pic == 1){
			$oldPic		= $db->getTableFieldValue("banner", "pic", "where id=$id");
			$oldPic1	= "";
			$sql = "update banner set sortnum=$sortnum, title='$title', url='$url', width=$width, height=$height, pic='$pic', content='$content', state=$state where id=$id";
		}
		else if (!empty($pic1) || $del_pic1 == 1){
			$oldPic		= "";
			$oldPic1	= $db->getTableFieldValue("banner", "pic1", "where id=$id");
			$sql = "update banner set sortnum=$sortnum, title='$title', url='$url', width=$width, height=$height, pic1='$pic1', content='$content', state=$state where id=$id";
		}
		else
		{
			$sql = "update banner set sortnum=$sortnum, title='$title', url='$url', width=$width, height=$height, content='$content', state=$state where id=$id";
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
			deleteFile($oldPic1, 1);
		}

		header("Location: $listUrl");
		exit;
	}
	else
	{
		//添加或修改失败后 删除上传的图片、附件
		deleteFile($pic, 1);
		deleteFile($pic1, 1);
	}
}

if ($id < 1)
{
	$sortnum		= $db->getMax("banner", "sortnum", "class_id=$class_id") + 10;
	$state			= 1;
}
else
{
	$sql = "select sortnum, title, url, width, height, pic, pic1, content, state from banner where id=$id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$sortnum		= $row["sortnum"];
		$title			= $row["title"];
		$url			= $row["url"];
		$width			= $row["width"];
		$height			= $row["height"];
		$pic			= $row["pic"];
		$pic1			= $row["pic1"];
		$content		= $row["content"];
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
<script charset="utf-8" src="ke/kindeditor-all.js"></script>
<script charset="utf-8" src="ke/lang/zh-CN.js"></script>
<script language="javascript">
	function check(form)
	{
		if (form.title.value == "")
		{
			alert("请输入标题！");
			form.title.focus();
			return false;
		}

		if (form.width.value != "" && !/^[0-9]+$/.exec(form.width.value))
		{
			alert("宽度填写错误！");
			form.width.focus();
			return false;
		}
		if (form.height.value != "" && !/^[0-9]+$/.exec(form.height.value))
		{
			alert("高度填写错误！");
			form.height.focus();
			return false;
		}

		if (form.pic.value != "")
		{
			var ext = form.pic.value.substr(form.pic.value.length - 3).toLowerCase();

			if (ext != "gif" && ext != "jpg" && ext != "png" && ext != "swf" && ext != "wmv")
			{
				alert("图片必须是GIF、JPG或PNG格式！");
				return false;
			}
		}

		if (form.pic1.value != "")
		{
			var ext = form.pic1.value.substr(form.pic1.value.length - 3).toLowerCase();

			if (ext != "gif" && ext != "jpg" && ext != "png" && ext != "swf" && ext != "wmv")
			{
				alert("图片必须是GIF、JPG或PNG格式！");
				return false;
			}
		}

		return true;
	}
</script>
<script>
	KindEditor.ready(function(K) {
		var editor = K.create('textarea[name="content"]', {
			uploadJson : 'ke/php/upload_json.php',
			fileManagerJson : 'ke/php/file_manager_json.php',
			items : [
						'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
						'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
						'insertunorderedlist', '|', 'emoticons', 'image', 'link'],
			width : '700px',
			height : '200px',
			pasteType : 1,
			allowFileManager : true,
			afterCreate : function() {
				var self = this;
				K.ctrl(document, 13, function() {
					self.sync();
					K('form[name=editForm]')[0].submit();
				});
				K.ctrl(self.edit.doc, 13, function() {
					self.sync();
					K('form[name=editForm]')[0].submit();
				});
			}
		});
	});
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
				<li><i class="fa fa-angle-right"></i><span class="active">Banner管理</span></li>
				<li><i class="fa fa-angle-right"></i><?echo $class_name?></li>
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
										<label class="control-label">链接地址</label>
										<div class="input-icon">
											<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="链接地址"></i>
											<input type="text" class="form-control" name="url" value="<?=$url?>" >
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">宽度</label>
												<div class="input-icon">
													<input type="text" class="form-control" name="width" value="<?=$width?>" placeholder="仅需输入数字">
												</div>
											</div>
											<div class="col-md-6">
												<label class="control-label">高度</label>
												<div class="input-icon">
 													<input type="text" class="form-control"  name="height" value="<?=$height?>" placeholder="仅需输入数字">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
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
											<div class="col-md-6">
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
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<?
												if($hasCon==1){
											?>
											<div class="col-md-6">
												<label class="control-label">内容</label>
		                                        <textarea name="content"><?php echo $content; ?></textarea>
		                                    </div>
		                                    <?
		                                    	}
		                                    ?>
		                                    <?
						                    if($hasPic1==1){
						                    ?>
		                                    <div class="col-md-6">
												<label class="control-label">附件图</label>
												<div class="checkbox-list">
													<label class="checkbox-inline"><input class="form-control form-file" type="file" name="pic1" size="40"></label>
													<?
								                    if ($pic1 != ""){
								                    ?>
								                    <label class="checkbox-inline"><input type="checkbox" name="del_pic1" value="1"> 删除现有图片</label>
													<?
								                    }
									                ?>
												</div>
		                                    </div>
		                                    <?
								                }
									        ?>
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