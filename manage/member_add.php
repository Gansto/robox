<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, MEMBER_ADVANCEDID) == false)
{
	info("没有权限！");
}

$page	= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;
$listUrl = "member_list.php?page=$page";

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$name	 	= trim($_POST['name']);
	$pass	 	= md5(trim($_POST['pass']));
	$realname	= trim($_POST['realname']);
	$company	= trim($_POST['company']);
	$phone	 	= trim($_POST['phone']);
	$email	 	= trim($_POST['email']);
	$address	= trim($_POST['address']);

	if($name!="" && $pass!="" && $realname!="" && $company!="" && $phone!="" && $email!="" && $address!=""){

		$sql = "select * from member where name='".$name."'";
		$rst = $db->query($sql);
		if ($row = $db->fetch_array($rst))
		{
			info("该会员账号已存在！");
		}
		else
		{
			$now	 = date('Y-m-d H:i:s',time());
			$id 	 = $db->getMax("member", "id") + 1;
			$sortnum = $db->getMax("member", "sortnum") + 10;

			$sql = "insert into member(id, sortnum, name, pass, realname, company, phone, email, address, state, create_time, modify_time, login_count) values ($id, $sortnum, '$name', '$pass', '$realname', '$company', '$phone', '$email', '$address', 1, '$now', '$now', 0)";
			$rst = $db->query($sql);
			$db->close();
			if($rst){
				header("location: $listUrl");
				exit;
			}else{
				info("添加失败！");
			}
		}
	}else{
		info("请完善会员信息！");
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
				<li><i class="fa fa-angle-right"></i><span class="active">会员管理</span></li>
			</ul>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light edit-panel">
						<div class="portlet-title">
							<div class="row">
								<div class="col-md-6 col-sm-6">
		                            <div class="caption font-red-sunglo"> <i class="fa fa-edit"></i> <strong class="caption-subject uppercase">新增/编辑</strong></div>
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
												<label class="control-label">状态</label>
												<div class="checkbox-list">
													<label class="checkbox-inline"><input type="radio" name="state" value="0"> 禁用</label>
													<label class="checkbox-inline"><input type="radio" name="state" value="1" checked="checked"> 正常</label>
												</div>
											</div>
											<div class="col-md-6">
												<label class="control-label">会员等级</label>
												<div class="checkbox-list">
													<label class="checkbox-inline"><input type="radio" name="memberGrade" value="1" checked="checked"> 普通会员</label>
													<label class="checkbox-inline"><input type="radio" name="memberGrade" value="2"> 高级会员</label>
													<label class="checkbox-inline"><input type="radio" name="memberGrade" value="3"> VIP会员</label>
												</div>
											</div>
										</div>
									</div>
                                    <div class="form-group">
                                        <div class="row">
                                        	<div class="col-md-6">
                                        		<label class="control-label">登陆帐号</label>
		                                        <div class="input-icon">
		                                        	<input type="text" class="form-control" name="name" value="">
		                                        </div>
                                        	</div>
                                        	<div class="col-md-6">
												<label class="control-label">登陆密码</label>
												<div class="input-icon">
 	                                               	<input class="form-control" type="password" name="pass" value="">
 	                                            </div>
											</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">真实姓名</label>
												<div class="checkbox-list">
 	                                               	<input type="text" class="form-control" name="realname" value="">
 	                                            </div>
											</div>
											<div class="col-md-6">
												<label class="control-label">公司名称</label>
												<div class="input-icon">
 													<input type="text" class="form-control" name="company" value="">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">联系电话</label>
												<div class="checkbox-list">
 	                                               	<input type="text" class="form-control"  name="phone" value="">
 	                                            </div>
											</div>
											<div class="col-md-6">
												<label class="control-label">电子邮箱</label>
												<div class="input-icon">
 													<input type="text" class="form-control" name="email" value="">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label class="control-label">地址</label>
												<div class="input-icon">
 													<input type="text" class="form-control" name="address" value="">
												</div>
											</div>
										</div>
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