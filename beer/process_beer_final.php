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

$date_en = $_GET["date_en"];

$month_en = $_GET["month_en"];

$year_en = $_GET["year_en"];

$shop_id = $_GET["shop_id"];

$current_id = $_GET["current_id"];

$timedate = strtotime($date_en.'-'.$month_en.'-'.$year_en);

$total_sizes = 4;

for($i=1; $i<=$total_sizes;$i++){
	$size_id = $i;

	mysql_query("DELETE from sale_beer_final where shop_id='$shop_id' and timestamp_day = '$timedate' and size_id = '$size_id' ");

	mysql_query("INSERT into sale_beer_final (shop_id, timestamp_day,  size_id, old_stock, stock_in, stock_transfer, initial_stock, final_stock, sale, total_cost) values ('$shop_id','$timedate', '$size_id','".$_POST["total_old_stock_".$size_id]."', '".$_POST["total_stock_in_".$size_id]."', '".$_POST["total_stock_transfer_".$size_id]."', '".$_POST["total_initial_stock_".$size_id]."', '".$_POST["total_final_stock_".$size_id]."', '".$_POST["total_sale_".$size_id]."', '".$_POST["total_total_cost_".$size_id]."') ");
}

mysql_query("DELETE from sale_beer where shop_id='$shop_id' and timestamp_day = '$timedate' and type_id='0' ");

$_POST["total_total_sum"] = mysql_real_escape_string($_POST["total_total_sum"]);
$_POST["money_given"] = mysql_real_escape_string($_POST["money_given"]);
$_POST["money_name"] = mysql_real_escape_string($_POST["money_name"]);
$_POST["other_money"] = mysql_real_escape_string($_POST["other_money"]);
$_POST["remark"] = mysql_real_escape_string($_POST["remark"]);
$_POST["final_total"] = mysql_real_escape_string($_POST["final_total"]);

mysql_query("INSERT into sale_beer_sum_final (shop_id, timestamp_day, total_sum, money_given, money_name, other_money, remark, final_total ) values ('$shop_id','$timedate','".$_POST["total_total_sum"]."','".$_POST["money_given"]."','".$_POST["money_name"]."','".$_POST["other_money"]."','".$_POST["remark"]."','".$_POST["final_total"]."') ");

	$time_next = $timedate + 86400;
	$_SESSION["date"] = date("j", $time_next);
	$_SESSION["month"] = date("n", $time_next);
	$_SESSION["year"] = date("Y", $time_next);

echo 'success';

?>