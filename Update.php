<?php

session_start();

/**
* 
Purpose: 

	- Changes Password
	- Changes Username 
	- Deletes Account 
*/
class Update{


	private $conn;

	function __construct(){
		$this->connect('localhost','root','xxxxxx','xxxxx');
	}

	function closeConnection(){
		mysqli_close($this->conn);
	}

	function query($query){
		return mysqli_query($this->conn, $query);
	}

	function connect($servername, $username, $password, $database){

	$this->conn = mysqli_connect($servername, $username, $password, $database);

		if(!$this->conn){
			die('Connection failed: ' . mysqli_connect_error());
		}else{
			echo '| Connected successfully';
		}
	}



	function headerErrors($error){
		header('location: Settings.php?error=' . $error);
		die();
	}



	function password_credentials(){

			$old_password = trim($_POST['old_password']);
			$new_password = trim($_POST['new_password']);
			$confirm_password = trim($_POST['confirm_new_password']);


			if($new_password != $confirm_password){		
				$this->headerErrors('pass_unequal');
			}

			$this->validPassword($new_password);
			$this->old_password_correct($old_password);


			//We pass the marks of checking is new password is valid & old password is correct. We can now change user's password
			$this->change_password($new_password);	
	}


	function change_password($new_password){

		require_once('Encryption.php');	
	
		$hashingObject = new Encryption();
		$hash = $hashingObject->encrypt($new_password);

		$email = $_SESSION['email'];

		$sql_password_query = "UPDATE Info Set Password = '$hash' WHERE Email = '$email' ";
		$this->query($sql_password_query);

		header('location: Settings.php?status=password_changed');
		die();

	}


	function old_password_correct($old_password){
			$sql_encrypt_password =  $this->sql_password();

			require_once('Encryption.php');
			$obj = new Encryption();
				
			$is_valid_password = $obj->verify($old_password, $sql_encrypt_password);
			
			if(!$is_valid_password){
				$this->headerErrors('old_password_incorrect');
			}
		}
		


	function sql_password(){


		$email = $_SESSION['email'];
		//$email = $this->users_email();

		$sql_encrypt_query = "SELECT Password FROM Info WHERE Email = '$email'";
		$result = $this->query($sql_encrypt_query);

		$row = $result->fetch_assoc(); 
		$sql_password =  $row['Password'];

		return $sql_password;

	}


	
	function validPassword($password){
		if(strlen($password) < 4){
			$this->headerErrors('short_pass');
		}
		if(strlen($password) > 25){
			$this->headerErrors('long_pass');
		}

		if(!preg_match('@[0-9]@', $password)){
			$this->headerErrors('no_numbers');
			
		}
		if(! preg_match('@[a-z]@', $password)){
			$this->headerErrors('no_lowercase');
			
		} 
		if(!preg_match('@[A-Z]@', $password)){
			$this->headerErrors('no_upper');
		}
		if(strpos($password, ' ')){
			$this->headerErrors('space_char');
		}	

	}


	function deleteAccount(){

		$delete_writing = $_POST['delete_confirm'];
		$delete_writing = trim($delete_writing);

		if($delete_writing != "delete my account"){
			$this->headerErrors('delete_account');
		}

		//Remove email from text files. In order to stop receiving emails about notes
		$this->fileRemove($_SESSION['email']);

		//Deletes from SQL database.
		$email = $_SESSION['email'];
		$delete_query = "Delete FROM Info WHERE Email = '$email'";

		$this->query($delete_query);
		header('location: index.php?stat=delete_account');
		die();

	}


	function fileRemove($email){

		$admin_file = file_get_contents('subjectAdmin.txt');
		$admin_file = str_replace($email, 'sillcoxhelp@gmail.com', $admin_file);

		file_put_contents('subjectAdmin.txt', $admin_file);
	}

	



	function username_credentials($new_username, $confirm_username){

		$new_username = trim($new_username);
		$confirm_username = trim($confirm_username);

		if($new_username != $confirm_username){
			$this->headerErrors('username_unequal');
		}


		//Check to see if username has valid credentials
		if(strlen($new_username) < 4){
			$this->headerErrors('username_short');
		} if(strlen($new_username) > 20){
			$this->headerErrors('username_long');
		}if(strpos($new_username, ' ')){
			$this->headerErrors('space_char');
		}



		//Check to see if there isnt already a user with that name in the database.
		$this->user_already_exists($new_username);

		//Change username
		$email  = $_SESSION['email'];

		$sql_change_username = "UPDATE Info Set Username = '$new_username' WHERE Email = '$email';";
		$this->query($sql_change_username);

		// ** Change session variable so it displays in website **	
		$_SESSION['username'] = $new_username;

		header('location: Settings.php?status=username_changed');
		die();


	}




	function user_already_exists($new_username){

		$user_exits_query = "SELECT * FROM Info WHERE Username = '$new_username' ";	
	 	$results = $this->query($user_exits_query);

	 	if($results->num_rows > 0){
	 		$this->headerErrors('username_taken');
	 		die();
	 	}
	}




	function main(){
	

		if(isset($_POST['old_password']) && isset($_POST['new_password']) && isset($_POST['confirm_new_password'])){
			$this->password_credentials();

		} else if(isset($_POST['delete_confirm']) && !empty($_POST['delete_confirm'])){
			$this->deleteAccount();
		}else if(isset($_POST['new_username']) && isset($_POST['confirm_new_username']) &&  !empty($_POST['new_username'])) {
		
			$new_username = $_POST['new_username'];
			$confirm_username = $_POST['confirm_new_username'];

			$this->username_credentials($new_username, $confirm_username);
		} else{

		}

	}



}//class ends



$update = new Update();
$update->main();


?>