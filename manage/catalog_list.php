<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, CATALOG_ADVANCEDID) == false)
{
	info("没有权限！");
}

$id				= trim($_GET["id"]);
$class_id		= trim($_GET["class_id"]);
$page			= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

if (empty($class_id))
{
	$sql = "select id, name, add_deny, delete_deny from catalog_class order by sortnum asc limit 1";
}
else
{
	$sql = "select id, name, add_deny, delete_deny from catalog_class where id=$class_id";
}
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst))
{
	$class_id			= $row["id"];
	$class_name			= $row["name"];
	$class_add_deny		= $row["add_deny"];
	$class_delete_deny	= $row["delete_deny"];
}
else
{
	$db->close();
	info("请增加链接分类！", "main.php");
}

$listUrl	= "catalog_list.php?class_id=$class_id&page=$page";
$editUrl	= "catalog_edit.php?class_id=$class_id";

//删除
if ($id != "")
{
	$sql = "delete from catalog where id=$id";
	$rst = $db->query($sql);
	$db->close();
	if ($rst)
	{
		header("Location: $listUrl");
		exit;
	}
	else
	{
		info("删除栏目失败！");
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
			<!--B 面包屑-->
			<ul class="page-breadcrumb breadcrumb">
				<li><i class="fa fa-home"></i><a href="index.php">首页</a></li>
				<li><i class="fa fa-angle-right"></i>栏目管理</li>
			</ul>
			<!--E 面包屑-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light">
						<div class="portlet-title">
							<div class="row">
								<div class="col-md-6 col-sm-6 col-xs-12 ">
									<div class="caption"><a class="btn btn-shy btn-default tooltips" href="<?=$editUrl?>" data-placement="top" data-original-title="添加信息"><i class="fa fa-plus-circle"></i></a> <a class="btn btn-shy btn-default tooltips" href="<?=$listUrl?>" data-placement="top" data-original-title="刷新列表"><i class="fa fa-eye"></i></a></div>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-12 ">
									<div class="sch-form pull-right"  style="height:40px">
										<select class="form-control" name="select_class" onChange="window.location='?class_id=' + this.options[this.selectedIndex].value;">
											<?
											$sql = "select id, name from catalog_class order by sortnum asc";
											$rst = $db->query($sql);
											while ($row = $db->fetch_array($rst)){
												if ($class_id == $row["id"]){
											?>
												<option value="<?echo $row["id"]?>" selected><?echo $row["name"]?></option>
											<?
												}else{
											?>
												<option value="<?echo $row["id"]?>"><?echo $row["name"]?></option>
											<?
												}
											}
											?>
										</select>
                                    </div>
								</div>
							</div>
						</div>
						<div class="portlet-body table-responsive">
						<form >
							<table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1">
								<thead>
									<tr>
										<th width="100" class="text-center">序号</th>
					                    <th class="text-center">标题</th>
					                    <th class="text-center hidden-xs">状态</th>
										<?
										if($class_delete_deny == 0){
										?>
											<th class="text-center">删除</th>
										<?
										}
										?>
									</tr>
								</thead>
								<tbody>
									<?
									//设置每页数
									$page_size		= DEFAULT_PAGE_SIZE;
									//总记录数
									$sql			= "select count(*) as cnt from catalog a where class_id =$class_id";
									$rst			= $db->query($sql);
									$row			= $db->fetch_array($rst);
									$record_count	= $row["cnt"];
									$page_count		= ceil($record_count / $page_size);
									//分页
									$page_str		= page($page, $page_count);
									//列表
									$sql = "select id, class_id, sortnum, title, state from catalog where class_id=$class_id order by sortnum asc";
					                $sql .= " limit " . ($page - 1) * $page_size . ", " . $page_size;
					                $rst = $db->query($sql);
					                while ($row = $db->fetch_array($rst)){
					                ?>
					                <tr class="<?if($i%2==1){echo "odd";}else{echo "even";}?>" >
					                	<th class="text-center"><?=$row["sortnum"]?></th>
										<th class="text-center "><a href="<?=$editUrl?>&id=<?=$row["id"]?>"><?=$row["title"]?></a></th>
										<th class="text-center hidden-xs">
											<?
											switch ($row["state"]){
												case 0:
													echo "<font color=#FF9900>不显示</font>";
													break;
												case 1:
													echo "显示";
													break;
												default :
													echo "<font color=#FF0000>错误</font>";
													exit;
											}
											?>
										</th>
										<?
										if($class_delete_deny == 0){
										?>
											<th class="text-center"><a class="label label-sm label-danger tooltips" href="<?=$listUrl?>&id=<?=$row["id"]?>" data-placement="top" data-original-title="删除" onClick="return confirm('该操作将同时删除此栏目下所有信息，删除后不能恢复。确定删除此栏目[<?=$row["name"]?>]吗？');">删</a></th>
										<?
										}
										?>
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