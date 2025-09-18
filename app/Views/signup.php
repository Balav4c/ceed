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
            <div class="col-md-5 signup-img d-flex flex-column justify-content-between">
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
                <div class="signup-form w-60">
                    <p class="welcome">Create Your Account</p>
                    <p class="mb-3 sub-content">
                        Join a community of learners where progress is rewarded.
                    </p>

                    <div id="responseMessage"></div>


                    <form id="signupForm" class="mt-4">
                        <div class="mb-3">
                            <label for="name" class="form-label fs-14">Full Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control fc-font"
                                placeholder="Enter full name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fs-14">Email Address <span
                                    class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control fc-font"
                                placeholder="Enter email address">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fs-14">Password <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="signpassword" name="password" class="form-control fc-font"
                                    placeholder="........">
                                <span class="input-group-text eye-icon" id="togglePassword" style="cursor: pointer;">
                                    <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                                </span>
                            </div>
                            <!-- Error message here -->
                            <small id="passwordError" style="font-size: 11px;" class="text-danger"></small>
                        </div>

                        <div class="mb-3">
                            <label for="cpassword" class="form-label fs-14">Confirm Password <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="signcpassword" name="cpassword" class="form-control fc-font"
                                    placeholder="........">
                                <span class="input-group-text eye-icon" id="toggleCPassword" style="cursor: pointer;">
                                    <i class="bi bi-eye-slash" id="toggleCPasswordIcon"></i>
                                </span>
                            </div>
                            <!-- Error message here -->
                            <small id="cpasswordError" style="font-size: 11px;" class="text-danger"></small>
                        </div>



                        <button type="submit" class="btn btn-signin w-100 mb-3">Create Account</button>

                        <!-- <div class="text-center mb-3">or</div> -->
                        <div class="separator text-center mb-3">or</div>


                        <button type="button" class="btn btn-outline-secondary w-100 mb-3 p-3">
                            <img class="google-icon"
                                src="<?php echo base_url().ASSET_PATH; ?>assets/img/social_icons/google.png"> Continue
                            with Google
                        </button>

                        <div class="text-center">
                            Already have an account? <a href="<?= base_url('signin') ?>" class="login-text-color">Sign
                                In</a>
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
    //save user 
    $(document).ready(function() {
        function validatePassword() {
            let password = $("#signpassword").val().trim();
            let passwordPattern = /^(?=.*[A-Z])(?=.*[!@#$%^&*])(?=.{8,})/;

            if (!passwordPattern.test(password)) {
                $("#passwordError").text(
                    "Minimum 8 chars with one uppercase and one special chars required"
                );
                return false;
            } else {
                $("#passwordError").text(""); // clear error
                return true;
            }
        }

        function validateConfirmPassword() {
            let password = $("#signpassword").val().trim();
            let cpassword = $("#signcpassword").val().trim();

            if (cpassword && password !== cpassword) {
                $("#cpasswordError").text("Passwords do not match.");
                return false;
            } else {
                $("#cpasswordError").text(""); // clear error
                return true;
            }
        }

        // Live validation while typing
        $("#signpassword").on("input", function() {
            validatePassword();
            validateConfirmPassword(); // re-check confirm password when password changes
        });

        $("#signcpassword").on("input", function() {
            validateConfirmPassword();
        });

        // Form submit
        // Form submit
        $("#signupForm").submit(function(e) {
            e.preventDefault();

            $("#passwordError").text("");
            $("#cpasswordError").text("");
            $("#responseMessage").html("");

            let name = $("#name").val().trim();
            let email = $("#email").val().trim();
            let password = $("#signpassword").val().trim();
            let cpassword = $("#signcpassword").val().trim();

            // Check empty fields first
            if (!name || !email || !password || !cpassword) {
                showAlert("Fill in all required fields.", "danger");
                return;
            }

            // Validate password + confirm password
            let passwordValid = validatePassword();
            let confirmValid = validateConfirmPassword();

            if (!passwordValid || !confirmValid) {
                return; // stop if validation fails
            }

            // Proceed with AJAX
            $.ajax({
                url: "<?= base_url('save/user'); ?>",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        showAlert(response.message, "success");
                        $("#signupForm")[0].reset();
                         setTimeout(() => {
                            window.location.href = "<?= base_url('signin'); ?>";
                        }, 1000);
                    } else {
                        showAlert(response.message, "danger");
                    }
                },
                error: function() {
                    showAlert("Something went wrong. Please try again.", "danger");
                }
            });
        });

    });




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
    $(document).on('click', '#togglePassword, #toggleCPassword', function() {
        const input = $(this).siblings('input'); // get the input in same group
        const icon = $(this).find('i');

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        } else {
            input.attr('type', 'password');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        }
    });
    </script>
</body>

</html>