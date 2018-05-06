<!DOCTYPE html>
<html>
<head>
  <title>High Spirit</title>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type">
</head>
<body style="font-size:12px; font-family:arial">
  <?php

  require_once('config.php');
  $link = mysql_connect( DB_HOST, DB_USER , DB_PASSWORD );
  if(!$link) {
    die('Failed to connect to server: ' . mysql_error());
  }
  
  //Select database
  $db = mysql_select_db(DB_DATABASE);
  if(!$db) {
    die("Unable to select database");
  }
  set_time_limit(3000);

$dis_names=array("","अकबरपुर","टाण्डा","फैजाबाद A","फैजाबाद B","सुल्तानपुर","देहरादून A","देहरादून B");

mysql_query('SET character_set_results=utf8');
$query_type = mysql_query("SELECT * from shops_beer");
while($row_type = mysql_fetch_array($query_type)){
  $times = array();
  
  $query = mysql_query("SELECT id, timestamp_day from sale_beer where shop_id='$row_type[id]' order by timestamp_day asc limit 1 ");
  $row_sale_beer = mysql_fetch_array($query);
  array_push($times, $row_sale_beer["timestamp_day"]);


  $query = mysql_query("SELECT id, timestamp_day from sale_beer_final where shop_id='$row_type[id]' order by timestamp_day asc limit 1 ");
  $row_sale_beer_final = mysql_fetch_array($query);
  array_push($times, $row_sale_beer_final["timestamp_day"]);


  $query = mysql_query("SELECT id, timestamp_day from sale_beer_sum_final where shop_id='$row_type[id]' order by timestamp_day asc limit 1 ");
  $row_sale_beer_sum_final = mysql_fetch_array($query);
  array_push($times, $row_sale_beer_sum_final["timestamp_day"]);

  $time = min($times);
  echo $row_type["id"].' '.$row_type["name"].' / '.$dis_names[$row_type["group_name"]].' /old '.date("d-M-y",$time).' /<br><br>';

}

?>
</body>
</html>