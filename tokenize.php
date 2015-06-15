<?php

  require('connect.php');
  include ('/Applications/XAMPP/xamppfiles/htdocs/emotweets/php-nlp-tools-master/php-nlp-tools-master/autoloader.php');
  use \NlpTools\Tokenizers\WhitespaceTokenizer;

  /*  SAMPLE TOKENIZER

  $str = "Lorem ipsum sit dolor amet";

  $spaceTok = new WhitespaceTokenizer();
  $result = $spaceTok->tokenize($str);
  $total = count($result);

  for ($c = 0; $c < $total; $c++) {

    echo $result[$c] . "<br>";

  }
  */

  // RETRIEVE TWEETS FROM DB

  $query = "SELECT * FROM tweets LIMIT 3"; // FOR TESTING, REMOVE LIMIT 1 TO GET ALL
  $result = mysql_query($query);

  while($r = mysql_fetch_array($result)) {

    //echo $r['tweet'] . " " . $r['sentiment'] . "<br>"; // WORKS

    $tokenized = tok($r['tweet']); // WORKS
    tallyStore($tokenized, $r['sentiment']);
    //displayVocab();

  }

  function getAllVocab() {

    $query = "SELECT * FROM vocab"; // FOR TESTING, REMOVE LIMIT 1 TO GET ALL
    $result = mysql_query($query);

    return $result;

  }

  function countVocab() {

    $query = "SELECT COUNT(word) AS 'total' FROM vocab"; // FOR TESTING, REMOVE LIMIT 1 TO GET ALL
    $result = mysql_query($query);

    $r = mysql_fetch_array($result);

    return $r['total'];

  }

  function addToVocab($newWord, $class) {

    if($class == "Positive") {

      $p = 1;
      $n = 0;

    }
    elseif($class == "Negative") {

      $p = 0;
      $n = 1;

    }

    $query = "INSERT INTO vocab values('" . $newWord . "', " . $p . ", " . $n . ");";
    mysql_query($query);

  }

  function updateVocab($oldWord, $class, $oldTally) {

    if($class == "Positive") {

      $query = "UPDATE vocab SET positiveCount = " . $oldTally . " WHERE word = " . $oldWord . "'";

    }
    elseif($class == "Negative") {

      $query = "UPDATE vocab SET negativeCount = " . $oldTally . " WHERE word = '" . $oldWord . "'";

    }

    mysql_query($query);


  }

  function displayVocab() {

    $query = "SELECT * FROM vocab;";
    $result = mysql_query($query);

    while($r = mysql_fetch_array($result)) {

      echo $r['word'] . " " . $r['positiveCount'] . " " . $r['negativeCount'] . "<br>";

    }

  }

  // TOKENIZE
  function tok($str) {

    $spaceTok = new WhitespaceTokenizer();
    $x = $spaceTok->tokenize($str);

    return $x;

  }


  // TALLY AND STORE
  function tallyStore($t, $class) {

    $totalTokens = count($t);

    for($i = 0; $i < $totalTokens; $i++) {

        $currentToken = $t[$i];

        $results = getAllVocab();
        $totalVocab = countVocab();

        if($totalVocab == 0) {
          // EMPTY VOCAB, ADD ALL TOKENS
          addToVocab($currentToken, $class);

        }
        else {

          $inVocab = 0;
          // CHECK AND COMPARE TO VOCAB CONTENTS
          while($r = mysql_fetch_array($results)) {

            if($currentToken == $r['word']) {

              // MATCH, UPDATE FOR CLASS;

              $inVocab = 1;
              if($class == "Positive") {

                updateVocab($currentToken, $class, $r['positiveCount']);

              }
              else if($class == "Negative") {

                updateVocab($currentToken, $class, $r['negativeCount']);

              }


            }

          }

          if($inVocab == 0) {

            addToVocab($currentToken, $class);

          }

        }

  }

}

?>
