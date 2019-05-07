<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, MESSAGE_ADVANCEDID) == false)
{
	info("没有权限！");
}

$id		= (int)$_GET["id"];
$page	= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;

if ($id < 1)
{
	info("参数有误！");
}

$listUrl = "message_list.php?page=$page";

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum	= (int)$_POST["sortnum"];
	$reply		= trim($_POST["reply"]);
	$state		= (int)$_POST["state"];

	$sql = "update message set sortnum=$sortnum, reply='$reply', reply_time='" . date("Y-m-d H:i:s") . "', state=$state where id=$id";
	$rst = $db->query($sql);
	$db->close();
	if ($rst)
	{
		header("location: $listUrl");
		exit;
	}
	else
	{
		info("回复留言失败！");
	}
}

$sql = "select sortnum, name, phone, email, content, create_time, state, reply from message where id=$id";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst))
{
	$sortnum		= $row["sortnum"];
	$name			= $row["name"];
	$phone			= $row["phone"];
	$email			= $row["email"];
	$content		= $row["content"];
	$create_time	= $row["create_time"];
	$state			= $row["state"];
	$reply			= $row["reply"];

	if ($state == 0)
	{
		$sql = "update message set state=1 where id=$id";
		$db->query($sql);
		$state = 1;
	}
}
else
{
 	$db->close();
 	info("指定的记录不存在！");
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
<script src="js/jquery-1.11.3.min.js"></script>
<script src="js/common.js"></script>
<script src="js/edit.js"></script>
<script charset="utf-8" src="ke/kindeditor-all.js"></script>
<script charset="utf-8" src="ke/lang/zh-CN.js"></script>
<script>
	KindEditor.ready(function(K) {
		var editor = K.create('textarea[name="reply"]', {
			uploadJson : 'ke/php/upload_json.php',
			fileManagerJson : 'ke/php/file_manager_json.php',
			width : '700px',
			height : '100px',
			pasteType : 1,
			items : [
					'source', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
					'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
					'insertunorderedlist', '|', 'link'],
			afterCreate : function() {
				var self = this;
				K.ctrl(document, 13, function() {
					self.sync();
					K('form[name=editForm]')[0].submit();
				});
				K.ctrl(self.edit.doc, 13, function() {
					self.sync();
					K('form[name=editForm]')[0].submit();
				});
			}
		});
	});
</script>
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
				<li><i class="fa fa-angle-right"></i>高级管理</li>
				<li><i class="fa fa-angle-right"></i><span class="active">留言管理</span></li>
			</ul>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light edit-panel">
						<div class="portlet-title">
							<div class="row">
								<div class="col-md-6 col-sm-6">
		                            <div class="caption font-red-sunglo"> <i class="fa fa-edit"></i> <strong class="caption-subject uppercase">留言信息</strong></div>
	                            </div>
	                            <div class="col-md-6  col-sm-6 hidden-xs">
	                            	<div class="actions pull-right"> <i class="fa fa-angle-left"></i> <a href="<?=$listUrl?>">返回列表</a></div>
	                            </div>
	                         </div>
                        </div>
                        <div class="portlet-body">
 							<form class="form" name="editForm" action="<?=$editUrl?>" method="post">
								<div class="form-body">
									<div class="form-group">
                                        <label class="control-label">姓名</label>
                                        <div class="input-icon"><?=$name?></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">电话</label>
                                        <div class="input-icon"><?=$phone?></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">邮箱</label>
                                        <div class="input-icon"><?=$email?></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">留言</label>
                                        <div class="input-icon"><?=nl2br($content)?></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">留言时间</label>
                                        <div class="input-icon"><?=$create_time?></div>
                                    </div>
                                    <div class="portlet-title">
										<div class="row">
											<div class="col-md-6 col-sm-6">
												<div class="caption font-red-sunglo"> <i class="fa fa-edit"></i> <strong class="caption-subject uppercase">回复</strong></div>
											</div>
 										 </div>
									</div>
                                    <div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">序号</label>
												<div class="input-icon">
													<i class="fa fa-bar-chart-o tooltips" data-placement="top" data-original-title="显示顺序"></i>
													<input type="text" class="form-control" name="sortnum" value="<?=$sortnum?>">
												</div>
											</div>
											<div class="col-md-6">
												<label class="control-label">是否显示</label>
												<div class="checkbox-list">
													<label class="checkbox-inline">
	 													<input type="radio" name="state" value="0" <?if($state == 0){echo "checked";}?>> 不显示
													</label>
													<label class="checkbox-inline">
														<input type="radio" name="state" value="1" <?if($state == 1){echo "checked";}?>> 显示
													</label>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
                                        <label class="control-label">回复内容</label>
                                        <textarea name="reply"><?php echo $reply; ?></textarea>
                                    </div>
								</div>
								<div class="form-actions">
                                     <button type="submit" class="btn blue">提交</button>
                                    <button type="button" class="btn default">重置</button>
                                </div>
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