<?php if (isset($_SESSION['errors'])) : ?>
    <div id="alert" class="warning">
        <h1><?= print_r($_SESSION['errors']) ?></h1>
    </div>
<?php else : ?>
    <div id="alert"></div>
<?php endif ?>