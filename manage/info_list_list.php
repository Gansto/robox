<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");

session_start();
$_SESSION['class_id'] = trim($_GET["class_id"]);


$infoid		= trim($_GET["infoid"]);

$page		= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;

//权限检查
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && $session_admin_grade != ADMIN_ADVANCED && hasInclude($session_admin_popedom, substr($class_id, 0, CLASS_LENGTH)) != true && hasInclude($session_admin_popedom, $class_id) != true)
{
	info("没有权限！");
}

$listUrl	= "info_list_list.php?infoid=$infoid&class_id=". $_SESSION['class_id'] ."&page=$page";
$editUrl	= "info_list_edit.php?infoid=$infoid&class_id=". $_SESSION['class_id'] ."&page=$page";
$baseUrl	= "info_list_list.php?infoid=$infoid&class_id=". $_SESSION['class_id'];
$backlistUrl= "info_list.php?class_id=". $_SESSION['class_id'] ."&page=$page";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//批量操作
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$id_array	= $_POST["ids"];
	$action		= trim($_POST["action"]);
 	if (!is_array($id_array))
	{
		$id_array = array($id_array);
	}

	//事务开始
	$db->query("begin");

	//删除记录
	if ($action == "delete")
	{
		//权限检查
		if ($session_admin_grade == ADMIN_COMMON)
		{
			$sql = "select * from info_list where id in (" . implode(",", $id_array) . ")  and admin_id=$session_admin_id";
		}
		else
		{
			$sql = "select * from info_list where id in (" . implode(",", $id_array) . ")";
		}

		$rst = $db->query($sql);
		while ($row = $db->fetch_array($rst))
		{
			$pic	.= $row["pic"] . ",";
		}

		//权限检查
		if ($session_admin_grade == ADMIN_COMMON)
		{
			$sql = "delete from info_list where id in (" . implode(",", $id_array) . ")  and admin_id=$session_admin_id";
		}
		else
		{
			$sql = "delete from info_list where id in (" . implode(",", $id_array) . ")";
		}

		if (!$db->query($sql))
		{
			$db->query("rollback");
			$db->close();
			info("删除信息失败！");
		}
	}
	$db->query("commit");
	$db->close();
	if ($action == "delete")
	{
		//删除图片
		deleteFiles($pic, 1);
	}
	header("Location: $listUrl");
	exit();
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
				<li><i class="fa fa-angle-right"></i>多图列表</li>
				<li><i class="fa fa-angle-right"></i><span class="active">信息列表</span></li>
			</ul>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light">
						<div class="portlet-title">
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<div class="caption"><a class="btn btn-shy btn-default tooltips" href="<?=$editUrl?>" data-placement="top" data-original-title="添加信息"><i class="fa fa-plus-circle"></i></a> <a class="btn btn-shy btn-default tooltips" href="javascript:if (CheckSomeConfirm('确定删除选中的记录吗？')){document.listForm.action.value='delete';document.listForm.submit();}" data-placement="top" data-original-title="批量删除"><i class="fa fa-remove"></i></a> <a class="btn btn-shy btn-default tooltips" href="<?=$listUrl?>" data-placement="top" data-original-title="刷新列表"><i class="fa fa-eye"></i></a> <a class="btn btn-shy btn-default tooltips s-back" href="<?=$backlistUrl?>" data-placement="top" data-original-title="返回列表"></a>
									</div>
								</div>
								<div class="col-md-6 col-sm-6">
									<div class="sch-form pull-right"  style="height:40px">
                                     </div>
								</div>
							</div>
						</div>
						<div class="portlet-body table-responsive">
							<form name="listForm" method="post">
								<input type="hidden" name="action" value="" />
								<table class="listTable table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1">
									<thead>
										<tr class="heaer">
											<th width="50" class="text-center table-checker"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkbox" /></th>
											<th width="50" class="text-center hidden-xs">序号</th>
											<th>标题</th>
											<th class="text-center">缩略图</th>
										</tr>
									</thead>
									<tbody>
										<?
										//设置每页数
										$page_size		= DEFAULT_PAGE_SIZE;
										//总记录数
										$sql			= "select count(*) as cnt from info_list where infoid = '$infoid' ";

										$rst			= $db->query($sql);
										$row			= $db->fetch_array($rst);
										$record_count	= $row["cnt"];
										$page_count		= ceil($record_count / $page_size);
										//分页
										$page_str		= page($page, $page_count);
										//列表
										$sql = "select id,sortnum,title, pic from info_list   where infoid = '$infoid' order by  sortnum desc";

								        $sql .= " limit " . ($page - 1) * $page_size . ", " . $page_size;
								        $rst = $db->query($sql);
								        while ($row = $db->fetch_array($rst)){
								        ?>
								        <tr class="<?if($i%2==1){echo "odd";}else{echo "even";}?>">
								        	<th width="50" class="text-center table-checker"><input class="checkbox ssss" type="checkbox" name="ids[]" value="<?=$row["id"]?>" />
											</th>
											<th width="50" class="text-center hidden-xs"><?=$row["sortnum"]?></th>
											<th><a href="<?=$editUrl?>&id=<?=$row["id"]?>" title="点击进入编辑"><?=$row["title"]?></a></th>
											<th class="text-center hidden-xs">
												<?
							                		if (!empty($row["pic"])){
							                	?>
							                	<a id="pic<?=$row["id"]?>" href="javascript:pic('<?=$row["id"]?>', 'info_list', 'id', 'pic', '', '')" picUrl="<?=UPLOAD_PATH_FOR_ADMIN.$row["pic"]?>"><font color='#FF6600'>有</font></a></th>
							                	<?
							                		}else{
							                	?>
												<a id="pic<?=$row["id"]?>" href="javascript:pic('<?=$row["id"]?>', 'info_list', 'id', 'pic', '', '')" picUrl="">上传</a>
							                	<?
							                		}
							                	?>
											</th>
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