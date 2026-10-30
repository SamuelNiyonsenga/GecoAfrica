<!---- Login Model-->
<div id="PrimaryModalhdbgcl" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header header-color-modal bg-color-1">
                                <h4 class="modal-title"><font color='black'>LOGIN</font></h4>
                                <div class="modal-close-area modal-close-df">
                                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                                </div>
                            </div>
                            <div class="modal-body">
                                
                                <?php
                            if(!empty($errors)){
                                echo "<div class='alert alert-danger'>";
                                foreach ($errors as $error) {
                                    echo "<span class='glyphicon glyphicon-remove'></span>&nbsp;".$error."<br>";
                                }
                                echo "</div>";
                            }
                        ?>
                        
                        <div class='alert alert-danger' id="login_error" style="display: none;">
                                 
                                    <span class='glyphicon glyphicon-remove'></span>&nbsp; wrong login credentials
                                
                                 </div> 

                                     
                        <div class='alert alert-success' id="login_succcess" style="display: none;">
                                 
                                 <span class='glyphicon glyphicon-remove'></span>&nbsp;Success logged in, wait while redirected.......
                             
                              </div> 
                                <form role="form" method="post" id="qalogin" name="qalogin">
                                    <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                                    <fieldset>
                                        <div class="form-group">
                                            <input class="form-control" placeholder="E-mail" name="email" type="email" required/>
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Password" name="password" type="password" value="" required/>
                                        </div>
                                        <!-- Change this to a button or input when using this as a form -->
                                        <input type="submit" class="btn btn-lg btn-success btn-block" id="submit" value="Login" />
                                        <input type="submit" disabled="disable" class="btn btn-lg btn-success btn-block" id="submit2" style="display: none;" value="Login....." />
                                    </fieldset>
                                </form>

                            </div>
                            <div class="modal-footer">
                           
                              
                            <button class="btn btn-lg btn-danger" id="forgetpassBtn"> Forget Password</button>
                                <a data-dismiss="modal" href="#">Cancel</a>
                               
                            </div>
                        </div>
                    </div>
                </div>
                <!--- end of login model-->

                  <!----Forget Password Model-->
                  <div id="forgetpassModal" class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header header-color-modal bg-color-1">
                                <h4 class="modal-title"> <font color='black'> Recover Passsword</font></h4>
                                <div class="modal-close-area modal-close-df">
                                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                                </div>
                            </div>
                            <div class="modal-body">
                         <font color='black'>   Enter your email here  bellow</font>
                            <div class="statusMsg"></div>
                                <form role="form" method="POST" id="forgetpassfrm" name="forgetpassfrm">
                                    <input type="hidden" name="hub_csrf_token" value="<?php echo $token; ?>">
                                    <input type="hidden" name="hub_csrf_token_time" value="<?php echo $_SESSION['csrf_token_time']; ?>">
                                    <fieldset>
                                        <div class="form-group">
                                            <input class="form-control" placeholder="E-mail" name="email" type="email" required/>
                                        </div>
                                        
                                        <!-- Change this to a button or input when using this as a form -->
                                        <input type="submit" class="btn btn-lg btn-success btn-block" id="fgtbtn" value="Send" />
                                       </fieldset>
                                </form>

                            </div>
                            <div class="modal-footer">
                           
                              
                            
                                <a data-dismiss="modal" href="#">Cancel</a>
                               
                            </div>
                        </div>
                    </div>
                </div>
                <!--- forget password model-->


 <footer class="ftco-footer ftco-bg-dark ftco-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md">
          <img src="media/pic/logo.jpg">
            <div class="ftco-footer-widget mb-5">
             
              <a class="blog-img mr-4" ></a>
              <p>Designing and building for ever.</p>
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-3">
                <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
                <li class="ftco-animate"><a href="#" data-toggle="modal" data-target="#PrimaryModalhdbgcl"><span class="icon-sign">  <img src="img/login.gif"></span></a></li>
                     </ul>
            </div>
            
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-5 ml-md-4">
              <h2 class="ftco-heading-2">Services</h2>
              <ul class="list-unstyled">
                <li><a href="services.php?geco_services"><span class="ion-ios-arrow-round-forward mr-4"></span>1.	Geotechnical Engineering</a></li>
                <li><a href="services.php?geco_services"><span class="ion-ios-arrow-round-forward mr-4"></span>2.	Road design and supervision</a></li>
                <li><a href="services.php?geco_services"><span class="ion-ios-arrow-round-forward mr-4"></span>3.	Building design and supervision</a></li>
                <li><a href="services.php?geco_services"><span class="ion-ios-arrow-round-forward mr-4"></span>4. Construction of Civil Engineering projects</a></li>

                 
              </ul>
            </div>
          </div>
          <div class="col-md-5">
            <div class="ftco-footer-widget mb-5">
              <h2 class="ftco-heading-2">Our Location</h2>
              <div class="block-21 mb-4 d-flex">
                <a class="blog-img mr-4" style="background-image: url(media/officeone.jpg);"></a>
                <div class="text">
                  <h3 class="heading"><a href="#">The office is located at Avenue des Poids Lourds in the Building of Source Oil Petrol Station</a></h3>
                 
                </div>
              </div>



              
              <div class="block-21 mb-5 d-flex">
                <a class="blog-img mr-4" style="background-image: url(media/pic/geco_qr.png);"></a>
                <div class="text">
                  <h3 class="heading"><a href="#">Scan our code</a></h3>
                  
                </div>
              </div>
            </div>
          </div>
         
        </div>
        <div class="row">
          <div class="col-md-12 text-center">
          <div id="loaderlogin">Please wait.......</div>
            <p class"lead"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
  Copyright &copy;<script>document.write(new Date().getFullYear());</script> GECO Africa Ltd All rights reserved ||&nbsp;<b><a href="https://gecoafrica.com/webmail" target="blank">webmail</b></a> &nbsp; || Developed by TNT COMPUTER CONSULTACY LTD</a>
  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
          </div>
        </div>
      </div>
    </footer>


    <script>
 $(document).ready(function() {
	 $('#qalogin').submit(function(e){ 
	e.preventDefault();
 $('#loaderlogin').show();
 $('#submit').hide();
            $('#submit2').show();
	$.ajax({
      url: 'Ajaxqa_login.php',
      data: $(this).serialize(),
      method: 'post',
      dataType: 'JSON'
    }).done(function(resp) {
		if(resp.result=='Success'){
           $(this).find('button[type=submit]').prop('disabled',true);
            $('#loaderlogin').hide();
            $('#login_error').hide();
            $('#submit').hide();
            $('#submit2').show();
            $('#login_succcess').show();
window.setTimeout(function(){
window.location.href="events.php?geco_event";
},2000);

		}
		else{
      $(this).find('button[type=submit]').prop('enabled',true);
	  $('#loaderlogin').hide();
      $('#login_error').show();
      $('#submit2').hide();
      $('#submit').show();
		}
       
	
    });
	 
    });

    // Forgot Password 

    $('#forgetpassfrm').submit(function(e){ 
	e.preventDefault();
 $('#loaderlogin').show();
 $('#fgtBtn').text("saving.....");
 $('#fgtBtn').attr("disabled","disabled");
  $('#forgetpassfrm').css("opacity",".5");
	$.ajax({
      url: 'Login-Portal/Ajaxadmin_forgotpass.php',
      data: $(this).serialize(),
      method: 'post',
      dataType: 'JSON'
    }).done(function(resp) {
		if(resp.result=='Success'){
            $('#loaderlogin').hide();
            $("#fgtBtn").hide("");
            $('.statusMsg').html('<p class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><span class="glyphicon glyphicon-ok"></span>&nbsp;'+resp.statusb+'</p>');
        }
		else{
      $('#loaderlogin').hide();
      $('.statusMsg').html('<p class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <span class="glyphicon glyphicon-remove"></span>&nbsp;'+resp.statusb+'</p>');
  

                          $("#forgetpassfrm").removeAttr("disabled");
                          $('#fgtBtn').text("save");
		}
        $('#forgetpassfrm').css("opacity","");
        $("#fgtBtn").removeAttr("disabled");
	
    });
	 
	});
    
    $('#tender').on('click',function(e){ 
	e.preventDefault();
    window.setTimeout(function(){
window.location.href="../tender?published";
});
});
$('#forgetpassBtn').on('click',function(e){ 
	e.preventDefault();
  $('#forgetpassModal').modal('show');
  $('#PrimaryModalhdbgcl').modal('hide');
	 
});
 });
 
 </script>
