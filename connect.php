<?php

	// CONNECTS TO DB

	$host = "localhost";
	$username = "root";
	$pw = "";
	$db = "emotweets";

	$con = new mysqli($host, $username, $pw, $db);

	if($con->connect_error) {
		die("Connection failed: " . $con->connect_error);
	}

?>
