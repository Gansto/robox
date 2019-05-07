<?php

error_reporting(0);
require("init.php");
//var_dump($_SERVER);
//die();
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
    header("HTTP/1.1 404 Not Found");
    exit();
}
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);
$page =mysql_escape_string(urldecode($_GET['page']));
$date = date('Y-m-d');
$ip         =   $_SERVER["REMOTE_ADDR"];
$statement = "UPDATE hit_counter SET counter=counter+1 WHERE date='$date' AND page='$page'  AND ip='$ip'";
$result = $db->query($statement);
if (!$db->affected_rows()) {
    $statement = "INSERT INTO hit_counter(`date`, `page`, `counter`, `ip`) VALUES ('$date', '$page', 1, '$ip')";
    $db->query($statement);
}