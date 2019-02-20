<!DOCTYPE html>
<html>

<?php

session_start();

?>

<head>
	<title>Upload Notes</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/upload.css">
</head>
<body>


	<header>
	

	<div>
		
		</div>


		<?php

		echo "
		<p 	id = 'header_greeting'>

		Hello " .  $_SESSION['username'];
		'</p>';


		?>


		<div class="header_btn" onclick="redirect_btn('home')">
		Home Page	
		</div>

		<div class = 'header_btn' onclick="redirect_btn('out')">
		Log Out

		</div>

	</header>




	<div id = 'msg'>
			<p>
				
			The following link 'Upload Notes' is only provided to students who 
			are deem responsible and have excelled in a certain academic subject. 
			You can upload notes based on the subject you think you could contribute to.    			 

			<br><br>
			All submissions	will be sent to the website email for further inspection. Your email will be used to give credit
			of your submission.    

			</p>

	</div>


	<center>

	<div id = 'submit_form'>

		<form action="submission.php" action="POST">
		

			<div class="submit_div">
				
				<b>	<label for="course">Courses</label> </b>

					<select>
						<option value="precalc">Pre Calculus</option>
						<option value="calcI">Calculus I</option>
						<option value="calcII">Calculus II</option>
						<option value="calcIII">Calculus III</option>
						<option value="calcIV">Calculus IV</option>
						<option value="stat">Probability and Statistics</option>
						<option value="dm">Discrete Mathematics</option>
						<option value="mb">Micro Biology</option>					
						<option value="apI">Anatomy and Physiology I.</option>
						<option value="phyI">Physics I</option>	
						<option value="phyII">Physics II</option>	
						<option value="chemI">Chemistry I</option>
						<option value="chemII">Chemistry II</option>			
						<option value="cseI">Computer Science I</option>
						<option value="macroEco">Macro Economics</option>
						<option value="microEco">Micro Economics</option>
					</select>


			</div>	







		</form>

	</div>


	</center>














<script type="text/javascript">		

	function redirect_btn(whereTo) {
	
		if(whereTo == 'home'){
		window.location.href = 'Hub.php';
		}else{
			window.location.href = 'index.php?stat=logout';
		}

	}

</script>










</body>
</html>