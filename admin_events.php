<?php
session_start();

/*
 * Product: Supportform 
 * Version: 1.0
 * Author: Oualid Burstrom
 * Email: ob@obkonsult.com 
 * Data: 2021-10-1
 * 
 */


if (!isset($_SESSION['loginPassword'])){
	
	unset($_SESSION['currentfile']);

	$_SESSION['currentfile'] = "admin_events.php";
	
	include("login.php");
}

else {

	include('include/DBHandler.php');
		
	$DBhandler = new DBHandler();
	

	// -------------------- Save -----------------------

	if(isset($_POST['saveEvent'])){
		
		
		$DBhandler->addEvent($_POST);					
	}
	
	// -------------------- Update -----------------------
	
	if(isset($_POST['updateEvent'])){
	
	 	$DBhandler->updateEvent($_POST);
	 	
	}
		

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Supportform V3">
		<meta name="author" content="obkonsult.com">
		<title>Admin - events</title>
		<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
		<link rel="stylesheet" href="assets/css/datatables.css">

        <link rel="stylesheet" href="assets/css/datepicker.css" />
        <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css" />                
		<link rel="shortcut icon" href="../images/favicon.ico"> 
        	        	
	</head>
	
<!-- 	<body> -->
	
	<body onload="javascript:ListEvents()">
	
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
				
				<?php
					if($level == 2){
						$infoData = $DBhandler->getUserInfo($userID);
						echo "<span class='navbar-brand'>".$infoData[0]."</span>";
					}
					else
						echo "<span class='navbar-brand'>Admin</span>";
				?>

		  </div>
		  <div id="navbar" class="navbar-collapse collapse">
		    <ul class="nav navbar-nav">			
			    
<!-- 						include("menu.php"); 				 -->
				
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
		    
				<h4>Hantera event</h4>					
		    	
				<a href='admin.php' class='btn btn-primary' >Hantera anmälningar</a>										
				<a data-toggle='modal' href='#createEventModal' class='btn btn-primary' onclick="createEventModal()">L&auml;gg till ett event</a>										
																		
		    </div>
		    
		</div>

		<div class="row">						
		    <div class="col-sm-12">
				<table cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered" id="usersTable">
									
					<thead>
						<tr>
							<th>id</th>	
							<th>Event</th>	
							<th>Startdatum</th>																			
							<th>Slutdatum</th>
							<th>Plats</th>
							<th>Arrangör</th>		
							<th>Epost-notifiering</th>		
							<th>HTTP-länk</th>
							<th>Status</th>                            																
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

<!--  ---------------------------- Create member modal --------------------------------------- -->

	<form class="form-horizontal" method="post" action="admin_events.php" id="block-validate">         				
	
		  <div class="modal fade" id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-dialog">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		          <h4 class="modal-title">L&auml;gg till ett event</h4>
		        </div>
		        
			        <div class="modal-body">
	
	                    <div class="controls" id="ajaxContent02">							
	
								<!-- Ajax call here  -->
	                    </div>
			        </div>

			        <div class="modal-footer">
			          <button type="button" class="btn btn-default" data-dismiss="modal">St&auml;ng</button>
			          <button class="btn btn-primary" type="submit" name="saveEvent">Spara</button>
			        </div>
		        
		      </div><!-- /.modal-content -->
		    </div><!-- /.modal-dialog -->
		  </div><!-- /.modal -->
		  
	</form>
		
<!--  ---------------------------- /Create member modal --------------------------------------- -->

<!--  ---------------------------- Update event modal --------------------------------------- -->

	<form class="form-horizontal" method="post" action="admin_events.php" id="block-validate02"  enctype="multipart/form-data">         				
	
	  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	    <div class="modal-dialog">
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title">Uppdatera event</h4>
	        </div>
	        <div class="modal-body">

                <div class="controls" id="ajaxContent01">							
						<!-- Ajax call here  -->
                </div>
	          
	        </div>

	        <div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal">St&auml;ng</button>
			  <button class="btn btn-primary" type="submit" name="updateEvent">Uppdatera</button>		          
	        </div>
	      </div><!-- /.modal-content -->
	    </div><!-- /.modal-dialog -->
	  </div><!-- /.modal -->
	  
<!--  ---------------------------- /displayUserInfoModal --------------------------------------- -->

        <!-- /#EditModal -->

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.9.4/jquery.dataTables.min.js"></script>
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

		function createEventModal(){
		
			var createEventModal = "createEventModal";
				
			$.ajax({                              
		      url: 'ajax.php', 
			  type: "POST",			      
		      data: ({createEventModal: createEventModal}),             			                            
		      success: function(data) 
		      {
				  $("#ajaxContent02").html(data);
		      } 
		    });
		}		   
		
		/* ---------------------------------------- */
				
		function editEventModal(eventID){
		
			console.log("eventID: "+eventID);
		
			var editEventModal = "editEventModal";
				
			$.ajax({                              
		      url: 'ajax.php', 
			  type: "POST",			      
		      data: ({editEventModal: editEventModal, eventID: eventID}),             			                            
		      success: function(data) 
		      {
				  $("#ajaxContent01").html(data);
		      } 
		    });
		}		    		    

		/* ---------------------------------------- */
					
		function deleteEvent(eventID) { 
		
		    bootbox.confirm("Vill du verkligen radera posten?", $.proxy(function(result) {
		        if (result) {

				console.log("eventID: "+eventID);				            							

				var deleteEvent = "deleteEvent";
			
				$.ajax({                                      
			      url: 'ajax.php', 
				  type: "POST",		
				  async: "false",	      
			      data: ({deleteEvent: deleteEvent, eventID: eventID}),             			                         
			      success: function(data) 
			      {
					  ListEvents();	
			      } 
			    });					
					
					
		        }
		    }, this));
		
		}

		/* ---------------------------------------- */
				
		function ListEvents() { 		
		
			spinner.spin(target);

			$('#usersTable').dataTable( {
				"bDestroy": true,				
				"bProcessing": true,
				"sAjaxSource": "ajax.php",
				"sPaginationType": "bs_full",
				"bAutoWidth": false,
				"iDisplayLength": 100,
				
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
    		      aoData.push( { "name": "getEvents", "value": "getEvents" } );
                },
				"aaSorting": [[ 1, "desc" ]],
				"aoColumns": [ {"bVisible": false}, null,null,null,null,null,null,null,null,
				
				  { 
				    "bSearchable": false,
				    "bSortable": false,
				    "mDataProp": null,
				    "fnRender": function (oObj) {
				    	var operString = "";
				    	
				    	operString += " <a data-toggle='modal' href='#editModal' class='btn btn-primary' onclick=\"editEventModal('"+oObj.aData[0]+"')\"><i class='glyphicon glyphicon-pencil'></i></a>";
 				    	
 				    	operString += " <a role='button' class='btn btn-danger' href='#' onclick=\"deleteEvent('"+oObj.aData[0]+"')\"><i class='glyphicon glyphicon-remove'></i></a>";		       				    	
				    	
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