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

/**************************************** Commission Adding **********************************************/
if($_GET["type"] == 1) {
	//$table = 'work_ex';
	$date = mysql_real_escape_string($_POST["date_en"]);
	$month = mysql_real_escape_string($_POST["month_en"]);
	$year = mysql_real_escape_string($_POST["year_en"]);
	$dis = mysql_real_escape_string($_GET["dis"]);
	$grp = mysql_real_escape_string($_GET["grp"]);
	$time_now =  strtotime($month.'/'.$date.'/'.$year);
	$query = mysql_query("SELECT id from shops where district='$dis' AND group_name='$grp' ");

	while($row = mysql_fetch_array($query)) {
		if($_POST["com_".$row["id"]] != '') {
			$val = $_POST["com_".$row["id"]];
			mysql_query("INSERT INTO commission ( shop_id, district, group_name, commission, date, month, year, timestamp) values ('$row[id]','$dis','$grp', '$val','$date', '$month', '$year', '$time_now')");
		}
	}


	//if(mysql_query($query1.$query2.$query3)) header("Location: ../".$folder.".php?cat=3&success=1");
	//else header("Location: ../".$folder.".php?cat=3&success=0");
}

if($_GET["type"] == 2) {
	//$table = 'work_ex';
	$dis = mysql_real_escape_string($_GET["dis"]);
	$grp = mysql_real_escape_string($_GET["grp"]);
	$date = mysql_real_escape_string($_POST["date_en"]);
	$month = mysql_real_escape_string($_POST["month_en"]);
	$year = mysql_real_escape_string($_POST["year_en"]);
	$time_now =  strtotime($month.'/'.$date.'/'.$year);
	$shop_id = mysql_real_escape_string($_POST["shop_id"]);
	//$current_id = mysql_real_escape_string($_POST["current_id"]) + 1;
	$current_id = mysql_real_escape_string($_POST["current_id"]);
	$array_type =array();
	$query_type = mysql_query("SELECT id from types");
	while($row_type = mysql_fetch_array($query_type)){
		array_push($array_type, $row_type["id"]);
	}

	$in_details = array("old_stock","stock_in","stock_transfer_in","initial_stock","final_stock","stock_transfer_out","sale","rate","total_cost","sale_ws","rate_ws","total_ws");

	$in_details_final = array("shop_id", "timestamp_day", "type_id" ,"old_stock","stock_in","stock_transfer_in","initial_stock","final_stock","stock_transfer_out","sale","rate","total_cost","sale_ws","rate_ws","total_ws");
	foreach ($array_type as $type) {
		$flag =0;
		$update = array();
		$update["type_id"] = $type;
		$update["shop_id"] = $shop_id;
		
		foreach ($in_details as $detail) {
			$update[$detail] = $_POST[$detail.'_'.$type];
			if( $update[$detail] != 0 && $update[$detail] != '' ){
				if($detail != 'rate') $flag =1;
			}
		}

		$flag_check = 0;
		$query_check = mysql_query("SELECT id from sale where shop_id='$shop_id' and type_id='$type' and timestamp_day='$time_now'  ");
		if(mysql_num_rows($query_check) > 0){
			$flag_check =1;
			$row_check = mysql_fetch_array($query_check);
			die('Duplicate Entry');
		}

		if($flag == 1){
			if($flag_check == 1){
				mysql_query("DELETE from sale where id='$row_check[id]' ");
			}

			$update["timestamp_day"] = $time_now;
			$query1 = "INSERT INTO sale(";
			$query2 = ")VALUES (";
			$query3 = ")";

			$i=1;
			foreach($in_details_final as $field ) {
				$name = $field;

				if($i==1) $query1 = $query1.$name;
				else $query1 = $query1.', '.$name;

				if($i==1) $query2 = $query2."'".$update[$name]."'";
				else $query2 = $query2.", '".$update[$name]."'";
				$i++;
			}
			mysql_query($query1.$query2.$query3);
		
		} else {
			if($flag_check == 1){
				mysql_query("DELETE from sale where id='$row_check[id]' ");
			}
		}
	}


	$array_fin = array("sada_total","masala_total","ws_total","chikna","jurmana","other","input_total","normal_sale","ws_sale","final_sale","commission","total_commission","commission_round","money_given","money_name","final_total","remark","buttat","bhada");
	
	$array_final = array("shop_id","timestamp_day","sada_total","masala_total","ws_total","chikna","jurmana","other","input_total","normal_sale","ws_sale","final_sale","commission","total_commission","commission_round","money_given","money_name","final_total","remark","buttat","bhada");

	$final = array();
	foreach ($array_fin as $fin) {
			$final[$fin] = $_POST[$fin];
		}
	$final["shop_id"] = $shop_id;
	$final["timestamp_day"] = $time_now;

	$query_check = mysql_query("SELECT id from sale_final where shop_id='$shop_id' and timestamp_day='$time_now' ");
	if(mysql_num_rows($query_check) > 0 ){
		$row_check = mysql_fetch_array($query_check);
		mysql_query("DELETE from sale_final where id='$row_check[id]' ");
	}

	$query1 = "INSERT INTO sale_final (";
	$query2 = ")VALUES (";
	$query3 = ")";

	$i=1;
	foreach($array_final as $field ) {
		$name = $field;

		if($i==1) $query1 = $query1.$name;
		else $query1 = $query1.', '.$name;

		if($i==1) $query2 = $query2."'".$final[$name]."'";
		else $query2 = $query2.", '".$final[$name]."'";
		$i++;
	}
	mysql_query($query1.$query2.$query3);
	
	// below only for date switch pattern
	$time_next = $time_now + 86400;
	$_SESSION["date"] = date("j", $time_next);
	$_SESSION["month"] = date("n", $time_next);
	$_SESSION["year"] = date("Y", $time_next);
	//$current_id = $current_id + 1;

	header("Location: index.php?dis=".$dis."&grp=".$grp."&type=3&shp=".$current_id);
}

if($_GET["type"] == 3) {
	$dis = mysql_real_escape_string($_GET["dis"]);
	$grp = mysql_real_escape_string($_GET["grp"]);
	$shop_id = mysql_real_escape_string($_GET["shop_id"]);
	$timestamp = mysql_real_escape_string($_GET["timestamp"]);
	$current_shop = mysql_real_escape_string($_GET["current_shop"]);
	
	$query = mysql_query("DELETE from sale where shop_id='$shop_id' and timestamp_day >= '$timestamp' ");
	$query2 = mysql_query("DELETE from sale_final where shop_id='$shop_id' and timestamp_day >= '$timestamp' ");

	header("Location: index.php?dis=".$dis."&grp=".$grp."&type=3&shp=".$current_id);
}

?>