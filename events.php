<!DOCTYPE html>
<html lang="en">
  <head>
    <title>GECO Africa Ltd || Activities</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!--SEO-->
    <meta name="description" content="Geco africa ltd official website,our services, rwanda Consultancy company located at Avenue des Poids Lourds in the Building of Source Oil Petrol Station,GECO Africa Ltd  is Consultancy Company that was first  in 2007 in the names of MUNYARUGERERO Jean Kizito, a Geotechnical Engineer, and was then registered in the names of GECO AFRICA LTD in 2009, as a result of a clarion call by the Government of Rwanda to Rwandans for them to rise above their challenges they faced and form consulting firms that performed above expectations and compete with the very best in Rwanda, and in Africa as a whole">
  <meta name="keywords" content="GECO,geco africa ltd, The office of geco africa ltd is located at Avenue des Poids Lourds in the Building of Source Oil Petrol Station,GECO Africa Ltd  is Consultancy Company that was first  in 2007 in the names of MUNYARUGERERO Jean Kizito, a Geotechnical Engineer, and was then registered in the names of GECO AFRICA LTD in 2009, as a result of a clarion call by the Government of Rwanda to Rwandans for them to rise above their challenges they faced and form consulting firms that performed above expectations and compete with the very best in Rwanda, and in Africa as a whole. 
              The company office  is located at Avenue des Poids Lourds in the Building of Source Oil Petrol Station.">
			  <link rel="stylesheet" href="css/css.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:200,300,400,600,700,800,900" rel="stylesheet">

    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/ionicons.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">

    
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="media/pic/icon.jpg">

    <!--- AJAX Script----->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/jquery.js"></script> 
        
        <!----- End of Ajax--->

        <style type="text/css">
#loaderlogin{
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  width: 100%;
  background: rgba(0,0,0,0.17) url(loading/loading_spinner_small.gif) no-repeat center center;
  z-index: 10000;
}
</style>
  </head>
  <body>
     
	  <?php include("nav-top.php");?>
    <!-- END nav -->
    
    <section class="hero-wrap hero-wrap-2" style="background-image: url('media/top.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
            <h1 class="mb-2 bread">Our Activities</h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="home.php">Home <i class="ion-ios-arrow-forward"></i></a></span> <span>Our activities <i class="ion-ios-arrow-forward"></i></span></p>
          </div>
        </div>
      </div>
    </section>

  <?php 
  ini_set('max_execution_time', '12000');
  error_reporting(1); 
  session_start();
  require_once('Login-Portal/includes/connect.php');
  
  if(empty($_GET['id'])){
  include("allevents.php");
  }
  else{
    include("event_details.php");    
  }
  ?>
    
  



	
    <!--- OPEN Review Approval Notice WINDOWS AT CENTER OF SCREEN-->
    <script> 
			  function createPopupWinNotice(pageURL, pageTitle, 
			  popupWinNoticeWidth, popupWinNoticeHeight) { 
			  var left = (screen.width - popupWinNoticeWidth) / 2; 
			  var top = (screen.height - popupWinNoticeHeight) / 4; 
			  
			  var myWindow = window.open(pageURL, pageTitle, 
			  'resizable=yes, width=' + popupWinNoticeWidth 
			  + ', height=' + popupWinNoticeHeight + ', top=' 
			  + top + ', left=' + left); 
			  } 
			  </script>
	
		
	<?php include("design.php");?>
		

<?php include("footer.php");?>
  

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>


  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/jquery.timepicker.min.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>
    
  </body>
</html>