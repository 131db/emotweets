<?php

  require('connect.php');
  $file = fopen("wordsToCombine.csv", "r");
  $words = array();
  $words = fgetcsv($file);

  $total = count($words);

  $positive = false;
  $negative = false;

  for($c = 0; $c < $total; $c++) {

    //$query = "DELETE FROM vocab WHERE word = '" . $words[$c] . "'";
    //mysql_query($query);
    //$query = "DELETE FROM vocab WHERE word = 'NOT_" . $words[$c] . "'";
    //mysql_query($query);

    $withApos = $words[$c];
    $withoutApos = preg_replace('/[\']+/', '', $withApos);

    $apos = getWithApos($withApos);
    $noApos = getNoApos($withoutApos);
    $withAposPositive = $apos['positiveCount'];
    $withAposNegative = $apos['negativeCount'];
    $withoutAposNegative = $noApos['negativeCount'];
    $withoutAposPositive = $noApos['positiveCount'];

    $totals = addTotal($withAposPositive, $withAposNegative, $withoutAposNegative, $withoutAposPositive);

    updateDB($totals[0], $totals[1], $withoutApos);
    deleteWord($withApos);

  }

  function getWithApos($withApos) {

    $query = 'SELECT * FROM vocab WHERE word = "' . $withApos . '"';
    $result = $con->query($query);
    if($result->num_rows > 0) {

      if($r = $result->fetch_assoc()) {
        return $r;

      }

    }

  }

  function getNoApos($withoutApos) {

    // GET W/OUTAPOS VALUES AND SAVE
    $query = "SELECT * FROM vocab WHERE word = '" . $withoutApos . "'";
    $result = $con->query($query);

    if($result->num_rows > 0) {

      if($r = $result->fetch_assoc()) {
        //echo $r['word'] . "<br>";
        return $r;

      }


    }


  }

  function addTotal($withAposPositive, $withAposNegative, $withoutAposNegative, $withoutAposPositive) {

    $totalPositive = $withAposPositive + $withoutAposPositive;
    $totalNegative = $withAposNegative + $withoutAposNegative;
    $array = array();
    $array[0] = $totalPositive;
    $array[1] = $totalNegative;
    echo "Positive: " . $withAposPositive . " + " . $withoutAposPositive . " = " . $totalPositive . " | Negative: " . $withAposNegative . " + " . $withoutAposNegative . " = " . $totalNegative . "<br>";
    return $array;


  }

  function updateDB($totalPositive, $totalNegative, $word) {

    $query = "UPDATE vocab SET positiveCount = " . $totalPositive . ", negativeCount = " . $totalNegative . " WHERE word = '" . $word . "'";
    $result = $con->query($query);
    $x = $result->affected_rows;
    echo $x . "row/s affected";
  }

  function deleteWord($word) {

    $query = 'DELETE FROM vocab WHERE word = "' . $word . '"';
    $result = $con->query($query);
    $x = $result->affected_rows;
    echo $x . "row/s affected for " . $word;

  }

  fclose($file);

?>
