<!DOCTYPE html>
<html>

<head>
	<title>CEED - Empowering Kids with Growth</title>
     <link rel="icon" href="<?php echo base_url().ASSET_PATH; ?>admin/assets/img/logo.png" type="image/x-icon" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>user/assets/css/styles.css">
	<link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>user/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>
	<header class="bg-white">
		<div class="container-lg">
			<div class="col-md-12">
				<div class="main-menu-box" data-aos="zoom-out">
					<div class="menu-lg">
						<div class="row pt-3">
							<div class="col-1 logo">
								 <img src="<?php echo base_url().ASSET_PATH; ?>assets/img/logo.png" />
							</div>
							<div class="col-8 menu-lg">
								<a href="#">Home</a>
								<a href="<?= base_url('course'); ?>">Courses</a>
								<a href="<?= base_url('leaderboard'); ?>">Leaderboard</a>
								<a href="#">Community</a>
								<a href="#">Help Desk</a>
							</div>
							<div class="col-3 text-right btn-holder d-flex align-items-center justify-content-end">
								<!-- Bell Icon -->
								<div class="icon-circle me-3">
									<i class="bi bi-bell-fill"></i>
								</div>

								<!-- Profile Dropdown -->
								<div class="dropdown">
									<button
										class="btn dropdown-toggle d-flex align-items-center border-0 bg-transparent p-0"
										type="button" id="profileDropdown" data-bs-toggle="dropdown"
										aria-expanded="false">
										<img src="<?php echo base_url().ASSET_PATH; ?>user/assets/img/profile.jpeg" alt="Profile" class="profile-img" />
										<span class="ms-3 username">Bala</span>
										<!-- <i class="bi bi-chevron-down ms-1"></i> -->
									</button>

									<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
										<li><a class="dropdown-item" href="#">Profile</a></li>
										<li><a class="dropdown-item" href="#">Settings</a></li>
										<li>
											<hr class="dropdown-divider">
										</li>
										<li><a class="dropdown-item" href="#">Logout</a></li>
									</ul>
								</div>
							</div>


						</div>


					</div>
					<!---Mobile Responsive-->
					<div class="menu-resp d-md-none">
						<div class="d-flex justify-content-between align-items-center p-3">
							<!-- Logo -->
							<div class="logo">
								<img src="assets/img/logo.png" alt="Logo" height="40" />
							</div>

							<!-- Right side (Hamburger + Login) -->
							<div class="d-flex align-items-center">
								<a href="#" class="me-3">Login</a>
								<i class="bi bi-list fs-3" onclick="openRespMenu()"></i>
							</div>
						</div>

						<!-- Hidden Responsive Menu -->
						<div class="resp-menu" id="respMenu">
							<div
								class="menu-header d-flex justify-content-between align-items-center p-3 border-bottom">
								<img src="assets/img/logo.png" alt="Logo" height="40">
								<span class="close-btn" onclick="closeRespMenu()">Close <i
										class="bi bi-x-lg"></i></span>
							</div>

							<div class="p-3">
								<div class="resp-menu-item"><a href="#">Home <i class="bi bi-chevron-right"></i></a>
								</div>
								<div class="resp-menu-item"><a href="#">Courses <i class="bi bi-chevron-right"></i></a>
								</div>
								<div class="resp-menu-item"><a href="#">Leaderboard <i
											class="bi bi-chevron-right"></i></a></div>
								<div class="resp-menu-item"><a href="#">Community <i
											class="bi bi-chevron-right"></i></a></div>
								<div class="resp-menu-item"><a href="#">Help Desk <i
											class="bi bi-chevron-right"></i></a></div>
							</div>
						</div>
					</div>

				</div>

			</div>
		</div>
		<hr>

	</header>