<?php
include('top.php'); 
$dis = array("अम्बेडकर नगर","फैजाबाद", "सुल्तानपुर","देहरादून");
$type =3;
$dis_names=array("","अकबरपुर","टाण्डा","फैजाबाद A","फैजाबाद B","सुल्तानपुर");
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
	<div class="clearfix"></div>
	<div id="date_change" style="background:#eee; padding:20px; margin-bottom:20px;" align="center">
		शॉप का डेटा ट्रांसफर करे
		<form action="config_try.php" method="get" target="_blank">
			<table>
				<tr>
					<td>ग्रुप</td>
					<td>
						<select name="grp" id="grp" onchange="fetch_shop_beer()">
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
					<td>दुकान</td>
					<td>
						<select name="shop" id="shop">
							<option value="0">सभी</option>
						</select>
					</td>
				</tr>
				
			</table>
			<button type="submit">Submit</button>
		</form>
		<br><br><br>
		<br><br><br>

		डुप्लीकेट पुराना डेटा हटाये
		<form action="delete_duplicate_data.php" method="get" target="_blank">
			<table>
				<tr>
					<td>ग्रुप</td>
					<td>
						<select name="grp" id="grp1" onchange="fetch_shop_beer2()">
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
					<td>दुकान</td>
					<td>
						<select name="shop" id="shop1">
							<option value="0">सभी</option>
						</select>
					</td>
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

function fetch_shop_beer(){
     
     var file = "fetch_shop_beer";
     var id = $("#grp").val();
     $.post(""+ file +".php", {id:id}, function(data) {
     	$("#shop").html(data);
   });
}
function fetch_shop_beer2(){
     
     var file = "fetch_shop_beer";
     var id = $("#grp1").val();
     $.post(""+ file +".php", {id:id}, function(data) {
     	$("#shop1").html(data);
   });
}

</script>
</html>