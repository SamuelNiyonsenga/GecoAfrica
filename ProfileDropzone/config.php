<?php
session_start();
define('HOST', 'ofline');
error_reporting(0);
  if($_GET['user']=="event"){
        $geco_imgtable="event_img";
        $geco_userid="event_Mid";
    $geco_profile_dr="../../uploads/events/";
    $geco_current_id=$_GET['eventID'];
    }
    else{
        $geco_imgtable="";
        $geco_userid="";
        $geco_profile_dr="";
        $geco_current_id="";
    }
$url	=	(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if(HOST == 'online'){
    define('DB_NAME', 'cspchukc_gecoafrica');
    define('DB_USER', 'cspchukc_gecoafrica');
    define('DB_PASSWORD', '6UkOwg424=Z2');
    define('DB_HOST', 'localhost:3306');
    define('HOME_URL','https://eh.sagec.rw/pages/Dropzone');
    define("HOME_PATH",'https://eh.sagec.rw/pages/Dropzone');
    define("HOME_AJAX",HOME_URL.'ajax/');
}else{
    define('DB_NAME', 'cspchukc_gecoafrica');
    define('DB_USER', 'cspchukc_gecoafrica');
    define('DB_PASSWORD', '6UkOwg424=Z2');
    define('DB_HOST', 'localhost:3306');
    define('HOME_URL',$url);
    define("HOME_PATH",$url);
    define("HOME_AJAX",HOME_URL.'ajax/');
} 

/*** TB DEFINE ***/
define('TB_IMG',$geco_imgtable);
 
/*** DB INCLUDES ***/
include_once 'Database.php';
 
/*** DB CONNECTION ***/
$dsn        =   "mysql:dbname=".DB_NAME.";host=".DB_HOST."";
$pdo        =   '';
try {$pdo   =   new PDO($dsn, DB_USER, DB_PASSWORD);} catch (PDOException $e) {echo "Connection failed: " . $e->getMessage();}
 
/*Classes*/
$db         =   new Database($pdo);
?>