<link rel="shortcut icon" href="../img/logo/icon.png">
<center>
<img src="../loading/MiserableWebbedBlacklab-small.gif" height="200px" width="200px">

</center>waiting while sending.....<br><a href="">RESEND</a>
<p style="padding: 20px;   margin-top: -100000px;
 background: ;
 color: #fff;
  -moz-border-radius: 4px;
  -webkit-border-radius: 4px;
  border-radius: 4px;">

 
 <?php // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    //Load Composer's autoloader
    require 'vendor/autoload.php';
    
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.googlemail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'gecoafricaltd1@gmail.com';                 // SMTP username
        $mail->Password = 'geco/001';                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to
    
        //Recipients
        $mail->setFrom("$_GET[email]", "$_GET[names]");
        $mail->addAddress("info@gecoafrica.com", "Geco Africa Ltd");     // Add a recipient
        $mail->addAddress("kimu6@yahoo.fr", "GENERAL MANAGER");     // Add a recipient
        $mail->addReplyTo("$_GET[email]", "$_GET[names]");
         
    
        //image imbedded
       // $mail->AddEmbeddedImage('pics/logo-apollo.png', 'logo_2u');
        
        //Attachments
         
           // Optional name
    
        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Feed back from website';
        $mail->Body    = "$_GET[message],<br><br> 
    Sent By:<br><b> $_GET[names].</b> <br> 
    phone number:<b>$_GET[phone]</b>
    <br><br>
    <hr>
   Geco Africa Ltd. 
        ";
        $mail->AltBody = 'Feedback from website';
    
        $mail->send();
        echo "<script>
                    window.location.href = '../home.php?success#sendquery';
    </script>";
    } catch (Exception $e) {
        echo "<script>
                    window.location.href = '../home.php?error#sendquery';
    </script>", $mail->ErrorInfo;
    }echo"</p>";