<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");

$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

$sql = "select SUM(counter) as today from hit_counter where to_days(date) = to_days(now())";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst)) {
	$today=(int)$row['today'];
}

$sql = "select SUM(counter) as week from hit_counter where YEARWEEK(date_format(date,'%Y%m%d'),1) = YEARWEEK(now(),1)";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst)) {
	$week=(int)$row['week'];
}

$sql = "select SUM(counter) as mouth from hit_counter where DATE_FORMAT( date, '%Y%m' ) = DATE_FORMAT( CURDATE(),'%Y%m' )";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst)) {
	$mouth=(int)$row['mouth'];
}

$sql = "select SUM(counter) as sum from hit_counter";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst)) {
	$sum=(int)$row['sum'];
}

$sql = "select SUM(counter) as summes from message";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst)) {
	$summes=(int)$row['summes'];
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
<link href="themes/css/shy.css" rel="stylesheet" />
<link href="themes/css/shy-skin.css" rel="stylesheet" />
<style>.page-content{overflow: hidden;}</style>
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
			<div class="page-head">
				<div class="page-title"><h1>后台首页<small>欢迎进入网站后台管理中心！</small></h1></div>
			</div>
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat blue">
						<div class="visual"><i class="fa fa-comments"></i></div>
						<div class="details">
							<div class="number"><span data-counter="counterup" data-value="<?=$today?>"><?=$today?></span></div>
							<div class="desc">今日访问(PV)</div>
						</div>
						<a class="more" href="counter_list.php?tt=1">more <i class="fa fa-angle-double-right"></i> </a>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat red">
						<div class="visual"><i class="fa fa-bar-chart-o"></i></div>
						<div class="details">
							<div class="number"><span data-counter="counterup" data-value="<?=$week?>"><?=$week?></span></div>
							<div class="desc">本周访问(PV)</div>
						</div>
						<a class="more" href="counter_list.php?tt=2">more <i class="fa fa-angle-double-right"></i> </a>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat green">
						<div class="visual"><i class="fa fa-shopping-cart"></i></div>
						<div class="details">
							<div class="number"><span data-counter="counterup" data-value="<?=$mouth?>"><?=$mouth?></span></div>
							<div class="desc">本月访问(PV)</div>
						</div>
						<a class="more" href="counter_list.php?tt=3">more  <i class="fa fa-angle-double-right"></i> </a>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat purple">
						<div class="visual"><i class="fa fa-globe"></i></div>
						<div class="details">
							<div class="number"><span data-counter="counterup" data-value="<?=$sum?>"><?=$sum?></span></div>
							<div class="desc">访问总量(PV)</div>
						</div>
						<a class="more" href="counter_list.php">more  <i class="fa fa-angle-double-right"></i> </a>
					</div>
				</div>
			</div>
  			<div class="row">
				<div class="col-md-6 col-sm-6">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption"> <i class="fa fa-cogs font-blue"></i> <span class="caption-subject font-blue bold uppercase">热门产品排行</span></div>
							<?
							$mProducts = $db->getCount("info_class","(name like '%产品%' or name like '%项目%') and id like '___'");
							$base_id = $db->getTableFieldValue("info_class","id"," where (name like '%产品%' or name like '%项目%') and id like '___'");
							?>
	 					</div>
						<div class="portlet-body">
							<div class="task-content">
								<ul class="task-list">
									<?
									if( $mProducts > 0){
										$sql = "select title, id from info where state>0 and class_id like '$base_id%' order by state desc, sortnum desc limit 7";
										$rst = $db->query($sql);
										while($row = $db->fetch_array($rst)){
									?>
										<li><a href="../display.php?id=<?=$row['id']?>" target="_blank"><?=$row["title"]?></a></li>
									<?
										$i += 1;
										}
									}
									?>
	 							</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption"> <i class="fa fa-cogs font-blue"></i> <span class="caption-subject font-blue bold uppercase">热门新闻排行</span></div>
							<?
							$mNews = $db->GetCount("info_class","(name like '%动态%' or name like '%新闻%') and id like '___'");
							if( $mNews > 0){
	 							$base_id = $db->getTableFieldValue("info_class","id"," where (name like '%动态%' or name like '%新闻%') and id like '___'");
							}
							?>
						</div>
						<div class="portlet-body">
							<div class="task-content">
								<ul class="task-list">
									<?
									if( $mNews > 0){
										$sql = "select title,id from info where state>0 and class_id like '$base_id%' order by state desc, sortnum desc limit 7";
										$rst = $db->query($sql);
										while($row = $db->fetch_array($rst)){
									?>
										<li><a href="../display.php?id=<?=$row['id']?>" target="_blank"><?=$row["title"]?></a></li>
									<?
										$i += 1;
										}
									}
									?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?
				if($summes>0){
			?>
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption"><i class="icon-bubble font-red-sunglo"></i><span class="caption-subject font-red-sunglo bold uppercase">客户留言</span></div>
						</div>
						<div class="portlet-body" id="chats">
							<ul class="chats">
	 						<?
	                        $sql = "select name, content, reply, create_time, reply_time from message where state=1 order by state desc, sortnum desc limit 3";
	                        	$rst = $db->query($sql);
	                        	$i = 1;
								while($row = $db->fetch_array($rst)){
	                        ?>
								<li class="<? if($i%2==1){echo "out";}else{echo "in";} ?>" >
									<div class="avatar">IBW</div>
									<div class="message">
										<span class="arrow"></span><a href="javascript:;" class="name"><?=$row['name']?></a><span class="datetime"> <?=$row['create_time']?> </span>
										<span class="body"> <?=utf8substr(strip_tags($row['content']),68)?></span>
									</div>
								</li>
	                     	<?
								$i += 1;
							}
							?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<?
				}
			?>
		</div>
	</div>
</div>
<?
	require_once("foot.php");
?>
</body>
</html>