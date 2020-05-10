<?php

require 'dbconfig/config.php';

?>
<!DOCTYPE html>
<html>
<head>
<title>Find PAGE</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #7f8c8d">
<ul>
  <li><a class="active" href="http://localhost/homepage.php">Home</a></li>
  <li><a href="http://localhost/testv1.php">Asset Information</a></li>
  <li><a href="http://localhost/find_asset.php">Find my asset</a></li>

</ul>

	<div style="background: white; padding: 5px; border-radius: 10px; border: 2px; width:40%; float:left; margin:10px"	>
		<center><h2>Find the asset</h2></center>
	

	<form class= "myform" action= "find_asset.php" method="post">
		<input name ="assetname" type="text" placeholder="Asset name" required style="width:70%" /><br>
		
		
		<input name ="submit_btn" type="submit" id="signup_btn" value="Find"style="width:70%"/><br>

		<a href="index1.php"><input type="button" id="back_btn" value="Back"style="width:70%" align/></a>
		
	</form>	</div>
	<div style="background: white; padding: 5px; border-radius: 10px; border: 2px; width:50%; float:left; margin:10px"	><table style="width:80%"align="center"><center><h1>Asset Information </h1></center>
</div>
	<?php 
	if(isset($_POST['submit_btn']))
	{
		// echo '<script type ="text/javascript"'
		$assetname= $_POST['assetname'];

		if($assetname!=Null)
		{
			$sql= " SELECT asset_id,asset_name, create_date, predicted_room FROM asset_device_info WHERE asset_name = '$assetname' group by asset_id  order by 1 DESC LIMIT 5 ";
			$result = $con->query($sql); 

             if ($result->num_rows > 0)
				 {
              // output data of each row
                    echo "<tr><th>"."SrnO: ". "</th><th>". "Asset:". "</th><th>". "On Date:". "</th><th>". "Predicted Room"."</th></tr>"; 
                     
					 while($row = $result->fetch_assoc()) 
					 {
						echo "<tr><td>". $row["asset_id"] . "</td><td>".  $row["asset_name"]. "</td><td>". $row["create_date"]."</td><td>". $row["predicted_room"]."</td></tr>"; 

                      } 
                 }     

             else {
                      echo "0 results"; 
                  } 

		$con->close(); }
		
		else{
			echo " Please provide a asset name";
			}
}	
	?>

	</div>
</body>
</html>