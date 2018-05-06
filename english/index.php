<?php include('top.php'); 
$dis = array("अम्बेडकर नगर","फैजाबाद", "सुल्तानपुर","देहरादून","लखनऊ");
$current_district = mysql_real_escape_string($_GET["dis"]);
$current_group = mysql_real_escape_string($_GET["grp"]);
$current_shop = mysql_real_escape_string($_GET["shp"]);
//$type = mysql_real_escape_string($_GET["type"]);
$type =3;
$dis_names=array("","अकबरपुर","टाण्डा","फैजाबाद A","फैजाबाद B","सुल्तानपुर","देहरादून A","देहरादून B","लखनऊ");
?>
<!DOCTYPE html>
<html>
<head>
	<title>High Spirit</title>
	<meta content="text/html; charset=UTF-8" http-equiv="content-type">
	<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
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
<body class="home" onload="initialize();">
	<div id="nav">
		<div id="logo">
 			<?php
 			if($current_district == 2 || $current_district == 3){
 				?>
 				<span>पिग्ग एंड पिट्स</span>
 				<?php
 			} else {
 				?>
 				<span>हाई</span> <span>स्पिरिट</span>
 				<?php
 			}
 			?>
 		</div>
 		<div id="dist">
 			<ul>
 				<?php 
 				$count =1;
 				foreach ($dis as $di) {
 					echo '<li class="';
 					echo ($count == $current_district)?'active':'';
 					echo '" ><a href="index.php?dis='.$count.'">'.$di.'</a></li>';
 					$count++;
 				}
 				?>
 			</ul>
 		</div>
 		<div class="clearfix"></div>
	</div>
	<div id="nav_group">
 		<div id="groups">
 			<ul>
 				<?php 
	 				$groups = mysql_query("SELECT DISTINCT group_name from shops_english where district = '$current_district' ");
	 				$count =1;
	 				if(mysql_num_rows($groups) > 0) {
		 				while($row = mysql_fetch_array($groups)) {
		 					echo '<li class="';
		 					echo ($row["group_name"] == $current_group)?'active':'';
		 					echo '" ><a href="index.php?dis='.$current_district.'&grp='.$row["group_name"].'">'.$dis_names[$row["group_name"]].' ग्रुप</a></li>';
		 					$count++;
	 					}
 					}
 				?>
 			</ul>
 		</div>
 		<div class="clearfix"></div>
	</div>
	<?php if($current_district != 0 && $current_group !=0 ): ?>
	<div id="options" style="float:right">
		<ul>
			<?php /*
			<li <?php  if($type == 3) echo 'style="background:#777;"' ?>><a href="index.php?dis=<?php echo $current_district?>&amp;grp=<?php echo $current_group;?>&amp;type=3" <?php if($type == 3) echo 'style="color:#fff;"';?>>बिक्री</a></li>
			<li <?php  if($type == 1) echo 'style="background:#777;"' ?>><a href="index.php?dis=<?php echo $current_district?>&amp;grp=<?php echo $current_group;?>&amp;type=1" <?php if($type == 1) echo 'style="color:#fff;"';?>>कमीशन</a></li>
			<li style="display:none;"><a href="index.php?dis=<?php echo $current_district?>&amp;grp=<?php echo $current_group;?>&amp;type=2">निकासी</a></li>
			*/ ?>
		</ul>
	</div>
	<div  style="float:left; margin:20px 0 20px 10px; font-size:20px;">
		दिनांक&nbsp;<?php echo ' '.$date_en.'-'.$month_en.'-'.$year_en; ?>
		<a href="javascript:;" style="font-size:14px; margin-left:10px; padding:7px 10px; background:#bbb" onclick="show_date_panel()">दिनांक बदले</a>
	</div>
	<div class="clearfix"></div>
	<div id="date_change" style="background:#eee; padding:20px; margin-bottom:20px; display:none;" align="center">
		
		<form action="" method="post">
			दिन<select name="date_ch" >
				<?php 
					for ($i=1; $i <32 ; $i++) { 
						echo '<option value="'.$i.'" ';
						if($i == $date_en) echo 'selected';
						echo ' >'.$i.'</option>';
					}
				?>
			</select>
			 माह <select name="month_ch">
				<?php 
					for ($i=1; $i <13 ; $i++) { 
						echo '<option value="'.$i.'" ';
						if($i == $month_en) echo 'selected';
						echo '>'.$i.'</option>';
					}
				?></select>
				वर्ष <select name="year_ch">
				<?php 
					for ($i=2013; $i <2020 ; $i++) { 
						echo '<option value="'.$i.'" ';
						if($i == $year_en) echo 'selected';
						echo '>'.$i.'</option>';
					}
					
				?>
			</select>
			<input type="submit" value="बदले">
			<input type="button" value="केन्सल" onclick="hide_date_panel()">
		</form>
	</div>
	<?php endif; 


		switch ($type) {
			case '3':
				include('sale_english.php');
				break;
		}

	?>
	
</body>

</html>