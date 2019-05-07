<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, JOB_ADVANCEDID) == false)
{
	info("没有权限！");
}

$id		= (int)$_GET["id"];
$page	= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;

$listUrl = "job_list.php?page=$page";

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum		= (int)$_POST["sortnum"];
	$state			= (int)$_POST["state"];
	$showForm		= (int)$_POST["showForm"];
	$name			= htmlspecialchars(trim($_POST["name"]));
	$publishdate	= htmlspecialchars(trim($_POST["publishdate"]));
	$content		= $_POST["content"];
	$email			= htmlspecialchars(trim($_POST["email"]));
	$qty			= (int)$_POST["qty"];

	if (empty($id))
	{
		$id = $db->getMax("job", "id") + 1;
		$sql = "insert into job(id, sortnum, state, showForm, name, publishdate, content, email, qty) values($id, $sortnum, $state, $showForm, '$name', '$publishdate', '$content', '$email', $qty)";
	}
	else
	{
		$sql = "update job set sortnum=$sortnum, state=$state, showForm=$showForm, name='$name', publishdate='$publishdate', content='$content',email='$email',qty=$qty where id=$id";
	}
	$rst = $db->query($sql);
	$db->close();
	header("Location: $listUrl");
}
else
{
	if ($id == "")
	{
		$sortnum 	= $db->getMax("job", "sortnum") + 10;
		$state		= 1;
		$showForm	= 1;
	}
	else
	{
		$sql = "select * from job where id='$id'";
		$rst = $db->query($sql);
		if ($row = $db->fetch_array($rst))
		{
			$id				= $row["id"];
			$sortnum		= $row["sortnum"];
			$state			= $row["state"];
			$showForm		= $row["showForm"];
			$name			= $row["name"];
			$publishdate	= $row["publishdate"];
			$content		= $row["content"];
			$email			= $row["email"];
			$qty			= $row["qty"];
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
<script src="js/jquery-1.11.3.min.js"></script>
<script src="js/common.js"></script>
<script src="js/edit.js"></script>
<link href="js/cxcalendar.css" rel="stylesheet" />
<script src="js/cxcalendar.min.js"></script>
<script charset="utf-8" src="ke/kindeditor-all.js"></script>
<script charset="utf-8" src="ke/lang/zh-CN.js"></script>
<script>
	KindEditor.ready(function(K) {
		var editor = K.create('textarea[name="content"]', {
			uploadJson : 'ke/php/upload_json.php',
			fileManagerJson : 'ke/php/file_manager_json.php',
			width : '700px',
			height : '300px',
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
<script language="JavaScript" type="text/javascript">
function check(form){
	if (form.name.value == ""){
		alert("职位名称不能为空。");
		form.name.focus();
		return false;
	}

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
				<li><i class="fa fa-angle-right"></i><span class="active">招聘职位</span></li>
			</ul>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light edit-panel">
						<div class="portlet-title">
							<div class="row">
								<div class="col-md-6 col-sm-6">
		                            <div class="caption font-red-sunglo"> <i class="fa fa-edit"></i> <strong class="caption-subject uppercase">职位编辑</strong></div>
	                            </div>
	                            <div class="col-md-6  col-sm-6 hidden-xs">
	                            	<div class="actions pull-right"> <i class="fa fa-angle-left"></i> <a href="<?=$listUrl?>">返回列表</a></div>
	                            </div>
	                         </div>
                        </div>
                        <div class="portlet-body">
 							<form class="form" name="editForm" method="post" onSubmit="return check(this);">
								<div class="form-body">
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">序号</label>
												<div class="input-icon">
													<i class="fa fa-bar-chart-o tooltips" data-placement="top" data-original-title="显示顺序"></i>
													<input type="text" class="form-control" name="sortnum" value="<?=$sortnum?>">
												</div>
											</div>
 										</div>
									</div>
									<div class="form-group">
                                        <div class="row">
                                        	<div class="col-md-6">
                                        		<label class="control-label">状态</label>
		                                        <div class="checkbox-list">
		                                        	<label class="checkbox-inline">
													<input type="radio" class="checkbox" name="state" value="1"<? if ($state == 1) echo " checked"?>>显示
													</label>
													<label class="checkbox-inline">
						                            <input type="radio" class="checkbox" name="state" value="0"<? if ($state == 0) echo " checked"?>>不显示
						                            </label>
												</div>
                                        	</div>
                                        	<div class="col-md-6">
                                        		<label class="control-label">有无表单</label>
		                                        <div class="checkbox-list">
		                                        	<label class="checkbox-inline">
													<input type="radio" class="checkbox" name="showForm" value="1" <? if ($showForm == 1) echo "checked"?>>有
													</label>
													<label class="checkbox-inline">
						                            <input type="radio" class="checkbox" name="showForm" value="0" <? if ($showForm == 0) echo "checked"?>>无
						                            </label>
												</div>
                                        	</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">职位名称</label>
												<div class="input-icon">
													<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="职位名称"></i>
													<input type="text" class="form-control" name="name" value="<?=$name?>" >
												</div>
											</div>
											<div class="col-md-6">
												<label class="control-label">发布日期</label>
												<div class="input-icon">
													<i class="fa fa-calendar tooltips" data-placement="top" data-original-title="发布日期"></i>
													<input id="cxcalendar" type="text" class="form-control" name="publishdate" value="<?=$publishdate?>" readonly>
												</div>
											</div>
 										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">招聘人数</label>
												<div class="input-icon">
													<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="招聘人数"></i>
													<input type="text" class="form-control" name="qty" value="<?=$qty?>" onkeyup="this.value=this.value.replace(/\D/g,'');"  onafterpaste="this.value=this.value.replace(/\D/g,'');" placeholder="仅需输入数字">
												</div>
											</div>
											<div class="col-md-6">
												<label class="control-label">电子信箱</label>
												<div class="input-icon">
													<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="电子信箱"></i>
													<input type="text" class="form-control" name="email" value="<?=$email?>" >
												</div>
											</div>
 										</div>
									</div>
									<div class="form-group">
										<label class="control-label">岗位要求</label>
										<textarea name="content"><?php echo $content; ?></textarea>
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
<script>$('#cxcalendar').cxCalendar({type: 'date',format: 'YYYY-MM-DD'});</script>
<?
	require_once("foot.php");
?>
</body>
</html>