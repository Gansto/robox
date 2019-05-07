<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


$id = trim($_GET["id"]);
if (!empty($id) && !checkClassID($id, 1))
{
	info("指定了错误的分类ID号！");
}


$listUrl = "base_class_list.php";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$id2			= htmlspecialchars(trim($_POST["id"]));
	$sortnum		= (int)$_POST["sortnum"];
	$name			= htmlspecialchars(trim($_POST["name"]));
	$state			= (int)$_POST["state"];
	$sub_content	= (int)$_POST["sub_content"];
	$sub_pic		= (int)$_POST["sub_pic"];
	$max_level		= (int)$_POST["max_level"];
	$info_state		= trim($_POST["info_state"]);
	$hasViews		= (int)$_POST["hasViews"];
	$hasState		= (int)$_POST["hasState"];
	$hasPic			= (int)$_POST["hasPic"];
	$hasPic2		= (int)$_POST["hasPic2"];
	$hasPics		= (int)$_POST["hasPics"];
	$hasAnnex		= (int)$_POST["hasAnnex"];
	$hasIntro		= (int)$_POST["hasIntro"];
	$hasContent		= (int)$_POST["hasContent"];
	$hasWebsite		= (int)$_POST["hasWebsite"];
	$hasAuthor		= (int)$_POST["hasAuthor"];
	$hasSource		= (int)$_POST["hasSource"];
	$hasKeyword		= (int)$_POST["hasKeyword"];
	$hasDescription	= (int)$_POST["hasDescription"];
	$hasContent2	= (int)$_POST["hasContent2"];
	$hasContent3	= (int)$_POST["hasContent3"];
	$hasContent4	= (int)$_POST["hasContent4"];
	$hasContent5	= (int)$_POST["hasContent5"];
	$hasContent6	= (int)$_POST["hasContent6"];

	if (empty($id2) || empty($name) || ($info_state != "pic" && $info_state != "list" && $info_state != "pictxt" && $info_state != "content" && $info_state != "custom") || ($max_level < 2 || $max_level > 6))
	{
		$db->close();
		info("填写的参数不完整！");
	}

	if (empty($id))
	{
		//检查填写的分类ID
		if (!checkClassID($id2, 1))
		{
			$db->close();
			info("填写的分类ID号错误！");
		}

		//检查分类ID是否存在
		if ($db->getCount("info_class", "id='$id2'") > 0)
		{
			$id2 = $db->getMax("info_class", "id", "id like '" . CLASS_SPACE . "'");
			$id2 = empty($id2) ? CLASS_DEFAULT : $id2 + 1;
		}

		$sql = "insert into info_class(id, sortnum, name, pic, content, files, info_state, max_level, has_sub, sub_content, sub_pic, hasViews, hasState, hasPic, hasPic2, hasPics, hasAnnex, hasIntro, hasContent, hasContent2, hasContent3, hasContent4, hasContent5, hasContent6, hasWebsite, hasAuthor, hasSource, hasKeyword, hasDescription, state) values('$id2', $sortnum, '$name', '', '', '', '$info_state', $max_level, 1, $sub_content, $sub_pic, $hasViews, $hasState, $hasPic, $hasPic2, $hasPics, $hasAnnex, $hasIntro, $hasContent, $hasContent2, $hasContent3, $hasContent4, $hasContent5, $hasContent6, $hasWebsite, $hasAuthor, $hasSource, $hasKeyword, $hasDescription, $state)";
	}
	else
	{
		$sql = "update info_class set sortnum=$sortnum, name='$name', state=$state, sub_content=$sub_content, sub_pic=$sub_pic, max_level=$max_level, info_state='$info_state', hasViews=$hasViews, hasState=$hasState, hasPic=$hasPic, hasPic2=$hasPic2, hasPics=$hasPics, hasAnnex=$hasAnnex, hasIntro=$hasIntro, hasContent=$hasContent, hasContent2=$hasContent2, hasContent3=$hasContent3, hasContent4=$hasContent4, hasContent5=$hasContent5, hasContent6=$hasContent6, hasWebsite=$hasWebsite, hasAuthor=$hasAuthor, hasSource=$hasSource, hasKeyword=$hasKeyword, hasDescription=$hasDescription where id =$id";
	}

	$rst = $db->query($sql);
	$db->close();

	if ($rst)
	{
		//刷新左边menu.php 显示最新的分类信息
		// echo "<script type='text/javascript'>window.top.frames('menu').window.location='menu.php?menu_id=$id2';window.location='" . $listUrl . "';</script>";
		echo "<script>window.location='" . $listUrl . "';</script>";
		exit;
	}
	else
	{
		info("添加/编辑分类失败！");
	}
}

if (empty($id))
{
	$id			= $db->getMax("info_class", "id", "id like '" . CLASS_SPACE . "'");
	$id			= empty($id) ? CLASS_DEFAULT : $id + 1;
	$sortnum	= $db->getMax("info_class", "sortnum", "id like '" . CLASS_SPACE . "'") + 10;
	$state		= 1;
	$max_level	= 2;
	$info_state	= "list";
	$hasViews	= 1;
	$hasState	= 1;
	$hasPic		= 1;
	$hasPic2	= 0;
	$hasPics	= 0;
	$hasContent	= 1;
	$hasContent2= 0;
	$hasContent3= 0;
	$hasContent4= 0;
	$hasContent5= 0;
	$hasContent6= 0;
}
else
{
	$sql = "select sortnum, name, info_state, max_level, sub_content, sub_pic, hasViews, hasState, hasPic, hasPic2, hasPics, hasAnnex, hasIntro, hasContent, hasContent2, hasContent3, hasContent4, hasContent5, hasContent6, hasWebsite, hasAuthor, hasSource, hasKeyword, hasDescription, state from info_class where id='$id'";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$sortnum		= $row["sortnum"];
		$name			= $row["name"];
		$info_state		= $row["info_state"];
		$max_level		= $row["max_level"];
		$sub_content	= $row["sub_content"];
		$sub_pic		= $row["sub_pic"];
		$hasViews		= $row["hasViews"];
		$hasState		= $row["hasState"];
		$hasPic			= $row["hasPic"];
		$hasPic2		= $row["hasPic2"];
		$hasPics		= $row["hasPics"];
		$hasAnnex		= $row["hasAnnex"];
		$hasIntro		= $row["hasIntro"];
		$hasContent		= $row["hasContent"];
		$hasContent2	= $row["hasContent2"];
		$hasContent3	= $row["hasContent3"];
		$hasContent4	= $row["hasContent4"];
		$hasContent5	= $row["hasContent5"];
		$hasContent6	= $row["hasContent6"];
		$hasWebsite		= $row["hasWebsite"];
		$hasAuthor		= $row["hasAuthor"];
		$hasSource		= $row["hasSource"];
		$hasKeyword		= $row["hasKeyword"];
		$hasDescription	= $row["hasDescription"];
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
<link href="themes/css/shy.css" rel="stylesheet" />
<link href="themes/css/shy-skin.css" rel="stylesheet" />
<script src="js/jquery.js"></script>
<script src="js/common.js"></script>
<script src="js/edit.js"></script>
<script language="javascript">
function check(form)
{
	if (form.id.value == "")
	{
		alert("请输入ID号！");
		form.id.focus();
		return false;
	}

	if (!/^[0-9]*$/.exec(form.sortnum.value))
	{
		alert("分类序号只能使用数字！");
		form.sortnum.focus();
		return false;
	}

	if (form.name.value == "")
	{
		alert("分类名称不能为空！");
		form.name.focus();
		return false;
	}

	return CheckInfoState(form);
}

function CheckInfoState(form)
{
	for (i = 0; i < form.info_state_default.length; i++)
	{
		if (form.info_state_default[i].checked)
		{
			if (!form.info_state[i].checked)
			{
				alert("可选记录状态必须包含默认记录状态！");
				return false;
			}
			else
			{
				return true;
			}
		}
	}

	alert("请选择默认记录状态！");
	return false;
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
				<li><i class="fa fa-angle-right"></i><span class="active">隐藏管理</span></li>
				<li><i class="fa fa-angle-right"></i><span class="active">一级分类</span></li>
				<li><i class="fa fa-angle-right"></i><? echo $name;?></li>
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
												<label class="control-label">分类ID号</label>
												<div class="input-icon">
													<i class="fa fa-bar-chart-o tooltips" data-placement="top" data-original-title="分类ID号"></i>
 													<input type="text" class="form-control" name="id" value="<?=$id?>" size="10" maxlength="3">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">序号</label>
												<div class="input-icon">
													<i class="fa fa-bar-chart-o tooltips" data-placement="top" data-original-title="显示顺序"></i>
 													<input type="text" class="form-control" name="sortnum" value="<?=$sortnum?>" size="10" maxlength="3">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">分类名称</label>
										<div class="input-icon">
											<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="分类名称"></i>
											<input type="text" class="form-control" name="name" value="<?=$name?>" size="10" maxlength="20">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">二级分类</label>
										<div class="checkbox-list">
											<label class="checkbox-inline"><input type="radio" class="checkbox" name="state" value="1" <?if($state == 1){echo "checked";}?> /> 允许</label>
											<label class="checkbox-inline"><input type="radio" class="checkbox" name="state" value="0" <?if($state == 0){echo "checked";}?> /> 拒绝</label>
											<label class="checkbox-inline"><input type="checkbox" class="checkbox" name="sub_content" value="1" <?if($sub_content == 1){echo "checked";}?> />有内容</label>
											<label class="checkbox-inline"><input type="checkbox" class="checkbox" name="sub_pic" value="1" <?if($sub_pic == 1){echo "checked";}?> />有图片</label>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-3">
												<label class="control-label">最大分类层次</label>
												<select name="max_level" class="form-control">
						                        	<option value="2"<? if ($max_level == 2) echo " selected"?>>2</option>
						                            <option value="3"<? if ($max_level == 3) echo " selected"?>>3</option>
						                            <option value="4"<? if ($max_level == 4) echo " selected"?>>4</option>
						                            <option value="5"<? if ($max_level == 5) echo " selected"?>>5</option>
													<option value="6"<? if ($max_level == 6) echo " selected"?>>6</option>
						                        </select>
					                        </div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">记录状态</label>
										<div class="checkbox-list">
					                        <label class="checkbox-inline"><input class="checkbox" type="radio" name="info_state" value="content"<? if ($info_state == "content") echo " checked"?>>内容模式</label>
					                        <label class="checkbox-inline"><input class="checkbox" type="radio" name="info_state" value="list"<? if ($info_state == "list") echo " checked"?>>新闻列表</label>
					                        <label class="checkbox-inline"><input class="checkbox" type="radio" name="info_state" value="pic"<? if ($info_state == "pic") echo " checked"?>>图片展示</label>
					                        <label class="checkbox-inline"><input class="checkbox" type="radio" name="info_state" value="pictxt"<? if ($info_state == "pictxt") echo " checked"?>>图文列表</label>
					                        <label class="checkbox-inline"><input class="checkbox" type="radio" name="info_state" value="custom"<? if ($info_state == "custom") echo " checked"?>>自定义</label>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">记录设置</label>
										<div class="checkbox-list">
											<label class="checkbox-inline"><input class="checkbox" type="checkbox" name="hasViews" value="1" <? if ($hasViews == 1) echo "checked"?>> 点击次数</label>
											<label class="checkbox-inline"><input class="checkbox" type="checkbox" name="hasState" value="1" <? if ($hasState == 1) echo "checked"?>> 是否显示</label>
					                        <label class="checkbox-inline"><input class="checkbox" type="checkbox" name="hasAuthor" value="1" <? if ($hasAuthor == 1) echo "checked"?>> 文章作者</label>
											<label class="checkbox-inline"><input class="checkbox" type="checkbox" name="hasSource" value="1" <? if ($hasSource == 1) echo "checked"?>> 文章来源</label>
					                        <label class="checkbox-inline"><input class="checkbox" type="checkbox" name="hasKeyword" value="1" <? if ($hasKeyword == 1) echo "checked"?>> 关键词</label>
					                        <label class="checkbox-inline"><input class="checkbox" type="checkbox" name="hasWebsite" value="1" <? if ($hasWebsite == 1) echo "checked"?>> 链接地址</label>
											<br>
											<label class="checkbox-inline"><input class="checkbox" type="checkbox" name="hasPic" value="1" <? if ($hasPic == 1) echo "checked"?>> 图片上传</label>
											<label class="checkbox-inline"><input class="checkbox" type="checkbox" name="hasPics" value="1" <? if ($hasPics == 1) echo "checked"?>> 多图图片</label>
											<label class="checkbox-inline"><input class="checkbox" type="checkbox" name="hasAnnex" value="1" <? if ($hasAnnex == 1) echo "checked"?>> 附件上传</label>
											<label class="checkbox-inline"><input class="checkbox" type="checkbox" name="hasIntro" value="1" <? if ($hasIntro == 1) echo "checked"?>> 简要介绍</label>
											<label class="checkbox-inline"><input class="checkbox" type="checkbox" name="hasPic2" value="1" <? if ($hasPic2 == 1) echo "checked"?>> 备用图</label>
											<label class="checkbox-inline"><input class="checkbox" type="checkbox" name="hasContent" value="1" <? if ($hasContent == 1) echo "checked"?>> 详细内容</label>
											<br>
											<label class="checkbox-inline"><input class="checkbox" type="checkbox" name="hasContent2" value="1" <? if ($hasContent2 == 1) echo "checked"?>> 内容二备</label>
											<label class="checkbox-inline"><input class="checkbox" type="checkbox" name="hasContent3" value="1" <? if ($hasContent3 == 1) echo "checked"?>> 内容三备</label>
											<label class="checkbox-inline"><input class="checkbox" type="checkbox" name="hasContent4" value="1" <? if ($hasContent4 == 1) echo "checked"?>> 内容四备</label>
											<label class="checkbox-inline"><input class="checkbox" type="checkbox" name="hasContent5" value="1" <? if ($hasContent5 == 1) echo "checked"?>> 内容五备</label>
											<label class="checkbox-inline"><input class="checkbox" type="checkbox" name="hasContent6" value="1" <? if ($hasContent6 == 1) echo "checked"?>> 内容六</label>
											<label class="checkbox-inline"><input class="checkbox" type="checkbox" name="hasDescription" value="1" <? if ($hasDescription == 1) echo "checked"?>> 描述词</label>
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