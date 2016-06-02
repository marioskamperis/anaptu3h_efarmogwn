<?php
require_once 'DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => false);


try {

	if (isset($_POST['place_id'])) {

		// receiving the post params
		$place_id = $_POST['place_id'];

		$id = $db->get_google_place_id($place_id);

		$live_number = $db->getLastServedNumber($id);

		if (!empty($live_number['number'])) {
			$response["error"] = false;
			$response["number"] = $live_number['number'];
			echo json_encode($response);
		} else {
			$response["error"] = true;
			$response["error_msg"] = "Live Number Not Numeric error";
			echo json_encode($response);
		}

	} else {
		// required post params is missing
		$response["error"] = true;
		$response["error_msg"] = "Required parameters place_id is missing!";
		echo json_encode($response);
	}
} catch (Exception $e) {
	//	syslog(LOG_DEBUG,"Login Activity Exception");
	$response["error"] = true;
	$response["error_msg"] = "Exception Caught at live_ticket" . $e . "";
	echo json_encode($response);
}
