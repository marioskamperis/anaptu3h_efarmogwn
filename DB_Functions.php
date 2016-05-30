<?php
error_reporting(E_ERROR | E_PARSE);

header('Content-Type: text/html; charset=utf-8');

class DB_Functions
{

	private $db;

	//put your code here
	// constructor
	function __construct()
	{

		require_once 'DB_Connect.php';
		// connecting to database
		$this->db = new DB_Connect();
		$this->db->connect();
	}

	// destructor
	function __destruct()
	{

	}

	/**
	 * Storing new user
	 * returns user details
	 */
	public function storeUser($email, $password, $name)
	{
		$uuid = uniqid('', true);
		$hash = $this->hashSSHA($password);
		$encrypted_password = $hash["encrypted"]; // encrypted password

		$salt = $hash["salt"]; // salt
		$sql = "INSERT INTO protereotitapp.users(unique_id, name, email, encrypted_password, salt, created_at) VALUES('$uuid', '$name', '$email', '$encrypted_password', '$salt', NOW())";

		$result = mysql_query($sql);
		// check for successful store

		if ($result) {
			// get user details
			$id = mysql_insert_id(); // last inserted id
			$result = mysql_query("SELECT * FROM users WHERE id = $id");
			// return user details
			return mysql_fetch_array($result);
		} else {
			return false;
		}
	}

	public function loginUser($user_id, $ip, $browser)
	{

		$result = mysql_query("INSERT INTO proteroetitapp.user_info(login_at,loggout_at,user_id,ip_adress,is_alive,browser)
                              VALUES(NOW(),NULL,$user_id,'$ip',NOW(),'$browser')");


		$id = mysql_insert_id();; // Initializing Session
		$user = mysql_query("SELECT * FROM proteroetitapp.user_info WHERE id = $id");

		// check for successful store
		if ( ! empty($user)) {
			return mysql_fetch_array($user);
		} else {
			return false;
		}
	}

	public function isAlive($user_id, $ip, $browser)
	{
		$result = mysql_query("INSERT INTO user_info(login_at,loggout_at,user_id,ip_adress,) VALUES(NOW(),NULL,$user_id,$ip,$browsero)");
		$_SESSION['logid'] = mysql_insert_id();; // Initializing Session

		// check for successful store
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	public function logoutUser($id)
	{
		$id = $_SESSION['user_info']['id'];
		$ok = mysql_query("UPDATE user_info SET loggout_at = NOW() WHERE id = $id;");

		$result = mysql_query("SELECT loggout_at FROM user_info
                        WHERE $id=$id
                        ORDER BY loggout_at DESC
                        LIMIT 1;");
		// // check for successful store

		// echo($id);

		// $smt=mysql_fetch_array($result);
		// echo(print_r($smt,true));
		// echo(" doihduo".$smt['loggout_at']);

		// //$_SESSION['user_info']['loggout_at']=mysql_fetch_array($result['loggout_at']);
		// //echo($_SESSION['user_info']['loggout_at']);
		//die();
		if ( ! empty($smt)) {
			return $smt['loggout_at'];
		} else {
			return false;
		}
	}

	public function getUsers()
	{
		$all_users = mysql_query("SELECT * FROM users");
		return mysql_fetch_array($all_users);

	}

	public function getUserInfo($id)
	{
		$user_info = mysql_query("SELECT * FROM user_info WHERE user_id='$id'");
		$return = mysql_fetch_array($user_info);

	}

	/**
	 * Get user by email and password
	 */
	public function getUserByEmailAndPassword($email, $password)
	{
		$result = mysql_query("SELECT * FROM protereotitapp.users WHERE email = '$email'") or die(mysql_error());
		// check for result

		$no_of_rows = mysql_num_rows($result);

		if ($no_of_rows > 0) {

			$result = mysql_fetch_array($result);
			$salt = $result['salt'];
			$encrypted_password = $result['encrypted_password'];
			$hash = $this->checkhashSSHA($salt, $password);

			// check for password equality
			//echo(print_r($result,true));
			if ($encrypted_password == $hash) {
				// user authentication details are correct
				return $result;
			}
		} else {

			// user not found
			return false;
		}
	}

	/**
	 * Check user is existed or not
	 */
	public function isUserExisted($email)
	{
		$result = mysql_query("SELECT email from users WHERE email = '$email'");
		$no_of_rows = mysql_num_rows($result);
		if ($no_of_rows > 0) {
			// user existed
			return true;
		} else {
			// user not existed
			return false;
		}
	}

	/**
	 * Encrypting password
	 * @param password
	 * returns salt and encrypted password
	 */
	public function hashSSHA($password)
	{

		$salt = sha1(rand());
		$salt = substr($salt, 0, 10);
		$encrypted = base64_encode(sha1($password . $salt, true) . $salt);
		$hash = array("salt" => $salt, "encrypted" => $encrypted);
		return $hash;
	}

	/**
	 * Decrypting password
	 * @param salt , password
	 * returns hash string
	 */
	public function checkhashSSHA($salt, $password)
	{

		$hash = base64_encode(sha1($password . $salt, true) . $salt);

		return $hash;
	}


	function sec_session_start()
	{
		$session_name = 'sec_session_id';   // Set a custom session name
		$secure = SECURE;
		// This stops JavaScript being able to access the session id.
		$httponly = true;
		// Forces sessions to only use cookies.
		if (ini_set('session.use_only_cookies', 1) === false) {
			header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
			exit();
		}
		// Gets current cookies params.
		$cookieParams = session_get_cookie_params();
		session_set_cookie_params($cookieParams["lifetime"],
			$cookieParams["path"],
			$cookieParams["domain"],
			$secure,
			$httponly);
		// Sets the session name to the one set above.
		session_name($session_name);
		session_start();            // Start the PHP session
		session_regenerate_id(true);    // regenerated the session, delete the old one.
	}

	function checkbrute($user_id, $mysqli)
	{
		// Get timestamp of current time
		$now = time();

		// All login attempts are counted from the past 2 hours.
		$valid_attempts = $now - (2 * 60 * 60);

		if ($stmt = $mysqli->prepare("SELECT time
								 FROM login_attempts 
								 WHERE user_id = ? 
								AND time > '$valid_attempts'")
		) {
			$stmt->bind_param('i', $user_id);

			// Execute the prepared query.
			$stmt->execute();
			$stmt->store_result();

			// If there have been more than 5 failed logins
			if ($stmt->num_rows > 5) {
				return true;
			} else {
				return false;
			}
		}
	}

	//	function login_check($mysqli) {
	//		// Check if all session variables are set
	//		if (isset($_SESSION['user_id'],
	//							$_SESSION['username'],
	//							$_SESSION['login_string'])) {
	//
	//			$user_id = $_SESSION['user_id'];
	//			$login_string = $_SESSION['login_string'];
	//			$username = $_SESSION['username'];
	//
	//			// Get the user-agent string of the user.
	//			$user_browser = $_SERVER['HTTP_USER_AGENT'];
	//
	//			if ($stmt = $mysqli->prepare("SELECT password
	//										  FROM members
	//										  WHERE id = ? LIMIT 1")) {
	//				// Bind "$user_id" to parameter.
	//				$stmt->bind_param('i', $user_id);
	//				$stmt->execute();   // Execute the prepared query.
	//				$stmt->store_result();
	//
	//				if ($stmt->num_rows == 1) {
	//					// If the user exists get variables from result.
	//					$stmt->bind_result($password);
	//					$stmt->fetch();
	//					$login_check = hash('sha512', $password . $user_browser);
	//
	//					if ($login_check == $login_string) {
	//						// Logged In!!!!
	//						return true;
	//					} else {
	//						// Not logged in
	//						return false;
	//					}
	//				} else {
	//					// Not logged in
	//					return false;
	//				}
	//			} else {
	//				// Not logged in
	//				return false;
	//			}
	//		} else {
	//			// Not logged in
	//			return false;
	//		}
	//	}
	function esc_url($url)
	{

		if ('' == $url) {
			return $url;
		}

		$url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);

		$strip = array('%0d', '%0a', '%0D', '%0A');
		$url = (string)$url;

		$count = 1;
		while ($count) {
			$url = str_replace($strip, '', $url, $count);
		}

		$url = str_replace(';//', '://', $url);

		$url = htmlentities($url);

		$url = str_replace('&amp;', '&#038;', $url);
		$url = str_replace("'", '&#039;', $url);

		if ($url[0] !== '/') {
			// We're only interested in relative links from $_SERVER['PHP_SELF']
			return '';
		} else {
			return $url;
		}
	}

	//Ticket things
	public function checkPlace($google_place_id)
	{
		$sql = "SELECT id, google_place_id, confirmed FROM protereotitapp.places WHERE google_place_id = '$google_place_id'";

		$response = mysql_query($sql);
		if (mysql_num_rows($response) == 0) {
			syslog(LOG_DEBUG, "Check Place return false");
			return false;
		}

		$resdata = mysql_fetch_array($response);

		syslog(LOG_DEBUG, "checkPlace " . print_r($resdata['google_place_id'], true));
		syslog(LOG_DEBUG, "checkPlace " . print_r($resdata['confirmed'], true));

		if ($resdata['confirmed'] == 1) {
			return true;
		} else {
			return false;
		}
	}

	public function addPlace($place)
	{

		//		syslog(LOG_DEBUG,"Place: ".print_r($place,true));
		mysql_set_charset("utf8");
		$sql =
			"
INSERT INTO protereotitapp.places
(google_place_id,name,lat,lon, telephone,type,attribute,website,confirmed)
VALUES(
		'{$place["google_place_id"]}',
		'{$place["name"]}',
		'{$place["lat"]}',
		'{$place["lon"]}',
		'{$place["telephone"]}',
		'{$place["type"]}',
		'{$place["attribute"]}',
		'{$place["website"]}',
		0);";

		$response = mysql_query($sql);
		//		syslog(LOG_DEBUG, "DB_Function : addPlace" . print_r($response, true));

		// check for successful store
		if ($response) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param $place_id
	 * @param $user_id
	 * @return bool OR Ticket['estimated_time','number','unique_code','expiration_date'
	 */
	public function book_ticket($place_id, $user_id)
	{
		if (isset($place_id) && ! empty($place_id) && isset($user_id) && ! empty($user_id)) {


			//		syslog(LOG_DEBUG, "book ticket function: " . $google_place_id . " user_id " . $user_id);
			$unique_code = uniqid('', true);
			//		syslog(LOG_DEBUG, "book ticket function: unique_code " . $unique_code );
			$datetime = new DateTime('tomorrow');
			$expiration_date = $datetime->format('Y-m-d H:i:s');
			//		syslog(LOG_DEBUG, "book ticket function: expiration_date " . $expiration_date );


			$max_number = "SELECT max(number) as number FROM protereotitapp.ticket WHERE place_id = '$place_id' ;";
			//		syslog(LOG_DEBUG, "book ticket function: Select sql " . $sql_select);
			$max_number_res = mysql_query($max_number);
			$max_number_resdata = mysql_fetch_array($max_number_res);
			//		syslog(LOG_DEBUG, "book ticket function: last number :" . print_r($resdata,true));
			$last_number = intval($max_number_resdata['number']);

			$number = $last_number + 1;
			//		syslog(LOG_DEBUG, "book ticket function: Int value last number augmented :" . $last_number);


			$average_time = "SELECT average_serve_time FROM protereotitapp.places WHERE id = '$place_id' ;";
			$average_time_res = mysql_query($average_time);
			$average_time_resdata = mysql_fetch_array($average_time_res);
			$average_time = $average_time_resdata['average_serve_time'];


			$shift_start = date('d-m-Y');
			$shift_start .= " 08:00";

			$shift_end = date('d-m-Y');
			$shift_end .= " 14:00";


			$minutes_to_add = ($last_number) * $average_time;
			//			echo $minutes_to_add;
			//			exit;
			$time = new DateTime($shift_start);
			$time->add(new DateInterval('PT' . intval($minutes_to_add) . 'M'));

			$estimated_time = $time->format('d-m-Y H:i');


			if (strtotime($estimated_time) > strtotime($shift_end)) {
				$ticket['estimated_time'] = -1;
			} else {
				$ticket['estimated_time'] = $estimated_time;
			}

			$ticket['average_time'] = $average_time;
			$ticket['number'] = $number;
			$ticket['unique_code'] = $unique_code;
			$ticket['expiration_date'] = $expiration_date;
			//		exit;

			if ($ticket['estimated_time'] == -1) {
				return $ticket;
			}
			$sql_insert = "INSERT INTO protereotitapp.ticket(user_id, expiration_date,place_id, unique_code, number, created_at) VALUES('$user_id', '$expiration_date', '$place_id', '$unique_code', '$number', NOW()) ;";
			//		syslog(LOG_DEBUG, "book ticket function: insert" . $sql_insert);
			$result = mysql_query($sql_insert);
			
			if ($ticket['estimated_time'] != -1) {
				$ticket_id = mysql_insert_id();
				$insert_estimated_time = "UPDATE protereotitapp.ticket SET estimated_time='$estimated_time' WHERE id = '$ticket_id';";
				//		syslog(LOG_DEBUG, "book ticket function: insert" . $sql_insert);
				$ok = mysql_query($insert_estimated_time);

			}

			// check for successful store

			if ($result) {
				// get user details
				return $ticket;
			} else {
				return false;
			}


		} else {
			return false;
		}
	}

	public function get_google_place_id($google_place_id)
	{

		$place_id = "SELECT id FROM protereotitapp.places WHERE google_place_id =  '$google_place_id' ;";
		$place_id_res = mysql_query($place_id);
		$place_id_resdata = mysql_fetch_array($place_id_res);
		$place_id = $place_id_resdata['id'];

		return $place_id;
	}
}

?>