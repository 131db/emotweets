<?php

  require 'connect.php';


  # NAIVE BAYES CLASSIFICATION IMPLEMENTATION

  /*
    Algorithm
    P(class) = (tweets | class) / no. of tweets
    P(word | class) = count(word, class) + 1/ count(total words | class) + |V|
  */


  # function for calculating the class prior for positive/negative
  function calcPrior($numOfClass, $totalTweets) {
    $prior = $numOfClass / $totalTweets;

    return $prior;
  }

  # function for calculating the word conditional probability in a given class
  function calcProb($wordCount, $totalWords, $vocabSize) {


    $probability = ($wordCount + 1) / ($totalWords + $vocabSize);

    return $probability;
  }

  # Calculates the probability of the tweet being classified as positive
  function calcPositiveNaiveBayes() {

    global $con;

    $queryTotalTweets = 'SELECT count(tweet) AS "totalTweets" FROM tweets';
    $resultTotalTweets = $con->query($queryTotalTweets);
    $total = $resultTotalTweets->fetch_assoc();


    $queryTotalPositive = 'SELECT count(sentiment) AS "positive" FROM tweets WHERE sentiment = "Positive"';
    $resultTotalPositive = $con->query($queryTotalPositive);
    $totalPositive = $resultTotalPositive->fetch_assoc();

    $classProbability = calcPrior($totalPositive["positive"], $total["totalTweets"]);

    $tokenizedTweet = array(
      "Chinese",
      "Chinese",
      "Chinese",
      "Tokyo",
      "Japan"
    );

    $arrayCount = array_count_values($tokenizedTweet); # key value pair on how many times the word has appeared in the tweet.

    foreach($arrayCount as $word => $count) {

        $queryWordCount = 'SELECT positiveCount FROM vocab WHERE word ="' . $word . '"'; # query for the number of times the word has appear in the given class

        $queryVocabSize = 'SELECT count(word) AS "total" FROM vocab'; # query for how many words are there in the vocabulary

        $queryTotalWords = 'SELECT SUM(positiveCount) AS "positve" FROM vocab'; # query for getting the sum of appearances of each word in the given class

        $result2 = $con->query($queryVocabSize);
        $result3 = $con->query($queryTotalWords);
        $totalWords = $result3->fetch_assoc();
        $vocabSize = $result2->fetch_assoc();
        $result = $con->query($queryWordCount);

        if($result->num_rows > 0) {
          $row = $result->fetch_assoc();

          $wordProb = calcProb($row["positiveCount"], $totalWords["positive"], $vocabSize["total"]);

          $classProbability *= pow($wordProb, $count);

        }else {
          echo "Query failed";
        }
    }

    return $classProbability;

  }

  # Calculates the probability of the tweet being classified as negative
  function calcNegativeNaiveBayes() {

    global $con;

    $queryTotalTweets = 'SELECT count(tweet) AS "totalTweets" FROM tweets';
    $resultTotalTweets = $con->query($queryTotalTweets);
    $total = $resultTotalTweets->fetch_assoc();
    

    $queryTotalNegative = 'SELECT count(sentiment) AS "negative" FROM tweets WHERE sentiment = "Negative"';
    $resultTotalNegative = $con->query($queryTotalNegative);
    $totalNegative = $resultTotalNegative->fetch_assoc();

    $classProbability = calcPrior($totalNegative["negative"], $total["totalTweets"]);

    $tokenizedTweet = array(
      "Chinese",
      "Chinese",
      "Chinese",
      "Tokyo",
      "Japan"
    );

    $arrayCount = array_count_values($tokenizedTweet); # key value pair on how many times the word has appeared in the tweet.

    foreach($arrayCount as $word => $count) {

        $queryWordCount = 'SELECT negativeCount FROM vocab WHERE word ="' . $word . '"'; # query for the number of times the word has appear in the given class

        $queryVocabSize = 'SELECT count(word) AS "total" FROM vocab'; # query for how many words are there in the vocabulary

        $queryTotalWords = 'SELECT SUM(negativeCount) AS "negative" FROM vocab'; # query for getting the sum of appearances of each word in the given class

        $result2 = $con->query($queryVocabSize);
        $result3 = $con->query($queryTotalWords);
        $totalWords = $result3->fetch_assoc();
        $vocabSize = $result2->fetch_assoc();
        $result = $con->query($queryWordCount);

        if($result->num_rows > 0) {
          $row = $result->fetch_assoc();

          $wordProb = calcProb($row["negativeCount"], $totalWords["negative"], $vocabSize["total"]);

          $classProbability *= pow($wordProb, $count);

        }else {
          echo "Query failed";
        }
    }

    return $classProbability;

  }

  /* compare the negative and positive probability value of the tweet
    returns "Positive" if positive is greater than negative and "Negative" if
    negative is greater than positive.
  */
  function compareValues($positiveValue, $negativeValue) {


    if($positiveValue > $negativeValue) {
      return "Positive";
    } elseif ($negativeValue > $positiveValue) {
      return "Negative";
    } else {
      return "Neutral";
    }

  }

  echo calcPositiveNaiveBayes() . "<br>";
  echo calcNegativeNaiveBayes() . "<br>";

  echo compareValues(calcPositiveNaiveBayes(), calcNegativeNaiveBayes());






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
