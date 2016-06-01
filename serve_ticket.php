<?php

require_once 'DB_Functions.php';
$db = new DB_Functions();
$response = array("error" => false);

$TAG = "ServeTicket";

syslog(LOG_DEBUG, "Serve Ticket: Post  " . print_r($_POST, true));

try {
	if (isset($_POST['ticket_id'])) {

		// receiving the post params
		$ticket_id = (isset($_POST['ticket_id']) && ! empty($_POST['ticket_id'])) ? $_POST['ticket_id'] : null;
		$time_served = (isset($_POST['time_served']) && ! empty($_POST['time_served'])) ? $_POST['time_served'] : null;


		
		//Get place Id
		if ( ! empty($time_served)) {
			$result = $db->serve_ticket($ticket_id, $time_served);
		} else {
			$result = $db->serve_ticket($ticket_id);
		}
		
		if ($result) {
			echo json_encode("success");
			return;
		} else {
			echo json_encode("error");
			return;
		}

	} else {
		// required post params is missing
		$response["error"] = true;
		$response["msg"] = "Required parameters ticket_id aremissing!";
		//		syslog(LOG_DEBUG,"Book Ticket : Post error ".print_r($response,true));
		echo json_encode($response);
	}

} catch (Exception $e) {
	$response["error"] = true;
	$response["msg"] = "Exception Caught at ServeTicket " . $e;
	//	syslog(LOG_DEBUG,"Book Ticket : Exception ".print_r($response));
	echo json_encode($response);
}
?>