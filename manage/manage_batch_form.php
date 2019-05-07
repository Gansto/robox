<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, BATCH_UPLOAD_ADVANCEDID) == false)
{
    info("没有权限！");
}


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

$advanced_class = $db->getTableFieldValue('advanced', 'advanced_class', 'where id=\'' . BATCH_UPLOAD_ADVANCEDID . '\' limit 1');
$advancedName = $db->getTableFieldValue('advanced', 'name', 'where id=\'' . BATCH_UPLOAD_ADVANCEDID . '\' limit 1');
$baseName = $db->getTableFieldValue('advanced_class', 'name', 'where id=\'' . $advanced_class . '\' limit 1');
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
<link href="images/upload.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
    function check(form)
    {
        if (form.select_class.value == "")
        {
            alert("请选择分类栏目！");
            return false;
        }
    	if(form.small_img_style[0].checked){
    		if (!/^[1-9][0-9]*$/.exec(form.small_img_width.value))
			{
			    alert("缩略图宽度填写错误。");
			    form.small_img_width.focus();
			    return false;
			}
			if (!/^[1-9][0-9]*$/.exec(form.small_img_height.value))
			{
			    alert("缩略图高度填写错误。");
			    form.small_img_height.focus();
			    return false;
			}
    	}
    	else if(form.small_img_style[1].checked){
    		if (!/^[1-9][0-9]*$/.exec(form.small_img_width.value))
			{
			    alert("缩略图宽度填写错误。");
			    form.small_img_width.focus();
			    return false;
			}
		}
		else if(form.small_img_style[2].checked){
			if (!/^[1-9][0-9]*$/.exec(form.small_img_height.value))
			{
			    alert("缩略图高度填写错误。");
			    form.small_img_height.focus();
			    return false;
			}
		}
        return true;
    }
</script>
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
                <li><i class="fa fa-angle-right"></i><span class="active">图片批量上传</span></li>
            </ul>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light">
						<div class="portlet-body table-responsive">
							<form class="form" action="manage_batch_upload.php" method="get" onSubmit="return check(this);">
								<div class="form-body">
									<div class="form-group clearfix">
										<label class="control-label">请选择栏目</label>
										<select class="form-control" name="select_class">
                                            <option value="">请选择栏目</option>
                                            <?
                                            $sql = "select id, name from info_class where id like '" . CLASS_SPACE . "%' order by sortnum asc";
                                            $rst = $db->query($sql);
                                            while ($row = $db->fetch_array($rst))
                                            {
                                                $data[] = array("id" => $row["id"], "name" => $row["name"]);
                                            }
                                            $data = getNodeData($data, '', CLASS_LENGTH);
                                            echo optionsTree($data, $select_class);
                                            ?>
                                        </select>
			                        </div>
			                        <div class="form-group">
										<label class="control-label">请输入标题（标题不填则使用图片文件名作为标题）</label>
										<div class="input-icon">
											<i class="fa fa-newspaper-o tooltips" data-placement="top" data-original-title="标题"></i>
											<input type="text" class="form-control" name="title" value="<?=$title?>" >
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">上传类别</label>
										<div class="checkbox-list">
											<label class="checkbox-inline"><input type="radio" name="upload_sort" value="0" checked />缩略图</label>
                                        	<label class="checkbox-inline"><input type="radio" name="upload_sort" value="1" />内容图</label>
											<label class="checkbox-inline"><input type="radio" name="upload_sort" value="2" />缩略图与内容图</label>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">缩略图</label>
										<div class="checkbox-list">
                                        	<label class="checkbox-inline"><input type="radio" name="small_img_style" value="0" checked />固定宽和高</label>
                                        	<label class="checkbox-inline"><input type="radio" name="small_img_style" value="1" />固定宽度</label>
                                        	<label class="checkbox-inline"><input type="radio" name="small_img_style" value="2" />固定高度</label>
											<label class="checkbox-inline"><input type="radio" name="small_img_style" value="3" />原始宽高</label>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">缩略图尺寸</label>
										<div class="row">
											<div class="col-md-4">
												<div class="input-icon">宽度
													<label class="checkbox-inline">
														<input type="text" class="form-control" name="small_img_width" value="" placeholder="仅需输入数字">
													</label>
												</div>
											</div>
											<div class="col-md-4">
												<div class="input-icon">高度
													<label class="checkbox-inline">
 														<input type="text" class="form-control"  name="small_img_height" value="" placeholder="仅需输入数字">
 													</label>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">备注信息</label>
										<div class="input-icon">
											<?
                                            if(!extension_loaded('gd')){
                                            ?>
                                            <p style="color:#f00">服务器没有加载GD库，无法对图片进行压缩、裁剪等相关操作。</p>
                                            <?
                                            }
                                            ?>
                                            <p>1、上传类别为缩略图时，只上传缩略图。</p>
                                            <p>2、缩略图压缩处理, 仅限jpg、png、gif。</p>
                                            <p>3、如图片尺寸小于缩略图尺寸，以原图作为缩略图。</p>
                                            <p>4、固定宽高压缩可能使图片变形。</p>
                                            <p>5、固定宽度、高度其中一个，另一个值自动等比例设置，可能与给定的数值不一样。</p>
										</div>
									</div>
								</div>
								<div class="form-actions">
                                    <button type="submit" class="btn blue">下一步</button>
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