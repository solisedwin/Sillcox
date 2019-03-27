<?php
session_start();


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
* 
*/
class SignUp {

	private $conn;

	function __construct(){
		
		$_SESSION['su_email'] =  $_POST['SignUp_Email'];
		$_SESSION['su_username'] = $_POST['SignUp_Username'];
		$_SESSION['su_password'] =  $_POST['SignUp_Password'];
		$_SESSION['su_password_again'] = $_POST['SignUp_Password_again'];


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
				echo '| Connected successfully';
			}
	}
	

	function passwordCheck(){

		$password = $_SESSION['su_password'];
		$password_again = $_SESSION['su_password_again'];

		if(strtolower($password) != strtolower($password_again)){
			header('location: index.php?error=passwd');
			die();
		}else{
			echo ' | Passwords are the same!';
		}
	}




	function is_sillcox_scholar(){
		$email = $_SESSION['su_email'];	

		if(strpos(file_get_contents('Scholars.txt'), $email) !== false){
			echo '| Email is in file';
		}else{
			header('location: index.php?error=scholars');
			die();
		}

	}


	
	function usernameTaken(){

		$username = $_SESSION['su_username'];
			
		if(strlen($username) < 4){
			header('location: index.php?error=short_user');
			die();
		} if(strlen($username) > 20){
			header('location: index.php?error=long_user');
			die();
		}

		
		$username_query = "SELECT * FROM Info Where Username = '$username';";	
		$result = $this->query($username_query);
		echo '<br>Query: ' . $username_query;
		
		if($result->num_rows  > 0){
			header('location: index.php?error=username');
			die();

		}

	}		


	
	function validPassword(){

		$password = $_SESSION['su_password'];

		if(strlen($password) < 4){
			header('location: index.php?error=short_pd');
			die();

		}
		if(strlen($password) > 25){
			header('location: index.php?error=long_pd');
			die();
		}

		if(!preg_match('@[0-9]@', $password)){
			header('location: index.php?error=num_pd');
			die();
		}
		if(! preg_match('@[a-z]@', $password)){
			header('location: index.php?=error=lower_pd');
			die();
		} 
		if(!preg_match('@[A-Z]@', $password)){
			header('location: index.php?error=upper_pd');
			die();
		}

	}



	function emailTaken(){

		$email = $_SESSION['su_email'];
		$email_query = "SELECT * FROM Info Where Email = '$email';";	
		$result = $this->query($email_query);

		if($result->num_rows  > 0){
			header('location: index.php?error=email_taken');
			die();
		}



	}


	function canRegister(){
		
		$this->is_sillcox_scholar();	
		$this->emailTaken();
		$this->usernameTaken();
		$this->passwordCheck();
		$this->validPassword();	
	}



	function insert_new_user(){

		$username = $_SESSION['su_username'];
		$email = $_SESSION['su_email'];

		$password = $_SESSION['su_password'];

		require('Encryption.php');	
	
		$hashingObject = new Encryption();
		$hash = $hashingObject->encrypt($password);
		$admin = 0;

		$stmt = $this->conn->prepare("INSERT INTO Info (Email, Username, Password, Admin) VALUES (?, ?, ?,?)");
		$stmt->bind_param("sssi", $_SESSION['su_email'] , $_SESSION['su_username'] , $hash ,$admin);
		$stmt->execute();
		$stmt->close();
 	
	}


}//end of class 


$signup_obj = new SignUp();
$signup_obj->connect('localhost','root','xxxxxxxx','xxxxxx');
$signup_obj->canRegister();
$signup_obj->insert_new_user();
	
$_SESSION['authenticated'] = True;
$_SESSION['username'] = $_SESSION['su_username'];
$_SESSION['password'] = $_SESSION['su_password'];
			

$this->closeConnection();

header('location: Hub.php');


?>