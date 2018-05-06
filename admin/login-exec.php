<?php
	//Start session
	session_start();
	require_once('../config.php');
	$errmsg_arr = array();
	$errflag = false;
	
	//Connect to mysql server
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}
	
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	
	//Sanitize the POST values
	$login = clean($_POST['username']);
	$password = clean($_POST['password']);
	//$login_as = clean($_POST['login_as']);

	//Create 	

	$table = 'admin';
	
	$qry="SELECT * FROM $table WHERE username='$login' and password='".md5($password)."' ";
	$result=mysql_query($qry);

	if(mysql_num_rows($result) == 1) {	
		$member = mysql_fetch_array($result);
		$_SESSION['SESS_MEMBER_ID'] = $member['username'];
		$_SESSION['SESS_MEMBER_NAME'] = $member['name'];
		$_SESSION['MEM_ID'] = $member['id'];
		$_SESSION['PRIV'] = 1;

		header("Location: account.php");
	} else {
		header("Location: index.php?err=1");
	}
?>