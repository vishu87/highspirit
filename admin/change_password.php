<?php
include('auth.php');
include('top.php'); 
?>
<!DOCTYPE html>
<html>
<head>
	<title>High Spirit</title>
	<meta content="text/html; charset=UTF-8" http-equiv="content-type">
	<script type="text/javascript" src="../jquery-1.7.2.min.js"></script>
	<style type="text/css">
			
		body{
			color: #000;
			padding: 0px !important;
			margin: 0px !important;
			font-size: 13px;
			font-family: 'Shusha';
		}
		table td {
			text-align: center;
			vertical-align: middle;
			border: 1px solid #000;
			padding: 5px;
		}
		@font-face { font-family: Shusha; src: url('shusha.ttf');src: url('shusha.eot');  }
		#nav{
			background: #f7f7f7;

		}
		a {
			color:#000;
			text-decoration: none;
		}
		#logo{
			color: #555;
			font-size: 20px;
			padding:5px 10px;
			float: left;
		}
		#dist{
			float: right;
		}
		#nav #dist ul{
			list-style: none;
			display: inline-block;
			margin: 0;
			font-size: 14px;
		}
		#nav #dist ul li{
			display: inline-block;
			padding: 10px;
		}
		#nav #dist ul li.active{
			background: #777;
			
		}
		#nav #dist ul li.active a{
			color:#fff;
		}
		.clearfix{
			clear: both;
		}
		#nav_group{
			background: #777;
			
		}
		#groups ul{
			list-style: none;
			display: inline-block;
			margin: 5px 0 0 0;
			font-size: 14px;
		}
		#groups ul li{
			display: inline-block;
			padding: 10px;
		}
		#groups ul li a{
			color: #eee;
		}
		#groups ul li.active{
			background: #fff;
			color: #000;
		}
		#groups ul li.active a{
			color: #000;
		}
		#options{
			margin-bottom: 30px;
		}
		#options ul{
			list-style: none;
			display: inline-block;
			margin: 10px 10px 0 0;
			font-size: 16px;
		}
		#options ul li{
			display: inline-block;
			padding: 10px 10px;
			margin: 0 0px;
			background: #ccc;
		}
		#options ul li.active{
			background: #fff;
			color: #000;

		}
		.number{
			text-align: right;
		}
		.highlight{
			background: #ccc;
		}
		#header-fixed {
		    position: fixed;
		    top: 0px; display:none;
		    background-color:white;
		}
	</style>
</head>
<body class="home" >
	<div id="nav">
		<div id="logo">
 			<span>हाई</span> <span>स्पिरिट</span>
 		</div>
 		<div class="clearfix"></div>
	</div>

	<div id="options" style="float:right">
		<ul>
			<li><a href="logout.php">लॉगआउट</a></li>
			<li><a href="change_password.php">पासवर्ड  बदले</a></li>
		</ul>
	</div>
	<div class="clearfix"></div>
	<div id="date_change" style="background:#eee; padding:20px; margin-bottom:20px;" align="center">
		
		<?php
		if(isset($_GET["err"])){

			if($_GET["err"] == 1){
				echo '<div  align="center" style="padding:20px;">पुराना पासवर्ड सही नहीं है </div>';
			}
			if($_GET["err"] == 2){
				echo '<div  align="center" style="padding:20px;">नया पासवर्ड 5 अक्षर से बड़ा होना चाहिए </div>';
			}
			if($_GET["err"] == 3){
				echo '<div  align="center" style="padding:20px;">दोनों नए डाले गए पासवर्ड मेल नहीं खाते</div>';
			}
			if($_GET["err"] == 4){
				echo '<div  align="center" style="padding:20px;">पासवर्ड बदल गया है</div>';
			}

		}
 		?>

		<form action="change.php" method="post" target="">
			<table>
				<tr>
					<td>पुराना पासवर्ड</td>
					<td>
						<input type="password" name="old_p">
					</td>
				</tr>
				<tr>
					<td>नया पासवर्ड</td>
					<td>
						<input type="password" name="new_p">
					</td>
				</tr>
				<tr>
					<td>नया पासवर्ड</td>
					<td>
						<input type="password" name="re_new_p">
					</td>
				</tr>
			</table>
			<button type="submit">Submit</button>
		</form>
	</div>
</html>