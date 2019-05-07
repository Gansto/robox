<?
require_once('include/imgwatermark.php');
function uploadImg($imgfile, $exts, $config_waterpic, $config_waterpos, $config_iswatermark)
{
	if ($imgfile["size"])
	{
		if ($imgfile["size"] > MAX_IMAGE_SIZE)
		{
			info("上传图片太大，超过" . formatSizeStr(MAX_IMAGE_SIZE) . "！");
		}

		if (!is_uploaded_file($imgfile["tmp_name"]))
		{
			info("上传图片错误！");
		}

		$ext = strToLower(getFileExt($imgfile["name"]));

		$extsArray = explode(",", $exts);
		if (!is_array($extsArray))
		{
			$extsArray = array($extsArray);
		}

		$hasExt = false;
		foreach ($extsArray as $extsValue)
		{
			if ($extsValue == $ext)
			{
				$hasExt = true;
				break;
			}
		}

		if ($hasExt == false)
		{
			info("上传文件必须是" . $exts . "格式！");
		}

		$UPLOAD_PATH_FOR_ADMIN = UPLOAD_PATH_FOR_ADMIN;
		if (!is_dir($UPLOAD_PATH_FOR_ADMIN))
		{
			if (!mkdir($UPLOAD_PATH_FOR_ADMIN, 0777))
			{
				info("无法建立保存图片的目录！");
			}
		}

		$ym = date("Y-m");
		$UPLOAD_PATH_FOR_ADMIN .= $ym . "/";
		if (!is_dir($UPLOAD_PATH_FOR_ADMIN))
		{
			if (!mkdir($UPLOAD_PATH_FOR_ADMIN, 0777))
			{
				info("无法建立保存图片的目录！");
			}
		}

		$tmp_name	= getTmpName();
		$image		= $tmp_name . "." . $ext;
		if (!move_uploaded_file($imgfile["tmp_name"], $UPLOAD_PATH_FOR_ADMIN . $image))
		{
			info("保存图片失败！");
		}
		//图片水印
		if ($config_iswatermark==false){
			$config_waterpic = "";
			$config_waterpos = 5;
		}
		if(($ext=="gif"||$ext=="png"||$ext=="jpg") && $config_waterpic!="")
		{
			// $waterImage="images/water.png";//水印图片路径
			$waterImage = '../upload/'. $config_waterpic; //水印图片路径
			imageWaterMark($UPLOAD_PATH_FOR_ADMIN . $image, $config_waterpos, $waterImage);
		}
		$image = $ym . "/" . $image;
	}
	else
	{
		$image = "";
	}

	return $image;
}
?>