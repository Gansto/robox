<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");
require(dirname(__FILE__) . "/uploadImg.php");

session_start();

$config_iswatermark = true;

$infoid			= trim($_GET["infoid"]);
$page			= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;
$id				= (int)$_GET["id"];

if (empty($infoid))
{
	info("指定了错误的记录！");
}

//权限检查
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && $session_admin_grade != ADMIN_ADVANCED && hasInclude($session_admin_popedom, substr($class_id, 0, CLASS_LENGTH)) != true && hasInclude($session_admin_popedom, $class_id) != true)
{
	info("没有权限！");
}


$listUrl = "info_list_list.php?infoid=$infoid&class_id=". $_SESSION['class_id'] ."&page=$page";
$editUrl = "info_list_edit.php?infoid=$infoid&class_id=". $_SESSION['class_id'] ."&page=$page&id=$id";

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum		= (int)$_POST["sortnum"];
	$title			= htmlspecialchars(trim($_POST["title"]));
	$info_class 	= trim($_POST["info_class"]);

	$pic_file	= &$_FILES["pic"];
	$pic		= uploadImg($pic_file, "gif,jpg,png,swf,fla,wmv",$config_waterpic,$config_waterpos,$config_iswatermark);			//上传图片
	$del_pic	= (int)$_POST["del_pic"];
	$content 	= $_POST["content"];

	if ($id < 1)
	{
		$aid	= $db->getMax("info_list", "id", "") + 1;
		//$sortnum = $db->getMax("info_list", "sortnum", "infoid=$infoid") + 10;
		$sql = "insert into info_list(id, sortnum, title, pic, content, infoid) values(" . ($db->getMax("info_list", "id", "") + 1) . ", $sortnum, '$title', '$pic', '$content', $infoid)";
	}
	else
	{
		if ((!empty($pic) || $del_pic == 1))
		{
			$oldPic		= $db->getTableFieldValue("info_list", "pic", "where id=$id");
			$sql = "update info_list set sortnum=$sortnum, title='$title', pic='$pic', content='$content' where id=$id";
		}
		else
		{
			$sql = "update info_list set sortnum=$sortnum, title='$title', content='$content' where id=$id";
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
		//添加失败还要删除编辑器内上传的图片
		if ($id < 1)
		{
			deleteFiles($files, 2);
		}

		info("添加/编辑信息失败！");
	}
}


if ($id < 1)
{
	$sortnum	 = $db->getMax("info_list", "sortnum", "infoid = $infoid") + 10;
}
else
{
	$sql = "select * from info_list where id=$id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$sortnum		= $row["sortnum"];
		$title			= $row["title"];
		$pic			= $row["pic"];
		$content		= $row["content"];
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
<link href="themes/css/shy.css" rel="stylesheet" />
<link href="themes/css/shy-skin.css" rel="stylesheet" />
<script src="js/jquery.js"></script>
<script src="js/common.js"></script>
<script src="js/edit.js"></script>
<script src="js/list.js" id="list" data="true"></script>
<script src="js/info.js"></script>
<script charset="utf-8" src="ke/kindeditor-all.js"></script>
<script charset="utf-8" src="ke/lang/zh-CN.js"></script>
<script>
	KindEditor.ready(function(K) {
		var editor = K.create('textarea[name="content"]', {
			uploadJson : 'ke/php/upload_json.php',
			fileManagerJson : 'ke/php/file_manager_json.php',
			width : '700px',
			height : '300px',
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
<script language="javascript">
	function check(form){
		if (!CheckInputNotEmpty("title", "请输入标题。", true)) return false;
		<?
		if ($hasPic == 1){
		?>
			if (form.pic.value != "")
			{
				var ext = form.pic.value.substr(form.pic.value.length - 3).toLowerCase();

				if (ext != "gif" && ext != "jpg" && ext != "png" && ext != "swf" && ext != "wmv")
				{
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
				<li><i class="fa fa-angle-right"></i><?=$db->getTableFieldValue("info_class", "name", "where id='$class_id'")?></li>
				<li><i class="fa fa-angle-right"></i><span class="active">信息维护</span></li>
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
										</div>
									</div>
									<div class="form-group">
                                        <label class="control-label">标题</label>
                                        <div class="input-icon">
											<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="标题"></i>
											<input type="text" class="form-control" name="title" value="<?=$title?>">
										</div>
                                    </div>
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
                                    <div class="form-group">
                                        <label class="control-label">详细内容</label>
                                        <textarea name="content" cols="100" rows="10"><?=$content?></textarea>
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