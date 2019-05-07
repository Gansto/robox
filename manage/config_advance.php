<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");
require(dirname(__FILE__) . "/uploadImg.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, CONFIG_ADVANCEDID) == false){
	info("没有权限！");
}

$config_iswatermark = false;

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

//提交
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	$javascriptFoot		= trim($_POST["javascriptFoot"]);
	$javascriptHead		= trim($_POST["javascriptHead"]);
	$webJavascriptFoot	= trim($_POST["webJavascriptFoot"]);
	$webJavascriptHead	= trim($_POST["webJavascriptHead"]);
	$rightButton		= (int)$_POST["rightButton"];
	$mobilejump			= (int)$_POST["mobilejump"];
	$watermark			= (int)$_POST["watermark"];
	$waterpic			= trim($_POST["waterpic"]);
	$waterpos			= (int)$_POST["waterpos"];

	$pic_file	= &$_FILES["waterpic"];
	$waterpic	= uploadImg($pic_file, "png", $config_waterpic, $config_waterpos, $config_iswatermark);	 //上传图片
	$del_waterpic = (int)$_POST["del_waterpic"];

	if ((!empty($waterpic) || $del_waterpic == 1))
	{
		$oldwaterPic = $db->getTableFieldValue("config_base", "waterpic", "where id=1");
		$sql = "update config_base set javascriptFoot='$javascriptFoot', javascriptHead='$javascriptHead', webJavascriptFoot='$webJavascriptFoot', webJavascriptHead='$webJavascriptHead', rightButton = $rightButton, mobilejump = $mobilejump, watermark = $watermark, waterpic='$waterpic', waterpos=$waterpos where id=1";
	}
	else
	{
		$sql = "update config_base set javascriptFoot='$javascriptFoot', javascriptHead='$javascriptHead', webJavascriptFoot='$webJavascriptFoot', webJavascriptHead='$webJavascriptHead', rightButton = $rightButton, mobilejump = $mobilejump, watermark = $watermark, waterpos=$waterpos where id=1";
	}

	$rst = $db->query($sql);
	
	if ($rst)
	{
		//日志记录
		$db->record($_SESSION["ADMIN_ID"]," 高级参数","修改高级设置" );
		
		deleteFile($oldwaterPic, 1);
		info("编辑基本信息成功！");
	}
	else
	{
		deleteFile($waterpic, 1);
		info("编辑基本信息失败！");
	}

	$db->close();
}

$sql = "select javascriptFoot, javascriptHead, webJavascriptFoot, webJavascriptHead, rightButton, mobilejump, watermark, waterpic, waterpos from config_base where id=1";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst)){
	$javascriptFoot		= $row["javascriptFoot"];
	$javascriptHead		= $row["javascriptHead"];
	$webJavascriptFoot	= $row["webJavascriptFoot"];
	$webJavascriptHead	= $row["webJavascriptHead"];
	$rightButton		= $row["rightButton"];
	$mobilejump			= $row["mobilejump"];
	$watermark			= $row["watermark"];
	$waterpic			= $row["waterpic"];
	$waterpos			= $row["waterpos"];
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
				<li><i class="fa fa-angle-right"></i><span class="active">网站高级设置</span></li>
			</ul>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light edit-panel">
						<div class="portlet-title">
							<div class="row">
								<div class="col-md-6 col-sm-6">
		                            <div class="caption font-red-sunglo"> <i class="fa fa-edit"></i> <strong class="caption-subject uppercase">高级参数</strong></div>
	                            </div>
	                        </div>
	                    </div>
                        <div class="portlet-body">
							<form class="form" method="post" enctype="multipart/form-data">
								<div class="form-body">
                                    <div class="form-group">
                                    	<div class="row">
	                                    	<div class="col-md-6">
	                                    		<div class="checkbox-list">
		                                    		<label class="control-label">是否禁用右键</label>
		                                        	<label class="checkbox-inline">
		                                        		<input class="checkbox" type="radio" name="rightButton" value="1" <? if($rightButton==1){echo "checked";}?>>是
		                                        	</label>
		                                        	<label class="checkbox-inline">
														<input class="checkbox" type="radio" name="rightButton" value="0" <? if($rightButton==0){echo "checked";}?>>否
													</label>
	                                    		</div>
	                                    	</div>
	                                    </div>
	                                </div>
	                                <div class="form-group">
                                    	<div class="row">
	                                    	<div class="col-md-6">
	                                    		<div class="checkbox-list">
		                                    		<label class="control-label">是否手机跳转</label>
		                                        	<label class="checkbox-inline">
		                                        		<input class="checkbox" type="radio" name="mobilejump" value="1" <? if($mobilejump==1){echo "checked";}?>>是
		                                        	</label>
		                                        	<label class="checkbox-inline">
														<input class="checkbox" type="radio" name="mobilejump" value="0" <? if($mobilejump==0){echo "checked";}?>>否
													</label>
		                                    	</div>
	                                    	</div>
	                                    </div>
	                                </div>
	                               <!--  <div class="form-group">
                                    	<div class="row">
	                                    	<div class="col-md-6">
	                                    		<div class="checkbox-list">
		                                    		<label class="control-label">是否启用水印</label>
		                                        	<label class="checkbox-inline">
		                                        		<input class="checkbox" type="radio" name="watermark" value="1" <? if($watermark==1){echo "checked";}?>>是
		                                        	</label>
		                                        	<label class="checkbox-inline">
														<input class="checkbox" type="radio" name="watermark" value="0" <? if($watermark==0){echo "checked";}?>>否
													</label>
	                                    		</div>
	                                    	</div>
	                                    </div>
	                                </div>
	                                <div class="watermark" <? if($watermark==0){echo "style='display:none;'";}?>>
	                                	<div class="form-group">
											<div class="row">
												<div class="col-md-6">
													<label class="control-label">上传水印图片</label>
			                                        <div class="checkbox-list">
														<label class="checkbox-inline"><input class="form-control form-file" type="file" name="waterpic" size="40"></label>
														<?
									                    if ($waterpic != ""){
									                    ?>
									                        <label class="checkbox-inline"><input type="checkbox" name="del_waterpic" value="1"> 删除现有图片</label>
									                    <?
									                    }
										                ?>
													</div>
												</div>
											</div>
		                                </div>
		                                <div class="form-group">
		                               		<div class="row">
												<div class="col-md-6">
													<div class="checkbox-list">
														<label class="control-label">水印图片位置</label>
				                                        <label class="checkbox-inline">
			                                        		<input class="checkbox" type="radio" name="waterpos" value="0" <? if($waterpos==0){echo "checked";}?>>随机位置
			                                        	</label>
			                                        	<label class="checkbox-inline">
															<input class="checkbox" type="radio" name="waterpos" value="1" <? if($waterpos==1){echo "checked";}?>>顶端居左
														</label>
														<label class="checkbox-inline">
															<input class="checkbox" type="radio" name="waterpos" value="2" <? if($waterpos==2){echo "checked";}?>>顶端居中
														</label>
														<label class="checkbox-inline">
															<input class="checkbox" type="radio" name="waterpos" value="3" <? if($waterpos==3){echo "checked";}?>>顶端居右
														</label>
														<label class="checkbox-inline">
															<input class="checkbox" type="radio" name="waterpos" value="4" <? if($waterpos==4){echo "checked";}?>>中部居左
														</label>
														<label class="checkbox-inline">
															<input class="checkbox" type="radio" name="waterpos" value="5" <? if($waterpos==5){echo "checked";}?>>中部居中
														</label>
														<label class="checkbox-inline">
															<input class="checkbox" type="radio" name="waterpos" value="6" <? if($waterpos==6){echo "checked";}?>>中部居右
														</label>
														<label class="checkbox-inline">
															<input class="checkbox" type="radio" name="waterpos" value="7" <? if($waterpos==7){echo "checked";}?>>底端居左
														</label>
														<label class="checkbox-inline">
															<input class="checkbox" type="radio" name="waterpos" value="8" <? if($waterpos==8){echo "checked";}?>>底端居中
														</label>
														<label class="checkbox-inline">
															<input class="checkbox" type="radio" name="waterpos" value="9" <? if($waterpos==9){echo "checked";}?>>底端居右
														</label>
													</div>
			                               		</div>
		                                	</div>
		                                </div>
	                                </div>
	                                <script>
	                                	$("input[name=watermark]").change(function(){
											if($("input[name=watermark]:checked").val()==1){
												$(".watermark").slideDown(500);
											}else{
												$(".watermark").slideUp(500);
											}
										})
	                                </script> -->
	                                <div class="form-group">
                                        <label class="control-label">PC第三方底部代码</label>
                                        <div class="input-icon">
  											<textarea name="javascriptFoot" cols="105" rows="5"><?=$javascriptFoot?></textarea>
										</div>
                                    </div>
 									<div class="form-group">
                                        <label class="control-label">PC第三方头部代码</label>
                                        <div class="input-icon">
											<textarea name="javascriptHead" cols="105" rows="5"><?=$javascriptHead?></textarea>
										</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">SJ第三方底部代码</label>
                                        <div class="input-icon">
  											<textarea name="webJavascriptFoot" cols="105" rows="5"><?=$webJavascriptFoot?></textarea>
										</div>
                                    </div>
 									<div class="form-group">
                                        <label class="control-label">SJ第三方头部代码</label>
                                        <div class="input-icon">
											<textarea name="webJavascriptHead" cols="105" rows="5"><?=$webJavascriptHead?></textarea>
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