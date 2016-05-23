<?php

require_once 'DB_Functions.php';
$db = new DB_Functions();

//$place_id=4;
//$user_id=63;
//$book_ticket = $db->book_ticket($place_id,$user_id);
//if($book_ticket){
//	echo "ok".$book_ticket;
//}else{
//	echo "not ok ".$book_ticket;
//}
//exit;
// json response array
$response = array("error" => false);
$TAG = "BookTicket";

try {

	if (isset($_POST['place_id']) && isset($_POST['user_id'])) {

		// receiving the post params
		$place_id = (isset($_POST['place_id']) && ! empty($_POST['place_id'])) ? $_POST['place_id'] : null;
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
		$book_ticket = $db->book_ticket($place_id, $user_id);
		if ($book_ticket == false) {
			$response["error"] = false;
			$response["msg"] = "Problem obtaining next ticket.";

		}
		//TODO calculate estimated time

		//TODO return ticket number and estimated time


		//		syslog(LOG_DEBUG,"Book Ticket : Exists ".print_r($exists,true));

		if ($exists != false) {

			$response["error"] = false;
			//			$response["ticket_number"] = ;
			//			$response["estimated_time"] = ;

			//			syslog(LOG_DEBUG,"Book Ticket : Exists ".print_r($response,true));
			echo json_encode($response);

		} else {
			$response["error"] = true;
			$response["msg"] = "Something went wrong when we tried to Book your ticket";

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