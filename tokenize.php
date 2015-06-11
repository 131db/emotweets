<?php

  require('connect.php');
  include ('C:/xampp/htdocs/emotweets/php-nlp-tools-master/php-nlp-tools-master/autoloader.php');
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

  // VOCAB OBJECT
  class Word {

    public $text;
    public $pos;
    public $neg;

  }

  // RETRIEVE TWEETS FROM DB

  $query = "SELECT * FROM tweets LIMIT 1"; // FOR TESTING, REMOVE LIMIT 1 TO GET ALL
  $result = mysql_query($query);

  $vocab = array();

  while($r = mysql_fetch_array($result)) {

    $tokenized = tok($r['tweet']);
    tallyStore($tokenized, $r['sentiment']);


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
    $totalVocab = count($vocab);

    for($i = 0; $i < $totalTokens; $i++) {

      // CHECK IF ALREADY IN VOCAB
      for($c = 0; $c < $totalVocab; $c++) {

        $inVocab = 0;
        $data = new Word();
        $data = $vocab[$c];

        if($t == $data->text) {

          // ALREADY IN VOCAB
          if($class == "Positive") {

            $data->pos = $data->pos + 1;

          }
          elseif($class == "Negative") {

            $data->neg = $data->neg + 1;

          }

          $vocab[$c] = $data;
          $inVocab = 1;

        }
        // END OUTER IF

        if($inVocab == 0) {

          // NOT IN VOCAB
          $obj = new Word();
          if($class == "Positive") {

            $obj->pos = 1;
            $obj->neg = 0;

          }
          elseif($class == "Negative") {

            $obj->pos = 0;
            $obj->neg = 1;

          }

          $vocab[$totalVocab] = $obj;
          $totalVocab++;

        }

      }
      // END INNER FOR LOOP

    }
    // END OUTER FOR LOOP

  }

?>
