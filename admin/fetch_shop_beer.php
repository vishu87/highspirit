<?php 
	session_start();
	require_once('../beer/config.php');
	$link = mysql_connect( DB_HOST, DB_USER , DB_PASSWORD );
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}

	$id = mysql_real_escape_string($_POST["id"]);
	
	mysql_query('SET character_set_results=utf8');
	$sql_shop = mysql_query("SELECT id,name from shops_beer where group_name = '$id' ");
	$count =0;
	echo '<option value="0">सभी</option>';
	while ($row_sh = mysql_fetch_array($sql_shop)) {
		echo '<option value="'.$row_sh["id"].'">'.$row_sh["name"].'</option>';
		$count++;
	}
?>