 <?
	require("adver.php");

	$surl = $_SERVER['HTTP_HOST'];
	$nurl = "ibw.cc";
	$boss  =  substr_count($surl, $nurl);

    if ($boss==1){
    	if ($_COOKIE["vcode"]<>$vcodeConst){
 			header("location: visit.php");
			exit;
		}
    }
?>

<div class="header">
	<div class="g-in clearfix">

		<div class="logo">
			<?
				$sql = "select pic, url from banner where class_id = 3 and state>0 and pic<>''";
				$rst = $db->query($sql);
				if ($row = $db->fetch_array($rst)){
			?>
			<a href="./"><img src="<?=UPLOAD_PATH.$row["pic"]?>" width="175px" height="58" alt="<?=$config_title?>" /></a>
			<?
				}
			?>
		</div>

		<div class="nav">
			<ul class="clearfix">
				<li><a href="./" <? if ($menu == "default") { echo "class='current'"; } ?>>首页</a></li>
				<li><a href="info.php?class_id=101"<?if($base_id=="101"){echo" class='current'";}?>>关于我们</a></li>
				<li><a href="info.php?class_id=102"<?if($base_id=="102"){echo" class='current'";}?>>产品中心</a></li>
				<li><a href="info.php?class_id=103"<?if($base_id=="103"){echo" class='current'";}?>>技术支持</a></li>
				<li><a href="info.php?class_id=104"<?if($base_id=="104"){echo" class='current'";}?>>资讯动态</a></li>
				<li><a href="info.php?class_id=105"<?if($base_id=="105"){echo" class='current'";}?>>应用中心</a></li>
				<li><a href="info.php?class_id=106"<?if($base_id=="106"){echo" class='current'";}?>>联系我们</a></li>
			</ul>
		</div>

		<div class="select">
			<select name="" id="">
				<option value="">EN</option>
				<option value="">CH</option>
			</select>
		</div>

		<div class="search">
			<form action="search.php">
				<input type="text" name="search_keyword" class="search_text" required="" placeholder="Search">
				<input type="submit" value="" class="search_btn">
			</form>
		</div>

	</div>		
</div>