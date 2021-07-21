<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Header -->
    <?php include($link->getComponent('header')) ?>
</head>

<body class="<?= $router->trimURI(true)  ?>">
    <!-- Nav -->
    <?php include($link->getComponent('nav'))  ?>
    <!-- View -->
    <?php include($link->getView()) ?>
    <!-- Scripts -->
    <?php include($link->getComponent('scripts'))  ?>

</body>

</html>