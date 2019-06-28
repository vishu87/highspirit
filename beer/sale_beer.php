<script type="text/javascript">
function show_shop_panel(){
				$("#shop_list").show("slow");
			}
	function hide_shop_panel(){
		$("#shop_list").hide("slow");
	}
	</script>
<?php
	if($current_shop == '') $current_shop =0;
	mysql_query('SET character_set_results=utf8');
	$res_shop = mysql_query("SELECT id, name from shops_beer where district='$current_district' and group_name = '$current_group' ");
	if($current_shop <= (mysql_num_rows($res_shop)-1)){
		
		mysql_data_seek($res_shop, $current_shop);
		$row_shop = mysql_fetch_assoc($res_shop);
	
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
		input[readonly], input[readonly="readonly"]
		{
		    background-color:#EEE;
		    border:0;
		}
		input[type="text"]
		{
		    width:25px;
		    font-size: 10px;
		}
		input.total_sum, input.total_cost{
			width: 40px;
			font-size: 12px;
		}
		input#total_total_sum{
			width: 50px;
			font-size: 14px;
			font-weight: bold;
		}
		#final_fields input{
			width: 200px;
			font-size: 20px;
		}
		#final_total{
			font-size: 30px;
			font-weight: bold;
		}
	</style>
<div style="font-size:14px;">
	<a href="javascript:;" style="font-size:14px; margin-left:10px; padding:7px 10px; background:#ccc" onclick="show_shop_panel()">दुकानो की लिस्ट</a>
	<div style="padding:7px 10px; background:#ccc; display:none;" id="shop_list">
		<?php  
			mysql_query('SET character_set_results=utf8');
			$sql_shop = mysql_query("SELECT name from shops_beer where district='$current_district' and group_name = '$current_group' ");
			$count =0;
			while ($row_sh = mysql_fetch_array($sql_shop)) {
				echo '<div style="float:left; width:100px; height:40px; text-align:center; padding:5px 10px">'.($count+1).' <a href="index.php?type=3&dis='.$current_district.'&grp='.$current_group.'&shp='.$count.'">'.$row_sh["name"].'</a></div>';
				$count++;
			}
		?>
		<div style="clear:both"></div>
		<div align="center" style="margin:10px 0;"><input type="button" value="बंद करें" onclick="hide_shop_panel()"></div>
	</div>

</div>
<div align="center" style="font-size:24px; margin:10px 0;"><?php echo $row_shop["name"];?></div>
<?php

$time_now =  strtotime($month_en.'/'.$date_en.'/'.$year_en);

$time_ytr = $time_now - 86400;
$flag_new = 0;
$priv = 1;

$check_old = mysql_query("SELECT timestamp_day from sale_beer where shop_id='$row_shop[id]' order by timestamp_day asc limit 1");
$row_check_old = mysql_fetch_array($check_old);

if($row_check_old["timestamp_day"] > $time_now){
	echo "<div style='background:#FFC7C7; font-size:16px;padding:5px' align='center'>इस दिनांक की प्रविष्टि आप नहीं कर सकते</div>";

	die();
}

$check_skip = mysql_query("SELECT timestamp_day from sale_beer where shop_id='$row_shop[id]' order by timestamp_day desc limit 1");
if(mysql_num_rows($check_skip) > 0){
	$row_skip = mysql_fetch_array($check_skip);
	$timestamp_last = $row_skip["timestamp_day"];
	$timestamp_shd_day = $timestamp_last + 86400;
	if($time_now > $timestamp_shd_day){
		echo "<div style='background:#FFC7C7; font-size:16px;padding:5px' align='center'>पहले दिनांक ".date("d M Y",$timestamp_shd_day)." की प्रविष्टि करे</div>";
		die();
	}
}

$sql_new = mysql_query("SELECT id from sale_beer where shop_id='$row_shop[id]' and timestamp_day = $time_now ");
if(mysql_num_rows($sql_new) > 0){
	$flag_new =1;
	if($priv == 0) {
		echo "<div style='background:#FFC7C7; font-size:16px;padding:5px' align='center'>इस दिनांक की प्रविष्टि को आप बदल नहीं सकते</div>";
		die();
	} else {
		echo "<div style='background:#FFC7C7; font-size:16px;padding:5px' align='center'>इस दिनांक की प्रविष्टि को आप देख सकते हैं, लेकिन बदल नहीं सकते </div>";
		$sql_ck_last = mysql_query("SELECT timestamp_day from sale_beer where shop_id='$row_shop[id]' order by timestamp_day desc");
		$row_ck_last = mysql_fetch_array($sql_ck_last);
		if($row_ck_last["timestamp_day"] == $time_now)echo '<div align="center" style="margin:20px;" ><a href="process_beer_delete.php?current_shop='.$current_shop.'&amp;type=3&amp;shop_id='.$row_shop["id"].'&amp;timestamp='.$time_now.'&amp;dis='.$current_district.'&amp;grp='.$current_group.'" style="padding:10px; background:#000; color:#fff;">यह डॉटा डिलीट कर दे</a></div>';
	}
}

$ar_details = array("स्टाक","आमद","ट्रांसफर","योग","बिक्री","रेट","योग","कुल योग","बचा माल");
$in_details = array("old_stock","stock_in","stock_transfer","initial_stock","sale","rate","total_cost","total_sum","final_stock");
$brand_size = array("","650ml","330ml","500ml","275ml");
$total_sizes = 4;

$total_form_data = 10;

?>
	<input type="hidden" id="date_en" value="<?php echo $date_en?>">
	<input type="hidden" id="month_en" value="<?php echo $month_en?>">
	<input type="hidden" id="year_en" value="<?php echo $year_en?>">
	<input type="hidden" id="shop_id" value="<?php echo $row_shop["id"]; $shop_id=$row_shop["id"]; ?>">
	<input type="hidden" id="current_id" value="<?php echo $current_shop; ?>">

	<table cellspacing="0" cellpaddin="0" width="100%">
		<?php 
			$row_id = 1;
			$input_count = 1;
			
			if($flag_new == 1){
				$array_day_sum = array();
				$sql_fetch = mysql_query("SELECT * from sale_beer_sum_final where shop_id='$shop_id' and timestamp_day='$time_now'");

				while($row_fetch = mysql_fetch_array($sql_fetch)){
					$array_day_sum[$row_fetch["type_id"]]["total_sum"] = $row_fetch["total_sum"];
					$array_day_sum[$row_fetch["type_id"]]["money_given"] = $row_fetch["money_given"];
					$array_day_sum[$row_fetch["type_id"]]["money_name"] = $row_fetch["money_name"];
					$array_day_sum[$row_fetch["type_id"]]["other_money"] = $row_fetch["other_money"];
					$array_day_sum[$row_fetch["type_id"]]["remark"] = $row_fetch["remark"];
					$array_day_sum[$row_fetch["type_id"]]["final_total"] = $row_fetch["final_total"];
				}
				
				$array_day = array();

				$sql_fetch = mysql_query("SELECT * from sale_beer where shop_id='$shop_id' and timestamp_day='$time_now' ");
				while($row_fetch = mysql_fetch_array($sql_fetch)){
					$array_day[$row_fetch["type_id"]][$row_fetch["size_id"]]["old_stock"] = $row_fetch["old_stock"];
					$array_day[$row_fetch["type_id"]][$row_fetch["size_id"]]["stock_in"] = $row_fetch["stock_in"];
					$array_day[$row_fetch["type_id"]][$row_fetch["size_id"]]["stock_transfer"] = $row_fetch["stock_transfer"];
					$array_day[$row_fetch["type_id"]][$row_fetch["size_id"]]["initial_stock"] = $row_fetch["initial_stock"];
					$array_day[$row_fetch["type_id"]][$row_fetch["size_id"]]["final_stock"] = $row_fetch["final_stock"];
					$array_day[$row_fetch["type_id"]][$row_fetch["size_id"]]["sale"] = $row_fetch["sale"];
					$array_day[$row_fetch["type_id"]][$row_fetch["size_id"]]["rate"] = $row_fetch["rate"];
					$array_day[$row_fetch["type_id"]][$row_fetch["size_id"]]["total_cost"] = $row_fetch["total_cost"];
				}

				$array_day_final = array();
				$sql_fetch = mysql_query("SELECT * from sale_beer_final where shop_id='$shop_id' and timestamp_day='$time_now' ");
				while($row_fetch = mysql_fetch_array($sql_fetch)){
					$array_day_final[$row_fetch["size_id"]]["old_stock"] = $row_fetch["old_stock"];
					$array_day_final[$row_fetch["size_id"]]["stock_in"] = $row_fetch["stock_in"];
					$array_day_final[$row_fetch["size_id"]]["stock_transfer"] = $row_fetch["stock_transfer"];
					$array_day_final[$row_fetch["size_id"]]["initial_stock"] = $row_fetch["initial_stock"];
					$array_day_final[$row_fetch["size_id"]]["final_stock"] = $row_fetch["final_stock"];
					$array_day_final[$row_fetch["size_id"]]["sale"] = $row_fetch["sale"];
					$array_day_final[$row_fetch["size_id"]]["rate"] = $row_fetch["rate"];
					$array_day_final[$row_fetch["size_id"]]["total_cost"] = $row_fetch["total_cost"];
				}


			} else {
				
				$array_day = array();

				$sql_fetch = mysql_query("SELECT * from sale_beer where shop_id='$shop_id' and timestamp_day='$time_ytr' ");
				while($row_fetch = mysql_fetch_array($sql_fetch)){
					$array_day[$row_fetch["type_id"]][$row_fetch["size_id"]]["old_stock"] = $row_fetch["old_stock"];
					$array_day[$row_fetch["type_id"]][$row_fetch["size_id"]]["stock_in"] = $row_fetch["stock_in"];
					$array_day[$row_fetch["type_id"]][$row_fetch["size_id"]]["stock_transfer"] = $row_fetch["stock_transfer"];
					$array_day[$row_fetch["type_id"]][$row_fetch["size_id"]]["initial_stock"] = $row_fetch["initial_stock"];
					$array_day[$row_fetch["type_id"]][$row_fetch["size_id"]]["final_stock"] = $row_fetch["final_stock"];
					$array_day[$row_fetch["type_id"]][$row_fetch["size_id"]]["sale"] = $row_fetch["sale"];
					$array_day[$row_fetch["type_id"]][$row_fetch["size_id"]]["rate"] = $row_fetch["rate"];
					$array_day[$row_fetch["type_id"]][$row_fetch["size_id"]]["total_cost"] = $row_fetch["total_cost"];
				}

				$array_day_final = array();
				$sql_fetch = mysql_query("SELECT * from sale_beer_final where shop_id='$shop_id' and timestamp_day='$time_ytr' ");
				while($row_fetch = mysql_fetch_array($sql_fetch)){
					$array_day_final[$row_fetch["size_id"]]["old_stock"] = $row_fetch["old_stock"];
					$array_day_final[$row_fetch["size_id"]]["stock_in"] = $row_fetch["stock_in"];
					$array_day_final[$row_fetch["size_id"]]["stock_transfer"] = $row_fetch["stock_transfer"];
					$array_day_final[$row_fetch["size_id"]]["initial_stock"] = $row_fetch["initial_stock"];
					$array_day_final[$row_fetch["size_id"]]["final_stock"] = $row_fetch["final_stock"];
					$array_day_final[$row_fetch["size_id"]]["sale"] = $row_fetch["sale"];
					$array_day_final[$row_fetch["size_id"]]["rate"] = $row_fetch["rate"];
					$array_day_final[$row_fetch["size_id"]]["total_cost"] = $row_fetch["total_cost"];
				}

			}	

			echo '<form class="english_form">';

			mysql_query('SET character_set_results=utf8');

				

				$query_type = mysql_query("SELECT type_brand, id from types_beer order by serial_count asc ");
				
				while($row_type = mysql_fetch_array($query_type)){

					if($row_id%$total_form_data == 0 || $row_id == 1){
						echo '</form><form class="english_form">';
						echo '<tr>';
						echo '<td></td>';
						$count_det =0;
						foreach ($ar_details as $detail) {
							echo '<td ';
							if($count_det != 7) echo 'colspan="'.$total_sizes.'"';
							echo '>'.$detail.'</td>';
							$count_det++;
						}	
						echo '</tr>';

						echo '<tr><td></td>';
						foreach ($in_details as $detail) {

							$count_br = 0;
							if($detail != 'total_sum'){
								foreach ($brand_size as $size) {
								if($count_br != 0) echo '<td>'.$size.'</td>';$count_br++;
								}
							} else {
								echo '<td>RS</td>';
							}
						}
						echo '</tr>';					
					}

					echo '<tr id="tr_'.$row_type["id"].'"><td>'.$row_type["type_brand"].'<input type="text" name="brand[]" value="'.$row_type["id"].'" style="display:none" readonly></td>';
					$count_det =0;
					$col_id = 1;
					foreach ($in_details as $detail) {
						switch ($detail) {
							case 'total_sum':
								if($flag_new == 1){
									$value = $array_day_sum[$row_type["id"]][$detail];
								} else {
									$value='';
								}
								echo '<td><input type="text" name="'.$detail.'_'.$row_type["id"].'" id="'.$detail.'_'.$row_type["id"].'" class="'.$detail.'" value="'.$value.'" readonly></td>';
								break;
							case 'total_cost':
								for($i = 1; $i <= $total_sizes; $i++) {
									if($flag_new == 1){
										$value = $array_day[$row_type["id"]][$i][$detail];
									}else {
										$value='';
									}

									echo '<td><input type="text" name="'.$detail.'_'.$row_type["id"].'_'.$i.'" class="'.$detail.' '.$detail.'_'.$row_type["id"].'_'.$i.' '.$detail.'_'.$i.'" size_type="'.$i.'" brand_type="'.$row_type["id"].'" value="'.$value.'" readonly></td>'; 
								}
								break;
							case 'final_stock':
								for($i = 1; $i <= $total_sizes; $i++) { 
									if($flag_new == 1){
										$value = $array_day[$row_type["id"]][$i][$detail];
									}else {
										$value = $array_day[$row_type["id"]][$i]["final_stock"];
									}

									echo '<td><input type="text" name="'.$detail.'_'.$row_type["id"].'_'.$i.'" class="'.$detail.' '.$detail.'_'.$row_type["id"].'_'.$i.' '.$detail.'_'.$i.'" size_type="'.$i.'" brand_type="'.$row_type["id"].'" value="'.$value.'" readonly></td>'; }
								break;	

							case 'initial_stock':
								for($i = 1; $i <= $total_sizes; $i++) { 
									if($flag_new == 1){
										$value = $array_day[$row_type["id"]][$i][$detail];
										
									} else {
										$value = $array_day[$row_type["id"]][$i]["final_stock"];
									}

									echo '<td><input type="text" name="'.$detail.'_'.$row_type["id"].'_'.$i.'" class="'.$detail.' '.$detail.'_'.$row_type["id"].'_'.$i.' '.$detail.'_'.$i.'" size_type="'.$i.'" brand_type="'.$row_type["id"].'" value="'.$value.'" readonly></td>'; }
								break;
							case 'old_stock':
								for($i = 1; $i <= $total_sizes; $i++) { 
									$flag_old = (sizeof($array_day[$row_type["id"]])>0)?0:1;

									if($flag_new == 1){
										$value = $array_day[$row_type["id"]][$i][$detail];	
									} else {
										$value = $array_day[$row_type["id"]][$i]["final_stock"];
									}

									echo '<td><input type="text" name="'.$detail.'_'.$row_type["id"].'_'.$i.'" class="input_arrow '.$detail.' '.$detail.'_'.$row_type["id"].'_'.$i.' '.$detail.'_'.$i.'" size_type="'.$i.'" brand_type="'.$row_type["id"].'" value="'.$value.'" id="input_'.$input_count.'" row_id="'.$row_type["id"].'" col_id="'.$col_id.'" input_id="'.$input_count.'" ';
									echo ($flag_old == 0)?'readonly':'';
									echo  '></td>'; 
									 $col_id++; $input_count++;
								}
								break;

							case 'rate':
								for($i = 1; $i <= $total_sizes; $i++) { 
									if($flag_new == 1){
										$value = $array_day[$row_type["id"]][$i][$detail];
									} else {
										if(!$array_day[$row_type["id"]][$i]["rate"] || $array_day[$row_type["id"]][$i]["rate"] == 0 || $array_day[$row_type["id"]][$i]["rate"] == ''){
											$sql_fetch_rate = mysql_query("SELECT rate from sale_beer where shop_id='$shop_id' and type_id = $row_type[id] and size_id = $i order by timestamp_day desc limit 1 ");
											if($row_fetch_rate = mysql_fetch_array($sql_fetch_rate)) $value = $row_fetch_rate["rate"];
											else $value = '';

										} else $value = $array_day[$row_type["id"]][$i]["rate"];
									}

									echo '<td class="td_'.$col_id.'"><input type="text" name="'.$detail.'_'.$row_type["id"].'_'.$i.'" size_type="'.$i.'" class="input_arrow '.$detail.' '.$detail.'_'.$row_type["id"].'_'.$i.' '.$detail.'_'.$i.'" brand_type="'.$row_type["id"].'" value="'.$value.'" id="input_'.$input_count.'" row_id="'.$row_type["id"].'" col_id="'.$col_id.'" input_id="'.$input_count.'"></td>'; 

									$col_id++; $input_count++;
								}
								break;

							default:
								
								for($i = 1; $i <= $total_sizes; $i++) { 
									if($flag_new == 1){
										$value = $array_day[$row_type["id"]][$i][$detail];
									} else {$value=''; }

									echo '<td class="td_'.$col_id.'"><input class="input_arrow '.$detail.' '.$detail.'_'.$row_type["id"].'_'.$i.' '.$detail.'_'.$i.'" type="text" id="input_'.$input_count.'"  name="'.$detail.'_'.$row_type["id"].'_'.$i.'" row_id="'.$row_type["id"].'" col_id="'.$col_id.'" input_id="'.$input_count.'" size_type="'.$i.'" brand_type="'.$row_type["id"].'" value="'.$value.'"></td>'; $col_id++; $input_count++;
								}
								break;
						}
						
					}
					echo '<td>'.$row_type["type_brand"].'</td></tr>';
					$row_id++;
				}
			echo '</form>';
			

			echo '<form id="final_form"><tr  style="font-size:13px; font-weight:bold;"><td>कुल योग </td>';
			foreach ($in_details as $detail) {
				if($detail == 'old_stock' || $detail == 'initial_stock' || $detail == 'final_stock' ){
					for($i=1;$i<=$total_sizes;$i++) {
						if($flag_new == 1){
							$value = $array_day_final[$i][$detail];
						}  else {
							$value = $array_day_final[$i]["final_stock"];
						}

						echo '<td><input type="text" name="total_'.$detail.'_'.$i.'" id="total_'.$detail.'_'.$i.'" class="'.$detail.'" value="'.$value.'" readonly></td>';
					}
				} else if($detail != 'total_sum'){
					for($i=1;$i<=$total_sizes;$i++) {
						if($flag_new == 1){
							$value = $array_day_final[$i][$detail];
						}  else {
							$value = '';
						}

						echo '<td><input type="text" name="total_'.$detail.'_'.$i.'" id="total_'.$detail.'_'.$i.'" class="'.$detail.'" value="'.$value.'" readonly></td>';
					}
				} else {
					if($flag_new == 1){
						$value = $array_day_sum[0][$detail];
					} else {$value=''; }

					echo '<td><input type="text" name="total_'.$detail.'" id="total_'.$detail.'"  value="'.$value.'" readonly></td>';
				}
			}
			echo '</tr>';
			
		?>
	</table>

	<div align="center" style="margin:20px 0" id="final_fields">

		<table style=" margin:10px 50px 10px 10px; font-weight:bold;">
		<tr>
			<td><input type="text"  id="money_given" autocomplete="off" name="money_given" value="<?php echo $array_day_sum[0]["money_given"]?>" /></td><td>पैसा दिया गया</td>
			<td><input type="text"  id="money_name" autocomplete="off" name="money_name" value="<?php echo $array_day_sum[0]["money_name"]?>" /></td><td>नाम</td>
		</tr>
		<tr>
			<td><input type="text"  autocomplete="off" name="other_money" id="other_money" value="<?php echo $array_day_sum[0]["other_money"]?>" /></td><td>अन्य जमा</td>
			<td><input type="text"  autocomplete="off" name="remark" value="<?php echo $array_day_sum[0]["remark"]?>" /></td><td>रिमार्क</td>
		</tr>
		<tr>
			<td colspan="3"><input type="text" readonly id="final_total" autocomplete="off" name="final_total" value="<?php echo $array_day_sum[0]["final_total"]?>" /></td><td>कुल योग</td>
		</tr>
		
	</table>

	</form>

		<?php
			if($flag_new == 0){
				?>
					<button type="button" onclick="submit_english_form()" style="padding:5px 10px" id="button_submit">जमा करे</button>
				<?php
			}
		?>
	</div>

	<script type="text/javascript">
 	var total_type = 10;
 	var row_id =1;
 	var col_id = 1;
 	var input_id = 1;
 	var shift_id = 1;
 	var size_type;
	var brand_type;

 	function submit_english_form(){
 		var total = $(".english_form").length;
 		var count = 0;

 		if( ($("#total_old_stock_1").val() == '' || $("#total_old_stock_1").val() == 0) && ($("#total_old_stock_2").val() == '' || $("#total_old_stock_2").val() == 0) ){
 				alert('कृपया फार्म भरे ');
 			}
 			else {
 					$("#button_submit").html("जमा हो रहा है");
 					 	$(".english_form").each( function(){ 
						var val = $(this).serialize();
						$.post("process_beer.php?shop_id="+$("#shop_id").val()+"&current_id="+$("#current_id").val()+"&date_en="+$("#date_en").val()+"&month_en="+$("#month_en").val()+"&year_en="+$("#year_en").val() ,val, function(data){
						});
						count++;

						if(count == total){
							var val = $("#final_form").serialize();
							$.post("process_beer_final.php?shop_id="+$("#shop_id").val()+"&current_id="+$("#current_id").val()+"&date_en="+$("#date_en").val()+"&month_en="+$("#month_en").val()+"&year_en="+$("#year_en").val() ,val, function(data){
								if(data == 'success'){
										//window.location.replace('index.php?dis=<?php echo $current_district ?>&grp=<?php echo $current_group ?>&shp=<?php echo $current_shop+1; ?>');

										window.location.replace('index.php?dis=<?php echo $current_district ?>&grp=<?php echo $current_group ?>&shp=<?php echo $current_shop; ?>');
								}
							});
						}

					});
 			}


 	}
 	
 	function initialize() 
		{
			

			$(".input_arrow").focus( function(){
				$(this).select();
				if($(this).val()=='0') $(this).val('');
				row_id = $(this).attr('row_id');
				col_id = $(this).attr('col_id');
				input_id = $(this).attr('input_id');
				$("#tr_"+row_id).addClass('highlight');
				$(".td_"+col_id).addClass('highlight');
				shift_id = input_id;
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
			   
			    if(shift_id != input_id) $("#input_"+shift_id).focus();
			});

			// page specific functions
			$(".old_stock").keyup(function() {
			
			   var val = parseFloat($(this).val());
			   
			   size_type = $(this).attr('size_type');
			   brand_type = $(this).attr('brand_type');

			   var stock_in = parseFloat($(".stock_in_"+brand_type+"_"+size_type).val());
			   var stock_transfer = parseFloat($(".stock_transfer_"+brand_type+"_"+size_type).val());
			   
			   var f_val =0;

			   if(val) f_val = val;

			   if(stock_in) f_val += stock_in;

			   if(stock_transfer) f_val -= stock_transfer;

			   $(".initial_stock_" + brand_type+"_"+size_type).val(f_val);
			   
			   update_final_stock(size_type, brand_type);
			   update_total_detail('old_stock',size_type);
			
			});

			$(".stock_in").keyup(function() {
			
			   var val = parseFloat($(this).val());
			   
			   size_type = $(this).attr('size_type');
			   brand_type = $(this).attr('brand_type');

			   var old_stock = parseFloat($(".old_stock_"+brand_type+"_"+size_type).val());
			   var stock_transfer = parseFloat($(".stock_transfer_"+brand_type+"_"+size_type).val());
			   
			   var f_val =0;

			   if(val) f_val = val;

			   if(old_stock) f_val += old_stock;

			   if(stock_transfer) f_val -= stock_transfer;

			   $(".initial_stock_" + brand_type+"_"+size_type).val(f_val);
			   
			   update_final_stock(size_type, brand_type);
			   update_total_detail('stock_in',size_type);

			
			});


			$(".stock_transfer").keyup(function() {
			
			   var val = parseFloat($(this).val());
			   
			   size_type = $(this).attr('size_type');
			   brand_type = $(this).attr('brand_type');

			   var old_stock = parseFloat($(".old_stock_"+brand_type+"_"+size_type).val());
			   var stock_in = parseFloat($(".stock_in_"+brand_type+"_"+size_type).val());
			   
			   var f_val =0;

			   if(val) f_val = -val;

			   if(old_stock) f_val += old_stock;

			   if(stock_in) f_val += stock_in;

			   $(".initial_stock_" + brand_type+"_"+size_type).val(f_val);
			   
			   update_final_stock(size_type, brand_type);
			   update_total_detail('stock_transfer',size_type);
				
			});


			$(".sale").keyup(function() {
			
			   var val = parseFloat($(this).val());
			   
			   size_type = $(this).attr('size_type');
			   brand_type = $(this).attr('brand_type');

			   var rate = parseFloat($(".rate_"+brand_type+"_"+size_type).val());
			   
			   var total = 0;
			   if(rate && val) var total = rate*val;
			   $(".total_cost_"+ brand_type+"_"+size_type).val(total);
			   
			   update_final_stock(size_type, brand_type);
			   update_sum(brand_type);
			   update_total_detail('sale',size_type);
			   update_total_detail('total_cost',size_type);
				
			});

			$(".rate").keyup(function() {
			
			   var val = parseFloat($(this).val());
			   
			   size_type = $(this).attr('size_type');
			   brand_type = $(this).attr('brand_type');

			   var sale = parseFloat($(".sale_"+brand_type+"_"+size_type).val());
			   
			   var total = 0;
			   if(sale && val) var total = sale*val;
			   $(".total_cost_"+ brand_type+"_"+size_type).val(total);
			   
			   update_final_stock(size_type, brand_type);
			   update_sum(brand_type);

			   update_total_detail('total_cost',size_type);
				
			});

			$("#money_given").keyup(function() {
				update_final_total();
			});

			$("#other_money").keyup(function() {
				update_final_total();
			});

			
		}

	function update_final_stock(size_type, brand_type){

	   var initial_stock = parseFloat($(".initial_stock_"+brand_type+"_"+size_type).val());
	   var sale = parseFloat($(".sale_"+brand_type+"_"+size_type).val());
	   
	   var f_val =0;

	   if(initial_stock) f_val += initial_stock;

	   if(sale) f_val -= sale;

	   $(".final_stock_"+brand_type+"_"+size_type).val(f_val);
	   update_total_detail('final_stock',size_type);
	   update_total_detail('initial_stock',size_type);
	}

	function update_total_detail(field,size_type){
		var total = 0;
		if(size_type == 0){
			var class_sum = field;
		} else {
			var class_sum = field+'_'+size_type;
		}

		$("."+class_sum).each( function(){
			var co = parseFloat($(this).val());
			if(co) total += co;
		});



		$("#total_"+class_sum).val(total);
	}

	function update_sum(brand_type){
		var total = 0;

		$("#tr_"+brand_type +" .total_cost").each( function(){
			var co = parseFloat($(this).val());
			if(co) total += co;
		});

		$("#total_sum_"+brand_type).val(total);
	   	update_total_detail('total_sum',0);
	   	update_final_total();

	}

	function update_final_total(){
		var total = parseFloat($("#total_total_sum").val());
		if(parseFloat($("#money_given").val())) total -= parseFloat($("#money_given").val());
		if(parseFloat($("#other_money").val())) total += parseFloat($("#other_money").val());

		$("#final_total").val(total);
	}

 </script>

<?php		
} else {
 echo "<div style='background:#FFC7C7; font-size:16px;padding:5px' align='center'>कृपया ग्रुप चुनें</div>";
// echo "<div style='background:#FFC7C7; font-size:16px;padding:5px' align='center'>यह ग्रुप समाप्त हो गया है, कृपया दूसरा ग्रुप चुनें</div>";

}