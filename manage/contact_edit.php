<?php
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, CONTACT_ADVANCEDID) == false)
{
	info("没有权限！");
}

$id = (int)$_GET['id'] ;

$listUrl = "contact_list.php";
$editUrl = "contact_edit.php?id=" . $id;


//连接数据库
$db = new onlyDB($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);


//如果是添加、编辑栏目（已经提交信息）
if ($_SERVER['REQUEST_METHOD'] == "POST")
{
	//接受表单数据，并对数据进行检验
	$name       = htmlspecialchars(trim($_POST['name']));
	$sortnum    = (int)$_POST['sortnum'];
	$email		= $_POST['email'];
	$showForm	= (int)$_POST['showForm'];
	$state		= $_POST['state'];
	$content	= $_POST['content'];
	$map		= $_POST['map'];


	if ($name == "")
	{
		$db->close();
		info("输入的参数错误！");
	}

	//存入数据库
	if ($id < 1)
	{
		$id	= $db->getMax("contact", "id") + 1;
		$sql = "insert into contact (id, sortnum, name, showForm, state, email, map, content) values ($id, $sortnum, '$name', '$showForm', '$state', '$email', '$map', '$content')";
	}
	else
	{
		$sql = "update contact set sortnum=$sortnum, name='$name', showForm='$showForm', state='$state', email='$email', map='$map', content='$content' where id=$id";
	}

	if(!$db->query($sql))
	{
		$db->close();
		info("新增或编辑部门失败！");
	}
	else
	{
		$db->close();
		header("location: $listUrl");
		exit();
	}
}
else
{
	if ($id > 0)
	{
		$sql = "select * from contact where id=$id";
		$rst = $db->query($sql);
		if ($row = $db->fetch_array($rst))
		{
			$name		= $row['name'];
			$sortnum	= $row['sortnum'];
			$showForm	= $row['showForm'];
			$state		= $row['state'];
			$email		= $row['email'];
			$map		= $row['map'];
			$content	= $row['content'];
		}
		else
		{
			$db->close();
			info("指定的记录不存在！");
		}
	}
	else
	{
		$sql = "select max(sortnum) as max_sortnum from contact";
		$rst = $db->query($sql);
		$row = $db->fetch_array($rst);
		$sortnum = $row['max_sortnum'] + 10;
		$showForm = 0;
		$state = 1;
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
<script language="javascript" type="text/javascript">
function check(form){
	if (!CheckInputNotEmpty("name", "部门名称不能为空。", true)) return false;

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
				<li><i class="fa fa-angle-right"></i>高级管理</li>
				<li><i class="fa fa-angle-right"></i><span class="active">联系部门</span></li>
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
 							<form class="form" name="editForm" method="post" onSubmit="return check(this);">
								<div class="form-body">
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">序号</label>
												<div class="input-icon">
													<i class="fa fa-bar-chart-o tooltips" data-placement="top" data-original-title="显示顺序"></i>
													<input type="text" class="form-control" name="sortnum" value="<?=$sortnum?>">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
                                        <label class="control-label">部门名称</label>
                                        <div class="input-icon">
											<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="部门名称"></i>
											<input type="text" class="form-control" name="name" value="<?=$name?>" >
										</div>
                                    </div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">状态</label>
												<div class="checkbox-list">
													<label class="checkbox-inline">
													<input type="radio" name="state" value="1" <?if($state == 1) echo "checked"; ?> /> 显示
													</label>
													<label class="checkbox-inline">
													<input type="radio" name="state" value="0" <?if($state == 0) echo "checked"; ?> /> 不显示
													</label>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">有无表单</label>
												<div class="checkbox-list">
												<label class="checkbox-inline">
												<input type="radio" name="showForm" value="1" <?if($showForm == 1) echo "checked"; ?> /> 有
												</label>
												<label class="checkbox-inline">
												<input type="radio" name="showForm" value="0" <?if($showForm == 0) echo "checked"; ?> /> 无
												</label>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
		                                        <label class="control-label">部门邮箱</label>
		                                        <div class="input-icon">
													<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="部门名称"></i>
													<input type="text" class="form-control" name="email" value="<?=$email?>"  >
												</div>
											</div>
										</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">联系信息</label>
                                        <textarea name="content"><?php echo $content; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">公司地图</label>
                                        <textarea name="map" cols="97" rows="6"><?=$map?></textarea>
                                        <p style="padding-top:20px;">
                                        	<a href="http://api.map.baidu.com/mapCard/" target="_blank">获取地图代码一</a>&emsp;
											<a href="http://api.map.baidu.com/lbsapi/creatmap/index.html" target="_blank">获取地图代码二</a>
                                        </p>
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