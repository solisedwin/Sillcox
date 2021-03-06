
<?php
session_start();


if(!($_SESSION['authenticated'])){
	header('location: index.php');
}

?>
<!DOCTYPE html>

<html>
<head>
	<title>Document Hub</title>
	<link rel="stylesheet" type="text/css" href="css/hub.css">
</head>
<body>

	


</div>


<header>

<!--  
<img src="Images/logo.png">
-->
		<span style="display: inline-block; position: absolute; margin-top: 75px;">
				<?php
				echo '<h2> Hello ' .  $_SESSION['username'] . '</h2>';
				?>

		</span>
	

		<div id = 'logOut_div' onclick="logOut()" style = "cursor: pointer; ">
			<p>Log Out</p>
		</div>

		<div id = 'logOut_div' onclick="settings()" style = "cursor: pointer; ">
			<p>Settings</p>
		</div>

	
		<span id = 'upload_span'>
		<p  style = 'font-size: 25px; cursor: pointer;' onclick='uploadNotes()'	> Submit Notes </p> 
		</span>


</header>

<center>
	<div id = 'notes_div'>
	
	<h2>Search Notes for Subject</h2>

	<form method="GET" action="Topics.php">

				<select name="view_subject" style="width: 400px; height: 31px;">
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

					<?php  
					
					$init_subjects = array('Precalc','CalcI','CalcII','CalcIII','CalcIV','Stat','Discrete_Math','Micro_Bio','Anatomy_Phy_I','PhyI',
						'PhyII','ChemI','ChemII','CseI','MacroEco','MicroEco');

					$notes_dir = __DIR__ . '/Notes/';
					chdir($notes_dir);

					$topics = glob('*', GLOB_ONLYDIR);

					foreach ($topics as $topic) {

						//not original choices
						if(!in_array($topic, $init_subjects)){

						$topic_spaces = str_replace('_', ' ', $topic);
						echo "<option value='$topic'>" . ($topic_spaces) . "</option>";
						
						}
					
					}
					?>



				</select>

				<br>

				<input type="Submit" id = 'view_btn' name="Submit" value="View Notes">

			</form>		
		</div>


			<?php
				

				$error = $_SERVER['QUERY_STRING'];	
				$fullUrl = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];	

				if(strpos($fullUrl, 'error=empty_feedback')){
					echo "<text class = 'error'> Error. Feedback message is empty </text>	";
				}else if(strpos($fullUrl, 'feedback=sent')){
					echo "<text class = 'good'> Feedback has been sent ! </text>";

					unset($_SESSION['feedback']);
					unset($_SESSION['emailTo']);

				}else if (strpos($fullUrl, 'error=no_dir')) {
					echo "<text class = 'error'> Sorry ! There arent any notes for that subject yet . </text>";
				}


			?>

		

	</center>






<footer>
	
	
	<center>
		<h3> Have comments, questions, or concerns about the website? Or request to be an admin for a specific subject? Email us. </h3>




	<form action="Feedback.php" method="POST">
		
	<textarea rows="9" cols="60" name="feedback_msg">
		

	</textarea> 


	<input type="Submit" name="Submit" value="Submit Feedback">


	</form>


	</center>






</footer>





<script type="text/javascript">
	

function logOut(){

	window.location.href = 'index.php?stat=logout';

}

function uploadNotes(){
	window.location.href = 'upload.php';
}


function settings(){
	window.location.href = 'Settings.php';

}

function admin(){
	window.location.href = 'Admin.php';
}





</script>	









</body>
</html>