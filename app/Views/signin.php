<!DOCTYPE html>
<html lang="en">

<head>
    <title>CEED - Empowering Kids with Growth</title>
    <link rel="icon" href="<?php echo base_url().ASSET_PATH; ?>admin/assets/img/logo.png" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>assets/css/styles.css">
    <link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>assets/css/custom.css">
    <link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>assets/css/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Left side with background -->
            <div class="col-md-5 login-img d-flex flex-column justify-content-between">
                <div class="p-4">

                    <img class="logo" src="<?php echo base_url().ASSET_PATH; ?>assets/img/login-logo.png" />
                </div>
                <div class="p-4">
                    <p class="text-white login-content">
                        Learning doesn't have to be boring. Earn badges, unlock
                        achievements, and track your progress as you master new skills
                        We make every lesson feel like a win.
                    </p>
                </div>
            </div>

            <!-- Right side with login form -->
            <div class="col-md-7 d-flex align-items-center justify-content-center login-form-section">
                <div class="login-form w-60">
                    <p class="welcome">Welcome Back!</p>
                    <p class="mb-3 sub-content">
                        Pick up right where you left off in your learning journey.
                    </p>

                    <div id="responseMessage"></div>


                    <form id="loginForm" class="mt-4">
                        <div class="mb-3">
                            <label for="email" class="form-label fs-14">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control fc-font"
                                placeholder="Enter email address">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fs-14">Password</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control fc-font"
                                    placeholder="Enter your password">
                                <span class="input-group-text eye-icon" id="togglePassword" style="cursor: pointer;">
                                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                                </span>
                            </div>
                            <div class="d-flex justify-content-end">
                                <small class="p-1">
                                    <a class="login-text-color" href="#">Forgot Password?</a>
                                </small>
                            </div>
                        </div>
                        <!--Captcha-->
                        <div class="g-recaptcha" data-sitekey="6Le-VXcrAAAAAFdEqJLtM5DxM6GoGl7cJdV6hknL"></div>
                        <br>


                        <!-- <button type="submit" id="signincheck" class="btn btn-signin w-100 mb-3">Sign In</button> -->
                        <button type="submit" id="signincheck" class="btn btn-signin w-100 mb-3">
                            <span class="spinner-border spinner-border-sm me-2 d-none" id="loginSpinner"></span>
                            <span id="loginText">Sign In</span>
                        </button>

                        <!-- <div class="text-center mb-3">or</div> -->
                        <div class="separator text-center mb-3">or</div>


                        <button type="button" class="btn btn-outline-secondary w-100 mb-3 p-3">
                            <img class="google-icon"
                                src="<?php echo base_url().ASSET_PATH; ?>assets/img/social_icons/google.png"> Continue
                            with Google
                        </button>

                        <div class="text-center">
                            Don’t have an account? <a href="<?= base_url('signup') ?>" class="login-text-color">Sign
                                Up</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url().ASSET_PATH; ?>assets/js/jquery-3.7.1.min.js"></script>
    <script src="<?php echo base_url().ASSET_PATH; ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url().ASSET_PATH; ?>assets/js/app.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
    $(document).ready(function() {
        $("#loginForm").submit(function(e) {
            e.preventDefault();

            let email = $("#email").val().trim();
            let password = $("#password").val().trim();
            let errorMessage = "";

            if (email === "" && password === "") {
                errorMessage = "Please enter email and password.";
            } else if (email === "") {
                errorMessage = "Please enter your email.";
            } else if (password === "") {
                errorMessage = "Please enter your password.";
            }

            if (errorMessage !== "") {
                showAlert(errorMessage, "danger");
                return;
            }

            var response = grecaptcha.getResponse();
            if (response.length === 0) {
                showAlert("Please complete the reCAPTCHA.", "danger");
                return;
            }

            let $btn = $("#signincheck");
            let $spinner = $("#loginSpinner");
            let $btnText = $("#loginText");

            // 👉 Show spinner and disable button
            $btn.prop("disabled", true);
            $spinner.removeClass("d-none");
            $btnText.text("Signing in...");

            $.ajax({
                url: "<?= base_url('auth/login'); ?>",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        showAlert(response.message, "success");

                        setTimeout(() => {
                            window.location.href = "<?= base_url('/'); ?>";
                        }, 1000);
                    } else {
                        showAlert(response.message, "danger");
                        // reset button so user can try again
                        $btn.prop("disabled", false);
                        $spinner.addClass("d-none");
                        $btnText.text("Sign In");
                    }
                },
                error: function() {
                    showAlert("Something went wrong. Please try again.", "danger");
                    // reset button on error
                    $btn.prop("disabled", false);
                    $spinner.addClass("d-none");
                    $btnText.text("Sign In");
                },
            });
        });
    });


    // Reusable Alert Function
    function showAlert(message, type = "danger") {
        let $alertBox = $("#responseMessage"); // container div in your HTML
        $alertBox
            .hide()
            .html('<div id="alertbox" class="alert alert-' + type + '">' + message + "</div>")
            .fadeIn();

        setTimeout(() => {
            $("#alertbox").fadeOut(function() {
                $(this).remove();
            });
        }, 3000);
    }


    //Reusable Alert Function
    function showAlert(message, type = 'danger') {
        let $alertBox = $('#responseMessage'); // container div in your HTML
        $alertBox
            .hide()
            .html('<div id="alertbox" class="alert alert-' + type + '">' + message + '</div>')
            .fadeIn();

        setTimeout(() => {
            $('#alertbox').fadeOut(function() {
                $(this).remove();
            });
        }, 3000);
    }


    //Password Show and hide
    $(document).on('click', '#togglePassword', function() {
        const passwordField = $('#password');
        const icon = $(this).find('i');

        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        }
    });
    </script>
</body>

</html>