
<?php  error_reporting(1);
require_once('../Login-Portal/includes/connect.php');
session_start();
date_default_timezone_set('Africa/Cairo');
$t=date("Y-m-d  H:i:s");
$time=date("Y-m-d H:i:s");
// Create To day date
$fulltime=date("Y-m-d H:i:s");$dateonly=date("Y-m-d");$timeonly=date("H:i:s");
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Add new Activities</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- favicon
		============================================ -->
    <link rel="shortcut icon" href="../media/pic/icon.jpg">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i,800" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- adminpro icon CSS
		============================================ -->
    <link rel="stylesheet" href="css/adminpro-custon-icon.css">
    <!-- meanmenu icon CSS
		============================================ -->
    <link rel="stylesheet" href="css/meanmenu.min.css">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="css/data-table/bootstrap-table.css">
    <link rel="stylesheet" href="css/data-table/bootstrap-editable.css">
    <!-- summernote CSS
		============================================ -->
    <link rel="stylesheet" href="css/summernote.css">
    
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="css/normalize.css">
    <!-- dropzone CSS
		============================================ -->
    <link rel="stylesheet" href="css/dropzone.css">
      <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="css/form.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- modernizr JS
		============================================ -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
<!-- notifications CSS
		============================================ -->
        <link rel="stylesheet" href="css/Lobibox.min.css">
    <link rel="stylesheet" href="css/notifications.css">

     <!-- modals CSS
		============================================ -->
    <link rel="stylesheet" href="css/modals.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="css/normalize.css">
    <!-- touchspin CSS
		============================================ -->
    <link rel="stylesheet" href="css/touchspin/jquery.bootstrap-touchspin.min.css">
    <!-- datapicker CSS
		============================================ -->
    <link rel="stylesheet" href="css/datapicker/datepicker3.css">
    <!-- forms CSS
		============================================ -->
    <link rel="stylesheet" href="css/form/themesaller-forms.css">
    <!-- colorpicker CSS
		============================================ -->
    <link rel="stylesheet" href="css/colorpicker/colorpicker.css">
    <!-- select2 CSS
		============================================ -->
    <link rel="stylesheet" href="css/select2/select2.min.css">
    <!-- chosen CSS
		============================================ -->
    <link rel="stylesheet" href="css/chosen/bootstrap-chosen.css">
    <!-- ionRangeSlider CSS
		============================================ -->
    <link rel="stylesheet" href="css/ionRangeSlider/ion.rangeSlider.css">
    <link rel="stylesheet" href="css/ionRangeSlider/ion.rangeSlider.skinFlat.css">

    <!-- colorpicker JS
		============================================ -->
    <script src="js/colorpicker/jquery.spectrum.min.js"></script>
    <script src="js/colorpicker/color-picker-active.js"></script>
    <!-- datapicker JS
		============================================ -->
    <script src="js/datapicker/bootstrap-datepicker.js"></script>
    <script src="js/datapicker/datepicker-active.js"></script>
    <!-- input-mask JS
		============================================ -->
    <script src="js/input-mask/jasny-bootstrap.min.js"></script>
    <!-- chosen JS
		============================================ -->
    <script src="js/chosen/chosen.jquery.js"></script>
    <script src="js/chosen/chosen-active.js"></script>
    <!-- select2 JS
		============================================ -->
    <script src="js/select2/select2.full.min.js"></script>
    <script src="js/select2/select2-active.js"></script>
    <!-- ionRangeSlider JS
		============================================ -->
    <script src="js/ionRangeSlider/ion.rangeSlider.min.js"></script>
    <script src="js/ionRangeSlider/ion.rangeSlider.active.js"></script>

    <!--- imported vendors--->
        <!-- bootstrap-daterangepicker -->
        <link href="import/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- bootstrap-datetimepicker -->
    <link href="import/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">

    <!--- END OF INPORTED VENDOR-->
<!--- AJAX Script----->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="http://code.jquery.com/jquery.js"></script> 
        
        <!----- End of Ajax--->
        <style type="text/css">
#loaderapplicantreg {
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
<script>function close_window() {

  close();
}</script> 
</head>

<body class="materialdesign">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    
            <!-- Breadcome End-->
            <div class="inbox-mailbox-area mg-b-40">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                
                                <div class="col-lg-9">
                                    <div class="tab-content">
                                        <?php
                                        if($_GET['cs']=="receive"){
                                          // select Your communication
                                    
                                        $emptyvalue="";$scr_viewstatus="read";
                                        $tdaysql = "UPDATE tb_communication_receiver SET tb_scr_receivedtime=:tb_scr_receivedtime,scr_viewstatus=:scr_viewstatus WHERE 
                                        tb_scr_scID=:sc_ID AND 	tb_scr_receiver=:tb_scr_receiver   AND tb_scr_receivedtime=:emptyvalue";
                                        $tdayresult = $db->prepare($tdaysql);
                                        $tdayresult->bindParam(':tb_scr_receivedtime',$time, PDO::PARAM_STR);  
                                        $tdayresult->bindParam(':scr_viewstatus',$scr_viewstatus, PDO::PARAM_STR);  
                                        $tdayresult->bindParam(':sc_ID', $_GET['sc_ID'], PDO::PARAM_STR);  
                                        $tdayresult->bindParam(':tb_scr_receiver',$_SESSION['memberID'], PDO::PARAM_STR);                                      
                                        $tdayresult->bindParam(':emptyvalue', $emptyvalue, PDO::PARAM_STR);  
                                        $tdayresult->execute();
                                          }else{

                                          }




                                         if($_GET['cs']=="sent" or $_GET['cs']=="receive"){
                                           $viewactive="active";
                                           $Composeactive="";
                                         }
                                         else{
                                            $Composeactive="active";
                                            $viewactive="";
                                         }   
                                    
                                        ?>
                                            
                                        <div id="viewmail" class="tab-pane fade in animated zoomInDown shadow-reset custom-inbox-message <?php echo$viewactive;?>">
                                            <div class="view-mail-wrap">
                                                <div class="mail-title">
                                                    <h2>View Message</h2>
                                                    <div class="view-mail-action">
                                                        
                                                        <a class="compose-draft-bt" href="javascript:window.print()"><i class="fa fa-print"></i> Print</a>
                                                        <a class="compose-discard-bt" href="javascript:window.close()"><i class="fa fa-times"></i> Close</a>
                                                    </div>
                                                </div>
                                              <?php  
                                              $tdaysql = "SELECT * FROM tb_communication WHERE  sc_ID=:sc_ID";
              $tdayresult = $db->prepare($tdaysql);
              $tdayresult->bindParam(':sc_ID', $_GET['sc_ID'], PDO::PARAM_STR);  
              $tdayresult->execute();
              $reportcount = $tdayresult->rowCount();
              $communicationres = $tdayresult->fetch(PDO::FETCH_ASSOC);            
                $sentdate = date("H:i:s , d M Y", strtotime($communicationres['sc_dateTime'])); 
              
              $tdaysql = "SELECT * FROM tb_users WHERE user_id=:user_id";
              $tdayresult = $db->prepare($tdaysql);
              $tdayresult->bindParam(':user_id',$communicationres['sc_by'], PDO::PARAM_STR);  
              $tdayresult->execute();
              $reportcount = $tdayresult->rowCount();
              $sender_result = $tdayresult->fetch(PDO::FETCH_ASSOC);  
              $senderFullname="$sender_result[user_firstname]  $sender_result[user_lastname]";           
             
                   ?>
                                                <div class="main-title-hd">
                                                    <h3>Subject: <span class="main-title-view"><?php echo$communicationres['sc_subject'];?></span></h3>
                                                </div>
                                                <div class="view-author-mail">
                                                    <span>From: <span class="view-mail-email"><?php echo$senderFullname;?></span></span>
                                                    <span class="view-mail-date"><?php echo$sentdate;?></span>
                                                </div>
                                                
                                                <div class="view-mail-content">
                                                    <p><?php echo$communicationres['sc_text'];?></p> 
                                            </div>
                                              
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="view-mail-reply-list">
                                                            <ul class="view-mail-forword">
                                                                
                                                                <li><a class="compose-draft-bt" href="javascript:window.print()"><i class="fa fa-print"></i> Print</a>
                                                                </li>
                                                                <li><a class="compose-discard-bt" href="javascript:window.close()"><i class="fa fa-times"></i> Close</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>


                                                    

                                                         <!--- Include recepient  for sent invitation-->
                                              <?php if($_GET['cs']=="sent"){
                                                  include("recepient.php");
                                              }
                                              ?>  
                                           <!--- end of recepients--->
                                                </div>
                                            </div>
                                        </div>
                                        <div id="composemail" class="tab-pane fade in animated  custom-inbox-message <?php echo $Composeactive;?>">
                                            <div class="view-mail-wrap">
                                                <div class="mail-title">
                                                    <h2>Add new Activities</h2> <form method="post" id="send_communication" enctype="multipart/form-data">
                                                    <div class="view-mail-action">                                                    
                                                        <a class="compose-discard-bt" href="javascript:close_window();"><i class="fa fa-times"></i> Discard</a>
                                                    </div>
                                                </div>
                                                 

                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="row">
                                                            <div class="col-lg-2">
                                                                <div class="compose-email-to compose-subject-title">
                                                                    <span>Activity Title:</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-10">
                                                                <div class="compose-multiple-email compose-subject-email">
                                                                    <input type="text" name="event_title" id="event_title" required/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="row">
                                                            <div class="col-lg-2">
                                                                <div class="compose-email-to compose-subject-title">
                                                                    <span>Client :</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-10">
                                                                <div class="compose-multiple-email compose-subject-email">
                                                                    <input type="text" name="event_client" id="event_client" required/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="row">
                                                            <div class="col-lg-2">
                                                                <div class="compose-email-to compose-subject-title">
                                                                <span>Duration [any format[text or..] eg: May to June 2021] :</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-10">
                                                                <div class="compose-multiple-email compose-subject-email">
                                                                <input type="text" name="event_date" id="event_date" Placeholder="Eg: May to June 2021" required/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> </div> <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="row">
                                                            <div class="col-lg-2">
                                                                <div class="compose-email-to compose-subject-title">
                                                                    <span>Date[YYY-MM-DD](<i><b>Please click on calender icon</b></i>):</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-10">
                                                                <div class="compose-multiple-email compose-subject-email">
                                                                    <input type="date" name="event_duration" class="form-control"id="event_duration" required/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">  
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                    
                                                        <div class="text-editor-compose">
                                                       <b> Type other  Content here like:Location,....</b>
                                                             <div id="textedutor">
                                                                
                                                                <textarea  class="note-editable panel-body" contenteditable="true" style="height: 10px;width:10px" id="summernote2" name="event_contents" required/></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="view-mail-reply-list">
                                                        <ul class="view-mail-forword"> 
                                                            
                                                            <li><button type="submit" name="send" id="send"  class="btn btn-primary"><i class="fa fa-save"></i> save</button>
                                                            </li>
                                                             
                                                            <li> <a class="compose-discard-bt" href="javascript:close_window();"><i class="fa fa-times"></i> Discard</a>
                                                            </li>
                                                            
                                                            </form>
                                                        </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Start-->
    <div class="footer-copyright-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="footer-copy-right">
                        <p>Copyright &#169; <?php echo date("Y");?> GECOAFRICA LTD All rights reserved. Designed by TNT Computer Consultancy Ltd.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End-->
    <!-- Chat Box Start-->
     
     <!-- add new project -->
 <!--- OPEN FIRM SCREEN-->
 <script> 
function createPopupWinNewdata(pageURL, pageTitle, 
popupWinNewdataWidth, popupWinNewdataHeight) { 
var left = (screen.width - popupWinNewdataWidth) / 2; 
var top = (screen.height - popupWinNewdataHeight) / 4; 

var myWindow = window.open(pageURL, pageTitle, 
'resizable=yes, width=' + popupWinNewdataWidth 
+ ', height=' + popupWinNewdataHeight + ', top=' 
+ top + ', left=' + left); 
} 
function checkTime(i) {
  if (i < 10) {
    i = "0" + i;
  }
  return i;
}

function startTime() {
  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  // add a zero in front of numbers<10
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('time').innerHTML = h + ":" + m + ":" + s;
  t = setTimeout(function() {
    startTime()
  }, 500);
}
startTime();
</script>
        <!-- add new project -->
        <script type="text/javascript">
$(document).ready(function(){
            $('#staffreceiver').hide();    
            $('#adminreceiver').hide(); 
            $('#ownerreceiver').hide(); 
    $('#receiver_cat').on('change',function(){
        var receiver_cat= $(this).val();
        $('#loadercomments').show();
        if(receiver_cat){
         if(receiver_cat=="staff"){
            $('#loadercomments').hide();
            $('#staffreceiver').show();   
            $('#receiver').hide();   
            $('#adminreceiver').hide();
            $('#ownerreceiver').hide();      
         }
         else if(receiver_cat=="owner"){
            $('#loadercomments').hide();
            $('#staffreceiver').hide();   
            $('#receiver').hide();   
            $('#adminreceiver').hide(); 
            $('#ownerreceiver').show(); 
         }
         else{
            $('#loadercomments').hide();
            $('#staffreceiver').hide();   
            $('#receiver').hide();   
            $('#adminreceiver').show(); 
            $('#ownerreceiver').hide(); 
         }

        }else{
			$('#loadercomments').hide();
            $('#staffreceiver').hide();   
            $('#receiver').show();   
            $('#adminreceiver').hide(); 
            $('#ownerreceiver').hide(); 
									
            
        }
    });  
    
});
</script> 
    
     <!-- Send invitation AJAX code- --->
<script type="text/javascript">
$(document).ready(function() {
var spinner = $('#loadercomments');
$('#send_communication').submit(function(e) {
    e.preventDefault();
    $('#loadercomments').show();
    $('#send').attr("disabled","disabled");
$('#send_communication').css("opacity",".5");
    $.ajax({
       url: '../Ajaxaddnewevent.php',
       data: $(this).serialize(),
       method: 'post',
      dataType: 'json',
      async:true,
      success:function(resp) {
    if(resp.result=='Success'){
  $('#loadercomments').hide();
  Lobibox.notify('success', {                    
                    title: (resp.result),
                    msg: (resp.statusb)
                });
                
                  window.setTimeout(function(){
                            createPopupWinNewdata(resp.link,'take pic', 800, 700);
                },2000);

      }

      else{
	  $('#loadercomments').hide();
     Lobibox.notify('error', {
                    delay: false,
                    title: (resp.result),
                    msg: (resp.statusb)
                });
window.setTimeout(function(){
},5000);
	
        }
        $('#send_communication').css("opacity","");
        $("#send").removeAttr("disabled");
	}
    }) 
  });
});
  </script>
<style type="text/css">
#loadercomments{
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
<div id="loaderapplicantreg">Please wait.......</div>
    <div id="loadercomments"> <center><font color="white">Please wait while Proccessing................</font></center></div>
    <!-- Chat Box End-->
    <!-- jquery
		============================================ -->
    <script src="js/vendor/jquery-1.11.3.min.js"></script>
    <!-- bootstrap JS
		============================================ -->
    <script src="js/bootstrap.min.js"></script>
    <!-- meanmenu JS
		============================================ -->
    <script src="js/jquery.meanmenu.js"></script>
    <!-- mCustomScrollbar JS
		============================================ -->
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <!-- sticky JS
		============================================ -->
    <script src="js/jquery.sticky.js"></script>
    <!-- scrollUp JS
		============================================ -->
    <script src="js/jquery.scrollUp.min.js"></script>
    <!-- counterup JS
		============================================ -->
    <script src="js/counterup/jquery.counterup.min.js"></script>
    <script src="js/counterup/waypoints.min.js"></script>
    <script src="js/counterup/counterup-active.js"></script>
    <!-- peity JS
		============================================ -->
    <script src="js/peity/jquery.peity.min.js"></script>
    <script src="js/peity/peity-active.js"></script>
    <!-- sparkline JS
		============================================ -->
    <script src="js/sparkline/jquery.sparkline.min.js"></script>
    <script src="js/sparkline/sparkline-active.js"></script>
    <!-- flot JS
		============================================ -->
    <script src="js/flot/jquery.flot.js"></script>
    <script src="js/flot/jquery.flot.tooltip.min.js"></script>
    <script src="js/flot/jquery.flot.spline.js"></script>
    <script src="js/flot/jquery.flot.resize.js"></script>
    <script src="js/flot/jquery.flot.pie.js"></script>
    <script src="js/flot/Chart.min.js"></script>
    <script src="js/flot/flot-active.js"></script>
    <!-- map JS
		============================================ -->
    <script src="js/map/raphael.min.js"></script>
    <script src="js/map/jquery.mapael.js"></script>
    <script src="js/map/france_departments.js"></script>
    <script src="js/map/world_countries.js"></script>
    <script src="js/map/usa_states.js"></script>
    <script src="js/map/map-active.js"></script>
    <!-- data table JS
		============================================ -->
    <script src="js/data-table/bootstrap-table.js"></script>
    <script src="js/data-table/tableExport.js"></script>
    <script src="js/data-table/data-table-active.js"></script>
    <script src="js/data-table/bootstrap-table-editable.js"></script>
    <script src="js/data-table/bootstrap-editable.js"></script>
    <script src="js/data-table/bootstrap-table-resizable.js"></script>
    <script src="js/data-table/colResizable-1.5.source.js"></script>
    <script src="js/data-table/bootstrap-table-export.js"></script>
     <!--  dropzone JS
		============================================ -->
        <script src="js/dropzone.js"></script>
 <!-- multiple email JS
		============================================ -->
        <script src="js/multiple-email/multiple-email-active.js"></script>
    <!-- summernote JS
		============================================ -->
    <script src="js/summernote.min.js"></script>
    <script src="js/summernote-active.js"></script>
    <!-- main JS
        ============================================ -->
         <!-- notification JS
		============================================ -->
    <script src="js/Lobibox.js"></script>
    <script src="js/notification-active.js"></script>

     <!-- modal JS
		============================================ -->
    <script src="js/modal-active.js"></script>
    <!-- touchspin JS
		============================================ -->
    <script src="js/touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="js/touchspin/touchspin-active.js"></script>
    <!-- colorpicker JS
		============================================ -->
    <script src="js/colorpicker/jquery.spectrum.min.js"></script>
    <script src="js/colorpicker/color-picker-active.js"></script>
    <!-- datapicker JS
		============================================ -->
    <script src="js/datapicker/bootstrap-datepicker.js"></script>
    <script src="js/datapicker/datepicker-active.js"></script>
    <!-- input-mask JS
		============================================ -->
    <script src="js/input-mask/jasny-bootstrap.min.js"></script>
    <!-- chosen JS
		============================================ -->
    <script src="js/chosen/chosen.jquery.js"></script>
    <script src="js/chosen/chosen-active.js"></script>
    <!-- select2 JS
		============================================ -->
    <script src="js/select2/select2.full.min.js"></script>
    <script src="js/select2/select2-active.js"></script>
    <!-- ionRangeSlider JS
		============================================ -->
    <script src="js/ionRangeSlider/ion.rangeSlider.min.js"></script>
    <script src="js/ionRangeSlider/ion.rangeSlider.active.js"></script>
    <!-- rangle-slider JS
		============================================ -->
    <script src="js/rangle-slider/jquery-ui-1.10.4.custom.min.js"></script>
    <script src="js/rangle-slider/jquery-ui-touch-punch.min.js"></script>
    <script src="js/rangle-slider/rangle-active.js"></script>
    <!-- knob JS
    ============================================ -->
        <!-- form validate JS
		============================================ -->
    <script src="js/jquery.form.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/form-active.js"></script>
    <!--- IMPORTED JS VENDORS--->
    <!-- bootstrap-daterangepicker -->
    <script src="import/vendors/moment/min/moment.min.js"></script>
    <script src="import/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap-datetimepicker -->    
    <script src="import/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <!----END OF INPORTED VENDORS-->
    <script src="js/knob/jquery.knob.js"></script>
    <script src="js/knob/knob-active.js"></script>
    

    <script src="js/main.js"></script>

        <!-- Initialize datetimepicker -->
<script>
    $('#myDatepicker').datetimepicker();
    
    $('#myDatepicker2').datetimepicker({
        format: 'DD.MM.YYYY'
    });
    
    $('#myDatepicker3').datetimepicker({
        format: 'hh:mm A'
    });
    $('#myDatepicker31').datetimepicker({
        format: 'hh:mm A'
    });
    
    $('#myDatepicker4').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true
    });

    $('#datetimepicker6').datetimepicker();
    
    $('#datetimepicker7').datetimepicker({
        useCurrent: false
    });
    
    $("#datetimepicker6").on("dp.change", function(e) {
        $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
    });
    
    $("#datetimepicker7").on("dp.change", function(e) {
        $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
    });
</script>
</body>

</html>