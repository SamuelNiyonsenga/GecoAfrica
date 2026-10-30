<?php ini_set('max_execution_time', '12000');
error_reporting(1); 
session_start();
require_once('Login-Portal/includes/connect.php');
//require_once('../Login-Portal/check-staff-login.php'); 
sleep(0);
$response = new stdClass;
date_default_timezone_set('Africa/Cairo');
  $t=date("Y-m-d  H:i:s");
  $time=date("Y-m-d H:i:s");
  // Create To day date
  $fulltime=date("Y-m-d H:i:s");$dateonly=date("Y-m-d");$timeonly=date("H:i:s");
  // Convert date 
  // select type of work form data base
  $timeconv = date("Y-m-d,H:i:s", strtotime($time));
  $event_duration = date("Y-m", strtotime($_POST['event_duration']));
 
// end of time calculation
$verifiedrow="verified";
$datetime=date("Y-m-d");
$fileName="None";
 
  // select goods history
 /* $sql= "SELECT `tb_goods`.`good_TotalQuantity`  FROM  tb_goods WHERE `tb_goods`.`goods_id`=:goods_id";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':goods_id',$_POST['stock_GoodsID'], PDO::PARAM_STR);	 	 	 
  $stmt->execute(); 
  $goodsres = $stmt->fetch(PDO::FETCH_ASSOC);
  $good_TotalQuantity=$goodsres['good_TotalQuantity']+$_POST['stock_gdquantity'];*/
  $event_status="pending";
if(isset($_SESSION['userID'])&& !empty($_POST['event_title'])&& !empty($_POST['event_duration']) && !empty($_POST['event_client'])  && !empty($_POST['event_date'])  && !empty($_POST['event_contents']))
{ 
   
  $sqlAPP = "INSERT INTO tb_events (event_duration,event_title,event_client,event_date,event_contents,event_postedby,event_postedtime,event_status) VALUES (
            :event_duration,:event_title,:event_client,:event_date,:event_contents,:event_postedby,:event_postedtime,:event_status)";
                                             
   $stmt = $db->prepare($sqlAPP);
   $stmt->bindParam(':event_duration',  $event_duration, PDO::PARAM_STR); 
   $stmt->bindParam(':event_title', $_POST['event_title'], PDO::PARAM_STR); 
   $stmt->bindParam(':event_client', $_POST['event_client'], PDO::PARAM_STR);
   $stmt->bindParam(':event_date', $_POST['event_date'], PDO::PARAM_STR);
   $stmt->bindParam(':event_contents', $_POST['event_contents'], PDO::PARAM_STR);
   $stmt->bindParam(':event_postedby', $_SESSION['userID'], PDO::PARAM_STR);
   $stmt->bindParam(':event_postedtime',$time, PDO::PARAM_STR);
   $stmt->bindParam(':event_status', $event_status, PDO::PARAM_STR);  
   $stmt->execute();
   $lasteventid= $db->lastInsertId();
  // success result
  $response->result = "Success";
  $response->status = ""; 
  $response->statusb = "Well saved!"; 
  $response->link="../ProfileDropzone?user=event&eventID=$lasteventid"; 
  $response->linkb="<a href='#' onclick =$quo createPopupWinNewdata('pages/livepick?stockID=$_POST[stockID]','Proforma', 800, 700);$quo >  click hire</a>"; 
  
}
    else{ 
        $response->result = "Empty Field";
        $response->status = "";  
        $response->statusb = "Fill all required field";  
       
    }
  //END 
 die(json_encode($response)); 
?>
