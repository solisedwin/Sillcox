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
		$this->connect('localhost','root','xxxxx','xxxxx');
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

		if (!isset($_FILES['files']) || empty($_FILES['files']) || is_null($_FILES['files'])) {
			header('location: upload.php?error=empty');
			die();
		}


		if(!isset($_POST['topic']) || empty($_POST['topic']) || strlen(trim($_POST['topic'])) == 0 ){
			header('location: upload.php?error=topic_err');
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

		$extensions = array("pdf","img","jpg","docx","doc","png");
		

		for($i = 0; $i < count($_FILES['files']['name']); $i++){

			$fileName = trim($_FILES['files']['name'][$i]);
			$ext = pathinfo($fileName, PATHINFO_EXTENSION);
			

			if(in_array($ext, $extensions) == false){
				$GLOBALS['invalid_ext'] .=  '~' . $fileName;
			
				//delete file because its extension is not supported
				unlink($_FILES['files']['tmp_name'][$i]);

			}	

		}
		
	}


	

	function topic_directory_check($subject){

		//check if topic dir name already exists, if so. Just append a number to it.

		//all directories within the subject folder 
		$dirs = glob("*", GLOB_ONLYDIR);

		$count_dir = 0;

		for($i = 0; $i < count($dirs); $i++){
			if($dirs[$i] == $subject){
				$count_dir += 1; 
			}
		}


		//Mutiple dirs with the same name
		if($count_dir != 0){
			return $subject . '(' . $count_dir . ')';
		}
		//No sub dir with that same topic name
		else{
			return $subject;
		}

	}




	function set_session_variables(){


		//Subject was written down because it wasnt offered in notes
		if(isset($_POST['specific_subject']) && !empty($_POST['specific_subject'])){

			$subject = trim($_POST['specific_subject']);
			$subject = preg_replace('/\s+/', '_', $subject);
			$_SESSION['subject'] = $subject;

		}else{
			
			$subject = trim($_POST['subject']);
			$_SESSION['subject'] = $subject;
		}


		//There is message that needs to be sent to admin. Written from uploader
		if(isset($_POST['msg']) && !empty($_POST['msg'])){
			$_SESSION['msg'] = $_POST['msg'];
		}
	

		$topic = preg_replace('/\s+/', '_', trim($_POST['topic']));
		$_SESSION['topic'] = $topic;
		
	}


	

	//Saves all files to given subejct directory 
	function save(){

		// cd (/var/www/html/SillcoxWeb/Notes/)

		$this->set_session_variables();


		$subject = $_SESSION['subject'];
		$notesDir = __DIR__ . '/Notes/';


		chdir($notesDir);
		$subject_notes_dir = getcwd() . '/' . $subject;

		$topic = $_SESSION['topic'];
		
		
		//There isnt a specific directory for this subject
		if(!file_exists($subject_notes_dir)){
			//Make subject directory, change to subject directory 

			mkdir($subject_notes_dir);	
			chdir($subject_notes_dir);

			//Make directory for topic, change to topic directory
			
			$topic_dir = getcwd() . '/' . $topic;


			mkdir($topic_dir);
			chdir($topic_dir);

	
		}
		//There is already a directory with this specific subject
		else{
			chdir($subject_notes_dir);
		
			//check to see if there is already a topic folder with the same name.
			$topic_folder_name = $this->topic_directory_check($topic);
			//Make and change to topic dir 
		
			$topic_dir = getcwd() . '/' . $topic_folder_name;
			
			mkdir($topic_dir);
			chdir($topic_dir);

		}	



		try {

			$new_files_array = [];
			$extensions = array("pdf","img","jpg","docx","doc","png");
		
					
			for ($i=0; $i < count($_FILES['files']['size']); $i++)	 { 

				//Get extension
				$fileName = trim($_FILES['files']['name'][$i]);

				//Filename has spaces in it
				if(strpos($fileName, ' ')){
					$fileName = preg_replace('/\s+/', '_', $fileName);
				}


				$ext = pathinfo($fileName, PATHINFO_EXTENSION);

				if(in_array($ext, $extensions)){

					array_push($new_files_array, $fileName);
					move_uploaded_file($_FILES['files']['tmp_name'][$i], getcwd() . '/'. ($fileName));
			
				}

			}

				$_SESSION['new_files'] = $new_files_array;

				$this->folder_details();

			
				} catch (Exception $e) {
					echo '~~ Error! File couldnt be uploaded. Reason: ' . $e->getMessage();
				}		

	}



	//Saves uploders email content info and admins email info. Save and display for when we view it in Topics.php
	function folder_details(){

		$subject_admin_file = file_get_contents('/var/www/html/SillcoxWeb/subjectAdmin.txt');

		//No admin for this subject (maybe specfic subject); sent to SillcoxHelp
		if(strpos($subject_admin_file, $_SESSION['subject']) == false){
			$_SESSION['emailTo'] = 'sillcoxhelp@gmail.com';
		}else{ 
			//There is an admin for this subject
			$subject_index	= strpos($subject_admin_file, '=');
			//Gets rest of the line, which is the admin email for who is in charge of reviewing notes for this course. 
			$adminEmail = substr($subject_admin_file,$subject_index + 1);

			$_SESSION['emailTo'] = $adminEmail;
		}


		$uploader_email = $_SESSION['email'];
		$admin_email = $_SESSION['emailTo'];


		$content = 'Uploader:' . $uploader_email . "\n" . 'Admin:' . $admin_email; 
	

		//Writes details to file.
		$fp = fopen(getcwd() . "/details.txt","wb");
		fwrite($fp,$content);
		fclose($fp);

	}




	function email(){

		require_once('Email.php');
	}


}//end of class 



$invalid_ext = '';


$sub = new Submission();
$sub->credentials();
$sub->save();
$sub->email();



//All files uploaded were valid. Passsed credentials. 
if(empty($invalid_ext)){
	header('location: upload.php?upload=sent');
}else{
	header('location: upload.php?~' . $GLOBALS['invalid_ext']);
}

?>