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

		$this->connect('localhost','root','xxxxx','xxxxxxx');

	}

	//closes mysql conneciton
	function closeConnection(){
		mysqli_close($this->conn);
	}


	function query($username_query){
		return mysqli_query($this->conn, $username_query);
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
			$uploadSize += $fileSize;
		}

		if($uploadSize > 500000){
			header('location: upload.php?error=size');
			die();
		
			}
		}



	function extensions(){

		$extensions = array("txt","pdf","img","jpg","docx","doc","tex","png");
		$invalid_ext = '';


		for($i = 0; $i < count($_FILES['files']['name']); $i++){

			$fileName = $_FILES['files']['name'][$i];
			$ext = pathinfo($fileName, PATHINFO_EXTENSION);
			echo $ext . '<br>';

			if(in_array($ext, $extensions) == false){
				echo $fileName  .  ' extension is not in array' . '<br>';
				$invalid_ext .=  '~' . $fileName;
			}	

		}

		header('location: upload.php?' . $invalid_ext);

	}
	


	}




$sub = new Submission();
$sub->credentials();






?>