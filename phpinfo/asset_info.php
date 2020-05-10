<?php

require 'dbconfig/config.php';

?>
<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Information PAGE</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #7f8c8d">
<ul>
  <li><a class="active" href="http://localhost/homepage.php">Home</a></li>
  <li><a href="http://localhost/testv1.php">Asset Information</a></li>
  <li><a href="http://localhost/find_asset.php">Find my asset</a></li>
  
</ul>

</h3>

	<div id="main-wrapper">
		<table style="width:50%"align="center"><center><h1>Asset Information </h1></center>
<?php 
// Create connection 
//$conn = new mysqli($servername, $username, $password, $dbname); 
// Check connection 
if ($con->connect_error) { 
    die("Connection failed: " . $con->connect_error); 
} 

$sql="SELECT asset_id,asset_name, create_date, predicted_room FROM asset_device_info group by asset_id order by 1 DESC LIMIT 50";
$result = $con->query($sql); 

if ($result->num_rows > 0) {
 // output data of each row
  echo "<tr><th>"."SrNo: ". "</th><th>". "Asset:". "</th><th>". "On Date:". "</th><th>". "Predicted Room No.:"."</th><th>". "</th></tr>"; 
  while($row = $result->fetch_assoc()) {
    

    echo "<tr><td>". $row["asset_id"] . "</td><td>".  $row["asset_name"]. "</td><td>". $row["create_date"]."</td><td>". $row["predicted_room"]."</td><td>". "</td></tr>"; 

  } 
} 

else {
   echo "0 results"; 
 } 

$con->close(); 
?>
	</div>
	
	
	
</body>
</html>