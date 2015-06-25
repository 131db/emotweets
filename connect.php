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
		if(!$d) {

			die(mysql_error());
			$temp = "";

		  $text = file($sqlFile);

		  foreach ($text as $line) {

		    if(substr($line, 0, 2) == "--" || $line == "")
		      continue;

		    $temp .= $line;

		    if(substr(trim($line), -1, 1) == ";") {

		      mysql_query($temp) or print ("Error");
		      $temp = "";

		    }

		  }

			$d = mysql_select_db($db, $con);
			die(mysql_error());
		}
		else {

			//echo "Connected successfully";

		}


	}

	//echo "Connected successfully";


?>
