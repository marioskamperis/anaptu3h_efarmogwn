<?php

require_once 'DB_Functions.php';
$db = new DB_Functions();

$place_id=1;
$user_id=63;
$ticket = $db->book_ticket($place_id, $user_id);
if ($ticket == false) {
	$response["error"] = false;
	$response["msg"] = "Problem obtaining next ticket.";

}
$ticket['error']=false;
var_dump($ticket);
echo print_r($ticket,true);
echo print_r(json_encode($ticket),true);
exit;
// json response array
$response = array("error" => false);
$TAG = "BookTicket";

try {

	if (isset($_POST['google_place_id']) && isset($_POST['user_id'])) {

		// receiving the post params
		$google_place_id = (isset($_POST['google_place_id']) && ! empty($_POST['google_place_id'])) ? $_POST['google_place_id'] : null;
		$user_id = (isset($_POST['user_id']) && ! empty($_POST['user_id'])) ? $_POST['user_id'] : null;
		//		$place['address'] = (isset($_POST['address']) && ! empty($_POST['address'])) ? $_POST['address'] : null;
		//		$place['lat'] = (isset($_POST['lat']) && ! empty($_POST['lat'])) ? $_POST['lat'] : null;
		//		$place['lon'] = (isset($_POST['lon']) && ! empty($_POST['lon'])) ? $_POST['lon'] : null;
		//		$place['telephone'] = (isset($_POST['telephone']) && ! empty($_POST['telephone'])) ? $_POST['telephone'] : null;
		//		$place['type'] = (isset($_POST['type']) && ! empty($_POST['type'])) ? $_POST['type'] : null;
		//		$place['attributes'] = (isset($_POST['attributes']) && ! empty($_POST['attributes'])) ? $_POST['attributes'] : null;
		//		$place['website'] = (isset($_POST['website']) && ! empty($_POST['website'])) ? $_POST['website'] : null;


		//TODO check if user can book ticket for the same service

		//TODO book next ticket
		$ticket = $db->book_ticket($google_place_id, $user_id);
		if ($ticket == false) {
			$response["error"] = false;
			$response["msg"] = "Problem obtaining next ticket.";
			echo json_encode($response);
		}


		//TODO calculate estimated time

//		$time_calculation = $db->calculate_time($ticket_id);
//		if ($ticket == false) {
//			$response["error"] = false;
//			$response["msg"] = "Problem calculating estimated time.";
//			echo json_encode($response);
//		}

		//TODO return ticket number and estimated time

		$ticket['error']=false;
		$ticket['estimated_time']=$time_calculated;
		echo json_encode($ticket);
		exit;

	} else {
		// required post params is missing
		$response["error"] = true;
		$response["msg"] = "Required parameters google_place_id or user_id is missing!";
		//		syslog(LOG_DEBUG,"Book Ticket : Post error ".print_r($response,true));
		echo json_encode($response);
	}

} catch (Exception $e) {
	$response["error"] = true;
	$response["msg"] = "Exception Caught at bookticket " . $e;
	//	syslog(LOG_DEBUG,"Book Ticket : Exception ".print_r($response));
	echo json_encode($response);
}
?>