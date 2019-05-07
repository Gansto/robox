<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, CONFIG_ADVANCEDID) == false){
	info("没有权限！");
}

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

//提交
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	$name			= htmlspecialchars(trim($_POST["name"]));
	$title			= htmlspecialchars(trim($_POST["title"]));
	$icp			= htmlspecialchars(trim($_POST["icp"]));
	$keyword		= $_POST["keyword"];
	$description	= $_POST["description"];
	$contact		= $_POST["contact"];
	$webcopyright	= $_POST["webcopyright"];
	$copyright		= $_POST["copyright"];
	$hotline		= trim($_POST["hotline"]);
	$phone			= trim($_POST["phone"]);
	$map			= trim($_POST["map"]);

	if (empty($name) || empty($title) || empty($icp))
	{
		$db->close();
		info("填写的参数不完整！");
	}

	$sql = "update config_base set name='$name', title='$title', icp='$icp', keyword='$keyword', description='$description', contact='$contact', copyright='$copyright', webcopyright='$webcopyright', hotline = '$hotline', phone = '$phone', map = '$map' where id=1";
	$rst = $db->query($sql);
	if ($rst)
	{
		//日志记录
		$db->record($_SESSION["ADMIN_ID"],$name,"修改基本设置" );

		info("编辑基本信息成功！");
	}
	else
	{
		info("编辑基本信息失败！");
	}

	$db->close();
}

$sql = "select name, title, icp, keyword, description, contact, copyright, webcopyright, hotline, phone, map from config_base where id=1";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst)){
	$name			= $row["name"];
	$title			= $row["title"];
	$icp			= $row["icp"];
	$keyword		= $row["keyword"];
	$description	= $row["description"];
	$contact		= $row["contact"];
	$copyright		= $row["copyright"];
	$webcopyright	= $row["webcopyright"];
	$hotline		= $row["hotline"];
	$phone			= $row["phone"];
	$map			= $row["map"];
	$db->close();
}
else
{
	$db->close();
	info("还没有记录！");
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
<script src="js/edit.js"></script>
<script charset="utf-8" src="ke/kindeditor-all.js"></script>
<script charset="utf-8" src="ke/lang/zh-CN.js"></script>
<script>
	KindEditor.ready(function(K) {
		var editor = K.create('textarea[name="contact"]', {
			uploadJson : 'ke/php/upload_json.php',
			fileManagerJson : 'ke/php/file_manager_json.php',
			width : '700px',
			height : '100px',
			pasteType : 1,
			allowFileManager : true,
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
		var editor = K.create('textarea[name="copyright"]', {
			uploadJson : 'ke/php/upload_json.php',
			fileManagerJson : 'ke/php/file_manager_json.php',
			width : '700px',
			height : '100px',
			pasteType : 1,
			allowFileManager : true,
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
		var editor = K.create('textarea[name="webcopyright"]', {
			uploadJson : 'ke/php/upload_json.php',
			fileManagerJson : 'ke/php/file_manager_json.php',
			width : '700px',
			height : '100px',
			pasteType : 1,
			allowFileManager : true,
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
<script language="javascript">
	function check(form){
		if (!CheckInputNotEmpty("name", "请输入网站名称。", true)) return false;
		if (!CheckInputNotEmpty("title", "请输入网站标题。", true)) return false;
		if (!CheckInputNotEmpty("icp", "请输入ICP备案号。", true)) return false;
		return true;
	}
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
				<li><i class="fa fa-angle-right"></i><span class="active">网站基本设置</span></li>
			</ul>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light edit-panel">
						<div class="portlet-title">
							<div class="row">
								<div class="col-md-6 col-sm-6">
		                            <div class="caption font-red-sunglo"> <i class="fa fa-edit"></i> <strong class="caption-subject uppercase">基本参数</strong></div>
	                            </div>
	                        </div>
	                    </div>
                        <div class="portlet-body">
							<form class="form" name="editForm" method="post" onSubmit="return check(this);">
								<div class="form-body">
									<div class="form-group">
                                        <label class="control-label">网站名称</label>
                                        <div class="input-icon">
  											<input type="text" class="form-control" name="name" value="<?=$name?>">
										</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">网站标题</label>
                                        <div class="input-icon">
  											<input type="text" class="form-control" name="title" value="<?=$title?>">
										</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">网站关键字</label>
                                        <div class="input-icon">
  											<input type="text" class="form-control" name="keyword" value="<?=$keyword?>">
										</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">网站描述</label>
                                        <div class="input-icon">
  											<input type="text" class="form-control" name="description" value="<?=$description?>">
										</div>
                                    </div>
                                    <div class="form-group">
                                    	<div class="row">
	                                    	<div class="col-md-4">
		                                        <label class="control-label">ICP备案号</label>
		                                        <div class="input-icon">
		  											<input type="text" class="form-control" name="icp" value="<?=$icp?>">
												</div>
											</div>
											<div class="col-md-4">
	                                        <label class="control-label">热线电话</label>
	                                        <div class="input-icon">
	  											<input type="text" class="form-control" name="hotline" value="<?=$hotline?>">
											</div>
											</div>
											<div class="col-md-4">
	                                        <label class="control-label">手机号码</label>
	                                        <div class="input-icon">
	  											<input type="text" class="form-control" name="phone" value="<?=$phone?>">
											</div>
											</div>
										</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">联系信息</label>
                                        <div class=""><textarea name="contact"><?php echo $contact; ?></textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">PC版权信息</label>
                                        <div class=""><textarea name="copyright"><?php echo $copyright; ?></textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">SJ版权信息</label>
                                        <div class=""><textarea name="webcopyright"><?php echo $webcopyright; ?></textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">公司地图</label>
                                        <div class="input-icon">
											<textarea name="map" cols="96" rows="6"><?=$map?></textarea>
										</div>
										<p style="padding-top:15px;"><a href="http://api.map.baidu.com/mapCard/" target="_blank">获取地图代码一</a>&emsp;
										<a href="http://api.map.baidu.com/lbsapi/creatmap/index.html" target="_blank">获取地图代码二</a></p>
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