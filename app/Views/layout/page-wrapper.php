<?php require_once('main.php'); // outputs <!doctype html><html ... > ?>
<head>
    <?php
        require_once('title-meta.php');    // meta tags and title
        require_once('head-css.php');      // CSS and layout.js
    ?>
</head>
<body>
    <?php
        // Render page content dynamically
        if (isset($content)) {
            echo view($content); // or include("views/$content.php");
        }

        require_once('footer.php');        // footer HTML
        require_once('vendor-scripts.php'); // JS libraries
    ?>
</body>
</html>
