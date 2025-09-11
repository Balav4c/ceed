<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEED</title>
    <link rel="icon" href="<?php echo base_url().ASSET_PATH; ?>admin/assets/img/kaiadmin/favicon.ico"
        type="image/x-icon" />
    <!-- CSS Files -->
    <link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>admin/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>admin/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>admin/assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="<?php echo base_url().ASSET_PATH; ?>admin/assets/css/custom.css" />
</head>

<body>


    <div class="container-fluid vh-100">
        <div class="row h-100">

            <!-- Left Side -->
            <div
                class="col-lg-6 col-md-6 d-flex flex-column justify-content-center align-items-center text-center left-side-card">
                <p class="logo">CEED</p>
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
                    <div class="card-body">
                        <form name="loginForm" id="loginForm" >
                            <div class="mb-3">
                                <label for="email2" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email2" placeholder="Enter Email">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" placeholder="Password">
                            </div>
                            <button type="submit" id="loginCheck" class="btn btn-bg w-100 text-white">Log in</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="<?php echo base_url().ASSET_PATH; ?>admin/assets/js/core/jquery-3.7.1.min.js">
    </script>
    <script src="<?php echo base_url().ASSET_PATH; ?>admin/assets/js/core/popper.min.js"></script>
    <script src="<?php echo base_url().ASSET_PATH; ?>admin/assets/js/core/bootstrap.min.js">
    </script>

</body>

</html>