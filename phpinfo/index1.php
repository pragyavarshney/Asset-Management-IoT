<?php
 	session_start();
 	require 'dbconfig/config.php';	

?>
<!DOCTYPE html>
<html>
<head>
<title>LOGIN PAGE</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body style="background-color: #7f8c8d">

	<div id="main-wrapper">
		<center><h2>Login Form</h2></center>
	

	<form class= "myform" action= "index1.php" method="post">
		<label>Username:</label><br>
		<input name="username" type="text" class= "inputvalues" placeholder="Type your username"/><br>
		<label>Password:</label><br>
		<input name="password" type="password" class= "inputvalues" placeholder="Type your password"/><br>

		<input name="login" type="submit" id="login_btn" value="Login"/><br>

		<a href="register.php"><input type="button" id="register_btn" value="Register"/></a>

		
	</form>	
	<?php
		if(isset($_POST['login']))
		{

			$username=$_POST['username'];
			$password=$_POST['password'];
			$query="select * from login WHERE username='$username' AND password='$password' ";

			$query_run = mysqli_query($con,$query);
			if(mysqli_num_rows($query_run)>0)
			{
				//valid
				$_SESSION['username']= $username;
				header('location:homepage.php');
			}
			else{
				
				//invalid
				echo '<script type="text/javascript"> alert("Invalid credentials") </script>';
			}
		}
	?>
	</div>
</body>
</html>