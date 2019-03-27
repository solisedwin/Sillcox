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

	
		<span id = 'upload_span'>
		<p  style = 'font-size: 25px; cursor: pointer;' onclick='uploadNotes()'	> Upload Notes </p> 
		</span>


</header>





<center>
	<div id = 'notes_div'>
	
	<b>	
	<label  style="font-size: 19px; ">Courses</label> </b>

				<select name="subject" 	style="width: 200px; height: 31px;">
					<option value="Precalc">Pre Calculus</option>
					<option value="CalcI">Calculus I</option>
					<option value="CalcII">Calculus II</option>
					<option value="CalcIII">Calculus III</option>
					<option value="CalcIV">Calculus IV</option>
					<option value="Stat">Probability and Statistics</option>
					<option value="Discrete_Math">Discrete Mathematics</option>
					<option value="Micro_Bio">Micro Biology</option>					
					<option value="Anatomy_Phy_I">Anatomy and Physiology I</option>
					<option value="PhyI">Physics I</option>	
					<option value="PhyII">Physics II</option>	
					<option value="ChemI">Chemistry I</option>
					<option value="ChemII">Chemistry II</option>			
					<option value="CseI">Computer Science I</option>
					<option value="MacroEco">Macro Economics</option>
					<option value="MicroEco">Micro Economics</option>
				</select>

				<br>
						

		</div>
	</center>




<footer>
	
	
	<center>
		<h3 >Have comments, questions, or concerns about the website? Email us your 
		feedback </h3>




	<form action="Feedback.php" method="POST">
		
	<textarea rows="9" cols="60" name="feedback_msg">
		

	</textarea>


	<input type="Submit" name="Submit" value="Submit Feedback">


	</form>


<?php


	$error = $_SERVER['QUERY_STRING'];	
	$fullUrl = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];	

	if(strpos($fullUrl, 'error=empty_feedback')){
		echo "<text class = 'error'> You didnt write anything as feedback ! </text>	";
	}else if(strpos($fullUrl, 'feedback=sent')){
		echo "<text class = 'error'> Feedback has been sent ! </text>";

		unset($_SESSION['feedback']);
		unset($_SESSION['emailTo']);


	}else{

	}


?>






	</center>






</footer>





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