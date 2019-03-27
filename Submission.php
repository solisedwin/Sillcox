<?php


session_start();



/**
Purpose: 

- Check if any notes have been uploaded
- Check size of each file
- Check correct extensions 
- Check who is in charge of course from SQL database. Then email that admin. (If no one , email to SillcoxHelp@gmail.com)
-  Save notes to local server. 

*/
class Submission {
	

	function __construct() {

		$this->connect('localhost','root','xxxxxxxx','xxxxxxxx');

	}

	//closes mysql conneciton
	function closeConnection(){
		mysqli_close($this->conn);
	}


	function query($_query){
		return mysqli_query($this->conn, $_query);
	}


	function connect($servername, $username, $password, $database){

		$this->conn = mysqli_connect($servername, $username, $password, $database);

			if(!$this->conn){
				die('Connection failed: ' . mysqli_connect_error());
			}else{
				echo '| Connected successfully' . '<br>';
			}
	}



	function credentials(){

		$this->isEmpty();
		$this->fileSize();
		$this->extensions();
	}


	function isEmpty(){

		if (!isset($_FILES['files']) || empty($_FILES['files'])) {
			header('location: upload.php?error=empty');
			die();
		}
	}

	function fileSize(){
				
		$uploadSize = 0;
		for($i = 0; $i < count($_FILES['files']['size']); $i++){
			
			$fileName = $_FILES['files']['name'][$i];
			$fileSize = $_FILES['files']['size'][$i];

			$uploadSize += $fileSize;
		}

		if($uploadSize > 500000){
			header('location: upload.php?error=size');
			die();
		
			}
		}



	function extensions(){

		$extensions = array("txt","pdf","img","jpg","docx","doc","tex","png");
		

		for($i = 0; $i < count($_FILES['files']['name']); $i++){

			$fileName = $_FILES['files']['name'][$i];
			$ext = pathinfo($fileName, PATHINFO_EXTENSION);
			

			if(in_array($ext, $extensions) == false){
	
				$GLOBALS['invalid_ext'] .=  '~' . $fileName;
				//delete file because its extension is not supported
				unlink($_FILES['files']['tmp_name'][$i]);

			}	

		}
		
	}
	

	//Saves all files to given subejct directory 
	function save(){

		
		// cd (/var/www/html/SillcoxWeb/Notes/)

		$subject = $_POST['subject'];
		$_SESSION['subject'] = $subject;

		echo 'Subject selected: ' . $subject . '<br>';


		$notesDir = __DIR__ . '/Notes/';
			
		chdir($notesDir);
		$subject_notes_dir = getcwd() . '/' . $subject;

		
		//There isnt a specific directory for this subject
		if(!file_exists($subject_notes_dir)){

			mkdir($subject_notes_dir);	
			chdir($subject_notes_dir);
		}else{
			chdir($subject_notes_dir);
		}	

		echo 'Current directory: ' . getcwd();

		echo '<pre>';
		var_dump($_FILES['files']);


		try {
					
			for ($i=0; $i < count($_FILES['files']['size']); $i++)	 { 
				move_uploaded_file($_FILES['files']['tmp_name'][$i], getcwd() . '/'. ($_FILES['files']['name'][$i]));
			}


				} catch (Exception $e) {
					echo '~~ Error! File couldnt be uploaded. Reason: ' . $e->getMessage();
				}		
	}



	function email(){

		//Know who is submitting these files 
		$this->users_email();

		$subject_admin_file = file_get_contents('/var/www/html/SillcoxWeb/subjectAdmin.txt');

		if(strpos($subject_admin_file, $_SESSION['subject']) == false){
			
			$_SESSION['emailTo'] = 'sillcoxhelp@gmail.com';
		
		}else{ 
			//There is an admin for this subject

			$subject_index	= strpos($subject_admin_file, '=');
			//Gets rest of the line, which is the admin email for who is in charge of reviewing notes for this course. 
			$adminEmail = substr($subject_admin_file,$subject_index + 1);


			$_SESSION['emailTo'] = $adminEmail;
		}

		include_once('Email.php');

	}



	function users_email(){

		$username = $_SESSION['username'];

		$users_email_query = "SELECT Email FROM Info WHERE Username = '$username' ";
		$result = $this->query($users_email_query);
		$rows = $result->fetch_assoc();
		$_SESSION['user_email'] = $rows['Email'];

	}


 


	}//end of class 


$invalid_ext = '';

$sub = new Submission();
$sub->credentials();
$sub->save();
$sub->email();

header('location: upload.php?' . $GLOBALS['invalid_ext']);




?>