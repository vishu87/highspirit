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

$total_sizes =4;

foreach ($_POST["brand"] as $type_id) {
	$flag = false;

	for($i=1; $i<=$total_sizes;$i++){
		$size_id = $i;
		
		//check for valid entry of stock aamad and transfer
		if( ($_POST["old_stock_".$type_id.'_'.$size_id] == '' || $_POST["old_stock_".$type_id.'_'.$size_id] == 0) && ($_POST["stock_in_".$type_id.'_'.$size_id] == '' || $_POST["stock_in_".$type_id.'_'.$size_id] == 0) && ($_POST["stock_transfer_".$type_id.'_'.$size_id] == '' || $_POST["stock_transfer_".$type_id.'_'.$size_id] == 0)  ){
			continue;
		} else {
			$flag = true;
			mysql_query("DELETE from sale_beer where shop_id='$shop_id' and timestamp_day = '$timedate' and type_id='$type_id' and size_id = '$size_id' ");

			mysql_query("INSERT into sale_beer (shop_id, timestamp_day, type_id,  size_id, old_stock, stock_in, stock_transfer, initial_stock, final_stock, sale, rate, total_cost) values ('$shop_id','$timedate','$type_id','$size_id','".$_POST["old_stock_".$type_id.'_'.$size_id]."', '".$_POST["stock_in_".$type_id.'_'.$size_id]."', '".$_POST["stock_transfer_".$type_id.'_'.$size_id]."', '".$_POST["initial_stock_".$type_id.'_'.$size_id]."', '".$_POST["final_stock_".$type_id.'_'.$size_id]."', '".$_POST["sale_".$type_id.'_'.$size_id]."', '".$_POST["rate_".$type_id.'_'.$size_id]."', '".$_POST["total_cost_".$type_id.'_'.$size_id]."') ");
		}
	}

	if($flag){
		mysql_query("DELETE from sale_beer_sum_final where shop_id='$shop_id' and timestamp_day = '$timedate' and type_id='$type_id' ");
		mysql_query("DELETE from sale_beer_sum_final where shop_id='$shop_id' and timestamp_day = '$timedate' and type_id='0' ");

		mysql_query("INSERT into sale_beer_sum_final (shop_id, timestamp_day, type_id, total_sum ) values ('$shop_id','$timedate','$type_id','".$_POST["total_sum_".$type_id]."') ");
	}
	
}

echo 'success';

?>