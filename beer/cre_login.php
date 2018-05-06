<?php 
session_start();
include('auth.php');
require_once('sysadmin/libraries/PHPMailerAutoload.php');

$mail = new PHPMailer;
$mail->isMail();
$mail->setFrom('soccerschools@bengalurufc.com', 'Bengaluru FC');
$mail->isHTML(true);
$mail->Subject = "Parent Login - Bengaluru FC";

$student_data=mysql_query("SELECT `id`,`father`,`father_email`, `mother`, `mother_email`, `name` FROM students where parent_id = 0 limit 50 ");

while($student=mysql_fetch_array($student_data)){
	$mail->ClearAllRecipients();
	$student_id = $student["id"];
	$fname = $student["father"];
	$femail = $student["father_email"];

	$mname = $student["mother"];
	$memail = $student["mother_email"];

	if($fname != '' && $femail != ''){
		$name = $fname; $email = $femail;
	} else {
		$name = $mname; $email = $memail;
	}

	$parent_id = 0;

	if($name != '' && $email != ''){
		$check_parent = mysql_query("SELECT id from parents where email = '$email'  limit 1 ");
		if(mysql_num_rows($check_parent) > 0){
			$parent = mysql_fetch_array($check_parent);
			$parent_id = $parent["id"];
			mysql_query("UPDATE `students` SET `parent_id`='$parent_id' WHERE `id`= $student_id ");
		} else {
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$password_check = substr( str_shuffle( $chars ), 0, 8 );
			$password = md5($password_check);

			$insert_parent_details="INSERT INTO parents (`name`,`email`,`password`) VALUES ('$name','$email','$password')";
			mysql_query($insert_parent_details);
			$parent_id=mysql_insert_id();

			if($parent_id){
				
				$mail->addAddress($email);
				$message = '
				<html>
				<body>
				<p>Dear Parent</p>
				<p>You have been registered with BFC soccer schools. To access your account for tracking your child\'s performance, attendance and payments, please use following details:</p>
				<p>Portal Link : <a href="http://soccerschools.bengalurufc.com/appsoft/parentlogin.php"><b>http://soccerschools.bengalurufc.com/appsoft/parentlogin.php</b></a><br>
				Username: <b>'.$email.'</b><br>
				Password: <b>'.$password_check.'</b>
				</p>
				<p>Please feel free to drop us an email at <a href="mailto:soccerschools@bengalurufc.com">soccerschools@bengalurufc.com</a> in case of any discrepancy.</p>
				<p>Warm Regards<br><br>
				<b>BFC Team</b>
				<p>
				</body></html>';
				$mail->Body = $message;
				echo 'Login created for '.$student["name"].' with '.$email.'<br>';
				if($mail->send()){
			        mysql_query("UPDATE `students` SET `parent_id`='$parent_id' WHERE `id`='$student_id'");
			        echo true;
			    } else {
			    	mysql_query("UPDATE `students` SET `parent_id`='$parent_id' WHERE `id`='$student_id'");
			    	echo false;
			    }
			}

		}
	} else {
		echo 'Login <span style="color:#F00">NOT</span> created for '.$student["name"].' <br>';

	}

}

?>