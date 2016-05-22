<?php

require_once 'DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => false);

if (isset($_POST['place_id']) && isset($_POST['name'])) {

	// receiving the post params
	$place['place_id'] = (isset($_POST['place_id']) && ! empty($_POST['place_id'])) ? $_POST['place_id'] : null;
	$place['name'] = (isset($_POST['name']) && ! empty($_POST['name'])) ? $_POST['name'] : null;
	$place['address'] = (isset($_POST['address']) && ! empty($_POST['address'])) ? $_POST['address'] : null;
	$place['lat'] = (isset($_POST['lat']) && ! empty($_POST['lat'])) ? $_POST['lat'] : null;
	$place['lon'] = (isset($_POST['lon']) && ! empty($_POST['lon'])) ? $_POST['lon'] : null;
	$place['telephone'] = (isset($_POST['telephone']) && ! empty($_POST['telephone'])) ? $_POST['telephone'] : null;
	$place['type'] = (isset($_POST['type']) && ! empty($_POST['type'])) ? $_POST['type'] : null;
	$place['attributes'] = (isset($_POST['attributes']) && ! empty($_POST['attributes'])) ? $_POST['attributes'] : null;
	$place['website'] = (isset($_POST['website']) && ! empty($_POST['website'])) ? $_POST['website'] : null;
	//TODO REQUEST USER ID

	//	// get the user by email and password
	//	$user = $db->getUserByEmailAndPassword($email, $password);
	//	$user_info = $db->loginUser($user['id'], '192.168.1.1', 'mobile');

	$exists = $db->checkPlace($place_id);

	if ($exists != false) {
		$response["error"] = false;
		$response["error_msg"] = "Found it";
		echo json_encode($response);

	} else {
		$response["error"] = true;
		$response["error_msg"] = "We do not currently support " . $name;

		//TODO ADD this place
		$query = $db->addPlace($place);

		echo json_encode($response);
	}
	//	if ($user != false) {
	//		// use is found
	//		$response["error"] = false;
	//		$response["uid"] = $user["unique_id"];
	//		$response["user"]["name"] = $user["name"];
	//		$response["user"]["email"] = $user["email"];
	//		$response["user"]["created_at"] = $user["created_at"];
	//		$response["user"]["updated_at"] = $user["updated_at"];
	//		echo json_encode($response);
	//	} else {
	//		// user is not found with the credentials
	//		$response["error"] = true;
	//		$response["error_msg"] = "Login credentials are wrong. Please try again!";
	//		echo json_encode($response);
	//	}
} else {
	// required post params is missing
	$response["error"] = true;
	$response["error_msg"] = "Required parameters place_id or name or user_id is missing!";
	echo json_encode($response);
}
?>