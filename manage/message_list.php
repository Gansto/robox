<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, MESSAGE_ADVANCEDID) == false)
{
	info("没有权限！");
}

$id		= trim($_GET["id"]);

$page = (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;

$listUrl = "message_list.php?page=$page";
$viewUrl = "message_view.php?page=$page";

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

if($id !=""){
	$sql = "delete from message where id =$id";
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

//删除
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$id_array = $_POST["ids"];
	if (!is_array($id_array))
	{
		$id_array = array($id_array);
	}

	$db->query("begin");

	$sql = "delete from message where id in (" . implode(",", $id_array) . ")";
	$rst = $db->query($sql);

	if ($rst)
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
		info("删除留言失败！");
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
                <li><i class="fa fa-angle-right"></i>高级管理</li>
                <li><i class="fa fa-angle-right"></i><span class="active">在线留言</span></li>
            </ul>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light  ">
                        <div class="portlet-title">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="caption"><a class="btn btn-shy btn-default tooltips" href="<?=$listUrl?>" data-placement="top" data-original-title="刷新列表"><i class="fa fa-eye"></i></a> <a class="btn btn-shy btn-default tooltips" href="javascript:if (CheckSomeConfirm('确定删除选中的记录吗？')){document.listForm.action.value='delete';document.listForm.submit();}" data-placement="top" data-original-title="批量删除"><i class="fa fa-remove"></i></a></div>
                                </div>
                                <div class="col-md-6 col-sm-6"> </div>
                            </div>
                        </div>
                        <div class="portlet-body table-responsive">
                            <form name="listForm" method="post">
                                <input type="hidden" name="action" value="" />
                                <table class="listTable table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1">
                                    <thead>
                                    	<tr class="heaer">
                                    		<th width="50" class="text-center table-checker"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkbox" /></th>
                                    		<th class="text-center">序号</th>
                                    		<th class="text-center">姓名</th>
                                    		<th class="text-center">电话</th>
                                    		<th class="text-center hidden-xs">邮箱</th>
                                    		<th class="text-center hidden-xs">留言内容</th>
                                    		<th class="text-center hidden-xs">留言时间</th>
                                    		<th class="text-center hidden-xs">是否查看</th>
											<th class="text-center hidden-xs">操作</th>
                                    	</tr>
                                    </thead>
                                    <tbody>
                                    	<?
										//设置每页数
					                    $page_size = DEFAULT_PAGE_SIZE;
					    				//总记录数
					                    $sql = "select count(*) as cnt from message";
					                    $rst = $db->query($sql);
					                    $row = $db->fetch_array($rst);
					                    $record_count = $row["cnt"];
					                    $page_count = ceil($record_count / $page_size);

					                    $page_str = page($page, $page_count, $pageUrl);
					                    // echo $page_str;

						                $sql = "select id, sortnum, name, phone, email, content, create_time, state from message order by sortnum desc";
						                $sql .= " limit " . ($page - 1) * $page_size . ", " . $page_size;
						                $rst = $db->query($sql);
						                while ($row = $db->fetch_array($rst)){
						                ?>
                                    	<tr class="<?if($i%2==1){echo "odd";}else{echo "even";}?>">
							                <th width="50" class="text-center table-checker"><input class="checkbox"  type="checkbox" name="ids[]" value="<?=$row["id"]?>" /></th>
							                <th class="text-center"><?=$row["sortnum"]?></th>
					                        <th class="text-center"><a href="<?=$viewUrl?>&id=<?=$row["id"]?>"><?=$row["name"]?></a></th>
					                        <th class="text-center"><?=$row["phone"]?></th>
					                        <th class="text-center hidden-xs"><?=$row["email"]?></th>
					                        <th class="text-center hidden-xs"><?=$row["content"]?></th>
					                        <th class="text-center hidden-xs"><?=formatDate("Y-m-d", $row["create_time"])?></th>
											<th class="text-center hidden-xs">
											<?
											switch ($row["state"]){
												case 0	: echo "<font color='#0066FF'>未查看</font>"; break;
												case 1  : echo "<font color='#ff0000'>已查看</font>"; break;
												// case 2  : echo "<font color='#f60'>已回复</font>"; break;
												default	: echo "<font color='#0066FF'>未查看</font>"; break;
											}?>
											</th>
											<th class="text-center"><a class="label label-sm label-primary tooltips" href="<?=$viewUrl?>&id=<?=$row['id']?>" data-placement="top" data-original-title="编辑信息">编辑</a><a class="label label-sm label-danger tooltips" href="<?=$listUrl?>&id=<?=$row['id']?>" data-placement="top" data-original-title="删除信息"  onClick="return DeleteConfirm();">删</a></th>
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