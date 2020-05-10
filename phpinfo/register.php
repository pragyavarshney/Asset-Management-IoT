<?php

require 'dbconfig/config.php';

?>
<!DOCTYPE html>
<html>
<head>
<title>REGISTRATION PAGE</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #7f8c8d">


	<div id="main-wrapper">
		<center><h2>Registration Form</h2></center>
	

	<form class= "myform" action= "register.php" method="post">
		<label>Username:</label><br>
		<input name ="username" type="text" class= "inputvalues" placeholder="Type your username" required /><br>
		<label>Password:</label><br>
		<input name ="password" type="password" class= "inputvalues" placeholder="your password"required/ ><br>
		<label>Confirm Password:</label><br>
		<input name ="cpassword" type="password" class= "inputvalues" placeholder="confirm password" required/><br>

		<input name ="submit_btn" type="submit" id="signup_btn" value="Sign Up"/><br>

		<a href="index1.php"><input type="button" id="back_btn" value="Back"/></a>

		
	</form>	

	<?php 
	if(isset($_POST['submit_btn']))
	{
		// echo '<script type ="text/javascript"'
		$username = $_POST['username'];
		$password = $_POST['password'];
		$cpassword = $_POST['cpassword'];


		if($password==$cpassword)
		{
			$query= " select * from login WHERE username = '$username' ";
			$query_run = mysqli_query($con,$query);

			if(mysqli_num_rows($query_run)>0)
			{	// there is already a user with the same user name
				echo '<script type ="text/javascript"> alert("User already exists.. try another username") </script>';
		}
		else
		{
			$query= "insert into login values('$username','$password')";
			$query_run = mysqli_query($con,$query);

			if($query_run)
			{
				echo '<script type="text/javascript"> alert("User registered.. go to login page to login")</script>';
			}
			else
			{
				echo '<script type="text/javascript"> alert("Error!")</script>';
			}
		}
	}
	else{
			echo '<script type="text/javascript"> alert("password and confirm password do not match")</script>';

	}
	}
	?>

	</div>
</body>
</html>