<?php

require_once('twitter_proxy.php');

// Twitter OAuth Config options
$oauth_access_token = '507359545-VeXYNUAxRg2O5HCHBktXLFYsXb61dXxGNN6OCP24';
$oauth_access_token_secret = 'lLzjhH3P3YEsimf2slfYKpn0geZK3bRAW6fLJb8sNrUPx';
$consumer_key = 'lIHWDWkKCzjthlWYzhvcvvZ8H';
$consumer_secret = 'gvF4DvAvWsnBndtIw3emMJvu6pTbuD0U6yVQSVqpQneaqZRnjY';
$user_id = '78884300';
$screen_name = 'parallax';
$count = 5;

$twitter_url = 'statuses/user_timeline.json';
$twitter_url .= '?user_id=' . $user_id;
$twitter_url .= '&screen_name=' . $screen_name;
$twitter_url .= '&count=' . $count;

// Create a Twitter Proxy object from our twitter_proxy.php class
$twitter_proxy = new TwitterProxy(
	$oauth_access_token,			// 'Access token' on https://apps.twitter.com
	$oauth_access_token_secret,		// 'Access token secret' on https://apps.twitter.com
	$consumer_key,					// 'API key' on https://apps.twitter.com
	$consumer_secret,				// 'API secret' on https://apps.twitter.com
	$user_id,						// User id (http://gettwitterid.com/)
	$screen_name,					// Twitter handle
	$count							// The number of tweets to pull out
);

$query = array(
	"q"=>"food",
	//"lang"=>"en"
	);

// Invoke the get method to retrieve results via a cURL request
$tweets = $twitter_proxy->get('search/tweets',$query);

echo $tweets;

?>