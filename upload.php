<?php
session_start();

if(!($_SESSION['authenticated'])){
	header('location: index.php');
}

unset($_SESSION['emailTo']);
unset($_SESSION['subject']);

?>
<!DOCTYPE html>
<html>

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

		<div class = 'header_btn' onclick="redirect_btn('out')">
		Log Out
		</div>

		<div class="header_btn" onclick="redirect_btn('home')">
		Home Page	
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

		<form action="Submission.php" method="POST" enctype = "multipart/form-data">
		
			<span class="submit_span">
				
				<b>	<label  style="font-size: 19px; ">Courses</label> </b>

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
					
					<br>
			<b>	<label>If Subject isnt listed, write it.<input type="text" placeholder="Specific Subject" name="specific_subject"> </label>  </b>

			</span>	
			<br><br>

			<hr>

		<b>	<label  >Have a specific message involving the notes being sent? Please write the message below.</label> </b>
			<textarea  cols="70" rows="7" style="font-size: 13px; margin-bottom: 10px;" name = 'msg'>
				

			</textarea>

		
			
			<hr style="position: inherit; top: 10px;">


	

			<div  id = 'file_div'>

				<b>	<label>Submit Files</label> </b>

				<input type="file" name="files[]"   id = 'selected_files'   size="9" multiple onchange="displayFile(this.value)"  style="cursor: pointer;">
				<input type="reset" value="Reset" onclick="clear_para()" style="cursor: pointer;">

			</div><br> <br> <br>	

			<div id="files_selected_div">
				<p id="files_selected_para" style="height: auto; width: auto;">
					
			

				</p>
			</div>
				
			<input type="submit" value="Submit Files" name = 'submit' style="cursor: pointer;">


		</form>


	<!-- end of submit form div -->	
	</div>



		<?php

		$error = $_SERVER['QUERY_STRING'];	
		$fullUrl = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];	

		if(strpos($fullUrl, 'error=empty')){
			echo "<text class = 'error'> Error ! You didnt upload any files.  </text>";
		}
		else if (strpos($fullUrl, 'upload=sent')){
			echo "<text class = 'good'> File has been sent for review. Thank you for your contribution. </text>";
			//Erase valid files that were previously uploaded. 
			unset($_SESSION['new_files']);
		}

		else if (strpos($fullUrl, 'error=size')){
			echo "<text class = 'error'> Error ! File size is too large! Try to upload smaller files or one at a time. </text>";
		}else if (strpos($fullUrl, '~')) {
			$invalid_ext_string = substr($fullUrl, strpos($fullUrl,'~') + 1);
			$invalid_extensions = explode('~', $invalid_ext_string);	
			$ext_list = '';

			foreach ($invalid_extensions as $fileName) {
				$ext_list .= $fileName . ',';	
			}

			//Counts occurences of '~', to know which message to display
			if(substr_count($ext_list, ',') == 1){

				echo "<text class = 'error'> $ext_list file couldnt be uploaded because of its extension. </text>";
				echo '<br>';
				echo "<text class = 'error'> Please view list of valid extensions to upload files above. </text>";

			}else{

			echo "<text class = 'error'> $ext_list couldnt be uploaded because of their extensions. </text>";
			echo '<br>';
			echo "<text class = 'error'> Please view list of valid extensions to upload files above. </text>";

			}


		}else{

		}

		?>




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

		var files = document.getElementById("selected_files").files;

		for (var i = 0; i < files.length; i++) {
 			//alert(files[i].name);
			var para = document.getElementById('files_selected_para');
			para.innerHTML += files[i].name  + '<br>';

		}

	}

</script>





</body>
</html>