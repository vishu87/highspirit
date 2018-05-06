<?php session_start();
require_once('config.php');
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
	die('Failed to connect to server: ' . mysql_error());
}
//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
	die("Unable to select database");
}

$shop_id = $_GET["shop_id"];
$dis = $_GET["dis"];
$grp = $_GET["grp"];
$timestamp = $_GET["timestamp"];

$current_id = $_GET["current_shop"];

$query = mysql_query("DELETE from sale_english where shop_id='$shop_id' and timestamp_day = '$timestamp'");
$query2 = mysql_query("DELETE from sale_english_final where shop_id='$shop_id' and timestamp_day = '$timestamp'");
$query2 = mysql_query("DELETE from sale_english_sum_final where shop_id='$shop_id' and timestamp_day = '$timestamp'");

header("Location: index.php?dis=".$dis."&grp=".$grp."&type=3&shp=".$current_id);

?>