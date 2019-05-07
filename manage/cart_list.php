<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, ORDER_ADVANCEDID) == false)
{
	info("没有权限！");
}

$id     = trim($_GET["id"]);

$listUrl = "cart_list.php";
$viewUrl = "cart_view.php";

$page = (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

//删除
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$id_array = $_POST["ids"];
	if (!is_array($id_array))
	{
		$id_array = array($id_array);
	}
		// print_r($id_array);exit;
 	$db->query("begin");
	$sql = "delete from `order` where id in (" . implode(",", $id_array) . ")";
	$rst = $db->query($sql);


	$sql = "delete from order_list where cart_id in (" . implode(",", $id_array) . ")";
	$rst = $db->query($sql);


	if ($rst)
	{
	 	$db->query("commit");
		header("Location: $listUrl");
		exit();
	}
	else
	{
		$db->query("rollback");
		info("删除记录失败！");
	}
}
?>
<?
//设置每页数
$page_size = DEFAULT_PAGE_SIZE;
//总记录数
$sql = "select count(*) as cnt from `order` ";
$rst = $db->query($sql);
$row = $db->fetch_array($rst);
$record_count = $row["cnt"];
$page_count = ceil($record_count / $page_size);
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
                <li><i class="fa fa-angle-right"></i>高级管理</li>
                <li><i class="fa fa-angle-right"></i><span class="active">订单管理</span></li>
            </ul>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="caption"><a class="btn btn-shy btn-default tooltips" href="<?=$listUrl?>" data-placement="top" data-original-title="刷新列表"><i class="fa fa-eye"></i></a> <a class="btn btn-shy btn-default tooltips" href="javascript:if (CheckSomeConfirm('确定删除选中的记录吗？')){document.listForm.action.value='delete';document.listForm.submit();}" data-placement="top" data-original-title="批量删除"><i class="fa fa-remove"></i></a></div>
                                </div>
                                <div class="col-md-6 col-sm-6">  </div>
                            </div>
                        </div>
						 <div class="portlet-body table-responsive"><form id="listForm" name="listForm" method="post">
							<table class="listTable table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1">
								<thead>
								<tr class="heaer">
									<th width="50" class="text-center table-checker"> </th>
									<th width="100" class="text-center hidden-xs">订单号</th>
									<th>客户姓名</th>
									<th class="text-center hidden-xs">订单金额</th>
									<th class="text-center hidden-xs">电话</th>
									<th class="text-center">下单日期</th>
									<th class="text-center">支付状态</th>
								</tr>
								</thead>
								<tbody>
								<?
								$sql = "select * from `order` order by id desc";

								$sql .= " limit " . ($page - 1) * $page_size . ", " . $page_size;
								$rst = $db->query($sql);
								while ($row = $db->fetch_array($rst)) {
									$css = ($css == "listTr") ? "listAlternatingTr" : "listTr"; ?>
									<tr>
										<td class="text-center"><input class="checkboxes" type="checkbox" id="ids[]" name="ids[]" value="<?=$row["id"]?>"></td>
										<td class="text-center hidden-xs"><a href="<?=$viewUrl?>?id=<?=$row["id"]?>" title="查看详细"><?=$row["orderid"]?></a></td>
										<td class="text-center"><a href="<?=$viewUrl?>?id=<?=$row["id"]?>" title="查看详细"><?=$row["name"]?> </a></td>
										<td class="text-center hidden-xs "><?=$row["total"]?>元</td>
										<td class="text-center hidden-xs"><?=$row["phone"]?></td>
										<td class="text-center"><?=formatDate("Y-m-d", $row["create_time"])?></td>
										<td class="text-center"> <? switch ($row["state"]) {
												case 0:
													echo "<font color='#FF6600'>未支付</font>"; break;
												case 1:
													echo "<font color='#0066FF'>已支付</font>"; break;
												default:
													echo "<font color='#FF0000'>错误</font>"; break;
											} ?>
									</tr>
								<? } ?>
								</tbody>
							</table></form>
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