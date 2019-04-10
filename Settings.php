<?php
session_start();

if(!($_SESSION['authenticated'])){
	header('location: index.php');
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Settings</title>
	<link rel="stylesheet" type="text/css" href="css/settings.css">
</head>
<body>



<header>	

<?php
	echo "<p  id = 'greeting'> Hello $_SESSION[username] </p> ";

?>



		<div id = 'logOut_div' onclick="logOut()" style = "cursor: pointer; ">
			<p>Log Out</p>
		</div>
	
		<div id = 'logOut_div' onclick="hub()" style = "cursor: pcointer; ">
			<p>Hub Page</p>
		</div>

	
		<span id = 'upload_span'>
		<p  style = 'font-size: 25px; cursor: pointer;' onclick='uploadNotes()'	> Upload Notes </p> 
		</span>


</header>




<?php

	$fullUrl = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];    

	require('Settings_error.php');
	$error_handling = new Settings_error($fullUrl);

?>






<center>


<div class="pass_div">	

	<h1>Change Password</h1>
	<hr>
	<br><br>	

	<form method="POST" action="Update.php">
		
		<h4>Old Password</h4>
		<input type="Password" name="old_password" >
		<br> <h4>New Password</h4> 
		<input type="Password" name="new_password" >
		<br> <h4>Confirm New Password</h4> 
		<input type="Password" name="confirm_new_password">	
		<br>
		<input type="submit" name="Submit" value="Submit">		

	</form>

	<br><br><br>


		<form method="POST" action="Update.php">
			
			<h1>Change Username</h1> 
			<hr>	
			<br><br>	
			<h4>New Username</h4>
			<input type="text" name="new_username"> <br>
			<h4>Confirm New Username</h4>
			<input type="text" name="confirm_new_username">
			<br>
			<input type="submit" name="Submit" value="Submit">


		</form>

		<br><br>	


		<form method="POST" action="Update.php">
			
			<h1 id = 'delete_header'>Delete Account</h1> <hr>
			<br><br>	
			<h4>Write "delete my account", then click "Delete" button. To permanently delete your SillocxWeb account.</h4>
			<input type="text" name="delete_confirm" placeholder="delete my account">
			<br>
			<input type="Submit" name="Submit" value="Delete">

		</form>


</div>



</center>




	<script type="text/javascript">
		

	function logOut(){
		window.location.href = 'index.php?stat=logout';
	}

	function uploadNotes(){
		window.location.href = 'upload.php';
	}


	function hub(){
		window.location.href = 'Hub.php';
	}





</script>	




</body>
</html>