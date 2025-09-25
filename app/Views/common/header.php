<!DOCTYPE html>
<html>

<head>
    <title>CEED - Empowering Kids with Growth</title>
    <link rel="icon" href="<?php echo base_url().ASSET_PATH; ?>admin/assets/img/logo.png" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>assets/css/styles.css">
    <link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>assets/css/app.css">
    <link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>assets/css/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
</head>

<body>
    <?php
$profileModel = new \App\Models\UserProfileModel();
$userId = session()->get('user_id');
$profile = $profileModel->where('user_id', $userId)->first();
$progress = $profile['profile_percentage'] ?? 0;
?>

    <header>
        <div class="container-lg">
            <div class="col-md-12">
                <div class="main-menu-box" data-aos="zoom-out">
                    <div class="menu-lg">
                        <div class="row">
                            <div class="col-1 logo">
                                <img src="<?php echo base_url().ASSET_PATH; ?>assets/img/logo.png" />
                            </div>
                            <div class="col-7 menu-lg">
                                <a href="#">HOME</a>
                                <a href="#">ABOUT</a>
                                <a href="#">Services</a>
                                <a href="#">Team</a>
                                <a href="#">Contact</a>
                            </div>
                            <!-- <div class="col-4 text-right btn-holder">
									<button class="btn btn-login" onclick="window.location.href='<?= base_url('login'); ?>'">login</button>
									<button class="btn btn-demo">Request A Demo</button>
								</div> -->
                            <div class="col-4 text-right btn-holder">
                                <?php if (session()->get('isLoggedIn')): ?>
                                <!-- Show user dropdown -->
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-user dropdown-toggle d-flex align-items-center gap-2"
                                        type="button" id="userMenu" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <!-- Username -->
                                       <span><?= ucwords(session()->get('user_name')); ?></span>


                                        <!-- Circular Progress (inline with name) -->
                                        <!-- <div id="profileProgressCircle" class="progress-circle"></div> -->
                                      

                                    </button>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userMenu">
                                        <a class="dropdown-item drop-menu"
                                            href="<?= base_url('profile'); ?>">Profile</a>
                                            <a class="dropdown-item drop-menu"
                                            href="<?= base_url('leaderboard'); ?>">Leaderboard</a>
                                        <a class="dropdown-item drop-menu" id="logout-btn">Logout</a>
                                    </div>
                                </div>

                                <?php else: ?>
                                <!-- Show login/demo buttons -->
                                <button class="btn btn-login"
                                    onclick="window.location.href='<?= base_url('signin'); ?>'">Login</button>

                                <?php endif; ?>
                                <button class="btn btn-demo">Request A Demo</button>
                            </div>

                        </div>
                    </div>
                    <div class="menu-resp">
                        <div class="row">
                            <div class="col-3 logo">
                                <img src="<?php echo base_url().ASSET_PATH; ?>assets/img/logo.png" />
                            </div>
                            <div class="col-2 text-right">
                                <i class="bi bi-list" onclick="openRestMenu();"></i>
                            </div>
                            <div class="col-2 text-right">
                                <a href="<?= base_url('login'); ?>">Login</a>
                            </div>
                            <div class="col-5 text-right">
                                <button class="btn btn-demo">Request A Demo</button>
                            </div>
                        </div>
                        <div class="resp-menu">
                            <div class="menu-header">
                                <img src="<?php echo base_url().ASSET_PATH; ?>assets/img/logo.png" />
                                <span>Close (<i class="bi bi-x-lg"></i>)</span>
                            </div>
                            <div class="col-md-12">
                                <div class="resp-menu-item"><a href="#">HOME<i class="bi bi-chevron-right"></i></a>
                                </div>
                                <div class="resp-menu-item"><a href="#">ABOUT<i class="bi bi-chevron-right"></i></a>
                                </div>
                                <div class="resp-menu-item"><a href="#">Services<i class="bi bi-chevron-right"></i></a>
                                </div>
                                <div class="resp-menu-item"><a href="#">Team<i class="bi bi-chevron-right"></i></a>
                                </div>
                                <div class="resp-menu-item"><a href="#">Contact<i class="bi bi-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>



    <!--Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to logout?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm-logout">Yes, Logout</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function() {
        // When logout button is clicked, show the modal
        $("#logout-btn").on("click", function(e) {
            e.preventDefault();
            $("#logoutModal").modal("show");
        });

        // When "Yes, Logout" is clicked
        $("#confirm-logout").on("click", function() {
            $.ajax({
                url: "<?= base_url('logout'); ?>",
                type: "GET",
                success: function(response) {
                    // Redirect to signin page after session destroyed
                    window.location.href = "<?= base_url('signin'); ?>";
                },
                error: function() {
                    alert("Something went wrong while logging out.");
                }
            });
        });
    });



    </script>