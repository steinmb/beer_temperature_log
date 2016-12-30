<html>

    <LINK REL=StyleSheet HREF="css/style.css" TYPE="text/css" MEDIA=screen>
    <meta charset="UTF-8" http-equiv="refresh" content="180">

    <head>
        <title>Ferment</title>
        <link>
    </head>

    <page>
        <h1 class="title">Temperature log</h1>

        <body>
            <img alt="temperatur log" src="temperatur.png">

            <div class="content">

              <?php
              foreach ($blocks as $block)
              {
                print $block->render;
              }
              ?>
            </div>

        </body>

    </page>

</html>
