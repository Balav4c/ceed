<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEED</title>
    <link rel="icon" href="<?php echo base_url().ASSET_PATH; ?>admin/assets/img/logo.png"
        type="image/x-icon" />
    <!-- CSS Files -->
    <link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>admin/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>admin/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>admin/assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>admin/assets/css/custom.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>


    <div class="container-fluid vh-100">
        <div class="row h-100">

            <!-- Left Side -->
            <div
                class="col-lg-6 col-md-6 d-flex flex-column justify-content-center align-items-center text-center left-side-card">
                <!-- <p class="logo">CEED</p> -->
                 <img src="<?php echo base_url().ASSET_PATH; ?>admin/assets/img/logo.png" alt="CEED Logo" class="mb-4" style="width: 150px;">
                <h2>Welcome to CEED Admin</h2>
                <p>Manage all your data in one place with our secure and professional admin panel.</p>

            </div>

            <!-- Right Side -->
            <div class="col-lg-6 col-md-6 d-flex align-items-center justify-content-center bg-light">
                <div class="card shadow login-card w-75 p-4">
                    <div class="card-header text-center bg-white border-0">
                        <h4 class="card-title mb-0">Welcome Back!</h4>
                        <p>Administration Login</p>
                    </div>
                    <div id="errorDiv"></div>

                    <div class="card-body">
                        <form name="loginForm" id="loginForm">
                            <div class="mb-3">
                                <label for="email2" class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" id="email"
                                    placeholder="Enter Email" required>
                            </div>
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Password" required>
                                    <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                        <i class="fa-solid fa-eye-slash"></i>
                                    </span>
                                </div>
                            </div>
                            <!--Captcha-->
                            <div class="g-recaptcha" data-sitekey="6Le-VXcrAAAAAFdEqJLtM5DxM6GoGl7cJdV6hknL"></div>


                            <br>

                            <button type="submit" id="loginCheck" class="btn btn-bg w-100 text-white">Log in</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="<?php echo base_url().ASSET_PATH; ?>admin/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="<?php echo base_url().ASSET_PATH; ?>admin/assets/js/core/popper.min.js"></script>
    <script src="<?php echo base_url().ASSET_PATH; ?>admin/assets/js/core/bootstrap.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
    $('#loginCheck').click(function(e) {
        e.preventDefault();
        var response = grecaptcha.getResponse();
        if (response.length === 0) {
            $('#errorDiv')
                .html('<div class="alert alert-danger">Please complete the reCAPTCHA.</div>');
            return;
        }

        let email = $('#email').val().trim();
        let password = $('#password').val().trim();
        let errorMessage = '';

        if (email === '' && password === '') {
            errorMessage = "Please enter email and password.";
        } else if (email === '') {
            errorMessage = "Please enter your email.";
        } else if (password === '') {
            errorMessage = "Please enter your password.";
        }

        if (errorMessage !== '') {

            $('#errorDiv').html('<div class="alert alert-danger">' + errorMessage + '</div>');
            return;
        }

        var url = "<?php echo base_url('admin/login'); ?>";

        $.post(url, $('#loginForm').serialize(), function(data) {
            if (data.status == 'success') {
                window.location.href = "<?php echo base_url('admin/dashboard'); ?>";
            } else {
                $('#errorDiv').html('<div class="alert alert-danger">' + data.message + '</div>');
            }
        }, 'json');
    });

    //Password Show and hide
    $(document).on('click', '#togglePassword', function() {
        const passwordField = $('#password');
        const icon = $(this).find('i');

        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        }
    });
    </script>


</body>

</html>