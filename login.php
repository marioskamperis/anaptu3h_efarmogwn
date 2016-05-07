<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Texnologia Polumeswn</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
<div class="wrapper">
    <div class="container">
        <h1>Proterotiapp Admin Panel</h1>

        <form name="form" actions="/game/login.php" method="POST" accept-charset="utf-8">
            <input type="text" name='email' id='email' placeholder="example@email.com" required>
            <input type="password" name='password' id='password' placeholder="password" required>
            <button type="submit" name='Submit' id="submit" value='Submit'>Login</button>
        </form>
        <form name="form" actions="/register.php" method="POST" accept-charset="utf-8">

            <button  id="register">Register</button>
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
    </ul>
</div>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script>
    $(function(){
        $("#register").click(function(){
            event.preventDefault();
            window.location.replace("/game/register.php");
        });
    });
</script>
<script src="js/login.js"></script>

</body>
</html>

<?php

/**
 * File to handle all API requests
 * Accepts GET and POST
 *
 * Each request will be identified by TAG
 * Response will be JSON data
 *
 * /**
 * check for POST request
 */

// include db handler
require_once 'DB_Functions.php';
$db = new DB_Functions();


// login from browser
if (isset($_POST['email']) && $_POST['email'] != ''&& isset($_POST['password'])  && $_POST['password'] != '') {
    echo("ok");
    $email = $_POST['email'];
    $password = $_POST['password'];
    $email = stripslashes($email);
    $password = stripslashes($password);
    $email = mysql_real_escape_string($email);
    $password = mysql_real_escape_string($password);
    // check for user


    $user = $db->getUserByEmailAndPassword($email, $password);

    if (!empty($user)) {
        session_start();
        $_SESSION['user'] = $user; // Initializing Session


        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $_SESSION['ip'] = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $_SESSION['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        }
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
            $_SESSION['browser'] = 'Internet explorer';
        elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
            $_SESSION['browser'] = 'Internet explorer';
        elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
            $_SESSION['browser'] = 'Mozilla Firefox';
        elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
            $_SESSION['browser'] = 'Google Chrome';
        elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
            $_SESSION['browser'] = "Opera Mini";
        elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
            $_SESSION['browser'] = "Opera";
        elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
            $_SESSION['browser'] = "Safari";
        else
            $_SESSION['browser'] = 'Something else';


        $user_info = $db->loginUser($user['id'], $_SESSION['ip'], $_SESSION['browser']);

        $_SESSION['user_info'] = $user_info;
        header("location: index.php"); // Redirecting To Other Page
    } else {
        // This is in the PHP file and sends a Javascript alert to the client
        $message = "Wrong UserName or Password";
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
}
?>