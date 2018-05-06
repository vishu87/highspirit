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
	$res_shop = mysql_query("SELECT id, name from shops where district='$current_district' and group_name = '$current_group' ");
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
	</style>
<div style="font-size:14px;">
	<a href="javascript:;" style="font-size:14px; margin-left:10px; padding:7px 10px; background:#ccc" onclick="show_shop_panel()">दुकानो की लिस्ट</a>
	<div style="padding:7px 10px; background:#ccc; display:none;" id="shop_list">
		<?php  
			mysql_query('SET character_set_results=utf8');
			$sql_shop = mysql_query("SELECT name from shops where district='$current_district' and group_name = '$current_group' ");
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

$check_old = mysql_query("SELECT timestamp_day from sale where shop_id='$row_shop[id]' order by timestamp_day asc limit 1");
$row_check_old = mysql_fetch_array($check_old);

if($row_check_old["timestamp_day"] > $time_now){
	echo "<div style='background:#FFC7C7; font-size:16px;padding:5px' align='center'>इस दिनांक की प्रविष्टि आप नहीं कर सकते</div>";

	die();
}

$check_skip = mysql_query("SELECT timestamp_day from sale where shop_id='$row_shop[id]' order by timestamp_day desc limit 1");
if(mysql_num_rows($check_skip) > 0){
	$row_skip = mysql_fetch_array($check_skip);
	$timestamp_last = $row_skip["timestamp_day"];
	$timestamp_shd_day = $timestamp_last + 86400;
	if($time_now > $timestamp_shd_day){
		echo "<div style='background:#FFC7C7; font-size:16px;padding:5px' align='center'>पहले दिनांक ".date("d M Y",$timestamp_shd_day)." की प्रविष्टि करे</div>";
		die();
	}
}

$sql_new = mysql_query("SELECT id from sale where shop_id='$row_shop[id]' and timestamp_day = $time_now ");
if(mysql_num_rows($sql_new) > 0){
	$flag_new =1;
	if($priv == 0) {
		echo "<div style='background:#FFC7C7; font-size:16px;padding:5px' align='center'>इस दिनांक की प्रविष्टि को आप बदल नहीं सकते</div>";
		die();
	} else {
		echo "<div style='background:#FFC7C7; font-size:16px;padding:5px' align='center'>इस दिनांक की प्रविष्टि को आप देख सकते हैं, लेकिन बदल नहीं सकते </div>";
		$sql_ck_last = mysql_query("SELECT timestamp_day from sale where shop_id='$row_shop[id]' order by timestamp_day desc");
		$row_ck_last = mysql_fetch_array($sql_ck_last);
		if($row_ck_last["timestamp_day"] == $time_now)echo '<div align="center" style="margin:20px;" ><a href="process.php?current_shop='.$current_shop.'&amp;type=3&amp;shop_id='.$row_shop["id"].'&amp;timestamp='.$time_now.'&amp;dis='.$current_district.'&amp;grp='.$current_group.'" style="padding:10px; background:#000; color:#fff;">यह डॉटा डिलीट कर दे</a></div>';
	}
}

$ar_details = array("पिछ्ला बाकी","आमद","ट्रांसफर आमद","योग","दुकान बाकी","ट्रांसफर","बिक्री","दर","कीमत","थोक बिक्री","थोक दर","थोक कीमत");
$in_details = array("old_stock","stock_in","stock_transfer_in","initial_stock","final_stock","stock_transfer_out","sale","rate","total_cost","sale_ws","rate_ws","total_ws");
?>
<form id="submit_form" action="process.php?type=2&amp;dis=<?php echo $current_district;?>&amp;grp=<?php echo $current_group;?>" method="post">
	<input type="hidden" name="date_en" value="<?php echo $date_en?>">
	<input type="hidden" name="month_en" value="<?php echo $month_en?>">
	<input type="hidden" name="year_en" value="<?php echo $year_en?>">
	<input type="hidden" name="shop_id" value="<?php echo $row_shop["id"]; ?>">
	<input type="hidden" name="current_id" value="<?php echo $current_shop; ?>">
	<table cellspacing="0" cellpaddin="0" width="100%">
		
		<tr>
			<th>विवरण</th>
			<?php 
			
			$array_type = array();
				mysql_query('SET character_set_results=utf8');
				$query_type = mysql_query("SELECT type_brand, id from types");
				while($row_type = mysql_fetch_array($query_type)){
					echo '<th class="td_'.$row_type["id"].'">'.$row_type["type_brand"].'</th>';
					array_push($array_type, $row_type["id"]);
				}
			?>
		</tr>
		<?php
		$count_detail=0;
		$input_count = 0;
			foreach ($ar_details as $detail) {
				echo '<tr id="tr_'.$count_detail.'">';
					echo '<td>'.$detail.'</td>';
					foreach ($array_type as $type) {
						

						$sql_fetch = mysql_query("SELECT ".$in_details[$count_detail]." from sale where shop_id='$row_shop[id]' and type_id='$type' and timestamp_day='$time_now' ");
						$row_fetch = mysql_fetch_array($sql_fetch);

						echo '<td class="td_'.$type.'"><input type="text" autocomplete="off" class="input_arrow '.$in_details[$count_detail].' '.$in_details[$count_detail].'_'.$type.'" style="width:50px;" name="'.$in_details[$count_detail].'_'.$type.'" id="input_'.$input_count.'" row_id="'.$count_detail.'" col_id="'.$type.'" input_id="'.$input_count.'" type_id="'.$type.'" ';
						if($in_details[$count_detail] == 'final_stock' || $in_details[$count_detail] == 'total_cost' || $in_details[$count_detail] == 'initial_stock' || $in_details[$count_detail] == 'total_ws'){
							echo 'readonly';
						} else {
							$input_count++;
						}

						if($in_details[$count_detail] == 'old_stock' || $in_details[$count_detail] == 'initial_stock' || $in_details[$count_detail] == 'final_stock'){
							$query_old = mysql_query("SELECT final_stock from sale where shop_id='$row_shop[id]' and type_id='$type' and timestamp_day='$time_ytr' ");
							if(mysql_num_rows($query_old) > 0 && mysql_num_rows($sql_fetch) == 0){
								$row_old = mysql_fetch_array($query_old);
								if($row_old["final_stock"]!=0)
									echo ' readonly value="'.$row_old["final_stock"].'">';
								else
									echo ' value="'.$row_old["final_stock"].'">';
							} else {
								echo ' value="'.$row_fetch[$in_details[$count_detail]].'">';
							}

						} else if($in_details[$count_detail] == 'rate'){
							if($flag_new == 0){
								$query_old = mysql_query("SELECT rate from sale where shop_id='$row_shop[id]' and type_id='$type' and timestamp_day='$time_ytr' ");
								if(mysql_num_rows($query_old) > 0){
									$row_old = mysql_fetch_array($query_old);
										echo ' value="'.$row_old["rate"].'">';
								} else {
									echo ' value="'.$row_fetch[$in_details[$count_detail]].'">';
								}
							} else {
								$query_old = mysql_query("SELECT rate from sale where shop_id='$row_shop[id]' and type_id='$type' and timestamp_day='$time_now' ");
								if(mysql_num_rows($query_old) > 0){
									$row_old = mysql_fetch_array($query_old);
										echo ' value="'.$row_old["rate"].'">';
								} else {
									echo ' value="'.$row_fetch[$in_details[$count_detail]].'">';
								}
							}
						} else
						echo ' value="'.$row_fetch[$in_details[$count_detail]].'">';
						echo '</td>';
					
					}
				echo '</tr>';

				$count_detail++;
			}
		?>
	</table>
	<?php
		$sql_fetch = mysql_query("SELECT * from sale_final where shop_id='$row_shop[id]'  and timestamp_day='$time_now' ");
		$row_fetch = mysql_fetch_array($sql_fetch);
	?>

	<table style="float:left; margin:10px 50px 10px 10px;">
		<tr>
			<td><input type="text" autocomplete="off" name="sada_total" id="sada_total" value="<?php echo $row_fetch["sada_total"]?>" readonly></td><td>सादा बिक्री खाते जमा</td>
		</tr>
		<tr>
			<td><input type="text" autocomplete="off" name="masala_total" id="masala_total" value="<?php echo $row_fetch["masala_total"]?>" readonly></td><td>मसाला बिक्री खाते जमा</td>
		</tr>
		<tr>
			<td><input type="text" autocomplete="off" name="ws_total" id="ws_total" value="<?php echo $row_fetch["ws_total"]?>" readonly></td><td>थोक बिक्री खाते जमा</td>
		</tr>
		<tr>
			<td><input type="text" autocomplete="off" name="chikna" id="chikna" value="<?php echo $row_fetch["chikna"]?>" ></td><td>चिकना खाते</td>
		</tr>
		<tr>
			<td><input type="text" autocomplete="off" name="jurmana" id="jurmana" value="<?php echo $row_fetch["jurmana"]?>" ></td><td>जुर्माना खाते</td>
		</tr>
		<tr>
			<td><input type="text" autocomplete="off" name="other" id="other" value="<?php echo $row_fetch["other"]?>" ></td><td>अन्य जमा</td>
		</tr>
		<tr>
			<td><input type="text" autocomplete="off" name="input_total" id="input_total" value="<?php echo $row_fetch["input_total"]?>" readonly></td><td>योग</td>
		</tr>
	</table>
	<table style="float:left; margin:10px 50px 10px 10px;">
		<tr>
			<td> <input type="text" autocomplete="off" id="normal_sale" name="normal_sale" value="<?php echo $row_fetch["normal_sale"]?>" readonly/></td><td>कुल बिक्री</td>
			
		</tr>
		<tr>
			<td> <input type="text" autocomplete="off" id="ws_sale" name="ws_sale" value="<?php echo $row_fetch["ws_sale"]?>" readonly/></td><td>थोक बिक्री</td>
		</tr>
		<tr>
			<td> <input type="text" autocomplete="off" id="final_sale" name="final_sale" value="<?php echo $row_fetch["final_sale"]?>" readonly/></td><td>कुल बिक्री + थोक बिक्री</td>
		</tr>
		<tr>
			<td><input type="text" autocomplete="off" id="commission" name="commission" value="<?php 
			if($flag_new == 0){
				$sql_fetch_com = mysql_query("SELECT commission from sale_final where shop_id='$row_shop[id]'  and timestamp_day='$time_ytr' ");
				if(mysql_num_rows($sql_fetch_com) > 0){
					$row_fetch_com = mysql_fetch_array($sql_fetch_com);
					echo $row_fetch_com["commission"];
				} else echo '0';
			} else {
				echo $row_fetch["commission"];
			}

			?>"/></td><td>कमीशन</td>
		</tr>
		<tr>
			<td><input type="text"  autocomplete="off" id="final_commission" name="total_commission" readonly value="<?php echo $row_fetch["total_commission"]?>"/></td><td>कुल कमीशन</td>
		</tr>
		<tr>
			<td><input type="text" autocomplete="off" id="final_commission_round" name="commission_round" value="<?php echo $row_fetch["commission_round"]?>" /></td><td>कुल कमीशन राउण्ड आफ</td>
		</tr>
		<tr>
			<td><input type="text" autocomplete="off" id="buttat" name="buttat" value="<?php echo $row_fetch["buttat"]?>" /></td><td>बुतात</td>
		</tr>
		<tr>
			<td><input type="text" autocomplete="off" id="bhada" name="bhada" value="<?php echo $row_fetch["bhada"]?>" /></td><td>भाड़ा</td>
		</tr>
	</table>

	<table style="float:left; margin:10px 50px 10px 10px; font-weight:bold;">
		<tr>
			<td><input type="text"  id="money_given" autocomplete="off" name="money_given" value="<?php echo $row_fetch["money_given"]?>" /></td><td>पैसा दिया गया</td>
		</tr>
		<tr>
			<td><input type="text"  id="money_name" autocomplete="off" name="money_name" value="<?php echo $row_fetch["money_name"]?>" /></td><td>नाम</td>
		</tr>
		<tr>
			<td><input type="text" readonly id="final_bill" autocomplete="off" name="final_total" value="<?php echo $row_fetch["final_total"]?>" /></td><td>कुल योग</td>
		</tr>
		<tr>
			<td><input type="text"  autocomplete="off" name="remark" value="<?php echo $row_fetch["remark"]?>" /></td><td>रिमार्क</td>
		</tr>
	</table>

	<div align="center">
		<?php 
			if($flag_new == 0){
			?>
				<input type="button" onclick="check_submit()" value="जमा करें" style="font-size:18px; float:right; margin:10px; padding:20px;">
		<?php } ?>
	</div>
	
</form>

 <script type="text/javascript">
 	var total_type = <?php echo sizeof($array_type);?>;
 	var row_id =0;
 	var col_id = 0;
 	var input_id = 0;
 	var shift_id = 1;
 	var type_id;

 	function check_submit(){
 		if($("#commission").val() != '' && $("#commission").val() != 0){
 			if($("#final_bill").val() == '' || $("#final_commission_round").val() == '') {
 			alert('कृपया फार्म पूरा भरे');
	 		} else {
	 			$("#submit_form").submit();
	 		}
 		} else {
 			alert('कृपया कमीशन भरे');
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


			$(".old_stock").keyup(function() {
			   var val = parseFloat($(this).val());
			   type_id = $(this).attr('type_id');
			   stock_in = parseFloat($(".stock_in_"+type_id).val());
			   stock_transfer_in = parseFloat($(".stock_transfer_in_"+type_id).val());
			   var f_val =0;
			    if(val) f_val = val;

			    if(stock_in) f_val += stock_in;

			    if(stock_transfer_in) f_val += stock_transfer_in;

			   $(".initial_stock_" + type_id).val(f_val);

			   update_final_stock(type_id);

			});

			function update_final_stock(ty_id){
				var ini_stock = parseFloat($(".initial_stock_" + ty_id).val());
				var trans = parseFloat($(".stock_transfer_out_" + ty_id).val());
				var bikri = parseFloat($(".sale_" + ty_id).val());
				var thok_bikri = parseFloat($(".sale_ws_" + ty_id).val());
				var final_val = 0;

				if(ini_stock) final_val += ini_stock;
				if(trans) final_val -= trans;
				if(bikri) final_val -= bikri;
				if(thok_bikri) final_val -= thok_bikri;

				$(".final_stock_" + ty_id).val(final_val);
			}

			function update_cost(ty_id){
				var bikri = parseFloat($(".sale_" + ty_id).val());
				var dar = parseFloat($(".rate_" + ty_id).val());
				var final_val = 0;

				if(bikri && dar) final_val = bikri*dar;

				$(".total_cost_" + ty_id).val(final_val);

				update_all_cost();

			}

			function update_cost_ws(ty_id){
				var bikri = parseFloat($(".sale_ws_" + ty_id).val());
				var dar = parseFloat($(".rate_ws_" + ty_id).val());
				var final_val = 0;

				if(bikri && dar)final_val = bikri*dar;
				//alert(final_val);
				$(".total_ws_" + ty_id).val(final_val);
				update_all_cost()

			}

			function update_all_cost(){
				var final_val_tot = 0;
				var count  =1;
				$(".total_cost").each( function(){
					var co = parseFloat($(this).val());
					if(co) final_val_tot += co;
					
					if(count == 3) return false;
					count++;
				});

				$("#sada_total").val(final_val_tot);
				
				//MASALA TOTAL

				final_val_tot = 0;
				count  =1;
				$(".total_cost").each( function(){
					if(count > 3){
						var co = parseFloat($(this).val());
						if(co) final_val_tot += co;
					}
					count++;
				});

				$("#masala_total").val(final_val_tot);
				

				//THOK TOTAL

				final_val_tot = 0;
				count  =1;
				$(".total_ws").each( function(){
						var co = parseFloat($(this).val());
						if(co) final_val_tot += co;
					count++;
				});

				$("#ws_total").val(final_val_tot);

				//FINAL TOTAL

				var input_total = 0;
				if($("#sada_total").val()) input_total += parseFloat($("#sada_total").val());
				if($("#masala_total").val()) input_total += parseFloat($("#masala_total").val());
				if($("#ws_total").val()) input_total += parseFloat($("#ws_total").val());
				if($("#chikna").val()) input_total += parseFloat($("#chikna").val());
				if($("#jurmana").val()) input_total += parseFloat($("#jurmana").val());
				if($("#other").val()) input_total += parseFloat($("#other").val());

				$("#input_total").val(input_total);

				update_final_bill();
			}

			function update_total_sale(){
				var final_sale = 0;
				var final_sale_ws = 0;

				$(".sale").each( function(){
					var co = parseFloat($(this).val());
					if(co) final_sale += co; 	
				});
				$("#normal_sale").val(final_sale);

				$(".sale_ws").each( function(){
					var co = parseFloat($(this).val());
					if(co) final_sale_ws += co; 	
				});
				$("#ws_sale").val(final_sale_ws);

				$("#final_sale").val(final_sale + final_sale_ws);

				var commission =0;
				if(parseFloat($("#commission").val())) commission = parseFloat($("#commission").val());

				var fin_com = commission*(final_sale);
				$("#final_commission").val(fin_com);

				$("#final_commission_round").val(Math.round(fin_com));

				update_final_bill();
			}

			function update_final_bill(){
				var final_bill =0;
				if(parseFloat($("#input_total").val())) final_bill += parseFloat($("#input_total").val());

				if(parseFloat($("#final_commission_round").val())) final_bill -= parseFloat($("#final_commission_round").val());

				if(parseFloat($("#money_given").val())) final_bill -= parseFloat($("#money_given").val());

				if(parseFloat($("#buttat").val())) final_bill -= parseFloat($("#buttat").val());

				if(parseFloat($("#bhada").val())) final_bill -= parseFloat($("#bhada").val());

				$("#final_bill").val(final_bill);
			}

			$(".stock_transfer_out").keyup(function() {
			   type_id = $(this).attr('type_id');
			   update_final_stock(type_id);
			});

			
			$(".sale").keyup(function() {
			   type_id = $(this).attr('type_id');
			   update_final_stock(type_id);
			   update_cost(type_id);
			   update_total_sale();
			});

			$(".sale_ws").keyup(function() {
			   type_id = $(this).attr('type_id');
			   update_final_stock(type_id);
			   update_cost_ws(type_id);
			   update_total_sale();
			});


			$(".rate").keyup(function() {
			   type_id = $(this).attr('type_id');
			   update_final_stock(type_id);
			   update_cost(type_id);
			});

			$(".rate_ws").keyup(function() {
			   type_id = $(this).attr('type_id');
			   update_final_stock(type_id);
			   update_cost_ws(type_id);
			});

			$("#chikna").keyup(function() {
			   update_all_cost();
			});

			$("#jurmana").keyup(function() {
			   update_all_cost();
			});

			$("#other").keyup(function() {
			   update_all_cost();
			});

			$("#buttat").keyup(function() {
			   update_all_cost();
			});

			$("#bhada").keyup(function() {
			   update_all_cost();
			});

			$("#commission").keyup(function() {
			    commission = parseFloat($("#commission").val());

				var fin_com = commission*$("#normal_sale").val();
				$("#final_commission").val(fin_com);
				$("#final_commission_round").val(Math.round(fin_com));

				update_final_bill();
			});

			$("#final_commission_round").keyup(function() {
			    update_final_bill();
			});

			$("#money_given").keyup(function() {
			    update_final_bill();
			});

			$(".stock_in").keyup(function() {
			   var val = parseFloat($(this).val());
			   type_id = $(this).attr('type_id');
			   old_stock = parseFloat($(".old_stock_"+type_id).val());
			   stock_transfer_in = parseFloat($(".stock_transfer_in_"+type_id).val());
			      var f_val =0;
			    if(val) f_val = val;

			    if(old_stock) f_val += old_stock;

			    if(stock_transfer_in) f_val += stock_transfer_in;



			   $(".initial_stock_" + type_id).val(f_val);
			   update_final_stock(type_id);
			});

			$(".stock_transfer_in").keyup(function() {
			   var val = parseFloat($(this).val());
			   type_id = $(this).attr('type_id');
			    old_stock = parseFloat($(".old_stock_"+type_id).val());
			   stock_in = parseFloat($(".stock_in_"+type_id).val());
			     var f_val =0;
			    if(val) f_val = val;

			     if(old_stock) f_val += old_stock;

			    if(stock_in) f_val += stock_in;



			   $(".initial_stock_" + type_id).val(f_val);
			   update_final_stock(type_id);
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
 <?php 

} else {
 echo "<div style='background:#FFC7C7; font-size:16px;padding:5px' align='center'>कृपया ग्रुप चुनें</div>";
// echo "<div style='background:#FFC7C7; font-size:16px;padding:5px' align='center'>यह ग्रुप समाप्त हो गया है, कृपया दूसरा ग्रुप चुनें</div>";

}