<?php require_once("config/index.php");

use Http\Request;

if (!empty($_POST)) {

    $form = request::allPost();

    print_r($form);
    // handle submit
}
