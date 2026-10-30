
<?php  error_reporting(1); 
include('geco_db/PdoConnect.php');
  session_start();
try {
	 $pdo = new PDO($dsn, $user, $password);
$pdo ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 sleep(1);
$response = new stdClass;
   date_default_timezone_set('Africa/Cairo');
  $t=date("Y-m-d  H:i:s");$datefield=date("Y-m-d");
  $trash="YES";
  $SmsMessagesict="Service Request:".$_POST['message']."\nClient:".$_POST['names']."\nphone:".$_POST['phone']."\nE-Mail:".$_POST['email'];
 
     $NewSMSict=urlencode($SmsMessagesict);
 
     $smsb=urlencode(htmlentities($NewSMSict, ENT_QUOTES, 'UTF-8'));
	 
     
 
 if(!empty($_POST['names']) && !empty($_POST['email']) && !empty($_POST['phone']) && !empty($_POST['message']))
  {
    $NewSMS=urlencode($_POST['message']);
    $newnames=urlencode($_POST['names']);
    $ictsendsmsnow= file_get_contents("http://api.rmlconnect.net/bulksms/bulksms?username=tntrw&password=EHM1iDdw&type=0&dlr=1&destination=250784439238,250788842990&source=GecoAfrica&message=$NewSMSict"); 

    $sqlS = "INSERT INTO  tb_notification (notificationn,fullnames,email,phone,sent_date) VALUES (
        :notfi,:names,:email,:phone,:sent_date)";
                             
    $stmt = $pdo->prepare($sqlS);
    $stmt->bindParam(':notfi',$_POST['message'], PDO::PARAM_STR);
    $stmt->bindParam(':names',$_POST['names'], PDO::PARAM_STR);
    $stmt->bindParam(':email',$_POST['email'], PDO::PARAM_STR);
    $stmt->bindParam(':phone',$_POST['phone'], PDO::PARAM_STR);
    $stmt->bindParam(':sent_date',$t, PDO::PARAM_STR);
    $stmt->execute(); 
 //header("location:geco_email/sendrequest.php?phone=$_POST[phone]&&email=$_POST[email]&&message=$NewSMS&&names=$newnames");
 $response->requestlink ="geco_email/sendqueryc.php?phone=$_POST[phone]&&email=$_POST[email]&&message=$NewSMS&&names=$newnames"; 
 //$response->request = "Sent successfully"; 
  } 
 die(json_encode($response)); 
	 $DBH = null;
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
	 
}
?>