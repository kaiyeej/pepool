<!doctype html>
<?php
require_once 'core/config.php';

if (isset($_SESSION['pepool_user_id'])) {
    header("Location: index.php");
    exit;
}

?>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Sign in - PePool</title>
    <!-- CSS files -->
    <link href="./dist/css/tabler.min.css?1684106062" rel="stylesheet" />
    <link href="./dist/css/tabler-flags.min.css?1684106062" rel="stylesheet" />
    <link href="./dist/css/tabler-payments.min.css?1684106062" rel="stylesheet" />
    <link href="./dist/css/tabler-vendors.min.css?1684106062" rel="stylesheet" />
    <link href="./dist/css/demo.min.css?1684106062" rel="stylesheet" />
    <link rel="stylesheet" href="dist/sweetalert/sweetalert.css">
    <link rel="shortcut icon" href="./static/logo.png" />
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>

<body class=" d-flex flex-column">
    <script src="./dist/js/demo-theme.min.js?1684106062"></script>
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <a href="." class="navbar-brand navbar-brand-autodark"><img src="./static/logo.png" height="100" alt=""></a>
            </div>
            <div class="card card-md">
                <div class="card-body">
                    <h2 class="h2 text-center mb-4">Login to your account</h2>
                    <form action="" method="POST" id='frm_login'>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="input[username]" required placeholder="Your username" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="input[password]" required placeholder="Your password" autocomplete="off">
                        </div>
                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary w-100">Sign in</button>
                        </div>
                        <!-- <div class="hr-text">or</div>
                        <div class="text-center text-muted mt-3">
                            Don't have account yet? <a href="./sign-up.html" tabindex="-1">Sign up</a>
                        </div>   -->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="dist/jquery-3.7.1.min.js" type="text/javascript"></script>
    <script src="dist/sweetalert/sweetalert2.js"></script>
    <script src="dist/sweetalert/sweetalert.js"></script>
    <script src="dist/js/tabler.min.js?1684106062" defer></script>
    <script src="dist/js/demo.min.js?1684106062" defer></script>
    <script type="text/javascript">
        $("#frm_login").submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "./controllers/sql.php?c=Users&q=login",
                data: $("#frm_login").serialize(),
                success: function(data) {
                    var json = JSON.parse(data);

                    if (json.data != 0) {
                        swal("Success!", "All is cool! Signed in successfully", "success");
                        window.location = "homepage";
                    } else {
                        swal("Login Failed!", 'Your username or password is incorrect. Please try again.', "warning");
                    }

                }
            });
        });
    </script>
</body>

</html>