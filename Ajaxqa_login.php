<?php  //ini_set('max_execution_time', '12000');

error_reporting(1); 
require_once('Login-Portal/includes/connect.php');
session_start();
sleep(0);
$response = new stdClass;
   date_default_timezone_set('Africa/Cairo');
  $t=date("Y-m-d  H:i:s");$log="on";$dateonly=date("Y-m-d");
if(isset($_POST['email'])&& !empty($_POST['email'])  && !empty($_POST['password']))
{
 // CSRF Token Validation
 if(isset($_POST['csrf_token'])){
    if($_POST['csrf_token'] === $_SESSION['csrf_token']){
    }else{
        $response->result = "Token Error";
        $response->status = "?Token=&&page=4&&link=AddnewUser";  
        $response->statusb = "Problem with CSRF Token Validation";
    }
}
// CSRF Token Time Validation
$max_time = 60*60*24; // in seconds
if(isset($_SESSION['csrf_token_time'])){
    $token_time = $_SESSION['csrf_token_time'];
    if(($token_time + $max_time) >= time() ){
    }else{
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_time']);
        $response->result = "Token Expired";
        $response->status = "?Token=&&page=4&&link=AddnewUser";  
        $response->statusb = "CSRF Token Expired";
    }
}
  // Check the Login Credentials
  $sql = "SELECT userID,useremail,passcode FROM tb_users WHERE ";
  if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
      $sql .= "useremail=?";
  }else{
      $sql .= "useremail=?";
  }
  $result = $db->prepare($sql);
  $result->execute(array($_POST['email']));
  $count = $result->rowCount();
  $res = $result->fetch(PDO::FETCH_ASSOC);
  if($count == 1){
      // Compare the password with password hash
      if(password_verify($_POST['password'], $res['passcode'])){
          // regenerate session id
          session_regenerate_id();
          $_SESSION['login'] = true;
          $_SESSION['userID'] = $res['userID'];
          $_SESSION['current_session']=$_SESSION['userID']; 
          $_SESSION['useremail'] = $_POST['email'];
          $_SESSION['last_login'] = time();
          $_SESSION['user_type'] ="ADMIN";

            // keep log info
            $sqlinsert = "INSERT INTO  tb_user_login (user_logid,log_date,user_log,log_time,log_usertype) VALUES (:id,:log_date,:log,:time,:log_usertype)";
            $stmt = $db->prepare($sqlinsert);                                  
            $stmt->bindParam(':log', $log, PDO::PARAM_STR);       
            $stmt->bindParam(':log_date',$dateonly, PDO::PARAM_STR);       
            $stmt->bindParam(':time', $t, PDO::PARAM_STR);  
            $stmt->bindParam(':id',$_SESSION['current_session'], PDO::PARAM_STR);
            $stmt->bindParam(':log_usertype',$_SESSION['user_type'], PDO::PARAM_STR);
            if($stmt->execute()>0){
              // redirect the user to members area/dashboard page
          $response->result = "Success";
          $response->status = "../?Token=$_POST[csrf_token]";  
          $response->statusb = "Successfully Logged in<br>Wait while system redirect you";
            
        }else{
            // Dispalying error
          $response->result = "Technical error";
          $response->status = "?";  
          $response->statusb = "Technical Issue please call IT Department";  
          }
         
      }else{
        $response->result = "Email or Password Error";
        $response->status = "?Token=&&page=4&&link=AddnewUser";  
        $response->statusb = "Wrong Email or password <br>Please try again or rest your password";
      }
    }else{
        $response->result = "Email Error";
        $response->status = "?Token=&&page=4&&link=AddnewUser";  
        $response->statusb = "E-Mail not Valid";   
    }
 
}
    else{
        $response->result = "Empty Field";
        $response->status = "?Token=&&page=3&&link=meeting#error";  
        $response->statusb = "Fill all required field";    
    }
  //END 
 die(json_encode($response)); 
?> 