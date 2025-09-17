<!DOCTYPE html>
<html lang="en">

<head>
    <title>CEED - Empowering Kids with Growth</title>
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

                    <form class="mt-4">
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


                        <button type="submit" class="btn btn-signin w-100 mb-3">Sign In</button>

                        <!-- <div class="text-center mb-3">or</div> -->
                        <div class="separator text-center mb-3">or</div>


                        <button type="button" class="btn btn-outline-secondary w-100 mb-3 p-3">
                            <img class="google-icon"
                                src="<?php echo base_url().ASSET_PATH; ?>assets/img/social_icons/google.png"> Continue
                            with Google
                        </button>

                        <div class="text-center">
                            Don’t have an account? <a href="#" class="login-text-color">Sign Up</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url().ASSET_PATH; ?>assets/js/jquery-3.7.1.min.js"></script>
    <script src="<?php echo base_url().ASSET_PATH; ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url().ASSET_PATH; ?>assets/js/app.js"></script>

    <script>
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