<?php

use Utilities\Utility; ?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= Utility::setTitle() ?></title>
<!-- <meta http-equiv="X-UA-Compatible" content="ie=edge"> -->
<!-- JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<?php if ($_SERVER["SERVER_NAME"] === PROD_URL): ?>
<!-- Set datalayer variable -->
<script type="text/JavaScript">
    // Added for Tracking. Please do not remove
        if(typeof (dataLayerInternal)==='undefined'){
            var dataLayerInternal={};
        }
    </script>
<!-- Analytics -->
<script src="//assets.adobedtm.com/bdc402f2525d/606bc8a34917/launch-b007c9afd75d.min.js" async></script>​
<?php endif; ?>

<!-- Popper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
</script>
<!-- Bootstrap -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
<!-- Set Ajax headers -->
<?php include_once $link->getComponent('/common/authenticate'); ?>
<!-- Custom -->
<link rel="stylesheet" href="<?= $link->public(
  'css'
) ?>core.css?v=<?= VERSION ?>" />