<?
$sql = "select id, title, mode, url, width, height, time, pic from adver where state=1 and pic<>''";
$rst = $db->query($sql);
while ($row = $db->fetch_array($rst))
{
	$ad_id		= $row["id"];
	$ad_title	= $row["title"];
	$ad_mode	= $row["mode"];
	$ad_url		= $row["url"];
	$ad_width	= $row["width"];
	$ad_height	= $row["height"];
	$ad_time	= $row["time"];
	$ad_pic		= UPLOAD_PATH . $row["pic"];

	if ($ad_width > 0 && $ad_height > 0)
	{
		//弹出广告
		if($ad_mode == "popup" && $menu == "default"){
		?>
			<div id="popup" style="width:<?=$ad_width?>px;height:<?=$ad_height?>px;">
				<div class="ad_closed">X</div>
				<div id="ad_link"><a href="<?=$ad_url?>"><img src="<?=$ad_pic?>" width="<?=$ad_width?>" height="<?=$ad_height?>" alt="<?=$ad_title?>" /></a></div>
			</div>
			<div id="ad_shadow"></div>
			<script>
				popup();
			</script>
		<?
		}

		//漂浮广告
		if($ad_mode == "float" && $menu == "default"){
		?>
			<div id="ad1" style="width:<?=$ad_width?>px;height:<?=$ad_height?>px;">
				<div class="ad_closed">X</div>
				<div id="ad_link"><a href="<?=$ad_url?>"><img src="<?=$ad_pic?>" width="<?=$ad_width?>" height="<?=$ad_height?>" alt="<?=$ad_title?>" /></a></div>
			</div>
			<script>
			var ad1=new AdMove("ad1");
			ad1.Run();
			//多组漂浮
			//var ad2=new AdMove("ad2");
			//ad2.Run();
			//
			$('.ad_closed').click(function(){
				$('#ad1').remove();
			});
			</script>
		<?
		}

		//左侧门帘
		if($ad_mode == "hangL"){
		?>
			<div id="hangL" style="width:<?=$ad_width?>px;height:<?=$ad_height?>px;">
				<div class="ad_closed">X</div>
				<div id="ad_link"><a href="<?=$ad_url?>"><img src="<?=$ad_pic?>" width="<?=$ad_width?>" height="<?=$ad_height?>" alt="<?=$ad_title?>" /></a></div>
			</div>
			<script>
				hang();
			</script>
		<?
		}

		//右侧门帘
		if($ad_mode == "hangR"){
		?>
			<div id="hangR" style="width:<?=$ad_width?>px;height:<?=$ad_height?>px;">
				<div class="ad_closed">X</div>
				<div id="ad_link"><a href="<?=$ad_url?>"><img src="<?=$ad_pic?>" width="<?=$ad_width?>" height="<?=$ad_height?>" alt="<?=$ad_title?>" /></a></div>
			</div>
			<script>
				hang();
			</script>
		<?
		}

		//左右门帘
		if($ad_mode == "hangLR"){
		?>
			<div id="hangL" style="width:<?=$ad_width?>px;height:<?=$ad_height?>px;">
				<div class="ad_closed">X</div>
				<div id="ad_link"><a href="<?=$ad_url?>"><img src="<?=$ad_pic?>" width="<?=$ad_width?>" height="<?=$ad_height?>" alt="<?=$ad_title?>" /></a></div>
			</div>
			<div id="hangR" style="width:<?=$ad_width?>px;height:<?=$ad_height?>px;">
				<div class="ad_closed">X</div>
				<div id="ad_link"><a href="<?=$ad_url?>"><img src="<?=$ad_pic?>" width="<?=$ad_width?>" height="<?=$ad_height?>" alt="<?=$ad_title?>" /></a></div>
			</div>
			<script>
				hang();
			</script>
		<?
		}

		//拉屏广告
		if($ad_mode == "bigScreen"){
		?>
			<div id="bigScreen" style="width:<?=$ad_width?>px;">
				<div class="ad_closed">X</div>
				<div id="ad_link"><a href="<?=$ad_url?>"><img src="<?=$ad_pic?>" width="<?=$ad_width?>" height="<?=$ad_height?>" alt="<?=$ad_title?>" /></a></div>
			</div>
			<script>
				setTimeout(bigScreen,<?=$ad_time?>000);
			</script>
		<?
		}

	}
}
?>