<?php include('top.php'); 
$dis = array("","अम्बेडकर नगर","फैजाबाद", "सुल्तानपुर","देहरादून","लखनऊ");
$dis_names=array("","अकबरपुर","टाण्डा","फैजाबाद A","फैजाबाद B","सुल्तानपुर","देहरादून A","देहरादून B","लखनऊ");
?>
<!DOCTYPE html>
<html>
<head>
	<title>High Spirit</title>
	<meta content="text/html; charset=UTF-8" http-equiv="content-type">
	<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
</head>
<form action="process-shop.php" method="POST">
<body>
Name of District - 
<select name="district" required="">
	<?php foreach ($dis as $key => $value) { ?>
		<option value="<?php echo $key ?>"><?php echo $value ?></option>
	<?php } ?>
</select>
<br><br>
Name of Group - 
<select name="group_name" required="">
	<?php foreach ($dis_names as $key => $value) { ?>
		<option value="<?php echo $key ?>"><?php echo $value ?></option>
	<?php } ?>
</select>
<br><br>
Name of Shop <input type="text" name="name" required=""><br><br>
<button name="submit">Submit</button>
</form>
<?php
	mysql_query('SET character_set_results=utf8');
	$query = mysql_query("SELECT * from shops_english order by district asc, group_name asc");
?>
All shops -
<table border="1">
	<tr>
		<th>SN</th>
		<th>Name</th>
		<th>Group</th>
		<th>District</th>
	</tr>
	<?php $count = 0; ?>
	<?php while($row = mysql_fetch_array($query)): ?>
	<tr>
		<td><?php echo ++$count; ?></td>
		<td><?php echo $row["name"] ?></td>
		<td><?php echo $dis_names[$row["group_name"]] ?></td>
		<td><?php echo $dis[$row["district"]] ?></td>
	</tr>
	<?php endwhile; ?>
</table>
</body>

</html>