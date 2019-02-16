<?php  

session_start();


	class SignIn {


		private $conn;

		function __construct(){
		
			$_SESSION['username'] = $_POST['SignIn_Username'];
			$_SESSION['password'] = $_POST['SignIn_Password'];
			

			$this->connect('localhost','root','xxxxxxxxxxx','Sillcox');

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
	



		function sqlValidate(){

			$user = $_SESSION['username'];
			$password = $_SESSION['password'];

			$sql_encrypt_query = "SELECT Password FROM Info WHERE Username = '$user' ";
			$result = $this->query($sql_encrypt_query);

			$sql_encrypt_password = '';

			while ($row = $result->fetch_assoc()) {
				$sql_encrypt_password =  $row['Password'];
				break;
			}

			require('Encryption.php');
			$obj = new Encryption();
			
			$is_valid_password = $obj->verify($password, $sql_encrypt_password);

			echo 'Valid Password: ' . $is_valid_password;

			if($is_valid_password){
				$_SESSION['authenticated'] = True;
				header('location: Hub.php');
			}else{
				header('location: index.php?error=login_err');
				die();
			}

		}
	}


	$signIn = new SignIn();
	$signIn->sqlValidate();


?>
