<?php
/**
 * Created by PhpStorm.
 * User: Marios
 * Date: 6/2/2016
 * Time: 1:10 AM
 */

include('../DB_Functions.php');

$db = new DB_Functions();
$tickets = $db->drop_tickets();

header("Location : /protereotitapp/data.php");


