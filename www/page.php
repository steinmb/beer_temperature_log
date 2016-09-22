<?php
/**
 * @file HTML markup of the log page.
 */


$meta = '<LINK REL=StyleSheet HREF="css/style.css" TYPE="text/css" MEDIA=screen>
         <meta charset="UTF-8" http-equiv="refresh" content="180">';

$header = '<head>
              <title>Gjæring</title>
              <link>
            </head>
           ';

$body = '<body>
            <div class="header">
              <h1 class="title">Brewpi temperature log</h1>
            </div>
            <div class="content">
              <img alt="temperatur log" src="temperatur.png">
            </div>
          </body>
        ';

$page = '<html>' . $meta . $header . $body . '</html>';
