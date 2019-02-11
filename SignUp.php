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
		
		$_SESSION['email'] = trim($_POST['SignUp_Email']);
		$_SESSION['username'] = trim($_POST['SignUp_Username']);
		$_SESSION['password'] = trim($_POST['SignUp_Password']);
		$_SESSION['password_again'] = trim($_POST['SignUp_Password_again']);

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

		$password = $_SESSION['password'];
		$password_again = $_SESSION['password_again'];

		if(strtolower($password) != strtolower($password_again)){
			header('location: index.php?error=passwd');
			die();
		}else{
			echo ' | Passwords are the same!';
		}
	}




	function is_sillcox_scholar(){
		$email = $_SESSION['email'];	

		if(strpos(file_get_contents('Scholars.txt'), $email) !== false){
			echo '| Email is in file';
		}else{
			header('location: index.php?error=scholars');
		}

	}


	
	function usernameTaken(){

	$username = $_SESSION['username'];
		
	if(strlen($username) < 4){
		header('location: index.php?error=short_user');
	} if(strlen($username) > 20){
		header('location: index.php?error=long_user');
	}

	
	$username_query = "SELECT * FROM Info Where Username = '$username';";	
	$result = $this->query($username_query);
	echo '<br>Query: ' . $username_query;
	
	if($result->num_rows  > 0){
		header('location: index.php?error=username');
	}

}		


	
	function validPassword(){

		$password = $_SESSION['password'];

		if(strlen($password) < 4){
			header('location: index.php?error=short_pd');
		}
		if(strlen($password) > 25){
			header('location: index.php?error=long_pd');
		}

		if(!preg_match('@[0-9]@', $password)){
			header('location: index.php?error=num_pd');
		}
		if(! preg_match('@[a-z]@', $password)){
			header('location: index.php?=error=lower_pd');
		} 
		if(!preg_match('@[A-Z]@', $password)){
			header('location: index.php?error=upper_pd');
		}

	}








	function canRegister(){
		
		$this->is_sillcox_scholar();	
		$this->usernameTaken();
		$this->passwordCheck();
		$this->validPassword();
	

	}




}//end of class 


$signup_obj = new SignUp();
$signup_obj->connect('localhost','root','fakepassword123','Sillcox');
$signup_obj->canRegister();




?>