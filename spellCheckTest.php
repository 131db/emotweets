<?php

  $word = spellCheck($word);

  echo $word;

  function spellCheck($word) {

    $result = exec('python spellChecker.py ' . escapeshellarg(json_encode($word)));

    $txt = json_decode($result, true);

    $total = count($txt);
    $highestWord = $txt[0][0];
    $highestValue = $txt[0][1];

    for($i = 1; $i < $total; $i++) {

      if($txt[$i][1] > $highestValue) {

        $highestWord = $txt[$i][0];
        $highestValue = $txt[$i][1];

      }

    }

    return $highestWord;
  }

?>
