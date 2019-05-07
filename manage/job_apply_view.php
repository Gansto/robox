<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, JOB_APPLY_ADVANCEDID) == false)
{
	info("没有权限！");
}


$id		= (int)$_GET["id"];
$page	= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;

$listUrl = "job_apply_list.php?page=$page";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

if ($id != "")
{
	$sql = "select ja.id, ja.name, ja.sortnum, ja.sex, ja.age, ja.major, ja.graduate_time, ja.college, ja.phone, ja.email, ja.resumes, ja.appraise, ja.create_time, ja.state, j.name as job_name from job_apply ja inner join job j on ja.job_id=j.id where ja.id='$id'";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$id				= $row["id"];
		$sortnum		= $row["sortnum"];
		$name			= $row["name"];
		$job_name		= $row["job_name"];
		$sex			= $row["sex"];
		$age			= $row["age"];
		$major			= $row["major"];
		$graduate_time	= $row["graduate_time"];
		$college		= $row["college"];
		$phone			= $row["phone"];
		$email			= $row["email"];
		$resumes		= $row["resumes"];
		$appraise		= $row["appraise"];
		$create_time	= $row["create_time"];
		$state			= $row["state"];

		if ($state == 0)
		{
			$sql = "update job_apply set state=1 where id=$id";
			$db->query($sql);
			$state = 1;
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
<script src="js/jquery.js"></script>
<script src="js/common.js"></script>
<script src="js/edit.js"></script>
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
				<li><i class="fa fa-angle-right"></i><span class="active">应聘人员</span></li>
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
							<form class="form">
								<div class="form-body">
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">应聘职位</label>
												<div class="input-icon"><?=$job_name?></div>
											</div>
											<div class="col-md-6">
												<label class="control-label">姓名</label>
												<div class="input-icon"><?=$name?></div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">性别</label>
												<div class="input-icon"><?=$sex?></div>
											</div>
											<div class="col-md-6">
												<label class="control-label">年龄</label>
												<div class="input-icon"><?=$age?></div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">毕业院校</label>
												<div class="input-icon"><?=$college?></div>
											</div>
											<div class="col-md-6">
												<label class="control-label">所学专业</label>
												<div class="input-icon"><?=$major?></div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">毕业时间</label>
												<div class="input-icon"><?=$graduate_time?></div>
											</div>
											<div class="col-md-6">
												<label class="control-label">电话</label>
												<div class="input-icon"><?=$phone?></div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">邮箱</label>
												<div class="input-icon"><?=$email?></div>
											</div>
											<div class="col-md-6">
												<label class="control-label">个人履历</label>
												<div class="input-icon"><?=$resumes?></div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">自我评价</label>
												<div class="input-icon"><?=$appraise?></div>
											</div>
											<div class="col-md-6">
												<label class="control-label">应聘时间</label>
												<div class="input-icon"><?=$create_time?></div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-actions">
                                	<a href="<?=$listUrl?>" class="btn blue">返回列表</a>
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