<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, BATCH_UPLOAD_ADVANCEDID) == false)
{
    info("没有权限！");
}

$baseUrl = "manage_batch_form.php";

$title            = trim($_GET["title"]);
$upload_sort      = (int)trim($_GET["upload_sort"]); // 上传类别 0 缩略图，1 内容图，2 缩略图与内容图
$small_img_style  = (int)trim($_GET["small_img_style"]);
$small_img_width  = (int)trim($_GET["small_img_width"]);
$small_img_height = (int)trim($_GET["small_img_height"]);

$selectClass      = trim($_GET["select_class"]);

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

$advanced_class = $db->getTableFieldValue('advanced', 'advanced_class', 'where id=\'' . BATCH_UPLOAD_ADVANCEDID . '\' limit 1');
$advancedName = $db->getTableFieldValue('advanced', 'name', 'where id=\'' . BATCH_UPLOAD_ADVANCEDID . '\' limit 1');
$baseName = $db->getTableFieldValue('advanced_class', 'name', 'where id=\'' . $advanced_class . '\' limit 1');

if ( empty($selectClass) || (strlen($selectClass) % CLASS_LENGTH != 0 && !checkClassID($selectClass, strlen($selectClass) / CLASS_LENGTH)) )
{
    info("选择了错误的分类！");
}

if((int)$db->getTableFieldValue('info_class', 'has_sub', 'where id=\'' . $selectClass . '\' limit 1') > 0)
{
    info("请选择下级分类！");
}

if($upload_sort != 0 && $upload_sort != 1 && $upload_sort != 2)
{
    info("请选择上传类别！");
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
<link href="images/upload.css" rel="stylesheet" />
<script type="text/javascript" src="images/jquery.js"></script>
<link rel="stylesheet" href="webUploader/css/webuploader.css" />
<link rel="stylesheet" href="webUploader/css/style.css" />
<script src="webUploader/js/webuploader.min.js"></script>
<script src="webUploader/js/upload.js"></script>
<script type="text/javascript">
    //设置需要传递的参数
    var upload_data_arr = new Array("<?=$selectClass?>", "<?=$title?>", "<?=$upload_sort?>", "<?=$small_img_style?>", "<?=$small_img_width?>", "<?=$small_img_height?>");
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
                <li><i class="fa fa-angle-right"></i>系统管理</li>
                <li><i class="fa fa-angle-right"></i><span class="active">图片批量上传</span></li>
            </ul>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light">
                        <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
                            <tr height="30">
                                <td>
                                    <a href="<?=$baseUrl?>">[返回设置]</a>&nbsp;
                                </td>
                            </tr>
                        </table>
                        <form method="post">
                            <table width="100%" border="0" cellSpacing="1" cellPadding="0" align="center" class="editTable2">
                                <tr class="editTr">
                                    <td class="editLeftTd">信息分类</td>
                                    <td class="editRightTd">
                                        <?
                                        $classCnt = strlen($selectClass) / CLASS_LENGTH;
                                        for($i = 1; $i <= $classCnt; $i++){
                                           if($db->getTableFieldValue('info_class', 'name', 'where id=\'' . substr($selectClass, 0, 3*$i) . '\' limit 1') == "")
                                            {
                                                info("选择了错误的分类！");
                                            }
                                            else
                                            {
                                                echo $db->getTableFieldValue('info_class', 'name', 'where id=\'' . substr($selectClass, 0, 3*$i) . '\' limit 1'). ($i != $classCnt ? " -&gt; " : "" );
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr class="editTr">
                                    <td class="editLeftTd">信息标题</td>
                                    <td class="editRightTd">
                                        <?=$title == "" ? "无标题" : $title ?>
                                    </td>
                                </tr>
                                <tr class="editTr">
                                    <td class="editLeftTd">上传类别</td>
                                    <td class="editRightTd">
                                        <?
                                        switch ($upload_sort)
                                        {
                                            case 0:
                                                echo "缩略图";
                                                break;
                                            case 1:
                                                echo "内容图";
                                                break;
                                            case 2:
                                                echo "缩略图与内容图";
                                                break;
                                            default:
                                                info("错误！");
                                                break;
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr class="editTr">
                                    <td class="editLeftTd">缩略图</td>
                                    <td class="editRightTd">
                                        <?
                                        switch ($small_img_style)
                                        {
                                            case 0:
                                                echo "固定宽和高，缩略图尺寸：宽度".$small_img_width."*".$small_img_height;
                                                break;
                                            case 1:
                                                echo "固定宽度，缩略图尺寸：宽度".$small_img_width."，高度自适应";
                                                break;
                                            case 2:
                                                echo "固定高度，缩略图尺寸：高度".$small_img_height."，宽度自适应";
                                                break;
                                            case 3:
                                                echo "原始宽高";
                                                break;
                                            default:
                                                info("缩略图设置错误！");
                                                break;
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr class="editTr">
                                    <td class="editLeftTd">选择图片文件</td>
                                    <td class="editRightTd" style="padding:0;">
                                        <div id="wrapper">
                                            <div id="container">
                                                <div id="uploader">
                                                    <div class="queueList">
                                                        <div id="dndArea" class="placeholder">
                                                            <div id="filePicker"></div>
                                                            <p class="tips">单次最多可选200张</p>
                                                        </div>
                                                    </div>
                                                    <div class="statusBar" style="display:none;">
                                                        <div class="progress">
                                                            <span class="text">0%</span>
                                                            <span class="percentage"></span>
                                                        </div><div class="info"></div>
                                                        <div class="btns">
                                                            <div id="filePicker2"></div><div class="uploadBtn">开始上传</div><div class="delAll">清空</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <script type="text/javascript">
                                            window.onload = function(){
                                                if (window.applicationCache){
                                                    var txt = $("#dndArea .tips").text();
                                                    $("#dndArea .tips").text("或将照片拖到这里，"+txt)
                                                };
                                            }
                                        </script>
                                    </td>
                                </tr>
                            </table>
                        </form>
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