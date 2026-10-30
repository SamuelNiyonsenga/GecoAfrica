<?php error_reporting(1);?>
  
  <!DOCTYPE html>
<html lang="en">
  <head>
  <title>GECO Africa Ltd || Contact Us</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Geco africa ltd official website, rwanda Consultancy company">
  <meta name="keywords" content="GECO,geco africa ltd, The office of geco africa ltd is located at Avenue des Poids Lourds in the Building of Source Oil Petrol Station,GECO Africa Ltd  is Consultancy Company that was first  in 2007 in the names of MUNYARUGERERO Jean Kizito, a Geotechnical Engineer, and was then registered in the names of GECO AFRICA LTD in 2009, as a result of a clarion call by the Government of Rwanda to Rwandans for them to rise above their challenges they faced and form consulting firms that performed above expectations and compete with the very best in Rwanda, and in Africa as a whole. 
              The company office  is located at Avenue des Poids Lourds in the Building of Source Oil Petrol Station.">
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
    <link rel="stylesheet" href="css/css.css">
    
    <link rel="shortcut icon" href="media/pic/icon.jpg">
    <style type="text/css">
#loadernotification {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  width: 100%;
  background: rgba(0,0,0,0.75) url(loading/loading_spinner_small.gif) no-repeat center center;
  z-index: 10000;
  text-align:center;
}
</style>
  </head>
  <body>
  
    <?php include("nav-top.php");?>
    <!-- END nav -->
    
    <section class="hero-wrap hero-wrap-2" style="background-image: url('media/pic/top.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
            <h1 class="mb-2 bread">Contact Us</h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="home.php">Home <i class="ion-ios-arrow-forward"></i></a></span> <span>Contact <i class="ion-ios-arrow-forward"></i></span></p>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section ftco-no-pt ftco-no-pb contact-section">
			<div class="container">
				<div class="row d-flex align-items-stretch no-gutters">
					<div class="col-md-6 p-4 p-md-5 order-md-last bg-light"><div id="sendquery"></div>
          <?php if(isset($_GET['error'])){
                $feedbackresult="<font color='red'><b>MESSAGE NOT SENT PLEASE TRY AGAIN<br>check your internet connection</font></b>";
              }
              if(isset($_GET['success'])){
                $feedbackresult="<font color='green'><b>MESSAGE WELL RECEIVED.THANKS!</font></b>";
              }
              
              ?><p><h3><u><b>Request Services Here:</b></u></h3></p>
              <p><?php echo$feedbackresult;?></p>
              <form  method="POST" name="request" id="request" class="request-form ftco-animate">
              <div class="form-group">
                <input type="text" class="form-control" id="sendemail" placeholder="Enter Your Name" name="names" required="required">
              </div>
              <div class="form-group">
                <input type="email" class="form-control" placeholder="Enter Your Email" name="email" required="required">
              </div>
              <div class="form-group">
                <input type="phone" class="form-control" placeholder="Enter Your phone" name="phone" required="required">
              </div>
              <div class="form-group">
                <textarea name="message" id="" cols="30" rows="7" class="form-control" placeholder="type your Message here" name="message" required="required"></textarea>
              </div>
              <div class="form-group">
                <input type="submit" value="Send Message" class="btn btn-primary py-3 px-5">
              </div>
            </form>
					</div>
       
				<script>
				focusMethod=function getFocus(){
					document.getElementById("sendemail").focus();

				}
				</script> 
					<div class="col-md-6 d-flex align-items-stretch">
          <!----MAP HERE BELLOW
						<div id="map"></div>
           -- END OF MAP---->
           <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d878.3757565389417!2d30.071269112158337!3d-1.943092727490683!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19dca74efc5b9d37%3A0xdc599965e0a07fe5!2sGECO%20Africa%20LTD!5e1!3m2!1sen!2srw!4v1571048510158!5m2!1sen!2srw" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen=""></iframe>	</div>
			</div>
		</section>
		
		<section class="ftco-section contact-section">
      <div class="container">
        <div class="row d-flex mb-5 contact-info">
          <div class="col-md-12 mb-4">
            <h2 class="h4">Contact Information</h2>
          </div>
          <div class="w-100"></div>
          <div class="col-md-3 d-flex">
          	<div class="bg-light d-flex align-self-stretch box p-4">
	            <p><span>Address:</span>P.O Box 3711 Kigali</p>
	          </div>
          </div>
          <div class="col-md-3 d-flex">
          	<div class="bg-light d-flex align-self-stretch box p-4">
	            <p><span>Phone:</span> <a href="tel://1234567920">+ 250 788 842 990 / 788 572 346</a></p>
	          </div>
          </div>
          <div class="col-md-3 d-flex">
          	<div class="bg-light d-flex align-self-stretch box p-4">
	            <p><span>Email:</span> <a href="mailto:info@yoursite.com">info@gecoafrica.com</a></p>
	          </div>
          </div>
          <div class="col-md-3 d-flex">
          	<div class="bg-light d-flex align-self-stretch box p-4">
	            <p><span>Website:</span> <a href="#">www.gecoafrica.com</a></p>
	          </div>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section ftco-no-pt ftco-no-pb contact-section">
    <div class="container">
    <div class="col-md-12 mb-4">
            <h2 class="h4">Office</h2>
          </div>

<p> Our office is located at Avenue des Poids Lourds in the Building of Source Oil Petrol Station, zoom map above to locate it.</p>

</div>
      </div>
    </section>

   <?php include("footer.php")?>
  
   <div id="loadernotification">Please wait.......</div>
   <script src="http://code.jquery.com/jquery.js"></script>
 

 <script>
 $(document).ready(function() {
	 $('#request').submit(function(e){ 
	e.preventDefault();
  $('#loadernotification').show();
	$.ajax({
      url: 'sendqueryc.php?send=yes',
      data: $(this).serialize(),
      method: 'post',
      dataType: 'JSON',
	  cache:false
    }).done(function(resp) {
		//if(resp.Rerror=='ok'){
			window.location.href=(resp.requestlink);
	  // alert(resp.request);
     //$('#loadernotification').hide();	
	//	}
	 
	
    });
	 
	});
	 
	 
 });
 
 </script>

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