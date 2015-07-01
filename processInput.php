<html>

<head></head>

<body>

  <?php

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
      //"lang"=>"en"
      );

    $results = search($query);

    foreach ($results->statuses as $result) {

      echo $result->user->screen_name . ": " . $result->text . "<br>";

    }

    //echo $results;

  ?>


</body>

</html>
