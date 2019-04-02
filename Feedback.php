<?php


session_start();

	class Feedback { 

		private $conn;

		function __construct(){
			
			$this->connect('localhost','root','xxxxxxx','xxxxxxx');
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
	
	
	function send_feedback(){


		if (!strlen(trim($_POST['feedback_msg'])) || empty(trim($_POST['feedback_msg']))){
			header('location: Hub.php?error=empty_feedback');
			die();
		}

		$_SESSION['feedback'] =  $_POST['feedback_msg'];
		$_SESSION['emailTo'] = 'sillcoxhelp@gmail.com';

		include_once('Email.php');

	}


	function getEmail(){

		$username = $_SESSION['username'];	
		$email_query = "SELECT Email FROM `Info` WHERE Username = '$username'";
		$result = $this->query($email_query);

		$row = $result->fetch_assoc();
		$email = $row['Email'];

		$_SESSION['user_email'] = $email;	

	}

}


$obj = new Feedback();
$obj->getEmail();
$obj->send_feedback();
$obj->closeConnection();



?>