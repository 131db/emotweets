<html>

<head>
  <link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="bootstrap/css/main.css" rel="stylesheet" type="text/css">
</head>

<body>

  <?php

    require ('naivebayes.php');
    require ('tokenize.php');
    require ('ouath/autoload.php');
    require_once ('ouath/src/TwitterOAuth.php');
    use Abraham\TwitterOAuth\TwitterOAuth;

    define('CONSUMER_KEY', 'lIHWDWkKCzjthlWYzhvcvvZ8H');
    define('CONSUMER_SECRET', 'gvF4DvAvWsnBndtIw3emMJvu6pTbuD0U6yVQSVqpQneaqZRnjY');
    define('ACCESS_TOKEN', '507359545-VeXYNUAxRg2O5HCHBktXLFYsXb61dXxGNN6OCP24');
    define('ACCESS_TOKEN_SECRET', 'lLzjhH3P3YEsimf2slfYKpn0geZK3bRAW6fLJb8sNrUPx');


    function search(array $query) {
      $toa = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
      return $toa->get('search/tweets', $query);
    }

    if(isset($_GET['q'])) {
      $search = $_GET['q'];
    }else {
      $search = 'food';
    }

    $query = array(
      "q"=>$search,
      "lang"=>"en"
      //"count"=>10
      );

    $results = search($query);

    echo "<table class='table table-hover'>

      <tr>
        <th>Sentiment</th>
        <th>Tweet</th>

      </tr>

      <tr>";

    foreach ($results->statuses as $result) {

      // echo $result->user->screen_name . ": " . $result->text . "<br>";
        $cleanedTweet = cleanTweets($result->text);
        $tokenized = tok(strtolower($cleanedTweet)); // WORKS
        $tokenized = iterateClean($tokenized);
        $tokenized = checkNegation($tokenized);

        // echo $result->text . "<br>";
        // echo compareValues(calcPositiveNaiveBayes($tokenized), calcNegativeNaiveBayes($tokenized));
        // echo "<br><br>";



          echo "<td>
                <br><br>";
          if(compareValues(calcPositiveNaiveBayes($tokenized), calcNegativeNaiveBayes($tokenized)) == "Positive") {
                echo "<span class='label label-success'>" . compareValues(calcPositiveNaiveBayes($tokenized), calcNegativeNaiveBayes($tokenized)) . "</span>";
              }else if (compareValues(calcPositiveNaiveBayes($tokenized), calcNegativeNaiveBayes($tokenized)) == "Negative") {
                echo "<span class='label label-danger'>" . compareValues(calcPositiveNaiveBayes($tokenized), calcNegativeNaiveBayes($tokenized)) . "</span>";
              } else if (compareValues(calcPositiveNaiveBayes($tokenized), calcNegativeNaiveBayes($tokenized)) == "Neutral") {
                echo "<span class='label label-default'>" . compareValues(calcPositiveNaiveBayes($tokenized), calcNegativeNaiveBayes($tokenized)) . "</span>";
              }
            echo "</td>";

            echo "<td>";

                  echo "<h3>" . $result->user->screen_name . "</h3>";
                  echo "<p>" . $result->text . "</p>";


            echo "</td>
              </tr>";

    }

    echo "</table>";

    //echo $results;

  ?>


</body>

</html>
