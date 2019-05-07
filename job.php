<?
require("init.php");

$base_name	  = "人力资源";
$base_en_name = "Job";
$menu 		  = "job";
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<meta name="renderer" content="webkit">
<meta name="wap-font-scale" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Cache-Control" content="no-siteapp" />
<meta name="keywords" content="<?=$config_keyword?>" />
<meta name="description" content="<?=$config_description?>" />
<title><?=$base_name?> - <?=$config_name?></title>
<link rel="stylesheet" href="images/base.css" />
<link rel="stylesheet" href="images/inside.css" />
<link rel="stylesheet" href="images/adver.css" />
<script src="js/jquery-1.11.3.min.js"></script>
<script src="js/jquery.SuperSlide.2.1.2.js"></script>
<script src="js/adver.js"></script>
<script src="js/common.js?rightButton=<?=$config_rightButton?>&mobilejump=<?=$config_mobilejump?>"></script>
<? echo $config_javascriptHead; ?>
</head>
<body>
<?
require_once("begin.php");
?>
<div class="container">
	<div class="location">
        <div class="breadcrumbs"><a href="./" class="u-home">网站首页</a> &gt; <a href="job.php"><?=$base_name?></a></div>
       	<h3><?=$base_name?></h3>
    </div>
	<div class="wrap clearfix">
		<?
		require_once("left.php");
		?>
		<div class="main">
			<div class="hr">
				<?
				$sql = "select count(*) as cnt from job where state=1";
				$rst = $db->query($sql);
				if ($row = $db->fetch_array($rst)) {
					$recordCount = $row["cnt"];
				} else {
					$recordCount = 0;
				}

				$page		= (int)$_GET["page"];
				$page		= $page > 0 ? $page : 1;
				$pageSize	= 10;
				$pageCount	= ceil($recordCount / $pageSize);
				if ($page > $pageCount) $page = $pageCount;

				$sql = "select * from job where state=1 order by sortnum desc limit ".$pageSize*($page-1).",".$pageSize;
				$rst = $db->query($sql);
				while ($row = $db->fetch_array($rst)) {
				?>
				<dl class="hr-list">
					<dt class="title"><?=$row['name']?></dt>
					<dd class="info"><?=$row['content']?></dd>
					<dd class='apply'><a href='job_display.php?id=<?=$row['id']?>'>我要应聘</a></dd>
				</dl>
				<?
				}
				?>
			</div>
			<div class="page"><span><?=page2($page, $pageCount, "job.php?")?></span>共 <?=$recordCount?> 条信息</div>
		</div>
	</div>
</div>
<?
require_once("end.php");
?>
</body>
</html>