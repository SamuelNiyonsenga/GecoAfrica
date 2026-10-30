<?php 
session_start();
//make this while upload
 if(!empty($_SESSION['current_session'])) { 
//if(empty($_SESSION['current_session'])) { ?>
<p class="mb-0"><a href="#"onclick = "createPopupWinRef('communication/communication.php','Our references', 650, 650);"  class="btn btn-primary" title="Visit them">Add New event <span class="ion-ios-arrow-round-forward"></span></a></p>
<?php } ?>
 
     <section class="ftco-section">
			<div class="container">
				<div class="row">
          <div class="col-lg-8 ftco-animate">
            <h2 class="mb-3 text-info">Our Activities List</h2>
            <p>Activities.</p>
            <!--- EVENT---->
            <?php    // SELECT FIRST IMAGE
  $sqli= "SELECT `tb_events`.*  FROM  tb_events ORDER BY `tb_events`.`event_duration` DESC";
  $stmt = $db->prepare($sqli);
  //$stmt->bindParam(':event_Mid',$eventsres['event_id'], PDO::PARAM_STR);	 	 	 
  $stmt->execute(); 
  while ($eventsres = $stmt->fetch()) {
     // SELECT FIRST IMAGE
  $sqlimg= "SELECT `event_img`.*  FROM  event_img WHERE `event_img`.`event_Mid`=:event_Mid ORDER BY  `event_img`.`event_imgID` ASC";
  $stmtimg = $db->prepare($sqlimg);
  $stmtimg->bindParam(':event_Mid',$eventsres['event_id'], PDO::PARAM_STR);	 	 	 
  $stmtimg->execute(); 
  $imgres = $stmtimg->fetch(PDO::FETCH_ASSOC);

  //Convert Date
  $converteddate = date("M-Y", strtotime($eventsres['event_duration']));

      ?>
            <p>
            <div class="about-author d-flex p-4 bg-light">
              <div class="bio mr-5">
                <img src="uploads/events/<?php echo$imgres['img_name'];?>" alt="Geco event" class="img-fluid mb-4">
              </div>
              <div class="desc">
                <h3><?php echo$eventsres['event_title'];?></h3>
                <p> <h3>Client:<?php echo$eventsres['event_client'];?></h3>
            <h4>Date: <?php echo$converteddate;?> </h4>
            <h4>Duration: <?php echo$eventsres['event_date'];?> </h4>
          
          </p>
            <p class="mb-0"><a href="events.php?geco_event&&id=<?php echo$eventsres['event_id'];?>" class="btn btn-primary" title="Read more">.........Read More <span class="ion-ios-arrow-round-forward"></span></a></p>
              </div>
            </div> 
            </p>
<?php } ?>
            <!-----#END OF event-->
        
					  
         
           
            <div class="tag-widget post-tag-container mb-5 mt-5">
             
            </div>
            
           


           
              
</div>

</section>  
                
          <!--- OPEN Review Approval Notice WINDOWS AT CENTER OF SCREEN-->
          <script> 
    function createPopupWinRef(pageURL, pageTitle, 
    popupWinRefWidth, popupWinRefHeight) { 
    var left = (screen.width - popupWinRefWidth) / 2; 
    var top = (screen.height - popupWinRefHeight) / 4; 
    
    var myWindow = window.open(pageURL, pageTitle, 
    'resizable=yes, width=' + popupWinRefWidth 
    + ', height=' + popupWinRefHeight + ', top=' 
    + top + ', left=' + left); 
    } 
    </script>