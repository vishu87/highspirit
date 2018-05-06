<?php include('top.php'); 

$count = 1;
$bcount = 1;
$scount = 1;
$sql = mysql_query("select id from types_beer order by id asc");
while ($row = mysql_fetch_array($sql)) {
	
	mysql_query("UPDATE types_beer set serial_count = '$scount' , brand_range = '$bcount' where id = '$row[id]' ");
	
	switch ($count) {
		case 23:
			$bcount = 2;
			$scount = 0;
			break;
		
		case 28:
			$scount = 0;
			$bcount = 3;
			break;

		case 30:
			$scount = 0;
			$bcount = 4;
			break;
	}
$count++;
$scount++;
}

?>
