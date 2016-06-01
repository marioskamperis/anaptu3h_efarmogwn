<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Protereotitapp</title>
	<link rel="stylesheet" href="css/register.css">
</head>

<body>

<div class="wrapper">
	<div class="container">
		<h1>Proterotiapp Registration Panel</h1>


		<!--        Pio katw sthn selida einai h php h opoia 8a dex8ei ths metavklhtes apo to registration .
		ftia3e thn forma kai 8a frontisw na valw egw tis metavlhtes tshn php kai sthn vash dedomenwn ftia3e kai ligo
		to css (css/register.css)-->
		<form role="form" action="register.php" method="post">
			<div class='login'>
				<h2>Register</h2>
				<input name='email' placeholder='E-Mail Address' type='text'>
				<input id='pw1' name='password' placeholder='Password' type='password'>

				<input name='name' placeholder='Name' type='text'>
				<input class='animated' type='submit' value='Register''>
			</div>

			<button id="register">Register</button>
		</form>
	</div>

	<ul class="bg-bubbles">
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
	</ul>
</div>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script>
	$(function ()
	{
		$("#register").click(function ()
		{
			event.preventDefault();
			window.location.replace("/protereotitapp/register.php");
		});
	});
</script>
<script src="js/login.js"></script>

</body>
</html>


<?php
// response json
$json = array();

if (isset($SESSION)) {
	session_destroy();
} else {
	session_start();
}

if (isset($_POST["email"]) && isset($_POST["password"])) {

	$password = $_POST["password"];
	$email = $_POST["email"];
	$name = $_POST['name'];

	//echo($email . " " . $password1 . " " . $password2   . " " . $name . "");

	// Store user details in db
	include_once 'DB_Functions.php';

	$db = new DB_Functions();

	$user = $db->storeUser($email, $password, $name);
	//echo(print_r($user,true));
	if ( ! empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
		$browser = 'Internet explorer';
	} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false) //For Supporting IE 11
	{
		$browser = 'Internet explorer';
	} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== false) {
		$browser = 'Mozilla Firefox';
	} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false) {
		$browser = 'Google Chrome';
	} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false) {
		$browser = "Opera Mini";
	} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false) {
		$browser = "Opera";
	} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== false) {
		$browser = "Safari";
	} else {
		$_SESSION['browser'] = 'Something else';
	}

	$user_info = $db->loginUser($user['id'], $ip, $browser);

	$_SESSION['user'] = $user; // Initializing Session
	$_SESSION['user_info'] = $user_info; // Initializing Session

	$newURL = "index.php";
	header('Location: ' . $newURL);
} else {

	// user details missing
}
?>