<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Twitter Sentimental Analysis</title>

    <!-- STYLESHEETS -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
	  <link href="bootstrap/css/main.css" rel="stylesheet" type="text/css">

    <!-- SCRIPTS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <script>
    $(document).ready(function(){

        $("button").click(function(){
          var tag = $("#hashtag").val();

            $.ajax({

              url: "processInput.php",
              data: {q: tag},
              success: function(result){
                $("#div1").html(result);
            }});
        });
    });
    </script>


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
            <!--<form class="form-inline">-->
              <div class="form-group bigGroup">
              <input type="text" class="form-control bigform" name="name" id="hashtag" value="" placeholder="Enter hashtag here..">
              <button id="submit" class="btn btn-success bigbtn">GO</button>
			  </div>
            <!--</form>-->

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

              <tr>

                    <td>
                      <br><br>
                      <span class="label label-success">Sentiment</span>
                    </td>

    					      <td>
                      <!--<p>Loading Tweets......</p>-->
                      <div id="div1"><h2>Let jQuery AJAX Change This Text</h2></div>

    					      </td>

    				      </tr>

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
