<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");
require(dirname(__FILE__) . "/uploadImg.php");

$config_iswatermark = true;

$class_id		= trim($_GET["class_id"]);
$select_class	= empty($_GET["select_class"]) ? $class_id : trim($_GET["select_class"]);
$page			= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;
$select_state	= (int)$_GET["select_state"];
$keyword		= urlencode(trim($_GET["keyword"]));
$id				= (int)$_GET["id"];
if (empty($class_id) || !checkClassID($class_id, 2))
{
	info("指定了错误的分类！");
}

if (strlen($select_class) % CLASS_LENGTH != 0 && !checkClassID($select_class, strlen($select_class) / CLASS_LENGTH))
{
	info("选择了错误的分类！");
}

//权限检查
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && $session_admin_grade != ADMIN_ADVANCED && hasInclude($session_admin_popedom, substr($class_id, 0, CLASS_LENGTH)) != true && hasInclude($session_admin_popedom, $class_id) != true)
{
	info("没有权限！");
}

$listUrl = "info_list.php?class_id=$class_id&select_class=$select_class&select_state=$select_state&keyword=$keyword&page=$page";
$editUrl = "info_edit.php?class_id=$class_id&select_class=$select_class&select_state=$select_state&keyword=$keyword&page=$page&id=$id";

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

//查询顶级分类的记录设置
$baseClassID = substr($class_id, 0, CLASS_LENGTH);
$sql = "select hasViews, hasState, hasPic, hasPic2, hasAnnex, hasIntro, hasContent, hasContent2, hasContent3, hasContent4, hasContent5, hasContent6, hasWebsite, hasAuthor, hasSource, hasKeyword, hasDescription from info_class where id='$baseClassID'";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst))
{
	$hasViews	= $row["hasViews"];
	$hasState	= $row["hasState"];
	$hasPic		= $row["hasPic"];
	$hasPic2	= $row["hasPic2"];
	$hasAnnex	= $row["hasAnnex"];
	$hasIntro	= $row["hasIntro"];
	$hasContent	= $row["hasContent"];
	$hasContent2= $row["hasContent2"];
	$hasContent3= $row["hasContent3"];
	$hasContent4= $row["hasContent4"];
	$hasContent5= $row["hasContent5"];
	$hasContent6= $row["hasContent6"];
	$hasWebsite	= $row["hasWebsite"];
	$hasAuthor	= $row["hasAuthor"];
	$hasSource	= $row["hasSource"];
	$hasKeyword	= $row["hasKeyword"];
	$hasDescription = $row["hasDescription"];

	$hasSelect	= $db->getTableFieldValue("info_class", "has_sub", "where id='$class_id'");

}
else
{
	$db->close();
	info("指定的分类不存在！");
}

//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum = (int)$_POST["sortnum"];

	if ($hasState == 1)
	{
		$state = (int)$_POST["state"];
	}
	else
	{
		$state = 1;
	}

	$hot = (int)$_POST["hot"];

	//权限 普通管理员只能发表未审核的信息
	if ($session_admin_grade == ADMIN_COMMON)
	{
		$state = 0;
	}

	$title	= htmlspecialchars(trim($_POST["title"]));

	$price 	 = trim($_POST["price"]);
	$actual  = trim($_POST["actual"]);
	$author = htmlspecialchars(trim($_POST["author"]));

	if ($hasSelect == 1)
	{
		$info_class = trim($_POST["info_class"]);
	}
	else
	{
		$info_class = $class_id;
	}

	if ($hasSource == 1)
	{
		$source = htmlspecialchars(trim($_POST["source"]));
	}
	else
	{
		$source = "";
	}

	if ($hasViews == 1)
	{
		$views = (int)($_POST["views"]);
	}
	else
	{
		$views = 0;
	}

	if ($hasWebsite == 1)
	{
		$website = htmlspecialchars(trim($_POST["website"]));
	}
	else
	{
		$website = "";
	}

	if ($hasPic == 1)
	{
		$pic_file	= &$_FILES["pic"];
		$pic		= uploadImg($pic_file, "gif,jpg,png,swf,fla,wmv",$config_waterpic,$config_waterpos,$config_iswatermark);			//上传图片
		$del_pic	= (int)$_POST["del_pic"];
	}
	else
	{
		$pic = "";
	}

	if ($hasPic2 == 1)
	{
		$pic_file2	= &$_FILES["pic2"];
		$pic2		= uploadImg($pic_file2, "gif,jpg,png,swf,fla,wmv",$config_waterpic,$config_waterpos,$config_iswatermark);			//上传图片
		$del_pic2	= (int)$_POST["del_pic2"];
	}
	else
	{
		$pic2 = "";
	}

	if ($hasAnnex == 1)
	{
		$annex_file	= &$_FILES["annex"];
		$annex		= uploadImg($annex_file, "pdf,doc,xls,ppt,rar,zip","","");	//上传附件
		$del_annex	= (int)$_POST["del_annex"];
	}
	else
	{
		$annex = "";
	}

	if ($hasKeyword == 1)
	{
		$keyword = htmlspecialchars(trim($_POST["keyword"]));
	}
	else
	{
		$keyword = "";
	}

	if ($hasDescription == 1)
	{
		$description = htmlspecialchars(trim($_POST["description"]));
	}
	else
	{
		$description = "";
	}

	if ($hasIntro == 1)
	{
		$intro = $_POST["intro"];
	}
	else
	{
		$intro = "";
	}

	if ($hasContent == 1)
	{
		$content = $_POST["content"];
		$files	 = $_POST["content_files"];
	}
	else
	{
		$content = "";
		$files	 = "";
	}

	$webcontent = $_POST["webcontent"];

	if ($hasContent2 == 1)
	{
		$content2 = $_POST["content2"];
		$files	  = $_POST["content2_files"];
	}
	else
	{
		$content2 = "";
		$files	 = "";
	}
	if ($hasContent3 == 1)
	{
		$content3 = $_POST["content3"];
		$files	 = $_POST["content3_files"];
	}
	else
	{
		$content3 = "";
		$files	 = "";
	}
	if ($hasContent4 == 1)
	{
		$content4 = $_POST["content4"];
		$files	 = $_POST["content4_files"];
	}
	else
	{
		$content4 = "";
		$files	 = "";
	}
	if ($hasContent5 == 1)
	{
		$content5 = $_POST["content5"];
		$files	 = $_POST["content5_files"];
	}
	else
	{
		$content5 = "";
		$files	 = "";
	}
	if ($hasContent6 == 1)
	{
		$content6 = $_POST["content6"];
		$files	 = $_POST["content6_files"];
	}
	else
	{
		$content6 = "";
		$files	 = "";
	}

	$create_time	= formatDate("Y-m-d H:i:s", $_POST["create_time"]);
	$now			= date("Y-m-d H:i:s");

	if (empty($title))
	{
		$db->close();
		info("填写的参数错误！");
	}

	if ($id < 1)
	{

		//日志记录
 		$db->record($_SESSION["ADMIN_ID"],$title,"新增信息" );

		$aid	 = $db->getMax("info", "id", "") + 1;
		//$sortnum = $db->getMax("info", "sortnum", "class_id='$info_class'") + 10;
		$sql = "insert into info(id, sortnum, title, views, admin_id, class_id, author, source, website, pic, annex, keyword, description, intro, content, webcontent, content2, content3, content4, content5, content6, files, create_time, modify_time, state, hot, price, actual) values(" . ($db->getMax("info", "id", "") + 1) . ", $sortnum, '$title', 0, $session_admin_id, '$info_class', '$author', '$source', '$website', '$pic', '$annex', '$keyword', '$description', '$intro', '$content', '$webcontent', '$content2', '$content3', '$content4', '$content5', '$content6', '$files', '$create_time', '$now', $state, $hot, '$price', '$actual')";
	}
	else
	{
		//日志记录
 		$db->record($_SESSION["ADMIN_ID"],$title,"修改信息" );

		//权限 普通管理员只能修改自己发表但未审核的信息
		if ($session_admin_grade == ADMIN_COMMON && ($db->getTableFieldValue("info", "state", "where id=$id") == 1 || $db->getTableFieldValue("info", "admin_id", "where id=$id") != $session_admin_id))
		{
			info("没有权限！");
		}

		if ((!empty($pic) || $del_pic == 1) && (!empty($pic2) || $del_pic2 == 1) && (!empty($annex) || $del_annex == 1))
		{
			$oldPic		= $db->getTableFieldValue("info", "pic", "where id=$id");
			$oldPic2	= $db->getTableFieldValue("info", "pic2", "where id=$id");
			$oldAnnex	= $db->getTableFieldValue("info", "annex", "where id=$id");
			$sql = "update info set sortnum=$sortnum, title='$title', views=$views, class_id='$info_class', author='$author', source='$source', website='$website', pic='$pic', pic2='$pic2', annex='$annex', keyword='$keyword', description='$description', intro='$intro', content='$content', webcontent='$webcontent', content2='$content2', content3='$content3', content4='$content4', content5='$content5', content6='$content6', files='$files', create_time='$create_time', modify_time='$now', state=$state, hot=$hot, price='$price', actual='$actual' where id=$id";
		}
		else if (!empty($pic) || $del_pic == 1)
		{
			$oldPic		= $db->getTableFieldValue("info", "pic", "where id=$id");
			$oldPic2	= "";
			$oldAnnex	= "";
			$sql = "update info set sortnum=$sortnum, title='$title', views=$views, class_id='$info_class', author='$author', source='$source', website='$website', pic='$pic', keyword='$keyword', description='$description', intro='$intro', content='$content', webcontent='$webcontent', content2='$content2', content3='$content3', content4='$content4', content5='$content5', content6='$content6', files='$files', create_time='$create_time', modify_time='$now', state=$state, hot=$hot, price='$price', actual='$actual' where id=$id";
		}
		else if (!empty($pic2) || $del_pic2 == 1)
		{
			$oldPic2		= $db->getTableFieldValue("info", "pic2", "where id=$id");
			$oldPic 	= "";
			$oldAnnex	= "";
			$sql = "update info set sortnum=$sortnum, title='$title', views=$views, class_id='$info_class', author='$author', source='$source', website='$website', pic2='$pic2', keyword='$keyword', description='$description', intro='$intro', content='$content', webcontent='$webcontent', content2='$content2', content3='$content3', content4='$content4', content5='$content5', content6='$content6', files='$files', create_time='$create_time', modify_time='$now', state=$state, hot=$hot, price='$price', actual='$actual' where id=$id";
		}
		else if (!empty($annex) || $del_annex == 1)
		{
			$oldPic		= "";
			$oldPic2	= "";
			$oldAnnex	= $db->getTableFieldValue("info", "annex", "where id=$id");
			$sql = "update info set sortnum=$sortnum, title='$title', views=$views, class_id='$info_class', author='$author', source='$source', website='$website', annex='$annex', keyword='$keyword', description='$description', intro='$intro', content='$content', webcontent='$webcontent', content2='$content2', content3='$content3', content4='$content4', content5='$content5', content6='$content6', files='$files', create_time='$create_time', modify_time='$now', state=$state, hot=$hot, price='$price', actual='$actual' where id=$id";
		}
		else
		{
			$sql = "update info set sortnum=$sortnum, title='$title', views=$views, class_id='$info_class', author='$author', source='$source', website='$website', keyword='$keyword', description='$description', intro='$intro', content='$content', webcontent='$webcontent', content2='$content2', content3='$content3', content4='$content4', content5='$content5', content6='$content6', files='$files', create_time='$create_time', modify_time='$now', state=$state, hot=$hot, price='$price', actual='$actual' where id=$id";
		}
	}


	$rst = $db->query($sql);
	//$db->close();

	if ($rst)
	{
		//修改成功后删除老图片、附件
		if ($id > 0)
		{
			deleteFile($oldPic, 1);
			deleteFile($oldPic2, 1);
			deleteFile($oldAnnex, 1);
		}

		header("Location: $listUrl");
		exit;
	}
	else
	{
		//添加或修改失败后 删除上传的图片、附件
		deleteFile($pic, 1);
		deleteFile($pic2, 1);
		deleteFile($annex, 1);
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
	$sortnum	 = $db->getMax("info", "sortnum", "class_id like '$class_id%'") + 10;
	$select_id	 = $select_class;
	$state		 = 1;
	$hot		 = 0;
	$create_time = date("Y-m-d");
}
else
{
	$sql = "select sortnum, title, views, class_id, author, source, website, pic, pic2, annex, keyword, description, intro, content, webcontent, content2, content3, content4, content5, content6, files, state, hot, create_time, price, actual from info where id=$id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$sortnum		= $row["sortnum"];
		$title			= $row["title"];
		$views			= $row["views"];
		$select_id		= $row["class_id"];
		$author			= $row["author"];
		$source			= $row["source"];
		$website		= $row["website"];
		$pic			= $row["pic"];
		$pic2			= $row["pic2"];
		$annex			= $row["annex"];
		$keyword		= $row["keyword"];
		$description    = $row["description"];
		$intro			= $row["intro"];
		$content		= $row["content"];
		$webcontent		= $row["webcontent"];
		$content2		= $row["content2"];
		$content3		= $row["content3"];
		$content4		= $row["content4"];
		$content5		= $row["content5"];
		$content6		= $row["content6"];
		$files			= $row["files"];
		$state			= $row["state"];
		$hot			= $row["hot"];
		$create_time	= $row["create_time"];
		$price			= $row["price"];
		$actual			= $row["actual"];
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
<script src="js/jquery-1.11.3.min.js"></script>
<script src="js/common.js"></script>
<script src="js/edit.js"></script>
<link href="js/cxcalendar.css" rel="stylesheet" />
<script src="js/cxcalendar.min.js"></script>
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
		var editor = K.create('textarea[name="webcontent"]', {
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
		var editor = K.create('textarea[name="content2"]', {
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
		var editor = K.create('textarea[name="content3"]', {
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
		var editor = K.create('textarea[name="content4"]', {
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
		var editor = K.create('textarea[name="content5"]', {
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
		var editor = K.create('textarea[name="content6"]', {
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
		var editor = K.create('textarea[name="intro"]', {
			uploadJson : 'ke/php/upload_json.php',
			fileManagerJson : 'ke/php/file_manager_json.php',
			width : '700px',
			height : '200px',
			pasteType : 1,
			items : [
					'source', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
					'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
					'insertunorderedlist', '|', 'link'],
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
	function check(form)
	{
		if (!CheckInputIsNumeric("sortnum", false, "序号只能使用数字。", true)) return false;
		if (!CheckInputNotEmpty("name", "栏目名称不能为空。", true)) return false;
		<?
		if ($sub_pic == 1)
		{
		?>
			if (form.pic.value != "")
			{
				var ext = form.pic.value.substr(form.pic.value.length - 3).toLowerCase();

				if (ext != "gif" && ext != "jpg" && ext != "png")
				{
					alert('图片必须是GIF、JPG、PNG格式！');
					return false;
				}
			}
		<?
		}
		?>
		<?
		if ($hasAnnex == 1)
		{
		?>
			if (form.annex.value != "")
			{
				var ext = form.annex.value.substr(form.annex.value.length - 3).toLowerCase();

				if (ext != "pdf" && ext != "doc" && ext != "xls" && ext != "ppt" && ext != "zip" && ext != "rar")
				{
					alert("附件必须是PDF、DOC、XLS、PPT、ZIP或RAR格式！");
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
	                            <div class="col-md-6 col-sm-6 hidden-xs">
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
											<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="网站标题"></i>
											<input type="text" class="form-control" name="title" value="<?=$title?>">
										</div>
                                    </div>
                                    <?
										if ($hasViews == 1){
									?>
									<div class="form-group">
                                        <label class="control-label">浏览次数</label>
                                        <div class="input-icon">
											<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="浏览次数"></i>
											<input type="text" class="form-control" name="views" value="<?=$views?>">
										</div>
                                    </div>
                                    <?
                                    	}
                                    ?>
                                    <div class="form-group">
										<div class="row">
											<?
									        if ($hasState == 1 && $session_admin_grade != ADMIN_COMMON){
											?>
											<div class="col-md-6">
												<label class="control-label">状态</label>
												<div class="checkbox-list">
		                                            <label class="checkbox-inline">
														<input type="radio" class="checkbox" name="state" value="0" <?if($state == 0) echo "checked"; ?> /> 未审核
													</label>
													<label class="checkbox-inline">
														<input type="radio" class="checkbox" name="state" value="1" <?if($state == 1) echo "checked"; ?> /> 正常
													</label>
													<label class="checkbox-inline">
														<input type="radio" class="checkbox" name="state" value="2" <?if($state == 2) echo "checked"; ?> /> 推荐
													</label>
												</div>
											</div>
									        <?
									        }
											?>
											<div class="col-md-6">
												<label class="control-label">热点</label>
												<div class="checkbox-list">
		                                            <label class="checkbox-inline">
														<input type="radio" class="checkbox" name="hot" value="0" <?if($hot == 0) echo "checked"; ?> /> 否
													</label>
													<label class="checkbox-inline">
														<input type="radio" class="checkbox" name="hot" value="1" <?if($hot == 1) echo "checked"; ?> /> 是
													</label>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">发布时间</label>
												<div class="input-icon">
													<i class="fa fa-calendar tooltips" data-placement="top" data-original-title="选择时间"></i>
													<input type="text" id="cxcalendar" class="form-control " name="create_time" value="<?=$create_time?>" readonly />
												</div>
											</div>
											<?
									        if ($hasSelect == 1){
											?>
											<div class="col-md-6">
												<label class="control-label">文章类别</label>
												<div class="checkbox-list">
													<select name="info_class" class="form-control" style="width:160px;" onChange="window.location='<?=$editUrl?>&select_class=' + this.options[this.selectedIndex].value;">
														<?
														$substr_class_id = substr($class_id, 0, CLASS_LENGTH);

														$sql = "select id, name from info_class where id like '" . $class_id . "_%' order by sortnum asc";
														$rst = $db->query($sql);
														while ($row = $db->fetch_array($rst))
														{
															$data[] = array("id" => $row["id"], "name" => $row["name"]);
														}

														$data = getNodeData($data, $class_id, CLASS_LENGTH);
														echo optionsTree($data, $select_id);
														?>
													</select>
												</div>
											</div>
									        <?
											}

											if ($hasWebsite == 1){
											?>
											<div class="col-md-6">
												<label class="control-label">链接网址</label>
		                                        <div class="input-icon">
													<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="链接网址"></i>
													<input type="text" class="form-control" name="website" value="<?=$website?>">
												</div>
											</div>
									        <?
									        }
									        ?>
									    </div>
									</div>
									<div class="form-group">
										<div class="row">
											<?
									        if ($hasSource == 1){
											?>
											<div class="col-md-6">
												<label class="control-label">文章来源</label>
		                                        <div class="input-icon">
													<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="文章来源"></i>
													<input type="text" class="form-control" name="source" value="<?=$source?>">
												</div>
											</div>
									        <?
									        }

									        if ($hasAuthor == 1){
											?>
											<div class="col-md-6">
												<label class="control-label">文章作者</label>
		                                        <div class="input-icon">
													<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="文章作者"></i>
													<input type="text" class="form-control" name="author" value="<?=$author?>">
												</div>
											</div>
									        <?
									        }
									        ?>
									    </div>
									</div>
									<?
										if($baseClassID=='150'){
									?>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">参考价格</label>
		                                        <div class="input-icon">
													<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="参考价格"></i>
													<input type="text" class="form-control" name="price" value="<?=$price?>">
												</div>
											</div>
											<div class="col-md-6">
												<label class="control-label">实际价格</label>
		                                        <div class="input-icon">
													<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="实际价格"></i>
													<input type="text" class="form-control" name="actual" value="<?=$actual?>">
												</div>
											</div>
										</div>
									</div>
									<?
										}
									?>
									<div class="form-group">
										<div class="row">
									        <?
									        if ($hasKeyword == 1){
											?>
											<div class="col-md-6">
												<label class="control-label">关键词</label>
		                                        <div class="input-icon">
													<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="关键词"></i>
													<input type="text" class="form-control" name="keyword" value="<?=$keyword?>">
												</div>
											</div>
									        <?
									        }
											?>
											<?
												if ($hasDescription == 1){
											?>
											<div class="col-md-6">
												<label class="control-label">描述词</label>
		                                        <div class="input-icon">
													<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="描述词"></i>
													<input type="text" class="form-control" name="description" value="<?=$description?>">
												</div>
											</div>
									        <?
									        }
											?>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<?
											if ($hasPic == 1){
											?>
											<div class="col-md-4">
												<label class="control-label">缩略图</label>
		                                        <div class="checkbox-list">
													<label class="checkbox-inline">
														<input class="form-control form-file" type="file" name="pic" size="40">
													</label>
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
									        <?
											if ($hasPic2 == 1){
											?>
											<div class="col-md-4">
												<label class="control-label">备用图</label>
		                                        <div class="checkbox-list">
													<label class="checkbox-inline">
														<input class="form-control form-file" type="file" name="pic2" size="40">
													</label>
													<?
								                    if ($pic2 != ""){
								                    ?>
								                        <label class="checkbox-inline"><input type="checkbox" name="del_pic2" value="1"> 删除现有图片</label>
								                    <?
								                    }
									                ?>
												</div>
											</div>
									        <?
									        }
									        ?>
									        <?
									        if ($hasAnnex == 1){
											?>
											<div class="col-md-4">
												<label class="control-label">附件</label>
		                                        <div class="checkbox-list">
													<label class="checkbox-inline">
														<input class="form-control form-file" type="file" name="annex" size="40" />
													</label>
													<?
								                    if ($annex != ""){
								                    ?>
								                        <label class="checkbox-inline"><input type="checkbox" name="del_annex" value="1"> 删除现有附件</label>
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
									<?
									if ($hasIntro == 1){
									?>
									<div class="form-group">
										<label class="control-label">简要介绍</label>
										<textarea name="intro" cols="50" rows="5"><?php echo $intro; ?></textarea>
									</div>
							        <?
							        }

							        if ($hasContent == 1){
									?>
									<div class="form-group">
										<label class="control-label">PC详细内容</label>
										<textarea name="content"><?php echo $content; ?></textarea>
									</div>
									<div class="form-group">
										<label class="control-label">SJ详细内容</label>
										<textarea name="webcontent"><?php echo $webcontent; ?></textarea>
									</div>
								    <?
								    }

								    if ($hasContent2 == 1){
									?>
									<div class="form-group">
										<label class="control-label">详细内容2</label>
										<textarea name="content2"><?php echo $content2; ?></textarea>
									</div>
								    <?
								    }

								    if ($hasContent3 == 1){
									?>
									<div class="form-group">
										<label class="control-label">详细内容3</label>
										<textarea name="content3"><?php echo $content3; ?></textarea>
									</div>
								    <?
								    }

								    if ($hasContent4 == 1){
									?>
									<div class="form-group">
										<label class="control-label">详细内容4</label>
										<textarea name="content4"><?php echo $content4; ?></textarea>
									</div>
								    <?
								    }

								    if ($hasContent5 == 1){
									?>
									<div class="form-group">
										<label class="control-label">详细内容5</label>
										<textarea name="content5"><?php echo $content5; ?></textarea>
									</div>
								    <?
								    }
								    if ($hasContent6 == 1){
									?>
									<div class="form-group">
										<label class="control-label">详细内容6</label>
										<textarea name="content6"><?php echo $content6; ?></textarea>
									</div>
								    <?
								    }
									?>
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
<script>$('#cxcalendar').cxCalendar({type: 'date',format: 'YYYY-MM-DD'});</script>
<?
	require_once("foot.php");
?>
</body>
</html>