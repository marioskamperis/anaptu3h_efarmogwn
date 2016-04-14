<?php
session_start();
require_once 'DB_Functions.php';
$db = new DB_Functions();
$user = $_SESSION['user'];
echo(print_r($_SESSION['user']['name'],true));
// echo(print_r($_SESSION['user_info']['login_at'],true));
// die();
$user = $db->logoutUser($user['id']);
  if ($user == true) {
	  $message="successfull Logout :".$_SESSION['user']['name']." : ".$_SESSION['user_info']['login_at']." Loggout At ".$user;
    echo "<script type='text/javascript'>alert('$message');</script>";
  }
  else{
	  echo("Logout informmation unsuccefull ");
    //die();
  }
if(session_destroy()) // Destroying All Sessions
{
header("Location: login.php"); // Redirecting To Home Page
}
?>