<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>HOME PAGE</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #7f8c8d">
<ul>
  <li><a class="active" href="http://localhost/homepage.php">Home</a></li>
  <li><a href="http://localhost/testv1.php">Asset Information</a></li>
  <li><a href="http://localhost/find_asset.php">Find my asset</a></li>
  
</ul>

	<div id="main-wrapper">
		<center><h2>HOME PAGE</h2></center>
		<h3>Welcome 
			<?php echo $_SESSION['username'] ?>
		</h3>

	<form class= "myform" action= "homepage.php" method="post">
	<p><a href="http://localhost/testv1.php">Asset Information</a>View full deatils of assets</p>
		
		<input name="logout" type="submit" id="logout_btn" value="Log Out"/><br>

	</form>	
	
	<?php 
		if(isset($_POST['logout']))
		{
		session_destroy();
		header('location:index1.php');
		}
	?>

	</div>
</body>
</html>