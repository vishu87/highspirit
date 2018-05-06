<?php 
	session_start();
	require_once('config.php');
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
	$dis_names=array("","अकबरपुर","जलालपुर","भीटी","टाण्डा","बसखारी","जहांगीरगंज","फैजाबाद","सुल्तानपुर");

	$groups = mysql_query("SELECT DISTINCT group_name from shops where district = '$id' ");
	$count =1;
	echo '<option value="0">सभी</option>';
	if(mysql_num_rows($groups) > 0) {
		while($row = mysql_fetch_array($groups)) {
			echo '<option value="'.$row["group_name"].'">'.$dis_names[$row["group_name"]].'</option>';
			$count++;
		}
	}
?>