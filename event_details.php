
<?php   $sql= "SELECT `tb_events`.*  FROM  tb_events WHERE `tb_events`.`event_id`=:event_id";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':event_id',$_GET['id'], PDO::PARAM_STR);	 	 	 
  $stmt->execute(); 
  $eventsres = $stmt->fetch(PDO::FETCH_ASSOC);
  
  // SELECT FIRST IMAGE
  $sqlimg= "SELECT `event_img`.*  FROM  event_img WHERE `event_img`.`event_Mid`=:event_Mid ORDER BY  `event_img`.`event_imgID` ASC";
  $stmtimg = $db->prepare($sqlimg);
  $stmtimg->bindParam(':event_Mid',$eventsres['event_id'], PDO::PARAM_STR);	 	 	 
  $stmtimg->execute(); 
  $imgres = $stmtimg->fetch(PDO::FETCH_ASSOC);
  //Convert Date
  $converteddate = date("M-Y", strtotime($eventsres['event_duration']));
  ?>
     <section class="ftco-section">
			<div class="container">
				<div class="row">
          <div class="col-lg-8 ftco-animate">
            <h2 class="mb-3 text-info"><?php echo$eventsres['event_title'];?></h2>
            <p>Event description.</p>
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
            <p>   <?php echo$eventsres['event_contents'];?></p>
              </div>
            </div> 
            </p>
             
 

<h4>IMAGES </h4>
            <p>
         <?php    // SELECT FIRST IMAGE
  $sqlimgfull= "SELECT `event_img`.*  FROM  event_img WHERE `event_img`.`event_Mid`=:event_Mid";
  $stmtimgfull = $db->prepare($sqlimgfull);
  $stmtimgfull->bindParam(':event_Mid',$eventsres['event_id'], PDO::PARAM_STR);	 	 	 
  $stmtimgfull->execute(); 
  while ($row = $stmtimgfull->fetch()) {  ?>
  <!---Event Images--->
                <div class="col-md-12 col-lg-12 ftco-animate">
    				<div class="project">
	    				<img src="uploads/events/<?php echo$row['img_name'];?>" class="img-fluid"   alt="Geco Africa">
	    				<div class="text">
	    					<span><?php echo$eventsres['event_title'];?></span>
	    					<h3><a href="#"><?php echo$eventsres['event_client'];?></a></h3>
	    				</div>
	    				<a href="uploads/events/<?php echo$row['img_name'];?>" class="icon image-popup d-flex justify-content-center align-items-center">
	    					<span class="icon-expand"></span>
	    				</a>
    				</div>
    			</div> <br>
<?php  } ?>
                <!----#END Event images--->
            </p>
   <?php if(!empty($_SESSION['userID'])){  ?>        
 <p class="mb-0"><a href="#"onclick = "createPopupWinNotice('ProfileDropzone?user=event&&eventID=<?php echo$eventsres['event_id'];?>&&event','Upload Images', 650, 650);"  class="btn btn-primary" title="Upload Images">Add pictures <span class="ion-ios-arrow-round-forward"></span></a></p><br><br>
  <?php } if($eventsres['event_status']!="published") { ?>
 <p class="mb-0"><a href="#"onclick = "createPopupWinNotice('ProfileDropzone/publish.php?user=event&&eventID=<?php echo$eventsres['event_id'];?>&&event','Publishing Event', 650, 650);"  class="btn btn-primary" title="Upload Images">PUBLISH <span class="ion-ios-arrow-round-forward"></span></a></p>
 <?php } ?>
					  
         
           
            <div class="tag-widget post-tag-container mb-5 mt-5">
             
            </div>
            
           


           
              
</div>

</section>   