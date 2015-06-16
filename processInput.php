<?php

  require ('C:\xampp\htdocs\emotweets\ouath\autoload.php');
  require_once ('C:\xampp\htdocs\emotweets\ouath\src\TwitterOAuth.php');
  use Abraham\TwitterOAuth\TwitterOAuth;

  define('CONSUMER_KEY', 'lIHWDWkKCzjthlWYzhvcvvZ8H');
  define('CONSUMER_SECRET', 'gvF4DvAvWsnBndtIw3emMJvu6pTbuD0U6yVQSVqpQneaqZRnjY');
  define('ACCESS_TOKEN', '507359545-VeXYNUAxRg2O5HCHBktXLFYsXb61dXxGNN6OCP24');
  define('ACCESS_TOKEN_SECRET', 'lLzjhH3P3YEsimf2slfYKpn0geZK3bRAW6fLJb8sNrUPx');
  $toa = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);

  $query = array("q"=>"#Marvel");

  $results = $toa->get('search/tweets', $query);

  foreach ($results->statuses as $result) {

    echo $result->user->screen_name . ": " . $result->text . "\n";

  }

?>
