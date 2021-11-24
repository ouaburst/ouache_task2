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


if (!isset($_SESSION['loginPassword'])){
	
	unset($_SESSION['currentfile']);

	$_SESSION['currentfile'] = "admin_admin_members.php";
	
	include("login.php");
}

else {

	include('include/DBHandler.php');
		
	$DBhandler = new DBHandler();
	
	$userID = $_SESSION['userID'];
	
	$level = $DBhandler->getUserLevel($userID);
	
	if($level != 1)
		header("location: login.php");	
	
	$message = "";	

	if(isset($_SESSION['loginPassword'])){
		$MD5password = $_SESSION['loginPassword'];
	}		
	elseif(isset($_COOKIE['loginPassword'])){
		$MD5password = $_COOKIE['loginPassword'];
	}

	// -------------------- Save -----------------------

	if(isset($_POST['saveMember'])){
		
		$DBhandler->addMember($_POST);					
	}
	
	// -------------------- Update -----------------------
	
	if(isset($_POST['updateMember'])){
	
	 	$DBhandler->updateMember($_POST);
	}
	
	// -------------------- Send email ---------------------

	if(isset($_POST['sendMessage'])){
		
		if(isset($_POST['sendEmail'])){			
			$sendEmail = $_POST['sendEmail']; 			
		}else
			$sendEmail = 0; 					
		
		$to = $_POST['email']; 
		$subject = $_POST['subject']; 
		$body = nl2br($_POST['message']);		
		
		$userID = $_POST['userID']; 
		
		if($sendEmail == 1){
	
			$headers = 'From: admin@kyokushin.se' . "\r\n" .
			'MIME-Version: 1.0\r\n' . "\r\n" .
			'Content-type: text/html;' . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();
		
			if (mail($to, $subject, $body, $headers)) {
			    $message = "<div class='alert alert-success'>Ett email har skickats.</div>";
			    
			} else {
			    $message = "<div class='alert alert-dangers'>Ett fel har uppstått. Email kunde inte skickas.</div>";
			}
		}

		$DBhandler->activateUser($userID);
	}
	

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description">
		<meta name="author" content="obkonsult.com">
		<title>SKK - Medlemmar</title>
		<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
<!-- 		<link rel="stylesheet" href="bootstrap/3.0.0/css/bootstrap.min.css"> -->
		<link rel="stylesheet" href="assets/css/datatables.css">

        <link rel="stylesheet" href="assets/css/datepicker.css" />
        <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css" />                

        
		<link rel="shortcut icon" href="../images/favicon.ico"> 
        	        	
	</head>
	
<!-- 	<body> -->
	
	<body onload="javascript:ListMembers()">
	
   <div class="container">


		<!-- Static navbar -->
		<nav class="navbar navbar-default" role="navigation">
		<div class="container-fluid">
		  <div class="navbar-header">
		    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
		      <span class="sr-only">Toggle navigation</span>
		      <span class="icon-bar"></span>
		      <span class="icon-bar"></span>
		      <span class="icon-bar"></span>
		    </button>
					
				<span class='navbar-brand'>Admin</span>

		  </div>
		  <div id="navbar" class="navbar-collapse collapse">
		    <ul class="nav navbar-nav">			
			    
				<?php //include("menu.php"); ?>                      
				
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
				<li><a href="logout.php">Logga ut</a></li>              
		    </ul>
		  </div><!--/.nav-collapse -->
		</div><!--/.container-fluid -->
		</nav>


		<div class="row">								
		    <div class="col-sm-4">			    
				<div id="message"> <?=$message ?></div>         				
		    </div>		    
		</div>

		<div class="row" style='margin-bottom: 20px;'>						
		
		    <div class="col-sm-10">
		    
				<h4>SKK - Medlemmar</h4>					
		    	
				<a data-toggle='modal' href='#createMemberModal' class='btn btn-primary' onclick="createMemberModal()">L&auml;gg till en ny medlem</a>						
				<a href='admin_seminars.php' class='btn btn-primary' >Seminarium</a>										
																		
		    </div>
		    
		</div>

		<div class="row">						
		    <div class="col-xs-12">
				<table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered" id="usersTable">
									
					<thead>
						<tr>
							<th>id</th>	
							<th>Foto</th>																			
							<th>Namn</th>
							<th>Grad</th>	
							<th>Klubb</th>
							<th>Document</th>	
							<th>Status</th>	
							<th>aktiverad</th>								
							<th>Behörighet</th>																																			
							
							<th>&Aring;tg&auml;rd</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="5" class="dataTables_empty">Loading data from server</td>																
						</tr>
					</tbody>
				</table>
		    </div>
		</div>
		
		<div id="spin" style="margin: 0 auto;"></div>		
		
		<div id="ajaxResult"></div>		
		<p class="text-muted"><?php //echo $DBhandler->getSupportformVersion(1); ?></p>			
		
	</div> <!-- /container -->

		<!-- Modals -->

<!--  ---------------------------- Send message modal --------------------------------------- -->

	<form class="form-horizontal" method="post" action="admin_members.php" id="block-validate03">         				
	
	  <div class="modal fade" id="sendMessageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	    <div class="modal-dialog">
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title">Aktivera användaren</h4>
	        </div>
	        <div class="modal-body">

                <div class="controls" id="ajaxContent04">							
						<!-- Ajax call here  -->

                </div>
	          
	        </div>

	        <div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal">St&auml;ng</button>
			  <button class="btn btn-primary" type="submit" name="sendMessage">Aktivera</button>		          
	        </div>
	      </div><!-- /.modal-content -->
	    </div><!-- /.modal-dialog -->
	  </div><!-- /.modal -->
	  
	</form>
		
<!--  ---------------------------- /send message  modal --------------------------------------- -->
		
<!--  ---------------------------- Change password modal --------------------------------------- -->

	<form class="form-horizontal" method="post" action="admin_members.php" id="block-validate03">         				
	
	  <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	    <div class="modal-dialog">
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title">&Auml;ndra l&ouml;senord</h4>
	        </div>
	        <div class="modal-body">

                <div class="controls" id="ajaxContent03">							
						<!-- Ajax call here  -->

                </div>
	          
	        </div>

	        <div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal">St&auml;ng</button>
			  <button class="btn btn-primary" type="submit" name="updatePassword">Uppdatera</button>		          
	        </div>
	      </div><!-- /.modal-content -->
	    </div><!-- /.modal-dialog -->
	  </div><!-- /.modal -->
	  
	</form>
		
<!--  ---------------------------- /Change password  modal --------------------------------------- -->

<!--  ---------------------------- Create member modal --------------------------------------- -->

	<form class="form-horizontal" method="post" action="admin_members.php" id="block-validate">         				
	
		  <div class="modal fade" id="createMemberModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-dialog">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		          <h4 class="modal-title">L&auml;gg till en ny medlem</h4>
		        </div>
		        
			        <div class="modal-body">
	
	                    <div class="controls" id="ajaxContent02">							
	
								<!-- Ajax call here  -->
	                    </div>
			        </div>

			        <div class="modal-footer">
			          <button type="button" class="btn btn-default" data-dismiss="modal">St&auml;ng</button>
			          <button class="btn btn-primary" type="submit" name="saveMember">Spara</button>
			        </div>
		        
		      </div><!-- /.modal-content -->
		    </div><!-- /.modal-dialog -->
		  </div><!-- /.modal -->
		  
	</form>
		
<!--  ---------------------------- /Create member modal --------------------------------------- -->

<!--  ---------------------------- Update user modal --------------------------------------- -->

	<form class="form-horizontal" method="post" action="admin_members.php" id="block-validate02"  enctype="multipart/form-data">         				
	
	  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	    <div class="modal-dialog">
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title">Uppdatera medlem</h4>
	        </div>
	        <div class="modal-body">

                <div class="controls" id="ajaxContent01">							
						<!-- Ajax call here  -->
                </div>
	          
	        </div>

	        <div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal">St&auml;ng</button>
			  <button class="btn btn-primary" type="submit" name="updateMember">Uppdatera</button>		          
	        </div>
	      </div><!-- /.modal-content -->
	    </div><!-- /.modal-dialog -->
	  </div><!-- /.modal -->
	  
	</form>

<!--  ---------------------------- displayUserInfoModal --------------------------------------- -->

	<form class="form-horizontal" method="post" action="admin_members.php" id="block-validate02"  enctype="multipart/form-data">         				
	
	  <div class="modal fade" id="displayUserInfoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	    <div class="modal-dialog">
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title">Information</h4>
	        </div>
	        <div class="modal-body">

                <div class="controls" id="ajaxContent05">							
						<!-- Ajax call here  -->
                </div>
	          
	        </div>

	        <div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal">St&auml;ng</button>
	        </div>
	      </div><!-- /.modal-content -->
	    </div><!-- /.modal-dialog -->
	  </div><!-- /.modal -->
	  
	</form>
		
<!--  ---------------------------- /displayUserInfoModal --------------------------------------- -->

        <!-- /#EditModal -->

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>		
		<script src="//cdnjs.cloudflare.com/ajax/libs/datatables/1.9.4/jquery.dataTables.min.js"></script>
		<script src="assets/js/datatables.js"></script>
		
        <script type="text/javascript" src="assets/js/lib/jquery.tablesorter.min.js"></script>		
        <script type="text/javascript" src="assets/js/lib/bootstrap-datepicker.js"></script> 
	    <script type='text/javascript' src="assets/js/lib/bootstrap-timepicker.js"></script>	
	    <script type='text/javascript' src="assets/js/lib/bootstrap-datetimepicker.min.js"></script>		        
		<script type='text/javascript' src="assets/js/lib/languages/bootstrap-datetimepicker.sv.js"></script> 

	    <script type='text/javascript' src="assets/js/lib/jquery.validationEngine.js"></script>
	    <script type='text/javascript' src="assets/js/lib/jquery.validate.min.js"></script>
	    <script type='text/javascript' src="assets/js/lib/languages/messages_sv.js"></script>        	                    
        
        <script type="text/javascript" src="assets/js/main.js"></script>     
				
		<script type="text/javascript" src="assets/js/spin.min.js"></script>
		
		<script type="text/javascript" src="assets/js/bootbox.min.js"></script>		
		
		<script>		
	        $(function() {
	            formValidation();
	        });
	                
		</script>
		
		
		<script type="text/javascript">

		var opts = {
		  lines: 10, // The number of lines to draw
		  length: 20, // The length of each line
		  width: 10, // The line thickness
		  radius: 30, // The radius of the inner circle
		  corners: 0.6, // Corner roundness (0..1)
		  rotate: 11, // The rotation offset
		  direction: 1, // 1: clockwise, -1: counterclockwise
		  color: '#000', // #rgb or #rrggbb
		  speed: 2, // Rounds per second
		  trail: 56, // Afterglow percentage
		  shadow: false, // Whether to render a shadow
		  hwaccel: false, // Whether to use hardware acceleration
		  className: 'spinner', // The CSS class to assign to the spinner
		  zIndex: 2e9, // The z-index (defaults to 2000000000)
		  top: 'auto', // Top position relative to parent in px
		  left: 'auto' // Left position relative to parent in px
		};

		var target = document.getElementById('spin');
		var spinner = new Spinner(opts);

		/* ---------------------------------------- */
				
		function changePasswordModal(userID){
		
			console.log("userID: "+userID);
		
			var changePasswordModal = "changePasswordModal";
				
			$.ajax({                              
		      url: 'ajax.php', 
			  type: "POST",			      
		      data: ({changePasswordModal: changePasswordModal, userID: userID}),             			                            
		      success: function(data) 
		      {
				  $("#ajaxContent03").html(data);
		      } 
		    });
		}		    		    

		/* ---------------------------------------- */

		function createMemberModal(){
		
			var createMemberModal = "createMemberModal";
				
			$.ajax({                              
		      url: 'ajax.php', 
			  type: "POST",			      
		      data: ({createMemberModal: createMemberModal}),             			                            
		      success: function(data) 
		      {
				  $("#ajaxContent02").html(data);
		      } 
		    });
		}		   
		
		/* ---------------------------------------- */
				
		function editMemberModal(memberID){
		
			console.log("memberID: "+memberID);
		
			var editMemberModal = "editMemberModal";
				
			$.ajax({                              
		      url: 'ajax.php', 
			  type: "POST",			      
		      data: ({editMemberModal: editMemberModal, memberID: memberID}),             			                            
		      success: function(data) 
		      {
				  $("#ajaxContent01").html(data);
		      } 
		    });
		}		    		    

		/* ---------------------------------------- */
				
		function sendMessageModal(memberID){
		
			console.log("memberID: "+memberID);
		
			var sendMessageModal = "sendMessageModal";
				
			$.ajax({                              
		      url: 'ajax.php', 
			  type: "POST",			      
		      data: ({sendMessageModal: sendMessageModal, memberID: memberID}),             			                            
		      success: function(data) 
		      {
				  $("#ajaxContent04").html(data);
		      } 
		    });
		}		    		    

		/* ---------------------------------------- */
				
		function deactivateUser(memberID){
		
			console.log("memberID: "+memberID);
		
			var deactivateUser = "deactivateUser";
				
			$.ajax({                              
		      url: 'ajax.php', 
			  type: "POST",			      
		      data: ({deactivateUser: deactivateUser, memberID: memberID}),             			                            
		      success: function(data) 
		      {
				  ListMembers();
		      } 
		    });
		}		    		    

		/* ---------------------------------------- */
					
		function deleteMember(memberID) { 
		
		    bootbox.confirm("Vill du verkligen radera posten?", $.proxy(function(result) {
		        if (result) {

				console.log("memberID: "+memberID);				            							

				var deleteMember = "deleteMember";
			
				$.ajax({                                      
			      url: 'ajax.php', 
				  type: "POST",		
				  async: "false",	      
			      data: ({deleteMember: deleteMember, memberID: memberID}),             			                         
			      success: function(data) 
			      {
					  ListMembers();	
			      } 
			    });					
					
					
		        }
		    }, this));
		
		}

		/* ---------------------------------------- */
		
		function displayUserInfoModal(memberID){
		
			console.log("memberID: "+memberID);
		
			var displayUserInfoModal = "displayUserInfoModal";
				
			$.ajax({                              
		      url: 'ajax.php', 
			  type: "POST",			      
		      data: ({displayUserInfoModal: displayUserInfoModal, memberID: memberID}),             			                            
		      success: function(data) 
		      {
				  $("#ajaxContent05").html(data);
		      } 
		    });
		}		    		    

		/* ---------------------------------------- */
				
		function ListMembers() { 		
		
			spinner.spin(target);

			$('#usersTable').dataTable( {
				"bDestroy": true,				
				"bProcessing": true,
				"sAjaxSource": "ajax.php",
				"sPaginationType": "bs_full",
				"bAutoWidth": false,
				"iDisplayLength": 100,
				responsive: true,
				
				"fnServerData": function ( sUrl, aoData, fnCallback, oSettings ) {
						oSettings.jqXHR = $.ajax( {
							"url":  sUrl,
							"data": aoData,
							"success": function (json) {
							
								spinner.stop();
								
								if ( json.sError ) {
									oSettings.oApi._fnLog( oSettings, 0, json.sError );
								}
								
								$(oSettings.oInstance).trigger('xhr', [oSettings, json]);
								fnCallback( json );
							},
							"dataType": "json",
							"cache": false,
							"type": oSettings.sServerMethod,
							"error": function (xhr, error, thrown) {
								
								spinner.stop();
				
								if ( error == "parsererror" ) {
									oSettings.oApi._fnLog( oSettings, 0, "DataTables warning: JSON data from "+
										"server could not be parsed. This is caused by a JSON formatting error." );
								}
							}
						} );
					},

				"sServerMethod": "POST",
				"fnServerParams": function ( aoData ) {
				      aoData.push( { "name": "getMembers", "value": "getMembers" } );
				},    

				"aaSorting": [[ 1, "desc" ]],
				"aoColumns": [ {"bVisible": false}, null,null,null,null,null,null,{"bVisible": false},null,
				
				  { 
				    "bSearchable": false,
				    "bSortable": false,
				    "mDataProp": null,
				    "fnRender": function (oObj) {
				    	var operString = "";
				    	
				    	operString += " <a data-toggle='modal' href='#editModal' class='btn btn-primary' onclick=\"editMemberModal('"+oObj.aData[0]+"')\"><i class='glyphicon glyphicon-pencil'></i></a>";

					    operString += " <a data-toggle='modal' href='#changePasswordModal' class='btn btn-primary' onclick=\"changePasswordModal('"+oObj.aData[0]+"')\"><i class='glyphicon glyphicon-lock'></i></a>";

 				    	operString += " <a data-toggle='modal' href='#displayUserInfoModal' class='btn btn-success' onclick=\"displayUserInfoModal('"+oObj.aData[0]+"')\"><i class='glyphicon glyphicon-eye-open'></i></a>";		       				    	
 				    	
						if(oObj.aData[7] == 0){						    
							operString += " <a data-toggle='modal' href='#sendMessageModal' class='btn btn-info' href='#' onclick=\"sendMessageModal('"+oObj.aData[0]+"')\"><i class='glyphicon glyphicon-plus-sign'></i></a>";	 				    
						}
						
					    if(oObj.aData[7] == 1){						    
							operString += " <a role='button' class='btn btn-danger' href='#' onclick=\"deactivateUser('"+oObj.aData[0]+"')\"><i class='glyphicon glyphicon-minus-sign'></i></a>";		       				    					    
						}
 				    	
 				    	operString += " <a role='button' class='btn btn-danger' href='#' onclick=\"deleteMember('"+oObj.aData[0]+"')\"><i class='glyphicon glyphicon-remove'></i></a>";		       				    	
				    	
				    	return operString;
				     }
				  }
				  
				]
			} );
		}
		
/* 		----------------- LOG OUT SCRIPT -------------------		 */

		<?php include("include/sessionLogout.php"); ?>
		
/* 		----------------- / LOG OUT SCRIPT -------------------		 */
		
		</script>
	</body>
</html>
<?php
	
} 
?>