<?php

use Http\Request;
// Set return / back link
!empty(Request::getReferer())
    ? $nav = [Request::getReferer(), "Go back"]
    : $nav = ["/", "Return home"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Header -->
    <?php include($link->getComponent('header'))  ?>
    <title>404</title>
</head>

<body>
    <!-- Nav -->
    <?php include($link->getComponent('nav'))  ?>
    <div class="container-fluid error-404">
        <div class="container">
            <h1 class="display-1">404</h1>
            <h2 class="display-2">Woops!</h2>
            <h3 class="lead">Page not found.</h3>
            <h4><a href="<?= $nav[0] ?>"><?= $nav[1] ?></a></h4>
        </div>
    </div>
</body>

</html>