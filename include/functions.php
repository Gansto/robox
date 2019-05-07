<?php
/*
 *	常用函数
*/

function msubstr($str, $length, $start=0, $charset="utf-8", $suffix=false) {
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
        if(false === $slice) {
            $slice = '';
        }
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice.'...' : $slice;
}


/**管理员验证
*author:derek
*publishdate:2013-4-23
*@param:$username用户名
*@param:$password密码
*/
function CheckAdmin($username, $password) {
	$objHttpRequest=new COM("MSXML2.ServerXMLHTTP");
	if (is_null($objHttpRequest))
	{
		echo "create Msxl2.ServerXMLHTTP error";
		exit();
	} else {
		$objHttpRequest->setTimeouts(10000, 10000, 10000, 10000);
		$url = "http://service.ibw.cn/Asp/CheckAdmin.asp?username=" . $username . "&password=" . $password;
		$objHttpRequest->open("GET",$url,False);
		$objHttpRequest->send();
		return $objHttpRequest->responseText;
	}
}

/*
 *	得到当前的时间，精确到百万分之一秒
*/
function getMicroTime()
{
	list($a, $b) = explode(" ", microtime());

	return (double)$b + (double)$a;
}


/*
 *	得到指定文件的扩展名
*/
function getFileExt($filename = "")
{
	$dot = strrpos($filename, ".");
	return substr($filename, $dot + 1);
}


/*
 *	利用UNIX时间戳返回一个唯一的文件名，不含后缀
*/
function getTmpName()
{
	list($a, $b) = explode(" ", microtime());
	return (string)$b . (string)substr($a, 2);
}

/*
 *	根据大图片，自动生成压缩小图片，信息批量上传用到
*/
function makeSmallImage($image, $small_image, $small_width = 100, $small_height = 100, $small_img_style)
{
	if (!function_exists(imageCreateFromGif))
	{
		copy($image, $small_image);
		return;
	}

	$size   = getImageSize($image);
	$width  = $size[0];
	$height = $size[1];
	$type   = $size[2];

	$width_ratio  = 1;
	$height_ratio = 1;

	if ($width > $small_width)
	{
		$width_ratio = $small_width / $width;
	}

	if ($height > $small_height)
	{
		$height_ratio = $small_height / $height;
	}


	//如果原图片的大小 小于 指定的小图片，直接拷贝并返回
	if ($width_ratio >= 1 && $height_ratio >= 1)
	{
		copy($image, $small_image);
		return;
	}

	if($small_img_style==0){ //固定宽和高
		$new_width  = $width_ratio * $width;
		$new_height = $height_ratio * $height;
	}
	else if($small_img_style==1){ //固定宽度
		$new_width  = $width_ratio * $width;
		$new_height = $width_ratio * $height;
	}
	else if($small_img_style==2){ //固定高度
		$new_width  = $height_ratio * $width;
		$new_height = $height_ratio * $height;
	}
	else if($small_img_style==3){ //原始宽高
		$ratio = ($width_ratio < $height_ratio) ? $width_ratio : $height_ratio;

		$new_width  = $width;
		$new_height = $height;
	}

	switch($type)
	{
		case 1: //gif -> jpg
			$im    = imageCreateFromGif($image);
			$newim = imageCreateTrueColor($new_width, $new_height);
			imageCopyResampled($newim, $im, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			imageJpeg($newim, $small_image);
			imageDestroy($newim);
			imageDestroy($im);
			break;
		case 2: //jpg -> jpg
			$im    = imageCreateFromJpeg($image);
			$newim = imageCreateTrueColor($new_width, $new_height);
			imageCopyResampled($newim, $im, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			imageJpeg($newim, $small_image);
			imageDestroy($newim);
			imageDestroy($im);
			break;
		case 3: //png -> png
			$im    = imageCreateFromPng($image);
			$newim = imageCreateTrueColor($new_width, $new_height);
			imageCopyResampled($newim, $im, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			imagePng($newim, $small_image);
			imageDestroy($newim);
			imageDestroy($im);
			break;
		default:
			copy($image, $small_image);
			break;
	}

	return;
}


/*
 *	根据给定的数值，返回格式化的字符串，专门针对磁盘空间大小。
*/
function formatSizeStr($size, $fix = 2)
{
	if ($size < 1024) return round($size, $fix) . " Byte";
	if ($size < 1024 * 1024) return round($size / 1024, $fix) . " KB";
	if ($size < 1024 * 1024 * 1024) return round($size / 1024 / 1024, $fix) . " MB";

}


/*
 *	删除编辑器里面上传的图片
*/
function GetImgSrc($sHtml)
{

	if(empty($sHtml)){
		return false;

	}else {
		if(!preg_match_all ('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i',$sHtml,$out))return false;
		return $out[2];
	}
}


/*
 *	目录拷贝（包括子目录及其中的所有文件）
 *	$dir_s原目录，$dir_d目标目录
*/
function copyTree($dir_s, $dir_d)
{
	if (!is_dir($dir_s)) return false;

	if (!is_dir($dir_d))
	{
		if (!mkdir($dir_d, 0777))
		{
			return false;
		}
	}


	if ($dir_s[strlen($dir_s) - 1] != DIRECTORY_SEPARATOR) $dir_s .= DIRECTORY_SEPARATOR;
	if ($dir_d[strlen($dir_d) - 1] != DIRECTORY_SEPARATOR) $dir_d .= DIRECTORY_SEPARATOR;

	$handle = opendir($dir_s);

	while (($filename = readdir($handle)) !== false )
	{
		if ($filename != "." && $filename != "..")
		{
			if (is_dir($dir_s . $filename) && !is_link($dir_s . $filename))
			{
				copyTree($dir_s . $filename, $dir_d . $filename);
			}
			else
			{
				copy($dir_s . $filename, $dir_d . $filename);
			}
		}
	}

	closedir($handle);


	return true;
}



/*
 *	统计目录的占用空间，包括下级子目录
 *	$dir目录
*/
function getTreeSize($dir)
{
	if (!is_dir($dir)) return false;

	if ($dir[strlen($dir) - 1] != DIRECTORY_SEPARATOR) $dir .= DIRECTORY_SEPARATOR;

	$size = 0;

	$handle = opendir($dir);

	while (($filename = readdir($handle)) !== false )
	{
		if ($filename != "." && $filename != "..")
		{
			if (is_dir($dir . $filename) && !is_link($dir . $filename))
			{
				$size += getTreeSize($dir . $filename);
			}
			else
			{
				$size += filesize($directory . $file);
			}
		}
	}

	closedir($handle);


	return $size;
}

//大小转换
function reSizeBytes($size)
{
   $count	= 0;
   $format	= array("B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");

   while (($size / 1024) > 1 && $count < 8)
   {
       $size = $size / 1024;
       $count++;
   }

   if ($count < 2)
	   return number_format($size, 0) . " " . $format[$count];
   else
	   return number_format($size, 1) . " " . $format[$count];
}

//根据给定的图片（或Flash）文件名，返回显示代码
//@param: filename, 文件名
//@width, height: 图片或动画文件的宽度、高度。
//@url: 图片文件的链接地址，注意仅对图片文件有效。
function adver($filename, $width, $height, $url)
{
	$ext = getFileExt($filename);
	$str = "";

	if ($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif" || $ext == "bmp")
	{
		$str = "<img src='" . $filename . "' width='" . $width . "' height='" . $height . "' border='0' />";

		if (!empty($url)) $str = "<a href='" . $url . "' target='_blank'>" . $str . "</a>";
	}
	elseif ($ext == "swf")
	{
		$str = "<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0' width='" . $width . "' height='" . $height . "'>";
		$str .= "<param name='movie' value='" . $filename . "'>";
		$str .= "<param name='quality' value='high'>";
		$str .= "<param name='wmode' value='transparent'>" ;
		$str .= "<embed src='" . $filename . "' width='" . $width . "' height='" . $height . "' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash' wmode='transparent'></embed>";
		$str .= "</object>";
	}

	return $str;
}


/*
 *	中文字符串截取函数
*/
function csubstr($str, $len)
{
	$chinese = 0;

	if (strlen($str) < $len) return $str;

	for ($i=0; $i < $len; $i++)
	{
		if (ord($str[$i]) >= 0xA1) $chinese++;
	}

	if ($chinese % 2 == 1) $len--;

	return substr($str, 0, $len) . "..";
}

//截取utf8字符串
function utf8substr($str, $len, $from = 0)
{
	$strlen = strlen($str)/3;
	if($strlen > $len){
		return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s', '$1',$str).'...';
	}else{
	return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s', '$1',$str);
	}
}

/*
 *	替换字符串中的回车换行符号
 *	$str: 需要替换的字符串
*/
function nlToBr($str)
{
	if (!$str) return "";

	if (strstr($str, "<table"))
	{
		return $str;
	}
	else
	{
		return nl2br($str) . "<br>";
	}
}



/*
 *	计算指定时间到目前的差值
 *	$date: 需要计算的时间
*/
function datePass($date)
{
	if (!$date) return 0;

	return floor((time() - mktime(0, 0, 0, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4))) / 3600 / 24);
}


/*
 *	显示错误信息
*/
function info($msg, $url = "javascript:history.back();")
{
	header("location: info.php?msg=" . urlencode($msg) . "&url=" . urlencode($url));
	exit();
}

/*
 *	判断分类ID是否合法
 *	$id为待检查的id
 *	$min_level为最小分类层次,默认为1(为0即可为空)
 *	$max_level为最大分类层次,默认为5
*/
function checkInfoClassId($id, $min_level = 1, $max_level = 5)
{
	return preg_match("/^([1-9]\d{2}){" . $min_level . "," . $max_level . "}$/", $id);
}

/*
 *	前台判断GET id是否合法
 *	$baseID	一级分类ID
 *	$classID	待检查的ID
 *	$level	分类级数
 *	$maxLevel	最大分类级数,这样可以实现在某个范围内
*/
function checkGetClassID($baseID, $classID, $level = 2, $maxLevel = 0)
{
	if ($maxLevel <= $level)
	{
		return preg_match("/^" . $baseID . "([1-9][0-9][0-9]){" . ($level - 1) . "}$/", $classID);
	}
	else
	{
		return preg_match("/^" . $baseID . "([1-9][0-9][0-9]){" . ($level - 1) . "," . ($maxLevel - 1) . "}$/", $classID);
	}
}

/*
 *	判断分类ID是否合法
 *	$id为待检查的id
 *	$level为分类层次
*/
function checkClassID($classID, $level)
{
	return preg_match("/^([1-9][0-9]{" . (CLASS_LENGTH - 1) . "}){" . $level . "}$/", $classID);
}

/*
 *	获取分类组信息
*/
function classGroup($classID)
{
	$num = (strlen($classID) - 3) / 3;

	for ($i = 0; $i < $num; $i++)
	{
		$result[] = substr($classID, 0, 6 + $i * 3);
	}

	return $result;
}

function classGroupArray($classGroup, $array)
{
	foreach ($classGroup as $value)
	{
		foreach ($array as $k => $v)
		{
			if ($value == $v["id"])
			{
				$result[$v["id"]] = $v["name"];
				continue;
			}
		}
	}

	return $result;
}

/*
 *	格式化时间
*/
function formatDate($ymd, $date)
{
	$date = strtotime($date) ? strtotime($date) : $date;
	return date($ymd, $date);
}

/*
 *	数组中是否包含$x
 *	包含，返回 True 不包含，返回False
*/
function hasInclude($array, $x)
{
	if (!is_array($array))
	{
		$array = array($array);
	}

	$has = false;

	foreach ($array as $value)
	{
		if ($x == $value)
		{
			$has = true;
			break;
		}
	}

	return $has;
}

/*
 *	menu 权限 $x
 *	包含，返回 True 不包含，返回False
*/
function hasBegin4Include($array, $x)
{
	if (!is_array($array))
	{
		$array = array($array);
	}

	$has = false;

	foreach ($array as $value)
	{
		if (substr($value, 0, 3) == $x)
		{
			$has = true;
			break;
		}
	}

	return $has;
}

/*
 *	功能：删除单个文件
 *	1 文件是相对路径
 *	2 文件是绝对路径
*/
function deleteFile($file, $x = 1)
{
	if (empty($file))
	{
		return;
	}

	if ($x == 2)
	{
		$file = $_SERVER["DOCUMENT_ROOT"] . $file;
	}
	else
	{
		$file = UPLOAD_PATH_FOR_ADMIN . $file;
	}

	if (file_exists($file))
	{
		@unlink($file);
	}
}

/*
 *	功能：删除多个文件
 *	1 文件是相对路径
 *	2 文件是绝对路径
 *	多个文件间以逗号“,”隔开
*/
function deleteFiles($file, $x = 1)
{
	if (empty($file))
	{
		return;
	}

	if (is_string($file))
	{
		$file = split(",", $file);
	}

	if (is_array($file))
	{
		if ($x == 2)
		{
			$root_path 	= $_SERVER["DOCUMENT_ROOT"];
		}
		else
		{
			$root_path 	= UPLOAD_PATH_FOR_ADMIN;
		}

		foreach($file as $value)
		{
			if ($value != "" && file_exists($root_path . $value))
			{
				@unlink($root_path . $value);
			}
		}
	}
}

/*
 *	多级分类返回下拉选项
 *	$array 分类数组，一定要包含id, name
 *	$currentID 被选中的项ID的值
 *	$func 对name操作的函数
*/
function optionTree($array, $currentID, $func = NULL)
{
	if (!is_array($array)) return NULL;
	$listStr = NULL;

	for ($i = 0, $cnt = count($array); $i < $cnt; $i++)
	{
		if ($i == 0) $fLen = strlen($array[$i]["id"]);

		if ($currentID === $array[$i]["id"])
		{
			$listStr .= "<option value='" . $array[$i]["id"] . "' selected>";
		}
		else
		{
			$listStr .= "<option value='" . $array[$i]["id"] . "'>";
		}

		$listStr .= str_repeat("&nbsp", ((strlen($array[$i]["id"]) - $fLen) / 3) * 2)
		. "|- "
		. (($func && function_exists($func)) ? call_user_func($func, $array[$i]) : $array[$i]["name"])
		. "</option>\n";
	}

	return $listStr;
}

/*
 *	多级分类返回下拉选项
 *	$array 分类数组，一定要包含id, name 切经过getNodeData函数处理过的数组
 *	$currentID 被选中的项ID的值
 *	$func 对name操作的函数
 *	$floor 无需人工指定，程序自动处理
*/
function optionsTree($array, $currentID, $func = NULL, $floor = 0)
{
	if (!is_array($array)) return NULL;
	$listStr = NULL;

	for ($i = 0, $cnt = count($array); $i < $cnt; $i++)
	{
		if ($currentID === $array[$i]["id"])
		{
			$listStr .= "<option value='" . $array[$i]["id"] . "' selected>";
		}
		else
		{
			$listStr .= "<option value='" . $array[$i]["id"] . "'>";
		}

		$listStr .= str_repeat("&nbsp", $floor * 2)
		. "|- "
		. (($func && function_exists($func)) ? call_user_func($func, $array[$i]) : $array[$i]["name"])
		. "</option>\n";

		if ($array[$i]["child"]) $listStr .= optionsTree($array[$i]["child"],  $currentID, $func = NULL, $floor + 1);
	}

	return $listStr;
}

/*
 *	分类从数据库中按 sortnum ASC排序查询后，递归生成兄弟双亲法表示的数组
 *	$parentID 父ID 可为空，为空即为第一级
 *	$len 多少位为一级 比如 3   101102
*/
function getNodeData($array, $parentID, $len)
{
	$arr = array();
	$arrCount = 0;

	for ($i = 0, $cnt = count($array); $i < $cnt; $i++)
	{
		if (substr($array[$i]["id"], 0, strlen($array[$i]["id"]) - $len) === $parentID)
		{
			$arr[$arrCount] = $array[$i];
			$arr[$arrCount++]["child"] = getNodeData($array, $array[$i]["id"], $len);
		}
	}

	return $arr;
}


//获取文件大小
function getFileSize($file)
{
	if (!empty($file))
	{
		$size = filesize($file);

		return reSizeBytes($size);
	}

	return "0 KB";
}

//加载Flash
function loadSWF($id,$src,$width,$height)
{
	 $str= "<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' width='$width' height='$height' id='$id' align='middle'>";
	 $str.=  "<param name='movie' value='$src' />";
	 $str.=  "<param name='quality' value='high' />";
	 $str.=  "<param name='bgcolor' value='#000' />";
	 $str.=  "<param name='play' value='true' />";
	 $str.=  "<param name='loop' value='true' />";
	 $str.=  "<param name='wmode' value='transparent' />";
	 $str.=  "<param name='scale' value='showall' />";
	 $str.=  "<param name='menu' value='true' />";
	 $str.=  "<param name='devicefont' value='true' />";
	 $str.=  "<param name='salign' value='' />";
	 $str.=  "<param name='allowScriptAccess' value='sameDomain' />";
	 $str.=  "<embed src='$src' wmode='transparent' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash' width='$width' height='$height' align='left' play='true' loop='true' scale='noscale' wmode='window' devicefont='false' bgcolor='#00FFFFFF' name='flash' menu='true' allowFullScreen='true' allowScriptAccess='sameDomain' salign='lt' type='application/x-shockwave-flash'></embed>";
	 $str.=  "</object>";
	 echo $str;
}
function loadMagazine($id,$src,$width,$height,$xml)
{
	 $str="<div id='$id'>
		<embed FlashVars=\"xml=".$xml."\" width='$width' height='$height' src='$src' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' align='left' play='true' loop='true' scale='noscale' wmode='window' devicefont='false' bgcolor='#FFFFFF' name='flash' menu='true' allowFullScreen='true' allowScriptAccess='sameDomain' salign='lt' type='application/x-shockwave-flash' />
	</div>";
	echo $str;
}
function loadBanner($id,$src,$width,$height,$xml)
{
	 $str="<div id='$id'>
		<embed FlashVars=\"xml=".$xml."\" width='$width' height='$height' src='$src' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' align='left' play='true' loop='true' scale='noscale' wmode='window' devicefont='false' bgcolor='#FFFFFF' name='flash' menu='true' allowFullScreen='true' allowScriptAccess='sameDomain' salign='lt' type='application/x-shockwave-flash' />
	</div>";
	echo $str;
}

//加载视频
function loadVideoSWF($width,$height,$video_src,$video_img,$auto=0)
{
	$str="<div id='player'>
		<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='$width' height='$height' id='f4Player' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0'>
			<param value='images/player.swf' name='movie' />
			<param value='high' name='quality' />
			<param value='true' name='allowFullScreen' />
			<param value='skin=images/mySkin.swf&thumbnail=$video_img&video=$video_src&autoplay=$auto' name='FlashVars' />
			<embed width='$width' height='$height' type='application/x-shockwave-flash' allowfullscreen='true' flashvars='skin=images/mySkin.swf&thumbnail=$video_img&video=$video_src' src='images/player.swf' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer'></embed>
		</object>
	</div>";
echo $str;
}
function getInfoArray($db,$condition)
{
	$sql = "select * from info where $condition";
	$rst = $db->query($sql);
	$info=array();
	while($row = $db->fetch_array($rst))
	{
		$info[]=$row;
	}
	return $info;
}
function get_class_name($db,$class_id)
{
	$sql = "select name from info_class where id=$class_id";
	$rst = $db->query($sql);
	if($row = $db->fetch_array($rst))
	{
		return $row["name"];
	}
}
function get_class_content($db,$class_id)
{
	$sql = "select content from info_class where id=$class_id";
	$rst = $db->query($sql);
	if($row = $db->fetch_array($rst))
	{
		return $row["content"];
	}
}
function get_class_en_name($db,$class_id)
{
	$sql = "select en_name from info_class where id=$class_id";
	$rst = $db->query($sql);
	if($row = $db->fetch_array($rst))
	{
		return $row["en_name"];
	}
}

function get_class_state($db,$class_id)
{
	$sql = "select info_state from info_class where id=$class_id";
	$rst = $db->query($sql);
	if($row = $db->fetch_array($rst))
	{
		return $row["info_state"];
	}
}
function get_class_pic($db,$class_id)
{
	$sql = "select pic from info_class where id=$class_id";
	$rst = $db->query($sql);
	if($row = $db->fetch_array($rst))
	{
		return $row["pic"];
	}
}
function info_page_str($db,$class_id,$page_size,$page_id)
{
	$record_cnt=$db->getCount("info","class_id=$class_id");
	$base_url="info.php?class_id=".$class_id;
	$page_num=ceil(($record_cnt)/$page_size);
	$prev_id=max(1,$page_id-1);
	$next_id=min($page_num,$page_id+1);
	$page_num=max(1,$page_num);

	$str="<div class='page'><span>";
	$str.="<a href='$base_url&page_id=1'>首页</a>";
	$str.="<a href='$base_url&page_id=$prev_id'>上一页</a>";
	for($i=1;$i<=$page_num;$i++)
	{
		$class=$i==$page_id?"class='current'":"";
		$str.="<a $class href='$base_url&page_id=$i'>".$i."</a>";
	}

	$str.="<a href='$base_url&page_id=$next_id'>下一页</a>";
	$str.="<a href='$base_url&page_id=$page_num'>尾页</a></span>共 <strong>$record_cnt</strong> 条信息</div>";
	return $str;
}


//将传入的参数转为整数，并限制最小值和最大值。
function ToLimitLng($number, $min, $max){
	$ToLimitLng	= (int)$number;

	if($ToLimitLng < $min){
		$ToLimitLng = $min;
	}elseif($ToLimitLng > $max){
		$ToLimitLng = $max;
	}

	return $ToLimitLng;
}

//星期几
switch (date("N",time())){
    case 1:
        $week = "一";
        break;
    case 2:
        $week = "二";
        break;
    case 3:
        $week = "三";
        break;
    case 4:
        $week = "四";
        break;
    case 5:
        $week = "五";
        break;
    case 6:
        $week = "六";
        break;
    case 7:
        $week = "日";
        break;
    default:
        echo "<font color='#FF0000'>错误</font>";
        break;
}

//获取IP地址
function get_real_ip()
{
	$ip=false;
	if(!empty($_SERVER["HTTP_CLIENT_IP"])){
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	}
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
		if($ip){
			array_unshift($ips, $ip); $ip = FALSE;
		}
		for($i = 0; $i < count($ips); $i++){
			if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])){
				$ip = $ips[$i];
				break;
			}
		}
	}
return($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

//$ip = get_real_ip();
?>
