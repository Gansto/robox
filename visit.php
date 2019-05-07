<?
require("init.php");

$menu   =   "visit";

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $vcode   =  htmlspecialchars(trim($_POST["vcode"]));

    if($vcode != $vcodeConst ){
        echo "<script>alert('密码错误！');history.back(-1);</script>";
    }else{
        setcookie("vcode", $vcode, time()+604800,'/');
        header("location: index.php");
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
<title>请输入密码访问测试路径</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<meta content="" name="description" />
<meta content="" name="author" />
<link rel="shortcut icon" href="favicon.ico" />
<script src="js/common.js?rightButton=<?=$config_rightButton?>"></script>
<style type="text/css">
*{margin:0;padding:0;font-family:microsoft yahei;}
body{font-size:14px;color:#5a5b5b;background:#f8f8f8;}
.pg_margins{width:460px;height:360px;position:absolute;top:0;left:0;bottom:0;right:0;margin:auto}
.pg_margins .logo{width:260px;height:75px;background:url(admin/images/logo.jpg) no-repeat;margin:0 auto 20px;}
.pg_margins dl{box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);}
.pg_margins dl dt{background:#0474c0;color:#fff;font-size:18px;font-weight:bold;padding:15px 30px;border-radius:3px 3px 0 0;margin:0}
.pg_margins dl dd{background:#fff;border-radius:0 0 3px 3px;}
.pg_margins dl dd p{padding:8px 0;}
.pg_margins dl dd form{margin:0;padding:30px}
.text{float:left;background:#f2f2f2;border:0;padding:10px 5%;width:55%;margin-right:5%;}
.btn{background:#0474c0;color:#fff;border:0;width:30%;padding:10px 0;cursor: pointer;}
.btn:hover { opacity: 0.8; }
.footer{text-align:center;padding:20px 0;line-height:22px;}
@media only screen and ( max-width: 600px ){
    .pg_margins { width: 90%; }
}
</style>
<script language="javascript">
    function check(form)
    {
        if (form.vcode.value == "")
        {
            alert("请输入测试路径访问密码");
            form.vcode.focus();
            return false;
        }
        return true;
    }
</script>
</head>
<body>
<div class="pg_margins">
    <h1 class="logo"></h1>
    <dl>
        <dt>请输入密码访问测试路径！</dt>
        <dd>
            <form method="post" onsubmit="return check(this);">
                <p>
                    <input name="vcode" type="password" class="text" value="" />
                    <input type="submit" class="btn" value="立即访问" />
                </p>
            </form>
        </dd>
    </dl>
    <div class="footer">如忘了测试路径密码，请咨询相关0551-62853960。<br />版权所有 <a href="http://www.ibw.cn" target="_blank">网新科技(www.ibw.cn)</a> <a href="http://www.miitbeian.gov.cn/" target="_blank">皖B1.B2-20070012-4</a></div>
</div>
</body>
</html>