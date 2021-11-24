<?php
session_start();

/*
 * Product: Supportform 
 * Version: 2.0
 * Author: Oualid Burstrom
 * Email: ob@obkonsult.com 
 * Data: 2014-01-31
 * 
 */


include('include/DBHandler.php');
	
$DBhandler = new DBHandler();

if(isset($_POST['getEvents'])){

	$result = array();
	
	$result = $DBhandler->getEvents();
		
	if($result)
		echo json_encode($result);
	else{
		
		echo '{
		    "sEcho": 1,
		    "iTotalRecords": "0",
		    "iTotalDisplayRecords": "0",
		    "aaData": []
		}';							
	}
}

if(isset($_POST['getRegistrations'])){

	$result = array();
	
	$result = $DBhandler->getRegistrations();
		
	if($result)
		echo json_encode($result);
	else{
		
		echo '{
		    "sEcho": 1,
		    "iTotalRecords": "0",
		    "iTotalDisplayRecords": "0",
		    "aaData": []
		}';							
	}
}


if(isset($_POST['createEventModal'])){
	
	echo $DBhandler->createEventModal();	
}

if(isset($_POST['deleteEvent'])){

	$eventID = $_POST['eventID']; 		
	
	$DBhandler->deleteEvent($eventID);		
}

if(isset($_POST['editEventModal'])){

	$eventID = $_POST['eventID']; 		
	
	echo $DBhandler->editEventModal($eventID);	
}

if(isset($_POST['deactivateUser'])){

	$memberID = $_POST['memberID']; 		
	
	$DBhandler->deactivateUser($memberID);		
}


//---------------------------------------------------------











if(isset($_POST['getNews'])){

	$result = array();
	
	$result = $DBhandler->getNews();
		
	if($result)
		echo json_encode($result);
	else{
		
		echo '{
		    "sEcho": 1,
		    "iTotalRecords": "0",
		    "iTotalDisplayRecords": "0",
		    "aaData": []
		}';							
	}
}


if(isset($_POST['getDocuments'])){

	$result = array();
	
	$userID = $_SESSION['userID'];
	
	$result = $DBhandler->getDocuments($userID);
		
	if($result)
		echo json_encode($result);
	else{
		
		echo '{
		    "sEcho": 1,
		    "iTotalRecords": "0",
		    "iTotalDisplayRecords": "0",
		    "aaData": []
		}';							
	}
}





if(isset($_POST['getSeminarParticipants'])){

	$result = array();
	
	$TKId =	$_SESSION['TKId'];	 	
	
	$result = $DBhandler->getSeminarParticipants($TKId);
		
	if($result)
		echo json_encode($result);
	else{
		
		echo '{
		    "sEcho": 1,
		    "iTotalRecords": "0",
		    "iTotalDisplayRecords": "0",
		    "aaData": []
		}';							
	}
}




// ----------------------- Cards ------------------------

if(isset($_POST['getCards'])){

	$result = array();
	
	$result = $DBhandler->getCards();
		
	if($result)
		echo json_encode($result);
	else{
		
		echo '{
		    "sEcho": 1,
		    "iTotalRecords": "0",
		    "iTotalDisplayRecords": "0",
		    "aaData": []
		}';							
	}
}

// ----------------------- Cards ------------------------

if(isset($_POST['getCardsKids'])){

	$result = array();
	
	$result = $DBhandler->getCardsKids();
		
	if($result)
		echo json_encode($result);
	else{
		
		echo '{
		    "sEcho": 1,
		    "iTotalRecords": "0",
		    "iTotalDisplayRecords": "0",
		    "aaData": []
		}';							
	}
}


// ----------------------- Messages ------------------------

if(isset($_POST['getMessages'])){

	$klubbID = 1;

	$result = array();
	
	$result = $DBhandler->getMessages($klubbID);
		
	if($result)
		echo json_encode($result);
	else{
		
		echo '{
		    "sEcho": 1,
		    "iTotalRecords": "0",
		    "iTotalDisplayRecords": "0",
		    "aaData": []
		}';							
	}
}


// ----------------------- Workouts Adults ------------------------

if(isset($_POST['statShowParticipentsToNextSessionAdmin'])){

	$result = array();
	
	$today = date('Y-m-d');	
	$sessionDate = $DBhandler->getClosestSessionDate($today);
	$kids = 0;
	
	$result = $DBhandler->statShowParticipentsToNextSessionAdmin($sessionDate,$kids);
		
	if($result)
		echo json_encode($result);
	else{
		
		echo '{
		    "sEcho": 1,
		    "iTotalRecords": "0",
		    "iTotalDisplayRecords": "0",
		    "aaData": []
		}';							
	}
}

// ----------------------- Workouts Kids ------------------------

if(isset($_POST['statShowParticipentsToNextSessionKidsAdmin'])){

	$result = array();
	$kids = 1;	
	$today = date('Y-m-d');	
	$sessionDate = $DBhandler->getClosestSessionDate($today,$kids);
	
	$result = $DBhandler->statShowParticipentsToNextSessionAdmin($sessionDate,$kids);
		
	if($result)
		echo json_encode($result);
	else{
		
		echo '{
		    "sEcho": 1,
		    "iTotalRecords": "0",
		    "iTotalDisplayRecords": "0",
		    "aaData": []
		}';							
	}
}

// ----------------------- All Workouts ------------------------

if(isset($_POST['getAllWorkoutDays'])){

	$result = array();
	
	$thisYear = date('Y');	
	
	$result = $DBhandler->getAllWorkoutDays($thisYear);
		
	if($result)
		echo json_encode($result);
	else{
		
		echo '{
		    "sEcho": 1,
		    "iTotalRecords": "0",
		    "iTotalDisplayRecords": "0",
		    "aaData": []
		}';							
	}
}

// ------------------------------------------------------------

if(isset($_POST['getAllWorkoutDaysKids'])){

	$result = array();
	
	$thisYear = date('Y');	
	$kids = 1;
	
	$result = $DBhandler->getAllWorkoutDays($thisYear,$kids);
		
	if($result)
		echo json_encode($result);
	else{
		
		echo '{
		    "sEcho": 1,
		    "iTotalRecords": "0",
		    "iTotalDisplayRecords": "0",
		    "aaData": []
		}';							
	}
}

// ------------------------------------------------------------

if(isset($_POST['getAvailableWorkoutDaysForInstruktors'])){

	$result = array();
		
	$result = $DBhandler->getAvailableWorkoutDaysForInstruktors();
		
	if($result)
		echo json_encode($result);
	else{
		
		echo '{
		    "sEcho": 1,
		    "iTotalRecords": "0",
		    "iTotalDisplayRecords": "0",
		    "aaData": []
		}';							
	}
}


// ------------------------------------------------------------

if(isset($_POST['deleteNews'])){
	
	$newsId = $_POST['newsId']; 			
	
	echo $DBhandler->deleteNews($newsId);	
}

if(isset($_POST['editNewsModal'])){

	$newsId = $_POST['newsId']; 			
		
	echo $DBhandler->editNewsModal($newsId);	
}

if(isset($_POST['createNewsModal'])){
	
	echo $DBhandler->createNewsModal();	
}



if(isset($_POST['createCommentModal'])){
	
	$userID = $_POST['userID']; 			

	echo $DBhandler->createCommentModal($userID);	
}


if(isset($_POST['deleteParticipant'])){

	$memberId = $_POST['memberId']; 		
	
	$DBhandler->deleteParticipant($memberId);		
}


if(isset($_POST['deleteDate'])){

	$dateID = $_POST['dateID']; 		
	
	$DBhandler->deleteDate($dateID);		
}

if(isset($_POST['deleteCardRegistration'])){

	$cardId = $_POST['cardId']; 		
	
	$DBhandler->deleteCardRegistration($cardId);		
}

if(isset($_POST['listCommentsModal'])){

	$userID = $_POST['userID']; 		
	
	echo $DBhandler->listCommentsModal($userID);		
}

if(isset($_POST['listCommentsModal2'])){

	$userID = $_POST['userID']; 		
	
	echo $DBhandler->listCommentsModal2($userID);		
}

if(isset($_POST['showCommentModal'])){
	
	$commentID = $_POST['commentID']; 		

	echo $DBhandler->showCommentModal($commentID);	
}

if(isset($_POST['editCommentModal'])){
	$commentID = $_POST['commentID']; 		
	echo $DBhandler->editCommentModal($commentID);	
}

if(isset($_POST['sendEmailModal'])){
	echo $DBhandler->sendEmailModal();	
}



// --------------------------------------------------------------


if(isset($_POST['editMemberModal'])){

	$memberID = $_POST['memberID']; 		
	
	echo $DBhandler->editMemberModal($memberID);	
}

if(isset($_POST['editMemberModal2'])){

	$memberID = $_POST['memberID']; 		
	
	echo $DBhandler->editMemberModal2($memberID);	
}

if(isset($_POST['editMessageModal'])){

	$messageID = $_POST['messageId']; 		
	
	echo $DBhandler->editMessageModal($messageID);	
}

if(isset($_POST['editRefereeModal'])){

	$memberID = $_POST['memberID']; 		
	
	echo $DBhandler->editRefereeModal($memberID);	
}

if(isset($_POST['changePasswordModal'])){

	$userID = $_POST['userID']; 		
	
	echo $DBhandler->changePasswordModal($userID);	
}
	
if(isset($_POST['deleteMember'])){

	$memberID = $_POST['memberID']; 		
	
	$DBhandler->deleteMember($memberID);		
}

if(isset($_POST['deleteDept'])){

	$id = $_POST['id']; 		
	
	$DBhandler->deleteDept($id);		
}

if(isset($_POST['deleteDocument'])){

	$documentID = $_POST['documentID']; 		
	
	$DBhandler->deleteDocument($documentID);		
}

if(isset($_POST['deleteMessage'])){

	$messageId = $_POST['messageId']; 		
	
	$DBhandler->deleteMessage($messageId);		
}


if(isset($_POST['deleteSeminar'])){

	$seminarID = $_POST['seminarID']; 		
	
	$DBhandler->deleteSeminar($seminarID);		
}




if(isset($_POST['displayUserInfoModal'])){

	$memberID = $_POST['memberID']; 		
	
	echo $DBhandler->displayUserInfoModal($memberID);		
}

if(isset($_POST['displayUserInfoModal2'])){

	$memberID = $_POST['memberID']; 		
	
	echo $DBhandler->displayUserInfoModal2($memberID);		
}

if(isset($_POST['displayRefereeyModal'])){

	$memberID = $_POST['memberID']; 		
	
	echo $DBhandler->displayRefereeyModal($memberID);		
}

if(isset($_POST['registerSeminarModal'])){

	$memberID = $_POST['memberID']; 		
	
	echo $DBhandler->registerSeminarModal($memberID);		
}




// --------------------------------------------------

if(isset($_POST['createMemberModal'])){

	echo $DBhandler->createMemberModal();	
}

if(isset($_POST['createDepthModal'])){

	$userId = $_POST['userId']; 		

	echo $DBhandler->createDepthModal($userId);	
}

if(isset($_POST['createMessageModal'])){

	$clubID = 1;

	echo $DBhandler->createMessageModal($clubID);	
}




// --------------------------------------------------

if(isset($_POST['editDeptModal'])){

	$id = $_POST['id']; 		
	
	echo $DBhandler->editDeptModal($id);	
}

if(isset($_POST['sendMessageModal'])){

	$memberID = $_POST['memberID']; 		
	
	echo $DBhandler->sendMessageModal($memberID);	
}

if(isset($_POST['sendMessageModal2'])){

	$memberID = $_POST['memberID']; 		
	
	echo $DBhandler->sendMessageModal2($memberID);	
}

if(isset($_POST['gradingModal'])){

	$memberID = $_POST['memberID']; 		
	
	echo $DBhandler->gradingModal($memberID);	
}



if(isset($_POST['createSesseionInstructorModal'])){

	echo $DBhandler->createSesseionInstructorModal();	
}

if(isset($_POST['connectUserCardModal'])){

	$cardId = $_POST['cardId']; 		
	echo $DBhandler->connectUserCardModal($cardId);	
}

if(isset($_POST['connectUserCardModalKids'])){

	$cardId = $_POST['cardId']; 		
	echo $DBhandler->connectUserCardModalKids($cardId);	
}

if(isset($_POST['uploadDocumentModal'])){

	$userID = $_POST['userID']; 		
	echo $DBhandler->uploadDocumentModal($userID);	
}



// --------------------------------------------------

if(isset($_POST['countParticipents'])){

	$result = array();
	
	$result = $DBhandler->countParticipents();
		
	if($result)
		echo json_encode($result);
	else{
		
		echo '{
		    "sEcho": 1,
		    "iTotalRecords": "0",
		    "iTotalDisplayRecords": "0",
		    "aaData": []
		}';							
	}
}

// --------------------------------------------------

if(isset($_POST['countParticipentsKids'])){

	$result = array();
	$kids = 1;
	$result = $DBhandler->countParticipents($kids);
		
	if($result)
		echo json_encode($result);
	else{
		
		echo '{
		    "sEcho": 1,
		    "iTotalRecords": "0",
		    "iTotalDisplayRecords": "0",
		    "aaData": []
		}';							
	}
}


?>