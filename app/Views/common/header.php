<!DOCTYPE html>
<html>

<head>
    <title>CEED - Empowering Kids with Growth</title>
	<link rel="icon" href="<?php echo base_url().ASSET_PATH; ?>admin/assets/img/logo.png" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>assets/css/styles.css">
    <link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>assets/css/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
</head>

<body>
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
                                    <button class="btn btn-user dropdown-toggle" type="button" id="userMenu"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?= session()->get('user_name'); ?>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userMenu">
                                        <li><a class="dropdown-item" href="<?= base_url('profile'); ?>">Profile</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('logout'); ?>">Logout</a></li>
                                    </div>
                                </div>
                                <?php else: ?>
                                <!-- Show login/demo buttons -->
                                <button class="btn btn-login"
                                    onclick="window.location.href='<?= base_url('signin'); ?>'">Signin</button>

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
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 text-center head-title" data-aos="fade-up">
                            <h1><span class="orange">Empowering Kids with<br />Growth Mindset Through<span><br /><span
                                            class="palesky">the Power of games.</span></h1>
                        </div>
                        <div class="col-md-12 text-center" data-aos="fade-up">
                            <div class="clearfix">&nbsp;</div>
                            <p class="palesky">Designed for busy families, CEED turns your child’s screen<br />time into
                                growth time with daily micro-lessons and brain-<br />building games.</p>
                        </div>
                        <div class="col-md-12">
                            <div class="puzzle">
                                <img src="<?php echo base_url().ASSET_PATH; ?>assets/img/puzzle.png"
                                    data-aos="flip-left" />
                                <div class="jix-mideset"></div>
                                <div class="jix-play"></div>
                                <div class="jix-learning"></div>
                                <div class="jix-thinking"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>