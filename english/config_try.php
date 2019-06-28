<html>
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

	$sale_english_details = array("id","shop_id","timestamp_day","type_id","size_id","old_stock","stock_in","stock_transfer","initial_stock","final_stock","sale","rate","total_cost");
	$sale_english_final_details = array("id","shop_id","timestamp_day","size_id","old_stock","stock_in","stock_transfer","initial_stock","final_stock","sale","rate","total_cost");
	$sale_english_sum_final_details = array("id","shop_id","timestamp_day","type_id","total_sum","money_given","money_name","other_money","remark","final_total");

	mysql_query('SET character_set_results=utf8');
	if(isset($_GET["shop"])){
		$query = mysql_query("SELECT id,name from shops_english where id = $_GET[shop] ", $link_local);
	} else {
		$query = mysql_query("SELECT id,name from shops_english", $link_local);
	}

	while ($row = mysql_fetch_array($query)) {

		$count = 0;
		
		$q_ts = mysql_query("SELECT timestamp_day from sale_english where shop_id='$row[id]' order by timestamp_day desc limit 1 ",$link_local);
		if(mysql_num_rows($q_ts) > 0){
			$today_ts = strtotime("now");
			$row_ts = mysql_fetch_array($q_ts);
			
			if(($today_ts - $row_ts["timestamp_day"]) < 90*86400){
				$last_timestamp = $row_ts["timestamp_day"] - 90*86400;
			} else $last_timestamp = $row_ts["timestamp_day"];

			

			$query_sale = mysql_query("SELECT * from sale_english where shop_id='$row[id]' and timestamp_day <= $last_timestamp ",$link_local);
			while ($row_sale = mysql_fetch_array($query_sale)) {
				$query1 = "INSERT INTO sale_english (";
				$query2 = ")VALUES (";
				$query3 = ")";

				$i=1;
				foreach($sale_english_details as $field ) {
					$name = $field;

					if($i==1) $query1 = $query1.$name;
					else $query1 = $query1.', '.$name;

					if($i==1) $query2 = $query2."'".$row_sale[$name]."'";
					else $query2 = $query2.", '".$row_sale[$name]."'";
					$i++;
				}

				if(mysql_query($query1.$query2.$query3, $link)) {
					mysql_query("DELETE from sale_english where id='$row_sale[id]' ",$link_local);
					$count++;
				}
			}

			//FINAL SALE
			$query_sale = mysql_query("SELECT * from sale_english_final where shop_id='$row[id]' and timestamp_day <= $last_timestamp ",$link_local);
			while ($row_sale = mysql_fetch_array($query_sale)) {
				$query1 = "INSERT INTO sale_english_final (";
				$query2 = ")VALUES (";
				$query3 = ")";

				$i=1;
				foreach($sale_english_final_details as $field ) {
					$name = $field;

					if($i==1) $query1 = $query1.$name;
					else $query1 = $query1.', '.$name;

					if($i==1) $query2 = $query2."'".$row_sale[$name]."'";
					else $query2 = $query2.", '".$row_sale[$name]."'";
					$i++;
				}

				if(mysql_query($query1.$query2.$query3, $link)) {
					mysql_query("DELETE from sale_english_final where id='$row_sale[id]' ",$link_local);
					$count++;
				}
			}

			//FINAL SALE SUM
			$query_sale = mysql_query("SELECT * from sale_english_sum_final where shop_id='$row[id]' and timestamp_day <= $last_timestamp ",$link_local);
			while ($row_sale = mysql_fetch_array($query_sale)) {
				$query1 = "INSERT INTO sale_english_sum_final (";
				$query2 = ")VALUES (";
				$query3 = ")";

				$i=1;
				foreach($sale_english_sum_final_details as $field ) {
					$name = $field;

					if($i==1) $query1 = $query1.$name;
					else $query1 = $query1.', '.$name;

					if($i==1) $query2 = $query2."'".$row_sale[$name]."'";
					else $query2 = $query2.", '".$row_sale[$name]."'";
					$i++;
				}

				if(mysql_query($query1.$query2.$query3, $link)) {
					mysql_query("DELETE from sale_english_sum_final where id='$row_sale[id]' ",$link_local);
					$count++;
				}
			}


		}

		echo 'ID - '.$row["id"].' '.$row["name"].'-'.$count.': डाटा ट्रान्सफर हो चूका है। कृपया इंतज़ार करे..........<br>';
	}

	echo '*********************************************************************************<br>';
	echo 'डाटा ट्रान्सफर हो चूका है। ';
	echo '*********************************************************************************<br>';

?>