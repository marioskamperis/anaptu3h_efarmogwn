<?php
/**
 * Created by PhpStorm.
 * User: Marios
 * Date: 5/30/2016
 * Time: 7:44 PM
 */
// Valid Users from 1 to (AND) 502
//Array of Places

include('../DB_Functions.php');

$db = new DB_Functions();

echo "ok";
$places = array(1, 4, 8);
$users_booked = array();


for ($user_id = 1; $user_id <= 502; $user_id++) {
	$random_user = rand(1, 502);

	if (in_array($random_user, $users_booked)) {
		$user_id--;
		continue;
	} else {
		echo "<pre>";
		echo "Round :" . $user_id . " user :" . $random_user;
		echo "</pre>";

		$users_booked[] = $random_user;
		//		sleep(rand(1, 120));
		$ok = $db->book_ticket($places[rand(0, 2)], $random_user);

	}
}


