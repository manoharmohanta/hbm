<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login - <?= WEBSITE_NAME ?></title>
    <meta name="description" content="<?= WEBSITE_NAME ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">

    <link rel="apple-touch-icon" href="<?= base_url('public/') ?>images/favicon.png">
    <link rel="shortcut icon" href="<?= base_url('public/') ?>images/favicon.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="<?= base_url('public/') ?>assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="<?= base_url('public/') ?>assets/css/style.css">
    <script src="https://unpkg.com/htmx.org"></script>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    
    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->
</head>
<body class="bg-dark-light">

    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    <a href="<?= base_url() ?>">
                        <img class="align-content" src="<?= base_url('public/') ?>images/logo.png" alt="<?= strtolower(str_replace(" ", "-",WEBSITE_NAME)) ?>">
                    </a>
                </div>
                <div class="login-form">
                    <form hx-post="<?= base_url('hotel/login') ?>" 
                            hx-target="this"
                            hx-trigger="click[event.target.matches('button')]"
                            hx-on::after-request="handleResponse(event)"
                            hx-swap="none" class="form">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                        <div class="form-group">
                            <label>Email address</label><span class="text-danger">*</span>
                            <input type="email" name="email" class="form-control" placeholder="Email" value="Manohar@idp.com">
                        </div>
                        <div class="form-group">
                            <label>Password</label><span class="text-danger">*</span>
                            <input type="password" name="password" class="form-control" placeholder="Password" value="Manohar">
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> Remember Me
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Sign in</button>
                    </form>
                    <div class="checkbox pt-1 mb-5">
                        <label class="pull-left">
                            Don't have account ? <a href="<?= base_url('hotel/register') ?>"> Sign Up Here</a>
                        </label>
                        <label class="pull-right">
                            <a href="<?= base_url('hotel/forget-password') ?>">Forgotten Password?</a>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="<?= base_url('public/') ?>assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function handleResponse(event) {
            const response = event.detail.xhr.response;

            try {
                const responseData = JSON.parse(response);

                // Check the response status
                if (responseData.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: responseData.message,
                    }).then(() => {
                        // Redirect to the dashboard after the alert closes
                        window.location.href = responseData.redirectUrl;  // Replace with the correct URL of your dashboard
                    });
                } else if (responseData.status === 'error') {
                    let errorMessage = responseData.message || 'An unknown error occurred.'; // Use the error message if present

                    // If there are errors, show them as a list
                    if (responseData.errors) {
                        let errorMessages = '<ul>';
                        for (const key in responseData.errors) {
                            errorMessages += `<li>${responseData.errors[key]}</li>`;
                        }
                        errorMessages += '</ul>';
                        errorMessage += '<br>' + errorMessages;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errorMessage,
                    });

                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Unexpected Response',
                        text: 'Received an unexpected response status.',
                    });
                }

                // Optionally update the CSRF token if needed
                if (responseData.csrf_token) {
                    document.querySelector('input[name="<?= csrf_token() ?>"]').value = responseData.csrf_token;
                    document.querySelector('meta[name="csrf-token"]').content = response.csrf_token;
                }
            } catch (e) {
                // Handle JSON parse errors or other unexpected issues
                Swal.fire({
                    icon: 'error',
                    title: 'Response Error',
                    text: 'Failed to process the server response. Please try again.',
                });
                console.error('Error parsing response:', e);
            }
        }
    </script>
</body>
</html>
