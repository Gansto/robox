<?php
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, CONTACT_MSG_ADVANCEDID) == false)
{
	info("没有权限！");
}

$id = (int)$_GET['id'];
$page = $_GET['page'];

if ($id < 1)
{
	info("没有指定记录！");
}

$listUrl = "contact_msg_list.php?page=" . $page;
$editUrl = "contact_msg_view.php?page=" . $page . "&id=" . $id;


//连接数据库
$db = new onlyDB($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

$sql = "select * from contact_msg where id=$id";
$rst = $db->query($sql);
if (!$row = $db->fetch_array($rst))
{
	$db->close();
	info("指定的记录不存在！");
}
else
{
	$dept_name   = $row['dept_name'];
	$title 		 = $row['title'];
	$name 		 = $row['name'];
	$company 	 = $row['company'];
	$phone 		 = $row['phone'];
	$fax 		 = $row['fax'];
	$email 		 = $row['email'];
	$address 	 = $row['address'];
	$content 	 = $row['content'];
	$create_time = $row['create_time'];
	$state 		 = $row['state'];

	if ($readed == 0)
	{
		$sql = "update contact_msg set state=1 where id=$id";
		$db->query($sql);
	}

	$db->close();
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
				<li><i class="fa fa-angle-right"></i><span class="active">联系留言</span></li>
			</ul>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light edit-panel">
						<div class="portlet-title">
							<div class="row">
								<div class="col-md-6 col-sm-6">
		                            <div class="caption font-red-sunglo"> <i class="fa fa-edit"></i> <strong class="caption-subject uppercase">新增/编辑</strong></div>
	                            </div>
	                            <div class="col-md-6  col-sm-6 hidden-xs">
	                            	<div class="actions pull-right"> <i class="fa fa-angle-left"></i> <a href="<?=$listUrl?>">返回列表</a></div>
	                            </div>
	                         </div>
                        </div>
                        <div class="portlet-body">
                        	<div class="form-body">
                        		<div class="form-group">
                                    <label class="control-label">序号</label>
                                    <div class="input-icon"><?=$id?></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">留言部门</label>
                                    <div class="input-icon"><?=$dept_name?></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">姓名</label>
                                    <div class="input-icon"><?=$name?></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">电话</label>
                                    <div class="input-icon"><?=$phone?></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">地址</label>
                                    <div class="input-icon"><?=$address?></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">留言内容</label>
                                    <div class="input-icon"><?=$content?></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">留言时间</label>
                                    <div class="input-icon"><?=$create_time?></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">状态</label>
                                    <div class="input-icon"><?= $state==1 ? '已阅读' : '<font color=ff6600>未阅读</font>'?></div>
                                </div>
                                <div class="form-actions">
                                	<a href="<?=$listUrl?>" class="btn blue">返回列表</a>
                                </div>
                        	</div>
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