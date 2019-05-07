<?
switch ($base_id){
    case 101:
        $base_en_name = "ABOUT";
        break;
    case 102:
        $base_en_name = "PRODUCT CENTER";
        break;
    case 103:
        $base_en_name = "SKILL";
        break;
    case 104:
        $base_en_name = "NEWS";
        break;
    case 105:
        $base_en_name = "APPLICATION AREA";
        break;
    case 106:
        $base_en_name = "Contact US";
        break;
    case 107:
        $base_en_name = "Contact";
        break;
    default:
        $base_en_name = $menu;
        break;
}
?>
<div class="sidebar">
	<h2 class="leftTitle"><i><?=$base_en_name?></i><s><?=$base_name?></s><div class="line"></div></h2>
	<div class="location">

    		<div class="breadcrumbs">
    		<p><s></s>你当前所在位置：</p>
    		
    		<? 
    			if ($menu == "display"){
    		?>
    				<a href="./" class="u-home">网站首页</a>
		    		<a href="info.php?class_id=<?=$base_id?>"> <?= '- ' .$base_name?></a> 
		    		<a href="info.php?class_id=<?=$class_id?>"> <?= '- ' .$second_name?></a>
		    		<?= !empty($third_id) ? " - <a href='info?class_id=". $third_id ."'>". $third_name ."</a>" : '' ?></div>

		    <?	
		    	}else if($menu == "SEARCH"){
		    ?> 		
		    		<a href="./" class="u-home">网站首页</a>
          			<a href="javascript:;"><?= '- ' .$base_name?></a>
         		 	<?=!empty($search) ? "<a href='?search_keyword=". $search ." '- ' ". $search ."</a>" : '' ?></div>
		    <?	
    			}else{
    		?>
    				<a href="./" class="u-home">网站首页</a>
		    		<a href="?class_id=<?=$base_id?>"> <?= '- ' .$base_name?></a> 
		    		<a href="?class_id=<?=$class_id?>"> <?= '- ' .$second_name?></a>
		    		<?= !empty($third_id) ? "<a href='?class_id=". $third_id ." '-' ". $third_name ."</a>" : '' ?></div>
			<?
    			}
    		?>

	</div>


	<div class="menu">
		<dl>
		<?
		if($menu == "job") {
			$sql = "select id, name from job where state>0 order by sortnum desc";
			$rst = $db->query($sql);
			while ($row = $db->fetch_array($rst)) {
		?>
			<dt><a href="job_display.php?id=<?=$row["id"]?>" <?=($id == $row["id"]) ? 'class="current"' : ''?>><?=$row["name"]?></a></dt>
		<?
			}
		} elseif ($menu =="contact"){
			$sql = "select * from contact order by sortnum desc";
			$rst = $db -> query($sql);
			while ($row = $db -> fetch_array($rst)){
		?>
			<dt><a href="contact.php?id=<?=$row["id"]?>" <?=($id == $row["id"]) ? 'class="current"' : ''?>><?=$row["name"]?></a></dt>
		<?
			}
		} elseif ($menu == "message" || $menu == "sitemap") {
		?>
			<dt><a href="?" class="current"><?=$base_name?></a></dt>
		<?
		} elseif ($menu == "search") {
			if ($search_keyword == "") {
		?>
			<dt><a href="?" class="current"><?=$base_name?></a></dt>
		<?
			} else {
		?>
			<dt><a href="<?=$pageUrl?>" class="current"><?=$base_name?></a></dt>
		<?
			}
		} else {
			$bid = $base_id;
			$secondClassCnt = count($secondClassArray[$bid]);

			for ($i = 0; $i < $secondClassCnt; $i++) {
				if ($secondClassArray[$bid][$i]['id'] == $second_id) {
		?>
				<dt><a href="info.php?class_id=<?=$secondClassArray[$bid][$i]['id']?>" class="current"><?=$secondClassArray[$bid][$i]['name']?></a></dt>
				<?
				if ($secondClassArray[$bid][$i]['has_sub'] = 1) {
					$sql = "select id, name from info_class where id like '".$second_id."___' order by sortnum asc";
					$rst = $db->query($sql);
					while ($row = $db->fetch_array($rst)) {
				?>
					<dd><a href="info.php?class_id=<?=$row["id"]?>"<?= ($row["id"] == $third_id) ?  ' class="current"' : ''?>><?=$row["name"]?></a></dd>
					<?
					if($third_id){
						$sql = "select id, name from info_class where id like '".$row["id"]."___' order by sortnum asc";
						//echo $sql;
						$rst2 = $db->query($sql);
						while ($row2 = $db->fetch_array($rst2)) {
					?>
						<dd class="third"><a href="info.php?class_id=<?=$row2["id"]?>"<?= ($row2["id"] == $four_id) ?  ' class="current"' : ''?>>└ <?=$row2["name"]?></a></dd>
					<?
						}
					}
					?>
				<?
					}
				}
				?>
		<?
				} else {
		?>
				<dt><a href="info.php?class_id=<?=$secondClassArray[$bid][$i]['id']?>"><?=$secondClassArray[$bid][$i]['name']?></a></dt>
		<?
				}
			}
		}
		?>
		</dl>
	</div>
</div>