<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


$id			= (int)$_GET["id"];
$clear_id	= (int)$_GET["clear_id"];


$listUrl = "admin_list.php";
$editUrl = "admin_edit.php";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//删除  admin 不可删除
if ($id > 1)
{
	if ($db->getCount("info", "admin_id=$id") > 0)
	{
		$db->close();
		info("该管理员已发表过信息，不允许删除！");
	}

	//事务开始
	$db->query("begin");

	//删除登陆日志
	$sql = "delete from admin_login where admin_id=$id";
	if (!$db->query($sql))
	{
		$db->query("rollback");
		$db->close();
		info("删除管理员失败！");
	}

	//删除分类栏目权限
	$sql = "delete from admin_popedom where admin_id=$id";
	if (!$db->query($sql))
	{
		$db->query("rollback");
		$db->close();
		info("删除管理员失败！");
	}

	//删除高级管理权限
	$sql = "delete from admin_advanced where admin_id=$id";
	if (!$db->query($sql))
	{
		$db->query("rollback");
		$db->close();
		info("删除管理员失败！");
	}

	//删除管理员
	$sql = "delete from admin where id=$id";
	if ($db->query($sql))
	{
		$db->query("commit");
		$db->close();
		header("Location: $listUrl");
		exit();
	}
	else
	{
		$db->query("rollback");
		$db->close();
		info("删除管理员失败！");
	}
}

//清空日志
if ($clear_id > 0)
{
	$db->query("begin");

	$sql= "delete from admin_login where admin_id=$clear_id";
	$rst = $db->query($sql);
	if ($rst)
	{
		$db->query("commit");
		$db->close();
		info("清空登录日志成功！");
	}
	else
	{
		$db->query("rollback");
		$db->close();
		info("清空登录日志失败！");
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
<script src="js/list.js" id="list" data="false"></script>
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
			<!--B 面包屑-->
			<ul class="page-breadcrumb breadcrumb">
				<li><i class="fa fa-home"></i><a href="index.php">首页</a></li>
				<li><i class="fa fa-angle-right"></i>系统管理</li>
				<li><i class="fa fa-angle-right"></i>系统管理员</li>
			</ul>
			<!--E 面包屑-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light">
						<div class="portlet-title">
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<div class="caption"><a class="btn btn-shy btn-default tooltips" href="<?=$editUrl?>" data-placement="top" data-original-title="添加信息"><i class="fa fa-plus-circle"></i></a> <a class="btn btn-shy btn-default tooltips" href="<?=$listUrl?>" data-placement="top" data-original-title="刷新列表"><i class="fa fa-eye"></i></a></div>
								</div>
								<div class="col-md-6 col-sm-6">
									<div class="sch-form pull-right" >
                                    </div>
								</div>
							</div>
						</div>
						<div class="portlet-body table-responsive">
						<form>
							<table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1">
								<thead>
									<tr>
										<th width="100" class="text-center">序号</th>
										<th class="text-center">登录帐号</th>
										<th class="text-center hidden-xs">真实姓名</th>
										<th class="text-center hidden-xs">管理等级</th>
										<th class="text-center">状态</th>
										<th width="100" class="text-center hidden-xs">创建时间</th>
										<th class="text-center hidden-xs" width="70">登录次数</th>
										<th class="text-center hidden-xs">登陆日志</th>
										<th class="text-center">操作</th>
									</tr>
								</thead>
								<tbody>
									<?
									$sql = "select id, name, realname, grade, login_count, create_time, state from admin order by id asc";
									$rst = $db->query($sql);
									$i = 1;
									while ($row = $db->fetch_array($rst)){
									?>
									<tr class="<?if($i%2==1){echo "odd";}else{echo "even";}?>" >
										<th class="text-center"><? echo $i?></th>
										<th class="text-center"><a href="<?=$editUrl?>?id=<?=$row["id"]?>"><?=$row["name"]?></a></th>
										<th class="text-center hidden-xs"><?=$row["realname"]?></th>
										<th class="text-center hidden-xs">
										<?
										switch ($row["grade"]){
											case 8:
												echo "<font color='#FF6600'>系统管理员</font>";
												break;
											case 7:
												echo "<font color='#0066FF'>高级管理员</font>";
												break;
											case 6:
												echo "审核管理员";
												break;
											case 5:
												echo "普通管理员";
												break;
											default:
												echo "<font color='#FF0000'>错误</font>";
												break;
										}
										?>
										</th>
										<th class="text-center"><?=($row["state"] == 1) ? "正常" : "<font color='#FF6600'>锁定</font>"?></th>
										<th class="text-center hidden-xs"><?=$row["create_time"]?></th>
										<th class="text-center hidden-xs"><?=$row["login_count"]?></th>
										<th class="text-center hidden-xs"><a class="label label-sm label-success tooltips" href="admin_login_list.php?id=<?=$row["id"]?>" data-placement="top" data-original-title="查看登录日志">日志</a>&nbsp;&nbsp;<a class="label label-sm label-danger tooltips" href="<?=$listUrl?>?clear_id=<?=$row["id"]?>" onClick="return clearLogin()" data-placement="top" data-original-title="清空登录日志">清空</a></th>
										<th class="text-center">
											<a class="label label-sm label-primary tooltips" href="<?=$editUrl?>?id=<?=$row["id"]?>" data-placement="top" data-original-title="编辑信息">编辑</a>
											<?
												if ($row["id"]!=1){
											?>
											<a class="label label-sm label-danger tooltips" href="<?=$listUrl?>?id=<?=$row["id"]?>" data-placement="top" data-original-title="删除信息" onClick="return confirm('确定删除管理员[<?=$row["name"]?>]吗？');">删除</a>
											<?
												}
											?>
										</th>
									</tr>
									<?
										$i = $i + 1;
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