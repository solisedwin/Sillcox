
<!DOCTYPE html>
<html>
<head>
    <title>Notes</title>
</head>
<link rel="stylesheet" type="text/css" href="css/notes.css">


<body>

    <header>


        <div class = 'header_btn' onclick="redirect_btn('out')" >
        Log Out
        </div>

        <div class="header_btn" onclick="redirect_btn('home')">
        Home Page   
        </div>

    </header>

    

</body>




    <script type="text/javascript">     

        function redirect_btn(whereTo) {
        
            if(whereTo == 'home'){
            window.location.href = 'Hub.php';
            }else{
                window.location.href = 'index.php?stat=logout';
            }

        }

    </script>


</html>




<?php

session_start();

class Notes {

	
	function __construct() {
            
    
    }


    function setUp(){

        //Gets query string (?view_topic=Graphs)
        $topic =  $_GET['view_topic'];

        $topic_dir =  getcwd() . "/Notes/" . $_SESSION['subject'] . "/" . $topic;

        if(!is_dir($topic_dir)){
            header('location: Topics.php?error=no_dir');
        }else{
            //chdir($topic_dir);
            return $topic_dir;
        }
    
    }




	function displayNotes($dir, $subject, $topic){


		//Attempt to read & display all files from folder
		try {		
  
            $files = scandir($dir);
            $subject = $_SESSION['subject'];

            for($i = 2; $i < sizeof($files); $i++){
    
                $extension = pathinfo($files[$i], PATHINFO_EXTENSION);   
            
               
               $src = "Notes/" . $subject . "/" . $topic . '/' . $files[$i];  

    	        echo '<center>';
                echo '<br>';

    			//array("pdf","img","jpg","docx","doc","png");   
                switch ($extension) {
                    case ($extension == 'png' || $extension == 'img' || $extension == 'jpg'):
						echo "<img src= " . $src . " style= 'width:90%; height:850px;' > ";
    					continue;

                    case 'doc':
                        echo "<iframe  style = 'height = 0%; width = 0%;' src = " . $src . ">";
                        echo '</iframe>';
                        continue;

    				case 'pdf':
    					echo "<iframe style = 'height:850px; width:95%; position: relative;'  src = " . $src  . " >";
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

    }//end of class



$notes =  new Notes();
$dir = $notes->setUp();
$notes->displayNotes($dir, $_SESSION['subject'], $_GET['view_topic']);

?>

