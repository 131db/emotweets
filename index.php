<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Twitter Sentimental Analysis</title>

    <!-- STYLESHEETS -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="bootstrap/css/main.css" rel="stylesheet" type="text/css">

  </head>
  <body>
    <!-- START CONTAINER-FLUID -->
    <div class="container">

      <!-- START ROW 1 -->
      <div class="row">

        <!-- START COL-XS-1 -->
        <div class="col-md-12">

          <!-- START SEARCH PANEL -->
          <div class="searchPanel">
            <form class="form-inline" action="index.html" method="post">
              <div class="form-group bigGroup">
              <input type="text" class="form-control bigform" name="name" id="hashtag" value="" placeholder="Enter hashtag here..">
              <button type="submit" class="btn btn-success bigbtn">GO</button>
			  </div>
            </form>
          <!-- END SEARCH PANEL -->
          </div>

        </div>
        <!-- START COL-XS-1 -->
      </div>
      <!-- END ROW 1 -->

		<br>

      <!-- START ROW 2 -->
      <div class="row">

        <!-- START COL-MD-6 -->
        <div class="col-md-5">

          <!-- START EMOTION GRAPH -->
          <div class="emotionGraph">

            <div class="panel panel-default">

              <div class="panel-body">

                <h2>Statistics</h2>
					      <p>Emotion graphs here</p>

				      </div>

			      </div>

          </div>
          <!-- END EMOTION GRAPH -->

        </div>
        <!-- END COL-MD-6 -->

        <!-- START COL-MD-6 -->
        <div class="col-md-7">
          <!-- START TWITTER FEED -->
          <div class="twitterFeed">
            <!-- START TABLE -->
            <table class="table table-hover">

              <tr>
                <th>Sentiment</th>
                <th>Tweet</th>

				      </tr>

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

                  $sentiment = 'Positive'; // CHANGE THIS WHEN SENTIMENT ANALYSIS IS APPLIED

                  echo '<tr>

                    <td>
                      <br><br>
                      <span class="label label-success">' . $sentiment . '</span>
                    </td>

    					      <td>

                      <h3>' . $result->user->screen_name . '</h3>
    						      <p>' . $result->text . '</p>

    					      </td>

    				      </tr>';

                  //echo $result->user->screen_name . ": " . $result->text . "\n";

                }

              ?>

			      </table>
            <!-- END TABLE -->

          </div>
          <!-- END TWITTER FEED -->
        </div>
        <!-- END COL-MD-6 -->

      </div>
      <!-- END ROW 2 -->

    </div>
    <!-- END CONTAINER FLUID -->

    <footer>

    </footer>

  </body>
</html>
