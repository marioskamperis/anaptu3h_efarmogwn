<?php
require_once 'DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => false);
try {

	if (isset($_POST['email']) && isset($_POST['password'])) {

		// receiving the post params
		$email = $_POST['email'];
		$password = $_POST['password'];

		// get the user by email and password
		$user = $db->getUserByEmailAndPassword($email, $password);
		$user_info = $db->loginUser($user['id'], '192.168.1.1', 'mobile');
		if ($user != false) {
//			syslog(LOG_DEBUG,"Login Activity found");
			// use is found
			$response["error"] = false;
			$response["uid"] = $user["unique_id"];
			$response["user"]["id"] = $user["id"];
			$response["user"]["name"] = $user["name"];
			$response["user"]["email"] = $user["email"];
			$response["user"]["created_at"] = $user["created_at"];
			$response["user"]["updated_at"] = $user["updated_at"];
			echo json_encode($response);
		} else {
//			syslog(LOG_DEBUG,"Login Activity NOT found");
			// user is not found with the credentials
			$response["error"] = true;
			$response["error_msg"] = "Login credentials are wrong. Please try again!";
			echo json_encode($response);
		}
	} else {
		// required post params is missing
		$response["error"] = true;
		$response["error_msg"] = "Required parameters email or password is missing!";
		echo json_encode($response);
	}
} catch (Exception $e) {
//	syslog(LOG_DEBUG,"Login Activity Exception");
	$response["error"] = true;
	$response["error_msg"] = "Exception Caught at mobile_login.php " . $e."";
	echo json_encode($response);
}
?>