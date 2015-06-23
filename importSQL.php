<?php

  require('connect.php');

  // IMPORT SQL TO MAKE DB
  $sqlFile = "emotweets.sql";
  $mysqlDB = "emotweets";

  $mysql_select_db($mysqlDB) or die mysql_error();

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

?>
