<?php

use Http\Session; ?>

<script>
    window.setHeaders = () => {
        $.ajaxSetup({
            headers: {
                'x-auth-token': '<?= Session::get('auth_token') ?>'
            },
            statusCode: {
                401: function(data) {
                    (sessionStorage.removeItem("loggedIn"),
                        (window.location.href = 'login')
                    )
                }
            }
        });
    };
</script>