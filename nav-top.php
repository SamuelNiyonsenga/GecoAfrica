 

 
 
 <div class="bg-top navbar-light">
    	<div class="container">
    		<div class="row no-gutters d-flex align-items-center align-items-stretch">
		
    			<div class="col-md-4 d-flex align-items-center py-4">
				<img src="media/pic/logo_top.png" style="height:100px;width:100px;">
    				<!--<a class="navbar-brand" href="#"><img src="media/pic/logo.jpg"></a>-->
    			</div>
	    		<div class="col-lg-8 d-block">
		    		<div class="row d-flex">
					    <div class="col-md d-flex topper align-items-center align-items-stretch py-md-4">
					    	<div class="icon d-flex justify-content-center align-items-center"><span class="icon-paper-plane"></span></div>
					    	<div class="text d-flex align-items-center">
						    	<span>info@gecoafrica.com</span>
						    </div>
					    </div>
					    <div class="col-md d-flex topper align-items-center align-items-stretch py-md-4">
					    	<div class="icon d-flex justify-content-center align-items-center"><span class="icon-phone2"></span></div>
						    <div class="text d-flex align-items-center">
						    	<span>Call Us: + 250 788 842 990</span>
						    </div>
					    </div>

						<div class="col-md topper d-flex align-items-center align-items-stretch">
					    	<p class="mb-0 d-flex d-block">
					    		<a href="contact.php?geco_contact#sendquery"  class="btn btn-primary d-flex align-items-center justify-content-center">
					    			<span>Request Services</span>
					    		</a>
					    	</p>
					    </div>
					    


				    </div>
			    </div>
		    </div>
		  </div>
    </div>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container d-flex align-items-center">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="oi oi-menu"></span> Menu
	      </button>
	      <form  method="POST" action="services.php?geco_services" class="searchform order-lg-last">
          <div class="form-group d-flex">
            <input type="text" class="form-control pl-2" name="search" placeholder="Search">
            <button type="submit" placeholder="" class="form-control search"><span class="ion-ios-search"></span></button>
          </div>
        </form>
		<?php 
 if(isset($_GET["geco_home"])){
$home_class="class='nav-item active'";
 }
 elseif(!isset($_GET["geco_home"]) && !isset($_GET["geco_about"]) && !isset($_GET["geco_projects"]) && !isset($_GET["geco_contact"]) && !isset($_GET["geco_services"]) && !isset($_GET["geco_team"]) && !isset($_GET["geco_gallery"]) && !isset($_GET["geco_Partners"])&& !isset($_GET["geco_event"]) ){
	$home_class="class='nav-item active'";
	 }
 else{

	$home_class="class='nav-item'";
 }
 if(isset($_GET["geco_about"])){
	$about_class="class='nav-item active'";
	 }else{
	
		$about_class="class='nav-item'";
	 }
	 if(isset($_GET["geco_projects"])){
		$project_class="class='nav-item active'";
		 }else{
		
			$project_class="class='nav-item'";
		 }
		 if(isset($_GET["geco_contact"])){
			$contact_class="class='nav-item active'";
			 }else{
			
				$contact_class="class='nav-item'";
			 }

			 if(isset($_GET["geco_services"])){
				$services_class="class='nav-item active'";
				 }else{
				
					$services_class="class='nav-item'";
				 }
				 if(isset($_GET["geco_team"])){
					$team_class="class='nav-item active'";
					 }else{
					
						$team_class="class='nav-item'";
					 }
					 if(isset($_GET["geco_gallery"])){
						$gallery_class="class='nav-item active'";
						 }else{
						
							$gallery_class="class='nav-item'";
						 }
						 if(isset($_GET["geco_Partners"])){
							$Partners_class="class='nav-item active'";
							 }else{
							
								$Partners_class="class='nav-item'";
							 } 
							 if(isset($_GET["geco_event"])){
								$event_class="class='nav-item active'";
							 }else{
							
								$event_class="class='nav-item'";
							 }
 
 ?>

	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav mr-auto">
	        	<li <?php echo$home_class;?> ><a href="home.php?geco_home" class="nav-link pl-0">Home</a></li>
						<li <?php echo$about_class;?>><a href="about.php?geco_about" class="nav-link">About Us</a></li>
						<li <?php echo$services_class;?>><a href="services.php?geco_services" class="nav-link">services</a></li>
	        	<li <?php echo$project_class;?>><a href="project.php?geco_projects" class="nav-link">Projects</a></li>
				<li <?php echo$team_class;?>><a href="team1.php?geco_team" class="nav-link">Team</a></li>
				<li <?php echo$Partners_class;?>><a href="Our_Partners.php?geco_Partners" class="nav-link">Partners</a></li>

				<!--<li <?php echo$team_class;?>><a href="#" class="nav-link">Construction</a></li>-->
				<li <?php echo$gallery_class;?>><a href="gallery.php?geco_gallery" class="nav-link">Gallery</a></li>
						<li <?php echo$contact_class;?>><a href="contact.php?geco_contact" class="nav-link">Contact Us</a></li>
						<li <?php echo$event_class;?>><a href="events.php?geco_event" class="nav-link">Our Activities</a></li>
						
						
	        </ul>
	      </div>
	    </div>
	  </nav>

	 