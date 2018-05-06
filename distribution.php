<?php
$query_check = mysql_query("SELECT timestamp from outgoing where district='$current_district' and group_name = '$current_group' order by timestamp desc limit 1");
$row_check = mysql_fetch_array($query_check);
if($row_check["timestamp"] < $timestring_now){
?>
<form action="process.php?type=2&amp;dis=<?php echo $current_district;?>&amp;grp=<?php echo $current_group;?>" method="post">
	<input name="date_en" value="<?php echo $date_en?>">
	<input name="month_en" value="<?php echo $month_en?>">
	<input name="year_en" value="<?php echo $year_en?>">
	<table id="header-fixed" cellspacing="0" cellpaddin="0">
	<tr>
			<td style="width:150px">दुकान का नाम</td>
			<?php 
				
				mysql_query('SET character_set_results=utf8');
				$query_type = mysql_query("SELECT * from types");
				$count_type = mysql_num_rows($query_type);
				while ($row_type = mysql_fetch_array($query_type)) {
					echo '<td class="td_'.$row_type["id"].'" style="width:80px">'.$row_type["type_brand"].'</td>';
				}
			?>
		</tr>
	</table>
<table cellspacing="0" cellpaddin="0" id="table-1">
		<tr>
			<td style="width:150px">दुकान का नाम</td>
			<?php 
				$input_count =1;
				mysql_query('SET character_set_results=utf8');
				$query_type = mysql_query("SELECT * from types");
				$count_type = mysql_num_rows($query_type);
				while ($row_type = mysql_fetch_array($query_type)) {
					echo '<td class="td_'.$row_type["id"].'" style="width:80px">'.$row_type["type_brand"].'</td>';
				}
			?>
		</tr>
<?php 
	$query = "SELECT id,name from shops where district='$current_district' and group_name = '$current_group' order by id asc ";
	$main_query = mysql_query($query);

	while($row = mysql_fetch_array($main_query)){
		$randomFloat = rand(0, 100);
?> 
	<tr id="tr_<?php echo $row["id"]?>">
		<td style="font-family:Shusha"><?php echo $row["name"];?></td>
		<?php
		$query_type = mysql_query("SELECT * from types");
		while ($row_type = mysql_fetch_array($query_type)) {
					echo '<td class="td_'.$row_type["id"].'"><input type="text" class="input_arrow" style="width:50px;" name="dis_'.$row_type["id"].'_'.$row["id"].'" value="'.$randomFloat.'" id="input_'.$input_count.'" row_id="'.$row["id"].'" col_id="'.$row_type["id"].'" input_id="'.$input_count.'"></td>';
					$input_count++;
				}
		?>
	</tr>
<?php  } ?>
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
 	var total_type = <?php echo $count_type;?>;
 	var row_id =0;
 	var col_id = 0;
 	var input_id = 0;
 	var shift_id = 1;

 	function initialize() 
		{
			var tableOffset = $("#table-1").offset().top;
			$(window).bind("scroll", function() {
			    var offset = $(this).scrollTop();

			    if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
			        $fixedHeader.show();
			    }
			    else if (offset < tableOffset) {
			        $fixedHeader.hide();
			    }
			});

			$(".input_arrow").focus( function(){
				$(this).select();
				if($(this).val()=='0') $(this).val('');
				row_id = $(this).attr('row_id');
				col_id = $(this).attr('col_id');
				input_id = $(this).attr('input_id');
				$("#tr_"+row_id).addClass('highlight');
				$(".td_"+col_id).addClass('highlight');
			});

			$(".input_arrow").blur( function(){
				if($(this).val()=='') $(this).val('0');
				row_id = $(this).attr('row_id');
				col_id = $(this).attr('col_id');
				$("#tr_"+row_id).removeClass('highlight');
				$(".td_"+col_id).removeClass('highlight');
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
			    console.log(shift_id);
			    if(shift_id != input_id) $("#input_"+shift_id).focus();
			});
			
		}

 </script>