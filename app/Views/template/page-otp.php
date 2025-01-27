<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Email OTP Verification - <?= WEBSITE_NAME ?></title>
    <meta name="description" content="<?= WEBSITE_NAME ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="shortcut icon" href="https://i.imgur.com/QRAUqs9.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="<?= base_url('public/') ?>assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="<?= base_url('public/') ?>assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
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
                    <form>
                        <div class="form-group">
                            <label>Enter 6-Digit OTP</label><span class="text-danger">*</span>
                            <div class="d-flex justify-content-between otp-inputs mb-3">
                                <input type="text" class="form-control text-center" maxlength="1" style="width: 50px; height: 50px;">
                                <input type="text" class="form-control text-center" maxlength="1" style="width: 50px; height: 50px;">
                                <input type="text" class="form-control text-center" maxlength="1" style="width: 50px; height: 50px;">
                                <input type="text" class="form-control text-center" maxlength="1" style="width: 50px; height: 50px;">
                                <input type="text" class="form-control text-center" maxlength="1" style="width: 50px; height: 50px;">
                                <input type="text" class="form-control text-center" maxlength="1" style="width: 50px; height: 50px;">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-flat m-b-15">Verify</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="<?= base_url('public/') ?>assets/js/main.js"></script>

    <script>
        // Automatically move to the next OTP input field
        document.querySelectorAll('.otp-inputs input').forEach((input, index, inputs) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && index > 0 && input.value === '') {
                    inputs[index - 1].focus();
                }
            });
        });
    </script>
</body>
</html>