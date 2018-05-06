<?php include('top.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>High Spirit</title>
	<meta content="text/html; charset=UTF-8" http-equiv="content-type">
</head>
<body style="font-size:12px;">
<?php
$dis = array("अम्बेडकर नगर","फैजाबाद", "सुल्तानपुर");
$dis_names=array("","अकबरपुर","जलालपुर","भीटी","टाण्डा","बसखारी","जहांगीरगंज","फैजाबाद","सुल्तानपुर");
$current_district = mysql_real_escape_string($_GET["dis"]);
$current_group = mysql_real_escape_string($_GET["grp"]);
$current_shop = mysql_real_escape_string($_GET["shp"]);
//$type = mysql_real_escape_string($_GET["type"]);
$type =3;
$dis_names=array("","अकबरपुर","जलालपुर","भीटी","टाण्डा","बसखारी","जहांगीरगंज","फैजाबाद","सुल्तानपुर");

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

//echo date("d M y",$time_nxt);

if($time_pre && $time_nxt ){
	if($time_pre > $time_nxt){
		echo 'Invalid Dates';
		die();
	}
}

$dis = mysql_real_escape_string($_POST["dis"]);
$grp = mysql_real_escape_string($_POST["grp"]);
$shop = mysql_real_escape_string($_POST["shop"]);
$type = mysql_real_escape_string($_POST["type"]);
$priv =1;
$details_shop = 1;

$array_type = array();
mysql_query('SET character_set_results=utf8');
$query_type = mysql_query("SELECT type_brand, id from types");
while($row_type = mysql_fetch_array($query_type)){
	$array_type[$row_type["id"]] = $row_type["type_brand"];
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

	if($dis == 0){
		$sql_en ='';
		$sql_shop = mysql_query("SELECT id from shops order by id asc  ");
			while ($fetch_shop = mysql_fetch_array($sql_shop)) {
				array_push($shops, $fetch_shop["id"]);
			}
	} else {
		if($grp == 0){
			$sql_shop = mysql_query("SELECT id from shops where district = '$dis' order by id asc  ");
			while ($fetch_shop = mysql_fetch_array($sql_shop)) {
				array_push($shops, $fetch_shop["id"]);
			}
		} else {

			if($shop == 0){
				$sql_shop = mysql_query("SELECT id from shops where group_name = '$grp' order by id asc  ");
				while ($fetch_shop = mysql_fetch_array($sql_shop)) {
				array_push($shops, $fetch_shop["id"]);
			}
			} else {
				array_push($shops, $shop);
			}
	}
}
		?>
			<table border="1" cellpadding="4" cellspacing="0">
			<thead>
				<tr>
					<th>दिनांक</th>
					<th>सादा बिक्री खाते जमा</th>
					<th>मसाला बिक्री खाते जमा</th>
					<th>थोक बिक्री</th>
					<th>चिकना</th>
					<th>जुर्माना</th>
					<th>अन्य जमा</th>
					<th>योग</th>
					<th>कुल बिक्री</th>
					<th>थोक बिक्री</th>
					<th>कमीशन</th>
					<th>कुल कमीशन</th>
					<th>कुल योग</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$array_fin = array("date","shop_name","sada_total","masala_total","ws_total","chikna","jurmana","other","input_total","normal_sale","ws_sale","commission","commission_round","final_total");
					
					foreach ($array_fin as $fin) {
						$total_array[$fin] = array();
					}
					
					for ($time=$time_start; $time <= $time_end ; $time += 86400 ) { 

						foreach ($array_fin as $fin) {
							$day_array[$fin] = array();
						}

						foreach ($shops as $shop) {
							mysql_query('SET character_set_results=utf8');
							$query_name = mysql_query("SELECT name from shops where id='$shop' limit 1 ");
							$row_name = mysql_fetch_array($query_name);
							$shop_name =  $row_name["name"];
						
						
						foreach ($array_fin as $key) {

							$query_final_sale = mysql_query("SELECT * from sale_final where shop_id='$shop' and timestamp_day = '$time' ");
							$row_final_sale = mysql_fetch_array($query_final_sale);

							switch ($key) {
								case 'date':
									echo '<td>'.date("d-M-y", $time).'</td>';
									break;
								case 'shop_name':
									echo '<td>'.$shop_name.'</td>';
									break;
								
								default:
									echo '<td>'.$row_final_sale[$key].'</td>';
									break;
							}
						}
						echo '</tr>';
					}
				}
				?>
				<tr>
					<td style="font-size:13px">कुल योग</td>
					<?php
						foreach ($array_fin as $fin) {
							echo '<td>'.array_sum($total_array[$fin]).'</td>';
						}
					?>
				</tr>
			</tbody>
		</table>
		<table>
		<?php //TYPE WISE
		$array_br = array("old_stock","stock_in","stock_transfer_in","initial_stock","final_stock","stock_transfer_out","sale","rate","total_cost","sale_ws","rate_ws","total_ws");
		foreach ($array_type as $type) {
			
		}
		?>
		</table>
		<?php
}

?>
</body>
</html>