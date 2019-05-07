<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");
require(dirname(__FILE__) . "/uploadImg.php");

$config_iswatermark = false;

$id			= (int)$_GET["id"];
$tableName	= htmlspecialchars(trim($_GET["tableName"]));
$idName		= htmlspecialchars(trim($_GET["idName"]));
$picName	= htmlspecialchars(trim($_GET["picName"]));
$action     = htmlspecialchars(trim($_GET["action"]));

if ($id < 1 || $tableName == "" || $idName == "" || $picName == ""){
	info("参数错误！");
	exit;
}

$baseURL	= "?id=$id&tableName=" . $tableName . "&idName=" . $idName . "&picName=" . $picName . "";

//建立数据库连接对象
$db	= new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

$sql = "select ".$picName." from ".$tableName." where ".$idName." = $id";
$rst = $db->query($sql);
if($row = $db->fetch_array($rst)){
	$fileName = $row["".$picName.""];
}else{
	info("参数错误！");
	exit;
}

//删除
if ($action == "del"){

	// 删除
	if ($fileName != ""){
		$pic = UPLOAD_PATH_FOR_ADMIN . $fileName;
		deleteFile($pic, 1);
	}

	$sql = "update ".$tableName." set ".$picName." ='' where ".$idName."=$id";
	$rst = $db->query($sql);

	$fileName = '';
	header('Location:'.$baseURL);
}

//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{

	$pic_file		= &$_FILES["file"];
	$pic			= uploadImg($pic_file, "gif,jpg,png,swf,fla,wmv",$config_waterpic,$config_waterpos,$config_iswatermark);			//上传图片
	//$create_time	= formatDate("Y-m-d H:i:s", $_POST["create_time"]);
	//$now			= date("Y-m-d H:i:s");

	if (!empty($pic))
	{
		$oldPic		= $db->getTableFieldValue("".$tableName."", "".$picName."", "where ".$idName."=$id");
	}

	$sql = "update ".$tableName." set ".$picName." ='$pic' where ".$idName."=$id";
	$rst = $db->query($sql);
	$db->close();

	if ($rst)
	{
		//修改成功后删除老图片
		deleteFile($oldPic, 1);
		header('Location:'.$baseURL);
		exit;
	}
	else
	{
		//添加或修改失败后 删除上传的图片
		deleteFile($pic, 1);
		info("添加/编辑信息失败！");
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
<link href="images/upload.css" rel="stylesheet" />
<script language="javascript" src="js/jquery.js"></script>
<script language="javascript" src="js/common.js"></script>
<script language="javascript" src="js/edit.js"></script>
<script language="javascript">
	function check()
	{
		var obj			= document.editForm;
		var file_name	= obj.file.value;
		var file_ext	= file_name.substr(file_name.lastIndexOf(".") + 1).toLowerCase();

		if (file_name == "")
		{
			alert("请选择上传文件！");
			return false;
		}
		if (file_ext != 'jpg' && file_ext != 'jpeg' && file_ext != 'gif' && file_ext != 'png')
		{
			alert('上传文件必须是 JPG、JPEG、GIF 或者 PNG 格式！');
			return false;
		}

		obj.action =  "?id=<?=$id?>&tableName=<?=$tableName?>&idName=<?=$idName?>&picName=<?=$picName?>"

		return true;
	}

	function del()
	{
		if (confirm('确定要删除此图片吗?'))
		{
			var obj		= document.editForm;
			window.location = "?id=<?=$id?>&tableName=<?=$tableName?>&idName=<?=$idName?>&picName=<?=$picName?>&action=del";
		}
	}

	function returnPic()
	{
		if (window.opener)
		{
			var picLinkObj = window.opener.document.getElementById("adver<?=$id?>");
			if ('<?=$fileName?>'!='')
			{
				picLinkObj.innerHTML = "<font color='#FF6600'>有</font>";
				//picLinkObj.setAttribute("picUrl2", "<?=UPLOAD_PATH_FOR_ADMIN.$fileName?>");
				//window.opener.BindPic();
			}
			else
			{
				picLinkObj.innerHTML = "上传";
				//picLinkObj.setAttribute("picUrl2", "");
				//window.opener.BindPic();
			}
		}
		return;
	}
</script>
</head>
<body onLoad="returnPic();">
	<div class="uploadWindow">
		<form name="editForm" method="post" enctype="multipart/form-data" onSubmit="return check(this);">
			<table class="editTable">
				<tr class="heaer">
					<td colspan="2">图片上传</td>
				</tr>
				<tr>
					<td class="left" style="width:20%;">选择图片</td>
					<td><input type="file" name="file" size="40"></td>
				</tr>
				<tr>
					<td class="left">图片名称</td>
					<td><?=$fileName?></td>
				</tr>
				<tr>
					<td class="left">说明信息</td>
					<td>
						仅限 Jpg、Jpeg、Gif、Png 格式的图片。
					</td>
				</tr>
				<tr>
					<td class="footer button" colSpan="2">
						<input type="submit" value="上传文件">
						<input type="button" value="关闭窗口" onClick="window.close();">
						<?
						if ($fileName != ""){
						?>
							<input type="button" value="删除文件" onClick="del();">
						<?
						}
						?>
					</td>
				</tr>
			</table>
			<?
				if($fileName !=""){
			?>
			<img src="<?=UPLOAD_PATH_FOR_ADMIN . $fileName?>">
			<?
				}
			?>
		</form>
	</div>
</body>
</html>