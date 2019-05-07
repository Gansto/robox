<?
session_start();

unset($_SESSION["ADMIN_ID"]);
unset($_SESSION["ADMIN_NAME"]);
unset($_SESSION["ADMIN_GRADE"]);
unset($_SESSION["ADMIN_POPEDOM"]);
unset($_SESSION["ADMIN_ADVANCED"]);

header("location: login.php");
?>