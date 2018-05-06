<!DOCTYPE html>
<html>
<head>
  <title>High Spirit</title>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type">
</head>
<body style="font-size:12px; font-family:arial">
  <?php
  set_time_limit(3000);
  include('top.php');
$dis_names=array("","अकबरपुर","जलालपुर","भीटी","टाण्डा","बसखारी","जहांगीरगंज","फैजाबाद","सुल्तानपुर");

mysql_query('SET character_set_results=utf8');
$query_type = mysql_query("SELECT * from shops");
while($row_type = mysql_fetch_array($query_type)){
  
   $queryo = mysql_query("SELECT timestamp_day from sale_final where shop_id='$row_type[id]' order by timestamp_day desc limit 1 ");
  $rowo = mysql_fetch_array($queryo);
  $rowo["timestamp_day"] += 86400;

  $query = mysql_query("SELECT id, timestamp_day from sale_un where shop_id='$row_type[id]' order by timestamp_day asc limit 1 ");
  $row = mysql_fetch_array($query);
  $month = date("m",$row["timestamp_day"]);

  if($rowo["timestamp_day"] == $row["timestamp_day"]){
    echo $row_type["id"].' '.$row_type["name"].' / '.$dis_names[$row_type["group_name"]].' /old '.date("d-M-y",$rowo["timestamp_day"]).' / '.date("d-M-y",$row["timestamp_day"]).' '.$row["id"].' <span style="background:#0f0; padding:2px;">'.$month.'</span><br><br>';
  } else {
    echo $row_type["id"].' '.$row_type["name"].' / '.$dis_names[$row_type["group_name"]].'  /old '.date("d-M-y",$rowo["timestamp_day"]).' / '.date("d-M-y",$row["timestamp_day"]).' '.$row["id"].' <span style="background:#f00; padding:0 10px;">'.$month.'</span><br><br>';

  }

}

?>
</body>
</html>