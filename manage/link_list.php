<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, LINK_ADVANCEDID) == false)
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
	$sql = "select id, name, haspic from link_class order by sortnum asc limit 1";
}
else
{
	$sql = "select id, name, haspic from link_class where id=$class_id";
}
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst))
{
	$class_id		= $row["id"];
	$class_name		= $row["name"];
	$class_haspic	= $row["haspic"];
}
else
{
	$db->close();
	info("请增加链接分类！", "main.php");
}

$listUrl	= "link_list.php?class_id=$class_id&page=$page";
$editUrl	= "link_edit.php?class_id=$class_id";

//删除
if ($id != "")
{
	$sql = " select pic from link where id=$id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$pic = $row["pic"];

		//删除图片
		deleteFiles($pic, 1);

		$sql = "delete from link where id='$id'";
		$rst = $db->query($sql);
		$db->close();
		if ($rst)
		{
			header("Location: $listUrl");
			exit;
		}
		else
		{
			info("删除链接失败！");
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
<link href="themes/css/shy.css" rel="stylesheet" />
<link href="themes/css/shy-skin.css" rel="stylesheet" />
<script src="js/jquery.js"></script>
<script src="js/common.js"></script>
<script src="js/list.js" id="list" data="true"></script>
<script src="js/info.js"></script>
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
				<li><i class="fa fa-angle-right"></i><span class="active">链接管理</span></li>
			</ul>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light">
						<div class="portlet-title">
							<div class="row">
								<div class="col-md-6 col-sm-6 col-xs-12 ">
									<div class="caption"><a class="btn btn-shy btn-default tooltips" href="<?=$editUrl?>" data-placement="top" data-original-title="添加信息"><i class="fa fa-plus-circle"></i></a> <a class="btn btn-shy btn-default tooltips" href="<?=$listUrl?>" data-placement="top" data-original-title="刷新列表"><i class="fa fa-eye"></i></a></div>
								</div>
								<div class="col-md-6 col-sm-6  col-xs-12">
									 <div class="sch-form pull-right">
										<select class="form-control" name="select_class" onChange="window.location='?class_id=' + this.options[this.selectedIndex].value;">
											<?
											$sql = "select id, name from link_class order by sortnum asc";
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
							<form id="listForm" method="post">
								<table class="listTable table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1">
									<thead>
										<tr class="heaer">
											<th width="100" class="text-center">序号</th>
											<th class="text-center">链接名称</th>
											<th class="text-center hidden-xs">链接地址</th>
											<th class="text-center">状态</th>
											<?
											if ($class_haspic == 1){
											?>
												<th class="text-center hidden-xs">图片</th>
											<?
											}
											?>
											<th class="text-center">操作</th>
										</tr>
									</thead>
									<tbody>
										<?
										//设置每页数
										$page_size		= DEFAULT_PAGE_SIZE;
										//总记录数
										$sql			= "select count(*) as cnt from link a where class_id =$class_id";
										$rst			= $db->query($sql);
										$row			= $db->fetch_array($rst);
										$record_count	= $row["cnt"];
										$page_count		= ceil($record_count / $page_size);
										//分页
										$page_str		= page($page, $page_count);
										//列表
										$sql = "select id, class_id, sortnum, name, url, pic, state from link where class_id=$class_id order by sortnum desc";
						                $sql .= " limit " . ($page - 1) * $page_size . ", " . $page_size;
						                $rst = $db->query($sql);
						                while ($row = $db->fetch_array($rst))
						                {
						                	$css = ($css == "listTr") ? "listAlternatingTr" : "listTr";
						                ?>
						                <tr class="<?if($i%2==1){echo "odd";}else{echo "even";}?>">
						                	<th class="text-center"><?=$row["sortnum"]?></th>
											<th class="text-center"><a href="<?=$editUrl?>&id=<?=$row["id"]?>"><?=$row["name"]?></a></th>
					                        <th class="text-center hidden-xs"><?=$row["url"]?></th>
											<th class="text-center">
												<?
												switch ($row["state"]){
													case 0:
														echo "<font color='#FF9900'>不显示</font>";
														break;
													case 1:
														echo "显示";
														break;
													default :
														echo "<font color='#FF0000'>错误</font>";
														exit;
												}
												?>
											</th>
											<?
											if ($class_haspic == 1){
											?>
												<th class="text-center hidden-xs">
													<?
								                		if (!empty($row["pic"])){
								                	?>
								                	<a id="pic<?=$row["id"]?>" href="javascript:pic('<?=$row["id"]?>', 'link', 'id', 'pic', '', '')" picUrl="<?=UPLOAD_PATH_FOR_ADMIN.$row["pic"]?>"><font color='#FF6600'>有</font></a></th>
								                	<?
								                		}else{
								                	?>
													<a id="pic<?=$row["id"]?>" href="javascript:pic('<?=$row["id"]?>', 'link', 'id', 'pic', '', '')">上传</a>
								                	<?
								                		}
								                	?>
												</th>
											<?
											}
											?>
											<th class="text-center"><a class="label label-sm label-primary tooltips" href="<?=$editUrl?>&id=<?=$row["id"]?>" data-placement="top" data-original-title="编辑信息">编辑</a> <a class="label label-sm label-danger tooltips" href="<?=$listUrl?>&id=<?=$row["id"]?>" data-placement="top" data-original-title="删除信息" onClick="return DeleteConfirm();">删</a></th>
						                </tr>
						                <?
						                }
						                ?>
									</tbody>
								</table>
							</form>
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