<?php


class Notes {

	
	function __construct() {

	}


	function get_subject_dir() {

		$view_subject = $_GET['view_subject'];
	
		$dir = 	getcwd() . '/Notes/' . $view_subject;

		if(is_dir($dir)){
			chdir($dir);
		}else{
			header('location: Hub.php?error=no_dir');
			die();
		}

		return $dir;
	}	


	function displayNotes($dir){


		//Attempt to read & display all files from folder

		try {		
	
    		$files = scandir($dir);


    		for($i = 2; $i < sizeof($files); $i++){
    
    			$extension = pathinfo($files[$i], PATHINFO_EXTENSION);
    			$src = 'Notes/' . $_GET['view_subject'] . '/' . $files[$i];


    			echo '<center>';

    			//array("txt","pdf","img","jpg","docx","doc","png");
    			switch ($extension) {
    				case ($extension == 'png' || $extension == 'img' || $extension == 'jpg'):

						echo "<img src= '$src' style='width:500px;height:520px;'> ";
    					continue;


                    case 'doc':
                        $doc_src = "Notes/Precalc/" . $files[$i];


                        echo "<iframe align = 'center' height = '75%' width = '75%'src = " . $doc_src. ">";
                        echo '</iframe>';
                                 
                        continue;


    				case 'pdf':
    					$pdf_src = "Notes/Precalc/" . $files[$i];

    					echo "<iframe align = 'center' height = '75%' width = '75%'src = " . $pdf_src. ">";
    					echo '</iframe>';
    				
    					continue;

    				default:
    					continue;
    			
                    echo "<br> <br>";
    				echo '</center>';
    			}


    		}//end for loop

				} catch (Exception $e) {
					echo '~~ Error! File couldnt be uploaded. Reason: ' . $e->getMessage();
				}		
			}


}




$notes =  new Notes();
$dir = $notes->get_subject_dir();
$notes->displayNotes($dir);



?>