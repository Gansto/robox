<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


$id = (int)$_GET["id"];


$listUrl = "admin_list.php";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if ($id < 1)
	{
		$name = htmlspecialchars(trim($_POST["name"]));
	}

	$realname	 = htmlspecialchars(trim($_POST["realname"]));
	$pass		 = trim($_POST["pass"]);
	$grade		 = (int)$_POST["grade"];
	$state		 = (int)$_POST["state"];
	$class_id	 = $_POST["class_id"];		//分类栏目权限 array
	$advanced_id = $_POST["advanced_id"];	//高级管理权限 array
	$now		 = date("Y-m-d H:i:s");

	if (($id < 1 && (empty($name) || empty($pass))) || (!empty($pass) && strlen($pass) < 8) || empty($realname) || $grade == 9)
	{
		$db->close();
		info("填写的参数不完整！");
	}

	if (!is_array($class_id))
	{
		$class_id = array($class_id);
	}

	if (!is_array($advanced_id))
	{
		$advanced_id = array($advanced_id);
	}

	//事务开始
	$db->query("begin");

	if ($id < 1)
	{
		//判断用户名是否重复
		if ($db->getCount("admin", "name='$name'") > 0)
		{
			$db->close();
			info("此帐号已经存在，请选择其他的帐号！");
		}

		$cid = $db->getMax("admin", "id", "") + 1;
		$sql = "insert into admin(id, name, pass, realname, grade, state, create_time, modify_time, login_count) values($cid, '$name', '" . md5($pass) . "', '$realname', $grade, $state, '$now', '$now', 0)";
	}
	else
	{
		$cid = $id;

		if (!empty($pass))
		{
			$sql = "update admin set pass='" . md5($pass) . "', realname='$realname', grade=$grade, state=$state, modify_time='$now' where id=$id";
		}
		else
		{
			$sql = "update admin set realname='$realname', grade=$grade, state=$state, modify_time='$now' where id=$id";
		}
	}

	if ($db->query($sql))
	{
		//系统管理员不需要记录分类栏目、高级管理权限
		if ($grade != ADMIN_SYSTEM)
		{
			if ($id > 0)
			{
				//更新前删除分类栏目权限
				$sql = "delete from admin_popedom where admin_id=$id";
				if (!$db->query($sql))
				{
					$db->query("rollback");
					$db->close();
					info("添加/编辑管理员失败！");
				}

				//更新前删除高级管理权限
				$sql = "delete from admin_advanced where admin_id=$id";
				if (!$db->query($sql))
				{
					$db->query("rollback");
					$db->close();
					info("添加/编辑管理员失败！");
				}
			}

			//添加分类栏目权限
			foreach ($class_id as $value)
			{
				if (trim($value) != "")
				{
					$sql = "insert into admin_popedom(admin_id, class_id) values($cid, '$value')";
					if (!$db->query($sql))
					{
						$db->query("rollback");
						$db->close();
						info("添加/编辑管理员失败！");
					}
				}
			}

			//添加高级管理权限
			foreach ($advanced_id as $value)
			{
				if ((int)$value > 0)
				{
					$sql = "insert into admin_advanced(admin_id, advanced_id) values($cid, $value)";
					if (!$db->query($sql))
					{
						$db->query("rollback");
						$db->close();
						info("添加/编辑管理员失败！");
					}
				}
			}
		}

		$db->query("commit");
		$db->close();
		header("Location: $listUrl");
		exit;
	}
	else
	{
		$db->query("rollback");
		$db->close();
		info("添加/编辑管理员失败！");
	}
}

if ($id < 1)
{
	$grade = 5;
	$state = 1;
}
else
{
	$sql = "select name, realname, grade, state from admin where id=$id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$name		 = $row["name"];
		$realname	 = $row["realname"];
		$grade		 = $row["grade"];
		$state		 = $row["state"];
		$class_id	 = array();
		$advanced_id = array();

		$sql = "select class_id from admin_popedom where admin_id=$id";
		$rst = $db->query($sql);
		while ($row = $db->fetch_array($rst))
		{
			$class_id[] = $row["class_id"];
		}

		$sql = "select advanced_id from admin_advanced where admin_id=$id";
		$rst = $db->query($sql);
		while ($row = $db->fetch_array($rst))
		{
			$advanced_id[] = $row["advanced_id"];
		}
	}
	else
	{
		$db->close();
		info("指定的帐号不存在！");
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
<script language="javascript">
function check(form){
	if (form.pass.value != "")
	{
		if (form.pass.value.length < 8)
		{
			alert("密码长度不能少于8位。");
			form.pass.focus();
			return false;
		}

		if (form.pass.value == form.name.value)
		{
			alert("密码不能和帐号相同。");
			form.pass.focus();
			return false;
		}

		if (/^[0-9]*$/.exec(form.pass.value))
		{
			alert("密码不能只使用数字。最好由数字、英文字母和特殊符号组成。");
			form.pass.focus();
			return false;
		}

		if (form.pass2.value != form.pass.value)
		{
			alert("两次输入的密码不一致。");
			form.pass2.focus();
			return false;
		}
	}

	// if (form.realname.value == "")
	// {
	// 	alert("用户真实姓名不能为空。");
	// 	form.realname.focus();
	// 	return false;
	// }

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
				<li><i class="fa fa-angle-right"></i>系统管理</li>
				<li><i class="fa fa-angle-right"></i><span class="active">系统管理员</span></li>
			</ul>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light edit-panel">
						<div class="portlet-title">
							<div class="row">
								<div class="col-md-6 col-sm-6">
		                            <div class="caption font-red-sunglo"> <i class="fa fa-edit"></i> <strong class="caption-subject uppercase">添加/编辑</strong></div>
	                            </div>
	                            <div class="col-md-6  col-sm-6 hidden-xs">
	                            	<div class="actions pull-right"> <i class="fa fa-angle-left"></i> <a href="<?=$listUrl?>">返回列表</a></div>
	                            </div>
	                         </div>
                        </div>
                        <div class="portlet-body">
 							<form class="form" name="editForm" method="post" onSubmit="return check(this);">
 								<div class="form-group">
 									<div class="row">
										<div class="col-md-6">
		                                    <label class="control-label">登陆帐号</label>
		                                    <div class="input-icon">
												<input type="text" class="form-control"  name="name" value="<?=$name?>" >
											</div>
										</div>
										<div class="col-md-6">
		                                    <label class="control-label">真实姓名</label>
		                                    <div class="input-icon">
												<input type="text" class="form-control"  name="realname" value="<?=$realname?>" >
											</div>
										</div>
									</div>
                                </div>
                                <div class="form-group">
 									<div class="row">
										<div class="col-md-6">
		                                    <label class="control-label">密码</label>
		                                    <div class="input-icon">
												<input type="password" class="form-control"  name="pass" value="<?=$pass?>" >
											</div>
										</div>
										<div class="col-md-6">
		                                    <label class="control-label">确认密码</label>
		                                    <div class="input-icon">
												<input type="password" class="form-control"  name="pass2" value="<?=$pass?>" >
											</div>
										</div>
									</div>
                                </div>
                                <div class="form-group">
                                	<label class="control-label">状态</label>
									<div class="checkbox-list">
										<label class="checkbox-inline"><input type="radio" class="checkbox" name="state" value="0" <?if($state == 0) echo "checked"?> /> 锁定</label>
										<label class="checkbox-inline"><input type="radio" class="checkbox" name="state" value="1" <?if($state == 1) echo "checked"?> /> 正常</label>
									</div>
                                </div>
                                <div class="form-group">
                                	<label class="control-label">身份</label>
									<div class="checkbox-list">
										<label class="checkbox-inline"><input type="radio" class="checkbox" name="grade" value="5"<? if ($grade == 5) echo " checked";?> /> 普通管理员</label>
										<label class="checkbox-inline"><input type="radio" class="checkbox" name="grade" value="6"<? if ($grade == 6) echo " checked";?> /> 审核管理员</label>
										<label class="checkbox-inline"><input type="radio" class="checkbox" name="grade" value="7"<? if ($grade == 7) echo " checked";?> /> 高级管理员</label>
										<label class="checkbox-inline"><input type="radio" class="checkbox" name="grade" value="8"<? if ($grade == 8) echo " checked";?> /> 系统管理员</label>
									</div>
                                </div>
                                <div class="form-group">
                                	<label class="control-label">栏目权限</label>
                                	<table cellpadding="5" cellspacing="5" class="editTable">
			                        	<?
			                            $sql = "select id, name from info_class where id like '" . CLASS_SPACE . "' order by sortnum asc";
										$rst = $db->query($sql);
										while ($row = $db->fetch_array($rst)){
										?>
										<tr>
											<td colspan="6"><input type="checkbox" name="class_id[]" value="<?=$row["id"]?>"<? if (hasInclude($class_id, $row["id"]) == true) echo " checked"?>> <font color="#FF6600"><?=$row["name"]?></font></td>
										</tr>
		                                <?
		                                $sql = "select id, name from info_class where id like '" . $row["id"] . CLASS_SPACE . "' order by sortnum asc";
										$rst2 = $db->query($sql);
										$i = 1;
										while ($row2 = $db->fetch_array($rst2)){
											if ($i % 5 == 1) echo "<tr><td width='20'></td>";
										?>
		                                    <td><input type="checkbox" name="class_id[]" value="<?=$row2["id"]?>"<? if (hasInclude($class_id, $row2["id"]) == true) echo " checked"?>><?=$row2["name"]?></td>
										<?
											$i++;
											if ($i % 5 == 1) echo "</tr>";
										}

											while ($i % 5 != 1){
												echo "<td>&nbsp;</td>";
												$i++;
												if ($i % 5 == 1) echo "</tr>";
											}
			                            }
			                            ?>
									</table>
                                </div>
                                <div class="form-group">
                                	<label class="control-label">高级权限</label>
                                	<table class="editTable" >
										<?
			                            $sql = "select id, name from advanced where state=1 order by sortnum asc";
										$rst = $db->query($sql);
										$i = 1;
										while ($row = $db->fetch_array($rst))
										{
											if ($i % 5 == 1) echo "<tr>";
										?>
			                                <td><input type="checkbox" name="advanced_id[]" value="<?=$row["id"]?>"<? if (hasInclude($advanced_id, $row["id"]) == true) echo " checked"?>><?=$row["name"]?></td>
										<?
											$i++;
											if ($i % 5 == 1) echo "</tr>";
			                            }

										while ($i % 5 != 1)
										{
											echo "<td>&nbsp;</td>";
											$i++;
											if ($i % 5 == 1) echo "</tr>";
										}
										?>
									</table>
                                </div>
                                <div class="form-group">
                                	<label class="control-label">说明</label>
                                	<div class="input-icon">
                                		1、普通管理员：在指定栏目内，允许新增，允许编辑、删除自己发布的并且未审核的内容。<br />
				                        2、审核管理员：在指定栏目内，允许新增、审核、编辑、删除栏目内的所有内容。<br />
				                        3、高级管理员：在所有栏目内，具有新增、审核、编辑、删除的权限。<br />
				                        4、以上管理员的高级权限都需要单独分配。<br />
				                        5、系统管理员：具有最高权限。<br />
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