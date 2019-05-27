<?php
session_start();

if(!($_SESSION['authenticated'])){
	header('location: index.php');
}

?>
<!DOCTYPE html>

<html>
<head>
	<title>Topics</title>
	<link rel="stylesheet" type="text/css" href="css/topics.css">
</head>
<body>


<div class="topics_div" >
	

	<!-- If they click on topic notes, but notes dont exist or were deleted --> 
	<?php

		$error = $_SERVER['QUERY_STRING'];	
		$fullUrl = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];	

			if(strpos($fullUrl, 'error=no_dir')){
				echo "<text class = 'error'> Error. Notes dont exists for this topic. Sorry for the inconvenience.  </text>	";
			}


		?>




<?php

	function displayTopics() {

		$_SESSION['subject'] =  $_GET['view_subject'];

		$subject = $_SESSION['subject'];

		$subject_dir = 	getcwd() . '/Notes/' . $subject;

		if(is_dir($subject_dir)){
			chdir($subject_dir);
		}else{
			//No notes for that subject yet
			header('location: Hub.php?error=no_dir');
			die();
		}

	
		$topics = glob('*', GLOB_ONLYDIR);

		foreach ($topics as $topic) {

			//Display topic with spaces if there are mutiple words					
			$topic_title = str_replace('_', ' ', $topic);

			echo    '<h2>'  . $topic_title  . '</h2>';
			echo '<img src = /SillcoxWeb/Images/folder.png  id ="' . $topic . '" onclick = "view_notes(this)"; class = "folder_img">';
			

			//get details (uploader & admin email) from details.txt
			$details_array = folderDetails($topic);

			echo '<h4>Uploader: ' . $details_array[0] . '</h4>';
			echo '<h4>Notes Reviewer: ' . $details_array[1] . '</h4>';

			echo '<hr>';

		}

	}	



	function folderDetails($topic){

		//Inside topic folder
		chdir(getcwd() . '/' . $topic);
		$details = file_get_contents('details.txt');

		//$uploader_email_index = strpos($details, 'Uploader:', );
		$uploader_email = substr($details, 9, strpos($details, 'Admin') - 9);
		
	
		$admin_email_index = strpos($details, 'Admin:');
		$admin_email = substr($details, strpos($details, 'Admin') + 6);

		return [$uploader_email, $admin_email];
		
	}

	displayTopics();



?>
	

</div>


	<script type="text/javascript">
		

	function view_notes(folder){

		console.log('Inside view_notes method');
		//id of folder , topic you want to view;
		var topic_name = folder.id;
		console.log('Id name: ' + topic_name);

		var url_encode = encodeURI('Notes.php?view_topic=' + topic_name);

		window.location.href = url_encode;

	}




	</script>







</body>
</html>