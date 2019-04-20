<?php  

session_start();


	class SignIn {


		private $conn;

		function __construct(){
		
			$_SESSION['username'] = $_POST['SignIn_Username'];
			$_SESSION['password'] = $_POST['SignIn_Password'];
			
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
	

		function isAdmin($user){

			$adminQuery = "SELECT Admin FROM Info Where Username = '$user'";
			echo '  ' . $adminQuery;

			$result = $this->query($adminQuery);

			$rows = $result->fetch_assoc();
			$_SESSION['admin'] = $rows['Admin'];

		}




	function users_email(){

		$username = $_SESSION['username'];

		$users_email_query = "SELECT Email FROM Info WHERE Username = '$username' ";
		$result = $this->query($users_email_query);
		$rows = $result->fetch_assoc();
		$_SESSION['email'] = $rows['Email'];

	}



		function sqlValidate(){

			$user = $_SESSION['username'];
			$password = $_SESSION['password'];

			$sql_encrypt_query = "SELECT Password FROM Info WHERE Username = '$user' ";
			$result = $this->query($sql_encrypt_query);

			$sql_encrypt_password = '';

			$row = $result->fetch_assoc(); 
			$sql_encrypt_password =  $row['Password'];
				

			require('Encryption.php');
			$obj = new Encryption();
			
			$is_valid_password = $obj->verify($password, $sql_encrypt_password);
			echo 'Valid Password: ' . $is_valid_password;

			if($is_valid_password){
			
				$this->isAdmin($user);
				$_SESSION['authenticated'] = True;

				//Save email info for session
				$this->users_email();
				

				$this->closeConnection();
				header('location: Hub.php');
			
			}else{
				$this->closeConnection();
				header('location: index.php?error=login_err');
				die();
			}

		}
	}


	$signIn = new SignIn();
	$signIn->sqlValidate();
	$signIn->closeConnection();


?>
