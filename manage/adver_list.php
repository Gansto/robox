<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, ADVER_ADVANCEDID) == false)
{
	info("没有权限！");
}

$id     = trim($_GET["id"]);

$listUrl = "adver_list.php";
$editUrl = "adver_edit.php";

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

//删除
if ($id != ""){
    $sql = "select pic from adver where id =$id";
    $rst = $db->query($sql);
    while ($row = $db->fetch_array($rst))
    {
        $pic .= $row["pic"] . ",";
    }

    $sql = "delete from adver where id=$id";
    $rst = $db->query($sql);
    $db->close();
    if ($rst)
    {
        deleteFiles($pic, 1);
        header("Location: $listUrl");
        exit();
    }
    else
    {
        info("删除记录失败！");
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
 
	$sql = "select pic from adver where id in (" . implode(",", $id_array) . ")";
	$rst = $db->query($sql);
	while ($row = $db->fetch_array($rst))
	{
		$pic .= $row["pic"] . ",";
	}

	$sql = "delete from adver where id in (" . implode(",", $id_array) . ")";
	$rst = $db->query($sql);
	$db->close();
	if ($rst)
	{
		deleteFiles($pic, 1);
		header("Location: $listUrl");
		exit();
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
                <li><i class="fa fa-angle-right"></i><span class="active">广告管理</span></li>
            </ul>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light edit-panel">
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
                                            <th class="text-center">标题名称</th>
                                            <th class="text-center">广告方式</th>
                                            <th class="text-center hidden-xs">宽度</th>
                                            <th class="text-center hidden-xs">高度</th>
                                            <th class="text-center hidden-xs">链接</th>
                                            <th class="text-center hidden-xs">广告文件</th>
                                            <th class="text-center hidden-xs">状态</th>
                                            <th class="text-center">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?
                                        $sql = "select id, title, mode, url, width, height, pic, state from adver order by id asc";
                                        $rst = $db->query($sql);
                                        while ($row = $db->fetch_array($rst)){
                                        ?>
                                        <tr class="<?if($i%2==1){echo "odd";}else{echo "even";}?>" >
                                            <th width="50" class="text-center table-checker"><input class="checkbox"  type="checkbox" name="ids[]" value="<?=$row["id"]?>" /></th>
                                            <th class="text-center"><a href="<?=$editUrl?>?id=<?=$row["id"]?>"><?=$row["title"]?></a></th>
                                            <th class="text-center">
                                            <?
                                            switch($row["mode"]){
                                                case "popup":
                                                    echo "弹出广告";
                                                    break;
                                                case "float":
                                                    echo "漂浮广告";
                                                    break;
                                                case "hangL":
                                                    echo "左侧门帘";
                                                    break;
                                                case "hangR":
                                                    echo "右侧门帘";
                                                    break;
                                                case "hangLR":
                                                    echo "左右门帘";
                                                    break;
                                                case "bigScreen":
                                                    echo "拉屏广告";
                                                    break;
                                                default:
                                                    echo "<font color='#FF0000'>错误</font>";
                                                    break;
                                            }
                                            ?>
                                            </th>
                                            <th class="text-center hidden-xs"><?=$row["width"]?></th>
                                            <th class="text-center hidden-xs"><?=$row["height"]?></th>
                                            <th class="text-center hidden-xs">
                                                <?
                                                if ($row["url"] != ""){
                                                ?>
                                                    <a href="<?=$row["url"]?>" target="_blank">有</a>
                                                <?
                                                }else{
                                                    echo "无";
                                                }
                                                ?>
                                            </th>
                                            <th class="text-center hidden-xs">
                                                <?
                                                    if ($row["pic"] != ""){
                                                ?>
                                                    <a id="adver<?=$row["id"]?>" href="javascript:adver('<?=$row["id"]?>', 'adver', 'id', 'pic', '', '')"><font color='#FF6600'>有</font></a>
                                                <?
                                                    }else{
                                                ?>
                                                    <a id="adver<?=$row["id"]?>" href="javascript:adver('<?=$row["id"]?>', 'adver', 'id', 'pic', '', '')">上传</a>
                                                <?
                                                    }
                                                ?>
                                            </th>
                                            <th class="text-center hidden-xs"><?=($row["state"] == 1) ? "显示" : "<font color='#FF6600'>不显示</font>"?></th>
                                            <th class="text-center"><a class="label label-sm label-danger tooltips" href="<?=$listUrl?>?id=<?=$row["id"]?>" data-placement="top" data-original-title="删除" onClick="return confirm('该操作将同时删除此栏目下所有信息，删除后不能恢复。确定删除此栏目[<?=$row["title"]?>]吗？');">删</a></th>
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