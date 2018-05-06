<?php
  include('auth.php');
  include('top.php');

$table = 'admin';
/**************************************** Personal Information **********************************************/
$query  = mysql_query("SELECT password from $table where id='$_SESSION[MEM_ID]' ");
$row  = mysql_fetch_array($query);
$old_password = $row["password"];

$old_p = mysql_real_escape_string($_POST["old_p"]);  
$new_p = mysql_real_escape_string($_POST["new_p"]); 
$re_new_p = mysql_real_escape_string($_POST["re_new_p"]); 

//check for old password
if(strcmp(md5($old_p), $old_password) != 0 ){
	header("Location: change_password.php?err=1");
} elseif(strlen($new_p) < 5) {
	header("Location: change_password.php?err=2");
} elseif(strcmp(md5($new_p), md5($re_new_p)) != 0) {
	header("Location: change_password.php?err=3");
} else {
	$new_p = md5($new_p);
	mysql_query("UPDATE $table set password='$new_p' where id='$_SESSION[MEM_ID]' ");
	header("Location: change_password.php?err=4");
}

?>