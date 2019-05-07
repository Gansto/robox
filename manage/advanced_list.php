<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");

$id = (int)$_GET["id"];


$listUrl = "advanced_list.php";
$editUrl = "advanced_edit.php";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//删除
if ($id > 0)
{
	$sql = "delete from advanced where id=$id";
	$rst = $db->query($sql);
	$db->close();
	if ($rst)
	{
		header("Location: $listUrl");
		exit;
	}
	else
	{
		info("删除记录失败！");
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
                <li><i class="fa fa-angle-right"></i>隐藏管理</li>
                <li><i class="fa fa-angle-right"></i><span class="active">高级功能管理</span></li>
            </ul>
            <!--E 面包屑-->
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light edit-panel">
                        <div class="portlet-title">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="caption"><a class="btn btn-shy btn-default tooltips" href="<?=$editUrl?>" data-placement="top" data-original-title="添加信息"><i class="fa fa-plus-circle"></i></a> <a class="btn btn-shy btn-default tooltips" href="<?=$listUrl?>" data-placement="top" data-original-title="刷新列表"><i class="fa fa-eye"></i></a></div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="sch-form pull-right"  >
                                     </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body table-responsive">
						<form>
							<table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1">
								<thead>
									<tr>
 										<th width="100" class="text-center hidden-xs">ID号</th>
 										<th width="100" class="text-center  ">序号</th>
										<th class="text-center  ">功能名称</th>
										<th class="text-center hidden-xs">标识名称</th>
										<th class="text-center hidden-xs">功能权限</th>
 										<th class="text-center">操作</th>
									</tr>
								</thead>
								<tbody>
									<?
									$sql = "select id, sortnum, name, default_file, state from advanced order by sortnum asc";
									$rst = $db->query($sql);
									while ($row = $db->fetch_array($rst)){
									?>
									<tr class="<?if($i%2==1){echo "odd";}else{echo "even";}?>" >
										<th class="text-center hidden-xs"><?=$row["id"]?></th>
										<th class="text-center  "><?=$row["sortnum"]?></th>
										<th class="text-center  "><a href="<?=$editUrl?>?id=<?=$row["id"]?>"><?=$row["name"]?></a></th>
										<th class="text-center hidden-xs"><?=$row["default_file"]?></th>
										<th class="text-center hidden-xs">
											<?
											if($row["state"] == 0){
											?>
											<span class="label label-sm label-default">隐藏</span>
											<?
											}elseif($row["state"] == 1){
											?>
											<span class="label label-sm label-danger">系统管理员</span>
											<?
											}elseif($row["state"] == 2){
											?>
											<span class="label label-sm label-success">超级管理员</span>
											<?
											}
											?>
										</th>
										<th class="text-center "><a class="label label-sm label-primary tooltips" href="<?=$editUrl?>?id=<?=$row["id"]?>" data-placement="top" data-original-title="编辑信息">编辑</a> <a class="label label-sm label-danger tooltips" href="<?=$listUrl?>?id=<?=$row["id"]?>" data-placement="top" data-original-title="删除信息" onClick="return DeleteConfirm();">删</a></th>
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