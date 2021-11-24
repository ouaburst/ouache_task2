<?php

/*
 * Product: Supportform 
 * Version: 2.0
 * Author: Oualid Burstrom
 * Email: ob@obkonsult.com 
 * Data: 2014-01-31
 * 
 */



if(!isset($_SESSION)) 
	session_start();

include('include/DBHandler.php');

$DBhandler = new DBHandler();

if (isset($_POST['login'])){
	

	$loginUser = trim($_POST['loginEmail']);
	$loginPassword = trim($_POST['loginPassword']);
	
	$MD5_password = md5($loginPassword);
			
	$results = $DBhandler->checkAccount($loginUser, $MD5_password);
		
	if(!$results)
	{
		$error_msg = "L&ouml;senordet &auml;r felaktigt eller du saknar beh&ouml;righet. V&auml;nligen prova igen.";
		process_login($error_msg);			
	}
	else
	{		

		$_SESSION['loginPassword'] = $loginPassword;

		$location = "Location: ".$_SESSION['currentfile'];
		header($location);				

	}

}
else
	process_login();

?>


<?php
function process_login($msg="")
{

	$DBhandler = new DBHandler();
	
	$errorMsg = "";

	if($msg)
		$errorMsg =  "<div class='alert alert-danger'>".$msg."</div>"; 
		

	echo "<!DOCTYPE html>\n"; 
	echo "<!-- saved from url=(0040)https://getbootstrap.com/examples/signin/ -->\n"; 
	echo "<html lang=\"en\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n"; 
	echo "    <meta charset=\"utf-8\">\n"; 
	echo "    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n"; 
	echo "    <meta name=\"description\" content=\"\">\n"; 
	echo "    <meta name=\"author\" content=\"\">\n"; 
	echo "    <link rel=\"shortcut icon\" href=\"../images/favicon.ico\">\n"; 
	echo "    <title>SKK admin event</title>\n"; 
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
	echo "      <form class=\"form-signin\" method='post' id='block-validate' action='login.php'>\n"; 
	echo $errorMsg;	
	
	
	echo "<div align='center' style='margin-bottom: 40px;'>\n"; 	
	echo "        <h3 class=\"form-signin-heading text-center\">SKK admin event</h3><a href='index.php'> <p style='text-align:center'><img src='assets/images/photo.png' height=100 width=100 class='img-circle'></p></a></div>\n"; 	
	
	echo "        <h3 class=\"form-signin-heading\">Logga in</h3>\n"; 

	echo "		<div class='control-group' style='margin-bottom: 10px;'> \n"; 
	echo "			<div class='controls'> \n"; 
	echo "				<input type=\"email\" class=\"form-control\" placeholder=\"E-postadressen\" autofocus=\"\" id='loginEmail' name='loginEmail'>\n"; 
	echo "			</div> \n"; 
	echo "		</div> \n"; 

	echo "		<div class='control-group' style='margin-bottom: 10px;'>\n"; 
	echo "			<div class='controls'> 	\n"; 
	echo "	        <input type='password' class='form-control' placeholder='L&ouml;senord' id='loginPassword' name='loginPassword'> \n"; 
	echo "			</div> 	\n"; 
	echo "		</div> 	\n"; 

/*
	echo "        <label class=\"checkbox\">\n"; 
	echo "          <input name='rememberme' type=\"checkbox\" value=1> Kom ih&aring;g mig\n"; 
	echo "        </label>\n"; 
*/

	
	echo "        <button class=\"btn btn-lg btn-primary btn-block\" type=\"submit\" name='login'>Logga in</button>\n"; 
	

//	echo "        <ul class='list-inline' style=\"position: relative; top: 10px;\"> \n"; 
//	echo "            <li><a href='signup.php' >Registrera</a></li> \n"; 
//	echo "            <li><a href='forgot_password.php' id='forgotPasssword'>Glömt lösenord</a></li> \n"; 
//	echo "            <li><a href='mailto: ob@obkonsult.com' >Kontakta admin</a></li> \n"; 	
//	echo "        </ul> \n"; 

	echo "      </form>\n"; 
	echo "    </div> <!-- /container -->\n"; 
	echo "	<script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js\"></script>\n"; 
	echo "	<script src=\"//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js\"></script>\n"; 
	echo "	<script src=\"//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js\"></script>\n"; 
	echo "	<script type=\"text/javascript\" src=\"assets/js/lib/jquery.tablesorter.min.js\"></script>\n";
	 
	echo "    <script type='text/javascript' src='assets/js/lib/jquery.validationEngine.js'></script>\n"; 	
	echo "    <script type='text/javascript' src='assets/js/lib/jquery.validate.min.js'></script>\n"; 
	echo "    <script type='text/javascript' src='assets/js/lib/languages/messages_sv.js'></script>\n"; 	
	
	echo "    <script type='text/javascript' src='assets/js/main.js'></script>\n"; 	
	echo "	<script>		\n"; 
	echo "        $(function() {\n"; 
	echo "            formValidation();\n"; 
	echo "        });\n"; 
	echo "	</script>\n"; 
	echo "</body></html>\n";

}
?>