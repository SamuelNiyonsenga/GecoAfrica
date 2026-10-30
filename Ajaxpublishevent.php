<?php ini_set('max_execution_time', '12000');
error_reporting(1); 
session_start();
require_once('Login-Portal/includes/connect.php'); 
sleep(0);
$response = new stdClass;
date_default_timezone_set('Africa/Cairo');
  $t=date("Y-m-d  H:i:s");
  $time=date("Y-m-d H:i:s");
  // Create To day date
  $fulltime=date("Y-m-d H:i:s");$dateonly=date("Y-m-d");$timeonly=date("H:i:s");
 
  
  $event_status="published";
if(isset($_GET['eventID'])&& !empty($_GET['eventID']))
{ 
   
  $sqlAPP = "UPDATE tb_events SET event_status=:event_status WHERE event_id=:event_id";
                                             
   $stmt = $db->prepare($sqlAPP);
   $stmt->bindParam(':event_status', $event_status, PDO::PARAM_STR); 
   $stmt->bindParam(':event_id', $_GET['eventID'], PDO::PARAM_STR); 
   $stmt->execute(); 
  // success result
  $response->result = "Success";
  $response->status = ""; 
  $response->statusb = "Well saved!"; 
   
}
    else{ 
        $response->result = "Empty Field";
        $response->status = "";  
        $response->statusb = "Fill all required field";  
       
    }
  //END 
 die(json_encode($response)); 
?>
