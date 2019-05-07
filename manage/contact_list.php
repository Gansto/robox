<?php
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, CONTACT_ADVANCEDID) == false)
{
	info("没有权限！");
}

$id		= trim($_GET["id"]);

$listUrl = "contact_list.php";
$editUrl = "contact_edit.php";

//连接数据库
$db = new onlyDB($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

if($id !=""){
	$sql = "delete from contact where id =$id";
	if (!$db->query($sql))
	{
		$db->close();
		info("删除资料失败！");
	}
	else
	{
		$db->close();
		header("location: $listUrl");
		exit();
	}
}

//如果是删除资料
if ($_SERVER['REQUEST_METHOD'] == "POST")
{
	$id_array = $_POST['ids'];
	$action		= trim($_POST["action"]);
	if (!is_array($id_array))
	{
		$id_array = array($id_array);
	}

	if ($action == "delete"){
		$sql = "delete from contact where id in (" . implode(',', $id_array) . ')';
		if (!$db->query($sql))
		{
			$db->close();
			info("删除资料失败！");
		}
		else
		{
			$db->close();
			header("location: $listUrl");
			exit();
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
<script src="js/jquery.js"></script>
<script src="js/common.js"></script>
<script src="js/list.js" id="list" data="true"></script>
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
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="caption"><a class="btn btn-shy btn-default tooltips" href="<?=$editUrl?>" data-placement="top" data-original-title="添加信息"><i class="fa fa-plus-circle"></i></a> <a class="btn btn-shy btn-default tooltips" href="<?=$listUrl?>" data-placement="top" data-original-title="刷新列表"><i class="fa fa-eye"></i></a> <a class="btn btn-shy btn-default tooltips" href="javascript:if (CheckSomeConfirm('确定删除选中的记录吗？')){document.listForm.action.value='delete';document.listForm.submit();}" data-placement="top" data-original-title="批量删除"><i class="fa fa-remove"></i></a></div>
                                </div>
                                <div class="col-md-6 col-sm-6">  </div>
                            </div>
                        </div>
                        <div class="portlet-body table-responsive">
                        	<form name="listForm" method="post">
                        		<input type="hidden" name="action" value="" />
								<table class="listTable table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1">
									<thead>
										<tr class="heaer">
											<th width="50" class="text-center table-checker"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkbox" /></th>
	 										<th width="100" class="text-center hidden-xs">序号</th>
	 										<th width="100" class="text-center">部门名称</th>
											<th class="text-center hidden-xs">部门邮箱</th>
											<th class="text-center hidden-xs">有无表单</th>
											<th class="text-center">状态</th>
	 										<th class="text-center">操作</th>
										</tr>
									</thead>
									<tbody>
										<?
										$sql = "select * from contact order by sortnum asc";
										$rst = $db->query($sql);
										while ($row = $db->fetch_array($rst)){
										?>
										<tr class="<?if($i%2==1){echo "odd";}else{echo "even";}?>" >
											<th width="50" class="text-center table-checker"><input class="checkbox"  type="checkbox" name="ids[]" value="<?=$row["id"]?>" /></th>
											<th class="text-center hidden-xs"><?=$row['sortnum']?></th>
											<th class="text-center"><a href="<?=$editUrl?>?id=<?=$row['id']?>"><?=$row['name']?></a></th>
											<th class="text-center hidden-xs"><?=$row["email"]?></th>
											<th class="text-center hidden-xs"><?= $row["showForm"]==0 ? "<font color=''>无</font>" : "<font color='#FF6600'>有</font>" ?></th>
											<th class="text-center "><a href="javascript:;" class="listArrow"><?= $row["state"]==0 ? "<span class='label label-sm label-default'>隐藏</span>" : "<span class='label label-sm label-danger'>显示</span>" ?></a></th>
											<th class="text-center"> <a class="label label-sm label-primary tooltips" href="<?=$editUrl?>?id=<?=$row['id']?>" data-placement="top" data-original-title="编辑信息">编辑</a> <a class="label label-sm label-danger tooltips" href="<?=$listUrl?>?id=<?=$row['id']?>" data-placement="top" data-original-title="删除信息"  onClick="return DeleteConfirm();">删</a></th>
										</tr>
										<?
										}
										?>
									</tbody>
								</table>
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