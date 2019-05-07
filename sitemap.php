<?
require("init.php");
$menu			= 'sitemap';
$base_name		= '站点地图';
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
        <div class="wrap clearfix">
        	<div class="breadcrumbs"><a href="./" class="u-home">网站首页</a> &gt; <a href="sitemap.php"><?=$base_name?></a></div>
       		<h3><?=$base_name?></h3>
        </div>
    </div>
	<div class="wrap clearfix">
		<?
		require_once("left.php");
		?>
		<div class="main">
			<div class="webmap">
				<?
				for ($i = 0; $i < $baseClassCnt; $i++) {
				?>
				<dl>
					<dt><a href="info.php?class_id=<?=$baseClassArray[$i]['id']?>"><?=$baseClassArray[$i]['name']?></a></dt>
					<dd class="clearfix">
						<?
						if ($secondClassArray[$i]['has_sub'] = 1) {
							$sql = "select id, name from info_class where id like '".$baseClassArray[$i]['id']."___' order by sortnum asc";
							$rst = $db->query($sql);
							while ($row = $db->fetch_array($rst)) {
						?>
						<a href="info.php?class_id=<?=$row["id"]?>"><?=$row["name"]?></a>
						<?
							}
						}
						?>
					</dd>
				</dl>
				<?
				}
				?>
			</div>
		</div>
	</div>
</div>
<?
require_once("end.php");
?>
</body>
</html>