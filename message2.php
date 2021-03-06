<?
require("init.php");

$menu		= "message";
$base_name	= "联系留言";

//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	$name		= htmlspecialchars(trim($_POST["name"]));
	$phone		= htmlspecialchars(trim($_POST["phone"]));
	$email		= htmlspecialchars(trim($_POST["email"]));
	$content	= htmlspecialchars(trim($_POST["content"]));
	$code		= htmlspecialchars(trim($_POST["code"]));

	//$ip       =   $_SERVER["REMOTE_ADDR"];
	$create_time=	date("Y-m-d H:i:s");
	$sortnum 	= 	$db->getMax("message", "sortnum") + 10;
	$id			=	$db->getMax("message", "id") + 1;

	if (empty($name) || empty($phone) || empty($content)) {
		$db->close();
		echo "<script>alert('请输入必填项！');history.back(-1);</script>";
		exit;
	}

	if(preg_match('/[A-Za-z]+/',$name)){
		$db->close();
		echo "<script>alert('姓名不能含有英文！');history.back(-1);</script>";
		exit;
	}

	if(preg_match('/\d/is',$name)){
		$db->close();
		echo "<script>alert('姓名不能含有数字！');history.back(-1);</script>";
		exit;
	}

	if ($code!="验证成功") {
		$db->close();
		echo "<script>alert('验证码错误或未填写！');history.back(-1);</script>";
		exit;
	}

	$sql = "insert into message(id, name, sortnum, phone, email, content, create_time, state) values($id, '$name', $sortnum, '$phone', '$email', '$content', '$create_time', 0)";
	$rst = $db->query($sql);
	$db->close();
	if ($rst){
		$db->close();
		echo "<script>alert('提交成功，我们稍后将处理您的留言！');self.location=document.referrer;</script>";
		exit;
	} else {
		echo "<script>alert('留言失败，请稍后再试！');history.back(-1);</script>";
		exit;
	}
}
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
<title><?=$base_name?> - <?=$config_name?></title>
<link rel="stylesheet" href="images/base.css" />
<link rel="stylesheet" href="images/inside.css" />
<link rel="stylesheet" href="images/adver.css" />
<script src="js/jquery-1.11.3.min.js"></script>
<script src="js/jquery.SuperSlide.2.1.2.js"></script>
<script src="js/common.js?rightButton=<?=$config_rightButton?>&mobilejump=<?=$config_mobilejump?>"></script>
<script type="text/javascript">
	function check(form)
	{
		var phone = document.getElementById('phone').value;
		var tncode = document.getElementById('tncode').innerText;

		if (form.name.value == "")
		{
			alert("请输入您的姓名！");
			form.name.focus();
			return false;
		}
		if(form.phone.value == "")
		{
			alert("请输入您的手机号码!");
			form.phone.focus();
			return false;
		}
		if(!(/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/.test(phone))){
	        alert("请输入正确的手机号码！");
	        form.phone.focus();
	        return false;
	    }
		if(form.content.value == "")
		{
			alert("请输入留言内容!");
			form.content.focus();
			return false;
		}
		if(tncode != "验证成功"){
			alert("请点击按钮进行验证！");
			return false;
		}
	}
</script>
<script src="tncode/tn_code.js?v=39"></script>
<link rel="stylesheet" href="tncode/style.css?v=21" />
<? echo $config_javascriptHead;?>
</head>
<body>
<?
require_once("begin.php");
?>
<div class="container">
	<div class="location">
        <div class="wrap clearfix">
        	<div class="breadcrumbs"><a href="./" class="u-home">网站首页</a> &gt; <a href="message.php"><?=$base_name?></a></div>
       		<h3><?=$base_name?></h3>
        </div>
    </div>
	<div class="wrap clearfix">
		<?
		require_once("left.php");
		?>
		<div class="main">
			<div class="message">
				<?
					$sql = "select count(*) as cnt from message where state>0 order by state desc, sortnum desc, create_time desc";
					$rst = $db->query($sql);
					if ($row = $db->fetch_array($rst)) {
						$recordCount = $row["cnt"];
					} else {
						$recordCount = 0;
					}

					$page		= (int)$_GET["page"];
					$page		= $page > 0 ? $page : 1;
					$pageSize	= 5;
					$pageCount	= ceil($recordCount / $pageSize);
					if ($page > $pageCount) $page = $pageCount;

					$sql = "select * from message where state>0 order by state desc, sortnum desc, create_time desc";
					$sql .= " limit " . ($page - 1) * $pageSize . ", " . $pageSize;

					$rst = $db->query($sql);
					while ($row = $db->fetch_array($rst)) {
				?>
				<dl class="message-list">
					<dt class="m-title"><span><?=$row["create_time"]?></span>留言者：<?=$row["name"]?></dt>
					<dd class="m-info">留言：[<?=$row["content"]?>]</dd>
					<?
						if($row["reply"] != ''){
					?>
						<dt class="r-title">管理员于 <?=$row["reply_time"]?> 回复：</dt>
						<dd class="r-info"><?=$row["reply"]?></dd>
					<?
						}
					?>
				</dl>
				<?
					}
				?>
			</div>
			<?
				if ($recordCount > 0){
			?>
			<div class="page"><span><?=page2($page, $pageCount, "message.php?")?></span>共 <?=$recordCount?> 条信息</div>
			<?
				}
			?>
			<div class="form-panel">
				<form name="msg" method="post" onsubmit="return check(this);">
					<ul>
						<li class="field">
							<div class="input">
								<label for="name">姓名：</label>
								<input name="name" type="text" size="40" maxlength="10" value="" class="text" /><i style="color:#FF0000;"> *</i>
							</div>
						</li>
						<li class="field">
							<div class="input">
								<label for="phone">电话：</label>
								<input id="phone" name="phone" type="text" size="40" maxlength="11" value="" class="text" /><i style="color:#FF0000;"> *</i>
							</div>
						</li>
						<li class="field">
							<div class="input">
								<label for="email">邮箱：</label>
								<input name="email" type="text" size="40" maxlength="30" value="" class="text" />
							</div>
						</li>
						<li class="field">
							<div class="input">
								<label for="content">留言：</label>
								<textarea name="content" cols="60" rows="6" class="textarea"><?=$content?></textarea><i style="color:#FF0000;"> *</i>
							</div>
						</li>
						<div class="tncode" id="tncode"></div>
						<input class="code" type="hidden" name="code" value="" />
						<li class="submit-field">
							<div class="input clearfix"><input type="submit" value="提交" class="btn-submit" /><input type="reset" value="重置" class="btn-reset" /></div>
						</li>
					</ul>
				</form>
			</div>
		</div>
	</div>
</div>
<?
require_once("end.php");
?>
</body>
</html>