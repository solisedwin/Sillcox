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

	<span>
		<input type="search" name="search_query" placeholder="Search subject">
		<img src="Images/search.png" id = 'search_icon'	style="cursor: pointer;" >
	</span>

	<div id = 'logOut_div' onclick="logOut()" style = "cursor: pointer; ">
		<p>Log Out</p>
	</div>

	<?php

	if($_SESSION['admin'] == 1){

		echo "

		<span id = 'upload_span'>
		<p  style = 'font-size: 25px; cursor: pointer;' onclick='uploadNotes()'	> Upload Notes </p> </span>

		";

	}


	?>


</header>





<center>
	<div id = 'notes_div'>
	<span><h2>Available notes </h2>	</span>



	</div>
</center>

















<script type="text/javascript">
	

function logOut(){

	window.location.href = 'index.php?stat=logout';

}

function uploadNotes(){
	window.location.href = 'upload.php';
}






</script>	









</body>
</html>