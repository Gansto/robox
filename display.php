<?
require("init.php");

$menu = "display";


$id	= (int)$_GET["id"];
if ($id < 1) {
	$db->close();
	header("location: ./");
	exit;
}

$sql = "select id, class_id, title, pic, annex, source, author, views, keyword, description, content, create_time from info where id=$id and state>0 limit 1";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst)) {
	$title			= $row["title"];
	$source			= $row["source"];
	$class_id		= $row["class_id"];
	$content		= $row["content"];
	$create_time	= $row["create_time"];
	$publishdate	= explode(' ', $create_time);
	$views			= $row['views'];
	$author			= $row['author'];
	$pic			= $row['pic'];
	$annex			= $row['annex'];
	$keyword		= $row['keyword'];
	$description	= $row['description'];

	$sql = "update info set views=views+1 where id=$id";
	$db->query($sql);
} else {
	$db->close();
	header("location: ./");
	exit;
}

$base_id	 = substr($class_id, 0, 3);

if ( strlen($class_id) == 3 ) {  // 只有base_id，默认第一个子栏目ID
	$base_id	= substr($class_id, 0, 3);
	$second_id	= '';
	$third_id	= '';
	$base_name  = $db->getTableFieldValue('info_class', 'name', 'where id=\'' . $base_id . '\' limit 1');

	$cont = $db->getTableFieldValue('info_class', 'content', 'where id=\'' . $second_id . '\' limit 1');
	$second_name= '';
	$third_name = '';

} elseif ( strlen($class_id) == 6 ) {
	$base_id	= substr($class_id, 0, 3);
	$second_id	= substr($class_id, 0, 6);
	$third_id	= '';
	$base_name  = $db->getTableFieldValue('info_class', 'name', 'where id=\'' . $base_id . '\' limit 1');
	$cont = $db->getTableFieldValue('info_class', 'content', 'where id=\'' . $second_id . '\' limit 1');
	$second_name= $db->getTableFieldValue('info_class', 'name', 'where id=\'' . $second_id . '\' limit 1');
	$third_name = '';
} elseif ( strlen($class_id) == 9 ) {
	$base_id	= substr($class_id, 0, 3);
	$second_id	= substr($class_id, 0, 6);
	$third_id	= substr($class_id, 0, 9);
	$base_name  = $db->getTableFieldValue('info_class', 'name', 'where id=\'' . $base_id . '\' limit 1');
	$cont = $db->getTableFieldValue('info_class', 'content', 'where id=\'' . $second_id . '\' limit 1');
	$second_name= $db->getTableFieldValue('info_class', 'name', 'where id=\'' . $second_id . '\' limit 1');
	$third_name = $db->getTableFieldValue('info_class', 'name', 'where id=\'' . $third_id . '\' limit 1');
}

//获取上下文信息
$sql = "select id, title from info where class_id like ".$class_id." and state>0 order by create_time desc";
$rst = $db->query($sql);
while ($row = $db->fetch_array($rst)) {
	$infoArray[]	= $row;
}

$infoArrayCnt = count($infoArray);
if ($infoArrayCnt < 1) {
	$infoArrayCnt = -1;
}

for ($k = 0; $k < $infoArrayCnt; $k++) {
	if ($infoArray[$k]['id'] == $id){
		if ($k < $infoArrayCnt) {
			$next_id	= $infoArray[$k + 1]['id'];
			$next_title	= $infoArray[$k + 1]['title'];
		} else {
			$next_id	= 0;
		}

		if ($k > 0) {
			$pre_id		= $infoArray[$k - 1]['id'];
			$pre_title	= $infoArray[$k - 1]['title'];
		} else {
			$pre_id		= 0;
		}
	}
}

$first_id	= $infoArray[0]['id'];
$last_id	= $infoArray[$k -1]['id'];
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<meta name="renderer" content="webkit">
<meta name="wap-font-scale" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Cache-Control" content="no-siteapp" />
<meta name="keywords" content="<?=!empty($keyword) ? $keyword : $config_keyword?>" />
<meta name="description" content="<?=!empty($description) ? $description : $config_description?>" />
<title><?=!empty($third_id) ? $third_name." - " : ''?><?=$second_name?> - <?=$base_name?> - <?=$config_name?></title>
<link rel="stylesheet" href="images/base.css" />
<link rel="stylesheet" href="images/inside.css" />
<link rel="stylesheet" href="images/adver.css" />
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/jquery.SuperSlide.2.1.1.js"></script>
<script src="js/adver.js"></script>
<script src="js/common.js?rightButton=<?=$config_rightButton?>&mobilejump=<?=$config_mobilejump?>"></script>
<?echo $config_javascriptHead;?>
</head>
<body>
<?
require_once("begin.php");
?>
<div class="container">
	
	<div class="wrap clearfix">
		<?
		require_once("left.php");
		?>
		<div class="main">
			<div class="article">
				<div class="hd">
                    <h1><?=$title?></h1>
                    <p class="titBar">发布日期：<?=$publishdate[0]?>&emsp;浏览次数：<?=$views?></p>
				</div>
				<div class="bd">
					<?=$content;?>
                </div>
                <div class="ft">
					<ul>
						<li>上一篇：<? if($pre_id>0){?><a href="display.php?id=<?=$pre_id?>"><?=$pre_title?></a><? }else{?>无<? }?></li>
						<li>下一篇：<? if($next_id>0){?><a href="display.php?id=<?=$next_id?>"><?=$next_title?></a><? }else{?>无<? }?></li>
					</ul>
				</div>
			</div>
			<script language="javascript">
                var content_width;
                var imgObj = $(".article").find("img");
                if (imgObj.length > 0)
                {
                    for (var i = 0; i < imgObj.length; i++)
                    {
                        if (imgObj[i].width > <?=$content_width?>) imgObj[i].width = <?=$content_width?>;
                    }
                }
            </script>
        </div>
	</div>
</div>
<?
require_once("end.php");
?>
</body>
</html>