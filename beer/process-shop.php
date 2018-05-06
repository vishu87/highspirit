<?php
session_start();
	//error_reporting(E_ALL ^ E_NOTICE);
	error_reporting(0);
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

	mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $link);

	$name = mysql_real_escape_string($_POST["name"]);

	mysql_query("INSERT into shops_beer (name, district, group_name) values ('$name','$_POST[district]', '$_POST[group_name]') ");
	
	header("location: add-shop.php");