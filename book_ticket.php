<?php

require_once 'DB_Functions.php';
$db = new DB_Functions();
//$place_id = $db->get_google_place_id('ChIJiUScwcu_oRQRR9t0zspX81E'); 
//echo $place_id;
//exit;
//
//$place_id = 1;
//$user_id = 67;
//$ticket = $db->book_ticket($place_id, $user_id);
//
//
//if($ticket['estimated_time']==-1){
//	$response["error"] = true;
//	$response["msg"] = "No more tickets. Please try again tomorrow !";
//	echo json_encode($response);
//	exit;
//}
//
//if ($ticket == false) {
//	$response["error"] = false;
//	$response["msg"] = "Problem obtaining next ticket.";
//	echo json_encode($response);
//	exit;
//}
//
//if (!isset($ticket['estimated_time']) || empty($ticket['estimated_time'])) {
//	$response["error"] = true;
//	$response["msg"] = "Problem calculating estimated time.";
//	echo json_encode($response);
//	exit;
//}
//if (!isset($ticket['number']) || empty($ticket['number'])) {
//	$response["error"] = true;
//	$response["msg"] = "Problem calculating number.";
//	echo json_encode($response);
//	exit;
//}
//if (!isset($ticket['unique_code']) || empty($ticket['unique_code'])) {
//	$response["error"] = true;
//	$response["msg"] = "Problem getting unique_code.";
//	echo json_encode($response);
//	exit;
//}
//if (!isset($ticket['expiration_date']) || empty($ticket['expiration_date'])) {
//	$response["error"] = true;
//	$response["msg"] = "Problem getting expiration_date";
//	echo json_encode($response);
//	exit;
//}
//
//
//echo json_encode($ticket);
//exit;
// json response array
$response = array("error" => false);
$TAG = "BookTicket";

syslog(LOG_DEBUG,"Book Ticket : Post  ".print_r($_POST,true));

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


		//Get place Id
		$place_id = $db->get_google_place_id($google_place_id);
		
		if(!isset($place_id) || empty($place_id)){
			$response["error"] = true;
			$response["msg"] = "Could not locate google place id.";
			echo json_encode($response);
			exit;
		}
		
		//TODO book next ticket
		$ticket = $db->book_ticket($place_id, $user_id);

		if($ticket['estimated_time']==-1){
			$response["error"] = true;
			$response["msg"] = "No more tickets. Please try again tomorrow !";
			echo json_encode($response);
			exit;
		}

		if ($ticket == false) {
			$response["error"] = false;
			$response["msg"] = "Problem obtaining next ticket.";
			echo json_encode($response);
			exit;
		}
		
		

		if (!isset($ticket['estimated_time']) || empty($ticket['estimated_time'])) {
			$response["error"] = true;
			$response["msg"] = "Problem calculating estimated time.";
			echo json_encode($response);
			exit;
		}
		if (!isset($ticket['number']) || empty($ticket['number'])) {
			$response["error"] = true;
			$response["msg"] = "Problem calculating number.";
			echo json_encode($response);
			exit;
		}
		if (!isset($ticket['unique_code']) || empty($ticket['unique_code'])) {
			$response["error"] = true;
			$response["msg"] = "Problem getting unique_code.";
			echo json_encode($response);
			exit;
		}
		if (!isset($ticket['expiration_date']) || empty($ticket['expiration_date'])) {
			$response["error"] = true;
			$response["msg"] = "Problem getting expiration_date";
			echo json_encode($response);
			exit;
		}

		$ticket['error']=false;
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