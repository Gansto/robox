<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, BANNER_CLASS_ADVANCEDID) == false)
{
	info("没有权限！");
}

$id		= trim($_GET["id"]);
$page	= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;

$listUrl	= "banner_class_list.php?page=$page";
$editUrl		= "banner_class_edit.php";

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

//删除
if ($id != "")
{
	//是否有内容
	if ($db->getCount("banner", "class_id='$id'") > 0)
	{
		$db->close();
		info("分类下有信息，请先删除信息！");
	}

	$sql = "delete from banner_class where id='$id'";
	$rst = $db->query($sql);
	if ($rst)
	{
		$db->query("commit");
		$db->close();
		header("Location: $listUrl");
		exit;
	}
	else
	{
		$db->query("rollback");
		$db->close();
		info("删除分类失败！");
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
<script src="js/info.js"></script>
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
				<li><i class="fa fa-angle-right"></i><span class="active">Banner分类管理</span></li>
			</ul>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light">
						<div class="portlet-title">
							<div class="row">
								<div class="col-xs-12 col-sm-12">
									<div class="caption"><a class="btn btn-shy btn-default tooltips" href="<?=$editUrl?>" data-placement="top" data-original-title="添加信息"><i class="fa fa-plus-circle"></i></a> <a class="btn btn-shy btn-default tooltips" href="<?=$listUrl?>" data-placement="top" data-original-title="刷新列表"><i class="fa fa-eye"></i></a></div>
								</div>
								<div class="col-xs-6 col-sm-6">
									<div class="sch-form pull-right" >
                                     </div>
								</div>
							</div>
						</div>
						<div class="portlet-body table-responsive">
							<form id="listForm" method="post">
							<table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1">
								<thead>
									<tr>
 										<th width="100" class="text-center">序号</th>
										<th class="text-center">分类名称</th>
										<th class="text-center hidden-xs">禁止增加</th>
										<th class="text-center hidden-xs">禁止删除</th>
 										<th class="text-center">操作</th>
									</tr>
								</thead>
								<tbody>
									<?
									//设置每页数
									$page_size = DEFAULT_PAGE_SIZE;
									//总记录数
									$sql = "select count(*) as cnt from banner_class";
									$rst = $db->query($sql);
									$row = $db->fetch_array($rst);
									$record_count = $row["cnt"];
									$page_count = ceil($record_count / $page_size);

									$page_str = page($page, $page_count, $pageUrl);
									//echo $page_str;

									$sql = "select id, sortnum, name, add_deny, delete_deny from banner_class order by sortnum asc";
									$sql .= " limit " . ($page - 1) * $page_size . ", " . $page_size;
									$rst = $db->query($sql);
									while ($row = $db->fetch_array($rst)){
									?>
									<tr class="<?if($i%2==1){echo "odd";}else{echo "even";}?>">
										<th class="text-center"><?=$row["sortnum"]?></th>
										<th class="text-center"><a href="<?=$editUrl?>?id=<?=$row["id"]?>"><?=$row["name"]?></a></th>
					                    <th class="text-center hidden-xs">
					                    	<?
					                        switch ($row["add_deny"]){
												case 0:
													echo "否";
													break;
												case 1:
													echo "是";
													break;
											}
											?>
					                    </th>
					                    <th class="text-center hidden-xs">
					                    	<?
					                        switch ($row["delete_deny"]){
												case 0:
													echo "否";
													break;
												case 1:
													echo "是";
													break;
											}
											?>
					                    </th>
										<th class="text-center"><a class="label label-sm label-danger tooltips" href="<?=$listUrl?>&id=<?=$row["id"]?>" data-placement="top" data-original-title="删除" onClick="return confirm('该操作将同时删除此栏目下所有信息，删除后不能恢复。确定删除此栏目[<?=$row["name"]?>]吗？');">删</a></th>
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