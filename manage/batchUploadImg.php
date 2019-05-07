<?php
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");

/**
 * file_batch_upload.php
 * copyright, all rights reserved, source code, can be free to use a variety of purposes
 */

// Make sure file is not cached (as it happens for example on iOS devices)
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


// Support CORS
// header("Access-Control-Allow-Origin: *");
// other CORS headers if any...
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit; // finish preflight CORS requests here
}

if ( !empty($_REQUEST[ 'debug' ]) ) {
    $random = rand(0, intval($_REQUEST[ 'debug' ]) );
    if ( $random === 0 ) {
        header("HTTP/1.0 500 Internal Server Error");
        exit;
    }
}

// header("HTTP/1.0 500 Internal Server Error");
// exit;

// 5 minutes execution time
@set_time_limit(5 * 60);

// Uncomment this one to fake upload time
// usleep(5000);

// Settings
// $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
$uploadDir = UPLOAD_PATH_FOR_ADMIN;
$targetDir = $uploadDir.'/upload_tmp';

$cleanupTargetDir = true; // Remove old files
$maxFileAge = 5 * 3600; // Temp file age in seconds

$selectClass = trim($_POST["selectClass"]);
$title       = trim($_POST["title"]);
$upload_sort = (int)trim($_POST["upload_sort"]);
$small_img_style  = (int)trim($_POST["small_img_style"]);
$small_img_width  = (int)trim($_POST["small_img_width"]);
$small_img_height = (int)trim($_POST["small_img_height"]);

if(empty($selectClass))
{
    info("请选择栏目分类！");
}

if($upload_sort != 0 && $upload_sort != 1 && $upload_sort != 2)
{
    info("请选择上传类别！");
}

// Create uploadDir
if (!is_dir($uploadDir))
{
    if (!mkdir($uploadDir))
    {
        exit('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "无法建立保存图片的目录！"}, "id" : "id"}');
    }
}

// Create targetDir
if (!is_dir($targetDir))
{
    if (!mkdir($targetDir))
    {
        exit('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "无法建立保存图片的目录！"}, "id" : "id"}');
    }
}

$ym = date("Y-m");
$uploadDir .= $ym;

if (!is_dir($uploadDir))
{
    if (!mkdir($uploadDir))
    {
        exit('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "无法建立保存图片的目录！"}, "id" : "id"}');
    }
}

// Get a file name
if (isset($_REQUEST["name"]))
{
    $ext = strToLower(getFileExt($_REQUEST["name"]));
    $fileName      = getTmpName() . "." . $ext;
}
elseif (!empty($_FILES))
{
    $ext = strToLower(getFileExt($_FILES["file"]["name"]));
    $fileName      = getTmpName() . "." . $ext;
}
else
{
    $fileName = uniqid("file_");
}

$fileName = iconv('UTF-8', 'GB2312', $fileName);//转编码

$filePath = $targetDir . "/" . $fileName;
$uploadPath = $uploadDir . "/" . $fileName;

// Chunking might be enabled
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;

// Remove old temp files
if ($cleanupTargetDir) {
    if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
        exit('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "打开文件存储临时目录失败！"}, "id" : "id"}');
    }

    while (($file = readdir($dir)) !== false) {
        $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

        // If temp file is current file proceed to the next
        if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
            continue;
        }

        // Remove temp file if it is older than the max age and is not the current file
        if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
            @unlink($tmpfilePath);
        }
    }
    closedir($dir);
}

// Open temp file
if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
    exit('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
}

if (!empty($_FILES)) {
    if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
        exit('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
    }

    // Read binary input stream and append it to temp file
    if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
        exit('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
    }
} else {
    if (!$in = @fopen("php://input", "rb")) {
        exit('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
    }
}

while ($buff = fread($in, 4096)) {
    fwrite($out, $buff);
}

@fclose($out);
@fclose($in);

rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");

$index = 0;
$done = true;
for( $index = 0; $index < $chunks; $index++ ) {
    if ( !file_exists("{$filePath}_{$index}.part") ) {
        $done = false;
        break;
    }
}

if ( $done ) {
    if (!$out = @fopen($uploadPath, "wb")) {
        exit('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
    }
    if ( flock($out, LOCK_EX) ) {
        for( $index = 0; $index < $chunks; $index++ ) {
            if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                break;
            }

            while ($buff = fread($in, 4096)) {
                fwrite($out, $buff);
            }

            @fclose($in);
            @unlink("{$filePath}_{$index}.part");
        }

        flock($out, LOCK_UN);
    }
    @fclose($out);
}

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

// 将图片信息插入数据库
if($title == ""){
    $title_arr = explode('.', $_REQUEST["name"]);
    $title     = $title_arr[0];
}

$aid         = (int)($db->getMax("info", "id", "") + 1);
$sortnum     = (int)($db->getMax("info", "sortnum", "class_id like '$selectClass'") + 10);
$state       = 1;
$hot         = 0;
$now         = date("Y-m-d H:i:s");
$create_time = formatDate("Y-m-d H:i:s", $now);

$supportGd   = ture;
if (!extension_loaded('gd'))  //是否开启gd库
{
    $supportGd   = false;
}

$sql_image_path  = $ym . "/"; //数据库缩略图路径
$content_image_path = UPLOAD_PATH.$sql_image_path;  //编辑器图片路径

if($upload_sort == 0) //上传缩略图
{
    if($supportGd && $small_img_style == 0) // 固定宽度 高度
    {
        $small_img_name = getTmpName() . "." . strToLower(getFileExt($fileName)); //生成图名称
        $small_img_path = $uploadDir . "/" . $small_img_name; //生成图路径

        makeSmallImage($uploadPath, $small_img_path, $small_width = $small_img_width, $small_height = $small_img_height, $small_img_style);

        $pic = $sql_image_path.$small_img_name;

    }
    else if($supportGd && $small_img_style == 1) // 固定宽度
    {
        $small_img_name = getTmpName() . "." . strToLower(getFileExt($fileName)); //生成图名称
        $small_img_path = $uploadDir . "/" . $small_img_name; //生成图路径

        makeSmallImage($uploadPath, $small_img_path, $small_width = $small_img_width, $small_height = $small_img_height, $small_img_style);

        $pic = $sql_image_path.$small_img_name;
    }
    else if($supportGd && $small_img_style == 2) // 固定高度
    {
        $small_img_name = getTmpName() . "." . strToLower(getFileExt($fileName)); //生成图名称
        $small_img_path = $uploadDir . "/" . $small_img_name; //生成图路径

        makeSmallImage($uploadPath, $small_img_path, $small_width = $small_img_width, $small_height = $small_img_height, $small_img_style);

        $pic = $sql_image_path.$small_img_name;
    }
    else if($supportGd && $small_img_style == 3) // 原始宽高
    {
        $small_img_name = getTmpName() . "." . strToLower(getFileExt($fileName)); //生成图名称
        $small_img_path = $uploadDir . "/" . $small_img_name; //生成图路径

        makeSmallImage($uploadPath, $small_img_path, $small_width = $small_img_width, $small_height = $small_img_height, $small_img_style);

        $pic = $sql_image_path.$small_img_name;
    }

    @unlink($uploadPath);
    $content = ""; //上传缩略图 内容为空
}
else if($upload_sort == 1) //上传内容图
{
    if($supportGd && $small_img_style == 0) // 固定宽度 高度
    {
        $small_img_name = getTmpName() . "." . strToLower(getFileExt($fileName)); //生成图名称
        $small_img_path = $uploadDir . "/" . $small_img_name; //生成图路径
        makeSmallImage($uploadPath, $small_img_path, $small_width = $small_img_width, $small_height = $small_img_height, $small_img_style);

        $content = '<p style="text-align:center;"><img src="'. $small_img_path .'" alt="" /><p>';
        $files   =  $small_img_path;
    }
    else if($supportGd && $small_img_style == 1) // 固定宽度
    {
        $small_img_name = getTmpName() . "." . strToLower(getFileExt($fileName)); //生成图名称
        $small_img_path = $uploadDir . "/" . $small_img_name; //生成图路径

        makeSmallImage($uploadPath, $small_img_path, $small_width = $small_img_width, $small_height = $small_img_height, $small_img_style);

        $content = '<p style="text-align:center;"><img src="'. $small_img_path .'" alt="" /><p>';
        $files   =  $small_img_path;
    }
    else if($supportGd && $small_img_style == 2) // 固定高度
    {
        $small_img_name = getTmpName() . "." . strToLower(getFileExt($fileName)); //生成图名称
        $small_img_path = $uploadDir . "/" . $small_img_name; //生成图路径

        makeSmallImage($uploadPath, $small_img_path, $small_width = $small_img_width, $small_height = $small_img_height, $small_img_style);

        $content = '<p style="text-align:center;"><img src="'. $small_img_path .'" alt="" /><p>';
        $files   =  $small_img_path; // 此处重要 勿删！！！
    }
    else if($supportGd && $small_img_style == 3) // 原始宽高
    {
        $small_img_name = getTmpName() . "." . strToLower(getFileExt($fileName)); //生成图名称
        $small_img_path = $uploadDir . "/" . $small_img_name; //生成图路径

        makeSmallImage($uploadPath, $small_img_path, $small_width = $small_img_width, $small_height = $small_img_height, $small_img_style);

        $content = '<p style="text-align:center;"><img src="'. $small_img_path .'" alt="" /><p>';
        $files   =  $small_img_path; // 此处重要 勿删！！！
    }

    @unlink($uploadPath);
    $pic = ""; //上传内容图 缩略图为空
}
else if($upload_sort == 2) //上传缩略图与内容图
{
    if($supportGd && $small_img_style == 0) // 缩略图固定宽度 高度，内容图原始宽高
    {
        $small_img_name = getTmpName() . "." . strToLower(getFileExt($fileName)); //生成图名称
        $small_img_path = $uploadDir . "/" . $small_img_name; //生成图路径

        makeSmallImage($uploadPath, $small_img_path, $small_width = $small_img_width, $small_height = $small_img_height, $small_img_style);

        $pic = $sql_image_path.$small_img_name;
    }
    else if($supportGd && $small_img_style == 1) // 缩略图固定宽度，内容图原始宽高
    {
        $small_img_name = getTmpName() . "." . strToLower(getFileExt($fileName)); //生成图名称
        $small_img_path = $uploadDir . "/" . $small_img_name; //生成图路径

        makeSmallImage($uploadPath, $small_img_path, $small_width = $small_img_width, $small_height = $small_img_height, $small_img_style);

        $pic = $sql_image_path.$small_img_name;
    }
    else if($supportGd && $small_img_style == 2) // 缩略图固定高度，内容图原始宽高
    {
        $small_img_name = getTmpName() . "." . strToLower(getFileExt($fileName)); //生成图名称
        $small_img_path = $uploadDir . "/" . $small_img_name; //生成图路径

        makeSmallImage($uploadPath, $small_img_path, $small_width = $small_img_width, $small_height = $small_img_height, $small_img_style);

        $pic = $sql_image_path.$small_img_name;
    }
    else if($supportGd && $small_img_style == 3) // 缩略图原始宽高，内容图原始宽高
    {
        $small_img_name = getTmpName() . "." . strToLower(getFileExt($fileName)); //生成图名称
        $small_img_path = $uploadDir . "/" . $small_img_name; //生成图路径

        makeSmallImage($uploadPath, $small_img_path, $small_width = $small_img_width, $small_height = $small_img_height, $small_img_style);

        $pic = $sql_image_path.$small_img_name;
    }

    $content = '<p style="text-align:center;"><img src="'. $content_image_path.$fileName .'" alt="" /><p>';
    $files   =  $content_image_path.$fileName; // 此处重要 勿删！！！
}

$sql = "insert into info(id, sortnum, title, admin_id, class_id, content, pic, files, create_time, modify_time, state, hot) values($aid, $sortnum, '$title', 1, '$selectClass', '$content', '$pic', '$files', '$now', '$now', $state, $hot)";
$rst = $db->query($sql);
$db->close();
if (!$rst)
{
   @unlink($uploadPath); // 信息添加失败清除上传文件
   @unlink($small_img_path);
   exit('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "信息添加到数据库失败！"}, "id" : "id"}');
};

// Return Success JSON-RPC response
//exit('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');