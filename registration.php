<?php

/*
 * Product: Eventreg 
 * Version: 1.0
 * Author: Oualid Burstrom
 * Email: ob@obkonsult.com 
 * Data: 2021-10-07
 * 
 */



if(!isset($_SESSION)) 
    session_start();

include('include/DBHandler.php');

$DBhandler = new DBHandler();

// -------- Check if there is an event ---------

$activeEvent = $DBhandler->getActiveEvent(); 

if($activeEvent){
    
    $eventData = $DBhandler->getEventData($activeEvent);
    
	$eventName = $eventData['eventName'][0];
	$eventDate = $eventData['eventStartdate'][0]." - ".$eventData['eventEnddate'][0];
	$HTTPLink = $eventData['eventHttplink'][0];
	$HTTPText = "Mer information";
}

$confirmation = "";

if (isset($_POST['registration'])){
    
    $result = $DBhandler->register($_POST); 
    
    if($result) {
	    
    	$eventData = $DBhandler->getEventData($activeEvent);
		$notificationEmail = $eventData['notificationEmail'][0];
	    	
        $confirmation = "<div class='alert alert-success'>Tack för din anmälan.<br> Din anmälan kommer att granskas och godkännas.<br>Du kommer att få en bekräftelse via email.<br>
        				Kontrollera att det inte hamnat i din skräppost!</div>";
        
        $to = $notificationEmail;
        
        $subject = "Registrering till $eventName";
        $body = "Namn: ".$_POST['username']."\n";
        $body .= "Grad: ".$_POST['grade']."\n";
        $body .= "\n Administrera: http://kyokushin.se/eventreg/admin.php";
        $headers = 'From: info@kyokushin.se';
        mail($to,$subject, $body, $headers);
        
    }
    else{
        $confirmation = "<div class='alert alert-danger'>Ett fel har uppstått.<br>Vänligen försök igen.</div>";    
    }
                
}

?>


<?php
    
if($activeEvent){
	$title =  "<h3 class=\"form-signin-heading text-center\">".$eventName."</h3>".$eventDate."<br><a href='".$HTTPLink."' target=_blank'>".$HTTPText."</a></h3></div>\n";
	processRegistration($title,$confirmation);
	
}else{
	processRegistration("","");
}
	
	
function processRegistration($title="", $confirmation="", $infoText="")
{

    $DBhandler = new DBHandler();
    
	$activeEvent = $DBhandler->getActiveEvent();     
	$eventData = $DBhandler->getEventData($activeEvent);
	$eventID = $eventData['id'][0];
    

    echo "<!DOCTYPE html>\n"; 
    echo "<!-- saved from url=(0040)http://getbootstrap.com/examples/signin/ -->\n"; 
    echo "<html lang=\"en\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n"; 
    echo "    <meta charset=\"utf-8\">\n"; 
    echo "    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n"; 
    echo "    <meta name=\"description\" content=\"\">\n"; 
    echo "    <meta name=\"author\" content=\"\">\n"; 
    echo "    <link rel=\"shortcut icon\" href=\"../images/favicon.ico\">\n"; 
    echo "    <title>EventReg Registration</title>\n"; 
    echo "    <!-- Bootstrap core CSS -->\n"; 
    echo "    <link rel='stylesheet' href='https://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css'>";

    echo "    <!-- Custom styles for this template -->\n"; 
    echo "    <link href=\"signin.css\" rel=\"stylesheet\">\n"; 
    echo "    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->\n"; 
    echo "    <!--[if lt IE 9]>\n"; 
    echo "      <script src=\"assets/js/html5shiv.js\"></script>\n"; 
    echo "      <script src=\"assets/js/respond.min.js\"></script>\n"; 
    echo "    <![endif]-->\n"; 
    echo "  <style type=\"text/css\"></style></head>\n"; 
    echo "  <body>\n"; 
    echo "    <div class=\"container\">\n"; 
    echo "      <form class=\"form-signin\" method='post' id='block-validate' action='registration.php'>\n"; 
    echo  "<input type='hidden' name='eventID' value='$eventID'>\n"; 				
    
    echo "<div align='center' style='margin-bottom: 40px;'>\n";     

	if($confirmation)
		echo $confirmation;
    
    if($title){
    
	    echo $title; 
	       
	
	    echo "        <h3 class=\"form-signin-heading\">Registrera dig</h3>\n";         
	    // --------------------------------------------------------------------------------
	
	    echo "      <div class='control-group' style='margin-bottom: 10px;'> \n"; 
	    echo "          <div class='controls'> \n"; 
	    echo  "          <label for='kategori'>Namn och efternamn</label> \n";  
	    echo "              <input type=\"text\" class=\"form-control\" id='username' name='username'>\n"; 
	    echo "          </div> \n"; 
	    echo "      </div> \n"; 
	
	    echo "      <div class='control-group' style='margin-bottom: 10px;'> \n"; 
	    echo "          <div class='controls'> \n"; 
	    echo  "          <label for='kategori'>E-postadressen</label> \n";      
	    echo "              <input type=\"email\" class=\"form-control\" id='loginEmail' name='loginEmail'>\n"; 
	    echo "          </div> \n"; 
	    echo "      </div> \n"; 
	
	    echo "      <div class='control-group' style='margin-bottom: 10px;'> \n"; 
	    echo "          <div class='controls'> \n"; 
	    echo  "          <label for='kategori'>Mobiltelefon</label> \n";        
	    echo "              <input type=\"text\" class=\"form-control\" id='cell' name='cell'>\n"; 
	    echo "          </div> \n"; 
	    echo "      </div> \n"; 
	
	    echo "      <div class='control-group' style='margin-bottom: 10px;'> \n"; 
	    echo "          <div class='controls'> \n"; 
	    echo  "          <label for='kategori'>Födelsedatum (år-månad-dag)</label> \n";         
	    echo "              <input type=\"text\" class=\"form-control\" id='birthdate' name='birthdate'>\n"; 
	    echo "          </div> \n"; 
	    echo "      </div> \n"; 
	
	    echo $DBhandler->selectClub();
	
	    echo $DBhandler->selectGrade();
	    
	    echo "<div class='row'> \n"; 
	    echo "  <div class='col-lg-6'> \n"; 
	    echo "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
	    echo "          <label class=\"control-label\">Instruktör</label> \n"; 
	    echo "              <div class=\"radio\">\n"; 
	    echo "                  <label>\n"; 
	    echo "                    <input type=\"radio\" name=\"instructor\" value='0' checked> Nej\n"; 
	    echo "                  </label>\n"; 
	    echo "              </div>\n"; 
	    echo "              <div class=\"radio\">\n"; 
	    echo "                  <label>\n"; 
	    echo "                    <input type=\"radio\" name=\"instructor\" value='1'> Ja\n"; 
	    echo "                  </label>\n"; 
	    echo "              </div>\n"; 
	    echo "        </div> \n"; 
	    echo "  </div> \n"; 
	    echo "</div> \n"; 
	
	    echo "<div class='row'> \n"; 
	    echo "  <div class='col-lg-12'> \n"; 
	    echo "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
	    echo "          <label class=\"control-label\">Boende</label> \n"; 
	    echo "              <div class=\"radio\">\n"; 
	    echo "                  <label>\n"; 
	    echo "                    <input type=\"radio\" name=\"accomodation\" value='Plats i 4-b&auml;dds rum + helpension (1100 kr)' checked> Plats i 4-b&auml;dds rum + helpension (1100 kr)\n"; 
	    echo "                  </label>\n"; 
	    echo "              </div>\n"; 
	    echo "              <div class=\"radio\">\n"; 
	    echo "                  <label>\n"; 
	    echo "                    <input type=\"radio\" name=\"accomodation\" value='Plats i 2-b&auml;dds rum + helpension (1300 kr)'> Plats i 2-b&auml;dds rum + helpension (1300 kr)\n"; 
	    echo "                  </label>\n"; 
	    echo "              </div>\n"; 
	    echo "              <div class=\"radio\">\n"; 
	    echo "                  <label>\n"; 
	    echo "                    <input type=\"radio\" name=\"accomodation\" value='Eget boende'> Eget boende\n"; 
	    echo "                  </label>\n"; 
	    echo "              </div>\n"; 	    
	    echo "        </div> \n"; 
	    echo "  </div> \n"; 
	    echo "</div> \n"; 

		echo "<div class='row'> \n"; 
		echo "	<div class='col-lg-12'> \n"; 
		echo "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
		echo "          <label class=\"control-label\">&Ouml;vrig information</label> \n"; 
		echo "          <div class='controls'> \n"; 
		echo "			  <textarea class='form-control' rows='3' name='extraInfo' id='extraInfo'></textarea> \n"; 
		echo "          </div> \n"; 
		echo "        </div> \n"; 
		echo "	</div> \n"; 
		echo "</div> \n"; 

	    echo "        <br><br><button class=\"btn btn-lg btn-primary btn-block\" type=\"submit\" name='registration'>Skicka</button>\n"; 
	    
	}else{
		echo "<div class='alert alert-success'>Inga event för tillfället.</div>";    
		
	}


    // --------------------------------------------------------------------------------
    
    //echo "        <ul class='list-inline' style=\"position: relative; top: 10px;\"> \n"; 
    //echo "            <li><a href='login.php' id='login'>Logga in</a></li> \n"; 
    //echo "        </ul> \n"; 

    echo "      </form>\n"; 
    
    echo "    </div> <!-- /container -->\n"; 
    echo "  <script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js\"></script>\n"; 
    echo "  <script src=\"//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js\"></script>\n"; 
    echo "  <script src=\"//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js\"></script>\n"; 
    echo "  <script type=\"text/javascript\" src=\"assets/js/lib/jquery.tablesorter.min.js\"></script>\n";
     
    echo "    <script type='text/javascript' src='assets/js/lib/jquery.validationEngine.js'></script>\n";   
    echo "    <script type='text/javascript' src='assets/js/lib/jquery.validate.min.js'></script>\n"; 
    echo "    <script type='text/javascript' src='assets/js/lib/languages/messages_sv.js'></script>\n";     
    
    echo "    <script type='text/javascript' src='assets/js/main.js'></script>\n";  
    echo "  <script>        \n"; 
    echo "        $(function() {\n"; 
    echo "            formValidation();\n"; 
    echo "        });\n"; 
    echo "  </script>\n"; 
    echo "</body></html>\n";

}
?>