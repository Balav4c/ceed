<!DOCTYPE html>
<html lang="en">

<head>
    <title>CEED - Empowering Kids with Growth</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?php echo base_url() . ASSET_PATH; ?>assets/css/styles.css">
    <link rel="stylesheet" href="<?php echo base_url() . ASSET_PATH; ?>assets/css/custom.css">
    <link rel="stylesheet" href="<?php echo base_url() . ASSET_PATH; ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url() . ASSET_PATH; ?>assets/css/bootstrap-icons.min.css">
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

                    <img class="logo" src="<?php echo base_url() . ASSET_PATH; ?>assets/img/login-logo.png" />
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
                    <p class="welcome">Reset Password</p>
                    <p class="mb-3 sub-content">
                        Enter a new password to login access to your account.
                    </p>

                    <div id="responseMessage"></div>


                    <form id="resetForm" class="mt-4">
                        <input type="hidden" name="token" value="<?= esc($token) ?>">
                        <div class="mb-3 position-relative">
                            <label for="password" class="form-label fs-14">New Password</label>
                            <input type="password" id="password" name="password" class="form-control fc-font p-3"
                                placeholder="Enter new password">
                            <span class="position-absolute toggle-password" data-target="password"
                                style="top: 68%; right: 15px; cursor: pointer; transform: translateY(-50%);">
                                <i class="bi bi-eye-slash"></i>
                            </span>
                        </div>

                        <div class="mb-3 position-relative">
                            <label for="confirm_password" class="form-label fs-14">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password"
                                class="form-control fc-font p-3" placeholder="Confirm new password">
                            <span class="position-absolute toggle-password" data-target="confirm_password"
                                style="top: 68%; right: 15px; cursor: pointer; transform: translateY(-50%);">
                                <i class="bi bi-eye-slash"></i>
                            </span>
                        </div>



                        <button type="submit" class="btn btn-signin w-100 mt-3">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url() . ASSET_PATH; ?>assets/js/jquery-3.7.1.min.js"></script>
    <script src="<?php echo base_url() . ASSET_PATH; ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url() . ASSET_PATH; ?>assets/js/app.js"></script>
    <script>
        $(document).ready(function () {
            $("#resetForm").submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "<?= base_url('update-password'); ?>",
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            showAlert(response.message, 'success');
                        } else {
                            showAlert(response.message, 'danger');
                        }
                    },
                    error: function () {
                        showAlert("Something went wrong.", 'danger');
                    }
                });
            });
        });

        function showAlert(message, type = 'danger') {
            let $alertBox = $('#responseMessage');
            $alertBox
                .hide()
                .html('<div id="alertbox" class="alert alert-' + type + '">' + message + '</div>')
                .fadeIn();

            setTimeout(() => {
                $('#alertbox').fadeOut(function () {
                    $(this).remove();
                });
            }, 3000);
        }
        $(document).ready(function () {
            $(document).on('click', '.toggle-password', function () {
                let targetId = $(this).data('target');
                let passwordField = $("#" + targetId);
                let icon = $(this).find("i");

                if (passwordField.attr("type") === "password") {
                    passwordField.attr("type", "text");
                    icon.removeClass("bi-eye-slash").addClass("bi-eye");
                } else {
                    passwordField.attr("type", "password");
                    icon.removeClass("bi-eye").addClass("bi-eye-slash");
                }
            });
        });
    </script>
</body>

</html>