<?php
$session = session();
$roleId = $session->get('role_id');
$menus   = $session->get('role_menu'); 

$uri          = service('uri');
$currentPath  = uri_string(); // e.g. "admin/manage_user/edit/5"

// define menus with keywords that should keep it active
$allMenus = [
    'Manage Role' => [
        'url'   => 'manage_role',
        'icon'  => 'fas fa-th-list',
        'match' => ['manage_role', 'add_role']
    ],
    'Manage Admin User' => [
        'url'   => 'manage_user',
        'icon'  => 'bi bi-person-fill',
        'match' => ['manage_user', 'adduser']
    ],
    'Manage Course' => [
        'url'   => 'manage_course',
        'icon'  => 'bi bi-book',
        'match' => ['manage_course', 'add_course', 'add_module']
    ],
    // add more menus with their subpage keywords
];
?>

<div class="sidebar"  data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="<?= base_url('admin/dashboard') ?>" class="logo">
                <img src="<?= base_url().ASSET_PATH; ?>admin/assets/img/logo.png" class="navbar-brand" height="50" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                <!-- Dashboard -->
                <li class="nav-item <?= ($uri->getSegment(2) == 'dashboard') ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('admin/dashboard') ?>">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">Menus</h4>
                </li>

                <?php foreach ($allMenus as $name => $data): ?>
                <?php
                    if ($roleId != 1 && (!in_array($name, $menus))) continue;

                    // ✅ check if any keyword from "match" exists in current URL
                    $isActive = false;
                    foreach ($data['match'] as $keyword) {
                        if (strpos($currentPath, $keyword) !== false) {
                            $isActive = true;
                            break;
                        }
                    }
                    ?>
                <li class="nav-item <?= $isActive ? 'active' : '' ?>">
                    <a class="nav-link <?= $isActive ? 'active' : '' ?>" href="<?= base_url('admin/'.$data['url']) ?>">
                        <i class="<?= $data['icon'] ?>"></i>
                        <p><?= esc($name) ?></p>
                    </a>
                </li>
                <?php endforeach; ?>

                <!-- Logout -->
                <li class="nav-item">
                    <a class="nav-link" id="logout_btn" style="cursor:pointer;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>





<script>
$(document).ready(function() {
    $("#logout_btn").on("click", function(e) {
        swal({
            title: "Are you sure?",
            text: "You will be logged out!",
            icon: "warning",
            buttons: {
                confirm: {
                    text: "Logout",
                    className: "btn btn-success",
                },
                cancel: {
                    visible: true,
                    className: "btn btn-danger",
                },
            },
        }).then((willLogout) => {
            if (willLogout) {
                window.location.href = "<?= base_url('admin/logout'); ?>";
            } else {
                swal.close();
            }
        });
    });
});
</script>