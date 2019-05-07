<div class="page-header">
	<div class="page-header-inner clearfix">
		<div class="page-logo"><a href="index.php">安徽网新科技</a><span class="menu-toggler sidebar-toggler"></span></div>
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"></a>
		<ul class="navbar-nav pull-right">
			<?
				if($db->getCount("message","state = 0")>0){
			?>
			<li class="tooltips" data-placement="bottom" data-original-title="客户留言"><a href="message_list.php"><i class="fa fa-envelope-o"></i><span class="badge badge-danger"><?=$db->getCount("message","state = 0")?></span></a></li>
			<?
			}
			?>
			<li class="tooltips" data-placement="bottom" data-original-title="网站首页"><a href="../"  target="_blank"> <i class="fa fa-home"></i> </a></li>
			<li class="separator"></li>
			<li class="dropdown dropdown-hover">
				<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"><span class="username"><?echo $_SESSION["ADMIN_NAME"]?></span><img alt="" class="img-circle" src="themes/images/avatar.jpg" /></a>
				<ul class="dropdown-menu dropdown-menu-default">
					<li><a href="index.php"><i class="fa fa-user-md"></i>用户管理中心</a></li>
 					<li><a href="logout.php" onClick="if (confirm('确定要退出吗？')) return true; else return false;"><i class="fa fa-key"></i>退出登录</a></li>
				</ul>
			</li>
			<!-- <li class="separator"></li>
			<li class="tooltips" data-placement="bottom" data-original-title="在线客服"><a href="javascript:;"><i class="fa fa-qq"></i></a></li> -->
		</ul>
	</div>
</div>