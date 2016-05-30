<?php
/**
 * Created by PhpStorm.
 * User: Marios
 * Date: 5/30/2016
 * Time: 7:55 PM
 */

include('../DB_Functions.php');

$db = new DB_Functions();
echo "ok";
$user_names = array('Rob', 'Aleta', 'Emma', 'Cindi', 'Jacquie', 'Kip', 'Lucrecia', 'Kasha', 'Rita', 'Twila', 'Angeles', 'Eugene', 'Mariko', 'Kristopher', 'Everette', 'Nelle', 'Carmen', 'Pansy', 'Florence', 'Thu', 'Kandy', 'Illa', 'Holley', 'Tasia', 'Elissa', 'Riva', 'Santo', 'Inga', 'Silvana', 'Tawanna', 'Bryan', 'Randell', 'Jeanne', 'Jerri', 'Wendie', 'Amalia', 'Bobby', 'Deloise', 'Eladia', 'Hildred', 'Margery', 'Salley', 'Vella', 'Lynna', 'Latasha', 'Elsie', 'Neva', 'Dotty', 'Kristyn', 'Malisa');
for ($i = 1; $i < 10; $i++) {
	foreach ($user_names as $username) {
		$user = $db->storeUser($username . "" . $i . "@gmail.com", $username . "" . $i, strtoupper($username . "" . $i));
	}

}

