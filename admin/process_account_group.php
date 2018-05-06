<?php
  set_time_limit(3000);
  include('top.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>High Spirit</title>
	<meta content="text/html; charset=UTF-8" http-equiv="content-type">
</head>
<body style="font-size:12px; font-family:arial">
<?php
$dis_array = array("","अम्बेडकर नगर","फैजाबाद", "सुल्तानपुर");
$dis_names=array("","अकबरपुर","जलालपुर","भीटी","टाण्डा","बसखारी","जहांगीरगंज","फैजाबाद","सुल्तानपुर");
//$type = mysql_real_escape_string($_GET["type"]);
$type =3;

if($_POST["date_pre"]) {
	$time_pre =  strtotime($_POST["month_pre"].'/'.$_POST["date_pre"].'/'.$_POST["year_pre"]);
} else {
	$time_pre = '';
}
//echo date("d M y",$time_pre);

if($_POST["date_nxt"]){
	$time_nxt =  strtotime($_POST["month_nxt"].'/'.$_POST["date_nxt"].'/'.$_POST["year_nxt"]);
} else {
	$time_nxt = '';
}

if($time_pre && $time_nxt ){
	if($time_pre > $time_nxt){
		echo 'Invalid Dates';
		die();
	}
}

$str_money = '';

$grp = mysql_real_escape_string($_POST["grp"]);
$type = mysql_real_escape_string($_POST["type"]);

$priv =1;
$details_day = mysql_real_escape_string($_POST["detail_day"]);

$array_type = array();
$total_brands_id = array();

mysql_query('SET character_set_results=utf8');
$query_type = mysql_query("SELECT type_brand, id from types");
while($row_type = mysql_fetch_array($query_type)){
	$array_type[$row_type["id"]] = $row_type["type_brand"];
	array_push($total_brands_id, $row_type["id"]);

}


if($priv == 1) { // all district

	if($time_pre){
		$time_start = $time_pre; 
	} else {
		$quer_start = mysql_query("SELECT timestamp_day from sale order by timestamp_day asc limit 1");
		$row_start = mysql_fetch_array($quer_start);
		$time_start = $row_start["timestamp_day"];

	}

	if($time_nxt){
		$time_end = $time_nxt; 
	} else {
		$quer_end = mysql_query("SELECT timestamp_day from sale order by timestamp_day desc limit 1");
		$row_end = mysql_fetch_array($quer_end);
		$time_end = $row_end["timestamp_day"];
	}

	$shops = array();

	$sql_shop = mysql_query("SELECT id from shops where group_name = '$grp' order by id asc  ");
	while ($fetch_shop = mysql_fetch_array($sql_shop)) {
		array_push($shops, $fetch_shop["id"]);
	}
	$shop_string = implode(',', $shops);

	$total_opening = array();
	$total_closing = array();


?>
			<table cellspacing="0" cellpadding="10" border="1" style="font-size:14px; margin-bottom:10px">
				<tr>
					<td>ग्रुप : <?php echo ($grp != 0)?$dis_names[$grp]:'सभी'; ?></td>
					<td>दुकान : <?php echo 'सभी'; ?></td>
					<td>दिनांक : <?php echo date("d-M-y",$time_start).' से '.date("d-M-y",$time_end).' तक'; ?></td>
					<td><button onclick="window.print()">Print</button></td>
				</tr>
			</table>
			<table border="1" cellpadding="4" cellspacing="0">
			<thead>
				<tr>
					<th rowspan="2">दिनांक</th>
					<th rowspan="2">दुकान</th>
					<th rowspan="2">सादा बिक्री<br>खाते जमा</th>
					<th rowspan="1" colspan="2">मसाला बिक्री<br>खाते जमा</th>
					<th rowspan="2">थोक बिक्री<br>खाते जमा</th>
					<th rowspan="2">चिकना</th>
					<th rowspan="2">जुर्माना</th>
					<th rowspan="2">अन्य जमा</th>
					<th rowspan="2">ब्रांड</th>
					<th rowspan="2">पिछ्ला बाकी</th>
					<th rowspan="2">आमद</th>
					<th rowspan="2">ट्रांसफर<br>आमद</th>
					<th rowspan="2">ट्रांसफर</th>
					<th rowspan="2">बिक्री</th>
					<th rowspan="2">थोक बिक्री</th>
					<th rowspan="2">दर</th>
					<th rowspan="2">थोक दर</th>
					<th rowspan="2">कमीशन</th>
					<th rowspan="2">कुल कमीशन</th>
					<th rowspan="2">बुतात</th>
					<th rowspan="2">भाड़ा</th>
					<th rowspan="2">पैसा दिया गया</th>
					<th rowspan="2">कुल योग</th>
				</tr>
				<tr>
					<th>36%</th>
					<th>42.8%</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$array_fin = array("date","shop_name","sada_total","masala_total_36","masala_total_42","ws_total","chikna","jurmana","other");
					$array_fin_last = array("commission","commission_round","buttat","bhada","money_given","final_total");

					$array_brand = array("type_brand","old_stock","stock_in","stock_transfer_in","stock_transfer_out","sale","sale_ws","rate","rate_ws");
					
					foreach ($array_fin as $fin) {
						$total_array[$fin] = array();
					}

					foreach ($array_fin_last as $fin) {
						$total_array[$fin] = array();
					}

					foreach($total_brands_id as $brand_id){
						foreach ($array_brand as $key) {
							$total_array['brand'.$brand_id.$key] = array();
						}

					}
				
					foreach ($shops as $shop) {
						mysql_query('SET character_set_results=utf8');
						$query_name = mysql_query("SELECT name from shops where id='$shop' limit 1 ");
						$row_name = mysql_fetch_array($query_name);
						$shop_name =  $row_name["name"];

						foreach ($array_fin as $fin) {
							$day_array[$fin] = array();
						}

						foreach ($array_fin_last as $fin) {
							$day_array[$fin] = array();
						}

						foreach($total_brands_id as $brand_id){
							foreach ($array_brand as $key) {
								$day_array['brand'.$brand_id.$key] = array();
							}
						}

						$opening = array();
						$closing = array();

						for ($time=$time_start; $time <= $time_end ; $time += 86400 ) { 

							
							
							$query_brands = mysql_query("SELECT id from sale where shop_id='$shop' and timestamp_day='$time' ");
							$total_brands = mysql_num_rows($query_brands);

							$total_masala_36 = 0;
							$query_masala_36 = mysql_query("SELECT sale.total_cost from sale inner join types on sale.type_id = types.id where sale.shop_id='$shop' and sale.timestamp_day='$time' and types.masala_type = '36' ");
							while ($row_masala_36 = mysql_fetch_array($query_masala_36)) {
								$total_masala_36 += $row_masala_36["total_cost"];
							}

							$total_masala_42 = 0;
							$query_masala_42 = mysql_query("SELECT sale.total_cost from sale inner join types on sale.type_id = types.id where sale.shop_id='$shop' and sale.timestamp_day='$time' and types.masala_type = '42' ");
							while ($row_masala_42 = mysql_fetch_array($query_masala_42)) {
								$total_masala_42 = $total_masala_42 + $row_masala_42["total_cost"];
							}

						echo '<tr ';
						if($details_day == 0) echo ' style="display:none;" ';
						echo '>';
						foreach ($array_fin as $key) {

							$query_final_sale = mysql_query("SELECT sada_total,ws_total, chikna, jurmana,other from sale_final where shop_id='$shop' and timestamp_day = '$time' ");
							$row_final_sale = mysql_fetch_array($query_final_sale);

							switch ($key) {
								case 'date':
									echo '<td rowspan="'.$total_brands.'">'.date("d-m-y", $time).'</td>';
									break;
								case 'shop_name':
									echo '<td rowspan="'.$total_brands.'" >'.$shop_name.'</td>';
									break;
								case 'masala_total_36':
									array_push($day_array[$key], $total_masala_36);
									array_push($total_array[$key], $total_masala_36);
									echo '<td rowspan="'.$total_brands.'">'.$total_masala_36.'</td>';
									break;
								case 'masala_total_42':
									array_push($day_array[$key], $total_masala_42);
									array_push($total_array[$key], $total_masala_42);
									echo '<td rowspan="'.$total_brands.'">'.$total_masala_42.'</td>';
									break;
								default:
									array_push($day_array[$key], $row_final_sale[$key]);
									array_push($total_array[$key], $row_final_sale[$key]);
									echo '<td rowspan="'.$total_brands.'">'.$row_final_sale[$key].'</td>';
									break;
							}
						}

						mysql_query('SET character_set_results=utf8');
						$query_sale = mysql_query("SELECT sale.*, types.type_brand from sale inner join types on sale.type_id = types.id where sale.shop_id='$shop' and sale.timestamp_day='$time' order by type_id asc ");
						$count_brands = 0;

						while ($row_sale = mysql_fetch_array($query_sale)) {

							if($count_brands != 0){
								echo '<tr ';
								if($details_shop == 0) echo ' style="display:none;" ';
								echo '>';
							}

							foreach ($array_brand as $brand) {

								switch ($brand) {
									case 'sale':
										array_push($day_array['brand'.$row_sale["type_id"].$brand], $row_sale[$brand]);
										array_push($total_array['brand'.$row_sale["type_id"].$brand], $row_sale[$brand]);
										echo '<td>'.$row_sale[$brand].'</td>';
										break;
									
									case 'rate':
										if($row_sale[$brand] != 0) array_push($day_array['brand'.$row_sale["type_id"].$brand], $row_sale[$brand]);
										if($row_sale[$brand] != 0) array_push($total_array['brand'.$row_sale["type_id"].$brand], $row_sale[$brand]);
										echo '<td>'.$row_sale[$brand].'</td>';
										break;

									case 'rate_ws':
										if($row_sale[$brand] != 0) array_push($day_array['brand'.$row_sale["type_id"].$brand], $row_sale[$brand]);
										if($row_sale[$brand] != 0) array_push($total_array['brand'.$row_sale["type_id"].$brand], $row_sale[$brand]);
										echo '<td>'.$row_sale[$brand].'</td>';
										break;

									case 'old_stock':
										if($row_sale["stock_in"] == -1){
											$row_sale["old_stock"] += $row_sale["stock_in"];
											$row_sale["stock_in"] = 0;
										}

										if($time == $time_start) { $opening[$row_sale["type_id"]] = $row_sale[$brand]; $total_opening[$row_sale["type_id"]] += $opening[$row_sale["type_id"]];}

										if($closing[$row_sale["type_id"]]) $total_closing[$row_sale["type_id"]] -= $closing[$row_sale["type_id"]];

										$total_closing[$row_sale["type_id"]] += $row_sale["final_stock"];

										$closing[$row_sale["type_id"]] = $row_sale["final_stock"];
										
										array_push($total_array['brand'.$row_sale["type_id"].$brand], $row_sale[$brand]);
										echo '<td>'.$row_sale[$brand].'</td>';
										break;

									default:
										array_push($day_array['brand'.$row_sale["type_id"].$brand], $row_sale[$brand]);
										array_push($total_array['brand'.$row_sale["type_id"].$brand], $row_sale[$brand]);
										echo '<td>'.$row_sale[$brand].'</td>';
										break;
								}
							}

							if($count_brands != 0){
								echo '</tr>';
							} else {
								$query_final_sale2 = mysql_query("SELECT commission, commission_round, final_total, bhada, buttat, money_given, money_name from sale_final where shop_id='$shop' and timestamp_day = '$time' limit 1 ");
								$row_final_sale2 = mysql_fetch_array($query_final_sale2);

								foreach ($array_fin_last as $key) {
									switch ($key) {
										case 'commission_round':
											array_push($day_array[$key], $row_final_sale2[$key]);
											array_push($total_array[$key], $row_final_sale2[$key]);
											echo '<td>'.$row_final_sale2[$key].'</td>';
											break;

										default:
											if($key == 'money_given'){
												if($row_final_sale2[$key] > 0){
													$str_money .= $shop_name.' / '.date("d-m-y",$time).' / '.$row_final_sale2["money_given"].'Rs / '.$row_final_sale2["money_name"].'<br>';
												}
											}
											array_push($day_array[$key], $row_final_sale2[$key]);
											array_push($total_array[$key], $row_final_sale2[$key]);
											echo '<td rowspan="'.$total_brands.'">'.$row_final_sale2[$key].'</td>';
											break;
									}
								}

								echo '</tr>';
							}
							
							$count_brands++;
						}

					} //end all day


					//shop_total

				if(sizeof($shops) > 1){

					$sql = "SELECT distinct type_id from sale where shop_id = '$shop' and timestamp_day between $time_start and $time_end ";
					//echo $sql;
					$query_brands_daily = mysql_query($sql);
					$total_brands = mysql_num_rows($query_brands_daily);
					//$total_brands =8;
					$total_brands_id_daily = array();

					while ($row_daily_brand = mysql_fetch_array($query_brands_daily)) {
						array_push($total_brands_id_daily, $row_daily_brand["type_id"]);
					}

					echo '<tr style="font-weight:bold; border:2px solid #000">';
						foreach ($array_fin as $key) {

							switch ($key) {
								case 'date':
									echo '<td rowspan="'.$total_brands.'"></td>';
									break;
								case 'shop_name':
									echo '<td rowspan="'.$total_brands.'" >'.$shop_name.' दुकान का कुल</td>';
									break;
								default:
									echo '<td rowspan="'.$total_brands.'">'.array_sum($day_array[$key]).'</td>';
									break;
							}
						}
					// daily_total brand
						$count_brands = 0;
						foreach ($total_brands_id_daily as $brand_id) {

							if($count_brands != 0){
								echo '<tr>';
							}

							foreach ($array_brand as $brand) {
								switch ($brand) {
									case 'type_brand':
										echo '<td>'.$array_type[$brand_id].'</td>';
										break;

									case 'old_stock':
										//echo '<td style="">'.date("d-m-y",$time_start).': '.$opening.'<br>'.date("d-m-y",$time_end).': '.$closing.'</td>';
										echo '<td style="">O '.$opening[$brand_id].'<br>C '.$closing[$brand_id].'</td>';

										break;

									case 'rate':
										if(sizeof($day_array['brand'.$brand_id.$brand]) > 0)
										echo '<td>'.round(array_sum($day_array['brand'.$brand_id.$brand])/sizeof($day_array['brand'.$brand_id.$brand]),2).'</td>';
										//echo '<td></td>';
										break;

									case 'rate_ws':
										if(sizeof($day_array['brand'.$brand_id.$brand]) > 0)
										echo '<td>'.round(array_sum($day_array['brand'.$brand_id.$brand])/sizeof($day_array['brand'.$brand_id.$brand]),2).'</td>';
										//echo '<td></td>';
										break;

									default:
										echo '<td>'.array_sum($day_array['brand'.$brand_id.$brand]).'</td>';
										break;
								}
							}

							if($count_brands != 0){
								echo '</tr>';
							} else {
								foreach ($array_fin_last as $key) {
									switch ($key) {
										case 'commission':
											echo '<td rowspan="'.$total_brands.'">'.round(array_sum($day_array[$key])/sizeof($day_array[$key]),2).'</td>';
											break;
										
										default:

											echo '<td rowspan="'.$total_brands.'">'.array_sum($day_array[$key]).'</td>';
											break;
									}
								}

								echo '</tr>';
							}
							
							$count_brands++;
						}
					}


					//daily_total 3

				}
				?>
				<?php


				// final total
				$query_brands_daily = mysql_query("SELECT distinct type_id from sale where shop_id IN (".$shop_string.") and timestamp_day between '$time_start' and '$time_end' ");

					$total_brands = mysql_num_rows($query_brands_daily);
					//$total_brands =8;
					$total_brands_id_daily = array();

					while ($row_daily_brand = mysql_fetch_array($query_brands_daily)) {
						array_push($total_brands_id_daily, $row_daily_brand["type_id"]);
					}

					echo '<tr>';
						foreach ($array_fin as $key) {

							switch ($key) {
								case 'date':
									echo '<td rowspan="'.$total_brands.'">कुल योग</td>';
									break;
								case 'shop_name':
									echo '<td rowspan="'.$total_brands.'" ></td>';
									break;
								default:
									echo '<td rowspan="'.$total_brands.'">'.array_sum($total_array[$key]).'</td>';
									break;
							}
						}
					// daily_total brand
						$count_brands = 0;
						foreach ($total_brands_id_daily as $brand_id) {

							if($count_brands != 0){
								echo '<tr>';
							}

							foreach ($array_brand as $brand) {
								switch ($brand) {
									case 'type_brand':
										echo '<td>'.$array_type[$brand_id].'</td>';
										break;

									case 'old_stock':
										//echo '<td style="">'.date("d-m-y",$time_start).': '.$total_opening.'<br>'.date("d-m-y",$time_end).': '.$total_closing.'</td>';
										echo '<td style="">O '.$total_opening[$brand_id].'<br>C '.$total_closing[$brand_id].'</td>';
										break;
									
									case 'rate':
										//echo '<td>'.array_sum($total_array['brand'.$brand_id.$brand])/sizeof($total_array['brand'.$brand_id.$brand]).'</td>';
										echo '<td></td>';
										break;

									case 'rate_ws':
										//echo '<td>'.round(array_sum($total_array['brand'.$brand_id.$brand])/sizeof($total_array['brand'.$brand_id.$brand]),2).'</td>';
										echo '<td></td>';
										break;

									default:
										echo '<td>'.array_sum($total_array['brand'.$brand_id.$brand]).'</td>';
										break;
								}
							}

							if($count_brands != 0){
								echo '</tr>';
							} else {
								foreach ($array_fin_last as $key) {
									switch ($key) {
										case 'commission':
											echo '<td rowspan="'.$total_brands.'">'.round(array_sum($total_array[$key])/sizeof($total_array[$key]),2).'</td>';
											break;
										
										default:
											echo '<td rowspan="'.$total_brands.'">'.array_sum($total_array[$key]).'</td>';
											break;
									}
								}

								echo '</tr>';
							}
							
							$count_brands++;
						}
				?>
			</tbody>
			<thead>
				<tr>
					<th rowspan="2">दिनांक</th>
					<th rowspan="2">दुकान</th>
					<th rowspan="2">सादा बिक्री<br>खाते जमा</th>
					<th rowspan="1" colspan="2">मसाला बिक्री<br>खाते जमा</th>
					<th rowspan="2">थोक बिक्री<br>खाते जमा</th>
					<th rowspan="2">चिकना</th>
					<th rowspan="2">जुर्माना</th>
					<th rowspan="2">अन्य जमा</th>
					<th rowspan="2">ब्रांड</th>
					<th rowspan="2">पिछ्ला बाकी</th>
					<th rowspan="2">आमद</th>
					<th rowspan="2">ट्रांसफर<br>आमद</th>
					<th rowspan="2">ट्रांसफर</th>
					<th rowspan="2">बिक्री</th>
					<th rowspan="2">थोक बिक्री</th>
					<th rowspan="2">दर</th>
					<th rowspan="2">थोक दर</th>
					<th rowspan="2">कमीशन</th>
					<th rowspan="2">कुल कमीशन</th>
					<th rowspan="2">बुतात</th>
					<th rowspan="2">भाड़ा</th>
					<th rowspan="2">पैसा दिया गया</th>
					<th rowspan="2">कुल योग</th>
				</tr>
				<tr>
					<th>36%</th>
					<th>42.8%</th>
				</tr>
			</thead>
		</table><br><br>
		<?php
		echo $str_money;
}

?>
</body>
</html>