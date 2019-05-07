<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


$page		= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;


$listUrl  = "record_list.php?page=$page&id=$id";
$clearUrl = "record_list.php?id=$id";
 

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

 
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
				<li><i class="fa fa-angle-right"></i>管理员操作日志</li>
			</ul>
			<!--E 面包屑-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light">
						<div class="portlet-title">
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<div class="caption"> <a class="btn btn-shy btn-default tooltips" href="<?=$listUrl?>" data-placement="top" data-original-title="刷新列表"><i class="fa fa-eye"></i></a>  </div>
								</div>
								<div class="col-md-6 col-sm-6">
									 
								</div>
							</div>
						</div>
						<div class="portlet-body table-responsive">
							<table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1">
								<thead>
									<tr>
										<th width="100" class="text-center hidden-xs">序号</th>
										<th class="text-center hidden-xs">登录帐号</th>
										<th class="text-center hidden-xs">真实姓名</th>
										<th class="text-center hidden-xs">登录IP</th>
										<th class="text-center hidden-xs">操作时间</th>
										<th class="text-center hidden-xs">信息名称</th>
										<th class="text-center hidden-xs">说明</th>
									</tr>
								</thead>
								<tbody>
									<?
									//设置每页数
									$page_size   = DEFAULT_PAGE_SIZE;
									//总记录数
									$sql = "select count(*) as cnt from record where adminid>0";
									$rst = $db->query($sql);
									$row = $db->fetch_array($rst);
									$record_count = $row["cnt"];
									$page_count = ceil($record_count / $page_size);
									//分页
									$page_str = page($page, $page_count, $pageUrl);

									$sql = "select * from record  where adminid>0 order by id desc";
									$sql .= " limit " . ($page - 1) * $page_size . ", " . $page_size;
									$rst = $db->query($sql);
									$i = 1;
									while ($row = $db->fetch_array($rst)){
									?>
									<tr class="<?if($i%2==1){echo "odd";}else{echo "even";}?>" >
										<td class="text-center hidden-xs"><?=$i?></td>
										<td class="text-center hidden-xs"><?=$db->getTableFieldValue("admin", "name", "where id='".$row["adminid"]."'")?></td>
										<td class="text-center hidden-xs"><?=$db->getTableFieldValue("admin", "realname", "where id='".$row["adminid"]."'")?></td>
										<td class="text-center hidden-xs"><?=$row["ip"]?></td>
										<td class="text-center hidden-xs"><?=$row["date"]?></td>
										<td class="text-center hidden-xs"><?=$row["title"]?></td>
										<td class="text-center hidden-xs"><?=$row["class"]?></td>
									</tr>
									<?
										$i++;
									}
									?>
								</tbody>
							</table>
						</div>
						<div class="row">
							<div class="col-md-5 col-sm-5 hidden-xs">
								<div class="records">第<?=$page?>页 共<?=$page_count?>页 共<?=$record_count?>条记录</div>
							</div>
							<div class="col-md-7 col-sm-7">
								<?=genPaginationBar($page, $page_count)?>
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