<?php

  require('connect.php');
  include ('/Applications/XAMPP/xamppfiles/htdocs/emotweets/php-nlp-tools-master/php-nlp-tools-master/autoloader.php');
  use \NlpTools\Tokenizers\WhitespaceTokenizer;
  use \NlpTools\Tokenizers\RegexTokenizer;



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
  $query = "SELECT * FROM tweets WHERE tweetID = 2512";
  $result = mysql_query($query);

  while($r = mysql_fetch_array($result)) {

    echo $r['tweetID'] . "<br>"; // WORKS
    $cleanedTweet = cleanTweets($tweet);
    $tokenized = tok(strtolower($cleanedTweet)); // WORKS
    $tokenized = iterateClean($tokenized);
    $tokenized = checkNegation($tokenized);
    tallyStore($tokenized, $r['sentiment']);

    //displayVocab();

  }
  echo "end";

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

  // HANDLES NEGATION
  function checkNegation($tokenized) {

    $total = count($tokenized);
    $negate = 0;

    for($i = 0; $i < $total; $i++) {

      $negate = searchNeg($tokenized[$i]);

      if($negate == 1) {
        // ADD NEG INDICATOR

        $tokenized = addNeg($tokenized, $i);

        return $tokenized;
      }


    }

    return $tokenized;


  }

  function checkStart($word, $start) {

    $length = strlen($word);
    return (substr($word, 0, $length) === $start);

  }

  function searchNeg($token) {

    $neg = array("don't", "won't", "can't", "not", "didn't", "hadn't", "hasn't", "haven't", "wouldn't", "aren't", "couldn't");

    $total = count($neg);

    for($i = 0; $i < $total; $i++) {

      if($token == $neg[$i]){
        return 1;
      }

    }

    return 0;


  }

  function addNeg($tokenized, $i) {



    $n = "NOT_";
    $total = count($tokenized);
    for($c = $i+1; $c < $total; $c++) {

      $str = $tokenized[$c];
      $tokenized[$c] = "{$n}{$str}";

    }

    return $tokenized;

  }

  // ADDS NEW WORD TO VOCAB
  function addToVocab($newWord, $class) {

    if($class == "Positive") {

      $p = 1;
      $n = 0;

    }
    elseif($class == "Negative") {

      $p = 0;
      $n = 1;

    }

    $query = 'INSERT INTO vocab values("' . $newWord . '", ' . $p . ', ' . $n . ');';
    mysql_query($query);
    mysql_error();

  }

  // UPDATES VOCAB IF WORD ALREADY EXISTS
  function updateVocab($oldWord, $class, $oldTally) {

    //echo "update " . $oldWord . "<br>";

    $oldTally++;

    if($class == "Positive") {

      $query = "UPDATE vocab SET positiveCount = " . $oldTally . " WHERE word = '" . $oldWord . "'";

    }
    elseif($class == "Negative") {

      $query = "UPDATE vocab SET negativeCount = " . $oldTally . " WHERE word = '" . $oldWord . "'";

    }

    mysql_query($query);


  }

  // DISPLAYS ALL VOCAB CONTENTS
  function displayVocab() {

    $query = "SELECT * FROM vocab;";
    $result = mysql_query($query);

    while($r = mysql_fetch_array($result)) {

      echo $r['word'] . " " . $r['positiveCount'] . " " . $r['negativeCount'] . "<br>";

    }

  }

  // TOKENIZE
  function tok($str) {

    //$spaceTok = new WhitespaceTokenizer();
    $regexTok = new RegexTokenizer(array(

      array("/\s+/"," "),
      array("/'(m|ve|d|s|re)/", " '\$1"),
      "/\,/",
      "/\./",
      "/\?/",
      "/\!/",
      "/\(/",
      "/\)/",
      "/\&/",
      "/\-/",
      "/\//",
      "/:/",
      "/;/",
      "/\^/",
      "/\$/",
      "/\"/",
      "/ /"

    ));
    //$x = $spaceTok->tokenize($str);
    $x = $regexTok->tokenize($str);

    return $x;

  }

  function clean($word) {

    $word = preg_replace('/[^a-z0-9]/', '', $word);
    $word = preg_replace('/[\*]+/', '', $word);
    return trim($word, '-');

  }

  function iterateClean($tokens) {

    $total = count($tokens);

    for($i = 0 ; $i < $total; $i++){

      $tokens[$i] = clean($tokens[$i]);

    }

    return $tokens;
  }

  function checkPlural($word) {

    $result = exec('python getSingular.py ' . escapeshellarg(json_encode($word)));

    $resultData = json_decode($result, true);

    return $resultData;

  }

  // TALLY AND STORE
  function tallyStore($t, $class) {

    $totalTokens = count($t);

    for($i = 0; $i < $totalTokens; $i++) {

        $currentToken = $t[$i];

        $results = getAllVocab();
        $totalVocab = countVocab();

        $currentToken = checkPlural($currentToken);

        if(checkStart($currentToken,"'")) {
          $currentToken = substr($currentToken, 1, strlen($currentToken));
          echo $currentToken;
        }

        if(preg_match('/[0-9]+/', $currentToken) || $currentToken == " " || $currentToken == "" || $currentToken == "'" || $currentToken == "'m" || $currentToken == "'ve" || $currentToken == "'d" || $currentToken == "'s" || $currentToken == "'re") {

          // do nothing;
          //echo $currentToken;

        }
        else {

          $inVocab = 0;
          if($totalVocab == 0) {
            // EMPTY VOCAB, ADD ALL TOKENS
            addToVocab($currentToken, $class);

          }
          else {

            // CHECK AND COMPARE TO VOCAB CONTENTS
            while($r = mysql_fetch_array($results)) {


              if($currentToken == $r['word']) {

                // MATCH, UPDATE FOR CLASS;
                //echo "match, update " . $currentToken . "<br>";
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

}

  function cleanTweets($tweet) {
    //Regular Expressions
    $mention = "/(@\w+)/";
    $hashtags = "/(#\w+)/";
    $links = "/(?:https?:\/\/)?(?:[\w]+\.)([a-zA-Z\.]{2,6})([\/\w\.-]*)*\/?/";
    $smile = "#(^|\W)(\>\:\]|\:-\)|\:\)|\:o\)|\:\]|\:3|\:c\)|\:\>|\=\]|8\)|\=\)|\:\}|\:\^\))($|\W)#";
    $laugh = "#(^|\W)(\>\:D|\:-D|\:D|8-D|x-D|X-D|\=-D|\=D|\=-3|8-\)|xD|XD|8D|\=3)($|\W)#";
    $sad = "#(^|\W)(\>\:\[|\:-\(|\:\(|\:-c|\:c|\:-\<|\:-\[|\:\[|\:\{|\>\.\>|\<\.\<|\>\.\<)($|\W)#";
    $wink = "#(^|\W)(\>;\]|;-\)|;\)|\*-\)|\*\)|;-\]|;\]|;D|;\^\))($|\W)#";
    $tongue = "#(^|\W)(\>\:P|\:-P|\:P|X-P|x-p|\:-p|\:p|\=p|\:-Þ|\:Þ|\:-b|\:b|\=p|\=P|xp|XP|xP|Xp)($|\W)#";
    $surprise = "#(^|\W)(\>\:o|\>\:O|\:-O|\:O|°o°|°O°|\:O|o_O|o\.O|8-0)($|\W)#";
    $annoyed = "#(^|\W)(\>\:\\|\>\:/|\:-/|\:-\.|\:\\|\=/|\=\\|\:S|\:\/)($|\W)#";
    $cry = "#(^|\W)(\:'\(|;'\()($|\W)#";

    $tweet = preg_replace($mention, "", $tweet);
    $tweet = preg_replace($links, "", $tweet);
    $tweet = preg_replace($laugh, "", $tweet);
    $tweet = preg_replace($smile, "", $tweet);
    $tweet = preg_replace($sad, "", $tweet);
    $tweet = preg_replace($wink, "", $tweet);
    $tweet = preg_replace($tongue, "", $tweet);
    $tweet = preg_replace($surprise, "", $tweet);
    $tweet = preg_replace($annoyed, "", $tweet);
    $tweet = preg_replace($cry, "", $tweet);
    $tweet = preg_replace($hashtags, "", $tweet);

    return $tweet
  }

?>
