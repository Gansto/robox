<?
$menu_id = $_GET["menu_id"] ? trim($_GET["menu_id"]) : "";
$i = 0;
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);
?>

<div class="page-sidebar-wrapper">
	<div class="page-sidebar navbar-collapse collapse">
		<ul class="page-sidebar-menu" data-slide-speed="200">
			<li class="heading"> <i class="fa fa-navicon"></i> 栏目管理</li>
            <?
            $sql = "select id, name, state from info_class where id like '" . CLASS_SPACE . "' order by sortnum asc";
			$rst = $db->query($sql);
			while ($row = $db->fetch_array($rst))
			{
				$i++;
				if ($session_admin_grade == ADMIN_HIDDEN || $session_admin_grade == ADMIN_SYSTEM || $session_admin_grade == ADMIN_ADVANCED || hasBegin4Include($session_admin_popedom, $row["id"]) == true)
				{
			?>
			<li class="nav-item">
				<a href="javascript:;" class="nav-toggle"> <i class="fa fa-list"></i> <span class="title"><?=$row["name"]?></span><span class="arrow"></span></a>
				<ul class="sub-menu" <?if($row["id"] == substr($class_id,0,3)) echo "style='display:block;'"?>>
					<?
					$sql  = "select id, name from info_class where id like '" . $row["id"] . CLASS_SPACE . "' order by sortnum asc";
					$rst2 = $db->query($sql);
					while ($row2 = $db->fetch_array($rst2))
					{
						if ($session_admin_grade == ADMIN_HIDDEN || $session_admin_grade == ADMIN_SYSTEM || $session_admin_grade == ADMIN_ADVANCED || hasInclude($session_admin_popedom, $row["id"]) == true || hasInclude($session_admin_popedom, $row2["id"]) == true)
						{
					?>
					<li class="nav-item"><a href="info_list.php?class_id=<?=$row2["id"]?>" class="nav-toggle"> <i class="fa fa-caret-right"></i> <span class="title"><?=$row2["name"]?></span><span class="arrow"></span></a></li>
                    <?
						}
                    }
                        if ($session_admin_grade == ADMIN_HIDDEN || ($session_admin_grade == ADMIN_SYSTEM && $row["state"] == 1))
                    {
                    ?>
                        <?
                        if ($row["state"] == 1)
						{
						?>

                        	<li class="nav-item"><a href="second_class_list.php?class_id=<?=$row["id"]?>" class="nav-toggle"> <i class="fa fa-caret-right"></i> <span class="title"><b style="color: #CC5151">分类管理</b></span> </a></li>
                        <?
                        }
						else
						{
						?>
                           <li class="nav-item"><a href="second_class_list.php?class_id=<?=$row["id"]?>" class="nav-toggle"> <i class="fa fa-caret-right"></i> <span class="title"><b style="color: #ff0000">分类管理</b></span> </a></li>
						<?
                        }
                        ?>
                    <?
                    }
                    ?>
				</ul>
			</li>
			<?
				}
            }

            if ($session_admin_grade == ADMIN_HIDDEN || $session_admin_grade == ADMIN_SYSTEM || count($session_admin_advanced) > 0)
			{
				$i++;
			?>
			<li class="heading"> <i class="fa fa-navicon"></i> 高级管理</li>

        	<?
        		if ($session_admin_grade == ADMIN_HIDDEN){
				$sql = "select id, name, default_file from advanced where state>0 order by sortnum asc limit 20";
				}else{
				$sql = "select id, name, default_file from advanced where state=1 order by sortnum asc limit 20";
				}
				$rst = $db->query($sql);
				$j=1;
				while ($row = $db->fetch_array($rst))
				{
					if ($session_admin_grade == ADMIN_HIDDEN || $session_admin_grade == ADMIN_SYSTEM || hasInclude($session_admin_advanced, $row["id"]) == true)
					{
				?>
				<li class="nav-item">
					<a href="<?=$row["default_file"]?>" class="nav-toggle"> <i class="fa fa-ico<?=$row["id"]?>"></i> <span class="title"><?=$row["name"]?></span></a>
	            </li>
				<?
					}
				$j+=1;
	            }
	        ?>

			<?
            }

            if ($session_admin_grade == ADMIN_HIDDEN || $session_admin_grade == ADMIN_SYSTEM)
			{
				$i++;
			?>

			<li class="heading"> <i class="fa fa-navicon"></i> 系统管理</li>
			<li class="nav-item"><a href="javascript:;" class="nav-toggle"><i class="fa fa-cogs"></i><span  class="title">系统功能</span><span class="arrow"></span> </a>
				<ul class="sub-menu" <? if ($menum=="system") {echo " style='display:block'";}?> >
					<li class="nav-item"><a href="config_base.php" class="nav-toggle"> <i class="fa fa-sun-o"></i> <span class="title">基本设置</span></a></li>
					<li class="nav-item"><a href="config_advance.php" class="nav-toggle"> <i class="fa fa-wrench"></i> <span class="title">高级设置</span></a></li>
					<li class="nav-item"><a href="admin_list.php" class="nav-toggle"> <i class="fa fa-user-plus"></i> <span class="title">管理员列表</span></a></li>
					<li class="nav-item"><a href="record_list.php" class="nav-toggle"> <i class="fa fa-undo"></i> <span class="title">系统操作日志</span></a></li>
				 </ul>
			</li>
			<?
            }

            if ($session_admin_grade == ADMIN_HIDDEN)
			{
				$i++;
			?>
			<li class="heading"> <i class="fa fa-navicon"></i> 隐藏管理</li>
			<li class="nav-item"><a href="base_class_list.php" class="nav-toggle"> <i class="fa fa-cubes"></i> <span class="title">一级分类管理</span></a></li>
			<li class="nav-item"><a href="advanced_list.php" class="nav-toggle"> <i class="fa fa-balance-scale"></i> <span class="title">高级功能管理</span></a></li>
			<?
            }
			?>
			<li class="heading"> <i class="fa fa-navicon"></i> 个人管理</li>
			<li class="nav-item"><a href="admin_changepass.php" class="nav-toggle"> <i class="fa fa-key"></i> <span class="title">修改口令</span></a></li>
			<li class="nav-item"><a href="logout.php" class="nav-toggle" onClick="if (confirm('确定要退出吗？')) return true; else return false;"> <i class="fa  fa-download"></i> <span class="title">退出系统</span></a></li>
		</ul>
	</div>
</div>