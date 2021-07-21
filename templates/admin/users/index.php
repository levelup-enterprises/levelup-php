<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Header -->
    <?php include($link->getComponent('header')) ?>
</head>

<body>
    <!-- Nav -->
    <?php include($link->getComponent('nav'))  ?>
    <!-- View -->
    <?php include($link->getView()) ?>
    <!-- Footer -->
    <?php include($link->getComponent('footer'))  ?>
    <!-- Scripts -->
    <?php include($link->getComponent('scripts'))  ?>

</body>

</html>