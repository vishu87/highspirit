<!DOCTYPE html>
<html>
<head>
  <title>High Spirit</title>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type">
</head>
<body style="font-size:12px; font-family:arial"><?php
  set_time_limit(3000);
  include('top.php');
$flag = 0;
  $query = mysql_query("SELECT * from sale_final where shop_id between 1 and 244 and timestamp_day >= 1398882600 order by timestamp_day asc ");
  $array  = array();
  while ($row = mysql_fetch_array($query)) {
  	
    mysql_query('SET character_set_results=utf8');
	 $query_type = mysql_query("SELECT * from shops where id= '$row[shop_id]' ");
	$row_type = mysql_fetch_array($query_type);

  	$value = $row["input_total"] - $row["final_total"] - $row["commission_round"] - $row["bhada"] - $row["buttat"] ;
  	//$value = $row["input_total"] - $row["final_total"] - $row["commission_round"];
  	
  	if($value == 0){

  		//echo $row["id"].' '.$row_type["name"].' '.$row["shop_id"].' '.date("d-M-y", $row["timestamp_day"]).': '.$value.'<br><br>';
  		
      if($value == 20){
      // mysql_query("UPDATE sale_final set buttat = '20' where id = '$row[id]' ");
      } else {
       //mysql_query("UPDATE sale_final set buttat = '20' , bhada='10' where id = '$row[id]'  ");
      }

      
  	} else {
  		$flag++;
      array_push($array, $row["id"]);
  		echo '<span style="background:#f00; padding: 0 10px;">'.$row["id"].' '.$row_type["name"].' '.$row["shop_id"].' '.date("d-M-y", $row["timestamp_day"]).': '.$value.'</span><br><br>';
  	}
  }

  if($flag>0){
  	echo '<script>alert("Yes'.$flag.'")</script>';
  }
  echo implode(',', $array);
?>
</body>
</html>
