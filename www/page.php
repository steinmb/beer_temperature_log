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
    <?php
    if (isset($graph)) {
        print '<img alt="temperatur log" class="graph" src="' . $graph . '">';
    }
    ?>

    <div class="content">

        <?php

        if (isset($blocks)) {
            foreach ($blocks as $block) {
                print $block;
            }
        }
        ?>
    </div>

    </body>

</page>

</html>
