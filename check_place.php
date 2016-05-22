<?php

require_once 'DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => false);


try {

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


		$exists = $db->checkPlace($place['place_id']);

//		syslog(LOG_DEBUG,"Book Ticket : Exists ".print_r($exists,true));

		if ($exists != false) {

			$response["error"] = false;
			$response["msg"] = "Found it";
//			syslog(LOG_DEBUG,"Book Ticket : Exists ".print_r($response,true));
			echo json_encode($response);

		} else {
			$response["error"] = true;
			$response["msg"] = "We do not currently support " . $name;
			//TODO ADD this place
			$query = $db->addPlace($place);
//			syslog(LOG_DEBUG,"Book Ticket : Does Not Exists ".print_r($response,true));
			echo json_encode($response);
		}

	} else {
		// required post params is missing
		$response["error"] = true;
		$response["msg"] = "Required parameters place_id or name or user_id is missing!";
//		syslog(LOG_DEBUG,"Book Ticket : Post error ".print_r($response,true));
		echo json_encode($response);
	}

} catch (Exception $e) {
	$response["error"] = true;
	$response["msg"] = "Exception Caught at checkplace " . $e;
//	syslog(LOG_DEBUG,"Book Ticket : Exception ".print_r($response));
	echo json_encode($response);
}
?>