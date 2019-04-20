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

	

<div class="topics_div">
	


<?php


	function displayTopics() {

		$subject = $_GET['view_subject'];
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
			
			echo    '<h2>'  . $topic  . '</h2>';
			echo '<img  src = /SillcoxWeb/Images/folder.png   class = folder_img >';
			

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










</body>
</html>