<?php

  require 'connect.php';


  // NAIVE BAYES IMPLEMENTATION

  /*
    P(class) = (tweets | class) / no. of tweets
    P(A|B) = (A & B) / B
    P(word | class) = count(word, class) + 1/ count(total words | class) + |V|



  */
  $chinese = 3;
  $japan = 1;
  $totalTweets = 4;
  $totalWords = 8;
  $vocabSize = 6;
  $chineseC = 5;
  $chineseJ = 1;
  $tokyoC = 0;
  $japanC = 0;
  $tokyoJ = 1;
  $japanJ = 1;

  function calcPrior($numOfClass, $totalTweets) {
    $prior = $numOfClass / $totalTweets;

    return $prior;
  }

  function calcProb($wordCount, $totalWords, $vocabSize) {
    echo $wordCount . "<br>";

    $probability = ($wordCount + 1) / ($totalWords + $vocabSize);

    return $probability;
  }

  // function for calculating the probability of a tweet be positive/negative using naive bayes algorithm
  function calcNaiveBayes($classPrior) {

    global $con;

    $classProbability = $classPrior;
    $tokenizedTweet = array(
      "Chinese",
      "Chinese",
      "Chinese",
      "Tokyo",
      "Japan"
    );

    $arrayCount = array_count_values($tokenizedTweet); // key value pair on how many times the word has appeared in the tweet.

    foreach($arrayCount as $word => $count) {

        $queryWordCount = 'SELECT chinese FROM wordbank WHERE word ="' . $word . '"'; //query for the number of times the word has appear in the given class

        $queryVocabSize = 'SELECT count(word) AS "total" FROM wordbank'; //query for how many words are there in the vocabulary

        $queryTotalWords = 'SELECT SUM(chinese) AS "chinese" FROM wordbank'; // query for getting the sum of appearances of each word in the given class
        
        $result2 = $con->query($queryVocabSize);
        $result3 = $con->query($queryTotalWords);
        $totalWords = $result3->fetch_assoc();
        $vocabSize = $result2->fetch_assoc();
        $result = $con->query($queryWordCount);

        if($result->num_rows > 0) {
          $row = $result->fetch_assoc();

          $wordProb = calcProb($row["chinese"], $totalWords["chinese"], $vocabSize["total"]);

          $classProbability *= pow($wordProb, $count);

        }else {
          echo "Query failed";
        }
    }

    return $classProbability;

  }

  $chinesePrior = calcPrior($chinese, $totalTweets);
  $japanPrior = calcPrior($japan, $totalTweets);

  echo calcNaiveBayes($chinesePrior) . "<br>";






  // function writeProb($file, $class, $prob) {
  //
  //   // write to file and add prob  or add to db
  //
  // }
  //
  // function getProbablities($file, $class) {
  //
  //   // read from file
  //
  //   $count = 0;
  //
  //   while() {
  //
  //     if($classFromFile == $class) {
  //
  //       if($wordFromFile == $word) {
  //
  //         $count++;
  //
  //       }
  //
  //     }
  //
  //     $prob = calcProb($count, 2500);
  //
  //     writeProb($file, $class, $prob);
  //
  //   }
  //
  // }
  //
  // function classifyNew($newTweet) {
  //
  //   $total = count($newTweet);
  //
  //   for($i = 0; $i < $total; $i++) {
  //
  //     // read from file
  //
  //     if($wordFromBank == $newTweet[$i]) {
  //
  //       // get prob from file
  //
  //     }
  //     else {
  //       // new instance
  //     }
  //
  //   }
  //
  // }


?>
