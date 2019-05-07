<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");
require(dirname(__FILE__) . "/uploadImg.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, INFO_MULTI_ADVANCEDID) == false)
{
	info("没有权限！");
}

$select_class	= trim($_GET["select_class"]);
$select_state	= (int)$_GET["select_state"];
$keyword		= urlencode(trim($_GET["keyword"]));
$page			= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;

$listUrl = "info_multi.php?select_class=$select_class&select_state=$select_state&keyword=$keyword&page=$page";
$baseUrl = "info_multi.php";

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

//删除
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$id_array = $_POST["ids"];
	if (!is_array($id_array))
	{
		$id_array = array($id_array);
	}

	$operation	= trim($_POST["operation"]);
	$end_class	= trim($_POST["end_class"]);

	if (empty($operation) || (!empty($operation) && $operation != "delete" && empty($end_class)))
	{
		$db->close();
		info("填写的参数错误！");
	}

	if ($operation == "delete") //删除
	{
		$sql = "select pic, annex, files from info where id in (" . implode(",", $id_array) . ")";
		$rst = $db->query($sql);
		while ($row = $db->fetch_array($rst))
		{
			$pic	.= $row["pic"] . ",";
			$annex	.= $row["annex"] . ",";
			$files	.= $row["files"] . ",";
		}

		$db->query("begin");

		$sql = "delete from info where id in (" . implode(",", $id_array) . ")";

		$rst = $db->query($sql);
		if ($rst)
		{
			//日志记录
			$db->record($_SESSION["ADMIN_ID"],"批量操作","批量删除信息" );

			deleteFiles($pic, 1);
			deleteFiles($annex, 1);
			deleteFiles($files, 2);
			$db->query("commit");
			$db->close();
			header("Location: $listUrl");
			exit();
		}
		else
		{
			$db->query("rollback");
			$db->close();
			info("删除信息失败！");
		}
	}
	elseif ($operation == "move") // 转移
	{
		$db->query("begin");

		$sql = "update info set class_id='$end_class' where id in (" . implode(",", $id_array) . ")";
		$rst = $db->query($sql);
		if ($rst)
		{
			//日志记录
			$db->record($_SESSION["ADMIN_ID"],"批量操作","批量转移信息" );
			
			$db->query("commit");
			$db->close();
			info("转移信息成功！");
			exit();
		}
		else
		{
			$db->query("rollback");
			$db->close();
			info("转移信息失败！");
		}
	}
	elseif ($operation == "copy") // 复制
	{
		$db->query("begin");

		$sql = "select * from info where id in (" . implode(",", $id_array) . ")";
		$rst = $db->query($sql);
		while ($row = $db->fetch_array($rst)){
			$id         	= $db->getMax("info", "id") + 1;
			$sortnum    	= $db->getMax("info", "sortnum", "class_id='$end_class'") + 10;
			$title   		= $row["title"];
			$admin_id   	= $row["admin_id"];
			$class_id   	= $row["class_id"];
			$author   		= $row["author"];
			$source   		= $row["source"];
			$website  		= $row["website"];
			$pic   			= $row["pic"];
			$pic2   		= $row["pic2"];
			$annex   		= $row["annex"];
			$keyword   		= $row["keyword"];
			$intro   		= mysql_real_escape_string($row["intro"]);
			$content   		= mysql_real_escape_string($row["content"]);
			$content2   	= mysql_real_escape_string($row["content2"]);
			$content3   	= mysql_real_escape_string($row["content3"]);
			$content4   	= mysql_real_escape_string($row["content4"]);
			$content5   	= mysql_real_escape_string($row["content5"]);
			$content6   	= mysql_real_escape_string($row["content6"]);
			$webcontent 	= mysql_real_escape_string($row["webcontent"]);
			$files   		= $row["files"];
			$views   		= $row["views"];
			$create_time   	= $row["create_time"];
			$modify_time   	= $row["modify_time"];
			$state   		= $row["state"];
			$price   		= $row["price"];
			$description   	= $row["description"];
			$hot   			= $row["hot"];
			$actual   		= $row["actual"];

			$sql2 = "insert into info(id, sortnum, title, admin_id, class_id, author, source, website, pic, pic2, annex, keyword, intro, content, content2, content3, content4, content5, content6, webcontent, files, views, create_time, modify_time, state, price, description, hot, actual) values($id, $sortnum, '$title', $session_admin_id, '$end_class', '$author', '$source', '$website', '$pic', '$pic2', '$annex', '$keyword', '$intro', '$content', '$content2', '$content3', '$content4', '$content5', '$content6', '$webcontent', '$files', $views, '$create_time', '$modify_time', $state, '$price', '$description', $hot, '$actual')";
			$rst2 = $db->query($sql2);
		}

		if ($rst)
		{
			$db->query("commit");
			$db->close();
			info("复制信息成功！");
			exit();
		}
		else
		{
			$db->query("rollback");
			$db->close();
			info("复制信息失败！");
		}
	}
	elseif ($operation == "waterPic") // 缩略图水印
	{
		$sql = "select pic from info where id in (" . implode(",", $id_array) . ")";
		$rst = $db->query($sql);
		while ($row = $db->fetch_array($rst))
		{
			//$pic	 .= $row["pic"] . ",";
			$ext 	  = strToLower(getFileExt($row['pic'])); //获取图片格式
			$pic_path = '../upload/' . $row["pic"];

			// 水印执行操作
			if ($config_watermark == 0){
				$config_waterpic = "";
				$config_waterpos = 5;

				$db->query("rollback");
				$db->close();
				info("请先进行水印设置！");
			}
			else
			{
				if(($ext=="gif" || $ext=="png" || $ext=="jpg") && $config_waterpic!="")
				{
					// $waterImage="images/water.png";//水印图片路径
					$waterImage = '../upload/'. $config_waterpic; //水印图片路径
					imageWaterMark($pic_path, $config_waterpos, $waterImage);
				}
			}
		}

		$db->query("commit");
		$db->close();
		info("批量缩略图水印执行成功！");
		exit();
	}
	elseif ($operation == "waterCon") // 内容图水印
	{
		$sql = "select content from info where id in (" . implode(",", $id_array) . ")";
		$rst = $db->query($sql);
		while ($row = $db->fetch_array($rst))
		{
			$pic_path = GetImgSrc($row["content"]);

			foreach($pic_path as $value){
				//$value = str_replace('/new', '..', $value); // 替换路径

				// 本地将注释去除
				//$url = explode("/", $value);
				//echo "string is: " . $value[1];
				//$value = str_replace('/'.$url[1], '..', $value); // 替换路径
				//

				$value = '..'.$value; // 替换路径
				//echo var_dump($value);
				//exit;

				$ext = @end(explode(".", $value));

				if ($config_watermark == 0){
					$config_waterpic = "";
					$config_waterpos = 5;

					$db->query("rollback");
					$db->close();
					info("请先进行水印设置！");
				}
				else
				{
					if(($ext=="gif" || $ext=="png" || $ext=="jpg") && $config_waterpic!="")
					{
						// $waterImage="images/water.png";//水印图片路径
						$waterImage = '../upload/'. $config_waterpic; //水印图片路径
						imageWaterMark($value, $config_waterpos, $waterImage);
					}
				}
			}
		}

		$db->query("commit");
		$db->close();
		info("内容图水印执行成功！");
		exit();
	}
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
				<li><i class="fa fa-angle-right"></i>系统管理</li>
				<li><i class="fa fa-angle-right"></i><span class="active">信息批量操作</span></li>
			</ul>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light">
						<div class="portlet-title">
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<div class="caption"><a class="btn btn-shy btn-default tooltips" href="<?=$listUrl?>" data-placement="top" data-original-title="刷新列表"><i class="fa fa-eye"></i></a></div>
								</div>
								<div class="col-md-6 col-sm-6">
									<div class="sch-form pull-right"  style="height:40px"></div>
								</div>
							</div>
						</div>
						<div class="portlet-body table-responsive">
							<input type="hidden" name="action" value="" />
							<form name="listForm" method="post">
								<table class="listTable table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1">
									<thead>
										<tr class="heaer">
											<th width="50" class="text-center table-checker"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkbox" /></th>
								            <th width="100" class="text-center hidden-xs">序号</th>
								            <th class="text-center hidden-xs">标题</th>
								            <th class="text-center hidden-xs">类别</th>
								            <th class="text-center hidden-xs">状态</th>
								            <th class="text-center hidden-xs">发表时间</th>
										</tr>
									</thead>
									<tbody>
										<?
										//筛选条件
										if (!empty($select_class))
										{
						                	$SQL_ = "and a.class_id like '$select_class%' ";
										}

										//设置每页数
										$page_size = DEFAULT_PAGE_SIZE;
										//总记录数
										$sql = "select count(*) as cnt from info a where a.title like '%" . urldecode($keyword) . "%' $SQL_";
										$rst = $db->query($sql);
										$row = $db->fetch_array($rst);
										$record_count = $row["cnt"];
										$page_count = ceil($record_count / $page_size);
										//分页
										$page_str = page($page, $page_count, $pageUrl);
										//列表
										$sql = "select a.id, a.title, a.sortnum, a.author, a.source, a.website, a.pic, a.views, a.files, a.create_time, a.state, b.name from info a left join info_class b on a.class_id=b.id where a.title like '%" . urldecode($keyword) . "%' $SQL_ order by a.class_id asc, a.sortnum desc";
						                $sql .= " limit " . ($page - 1) * $page_size . ", " . $page_size;
						                $rst = $db->query($sql);
						                while ($row = $db->fetch_array($rst)){
						                ?>
						                <tr class="<?if($i%2==1){echo "odd";}else{echo "even";}?>">
						                	<th width="50" class="text-center table-checker"><input class="checkbox" type="checkbox" name="ids[]" id="ids" value="<?=$row["id"]?>" /></th>
					                        <th class="text-center hidden-xs"><?=$row["sortnum"]?></th>
					                        <th class="text-center hidden-xs"><?=$row["title"]?></th>
					                        <th class="text-center hidden-xs"><?=$row["name"]?></th>
					                        <th class="text-center hidden-xs">
					                            <?
					                            switch ($row["state"]){
					                                case 0:
					                                    echo "<font color=#FF9900>未审核</font>";
					                                    break;
					                                case 1:
					                                    echo "正常";
					                                    break;
					                                case 2:
					                                    echo "<font color=#FF3300>推荐</font>";
					                                    break;
					                                default :
					                                    echo "<font color=#FF0000>错误</font>";
					                                    exit;
					                            }
					                            ?>
					                        </th>
					                        <th class="text-center hidden-xs"><?=formatDate("Y-m-d", $row["create_time"])?></th>
						                </tr>
						                <?
						                }
						                ?>
									</tbody>
								</table>
								<table>
									<div style="float: left;margin-right: 20px;">
										<select class="form-control" name="select_class" onChange="window.location='<?=$baseUrl?>?select_class=' + this.options[this.selectedIndex].value;">
											<option value="">请选择栏目</option>
											<?
											$sql = "select id, name from info_class where id like '" . CLASS_SPACE . "%' order by sortnum asc";
											$rst = $db->query($sql);
											while ($row = $db->fetch_array($rst))
											{
												$data[] = array("id" => $row["id"], "name" => $row["name"]);
											}
											$data = getNodeData($data, '', CLASS_LENGTH);
											echo optionSTree($data, $select_class);
											?>
										</select>
									</div>
									<div style="float: left;margin-right: 20px;">
			                            <select class="form-control" name="operation" onChange="optionCheck();">
			                                <option value="">请选择操作选项</option>
			                                <option value="delete">删除</option>
			                                <option value="move">转移</option>
			                                <option value="copy">复制</option>
			                            </select>
				                    </div>
				                    <div id="end_select" style="float:left;margin-right: 20px;display:none;">
			                            <select class="form-control" name="end_class">
											<?
											echo optionsTree($data);
			                                ?>
			                            </select>
			                        </div>
				                    <div style="float:left;line-height:34px;"><a class="label label-sm label-primary tooltips" href="javascript:if(operationCheck(document.listForm.ids)) {document.listForm.submit();}" data-placement="top" data-original-title="操作" style="font-size:100%;">操作</a></div>
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