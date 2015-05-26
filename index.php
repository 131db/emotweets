<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Twitter Sentimental Analysis</title>

    <!-- STYLESHEETS -->
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet" type="text/css">

  </head>
  <body>
    <!-- START CONTAINER-FLUID -->
    <div class="container-fluid">

      <!-- START ROW 1 -->
      <div class="row-fluid">

        <!-- START COL-XS-1 -->
        <div class="col-md-1">

          <!-- START SEARCH PANEL -->
          <div class="searchPanel">
            <form action="index.html" method="post">
              <div class="form-group">
              <input type="text" class="form-control" name="name" id="hashtag" value="" placeholder="Enter hashtag here..">
              </div>
            </form>
          </div>
          <!-- END SEARCH PANEL -->

        </div>
        <!-- START COL-XS-1 -->
      </div>
      <!-- END ROW 1 -->

      <!-- START ROW 2 -->
      <div class="row">

        <!-- START COL-MD-6 -->
        <div class="col-md-6">

          <!-- START EMOTION GRAPH -->
          <div class="emotionGraph">

          </div>
          <!-- END EMOTION GRAPH -->

        </div>
        <!-- END COL-MD-6 -->

        <!-- START COL-MD-6 -->
        <div class="col-md-6">
          <!-- START TWITTER FEED -->
          <div class="twitterFeed">

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
