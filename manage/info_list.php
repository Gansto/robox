<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");

$id				= (int)$_GET["id"];
$class_id		= trim($_GET["class_id"]);
$base_id	 	= substr($class_id, 0, 3);
$select_class	= empty($_GET["select_class"]) ? $class_id : trim($_GET["select_class"]);
$select_state	= (int)$_GET["select_state"];
$keyword		= urlencode(trim($_GET["keyword"]));
$page			= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;
if (empty($class_id) || !checkClassID($class_id, 2))
{
	info("参数有误！");
}

if (strlen($select_class) % CLASS_LENGTH != 0 && !checkClassID($select_class, strlen($select_class) / CLASS_LENGTH))
{
	info("参数有误！");
}

//权限检查
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && $session_admin_grade != ADMIN_ADVANCED && hasInclude($session_admin_popedom, substr($class_id, 0, CLASS_LENGTH)) != true && hasInclude($session_admin_popedom, $class_id) != true)
{
	info("没有权限！");
}

$listUrl	= "info_list.php?class_id=$class_id&select_class=$select_class&select_state=$select_state&keyword=$keyword&page=$page";
$editUrl	= "info_edit.php?class_id=$class_id&select_class=$select_class&select_state=$select_state&keyword=$keyword&page=$page";
$baseUrl	= "info_list.php?class_id=$class_id";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

$sql = "select title from info where id = $id";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst)){
	$oldtitle = $row["title"];
}


//查询顶级分类的记录设置
$sql = "select hasViews, hasState, hasPic, hasPic2, hasPics, hasContent, hasAnnex, hasWebsite, hasAuthor, hasSource from info_class where id='" . substr($class_id, 0, CLASS_LENGTH) . "'";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst))
{
	$hasViews	= $row["hasViews"];
	$hasState	= $row["hasState"];
	$hasPic		= $row["hasPic"];
	$hasPic2	= $row["hasPic2"];
	$hasPics	= $row["hasPics"];
	$hasContent	= $row["hasContent"];
	$hasAnnex	= $row["hasAnnex"];
	$hasWebsite	= $row["hasWebsite"];
	$hasAuthor	= $row["hasAuthor"];
	$hasSource	= $row["hasSource"];
}
else
{
	$db->close();
	info("参数有误！");
}

if($id > 0){
	//权限检查
	if ($session_admin_grade == ADMIN_COMMON)
	{
		$sql = "select pic, pic2, annex, files, content from info where id =$id and state=0 and admin_id=$session_admin_id";
	}
	else
	{
		$sql = "select pic, pic2, annex, files, content from info where id =$id";
	}

	$rst = $db->query($sql);
	while ($row = $db->fetch_array($rst))
	{
		$pic		.= $row["pic"] . ",";
		$pic2		.= $row["pic2"] . ",";
		$annex		.= $row["annex"] . ",";
		$files		.= $row["files"] . ",";
		$content	.= $row["content"] . ",";
	}

	//权限检查
	if ($session_admin_grade == ADMIN_COMMON)
	{
		$sql = "delete from info where id =$id and state=0 and admin_id=$session_admin_id";
	}
	else
	{
		$sql = "delete from info where id =$id";
	}

	//删除信息时 删除图片
	deleteFiles($pic, 1);
	deleteFiles($pic2, 1);
	deleteFiles($annex, 1);
	deleteFiles($files, 1);

	foreach(GetImgSrc($content) as $key=>$value){
		$newurl = explode("/",$value);
		$num = count($newurl);
		$newcontent[$key] = $newurl[$num-3].'/'.$newurl[$num-2].'/'.$newurl[$num-1];
	}
	deleteFiles($newcontent, 1);


	if (!$db->query($sql))
	{
		$db->query("rollback");
		$db->close();
		info("删除信息失败！");
	}

	//日志记录
	$db->record($_SESSION["ADMIN_ID"], $oldtitle, "删除信息" );
}

//批量操作
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$id_array	= $_POST["ids"];
	$action		= trim($_POST["action"]);
	if (empty($action))
	{
		$db->close();
		info("参数有误！");
	}

	if (!is_array($id_array))
	{
		$id_array = array($id_array);
	}

	//事务开始
	$db->query("begin");

	//删除记录
	if ($action == "delete")
	{
		//权限检查
		if ($session_admin_grade == ADMIN_COMMON)
		{
			$sql = "select pic, pic2, annex, files, content from info where id in (" . implode(",", $id_array) . ") and state=0 and admin_id=$session_admin_id";
		}
		else
		{
			$sql = "select pic, pic2, annex, files, content from info where id in (" . implode(",", $id_array) . ")";
		}

		$rst = $db->query($sql);
		while ($row = $db->fetch_array($rst))
		{
			$pic	 .= $row["pic"] . ",";
			$pic2	 .= $row["pic2"] . ",";
			$annex	 .= $row["annex"] . ",";
			$files	 .= $row["files"] . ",";
			$content .= $row["content"] . ",";
		}

		//权限检查
		if ($session_admin_grade == ADMIN_COMMON)
		{
			$sql = "delete from info where id in (" . implode(",", $id_array) . ") and state=0 and admin_id=$session_admin_id";
		}
		else
		{
			$sql = "delete from info where id in (" . implode(",", $id_array) . ")";
		}

		if (!$db->query($sql))
		{
			$db->query("rollback");
			$db->close();
			info("删除信息失败！");
		}

		//日志记录
 		$db->record($_SESSION["ADMIN_ID"],$db->getTableFieldValue("info_class", "name", "where id=$class_id"),"批量删除信息" );
	}
	//设置状态
	elseif ($action == "state")
	{
		$state = (int)$_POST["state"];
		$sql = "update info set state=$state where id in (" . implode(",", $id_array) . ")";
		if (!$db->query($sql))
		{
			$db->query("rollback");
			$db->close();
			info("设置状态失败！");
		}

		//日志记录
 		$db->record($_SESSION["ADMIN_ID"],$db->getTableFieldValue("info_class", "name", "where id=$class_id"),"批量更改信息状态" );
	}

	$db->query("commit");
	$db->close();
	if ($action == "delete")
	{
		//删除图片
		deleteFiles($pic, 1);
		deleteFiles($pic2, 1);
		deleteFiles($annex, 1);
		deleteFiles($files, 1);

		foreach(GetImgSrc($content) as $key=>$value){
			$newurl = explode("/",$value);
			$num = count($newurl);
			$newcontent[$key] = $newurl[$num-3].'/'.$newurl[$num-2].'/'.$newurl[$num-1];
		}
		deleteFiles($newcontent, 1);
	}
	header("Location: $listUrl");
	exit();
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
<script src="js/jquery.js"></script>
<script src="js/common.js"></script>
<script src="js/list.js" id="list" data="true"></script>
<script src="js/info.js"></script>
<script>
	function submit() {
	    var form = $('#listForm');
	    $('.checkboxes').each(function(index, element) {
	    	if (element.checked) {
	    		form.append($(element))
			}
		});
		form.submit();
	}
</script>
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
			<ul class="page-breadcrumb breadcrumb">
				<li><i class="fa fa-home"></i><a href="index.php">首页</a></li>
				<li><i class="fa fa-angle-right"></i><?=$db->getTableFieldValue("info_class", "name", "where id='$class_id'")?></li>
				<li><i class="fa fa-angle-right"></i><span class="active">信息列表</span></li>
			</ul>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light">
						<div class="portlet-title">
							<div class="row">
								<div class="col-md-8 col-sm-8">
									<div class="caption">
										<div class="form-inline">
											<a class="btn btn-shy btn-default tooltips" href="<?=$editUrl?>" data-placement="top" data-original-title="添加信息"><i class="fa fa-plus-circle"></i></a>
											<a class="btn btn-shy btn-default tooltips" href="<?=$listUrl?>" data-placement="top" data-original-title="刷新列表"><i class="fa fa-eye"></i></a>
											<a class="btn btn-shy btn-default tooltips" href="javascript:if (CheckSomeConfirm('确定删除选中的记录吗？')){document.listForm.action.value='delete';document.listForm.submit();}" data-placement="top" data-original-title="批量删除"><i class="fa fa-remove"></i></a>&nbsp;
											<?
											if ($db->getTableFieldValue("info_class", "has_sub", "where id='$class_id'") == 1) {
												$sql = "select id, name from info_class where id like '" . $class_id . "%' order by sortnum asc";
												//echo $sql;
												$rst = $db->query($sql);
												while ($row = $db->fetch_array($rst)) {
													$data[] = array("id" => $row["id"], "name" => $row["name"]);
												}
												$data = getNodeData($data, substr($class_id, 0, strlen($class_id) - CLASS_LENGTH), CLASS_LENGTH);
												?>
												<select class="form-control" name="select_class" style="width:250px;"
														onChange="window.location='<?= $baseUrl ?>&select_class=' + this.options[this.selectedIndex].value;">
													<?= optionsTree($data, $select_class) ?>
												</select>
												<? } ?>

											<select class="form-control" name="select_state" onChange="window.location='<?=$baseUrl?>&select_class=<?=$select_class?>&select_state=' + this.options[this.selectedIndex].value;">
												<option value="">请选择</option>
												<option value="1"<? if ($select_state == 1) echo " selected"?>>未审核</option>
												<option value="2"<? if ($select_state == 2) echo " selected"?>>正常</option>
												<option value="3"<? if ($select_state == 3) echo " selected"?>>推荐</option>
											</select>

											<select class="form-control" name="state" id="state" onChange="if(stateCheck($('input[type=checkbox]'))) {document.listForm.action.value = 'state';document.listForm.state.value='' + this.options[this.selectedIndex].value + '';submit();}">
												<option value="-1">设置状态为</option>
												<option value="0">未审核</option>
												<option value="1">正常</option>
												<option value="2">推荐</option>
											</select>
										</div>
	                                </div>
								</div>
								<div class="col-md-4 col-sm-4">
									<div class="sch-form pull-right">
										<form name="searchForm" method="get">
                                        	<div class="input-cont">
												<input class="form-control" type="text" name="keyword" value="<?=urldecode($keyword)?>" placeholder="标题搜索...">
												<input type="hidden" name="class_id" value="<?=$class_id?>">
												<input type="hidden" name="select_class" value="<?=$select_class?>">
												<input type="hidden" name="select_state" value="<?=$select_state?>">
 											</div>
											<div class="btn-cont"><a href="javascript:document.searchForm.submit();" class="btn blue icn-only"><i class="fa fa-check icon-white"></i></a></div>
										 </form>
                                    </div>
								</div>
							</div>
						</div>
						<div class="portlet-body table-responsive">
							<form id="listForm" name="listForm" method="post">
								<input type="hidden" name="action" value="" />
								<input type="hidden" name="state" value="">
								<table class="listTable table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1">

									<thead>
										<tr class="heaer">
											<th width="50" class="text-center table-checker"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkbox" /></th>
								            <th width="50" class="text-center hidden-xs">序号</th>
								            <th >标题</th>
								            <th width="100" class="text-center hidden-xs">类别</th>
								            <?
											if ($hasPic == 1){
											?>
								            	<th width="80" class="text-center hidden-xs">缩略图</th>
								            <?
								            }

											if ($hasPic2 == 1){
											?>
								            	<th width="80" class="text-center hidden-xs">备用图</th>
								            <?
								            }

											if ($hasAnnex == 1){
											?>
								            	<th width="80" class="text-center hidden-xs">附件</th>
								            <?
											}

											if ($hasViews == 1){
											?>
								            	<th width="80"  class="text-center hidden-xs">浏览</th>
								            <?
								            }

											if ($hasState == 1){
											?>
								            	<th width="100"  class="text-center">状态</th>
								            <?
								            }
											?>
								            <th width="120" class="text-center hidden-xs">发表时间</th>
								            <?
											if ($hasPics == 1){
											?>
								            	<th width="80" class="text-center hidden-xs">多图</th>
								            <?
								            }
											?>
											<th width="120" class="text-center hidden-xs">操作</th>
										</tr>
									</thead>
									<tbody>
										<?
										//筛选条件、权限
										if ($session_admin_grade == ADMIN_COMMON){
											$SQL_ = "and a.state=0 and a.admin_id=$session_admin_id and title like '%" . urldecode($keyword) . "%'";
										}else{
											switch ($select_state){
												case 1:
													$SQL_ = "and a.state=0";
													break;
												case 2:
													$SQL_ = "and a.state=1";
													break;
												case 3:
													$SQL_ = "and a.state=2";
													break;
												default:
													$SQL_ = "";
													break;
											}
										}

										//设置每页数
										$page_size		= DEFAULT_PAGE_SIZE;
										//总记录数
										$sql			= "select count(*) as cnt from info a where a.class_id like '" . $select_class . "%' and a.title like '%" . urldecode($keyword) . "%' $SQL_";
										$rst			= $db->query($sql);
										$row			= $db->fetch_array($rst);
										$record_count	= $row["cnt"];
										$page_count		= ceil($record_count / $page_size);
										//分页
										$page_str		= page($page, $page_count);
										//列表
										$sql = "select a.id, a.sortnum, a.title, a.author, a.source, a.website, a.pic, a.pic2, a.annex, a.views, a.files, a.create_time, a.state, b.name from info a left join info_class b on a.class_id=b.id where a.class_id like '" . $select_class . "%' and a.title like '%" . urldecode($keyword) . "%' $SQL_ order by a.sortnum desc";
								        $sql .= " limit " . ($page - 1) * $page_size . ", " . $page_size;
								        $rst = $db->query($sql);
								        while ($row = $db->fetch_array($rst)){
								        ?>
								        <tr class="<?if($i%2==1){echo "odd";}else{echo "even";}?>">
								        	<td width="50" class="text-center table-checker"><input class="checkbox" type="checkbox" name="ids[]" value="<?=$row["id"]?>" /></td>
							               	<td width="50" class="text-center hidden-xs"><?=$row["sortnum"]?></td>
											<td><a href="<?=$editUrl?>&id=<?=$row["id"]?>"><?=$row["title"]?></a></td>
							                <td class="text-center hidden-xs"><?=$row["name"]?></td>
							                <?
											if ($hasPic == 1){
											?>
							                <td class="text-center hidden-xs">
												<?
							                		if (!empty($row["pic"])){
							                	?>
							                	<a id="pic<?=$row["id"]?>" href="javascript:pic('<?=$row["id"]?>', 'info', 'id', 'pic', '', '')" picUrl="<?=UPLOAD_PATH_FOR_ADMIN.$row["pic"]?>" class="picView"><font color='#FF6600'>有</font></a>
							                	<?
							                		}else{
							                	?>
												<a id="pic<?=$row["id"]?>" href="javascript:pic('<?=$row["id"]?>', 'info', 'id', 'pic', '', '')" picUrl="" class="picView">上传</a>
							                	<?
							                		}
							                	?>
							                </td>
							                <?
							                }

											if ($hasPic2 == 1){
											?>
							                	<td class="text-center hidden-xs">
													<?
								                		if (!empty($row["pic2"])){
								                	?>
								                	<a id="pic2<?=$row["id"]?>" href="javascript:pic2('<?=$row["id"]?>', 'info', 'id', 'pic2', '', '')"><font color='#FF6600'>有</font></a></th>
								                	<?
								                		}else{
								                	?>
													<a id="pic2<?=$row["id"]?>" href="javascript:pic2('<?=$row["id"]?>', 'info', 'id', 'pic2', '', '')">上传</a>
								                	<?
								                		}
								                	?>
							                	</td>
							                <?
							                }

											if ($hasAnnex == 1){
											?>
							                <td class="text-center hidden-xs">
												<?
							                		if (!empty($row["annex"])){
							                	?>
							                	<a id="annex<?=$row["id"]?>" href="javascript:annex('<?=$row["id"]?>', 'info', 'id', 'annex', '', '')"><font color='#FF6600'>有</font></a>
							                	<?
							                		}else{
							                	?>
												<a id="annex<?=$row["id"]?>" href="javascript:annex('<?=$row["id"]?>', 'info', 'id', 'annex', '', '')">上传</a>
							                	<?
							                		}
							                	?>
							                </td>
							                <?
											}

											if ($hasViews == 1){
											?>
							                <td class="text-center hidden-xs"><?=$row["views"]?></td>
							                <?
							                }

											if ($hasState == 1){
											?>
						                    <td class="text-center  ">
					                        <?
					                        switch ($row["state"])
					                        {
					                            case 0:
					                                echo "<span class='label label-sm label-default'>未审核</span>";
					                                break;
					                            case 1:
					                                echo "<span class='label label-sm label-success'>正常</span>";
					                                break;
					                            case 2:
					                                echo "<span class='label label-sm label-danger'>推荐</span>";
					                                break;
					                            default :
					                                echo "<font color=#FF0000>错误</font>";
					                                exit;
					                        }
					                        ?>
						                    </td>
							                <?
							                }
											?>
							                <td class="text-center hidden-xs"><?=formatDate("Y-m-d", $row["create_time"])?></td>
							                <?
							                	if($hasPics == 1){
							                ?>
											<td class="text-center hidden-xs"><a class="label label-sm label-primary tooltips" href="info_list_list.php?infoid=<?=$row["id"]?>&class_id=<?=$class_id?>" data-placement="top" data-original-title="管理">管理</a></td>
											<?
											}
											?>
											<td class="text-center hidden-xs"><a class="label label-sm label-primary tooltips" href="<?=$editUrl?>&id=<?=$row["id"]?>" data-placement="top" data-original-title="编辑信息">编辑</a> <a class="label label-sm label-danger tooltips" href="<?=$listUrl?>&id=<?=$row["id"]?>" data-placement="top" data-original-title="删除" onClick="return DeleteConfirmCustom('');">删</a></td>
							            </tr>
								        <?
								        }
								        ?>
									</tbody>
								</table>
							</form>
						</div>
						<div class="row">
							<div class="col-md-5 col-sm-5 hidden-xs">
								<div class="records">第<?=$page?>页 共<?=$page_count?>页 共<?=$record_count?>条记录</div>
							</div>
							<div class="col-md-7 col-sm-7">
								<?=genPaginationBar($page, $page_count)?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?
	require_once("foot.php");
?>
</body>
</html>