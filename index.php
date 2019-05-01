<!DOCTYPE html>

<?php

	session_start();
	$_SESSION['authenticated'] = False;

?>


<html>
<head>
	<title>Sillcox Home Page</title>

	<link rel="stylesheet" type="text/css" href="css/index.css">

</head>

<body>





<center>
<div id="message">
<label>Sillcox Note Hub </label>

<p>

Exclusive academic note repository for Sillcox scholarship recipients. Upload or view notes for
different classes. Contribute to the betterment of our fellow peers.  

</p>

	
</div>


</center>






<div class="enter" id = 'signUp_div'  style="display: : visible;">
	<h1 style="background-color: inherit; margin-top: 0px; margin-left: 150px;"> Sign Up</h1>	
	<p  onclick= "show_signIn()">Already have an account? Sign In </p>



<?php

$error = $_SERVER['QUERY_STRING'];	
$fullUrl = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];	

if(strpos($fullUrl, 'error=scholars')){
	echo "<text class = 'error'>	Sorry! But you are not part of the Sillcox ScholarShip Program. Only recipients are allowed to use this resource! </text>	";
}else if(strpos($fullUrl, 'error=passwd')){
	echo "<text class = 'error'> Passwords entered do not match ! Check again </text>	";

}else if (strpos($fullUrl, 'error=username')) {
	echo "<text class = 'error'> Sorry! Username is already taken. Enter a different username. </text>	";
}else if (strpos($fullUrl, 'error=short_pd')) {
	echo "<text class = 'error'> Password is too short. Password must be atleast of length 5 </text>	";
}else if (strpos($fullUrl, 'error=num_pd')) {
	echo "<text class = 'error'> Password must contain atleast one number in it.  </text>	";
}else if (strpos($fullUrl, 'error=lower_pd')) {
	echo "<text class = 'error'> Password must contain atleast one LOWER case character </text>	";
}else if (strpos($fullUrl, 'error=upper_pd')) {
	echo "<text class = 'error'> Password must contain atleast one UPPER case character </text>	";
}else if (strpos($fullUrl, 'error=short_user')) {
	echo "<text class = 'error'> Username is too short. Must be atleast of length 5 </text>	";
}else if (strpos($fullUrl, 'error=long_pd')) {
	echo "<text class = 'error'> Password is too long. Sorry. Max is 25. </text>	";
}else if (strpos($fullUrl, 'error=long_user')) {
	echo "<text class = 'error'> Username is too long. Max is 20. Sorry </text>	";
}else if (strpos($fullUrl, 'error=email_taken')) {
	echo "<text class = 'error'> Email is already taken !! Account already made !! </text>	";
}else if (strpos($fullUrl, 'stat=logout')) {
	session_destroy();
}else if (strpos($fullUrl, 'stat=delete_account')) {
	
	//We just delete an account 
	session_unset();
	session_destroy();

}

else{

}

?>



<form action="SignUp.php" method="POST">

	<input type="email" name="SignUp_Email" placeholder="Enter your email" required="true">

	<input type="text" name="SignUp_Username"	placeholder="Enter a username" required="true">

	<input type="password" name="SignUp_Password"	placeholder="Enter a password" required="true">

	<input type="password" name="SignUp_Password_again"	placeholder="Re-Enter your password" required="true">


	<input type="submit" name="submit">

</form>

</div>



<!-- Hidden until user changes it to sign in -->

<div class="enter" id = 'signIn_div' style="display: none;">
	
	<h1 style="background-color: inherit;	margin-top: 0px; margin-left: 150px;"> Sign In</h1>
	<p   onclick="show_signUp()">Don't have an account? Create an account</p>



<?php

if(strpos($fullUrl, 'error=login_err')){
	echo "<text class = 'error'> Login Error ! Input isnt correct </text>";
}


?>



<form action="SignIn.php" method="POST">
	
	<input type="text" name="SignIn_Username" placeholder="Enter username" required="true">
	<input type="password" name="SignIn_Password" placeholder="Enter password" required="true">
	<!-- 
	<p  id = 'signIn_show'>Show</p>
-->
	<input type="submit" name="Submit">

</form>


</div>






<footer>
	
<img src="Images/facebook.png" onclick="facebook_group()"	onmouseover="" style="cursor: pointer;" id = 'fb_img'>

<img src="Images/privacy.png" 	onclick="privacy_page()" 	onmouseover="" style="cursor: pointer;" id = 'privacy_img'>


</footer>

	



	

<script type="text/javascript">

//refresh url
window.onbeforeunload = function() {
   
	window.location.href = 'index.php';
}


function privacy_page(){
	window.location.href = 'privacy.html'; 
}



function facebook_group(){
	window.location.href = 'https://www.facebook.com/groups/785289731586913/';

}

function show_signIn(){
	var signUp_div = document.getElementById('signUp_div');
	signUp_div.style.display = 'none';


	var signIn_div = document.getElementById('signIn_div');
	signIn_div.style.display = 'block';

}


function show_signUp() {
	var signIn_div = document.getElementById('signIn_div');
	signIn_div.style.display = 'none';


	var signUp_div = document.getElementById('signUp_div');
	signUp_div.style.display = 'block';

}


</script>


</body>
</html>