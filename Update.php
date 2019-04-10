<?php

session_start();

/**
* 
*/
class Update{


	private $conn;

	function __construct(){
		$this->connect('localhost','root','xxxxxxx','xxxxxx');
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


			if($new_password != $confirm_new_password){		
				$this->headerErrors('pass_unequal');
			}


			$this->validPassword($new_password);
			/*
			$sql_pass = $this->sql_password();

			if($old_password != $sql_pass){
				$this->headerErrors('sql_pass_unequal');
			}*/

			$this->old_password_correct($old_password, $new_password);


			//We pass the marks of checking is new password is valid & old password is correct. We can now change user's password
			$this->change_password($new_password);	

		
	}

	function change_password($new_password){

		require('Encryption.php');	
	
		$hashingObject = new Encryption();
		$hash = $hashingObject->encrypt($new_password);

		$username = $_SESSION['username'];

		$sql_password_query = "UPDATE Info Set Password = '$hash' WHERE Username = '$username' ";
		$this->query($sql_password_query);
		header('location: Settings.php?status=password_changed');
		die();

	}







	function old_password_correct($old_password, $newPassword){
			$sql_encrypt_password =  $this->sql_password();

			require('Encryption.php');
			$obj = new Encryption();
			
			$is_valid_password = $obj->verify($old_password, $sql_encrypt_password);
			
			if($is_valid_password == False){
				$this->headerErrors('old_password_incorrect');
				
			}
		
	}



	function sql_password(){

		$user = $_SESSION['username'];
		$sql__query = "SELECT Password FROM Info WHERE Username = '$user' ";
		$result = $this->query($sql_encrypt_query);

		$row = $result->fetch_assoc(); 
		$sql__password =  $row['Password'];

		return $sql__password;

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

	}


	function deleteAccount(){

		$delete_writing = $_POST['delete_confirm'];
		$delete_writing = trim($delete_writing);

		if($delete_writing != "delete my account"){
			$this->headerErrors('delete_account');
		}

		$username = $_SESSION['username'];
		$delete_query = "Delete FROM Info WHERE Username = '$username'";

		$this->query($delete_query);
		header('location: index.php?stat=delete_account');
		die();

	}

	



	function username_credentials($new_username, $confirm_username){

		$new_username = trim($new_username);
		$confirm_username = trim($confirm_username);

		if($new_username != $confirm_username){
			$this->headerErrors('username_unequal');
		}


		if(strlen($new_username) < 4){
			$this->headerErrors('username_short');
		} if(strlen($new_username) > 20){
			$this->headerErrors('username_long');
		}


		//Check to see if there isnt already a user with that name in the database.
		$this->user_already_exists($new_username);

		//Change username
		$user = $_SESSION['username'];

		$sql_change_username = "UPDATE Info Set Username = '$new_username' WHERE Username = '$user';";
		$this->query($sql_change_username);

		// ** Change session variable so it displays in website **
		$_SESSION['username'] = $new_username;

		header('location: Settings.php?status=username_changed');
		die();



	}




	function user_already_exists($new_username){

		$user_exits_query = "SELECT * FROM Info WHERE Username = '$new_username' ";	
	 	$results = $this->query($user_exits_query);

	 	if(mysqli_num_rows($results) > 1){
	 		$this->headerErrors('username_taken');
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