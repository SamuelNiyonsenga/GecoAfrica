<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Publish event</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="dropzone/dropzone.css" type="text/css">
	<link rel="icon" href="../../images/flont_logo.png" type="image/x-icon">
<!--aadditional scrept-->
  
<script>function close_window() {
  
  close();

}</script> 
</head>
<body>
	
    <div class="container">
	<div style="align:right;">
	<button>	</button><a href="#" ><i type="button"  onclick="javascript:close_window();" class="fa fa-1x fa-times-circle float-right">Close</i> </a>
            </div>               
	<div class="alert alert-warning my-2" id="waitingmsg">
        	<i class="fa fa-2x fa-exclamation-circle float-right"></i>
        	<ol class="m-0"> 
				<li >Wait while Publishing........</li> 
            </ol>
		</div>
		<div class="alert alert-success" id="msguploaded"  style="display: none;">Successfully Published..., wait while closing</div>
		<div  >
            <div class="alert alert-danger" id="msgerror"  style="display: none;">Same thing went wrong.</div>
		<div  >
        
</div>
           </div>
    <hr class="my-5">
    <div class="container">
    	<div id="msg" class="mb-3"></div>
       <!-- <a href="javascript:void(0);" class="btn btn-outline-primary reorder" id="updateReorder">Reorder Imgaes</a>-->
        
        <div class="gallery">
            <ul class="nav nav-pills">
            
            </ul>
        </div>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <script src="dropzone/dropzone.js"></script>
     
  
 <script>
 $(document).ready(function() {
	$.ajax({
      url: '../Ajaxpublishevent.php?eventID=<?php echo$_GET['eventID'];?>',
      data: $(this).serialize(),
      method: 'post',
      dataType: 'JSON',
	  cache:false
    }).done(function(resp) {
		if(resp.result=='Success'){
            $('#waitingmsg').hide();
  $('#msguploaded').show(); 

  window.setTimeout(function(){
                          window.opener.location.reload(); // or whatever needs to be done (if anything)
                 window.close();
                },5000);
        }
	   //alert(resp.request);
     //$('#loadernotification').hide();	
	//	}
    else{
        $('#waitingmsg').hide();
  $('#msgerror').show();
        }
	
    });
	 
	 
	 
	 
 });
 
 </script>
</body>
</html>
