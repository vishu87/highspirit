﻿<html>
	<head>
		<title>SYNCING</title>
		<meta content="text/html; charset=UTF-8" http-equiv="content-type">
	</head>
	<body style="padding:50px;">
		डाटा ट्रान्सफर हो रहा है कृपया इंतज़ार करे............<br><br>
	</body>
</html>
<?php
	set_time_limit(5000);
	error_reporting(0);
	date_default_timezone_set('Asia/Calcutta');
	define('DB_HOST', 'host281.hostmonster.com');
    define('DB_USER', 'cherrya1');
    define('DB_PASSWORD', 'WEL#@m7899');
    define('DB_DATABASE', 'cherrya1_ritesh');

    $link = mysql_connect( DB_HOST, DB_USER , DB_PASSWORD );
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_DATABASE, $link);
	if(!$db) {
		die("Unable to select database");
	}

    $link_local = mysql_connect("localhost", "root" , "", true );
	if(!$link_local) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db_local = mysql_select_db("ritesh",$link_local);
	if(!$db_local) {
		die("Unable to select database");
	}

	$in_details_final = array("id","shop_id", "timestamp_day", "type_id" ,"old_stock","stock_in","stock_transfer_in","initial_stock","final_stock","stock_transfer_out","sale","rate","total_cost","sale_ws","rate_ws","total_ws");
	$array_final = array("id","shop_id","timestamp_day","sada_total","masala_total","ws_total","chikna","jurmana","other","input_total","normal_sale","ws_sale","final_sale","commission","total_commission","commission_round","money_given","money_name","final_total","remark","buttat","bhada");

	mysql_query('SET character_set_results=utf8');
	$query = mysql_query("SELECT id,name from shops", $link_local);
	while ($row = mysql_fetch_array($query)) {
		
		$q_ts = mysql_query("SELECT timestamp_day from sale where shop_id='$row[id]' order by timestamp_day desc ",$link_local);
		if(mysql_num_rows($q_ts) > 0){
			$row_ts = mysql_fetch_array($q_ts);
			$last_timestamp = $row_ts["timestamp_day"] - 90*86400;

			$query_sale = mysql_query("SELECT * from sale where shop_id='$row[id]' and timestamp_day <= $last_timestamp ",$link_local);
			while ($row_sale = mysql_fetch_array($query_sale)) {
				$query1 = "INSERT INTO sale(";
				$query2 = ")VALUES (";
				$query3 = ")";

				$i=1;
				foreach($in_details_final as $field ) {
					$name = $field;

					if($i==1) $query1 = $query1.$name;
					else $query1 = $query1.', '.$name;

					if($i==1) $query2 = $query2."'".$row_sale[$name]."'";
					else $query2 = $query2.", '".$row_sale[$name]."'";
					$i++;
				}

				if(mysql_query($query1.$query2.$query3, $link)) {
					mysql_query("DELETE from sale where id='$row_sale[id]' ",$link_local);
				}
			}
			//FINAL SALE
			$query_sale = mysql_query("SELECT * from sale_final where shop_id='$row[id]' and timestamp_day <= $last_timestamp ",$link_local);
			while ($row_sale = mysql_fetch_array($query_sale)) {
				$query1 = "INSERT INTO sale_final(";
				$query2 = ")VALUES (";
				$query3 = ")";

				$i=1;
				foreach($array_final as $field ) {
					$name = $field;

					if($i==1) $query1 = $query1.$name;
					else $query1 = $query1.', '.$name;

					if($i==1) $query2 = $query2."'".$row_sale[$name]."'";
					else $query2 = $query2.", '".$row_sale[$name]."'";
					$i++;
				}

				if(mysql_query($query1.$query2.$query3, $link)) {
					mysql_query("DELETE from sale_final where id='$row_sale[id]' ",$link_local);
				}
			}


		}
		echo $row["name"].': डाटा ट्रान्सफर हो चूका है। कृपया इंतज़ार करे..........<br>';
	}

	echo '*********************************************************************************<br>';
	echo 'डाटा ट्रान्सफर हो चूका है। ';
	echo '*********************************************************************************<br>';

?>