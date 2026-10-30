<?php
session_start();
if(isset($_SESSION['login']) & ($_SESSION['login'] == true)){

}else{
	// redirect user to login page
	header("location: eazhubHome/");
}
if(isset($_SESSION['adminID']) & !empty($_SESSION['adminemail'])){

}else{
	// redirect user to login page
	header("location: eazhubHome/");
}
if(isset($_SESSION['last_login']) & !empty($_SESSION['last_login'])){

}else{
	// redirect user to login page
	header("location: eazhubHome/");
}
?>