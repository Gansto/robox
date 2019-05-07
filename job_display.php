<?
require("init.php");

$base_name	  = "人力资源";
$base_en_name = "Job";
$menu 		  = "job";

$id		=  $_GET['id'];

$sql = "select * from job where id='$id'";
$rst = $db->query($sql);
$record_arr=array();
$row = $db->fetch_array($rst);

$showForm    = $row["showForm"];
$job_name 	 = $row["name"];
$job_content = $row["content"];

//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$name			=	htmlspecialchars(trim($_POST['name']));
	$sex			=	htmlspecialchars(trim($_POST['sex']));
	$age			=	htmlspecialchars(trim($_POST['age']));
	$major			=	htmlspecialchars(trim($_POST['major']));
	$graduate_time	=	htmlspecialchars(trim($_POST['graduate_time']));
	$college		=	htmlspecialchars(trim($_POST['college']));
	$phone			=	htmlspecialchars(trim($_POST['phone']));
	$email			=	htmlspecialchars(trim($_POST['email']));
	$resumes		=	htmlspecialchars(trim($_POST['resumes']));
	$appraise		=	htmlspecialchars(trim($_POST['appraise']));

	$create_time	=	date("Y-m-d H:i:s");
	$sortnum 		= 	$db->getMax("job_apply", "sortnum") + 10;
	$aid			=	$db->getMax("job_apply", "id") + 1;

	if (empty($name) || empty($phone) || empty($resumes)) {
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

	if(isset($_POST["code"])){
		$code = $_POST["code"];
		if(strtolower($code) != strtolower($_SESSION["img_code"])){
			echo "<script>alert('验证码错误或未填写！');history.back(-1);</script>";
			exit;
		}
	}

	$sql = "insert into job_apply(id, name, job_id, sortnum, sex, age, major, graduate_time, college, phone, email, resumes, appraise, create_time, state) values($aid, '$name', $id, $sortnum, '$sex', '$age', '$major', '$graduate_time', '$college', '$phone', '$email', '$resumes', '$appraise', '$create_time', 0)";
	if($rst = $db->query($sql))
	{
		$db->close();
		echo "<script>alert('提交成功，我们稍后将处理您的申请！');self.location=document.referrer;</script>";
		exit;
		//$tip="提交成功,我们稍后将处理您的申请！";
	}
	else
	{
		$db->close();
		echo "<script>alert('提交失败，请稍后重试！');history.back(-1);</script>";
		exit;
		//$tip="提交失败，请稍后重试！";
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
<title><?=$job_name?> - <?=$base_name?> - <?=$config_name?></title>
<link rel="stylesheet" href="images/base.css" />
<link rel="stylesheet" href="images/inside.css" />
<link rel="stylesheet" href="images/adver.css" />
<script src="js/jquery-1.11.3.min.js"></script>
<script src="js/jquery.SuperSlide.2.1.2.js"></script>
<script src="js/adver.js"></script>
<script src="js/common.js?rightButton=<?=$config_rightButton?>&mobilejump=<?=$config_mobilejump?>"></script>
<script type="text/javascript">
	function check(form)
	{
		var phone = document.getElementById('phone').value;
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
		if (form.resumes.value == "")
		{
			alert("请输入您的个人履历！");
			form.resumes.focus();
			return false;
		}
	}
</script>
<? echo $config_javascriptHead; ?>
</head>
<body>
<?
require_once("begin.php");
?>
<div class="container">
	<div class="location">
		<p class="breadcrumbs"><a href="./" class="u-home">网站首页</a> &gt; <a href="job.php"><?=$base_name?></a> &gt; <a href="job_display.php?id=<?=$id?>"><?=$job_name?></a></p>
		<h3><?=$base_name?></h3>
	</div>
	<div class="wrap clearfix">
		<?
		require_once("left.php");
		?>
		<div class="main">
			<div class="hr">
				<dl class="hr-list">
					<dt class="title"><?=$job_name?></dt>
					<dd class="info"><?=$job_content?></dd>
				</dl>
			</div>
			<?
				if ($showForm == 1){
			?>
			<div class="form-panel">
				<h4 style="display:none;">在线填写应聘资料</h4>
				<div class="tips"><font color="#FF0000"><?=$tip?></font></div>
				<form name="form_job" method="post" onSubmit="return check(this);">
					<ul>
						<li class="field">
							<div class="input">
								<label>姓名：</label>
								<input name="name" type="text" size="20" maxlength="4" class="text" /><font color="#FF0000"> *</font>
							</div>
						</li>
						<li class="field">
							<div class="input">
								<label>性别：</label>
								<input type="radio" name="sex" value="男" checked />&nbsp;男&nbsp;<input type="radio" name="sex" value="女" />&nbsp;女&nbsp;
							</div>
						</li>
						<li class="field">
							<div class="input">
								<label>年龄：</label>
								<input name="age" type="text" size="5" maxlength="2" class="text" />
							</div>
						</li>
						<li class="field">
							<div class="input">
								<label>专业：</label>
								<input name="major" type="text" size="40" maxlength="50" class="text" />
							</div>
						</li>
						<li class="field">
							<div class="input">
								<label>毕业时间：</label>
								<input name="graduate_time" type="text" size="40" maxlength="50" class="text" />
							</div>
						</li>
						<li class="field">
							<div class="input">
								<label>毕业院校：</label>
								<input name="college" type="text" size="40" maxlength="50" class="text" />
							</div>
						</li>
						<li class="field">
							<div class="input">
								<label>电话：</label>
								<input id="phone" name="phone" type="text" size="20" maxlength="11" class="text" /><font color="#FF0000"> *</font>
							</div>
						</li>
						<li class="field">
							<div class="input">
								<label>邮箱：</label>
								<input name="email" type="text" size="40" maxlength="50" class="text" />
							</div>
						</li>
						<li class="field">
							<div class="input">
								<label>个人履历：</label>
								<textarea name="resumes" cols="60" rows="6" class="textarea"></textarea><font color="#FF0000"> *</font>
							</div>
						</li>
						<li class="field">
							<div class="input">
								<label>自我评价：</label>
								<textarea name="appraise" cols="60" rows="6" class="textarea"></textarea>
							</div>
						</li>
						<li class="field">
							<div class="input">
								<label for="code">验证码：</label>
								<input name="code" type="text" size="10" maxlength="4" value="" class="text" />
								<img title="点击刷新" style="cursor:pointer;margin-left:8px;" src="include/code.php" onclick="this.src='include/code.php?'+Math.random();">
							</div>
						</li>
						<li class="submit-field">
							<div class="input clearfix"><input type="submit" value="提交" class="btn-submit" /><input type="reset" value="重置" class="btn-reset" /></div>
						</li>
					</ul>
				</form>
			</div>
			<?
				}
			?>
		</div>
	</div>
</div>
<?
require_once("end.php");
?>
</body>
</html>