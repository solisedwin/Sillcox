<?php
	
	/**
	* 
	*/
	class Settings_error {
		
		function __construct($url){
			$this->message($url);
		}
	
		
		function message($url){


			if(strpos($url, 'pass_unequal')){
				$this->output_message('New password and confirm password arent the same!');
			}

			else if(strpos($url, 'old_password_incorrect')){

				$this->output_message('Old Password is incorrect! Doesnt match our records.');

			}else if(strpos($url, 'short_pass')){
				$this->output_message('New password is too short! Must be more than 4 characters.');
			}else if(strpos($url, 'long_pass')){
				$this->output_message('New password is too long! Must be less than 24 characters');
			}else if(strpos($url, 'no_numbers')){
				$this->output_message('New password doesnt contain any numbers! Must contain a number');
			}else if(strpos($url, 'no_lowercase')){
				$this->output_message('New password doesnt contain any lower case values! Must contain atleast one lower case.');

			}else if(strpos($url, 'no_upper')){
				$this->output_message('New password doesnt contain any UPPER case values! Must contain atleast one upper case.');
			}else if(strpos($url, 'delete_account')){
				$this->output_message('You didnt write it correctly ! Please write "delete my account" as the input');
			}else if(strpos($url, 'username_unequal')){
				$this->output_message('New username and confirm username are not the same value! Make sure you wrote both correctly and the same');
			}else if(strpos($url, 'username_short')){
				$this->output_message('Username is too short. Username must be more than 4 characters long');
			}else if(strpos($url, 'username_long')){
				$this->output_message('Username is too long. Username must be less than 20 characters');
			}else if(strpos($url, 'username_taken')){
				$this->output_message('Username is already taken. Choose a different name');
			}else if(strpos($url, 'username_changed')){
				$this->updated_message('Username has been changed!');
			}else if(strpos($url, 'password_changed')){
				$this->updated_message('Password has been changed!');
			}else if(strpos($url, 'space_char'))
				$this->output_message('Cant have space characters as part of your input!');
			else{

			}

		}


		function output_message($msg){

			echo "

			<p class = 'error'>

			$msg

			</p>

			";

		}


		function updated_message($msg){
			echo "

			<p class = 'updated'>

			$msg

			</p>

			";





		}



	}



?>