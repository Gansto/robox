<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, ORDER_ADVANCEDID) == false) {
    info("没有权限！");
}


$id = (int)$_GET["id"];
$page = (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;
if ($id < 1) {
    info("参数有误！");
}


$listUrl = "cart_list.php?page=$page";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $state = (int)$_POST["state"];

    $sql = "update `order` set  state=$state where id=$id";
    $rst = $db->query($sql);
    $db->close();
    if ($rst) {
        header("location: $listUrl");
        exit;
    } else {
        info("修改支付状态失败！");
    }
}

$sql = "select a.state as bb,a.sex,a.name,a.orderid,a.phone,a.total , a.userid,a.create_time, b.* from `order` a  left  join  member b on a.userid=b.id  where a.id=$id";
 
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst)) {
	$name			= $row["name"];
	$orderid		= $row["orderid"];
	$phone			= $row["phone"];
	$email			= $row["email"];
	$address		= $row["address"];
	$company		= $row["company"];
	$sex			= $row["sex"];
	$create_time	= $row["create_time"];
	$province		= $row["province"];
	$city			= $row["city"];
	$area			= $row["area"];
	$code			= $row["code"];	
	$sj				= $row["sj"];	
	$total			= $row["total"];		
	$state			= $row["bb"];

} else {
    $db->close();
    info("指定的记录不存在！");
}

?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title>网站管理系统</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Expires" content="-1000">
    <link href="images/admin.css" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="favicon.ico"/>
    <link href="themes/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="themes/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="themes/plugins/uniform/css/uniform.default.css" rel="stylesheet"/>
    <link href="themes/css/shy.css" rel="stylesheet"/>
    <link href="themes/css/shy-skin.css" rel="stylesheet"/>

    <script src="//cdn.bootcss.com/jquery/1.12.3/jquery.min.js"></script>
</head>
<body>
<? include "header.php" ?>
<div class="page-container clearfix">
<? include "menu.php" ?>
<div class="page-content-wrapper"> 
	<div class="page-content">
        <ul class="page-breadcrumb breadcrumb">
            <li><i class="fa fa-home"></i><a href="#">首页</a></li>
            <li><i class="fa fa-angle-right"></i><span class="active">高级管理</span></li>
            <li><i class="fa fa-angle-right"></i><span class="active">订单管理</span></li>
        </ul>
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-body table-responsive">
                    <form name="form1" action="" method="post">
                        <table width="100%" border="0" cellSpacing="1" cellPadding="0" align="center"
                               class="table table-striped table-bordered table-hover">
                            <tr>
                                <td colSpan="6">订单详细</td>
                            </tr>
         
                            <tr>
                                <td width="150">订单号</td>
                                <td width="219"><?= $orderid ?></td>
                                <td width="94">订单金额</td>
                                <td><?= $total ?>元</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>客户姓名</td>
                                <td><?= $name ?></td>
                                <td><span>联系电话</span></td>
                                <td width="157"><?= $phone ?></td>
                                <td width="80"><span>电子信箱</span></td>
                                <td><?= $email ?></td>
                            </tr>
                             
                             
                
                            <tr>
                                <td>详细列表</td>
                                <td colspan="5" style="padding:5px 0;">
                                
                                        <table width="100%" border="0" cellSpacing="1" cellPadding="0" align="center"   class="table table-striped table-bordered table-hover">
                                            <tr>
                                                <td align="center" >编号</td>
                                                <td align="center" >名称</td>
                                                <td align="center" >单价</td>
                                                <td align="center" >数量</td>
                                                 
                                            </tr>
											<?
                                            $sql = "select * from order_list where cart_id=$id ";
										 
                                            $rst = $db->query($sql);
											 $i =1;
                                            while ($row = $db->fetch_array($rst))
                                            {
                                            ?>
                                            <tr>
                                                <td width="11%" align="center"><?= $i ?></td>
                                                <td width="59%"><?= $row["product_name"] ?></td>
                                                <td width="16%" align="center"><?= $row["price"] ?>元</td>
                                                <td width="16%" align="center"><?= $row["qty"] ?></td>
                                                 
                                            </tr>
                                            <?
                                            $i = $i + 1;
                                        }
                                        ?>
                                        </table>					
                                </td>
                            </tr> 

                            <tr>
                                <td>是否支付</td>
                                <td colspan="5">
								 <label class="checkbox-inline">
                                    <input type="radio" name="state" value="0"<? if ($state == 0) echo " checked" ?>>
                                    未支付
									</label>
									 <label class="checkbox-inline">
                                    <input type="radio" name="state" value="1"<? if ($state == 1) echo " checked" ?>>
                                    已支付</label>
                                </td>
                            </tr>
                            <tr>
                                <td colSpan="6">
                                    <input class="hidden" type="submit" value=" 确 定 ">
                                    <input class="hidden" type="reset" value=" 重 填 ">
                                    <div class="form-actions col-md-offset-1">
                                        <button type="submit" class="btn blue" id="btn-submit">提交</button>
                                        <button type="button" class="btn default" id="btn-reset">重置</button>
                                    </div>
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

<? include "foot.php" ?>
 </html>
<? $db->close(); ?>
