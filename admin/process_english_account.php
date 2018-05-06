<?php
  set_time_limit(3000);
  include('top.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>High Spirit</title>
	<meta content="text/html; charset=UTF-8" http-equiv="content-type">
	<style type="text/css">
		table td {
			text-align: center;
			vertical-align: middle;
			border: 1px solid #555;
			padding: 2px;
			font-size: 12px;
		}
		table th {
			text-align: center;
			vertical-align: middle;
			border: 1px solid #555;
			padding: 2px;
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
</head>
<body style="font-size:12px; font-family:arial">
<?php
$dis_names=array("","अकबरपुर","टाण्डा","फैजाबाद A","फैजाबाद B","सुल्तानपुर","देहरादून A","देहरादून B");
$ar_details = array("स्टाक","आमद","ट्रांसफर","बिक्री","रेट","बचा माल","योग","कुल योग");
$in_details = array("old_stock","stock_in","stock_transfer","sale","rate","final_stock","total_cost","total_sum");
$brand_size = array("","Q","P","N","N<br>90M");
$total_sizes = 4;
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

$array_sum = array();
foreach ($in_details as $detail) {
	if($detail != 'total_sum'){
		for ($i=1; $i <= $total_sizes ; $i++) {
			$array_sum[$detail][$i] = array();
		}
	} else {
		$array_sum[$detail] = array();
	}
}

$str_money = '';

$grp = mysql_real_escape_string($_POST["grp"]);
$shop = mysql_real_escape_string($_POST["shop"]);

if($shop == 0){
	$shops = array();
	$res_shop = mysql_query("SELECT id from shops_english where group_name = '$grp'");
	while($row_shop = mysql_fetch_array($res_shop)){
		array_push($shops, $row_shop["id"]);
	}
	$shop_string = implode(',', $shops);
} else {
	mysql_query('SET character_set_results=utf8');
	$res_shop = mysql_query("SELECT id, name, group_name from shops_english where id = '$shop' limit 1 ");
	$row_shop = mysql_fetch_array($res_shop);
	$shop_string = $shop;
	$shop_name = $row_shop["name"];
}

$array_type = array();

$total_brands_id = array();

mysql_query('SET character_set_results=utf8');

$query_type = mysql_query("SELECT type_brand, id from types_english order by serial_count asc ");


// INITIAL STOCK QUERY
$old_stock = array();

$query = "SELECT type_id, size_id, SUM(old_stock) as old_stock from sale_english where shop_id IN (".$shop_string.") and timestamp_day = $time_pre GROUP BY type_id, size_id";
$result = mysql_query($query);
while ($row = mysql_fetch_array($result)) {
	$old_stock[$row["type_id"]][$row["size_id"]] = $row["old_stock"];
}

// FINAL STOCK QUERY
$final_stock = array();

$query = "SELECT type_id, size_id, SUM(final_stock) as final_stock from sale_english where shop_id IN (".$shop_string.") and timestamp_day = $time_nxt GROUP BY type_id, size_id";
$result = mysql_query($query);
while ($row = mysql_fetch_array($result)) {
	$final_stock[$row["type_id"]][$row["size_id"]] = $row["final_stock"];
}

//SALE QUERY

$array_sale = array();
$query = "SELECT type_id, size_id, SUM(stock_in) as stock_in, SUM(stock_transfer) as stock_transfer, SUM(sale) as sale, SUM(rate*sale) as rate, SUM(total_cost) as total_cost from sale_english where shop_id IN (".$shop_string.") and timestamp_day between $time_pre and $time_nxt GROUP BY type_id, size_id";

$result = mysql_query($query);
while ($row = mysql_fetch_array($result)) {
  if($row["sale"] != 0 ) $rate = $row["rate"]/$row["sale"];
  else $rate = 0;
	$array_sale[$row["type_id"]][$row["size_id"]] = array($row["stock_in"],$row["stock_transfer"],$row["sale"],$rate,$row["total_cost"]);
}
//FINAL SALE SUM QUERY

$array_sale_sum = array();

$query = "SELECT type_id, SUM(total_sum) as total_sum from sale_english_sum_final where shop_id IN (".$shop_string.") and timestamp_day between $time_pre and $time_nxt GROUP BY type_id";

$result = mysql_query($query);
while ($row = mysql_fetch_array($result)) {
	$array_sale_sum[$row["type_id"]] = $row["total_sum"];
}

?>
<div style="text-align:center; padding:20px 0">
	<div style="font-size:30px">ग्रुप: <?php echo $dis_names[$grp]; ?></div>
	<?php if($shop != 0){ ?>
	<div  style="font-size:20px">दुकान : <?php echo $shop_name ?></div>
	<?php } ?>
	<div style="font-size:30px"><?php echo date("d-M-y",$time_pre); ?> से <?php echo date("d-M-y",$time_nxt); ?></div>
</div>
<table  cellspacing="0" cellpaddin="0" width="100%">
	<tr>
		<th></th>
		<?php
			$count = 0;
			foreach ($in_details as $detail) {
				if($detail != 'total_sum')
					echo '<th colspan="4">'.$ar_details[$count].'</th>';
				else echo '<th>'.$ar_details[$count].'</th>';
				$count++;
			}
		?>
	</tr>
	<tr>
		<th></th>
		<?php
			foreach ($in_details as $detail) {
				if($detail != 'total_sum'){
					for ($i=1; $i <= $total_sizes ; $i++) { 
						echo '<th>'.$brand_size[$i].'</th>';
					}
				} else echo '<th></th>';
			}
		?>
	</tr>
	<?php
		while ( $row_type = mysql_fetch_array($query_type)) {
			$brand_id = $row_type["id"];
	?>
	<tr>
		<td><?php echo $row_type["type_brand"]?></td>
		<?php
			foreach ($in_details as $detail) {
				if($detail != 'total_sum'){
					for ($i=1; $i <= $total_sizes ; $i++) {
						switch ($detail) {
							case 'old_stock':
								echo '<td>';
								$val = (isset($old_stock[$brand_id][$i]))?$old_stock[$brand_id][$i]:0;
								echo $val;
								echo '</td>';
								break;
							case 'stock_in':
								echo '<td>';
								$val = (isset($array_sale[$brand_id][$i]))?$array_sale[$brand_id][$i][0]:0;
								echo $val;
								echo '</td>';
								break;
							case 'stock_transfer':
								echo '<td>';
								$val = (isset($array_sale[$brand_id][$i]))?$array_sale[$brand_id][$i][1]:0;
								echo $val;
								echo '</td>';
								break;
							case 'sale':
								echo '<td>';
								$val = (isset($array_sale[$brand_id][$i]))?$array_sale[$brand_id][$i][2]:0;
								echo $val;
								echo '</td>';
								break;
							case 'rate':
								echo '<td>';
								$val = (isset($array_sale[$brand_id][$i]))?round($array_sale[$brand_id][$i][3]):0;
								echo $val;
								echo '</td>';
								break;
							case 'final_stock':
								echo '<td>';
								$val = (isset($final_stock[$brand_id][$i]))?$final_stock[$brand_id][$i]:0;
								echo $val;
								echo '</td>';
								break;
							case 'total_cost':
								echo '<td>';
								$val = (isset($array_sale[$brand_id][$i]))?$array_sale[$brand_id][$i][4]:0;
								echo $val;
								echo '</td>';
								break;
							default:
								# code...
								break;
						}
						array_push($array_sum[$detail][$i], $val);
					}
				} else {
					echo '<th>';
					$val = (isset($array_sale_sum[$brand_id]))?$array_sale_sum[$brand_id]:0;
					echo $val;
					echo '</th>';
					array_push($array_sum[$detail], $val);
				}
			}
		?>
	</tr>
	<?php  } ?>
	<tr>
		<td></td>
		<?php
			foreach ($in_details as $detail) {
				if($detail != 'total_sum'){
					for ($i=1; $i <= $total_sizes ; $i++) { 
						echo '<td>'.array_sum($array_sum[$detail][$i]).'</td>';
					}
				} else echo '<td>'.array_sum($array_sum[$detail]).'</td>';
			}
		?>
	</tr>
</table>
	<?php
		echo array_sum($array_sum["total_sum"]).'<br>'.$array_sale_sum[0].'<br>';
		?>
		<table>
			<tr>
				<th>Date</th>
				<th>Money Given</th>
				<th>Money Given To</th>
				<th>Other Money</th>
				<th>Remark</th>
			</tr>
			<?php
				$sql_money = mysql_query("SELECT timestamp_day, money_given, money_name, other_money, remark from sale_english_sum_final where (money_given != 0 OR other_money != 0) and shop_id in (".$shop_string.") and timestamp_day between $time_pre and $time_nxt ");
				$sum = 0;
				while ($row_money = mysql_fetch_array($sql_money)) {
					$sum += $row_money["money_given"];
					$sum -= $row_money["other_money"];
				?>
					<tr>
						<td><?php echo date("d-m-y",$row_money["timestamp_day"]) ?></td>
						<td><?php echo $row_money["money_given"];  ?></td>
						<td><?php echo $row_money["money_name"] ?></td>
						<td><?php echo $row_money["other_money"]; ?></td>
						<td><?php echo $row_money["remark"] ?></td>
					</tr>
				<?php
				}
			?>
		</table>
		<?php
		echo $array_sale_sum[0].'<br>'.$sum.'<br>';

		
		?>
		<?php
// 				$time_pre = $time_pre - 86400;
// echo strtotime("01-05-2015").'<br>';
// 			$query = "SELECT total_sum, timestamp_day from sale_english_sum_final where shop_id IN (".$shop_string.") and timestamp_day between $time_pre and $time_nxt and type_id = 0 order by timestamp_day asc ";

// 			$result = mysql_query($query);
// 			while ($row = mysql_fetch_array($result)) {
// 				echo $row["total_sum"].' '.date("d-M-y", $row["timestamp_day"]).'<br>';
// 			}
		?>
		
</body>
</html>