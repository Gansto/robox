<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");
require(dirname(__FILE__) . "/uploadImg.php");

$config_iswatermark = false;

$class_id	= trim($_GET["class_id"]);
$sup_class	= empty($_GET["sup_class"]) ? $class_id : trim($_GET["sup_class"]);
$id			= trim($_GET["id"]);
if (empty($class_id) || !checkClassID($class_id, 2))
{
	info("参数有误！");
}

if (strlen($sup_class) % CLASS_LENGTH != 0 && !checkClassID($sup_class, strlen($sup_class) / CLASS_LENGTH))
{
	info("参数有误！");
}

$sup_level = strlen($sup_class) / CLASS_LENGTH;

if (!empty($id) && !checkClassID($id, $sup_level + 1))
{
	info("参数有误！");
}


$listUrl = "third_class_list.php?class_id=$class_id&sup_class=$sup_class";
$backUrl = "second_class_list.php?class_id=" . substr($class_id, 0, CLASS_LENGTH);


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//查询顶级分类的记录设置等
$sql = "select max_level, sub_content, sub_pic, info_state, hasViews, hasState, hasPic, hasAnnex, hasIntro, hasContent, hasContent2, hasContent3, hasContent4, hasContent5, hasContent6, hasWebsite, hasAuthor, hasSource, hasKeyword from info_class where id='" . substr($class_id, 0, CLASS_LENGTH) . "'";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst))
{
	$max_level		 = $row["max_level"];
	$sub_pic		 = $row["sub_pic"];
	$sub_content	 = $row["sub_content"];
	//$sup_info_state	 = $row["info_state"];
	$sup_info_state	 = $db->getTableFieldValue("info_class", "info_state", "where id='$sup_class'");
	$hasViews		 = $row["hasViews"];
	$hasState		 = $row["hasState"];
	$hasPic			 = $row["hasPic"];
	$hasAnnex		 = $row["hasAnnex"];
	$hasIntro		 = $row["hasIntro"];
	$hasContent		 = $row["hasContent"];
	$hasContent2	 = $row["hasContent2"];
	$hasContent3	 = $row["hasContent3"];
	$hasContent4	 = $row["hasContent4"];
	$hasContent5	 = $row["hasContent5"];
	$hasContent6	 = $row["hasContent6"];
	$hasWebsite		 = $row["hasWebsite"];
	$hasAuthor		 = $row["hasAuthor"];
	$hasSource		 = $row["hasSource"];
	$hasKeyword		 = $row["hasKeyword"];
}
else
{
	$db->close();
	info("指定的二级分类不存在！");
}


//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum		= (int)$_POST["sortnum"];
	$name			= htmlspecialchars(trim($_POST["name"]));
	$en_name		= htmlspecialchars(trim($_POST["en_name"]));
	$state			= (int)$_POST["state"];
	$info_state		= trim($_POST["info_state"]);
	$has_sub		= (int)$_POST["has_sub"];

	if ($sub_pic == 1)
	{
		$pic_file	= &$_FILES["pic"];
		$pic		= uploadImg($pic_file, "gif,jpg,png",$config_waterpic,$config_waterpos,$config_iswatermark);
		$del_pic	= (int)$_POST["del_pic"];
	}
	else
	{
		$pic	 = "";
		$del_pic = 0;
	}

	if ($sub_content == 1)
	{
		$content = $_POST["content"];
		$files	 = $_POST["content_files"];
	}
	else
	{
		$content = "";
		$files	 = "";
	}

	if ($name == "" || ($info_state != "pic" && $info_state != "list" && $info_state != "content" && $info_state != "custom"))
	{
		$db->close();
		info("填写的参数错误！");
	}

	if (empty($id))
	{
		$id = $db->getMax("info_class", "id", "id like '" . $sup_class . CLASS_SPACE . "'");
		$id = empty($id) ? $sup_class . CLASS_DEFAULT : substr($id, 0, strlen($id) - CLASS_LENGTH) . (substr($id, strlen($id) - CLASS_LENGTH) + 1);

		//检查分类ID号是否存在
		if ($db->getCount("info_class", "id='$id'") > 0)
		{
			info("不能再增加分类！");
		}

		$sql = "insert into info_class(id, sortnum, name, en_name, pic, content, files, info_state, max_level, has_sub, sub_content, sub_pic, hasViews, hasState, hasPic, hasAnnex, hasIntro, hasContent, hasContent2, hasContent3, hasContent4, hasContent5, hasContent6, hasWebsite, hasAuthor, hasSource, hasKeyword, state) values('$id', $sortnum, '$name', '$en_name', '$pic', '$content', '$files', '$info_state', 0, $has_sub, $sub_content, $sub_pic, $hasViews, $hasState, $hasPic, $hasAnnex, $hasIntro, $hasContent, $hasContent2, $hasContent3, $hasContent4, $hasContent5, $hasContent6, $hasWebsite, $hasAuthor, $hasSource, $hasKeyword, $state)";
	}
	else
	{
		//若分类下有子类，则这个分类是否允许子类只能是允许
		if ($has_sub == 0 && $db->getCount("info_class", "id like '" . $id . CLASS_SPACE . "'") > 0)
		{
			$db->close();
			info("分类下有子类，是否有子类不可以拒绝！");
		}

		if (!empty($pic) || $del_pic == 1)
		{
			$oldPic	= $db->getTableFieldValue("info_class", "pic", "where id='$id'");
			$sql	= "update info_class set sortnum=$sortnum, name='$name', en_name='$en_name', pic='$pic', content='$content', files='$files', info_state='$info_state', max_level=0, has_sub=$has_sub, sub_content=$sub_content, sub_pic=$sub_pic, hasViews=$hasViews, hasState=$hasState, hasPic=$hasPic, hasAnnex=$hasAnnex, hasIntro=$hasIntro, hasContent=$hasContent, hasContent2=$hasContent2, hasContent3=$hasContent3, hasContent4=$hasContent4, hasContent5=$hasContent5, hasContent6=$hasContent6, hasWebsite=$hasWebsite, hasAuthor=$hasAuthor, hasSource=$hasSource, hasKeyword=$hasKeyword, state=$state where id='$id'";
		}
		else
		{
			$sql = "update info_class set sortnum=$sortnum, name='$name', en_name='$en_name', content='$content', files='$files', info_state='$info_state', max_level=0, has_sub=$has_sub, sub_content=$sub_content, sub_pic=$sub_pic, hasViews=$hasViews, hasState=$hasState, hasPic=$hasPic, hasAnnex=$hasAnnex, hasIntro=$hasIntro, hasContent=$hasContent, hasContent2=$hasContent2, hasContent3=$hasContent3, hasContent4=$hasContent4, hasContent5=$hasContent5, hasContent6=$hasContent6, hasWebsite=$hasWebsite, hasAuthor=$hasAuthor, hasSource=$hasSource, hasKeyword=$hasKeyword, state=$state where id='$id'";
		}

		//日志记录
		$db->record($_SESSION["ADMIN_ID"],$name,"修改分类" );
	}

	$rst = $db->query($sql);
	$db->close();

	if ($rst)
	{
		//修改时 删除老图片
		if (!empty($id) && (!empty($pic) || $del_pic == 1))
		{
			deleteFile($oldPic, 1);
		}

		header("Location: $listUrl");
		exit;
	}
	else
	{
		//添加失败了 删除上传的图片
		if (empty($id))
		{
			deleteFile($pic, 1);
			deleteFiles($files, 2);
		}

		info("添加/编辑分类失败！");
	}
}

if ($id == "")
{
	$sortnum 	= $db->getMax("info_class", "sortnum", "id like '" . $sup_class . CLASS_SPACE . "'") + 10;
	$info_state = "content";
	$state		= 1;
	$has_sub	= 1;
}
else
{
	$sql = "select id, sortnum, name, en_name, pic, content, files, info_state, has_sub, state from info_class where id='$id'";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$id				= $row["id"];
		$sortnum		= $row["sortnum"];
		$name			= $row["name"];
		$en_name		= $row["en_name"];

		if ($sub_pic == 1)
		{
			$pic = $row["pic"];
		}
		else
		{
			$pic = "";
		}

		if ($sub_content == 1)
		{
			$content = $row["content"];
			$files	 = $row["files"];
		}
		else
		{
			$content = "";
			$files	 = "";
		}

		$info_state		= $row["info_state"];
		$has_sub		= $row["has_sub"];
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
		return true;
	}
	function InfoStateChange()
	{
		$("div.class_info_state").click(function(){
			$(this).find(":radio").attr("checked","checked");
		});
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
				<li><i class="fa fa-angle-right"></i><span class="active">栏目三级分类</span></li>
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
	                            	<div class="actions pull-right">
	                            	<i class="fa fa-angle-left"></i> <a href="<?=$backUrl?>">返回二级列表</a>&nbsp;
	                            	<i class="fa fa-angle-left"></i> <a href="<?=$listUrl?>">返回三级列表</a>
	                            	</div>
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
                                        <label class="control-label">分类名称</label>
                                        <div class="input-icon">
											<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="分类名称"></i>
											<input type="text" name="name" value="<?=$name?>" size="50" maxlength="50" class="form-control" />
										</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">英文名称</label>
                                        <div class="input-icon">
											<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="英文名称"></i>
											<input type="text" name="en_name" value="<?=$en_name?>" size="50" maxlength="50" class="form-control" />
										</div>
                                    </div>
                                    <?
					                if ($session_admin_grade == ADMIN_HIDDEN){
									?>
										<div class="form-group">
	                                        <label class="control-label">是否允许删除</label>
	                                        <div class="checkbox-list">
												<label class="checkbox-inline">
												<input type="radio" class="checkbox" name="state" value="1"<? if ($state == 1) echo " checked"?>>允许
												</label>
												<label class="checkbox-inline">
					                            <input type="radio" class="checkbox" name="state" value="0"<? if ($state == 0) echo " checked"?>>拒绝
					                            </label>
											</div>
	                                    </div>
					                <?
					                }
									else{
									?>
					                	<input type="hidden" name="state" value="<?=$state?>">
					                <?
									}
					                if ($max_level > $sup_level + 1){
									?>
										<input type="hidden" name="has_sub" value="1">
					                <?
					                }else{
									?>
					                	<input type="hidden" name="has_sub" value="0">
					                <?
									}

									if ($sub_pic == 1){
									?>
										<div class="form-group">
	                                        <label class="control-label">图片</label>
	                                        <div class="input-icon">
	                                        	<label class="checkbox-inline" style="padding-left: 0;">
													<input type="file" name="pic" size="40">
												</label>
					                            <?
					                            if (!empty($pic)){
					                            ?>
					                            <label class="checkbox-inline">
					                            	<input type="checkbox" name="del_pic" value="1"> 删除现有图片
					                            </label>
					                            <?
					                            }
					                            ?>
					                        </div>
					                    </div>
					                <?
									}
									if ($sub_content == 1){
									?>
										<div class="form-group">
	                                        <label class="control-label">分类描述</label>
	                                        <div class=""><textarea name="content"><?php echo $content; ?></textarea></div>
	                                    </div>
					                <?
									}
		                            ?>
		                            <div class="form-group">
                                        <label class="control-label">记录状态</label>
                                        <div class="checkbox-list">
                                        	<label class="checkbox-inline">
				                            <input type="radio" class="checkbox" name="info_state" value="content"<? if ($info_state == "content") echo " checked"?>>内容模式
				                            </label>
											<label class="checkbox-inline">
				                            <input type="radio" class="checkbox" name="info_state" value="list"<? if ($info_state == "list") echo " checked"?>>新闻列表
				                            </label>
				                            <label class="checkbox-inline">
                                        	<input type="radio" class="checkbox" name="info_state" value="pic"<? if ($info_state == "pic") echo " checked"?>>图片展示
											</label>
				                            <label class="checkbox-inline">
											<input type="radio" class="checkbox" name="info_state" value="pictxt"<? if ($info_state == "pictxt") echo " checked"?>>图文列表
											</label>
				                            <!-- <?
				                            if ($max_level > 2)
											{
											?>
											<label class="checkbox-inline">
				                            	<input type="radio" class="checkbox" name="info_state" value="custom"<? if ($info_state == "custom") echo " checked"?>>自定义
				                            	</label>
				                            <?
				                            }
											?> -->
										</div>
                                    </div>
									<div class="form-group">
	                                    <label class="control-label">注意事项</label>
	                                    <div class="input-icon">
											<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="注意事项"></i>
											<input type="text" value="修改记录状态的值后，需要重新编辑此分类的下级分类。" size="50" maxlength="50" class="form-control" readonly />
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
<script language="javascript">InfoStateChange();</script>
<?
	require_once("foot.php");
?>
</body>
</html>