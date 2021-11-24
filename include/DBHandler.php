<?php
/*
 * Product: Eventreg
 * Version: 1.0
 * Author: Oualid Burstrom
 * Email: ob@obkonsult.com 
 * Data: 2021-10-07
 * 
 */


include('Mailer.php');

/* 
 * Class handles database connection, providing methods for common functions
 */
class DBHandler
{
	private $dbUrl;
	private $dbName;
	private $dbUser;
	private $dbPass;
	
	function __construct($dbUrl  = 'localhost', $dbName  = 'kyokus97_db2', $dbUser  = 'kyokus97_user2', $dbPass = 'swe-kar-kyo-kai') //Database connection parameters are required
	{
		$this->dbUrl = $dbUrl;
		$this->dbName = $dbName;
		$this->dbUser = $dbUser;
		$this->dbPass = $dbPass;
		

		$this->mysqli = new mysqli($dbUrl, $dbUser, $dbPass, $dbName);
		
		/* check connection */
		if (mysqli_connect_errno()) {
		    printf("Connect failed: %s\n", mysqli_connect_error());
		    exit();
		}

	}

	function selectClub(){
		
		$htmlString = "";
		
		$clubList = array();		
		$clubList = $this->getClubList();

		$size = count($clubList['id']);
			
		$htmlString .= "<div class='row'> \n"; 
		$htmlString .= "	<div class='col-xs-10 col-md-12'> \n"; 
		$htmlString .= "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
		$htmlString .= "          <label for='kategori'>Välj klubb</label> \n"; 
		$htmlString .= "			<select name='clubID' class=\"form-control\">\n"; 
		
		for($i=0 ; $i<$size ; $i++){
			
			$htmlString .= "<option value=".$clubList['id'][$i].">".$clubList['clubName'][$i]."</option>\n"; 		
		}
				
		$htmlString .= "			</select>\n";		
		$htmlString .= "        </div> \n"; 
		$htmlString .= "	</div> \n"; 
		$htmlString .= "</div>\n"; 
		
		return $htmlString;
	}

	function selectGrade(){
		
		$htmlString = "";
		
		$htmlString .= "<div class='row'> \n"; 
		$htmlString .= "	<div class='col-xs-10 col-md-12'> \n"; 
		$htmlString .= "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
		$htmlString .= "          <label for='kategori'>Välj grad</label> \n"; 
		$htmlString .= "			<select name='grade' class=\"form-control\">\n"; 			
		$htmlString .= "<option value='8 Kyu'> 8 Kyu</option>\n"; 
		$htmlString .= "<option value='7 Kyu'> 7 Kyu</option>\n"; 		
		$htmlString .= "<option value='6 Kyu'> 6 Kyu</option>\n"; 
		$htmlString .= "<option value='5 Kyu'> 5 Kyu</option>\n"; 
		$htmlString .= "<option value='4 Kyu'> 4 Kyu</option>\n"; 
		$htmlString .= "<option value='3 Kyu'> 3 Kyu</option>\n"; 
		$htmlString .= "<option value='2 Kyu'> 2 Kyu</option>\n"; 
		$htmlString .= "<option value='1 Kyu'> 1 Kyu</option>\n"; 
		$htmlString .= "<option value='1 Dan'> 1 Dan</option>\n"; 
		$htmlString .= "<option value='2 Dan'> 2 Dan</option>\n"; 
		$htmlString .= "<option value='3 Dan'> 3 Dan</option>\n"; 		
		$htmlString .= "<option value='4 Dan'> 4 Dan</option>\n"; 		
		$htmlString .= "<option value='5 Dan'> 5 Dan</option>\n"; 		
		$htmlString .= "			</select>\n";		
		$htmlString .= "        </div> \n"; 
		$htmlString .= "	</div> \n"; 
		$htmlString .= "</div>\n"; 
		
		return $htmlString;
	}

	function getClubList(){
	
		$query = "SELECT id,club_name FROM skk_medlemsdb_klubbar where id <> 38 ORDER BY club_name ASC ";				

		$rs=$this->mysqli->query($query) or die(mysqli_error($this->$mysqli));
		
		$rs->data_seek(0);
		
		$content = array();	
			
		while($row = $rs->fetch_assoc()){
			
			$content['id'][] = $row['id'];		
			$content['clubName'][] = $row['club_name'];					
		}

		$rs->close();		
				
		return $content;		
	}

	function register($data){

		$userName = $this->convertToHTML($data['username']);	
		$email = trim($data['loginEmail']);
		$grade = $data['grade'];	
		$birthDate = $data['birthdate'];	
		$cell = $data['cell'];	
		$club = $data['clubID'];	
		$instructor = $data['instructor'];		
		$eventID = $data['eventID'];		
		$accomodation = $data['accomodation'];							
		$extraInfo = $data['extraInfo'];									

		$query = "INSERT INTO eventreg_events_reg (username,email,grade,birth_date,cell,club,instructor,event_id,accomodation,extraInfo) VALUES (?,?,?,?,?,?,?,?,?,?)";	
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param('sssssiiiss',$userName,$email,$grade,$birthDate,$cell,$club,$instructor,$eventID,$accomodation,$extraInfo);	
		$result = $stmt->execute();		
		
		$stmt->close();		

		return $result;
	}	

	function getEvents(){
	
		$OPEN_FOR_REGISTRATION = "Öppen för anmälning";
		$CLOSE_FOR_REGISTRATION = "Stängd för anmälning";		
		$OPEN_LINK = "Öppna länk";
				
		if(isset($_SESSION['language'])){
			if($_SESSION['language'] == "eng"){
				$OPEN_FOR_REGISTRATION = "Open for registration";
				$CLOSE_FOR_REGISTRATION = "Closed for registration";		
				$OPEN_LINK = "Open link";
			}
		}
	
		$query = "SELECT id,event_startdate,event_enddate,event_name,event_location,notification_email,event_organizer,event_httplink,active FROM eventreg_events ORDER BY id desc ";				

		$rs=$this->mysqli->query($query) or die(mysqli_error($this->$mysqli));		
		$rs->data_seek(0);		
		$content = array();	
		
		$output = array( "aaData" => array());
			
		while($row = $rs->fetch_assoc()){

			$content = array();	
			
			$content[] = $row['id'];
            $content[] = $row['event_name'];
			$content[] = $row['event_startdate'];
			$content[] = $row['event_enddate'];					
			$content[] = utf8_encode($row['event_location']);
			$content[] = utf8_encode($row['event_organizer']);
			$content[] = $row['notification_email'];

			$httpLink = $row['event_httplink'];			
			$content[] = "<a href='".$httpLink."' target=_blank> $OPEN_LINK </a>";
			
			if($row['active'])
				$content[] = "<span style='font-weight: bold; color: rgb(51, 204, 0);'>".$OPEN_FOR_REGISTRATION."</span>";
			else
				$content[] = "<span style='font-weight: bold; color: red;'>".$CLOSE_FOR_REGISTRATION."</span>";				
			
			$content[] = "";						
									
			$output['aaData'][] = $content;						
		}

		$rs->close();		
				
		return $output;		
	}

	function checkAccount($username, $password){
	
		$query = "SELECT * FROM eventreg_admin WHERE username = ? AND password = ?";				
	
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param('ss', $username,$password);
		
		$stmt->execute();
		
		$stmt->store_result();
	
		$result = $stmt->num_rows;
		
		$stmt->close();				
		
		return $result;
	}
	
	function createEventModal(){
		
		$EVENT = "Event";
		$START_DATE = "Startdatum";
		$END_DATE = "Slutdatum";		
		$LOCATION = "Plats";		
		$ORGANIZER = "Arrangör";				
		$EMAIL = "E-post";						
		$HTTP_LINK = "HTTP-länk";								
				
		if(isset($_SESSION['language'])){
			if($_SESSION['language'] == "eng"){
				$SEMINAR = "Seminar";
				$START_DATE = "Start date";
				$END_DATE = "End date";		
				$LOCATION = "Location";		
				$ORGANISER = "Organiser";				
				$EMAIL = "Email";						
				$HTTP_LINK = "HTTP link (Link to SKKs calendar)";								
			}
		}

		$htmlString = "";
		
		$todayDate = date('Y-m-d');		

		$htmlString .= "<div class='row'> \n"; 
		$htmlString .= "	<div class='col-lg-8'> \n"; 
		$htmlString .= "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
		$htmlString .= "          <label class=\"control-label\">$EVENT</label> \n"; 
		$htmlString .= "          <div class='controls'> \n"; 
		$htmlString .= "            <input class='form-control' type='text' name='event' id='seminar' >\n"; 
		$htmlString .= "          </div> \n"; 
		$htmlString .= "        </div> \n"; 
		$htmlString .= "	</div> \n"; 
		$htmlString .= "</div> \n"; 

		// -----------------------------
		
		$htmlString .= "<div class='row'> \n"; 		
		$htmlString .= "	<div class=\"col-xs-6\">\n"; 
		$htmlString .= "		<label for=\"date-picker\" class=\"control-label\">$START_DATE:</label>\n"; 
		$htmlString .= "<div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"yyyy-mm-dd\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\">\n"; 
		$htmlString .= "    <input class=\"form-control\" size=\"16\" type=\"text\" value=\"$todayDate\" name='startDate' id='startDate' readonly>\n"; 
		$htmlString .= "	<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>\n"; 
		$htmlString .= "</div>\n"; 
		$htmlString .= "	</div>\n"; 
		$htmlString .= "</div>\n"; 		

		$htmlString .= "<script type=\"text/javascript\">\n"; 
		$htmlString .= "	$('.form_date').datetimepicker({\n"; 
		//$htmlString .= "        language:  'sv',\n"; 
		$htmlString .= "        weekStart: 1,\n"; 
		$htmlString .= "        todayBtn:  1,\n"; 
		$htmlString .= "		autoclose: 1,\n"; 
		$htmlString .= "		todayHighlight: 1,\n"; 
		$htmlString .= "		startView: 2,\n"; 
		$htmlString .= "		minView: 2,\n"; 
		$htmlString .= "		forceParse: 0\n"; 
		$htmlString .= "    });\n"; 
		$htmlString .= "</script>\n"; 

		// -----------------------------
		
		$htmlString .= "<div class='row'> \n"; 		
		$htmlString .= "	<div class=\"col-xs-6\">\n"; 
		$htmlString .= "		<label for=\"date-picker\" class=\"control-label\">$END_DATE:</label>\n"; 
		$htmlString .= "<div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"yyyy-mm-dd\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\">\n"; 
		$htmlString .= "    <input class=\"form-control\" size=\"16\" type=\"text\" value=\"$todayDate\" name='endDate' id='endDate' readonly>\n"; 
		$htmlString .= "	<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>\n"; 
		$htmlString .= "</div>\n"; 
		$htmlString .= "	</div>\n"; 
		$htmlString .= "</div>\n"; 		

		$htmlString .= "<script type=\"text/javascript\">\n"; 
		$htmlString .= "	$('.form_date').datetimepicker({\n"; 
		//$htmlString .= "        language:  'sv',\n"; 
		$htmlString .= "        weekStart: 1,\n"; 
		$htmlString .= "        todayBtn:  1,\n"; 
		$htmlString .= "		autoclose: 1,\n"; 
		$htmlString .= "		todayHighlight: 1,\n"; 
		$htmlString .= "		startView: 2,\n"; 
		$htmlString .= "		minView: 2,\n"; 
		$htmlString .= "		forceParse: 0\n"; 
		$htmlString .= "    });\n"; 
		$htmlString .= "</script>\n"; 

		// -----------------------------
		
		$htmlString .= "<div class='row'> \n"; 
		$htmlString .= "	<div class='col-lg-8'> \n"; 
		$htmlString .= "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
		$htmlString .= "          <label class=\"control-label\">$LOCATION</label> \n"; 
		$htmlString .= "          <div class='controls'> \n"; 
		$htmlString .= "            <input class='form-control' type='text' name='location' id='location'>\n"; 
		$htmlString .= "          </div> \n"; 
		$htmlString .= "        </div> \n"; 
		$htmlString .= "	</div> \n"; 
		$htmlString .= "</div> \n"; 

		$htmlString .= "<div class='row'> \n"; 
		$htmlString .= "	<div class='col-lg-8'> \n"; 
		$htmlString .= "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
		$htmlString .= "          <label class=\"control-label\">$ORGANIZER</label> \n"; 
		$htmlString .= "          <div class='controls'> \n"; 
		$htmlString .= "            <input class='form-control' type='text' name='organizer' id='organizer'>\n"; 
		$htmlString .= "          </div> \n"; 
		$htmlString .= "        </div> \n"; 
		$htmlString .= "	</div> \n"; 
		$htmlString .= "</div> \n"; 

		$htmlString .= "<div class='row'> \n"; 
		$htmlString .= "	<div class='col-lg-8'> \n"; 
		$htmlString .= "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
		$htmlString .= "          <label class=\"control-label\">$EMAIL</label> \n"; 
		$htmlString .= "          <div class='controls'> \n"; 
		$htmlString .= "            <input class='form-control' type='text' name='email' id='email'>\n"; 
		$htmlString .= "          </div> \n"; 
		$htmlString .= "        </div> \n"; 
		$htmlString .= "	</div> \n"; 
		$htmlString .= "</div> \n"; 
		
		$htmlString .= "<div class='row'> \n"; 
		$htmlString .= "	<div class='col-lg-8'> \n"; 
		$htmlString .= "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
		$htmlString .= "          <label class=\"control-label\">$HTTP_LINK</label> \n"; 
		$htmlString .= "          <div class='controls'> \n"; 
		$htmlString .= "            <input class='form-control' type='text' name='httpLink' id='httpLink'>\n"; 
		$htmlString .= "          </div> \n"; 
		$htmlString .= "        </div> \n"; 
		$htmlString .= "	</div> \n"; 
		$htmlString .= "</div> \n"; 
		
/*
		$htmlString .= "<div class='row'> \n"; 
		$htmlString .= "	<div class='col-lg-8'> \n"; 
		$htmlString .= "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
		$htmlString .= "          <label class=\"control-label\">Document</label> \n"; 
		$htmlString .= "          <div class='controls'> \n"; 
		$htmlString .= "			  <input class='form-control' type='file' name='files' id='file' >\n"; 
		$htmlString .= "          </div> \n"; 
		$htmlString .= "        </div> \n"; 
		$htmlString .= "	</div> \n"; 
		$htmlString .= "</div> \n"; 		
*/

		return $htmlString;								
	}	
	
	function addEvent($data){

		$event = $this->convertToHTML($data['event']);
		$startDate = $data['startDate'];
		$endDate = $data['endDate'];			
		$location = $this->convertToHTML($data['location']);			
		$organizer = $this->convertToHTML($data['organizer']);					
		$email =  $data['email'];							
		$httpLink =  $data['httpLink'];							
				
		$query = "INSERT INTO eventreg_events (event_name,event_startdate,event_enddate,event_location,event_organizer,notification_email,event_httplink) VALUES (?,?,?,?,?,?,?)";
	
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param('sssssss',$event,$startDate,$endDate,$location,$organizer,$email,$httpLink);
	
		$result = $stmt->execute();
		
		$stmt->close();	
		
		return $result;
	}
	
	function deleteEvent($eventID){
		
		$query = "DELETE FROM eventreg_events WHERE id = ?";
	
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param('i', $eventID);
	
		$result = $stmt->execute();
		
		$stmt->close();	
		
		return $result;				
	}
	
	function editEventModal($eventID){
		
		$EVENT = "Event";
		$START_DATE = "Startdatum";
		$END_DATE = "Slutdatum";		
		$LOCATION = "Plats";		
		$ORGANIZER = "Arrangör";				
		$EMAIL = "E-post notifiering";						
		$HTTP_LINK = "HTTP-länk";		
		$OPEN_FOR_REGISTRATION = "Öppen för anmälning";		
		$YES = "Ja";
		$NO = "Nej";				
				
		if(isset($_SESSION['language'])){
			if($_SESSION['language'] == "eng"){
				$SEMINAR = "Seminar";
				$START_DATE = "Start date";
				$END_DATE = "End date";		
				$LOCATION = "Location";		
				$ORGANISER = "Organiser";				
				$EMAIL = "Email";						
				$HTTP_LINK = "HTTP link";								
				$OPEN_FOR_REGISTRATION = "Open for registration";										
				$YES = "Yes";
				$NO = "No";								
			}
		}		
		
		$htmlString = "";		
		
		$query = "SELECT event_name,event_startdate,event_enddate,event_location,event_organizer,notification_email,event_httplink,active FROM eventreg_events WHERE id = ?";
	
	
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param('i', $eventID);		
		$stmt->execute();	
		$stmt->bind_result($event,$startDate,$endDate,$location,$organizer,$email,$httpLink,$active);	
		$stmt->fetch();		
		$stmt->close();						
		
		$htmlString .= "<input type='hidden' name='id' value='$eventID'>\n"; 				

		$htmlString .= "<div class='row'> \n"; 
		$htmlString .= "	<div class='col-lg-8'> \n"; 
		$htmlString .= "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
		$htmlString .= "          <label class=\"control-label\">$EVENT</label> \n"; 
		$htmlString .= "          <div class='controls'> \n"; 
		$htmlString .= "            <input class='form-control' type='text' name='event' id='event' value='".$event."'>\n"; 
		$htmlString .= "          </div> \n"; 
		$htmlString .= "        </div> \n"; 
		$htmlString .= "	</div> \n"; 
		$htmlString .= "</div> \n"; 

		// -----------------------------
		
		$htmlString .= "<div class='row'> \n"; 		
		$htmlString .= "	<div class=\"col-xs-6\">\n"; 
		$htmlString .= "		<label for=\"date-picker\" class=\"control-label\">$START_DATE:</label>\n"; 
		$htmlString .= "<div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"yyyy-mm-dd\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\">\n"; 
		$htmlString .= "    <input class=\"form-control\" size=\"16\" type=\"text\" value=\"$startDate\" name='startDate' id='startDate' readonly>\n"; 
		$htmlString .= "	<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>\n"; 
		$htmlString .= "</div>\n"; 
		$htmlString .= "	</div>\n"; 
		$htmlString .= "</div>\n"; 		

		$htmlString .= "<script type=\"text/javascript\">\n"; 
		$htmlString .= "	$('.form_date').datetimepicker({\n"; 
		//$htmlString .= "        language:  'sv',\n"; 
		$htmlString .= "        weekStart: 1,\n"; 
		$htmlString .= "        todayBtn:  1,\n"; 
		$htmlString .= "		autoclose: 1,\n"; 
		$htmlString .= "		todayHighlight: 1,\n"; 
		$htmlString .= "		startView: 2,\n"; 
		$htmlString .= "		minView: 2,\n"; 
		$htmlString .= "		forceParse: 0\n"; 
		$htmlString .= "    });\n"; 
		$htmlString .= "</script>\n"; 

		// -----------------------------
		
		$htmlString .= "<div class='row'> \n"; 		
		$htmlString .= "	<div class=\"col-xs-6\">\n"; 
		$htmlString .= "		<label for=\"date-picker\" class=\"control-label\">$END_DATE:</label>\n"; 
		$htmlString .= "<div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"yyyy-mm-dd\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\">\n"; 
		$htmlString .= "    <input class=\"form-control\" size=\"16\" type=\"text\" value=\"$endDate\" name='endDate' id='endDate' readonly>\n"; 
		$htmlString .= "	<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>\n"; 
		$htmlString .= "</div>\n"; 
		$htmlString .= "	</div>\n"; 
		$htmlString .= "</div>\n"; 		

		$htmlString .= "<script type=\"text/javascript\">\n"; 
		$htmlString .= "	$('.form_date').datetimepicker({\n"; 
		//$htmlString .= "        language:  'sv',\n"; 
		$htmlString .= "        weekStart: 1,\n"; 
		$htmlString .= "        todayBtn:  1,\n"; 
		$htmlString .= "		autoclose: 1,\n"; 
		$htmlString .= "		todayHighlight: 1,\n"; 
		$htmlString .= "		startView: 2,\n"; 
		$htmlString .= "		minView: 2,\n"; 
		$htmlString .= "		forceParse: 0\n"; 
		$htmlString .= "    });\n"; 
		$htmlString .= "</script>\n"; 

		$htmlString .= "<div class='row'> \n"; 
		$htmlString .= "	<div class='col-lg-8'> \n"; 
		$htmlString .= "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
		$htmlString .= "          <label class=\"control-label\">$LOCATION</label> \n"; 
		$htmlString .= "          <div class='controls'> \n"; 
		$htmlString .= "            <input class='form-control' type='text' name='location' id='location' value='".$location."'>\n"; 
		$htmlString .= "          </div> \n"; 
		$htmlString .= "        </div> \n"; 
		$htmlString .= "	</div> \n"; 
		$htmlString .= "</div> \n"; 

		$htmlString .= "<div class='row'> \n"; 
		$htmlString .= "	<div class='col-lg-8'> \n"; 
		$htmlString .= "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
		$htmlString .= "          <label class=\"control-label\">$ORGANIZER</label> \n"; 
		$htmlString .= "          <div class='controls'> \n"; 
		$htmlString .= "            <input class='form-control' type='text' name='organizer' id='organizer' value='".$organizer."'>\n"; 
		$htmlString .= "          </div> \n"; 
		$htmlString .= "        </div> \n"; 
		$htmlString .= "	</div> \n"; 
		$htmlString .= "</div> \n"; 

		$htmlString .= "<div class='row'> \n"; 
		$htmlString .= "	<div class='col-lg-8'> \n"; 
		$htmlString .= "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
		$htmlString .= "          <label class=\"control-label\">$EMAIL</label> \n"; 
		$htmlString .= "          <div class='controls'> \n"; 
		$htmlString .= "            <input class='form-control' type='text' name='email' id='email' value='$email'>\n"; 
		$htmlString .= "          </div> \n"; 
		$htmlString .= "        </div> \n"; 
		$htmlString .= "	</div> \n"; 
		$htmlString .= "</div> \n"; 
		
		$htmlString .= "<div class='row'> \n"; 
		$htmlString .= "	<div class='col-lg-8'> \n"; 
		$htmlString .= "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
		$htmlString .= "          <label class=\"control-label\">$HTTP_LINK</label> \n"; 
		$htmlString .= "          <div class='controls'> \n"; 
		$htmlString .= "            <input class='form-control' type='text' name='httpLink' id='httpLink' value='$httpLink'>\n"; 
		$htmlString .= "          </div> \n"; 
		$htmlString .= "        </div> \n"; 
		$htmlString .= "	</div> \n"; 
		$htmlString .= "</div> \n"; 

		$checked1 = ($active == 1) ? "checked" : "";
		$checked2 = ($active == 0) ? "checked" : "";							

		$htmlString .= "<div class='row'> \n"; 
		$htmlString .= "	<div class='col-lg-6'> \n"; 
		$htmlString .= "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
		$htmlString .= "          <label class=\"control-label\">$OPEN_FOR_REGISTRATION</label> \n"; 
		$htmlString .="				<div class=\"radio\">\n"; 
		$htmlString .="				    <label>\n"; 
		$htmlString .="				      <input type=\"radio\" name=\"active\" value='1' $checked1>$YES\n"; 
		$htmlString .="				    </label>\n"; 
		$htmlString .="				</div>\n"; 
		$htmlString .="				<div class=\"radio\">\n"; 
		$htmlString .="				    <label>\n"; 
		$htmlString .="				      <input type=\"radio\" name=\"active\" value='0' $checked2>$NO\n"; 
		$htmlString .="				    </label>\n"; 
		$htmlString .="				</div>\n"; 
		$htmlString .= "        </div> \n"; 
		$htmlString .= "	</div> \n"; 
		$htmlString .= "</div> \n"; 

		
/*
		$htmlString .= "<div class='row'> \n"; 
		$htmlString .= "	<div class='col-lg-8'> \n"; 
		$htmlString .= "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
		$htmlString .= "          <label class=\"control-label\">Document</label> \n"; 
		$htmlString .= "          <div class='controls'> \n"; 
		$htmlString .= "			  <input class='form-control' type='file' name='files' id='file' >\n"; 
		$htmlString .= "          </div> \n"; 
		$htmlString .= "        </div> \n"; 
		$htmlString .= "	</div> \n"; 
		$htmlString .= "</div> \n"; 		
*/

		return $htmlString;								
	}		
	
	function updateEvent($data){	
		
		$id = $data['id'];
		$event = $this->convertToHTML($data['event']);
		$startDate = $data['startDate'];
		$endDate = $data['endDate'];			
		$location = $this->convertToHTML($data['location']);			
		$organizer = $this->convertToHTML($data['organizer']);					
		$email =  $data['email'];							
		$httpLink =  $data['httpLink'];	
		$active =  $data['active'];	
								
										                
		$query = "UPDATE eventreg_events SET event_name = ?,event_startdate = ?,event_enddate = ?,event_location = ?,event_organizer = ?,notification_email = ?,event_httplink = ?, active = ? WHERE id = ?";	
		
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param('sssssssii',$event,$startDate,$endDate,$location,$organizer,$email,$httpLink,$active,$id);
						
		$result =  $stmt->execute();
		
		$stmt->close();				
	
		return $result;
	}
		
	
    function convertToHTML($string) 
    {
        $string = str_replace( 'ä', '&auml;', $string );
        $string = str_replace( 'Ä', '&Auml;',  $string );
        $string = str_replace( 'ö', '&ouml;',  $string );
        $string = str_replace( 'Ö', '&Ouml;',  $string );
        $string = str_replace( 'å', '&aring;', $string );
        $string = str_replace( 'Å', '&Aring;', $string );
        
        return $string;
    }	

	function getEventData($eventID){
		
		$query = "SELECT id,event_startdate,event_enddate,event_name,event_location,notification_email,event_organizer,event_httplink,active FROM eventreg_events where id = ? ";				

		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param('i', $eventID);
		$stmt->execute();		
		$stmt->bind_result($id,$eventStartdate,$eventEnddate,$eventName,$eventLocation,$notificationEmail,$eventOrganizer,$eventHttplink,$active);
		
		$data = array();
		
		while ($stmt->fetch()) {
		
			$data['id'][] = $id;			
			$data['eventStartdate'][] = $eventStartdate;
			$data['eventEnddate'][] = $eventEnddate;			
			$data['eventName'][] = $eventName;			
			$data['eventLocation'][] = $eventLocation;						
			$data['notificationEmail'][] = $notificationEmail;									
			$data['eventOrganizer'][] = $eventOrganizer;												
			$data['eventHttplink'][] = $eventHttplink;																		
			$data['active'][] = $active;																					
		}
		
		$stmt->close();		
				
		return $data;
	}


    function getActiveEvent(){
		$query = "SELECT id FROM eventreg_events where active = 1";				

		$rs=$this->mysqli->query($query) or die(mysqli_error($this->$mysqli));
		
		$rs->data_seek(0);					
		$row = $rs->fetch_assoc();	   		
	    $id = $row['id'];		
		$rs->close();		
				
		return $id;		    
    }
    
	function getRegistrations(){

		$query = "SELECT
					`eventreg_events_reg`.`id`,
					`eventreg_events_reg`.`username`,
					`eventreg_events_reg`.`birth_date`,
					`eventreg_events_reg`.`email`,
					`eventreg_events_reg`.`grade`,
					`eventreg_events_reg`.`cell`,					
					`skk_medlemsdb_klubbar`.`club_name`,
					`eventreg_events_reg`.`instructor`,
					`eventreg_events`.`event_name`,
                    `eventreg_events_reg`.`accepted`,
                    `eventreg_events_reg`.`accomodation`,
                    `eventreg_events_reg`.`extraInfo`
				FROM
					`eventreg_events_reg` `eventreg_events_reg` 
						INNER JOIN `skk_medlemsdb_klubbar` `skk_medlemsdb_klubbar` 
						ON `eventreg_events_reg`.`club` = `skk_medlemsdb_klubbar`.`id` 
							INNER JOIN `eventreg_events` `eventreg_events` 
							ON `eventreg_events`.`id` = `eventreg_events_reg`.`event_id`";				

		$rs=$this->mysqli->query($query) or die(mysqli_error($this->$mysqli));		
		$rs->data_seek(0);		
		$content = array();	
		
		$output = array( "aaData" => array());
			
		while($row = $rs->fetch_assoc()){

			$content = array();	
			
			$content[] = $row['id'];
            $content[] = $row['accepted'];            
            $content[] = $row['username'];
			$content[] = $row['birth_date'];
			$content[] = $row['email'];					
			$content[] = $row['grade'];
			$content[] = $row['cell'];			
			$content[] = $row['club_name'];

			if($row['accepted'])
				$content[] = "<span style='font-weight: bold; color: rgb(51, 204, 0);'>Godkänt</span>";
			else
				$content[] = "<span style='font-weight: bold; color: red;'>Inte godkänt</span>";				
			
			if($row['instructor'])
				$content[] = "Ja";
			else
				$content[] = "Nej";				
						
			$content[] = $row['event_name'];			
			
			$content[] = $row['accomodation'];

			$content[] = $row['extraInfo'];			

			$content[] = "";						
									
			$output['aaData'][] = $content;						
		}

		$rs->close();		
				
		return $output;			
	}
    
	function deleteMember($id){	
	
		$query = "DELETE FROM eventreg_events_reg WHERE id = ?";
	
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param('i', $id);
	
		$result = $stmt->execute();
		
		$stmt->close();	
		
		return $result;				
		
	}
    

	function sendMessageModal($memberID){
		
		$query = "SELECT
        		`eventreg_events_reg`.`username`,
        		`eventreg_events_reg`.`email`,
        		`eventreg_events`.`event_name`
        	FROM
        		`eventreg_events_reg` `eventreg_events_reg` 
        				INNER JOIN `eventreg_events` `eventreg_events` 
        				ON `eventreg_events`.`id` = `eventreg_events_reg`.`event_id`
        				WHERE `eventreg_events_reg`.`id` = ?";
	
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param('i', $memberID);		
		$stmt->execute();	
		$stmt->bind_result($userName,$email,$eventName);	
		$stmt->fetch();		
		$stmt->close();						
	
		$htmlString = "";		

		$htmlString .= "<input type='hidden' name='email' value='$email'>\n"; 		
		$htmlString .= "<input type='hidden' name='userID' value='$memberID'>\n"; 						
		
		$MESSAGE = "V&auml;lkommen till FightCamp p&aring; Bos&ouml;n 29-30 jan. 
Du &auml;r registrerad, men din anm&auml;lan &auml;r giltig f&ouml;rst n&auml;r boknings avg 300 kr &auml;r betald till SKK Swish konto 123 278 8735.

Mer detaljerad information om boende m.m. kommer senare.
Ev. fr&aring;gor kan skickas till magnus.hanssen@keiko.se.

Mvh

/Magnus";


		$htmlString .= "<div class='row'> \n"; 
		$htmlString .= "	<div class='col-xs-12'> \n"; 
		$htmlString .= "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
		$htmlString .= "          <label class=\"control-label\">Rubrik</label> \n"; 
		$htmlString .= "          <div class='controls'> \n"; 
		$htmlString .= "            <input class='form-control' type='text' name='subject' id='subject' value='Registrering till $eventName' >\n"; 
		$htmlString .= "          </div> \n"; 
		$htmlString .= "        </div> \n"; 
		$htmlString .= "	</div> \n"; 
		$htmlString .= "</div> \n"; 


		$message ="If you want to send an email to the user please write something here...";


		$htmlString .= "<div class='row'> \n"; 
		$htmlString .= "	<div class='col-xs-12'> \n"; 
		$htmlString .= "        <div class='control-group' style='margin-bottom: 10px;'> \n"; 
		$htmlString .= "          <label class=\"control-label\">Meddelande</label> \n"; 
		$htmlString .= "          <div class='controls'> \n"; 
		$htmlString .= "			  <textarea class='form-control' rows='20' name='message' id='message'>".$MESSAGE."</textarea> \n"; 
		$htmlString .= "          </div> \n"; 
		$htmlString .= "        </div> \n"; 
		$htmlString .= "	</div> \n"; 
		$htmlString .= "</div> \n"; 
		
		$htmlString .= "<div class='checkbox'><input name='sendEmail' type='checkbox' value=1 checked><strong>Skicka email</strong></div>";	

 			
		return $htmlString;	
	}	
    
	function activateUser($userID){	
	
		$query = "UPDATE eventreg_events_reg SET accepted = ? WHERE id = ?";
		
		$activated = 1;
	
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param('ii', $activated,$userID);	
		$result = $stmt->execute();		
		$stmt->close();		
		
		return $result;		
	}
    
	function deactivateUser($userID){	
	
		$query = "UPDATE eventreg_events_reg SET accepted = ? WHERE id = ?";
		
		$activated = 0;
	
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param('ii', $activated,$userID);	
		$result = $stmt->execute();		
		$stmt->close();		
		
		return $result;		
	}
    

}