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
			for your submission.    

			</p>

	</div>


	<center>

	<div id = 'submit_form'>

		<form action="Submission.php" method="GET">
		

			<span class="submit_span">
				
				<b>	<label for="course" style="font-size: 19px; ">Courses</label> </b>

					<select name="select_course" 	style="width: 200px; height: 31px;">
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

					<br>
					
					<br>
			<b>	<label>If Subject isnt listed, write it.<input type="text" placeholder="Specific Subject" name="specific_subject"> </label>  </b>

			</span>	
			<br><br>

			<hr>

		<b>	<label  >Have a specific message involving the notes being sent? Please write the message below.</label> </b>
			<textarea value = 'textarea' cols="70" rows="7" style="font-size: 13px; margin-bottom: 10px;" name = 'msg'>
				

			</textarea>

		</form>
			
			<hr style="position: inherit; top: 10px;">


		<form action="Submission.php" method="POST">

			<div  id = 'file_div'>

				<b>	<label>Submit Files</label> </b>

				<input type="file" name="inputFiles" onchange="displayFile(this.value)" value="files" style="cursor: pointer;">
				<input type="reset" value="Reset" onclick="clear_para()" style="cursor: pointer;">

			</div><br> <br> <br>	

			<div id="files_selected_div">
				<p id="files_selected_para" style="height: auto; width: auto;">
					
				</p>
			</div>
				
			<input type="submit" value="Submit Files" style="cursor: pointer;">


		</form>


	<!-- end of submit form div -->	
	</div>


	</center>














<script type="text/javascript">		

	function clear_para(){

		var para = document.getElementById('files_selected_para');
		para.innerHTML = '';

	}



	function redirect_btn(whereTo) {
	
		if(whereTo == 'home'){
		window.location.href = 'Hub.php';
		}else{
			window.location.href = 'index.php?stat=logout';
		}

	}


	function displayFile(val){

		var fileName = val.substr(val.lastIndexOf("\\")+1, val.length);
    	document.getElementById("files_selected_para").innerHTML +=	'<br>' + fileName ;

	}

	function clear(val){






	}





</script>










</body>
</html>