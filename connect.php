<?php

	// CONNECTS TO DB

	$host = "localhost";
	$username = "root";
	$pw = "";
	$db = "emotweets";

	$con = mysql_connect($host, $username, $pw);

	if(!$con) {

		die("Connection failed: " . mysqli_connect_error());
	}
	else {

		$d = mysql_select_db($db, $con);
		if(!$db) {

			die(mysql_error());

		}

	}

	//echo "Connected successfully";


?>
