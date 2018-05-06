<html>
	<head>
		<title>SYNCING</title>
		<meta content="text/html; charset=UTF-8" http-equiv="content-type">
	</head>
	<body style="padding:50px;">
		डुप्लीकेट पुराना डेटा डिलीट हो रहा है कृपया इंतज़ार करे............<br><br>
	</body>
</html>
<?php
	set_time_limit(5000);
	error_reporting(0);
	date_default_timezone_set('Asia/Calcutta');
	define('DB_HOST', 'host281.hostmonster.com');
    define('DB_USER', 'cherrya1');
    define('DB_PASSWORD', 'WEL#@m789789');
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

	mysql_query('SET character_set_results=utf8');
	if(isset($_GET["shop"])){
		$query = mysql_query("SELECT id,name from shops_beer where id = $_GET[shop] ", $link_local);
	} else {
		die();
	}
	while ($row = mysql_fetch_array($query)) {

			$query_sale = mysql_query("SELECT id from sale_beer where shop_id='$row[id]' order by timestamp_day asc ",$link_local);
			while ($row_sale = mysql_fetch_array($query_sale)) {
				$query = mysql_query("SELECT id from sale_beer where id = $row_sale[id] limit 1",$link);

				if(mysql_num_rows($query) == 1) {
					mysql_query("DELETE from sale_beer where id='$row_sale[id]' ",$link_local);
				}
			}

			//FINAL SALE
			$query_sale = mysql_query("SELECT * from sale_beer_final where shop_id='$row[id]' order by timestamp_day asc",$link_local);
			while ($row_sale = mysql_fetch_array($query_sale)) {
				$query = mysql_query("SELECT id from sale_beer_final where id = $row_sale[id] limit 1",$link);

				if(mysql_num_rows($query) == 1) {
					mysql_query("DELETE from sale_beer_final where id='$row_sale[id]' ",$link_local);
				}
			}

			//FINAL SALE SUM
			$query_sale = mysql_query("SELECT * from sale_beer_sum_final where shop_id='$row[id]' order by timestamp_day asc",$link_local);
			while ($row_sale = mysql_fetch_array($query_sale)) {
				$query = mysql_query("SELECT id from sale_beer_sum_final where id = $row_sale[id] limit 1",$link);

				if(mysql_num_rows($query) == 1) {
					mysql_query("DELETE from sale_beer_sum_final where id='$row_sale[id]' ",$link_local);
				}
			}


		echo $row["name"].': डुप्लीकेट पुराना डेटा डिलीट हो चूका है। कृपया इंतज़ार करे..........<br>';
	}

	echo '*********************************************************************************<br>';
	echo 'डुप्लीकेट पुराना डेटा डिलीट हो हो चूका है। ';
	echo '*********************************************************************************<br>';

?>