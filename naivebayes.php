<?php

  // NAIVE BAYES IMPLEMENTATION

  /*

    P(A|B) = (A & B) / B



  */

  function calcProb($AB, $B) {

    $p = ($AB / $B);

    return $p;

  }


  function writeProb($file, $class, $prob) {

    // write to file and add prob  or add to db

  }

  function getProbablities($file, $class) {

    // read from file
    $count = 0;

    while() {

      if($classFromFile == $class) {

        if($wordFromFile == $word) {

          $count++;

        }

      }

      $prob = calcProb($count, 2500);

      writeProb($file, $class, $prob);

    }

  }

  function classifyNew($newTweet) {

    $total = count($newTweet);

    for($i = 0; $i < $total; $i++) {

      // read from file

      if($wordFromBank == $newTweet[$i]) {

        // get prob from file

      }
      else {
        // new instance
      }

    }

  }


?>
