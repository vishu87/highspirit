<?php include('../top.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<title>High Spirit</title>
	<meta content="text/html; charset=UTF-8" http-equiv="content-type">
	<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
</head>
<body class="home">
	<div id="nav">
		<div id="logo" align="center" style="font-size:26px">
 			<span>हाई</span> <span>स्पिरिट</span>
 		</div>
 	</div>

 	<div  align="center" style="margin-top:100px;">
 	<?php
		if(isset($_GET["err"])){

			if($_GET["err"] == 1){
				echo '<div  align="center" style="padding:20px;">गलत यूजरनेम या पासवर्ड</div>';
			}

		}
 	?>
	 	<form action="login-exec.php" method="post">
	 		<table cellspacing="5" cellpadding="5" style="border:1px solid #CCC">
	 			<tr>
	 				<td>यूजरनेम</td>
	 				<td><input name="username"></td>
	 			</tr>
	 			<tr>
	 				<td>पासवर्ड</td>
	 				<td><input name="password" type="password"></td>
	 			</tr>
	 			<tr>
	 				<td colspan="2" style="text-align:center"><input type="submit" value="लोगिन करे"></td>
	 			</tr>
	 		</table>
	 	</form>
 	</div>
</body>

</html>