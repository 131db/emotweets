<?php

  require 'connect.php';
  // include 'tokenize.php';


  # NAIVE BAYES CLASSIFICATION IMPLEMENTATION

  /*
    Algorithm
    P(class) = (tweets | class) / no. of tweets
    P(word | class) = count(word, class) + 1/ count(total words | class) + |V|
  */
  // $trueNegative = 0;
  // $truePositive = 0;
  //
  // $query = "SELECT * FROM test_set";
  // $result = $con->query($query);
  //
  // while($r = $result->fetch_assoc()) {
  //
  //   //echo $r['tweetID'] . "<br>"; // WORKS
  //   $cleanedTweet = cleanTweets($r["tweet"]);
  //   //echo $cleanedTweet . "<br>";
  //   $tokenized = tok(strtolower($cleanedTweet)); // WORKS
  //   $tokenized = iterateClean($tokenized);
  //   $tokenized = checkNegation($tokenized);
  //
  //   if((compareValues(calcPositiveNaiveBayes($tokenized), calcNegativeNaiveBayes($tokenized))) == $r["sentiment"]) {
  //     if($r["sentiment"] == "Negative")
  //       $trueNegative++;
  //     else if($r["sentiment"] == "Positive")
  //       $truePositive++;
  //   }



    // for($i = 0; $i < count($tokenized); $i++) {
    //   echo $tokenized[$i];
    //   echo "<br>";
    // }
  // }

  // echo "Negative: " . $trueNegative . "<br>";
  // echo "Positive: " . $truePositive . "<br>";


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
  function calcPositiveNaiveBayes($tokenizedTweet) {


    global $con;

    $queryTotalTweets = 'SELECT count(tweet) AS "totalTweets" FROM tweets';
    $resultTotalTweets = $con->query($queryTotalTweets);
    $total = $resultTotalTweets->fetch_assoc();
    //echo $total["totalTweets"] . "<br>";


    $queryTotalPositive = 'SELECT count(sentiment) AS "positive" FROM tweets WHERE sentiment = "Positive"';
    $resultTotalPositive = $con->query($queryTotalPositive);
    $totalPositive = $resultTotalPositive->fetch_assoc();
    //echo $totalPositive["positive"] . "<br>";

    $positiveProbability = calcPrior($totalPositive["positive"], $total["totalTweets"]);
    //echo $positiveProbability . "<br>";

    // $tokenizedTweet = array(
    //   "today",
    //   "i",
    //   "am",
    //   "a",
    //   "sewing",
    //   "genius"
    // );

    $arrayCount = array_count_values($tokenizedTweet); # key value pair on how many times the word has appeared in the tweet.

    foreach($arrayCount as $word => $count) {



        $queryWordCount = 'SELECT positiveCount FROM vocab WHERE word ="' . $word . '"'; # query for the number of times the word has appear in the given class

        $queryVocabSize = 'SELECT count(word) AS "total" FROM vocab'; # query for how many words are there in the vocabulary

        $queryTotalWords = 'SELECT SUM(positiveCount) AS "positive" FROM vocab WHERE 1'; # query for getting the sum of appearances of each word in the given class

        $result2 = $con->query($queryVocabSize);
        $result3 = $con->query($queryTotalWords);
        $totalWords = $result3->fetch_assoc();
        $vocabSize = $result2->fetch_assoc();
        $result = $con->query($queryWordCount);


        $row = $result->fetch_assoc();

        $wordProb = calcProb($row["positiveCount"], $totalWords["positive"], $vocabSize["total"]);
        // echo $word . " = ";
        // echo $wordProb . "<br>";
        $positiveProbability *= pow($wordProb, $count);


    }
    //echo "Positive = " . $positiveProbability . "<br>";
    return $positiveProbability;

  }

  # Calculates the probability of the tweet being classified as negative
  function calcNegativeNaiveBayes($tokenizedTweet) {

    global $con;

    $queryTotalTweets = 'SELECT count(tweet) AS "totalTweets" FROM tweets';
    $resultTotalTweets = $con->query($queryTotalTweets);
    $total = $resultTotalTweets->fetch_assoc();
    //echo $total["totalTweets"] . "<br>";


    $queryTotalNegative = 'SELECT count(sentiment) AS "negative" FROM tweets WHERE sentiment = "Negative"';
    $resultTotalNegative = $con->query($queryTotalNegative);
    $totalNegative = $resultTotalNegative->fetch_assoc();
    //echo $totalNegative["negative"] . "<br>";

    $negativeProbability = calcPrior($totalNegative["negative"], $total["totalTweets"]);
    //echo $negativeProbability . "<br>";

    // $tokenizedTweet = array(
    //   "today",
    //   "i",
    //   "am",
    //   "a",
    //   "sewing",
    //   "genius"
    // );

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

        // if($result->num_rows > 0) {
          $row = $result->fetch_assoc();

          $wordProb = calcProb($row["negativeCount"], $totalWords["negative"], $vocabSize["total"]);
          // echo $word . " = ";
          // echo $wordProb . "<br>";
          $negativeProbability *= pow($wordProb, $count);

        // }else {
        //   echo "Query failed";
        // }
    }

    //echo "Negative = " . $negativeProbability;
    return $negativeProbability;

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


?>
