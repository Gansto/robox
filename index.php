<?
require ('init.php');
$menu = "default";
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
<title><?=$config_title?></title>
<link rel="stylesheet" href="images/base.css" />
<link rel="stylesheet" href="images/home.css" />
<link rel="stylesheet" href="images/adver.css" />
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/jquery.SuperSlide.2.1.1.js"></script>
<script src="js/adver.js"></script>
<script src="js/common.js?rightButton=<?=$config_rightButton?>&mobilejump=<?=$config_mobilejump?>"></script>
<?echo $config_javascriptHead;?>
</head>
<body>

<div class="wrapper">

	<div id="pageContain">
	
		<div class="page page1 current">
			<div class="contain">
				<?
				require ("begin.php");
				?>					
				<div class="banner">
					<div class="bd">
						<ul>
							<?
								$sql = "select title, pic, url from banner where class_id = 1 and state>0 and pic<>'' order by sortnum desc";
								$rst = $db->query($sql);
								while ($row = $db->fetch_array($rst)){
							?>
							<li><a href="<?=$row["url"]?>"><img src="<?=UPLOAD_PATH.$row["pic"]?>" width="1920" height="792" alt="<?=$row["title"]?>" /></a></li>
							<?
								}
							?>
						</ul>
					
					</div>
					<div class="hd clearfix">
						<ul><li class="">1</li><li class="">2</li><li class="on">3</li></ul>
					</div>
				</div>

				<script>
					$(".banner").slide({titCell:'.hd ul',mainCell:".bd ul",autoPlay:true, autoPage:true,delayTime:2000,interTime:5000});
				</script>
			</div>
		</div>

		<div class="page page2">
			<div class="contain">

				<div id="fix1" class="fix"></div>

				<div id="h-top1" class="h-top">
					<?
					require ("begin.php");
					?>
				</div>

				<div class="page2_area">

					<div class="page2_til"></div>

					<div class="page2_list">
						<ul class="clearfix">
							<?
							    $sql = "select title, pic, id from info where class_id = '105101' and state>0 order by sortnum desc limit 4";
							    $rst = $db->query($sql);
							    while ( $row = $db->fetch_array($rst) ) {
							?>
								<li>
									<a href="display.php?id=<?=$row['id']?>">
								    	<img src="<?=UPLOAD_PATH . $row['pic']?>" alt="<?=$row["title"]?>" >
										<p><?=$row["title"]?></p>
									</a>
								</li>
							<?
								}
							?>
						</ul>
				
					</div>

					<div class="page2_more"><a href="info.php?class_id=105">查看更多></a></div>

				</div>
			</div>
		</div>

		<div class="page page3">
			<div class="contain">

				<div id="fix2" class="fix"></div>

				<div id="h-top2" class="h-top">
					<?
					require ("begin.php");
					?>
				</div>


				<div class="page3_area">

					<div class="page3_til"></div>

					<div class="page3_list">
						<ul class="clearfix">
							
							<?
							    $sql = "select title,pic,id from info where class_id = '102101'  and state>0 order by sortnum desc limit 4";
							    $rst = $db->query($sql);
							    while ( $row = $db->fetch_array($rst) ) {
						    ?>						    
						    <li>
								<a href="display.php?id=<?=$row['id']?>">
									<img src="<?=UPLOAD_PATH . $row['pic']?>" alt="<?=$row["title"]?>">
									<div class="imgtext">
										<span><?=$row["title"]?></span>
										<s></s>
									</div>

									<div class="hid">
										<img src="images/se.png" alt="">
									 </div>
								</a>
							</li>

							<?
								}
							?>

						</ul>
					</div>

					<div class="page3_more"><a href="info.php?class_id=102">查看更多></a></div>

				</div>
			
			</div>
		</div>

		<div class="page page4">
			<div class="contain">

				<div id="fix3" class="fix"></div>

				<div id="h-top3" class="h-top">
					<?
					require ("begin.php");
					?>
				</div>

				<div class="page4_area clearfix">

					<div class="p4_l ">
						<div class="p4_lt clearfix">

							<div class="p4_lt1">							
								<?
								    $sql = "select title, pic, id,content from info where class_id = '104101' and state>0 and hot=1 order by sortnum desc limit 1";
								    $rst = $db->query($sql);
								    if ( $row = $db->fetch_array($rst) ) {
								?>
									<img src="<?=UPLOAD_PATH . $row['pic']?>" alt="<?=$row["title"]?>">
									<div class="txt">
										<strong><?=$row["title"]?></strong>
										<p><?=$row["content"]?></p>
										<a href="display.php?id=<?=$row['id']?>"><p class="btn"></p></a>
									</div>	
								<?
									}
								?>
							</div>

							<div class="p4til">
								<img src="images/p4til_03.jpg" alt="">
								<p>意大利瑞博思公司由RobertoMontorsi先生在1975年创立，总部位于意大利，主要生产运动控制器、集成驱动器、控制系统软件、HMI等。</p>
							</div>


							<div class="p4_lt2">								
								<?
								    $sql = "select title, pic, id,content from info where class_id = '104101' and state>0 and hot=1 order by sortnum desc limit 1,1";
								    $rst = $db->query($sql);
								    if ( $row = $db->fetch_array($rst) ) {
								?>
									<img src="<?=UPLOAD_PATH . $row['pic']?>" alt="<?=$row["title"]?>">
									<div class="txt">
										<strong><?=$row["title"]?></strong>
										<p><?=$row["content"]?></p>
										<a href="display.php?id=<?=$row['id']?>"><p class="btn"></p></a>
									</div>	
								<?
									}
								?>														
							</div>

						</div>

						<div class="p4_lb">
							<?
							    $sql = "select title, pic2, id,content from info where class_id = '104101' and 2>state>0 order by sortnum desc limit 1";
							    $rst = $db->query($sql);
							    if ( $row = $db->fetch_array($rst) ) {
							?>
								<img src="<?=UPLOAD_PATH . $row['pic2']?>" alt="<?=$row["title"]?>">
								<div class="txt">
									<strong><?=$row["title"]?></strong>
									<p><?=$row["content"]?></p>
									<a href="display.php?id=<?=$row['id']?>"><p class="btn"></p></a>
								</div>	
							<?
								}
							?>
						</div>

					</div>


					<div class="p4_r">
						<?
						    $sql = "select title, pic2, id,content from info where class_id = '104101' and state>1 order by sortnum desc limit 1";
						    $rst = $db->query($sql);
						    if ( $row = $db->fetch_array($rst) ) {
						?>
							<img src="<?=UPLOAD_PATH . $row['pic2']?>" alt="<?=$row["title"]?>">
							<div class="txt">
								<strong><?=$row["title"]?></strong>
								<p><?=$row["content"]?></p>
								<a href="display.php?id=<?=$row['id']?>"><p class="btn"></p></a>
							</div>	
						<?
							}
						?>											
					</div>


				</div>
			</div>
		</div>

		<div class="page page5">
			<div class="contain">

				<div id="fix4" class="fix"></div>

				<div id="h-top4" class="h-top">
					<?
					require ("begin.php");
					?>
				</div>

				<div class="page5_area clearfix">

					
					<?
					    $sql = "select keyword, pic, id,content from info where class_id = '101101' and state>0 and hot=1 order by sortnum desc limit 1";
					    $rst = $db->query($sql);
					    if ( $row = $db->fetch_array($rst) ) {
					?>					
						<div class="p5left">
							<img src="<?=UPLOAD_PATH . $row['pic']?>" alt="<?=$row["keyword"]?>">
							<p><?=$row["keyword"]?></p>
						</div>
					

						<div class="p5right">
							<div class="p5til"><img src="images/tt_03.png" alt=""></div>

							<div class="p5txt">

								<?=$row["content"]?>

								<div class="p5txt_bm">
									<p>公司理念</p>
									<p>公司宗旨</p>
									<p>运营方针</p>
									<a href="info.php?class_id=101">查看更多></a>
								</div>
							</div>
						</div>

					<?
						}
					?>

				</div>
			</div>
		</div>

		<div class="page page6">
			<div class="contain">

				<div id="fix5" class="fix"></div>

				<div id="h-top5" class="h-top">
					<?
					require ("begin.php");
					?>
				</div>


				<div class="page6_area">

					<div class="p6til"><img src="images/p6til_03.png" alt=""></div>

					<div class="p6_text clearfix">
						<div class="p6_box p1">
							<dl>
								<dt>关注我们</dt>

								<dd>
									<div class="contact clearfix">

										<?
										    $sql = "select  pic, title from banner where id = '4' and state>0  order by sortnum desc limit 1";
										    $rst = $db->query($sql);
										    if ( $row = $db->fetch_array($rst) ) {
										?>					
											<div class="cont contact1">
												<img src="images/q_03.jpg" alt="">
												<div class=" ctimg"><img src="<?=UPLOAD_PATH . $row['pic']?>" alt="<?=$row["title"]?>"></div>
											</div>

										<?
											}
										?>



										<?
										    $sql = "select  pic, title from banner where id = '5' and state>0  order by sortnum desc limit 1";
										    $rst = $db->query($sql);
										    if ( $row = $db->fetch_array($rst) ) {
										?>					
											<div class="cont contact2">
												<img src="images/q_05.jpg" alt="">
												<div class=" ctimg"><img src="<?=UPLOAD_PATH . $row['pic']?>" alt="<?=$row["title"]?>"></div>
											</div>

										<?
											}
										?>


										<?
										    $sql = "select  pic, title from banner where id = '6' and state>0  order by sortnum desc limit 1";
										    $rst = $db->query($sql);
										    if ( $row = $db->fetch_array($rst) ) {
										?>					
											<div class="cont contact3">
												<img src="images/q_07.jpg" alt="">
												<div class=" ctimg"><img src="<?=UPLOAD_PATH . $row['pic']?>" alt="<?=$row["title"]?>"></div>
											</div>

										<?
											}
										?>
										
									</div>
								</dd>
							</dl>
						</div>


						<div class="p6_box p2">
							<dl>
								<dt><?=$config_title?></dt>
								<dd>
									<p>地址：</p>
									<p><?=$config_copyright?></p>
								</dd>
							</dl>
						</div>

						<div class="p6_box p3">						
							<?=$config_contact?>
						</div>

					</div>
					
					<?
					    $sql = "select  pic, title from banner where id = '7' and state>0  order by sortnum desc limit 1";
					    $rst = $db->query($sql);
					    if ( $row = $db->fetch_array($rst) ) {
					?>					
						
						<div class="linklogo"><img src="<?=UPLOAD_PATH . $row['pic']?>" alt="<?=$row["title"]?>"></div>

					<?
						}
					?>
					
					


				</div>

				<?
				require ("end.php");
				?>

			</div>
		</div>




	</div>

	<ul id="navBar">
		<li><span></span><div class="p">-1</div></li>
		<li><span></span><div class="p">-2</div></li>
		<li><span></span><div class="p">-3</div></li>
		<li><span></span><div class="p">-4</div></li>
		<li><span></span><div class="p">-5</div></li>
		<li><span></span><div class="p">-6</div></li>
	</ul>


	<script src="js/fullPage.min.js"></script>

	<script>
		var runPage;
		runPage = new FullPage({
			id: 'pageContain',
			slideTime: 800,
			effect: {
				transform: {
					translate: 'Y'
				},
				opacity: [1, 1]
			},
			mode: 'wheel, touch, nav:navBar',
			easing: 'ease'
		});



	</script>

	<script>
        $("#fix1").click(function(){           
            $("#h-top1").slideToggle();
        });

        $("#fix2").click(function(){           
            $("#h-top2").slideToggle();
        });

        $("#fix3").click(function(){           
            $("#h-top3").slideToggle();
        });

        $("#fix4").click(function(){           
            $("#h-top4").slideToggle();
        });

        $("#fix5").click(function(){           
            $("#h-top5").slideToggle();
        });
	</script>

	
</div>


</body>
</html>