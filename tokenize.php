<?php

  include ('C:/xampp/htdocs/emotweets/php-nlp-tools-master/php-nlp-tools-master/autoloader.php');
  use \NlpTools\Tokenizers\WhitespaceTokenizer;

  $str = "Lorem ipsum sit dolor amet";

  $spaceTok = new WhitespaceTokenizer();
  $result = $spaceTok->tokenize($str);
  $total = count($result);


  for ($c = 0; $c < $total; $c++) {

    echo $result[$c] . "<br>";

  }

?>
