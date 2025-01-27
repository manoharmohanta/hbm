<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Register - Freemium Hotel Booking Management</title>
    <meta name="description" content="Freemium Hotel Booking Management">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
                <form hx-post="<?= base_url('hotel/register') ?>" 
                        hx-target="this"
                        hx-trigger="click[event.target.matches('button')]"
                        hx-on::after-request="handleResponse(event)"
                        hx-swap="none" class="form">
                    <div class="form-group">
                        <label>Full Name (As per id Proof)</label><span class="text-danger">*</span>
                        <input type="text" class="form-control" name="name" placeholder="User Name" value="Manohar">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Email address</label><span class="text-danger">*</span>
                                <input type="email" class="form-control" name="email" placeholder="Email" value="Manohar@idp.com">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Phone number</label><span class="text-danger">*</span>
                                <input type="tel" class="form-control" name="phone" placeholder="+91-" value="8801005610">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Password</label><span class="text-danger">*</span>
                        <input type="password" class="form-control" name="password" placeholder="Password" value="Manohar">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"> Agree the terms &amp; policy that you are the owner of property
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Register</button>
                </form>
                <div class="register-link m-t-15 text-center">
                    <p>Already have account ? <a href="<?= base_url('hotel/login') ?>"> Sign in</a></p>
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
            console.log('Raw Response:', response); // Debugging the response

            try {
                const responseData = JSON.parse(response);

                if (responseData.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: responseData.message,
                    });
                } else if (responseData.status === 'error') {
                    const errors = responseData.errors;
                    let errorMessages = '';
                    for (const key in errors) {
                        errorMessages += `<p>${errors[key]}</p>`;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errorMessages,
                    });
                }
            } catch (e) {
                console.error('Error parsing response:', e);
            }
        }
    </script>
</body>
</html>
