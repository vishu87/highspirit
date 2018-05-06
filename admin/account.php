<?php
include('auth.php');
include('top.php'); 
$dis = array("अम्बेडकर नगर","फैजाबाद", "सुल्तानपुर");
$current_district = mysql_real_escape_string($_GET["dis"]);
$current_group = mysql_real_escape_string($_GET["grp"]);
$current_shop = mysql_real_escape_string($_GET["shp"]);
//$type = mysql_real_escape_string($_GET["type"]);
$type =3;
$dis_names=array("","अकबरपुर","जलालपुर","भीटी","टाण्डा","बसखारी","जहांगीरगंज","फैजाबाद","सुल्तानपुर");
?>
<!DOCTYPE html>
<html>
<head>
	<title>High Spirit</title>
	<meta content="text/html; charset=UTF-8" http-equiv="content-type">
	<script type="text/javascript" src="../jquery-1.7.2.min.js"></script>
	<script>
	function show_date_panel(){
		$("#date_change").show("slow");
	}
	function hide_date_panel(){
		$("#date_change").hide("slow");
	}
	</script>
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
			<li><a href="english_account.php">अंग्रेजी शराब</a></li>
			<li><a href="beer_account.php">बीय़र शराब</a></li>
			<li><a href="../english/index.php" target="_blank">अंग्रेजी एंट्री</a></li>
			<li><a href="../hindi/index.php" target="_blank">देशी एंट्री</a></li>
			<li><a href="account.php">देशी शराब</a></li>
		</ul>
	</div>
	<div class="clearfix"></div>
	<div id="date_change" style="background:#eee; padding:20px; margin-bottom:20px;" align="center">
		
		<form action="process_account.php" method="post" target="_blank">
			<table>
				<tr>
					<td>जिला</td>
					<td>
						<select name="dis" id="dis" onchange="fetch_grp()">
							<option value="0">सभी</option>
							<?php
								$count =1;
				 				foreach ($dis as $di) {
				 					echo '<option value="'.$count.'">'.$di.'</option>';
				 					$count++;
				 				}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>ग्रुप</td>
					<td>
						<select name="grp" id="grp" onchange="fetch_shop()">
							<option value="0">सभी</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>दुकान</td>
					<td>
						<select name="shop" id="shop">
							<option value="0">सभी</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>तारीख से</td>
					<td>
						दिन<select name="date_pre" >
						<option value="0"></option>
						<?php 
							for ($i=1; $i <32 ; $i++) { 
								echo '<option value="'.$i.'" >'.$i.'</option>';
							}
						?>
					</select>
					 माह <select name="month_pre">
					 <option value="0"></option>
						<?php 
							for ($i=1; $i <13 ; $i++) { 
								echo '<option value="'.$i.'" >'.$i.'</option>';
							}
						?></select>
						वर्ष <select name="year_pre">
						<option value="0"></option>
						<?php 
							for ($i=2013; $i <2020 ; $i++) { 
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
							
						?>
						</select>
					</td>

				</tr>
				<tr>
					<td>तारीख तक</td>
					<td>
						दिन<select name="date_nxt" >
						<option value="0"></option>
						<?php 
							for ($i=1; $i <32 ; $i++) { 
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
						?>
					</select>
					 माह <select name="month_nxt">
					 <option value="0"></option>
						<?php 
							for ($i=1; $i <13 ; $i++) { 
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
						?></select>
						वर्ष <select name="year_nxt">
						<option value="0"></option>
						<?php 
							for ($i=2013; $i <2020 ; $i++) { 
								echo '<option value="'.$i.'" >'.$i.'</option>';
							}
							
						?>
						</select>
					</td>
					<tr>
						<td>दुकानो की डिटेल </td>
						<td><input type="radio" name="detail_shop" value="1" checked>हॉ
						<input type="radio" name="detail_shop" value = "0" >नही </td>
					</tr>
					<tr>
						<td>अधिकतम कमीशन</td>
						<td><input type="text" name="min_commission" value="150" >
					</tr>
					<tr>
						<td>सेल की कमी का प्रतिशत</td>
						<td><input type="text" name="sale_down" value="10" >
					</tr>
					
				</tr>
			</table>
			<button type="submit">Submit</button>
		</form>
	</div>
	<div id="date_change" style="background:#eee; padding:20px; margin-bottom:20px;" align="center">
		
		<form action="process_account_group.php" method="post" target="_blank">
			<table>
				<tr>
					<td>ग्रुप</td>
					<td>
						<select name="grp" id="grp">
							<?php
								$count =0;
				 				foreach ($dis_names as $di) {
				 					echo '<option value="'.$count.'">'.$di.'</option>';
				 					$count++;
				 				}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>तारीख से</td>
					<td>
						दिन<select name="date_pre" >
						<option value="0"></option>
						<?php 
							for ($i=1; $i <32 ; $i++) { 
								echo '<option value="'.$i.'" >'.$i.'</option>';
							}
						?>
					</select>
					 माह <select name="month_pre">
					 <option value="0"></option>
						<?php 
							for ($i=1; $i <13 ; $i++) { 
								echo '<option value="'.$i.'" >'.$i.'</option>';
							}
						?></select>
						वर्ष <select name="year_pre">
						<option value="0"></option>
						<?php 
							for ($i=2013; $i <2020 ; $i++) { 
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
							
						?>
						</select>
					</td>

				</tr>
				<tr>
					<td>तारीख तक</td>
					<td>
						दिन<select name="date_nxt" >
						<option value="0"></option>
						<?php 
							for ($i=1; $i <32 ; $i++) { 
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
						?>
					</select>
					 माह <select name="month_nxt">
					 <option value="0"></option>
						<?php 
							for ($i=1; $i <13 ; $i++) { 
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
						?></select>
						वर्ष <select name="year_nxt">
						<option value="0"></option>
						<?php 
							for ($i=2013; $i <2020 ; $i++) { 
								echo '<option value="'.$i.'" >'.$i.'</option>';
							}
							
						?>
						</select>
					</td>
					<tr>
						<td>दिन की डिटेल </td>
						<td><input type="radio" name="detail_day" value="1" checked>हॉ
						<input type="radio" name="detail_day" value = "0" >नही </td>
					</tr>
					
				</tr>
			</table>
			<button type="submit">Submit</button>
		</form>
	</div>
</body>
<script type="text/javascript">
function fetch_grp(){
     
     var file = "fetch_grp";
     var id = $("#dis").val();
     $.post(""+ file +".php", {id:id}, function(data) {
     	$("#grp").html(data);
   });
}

function fetch_shop(){
     
     var file = "fetch_shop";
     var id = $("#grp").val();
     $.post(""+ file +".php", {id:id}, function(data) {
     	$("#shop").html(data);
   });
}

</script>
</html>