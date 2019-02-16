<!DOCTYPE html>
<?php
session_start();


if(!($_SESSION['authenticated'])){
	header('location: index.php');
}


?>

<html>
<head>
	<title>Document Hub</title>
	<link rel="stylesheet" type="text/css" href="css/hub.css">
</head>
<body>



<header>

<img src="Images/logo.png">


<input type="search" name="search_query" placeholder="Search subject">



<div id = 'logOut_div' onclick="logOut()" style = "cursor: pointer; ">
	<p>Log Out</p>
</div>



</header>


<script type="text/javascript">
	

function logOut(){

	window.location.href = 'index.php';






}





</script>	









</body>
</html>