<?php
	session_start();
	//Check whether the session variable SESS_MEMBER_ID is present or not
	if(!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '' || $_SESSION["PRIV"] != 1 )) {
		header("location: index.php");
		exit();
	}
	
?>