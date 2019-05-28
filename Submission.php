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
		$this->connect('localhost','root','xxxxxxxx','xxxxxxx');
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
		
	}

	function set_temp_dir(){

		chdir('/var/www/html/SillcoxWeb');


		if(!is_dir(getcwd() . '/tmp_uploads') ){

			echo '** Made new dir **';
			$tmp_dir =  getcwd() . '/tmp_uploads';
			mkdir($tmp_dir);

		}

	}



	function uploaded_notes(){

		echo '<pre>';
		var_dump($_FILES);

		try {
	
			$new_files_array = [];
			$extensions = array("pdf","img","jpg","docx","doc","png");
	

			for ($i = 0; $i < count($_FILES['files']['size']); $i++) { 

				$fileName =  ($_FILES['files']['name'][$i]);
				$tmp_file = $_FILES['files']['tmp_name'][$i];


				//Filename has spaces in it
				if(strpos($fileName, ' ')){
				  $fileName = preg_replace('/\s+/', '_', $fileName);
				}


				$ext = pathinfo($fileName, PATHINFO_EXTENSION);

				if(in_array($ext, $extensions) && is_uploaded_file($tmp_file)) {

					//Add uploaded files to array 
					//array_push($new_files_array, $_FILES['files']['tmp_name'][$i]);


					//User is admin, so they can upload notes.
					if($_SESSION['admin']){
					 	move_uploaded_file($tmp_file, getcwd() . '/'. ($fileName));
					}else{
						//move to temp folder. Email content of folder, then delete content
						chdir('/var/www/html/SillcoxWeb');

						move_uploaded_file($tmp_file, getcwd() . '/tmp_uploads/' . $fileName);


					}


				}

			}


				//$_SESSION['new_files'] = $new_files_array;

			
				} catch (Exception $e) {
					echo '~~ Error! File couldnt be uploaded. Reason: ' . $e->getMessage();
				}		

	}



	function emailTo(){

		if(file_exists('subjectAdmin.json')){

		$json_tabs = file_get_contents('subjectAdmin.json');

		//Remove tabs from json file
		$json_tabs = trim(preg_replace('/\t+/', '', $json_tabs));
		$json = json_decode($json_tabs,true);


		if(!isset($json['Subjects'][$_SESSION['subject']])){
			$_SESSION['emailTo'] = 'sillcoxhelp@gmail.com';
		}else{

		$admin = $json['Subjects'][$_SESSION['subject']];

		echo 'Admin: ' . $admin;
		$_SESSION['emailTo'] = $admin;

		}
		
	}else{
		$_SESSION['emailTo'] = 'sillcoxhelp@gmail.com';
	}


	}




	//Saves uploders email content info and admins email info. Save and display for when we view it in Topics.php
	function folder_details(){

		//Find correct admin for this subject . And give them credit. 
		$this->emailTo();
		$admin_email = $_SESSION['emailTo'];

		if(isset($_POST['uploader'])){
			$uploader_email = $_POST['uploader'];
		}else{
			$uploader_email = 'sillcoxhelp@gmail.com';
		}


		$content = 'Uploader:' . $uploader_email . "\n" . 'Admin:' . $admin_email; 	

		//Writes details to file.
		$fp = fopen(getcwd() . "/details.txt","wb");
		fwrite($fp,$content);
		fclose($fp);

	}


	//Remove all tmp files client sent after emailing them. To make room for further notes
	function delete_tmpfiles(){

		$tmp_dir = getcwd() . '/tmp_uploads';

		if(is_dir($tmp_dir)){

			$tmp_files = scandir($tmp_dir);

			foreach ($tmp_files as $file) {
				unlink($tmp_dir . '/' . $file);
			}
		}


	}


 
	function main(){


		$this->credentials();

		//Current user isnt admin(Status: 0). Emails notes to subject admin 
		if(!$_SESSION['admin']){

			$this->set_session_variables();
			$this->set_temp_dir();
			$this->uploaded_notes();
			
			//Know who to email notes to and write txt file of uploader for credit
			$this->emailTo();	

			echo '<pre>';
			var_dump($_SESSION);

			require_once('Email.php');

			$this->delete_tmpfiles();

			header('location: upload.php?upload=sent');
		
		}
		//Current user is an admin, so we trust there notes. Thus notes automatically gets uploaded to site. 
		else{		

			$this->save();
			$this->uploaded_notes();
			$this->folder_details();
			header('location: upload.php?upload=sent');

		}

		
		
	}

}//end of class 



$invalid_ext = '';


$sub = new Submission();
$sub->main();

/*

//All files uploaded were valid. Passsed credentials. 
if(empty($invalid_ext)){
	header('location: upload.php?upload=sent');
}else{
	header('location: upload.php?~' . $GLOBALS['invalid_ext']);
}
*/
?>