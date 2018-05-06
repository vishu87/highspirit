<?php 
	error_reporting(E_ALL ^ E_NOTICE);
	require_once('../config.php');
	$link = mysql_connect( DB_HOST, DB_USER , DB_PASSWORD );
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}

	if($_POST["date_ch"] && $_POST["month_ch"] && $_POST["year_ch"]) {
		$_SESSION["date"] = $_POST["date_ch"];
		$_SESSION["month"] = $_POST["month_ch"];
		$_SESSION["year"] = $_POST["year_ch"];
	}
	if(!isset($_SESSION["date"])) {
		$date_en = date("j", strtotime("now"));
	} else {
		$date_en = $_SESSION["date"];
	}

	if(!isset($_SESSION["month"])) {
		$month_en = date("n", strtotime("now"));
	} else {
		$month_en = $_SESSION["month"];
	}

	if(!isset($_SESSION["year"])) {
		$year_en = date("Y", strtotime("now"));
	} else {
		$year_en = $_SESSION["year"];
	}
	$timestring_now =  strtotime($month_en.'/'.$date_en.'/'.$year_en);
?>