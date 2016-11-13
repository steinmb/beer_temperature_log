<html>

    <LINK REL=StyleSheet HREF="css/style.css" TYPE="text/css" MEDIA=screen>
    <meta charset="UTF-8" http-equiv="refresh" content="180">

    <head>
        <title>Gjæring</title>
        <link>
    </head>

    <body>
    <img alt="temperatur log" src="temperatur.png">

    <h1 class="title">Brewpi temperature log</h1>

        <div class="content">
            <?php
                foreach ($blocks as $block)
                {
                    print $block->render;
                }
            ?>
        </div>

    </body>

</html>
