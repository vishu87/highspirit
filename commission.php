<?php
$query_check = mysql_query("SELECT timestamp from commission where district='$current_district' and group_name = '$current_group' order by timestamp desc limit 1");
$row_check = mysql_fetch_array($query_check);
$timestring_last = $row_check["timestamp"];
if( $timestring_last < $timestring_now){
?>
<style type="text/css">
		table td {
			text-align: center;
			vertical-align: middle;
			border: 1px solid #555;
			padding: 5px;
			font-size: 14px;
		}
		table th {
			text-align: center;
			vertical-align: middle;
			border: 1px solid #555;
			padding: 5px;
		}
	</style>
<div align="center" style="font-size:24px; margin:10px 0;"><?php echo $dis_names[$current_district];?> ग्रुप का कमीशन</div>
<form action="process.php?type=1&amp;dis=<?php echo $current_district;?>&amp;grp=<?php echo $current_group;?>" method="post">
	<input name="date_en" type="hidden" value="<?php echo $date_en?>">
	<input name="month_en" type="hidden" value="<?php echo $month_en?>">
	<input name="year_en" type="hidden" value="<?php echo $year_en?>">
<table cellspacing="0" cellpaddin="0" align="center" style="width:100%">
		<tr>
			<th>दुकान का नाम</th>
			<th>कमीशन</th>
			<th>पुराना कमीशन <br> दिनांक <?php echo date("d-m-y", $timestring_last);?></th>
			<th>300 रुपये के हिसाब से</th>
		</tr>
<?php 
	$input_count = 0;
	mysql_query('SET character_set_results=utf8');
	$query = "SELECT id,name from shops where district='$current_district' and group_name = '$current_group' order by id asc ";
	$main_query = mysql_query($query);
	while($row = mysql_fetch_array($main_query)){
		$randomFloat = rand(0, 100) / 100;
?> 
	<tr id="tr_<?php echo  $input_count?>">
		<td style="font-family:Shusha"><?php echo $row["name"];?></td>
		<td><input type="text" class="number input_arrow" name="com_<?php echo $row["id"];?>" value="<?php echo $randomFloat?>" id="input_<?php echo $input_count;?>" input_id="<?php echo  $input_count?>" row_id="<?php echo  $input_count?>"></td>
		<td><?php 
			$sql_last = mysql_query("SELECT commission from commission where shop_id='$row[id]' and timestamp = '$timestring_last' limit 1");
			$row_last = mysql_fetch_array($sql_last);
			echo $row_last["commission"];
		?></td>
		<td></td>
	</tr>
<?php  $input_count++;
} ?>
</table>
<input type="submit" value="जमा करें"> 
</form>
<?php } else { 
	if($row_check["timestamp"] == $timestring_now){
		echo 'दिनांक '.date("d-m-y", $row_check["timestamp"] ).' की प्रविष्टि हो चुकि है।';
		
	}
	else {
		echo 'दिनांक '.date("d-m-y", $row_check["timestamp"] ).' की प्रविष्टि हो चुकि है। अतः पुरानी दिनांक '.date("d-m-y", $timestring_now ).' की प्रविष्टि नहीं हो सकती हैं।';
	}

 } ?>

<script type="text/javascript">
	var total_type =1;
 	var input_id = 0;
 	var shift_id = 1;

 
 	function initialize() 
		{
			

		$(".input_arrow").focus( function(){
				$(this).select();
				if($(this).val()=='0') $(this).val('');
				input_id = $(this).attr('input_id');
				$("#tr_"+input_id).addClass('highlight');
				shift_id = input_id;
			});

			$(".input_arrow").blur( function(){
				if($(this).val()=='') $(this).val('0');
				row_id = $(this).attr('row_id');
				$("#tr_"+row_id).removeClass('highlight');
			});


			

			$(".input_arrow").keydown(function(e){
			    if (e.keyCode == 37) { 
			      	//alert("left");
			      	shift_id = parseInt(input_id) - 1;
			    
			    }
			     if (e.keyCode == 38) { 
			      	//alert("top");
			      	shift_id = parseInt(input_id) - total_type;
			       
			    }
				if (e.keyCode == 39) { 
			      	//alert("right");
			      	shift_id = parseInt(input_id) + 1;
			       
			    }
			    if (e.keyCode == 40) { 
			      	//alert("bottom");
			      	shift_id = parseInt(input_id) + total_type;
			       
			    }
			   
			    if(shift_id != input_id) $("#input_"+shift_id).focus();
			});
			
		}


 </script>