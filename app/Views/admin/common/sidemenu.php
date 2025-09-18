  <!-- Sidebar -->
  <?php
$session = session();
$roleId = $session->get('role_id');
$menus = $session->get('role_menu'); // array of allowed menu names for non-admin
$uri = service('uri');
$segment1 = $uri->getSegment(2);

// Define all available menus with their URLs and icons
$allMenus = [
    'Manage Role'       => ['url' => 'admin/manage_role', 'icon' => 'fas fa-th-list'],
    'Manage Admin User' => ['url' => 'admin/manage_user', 'icon' => 'bi bi-person-fill'],
    'Manage Course'     => ['url' => 'admin/manage_course', 'icon' => 'bi bi-book'],
    // Add more menus here if needed
];
?>

<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="<?= base_url('admin/dashboard') ?>" class="logo">
                <img src="<?= base_url().ASSET_PATH; ?>admin/assets/img/logo.png" alt="navbar brand" class="navbar-brand" height="50" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
            </div>
            <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
        </div>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                <!-- Dashboard is always visible -->
                <li class="nav-item <?= ($segment1 == 'dashboard') ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('admin/dashboard') ?>">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">Menus</h4>
                </li>

                <!-- Loop through menus -->
                <?php
                foreach ($allMenus as $name => $data):
                    // Skip Dashboard as it is already displayed
                    if ($name === 'Dashboard') continue;

                    // For non-admin users, only show allowed menus
                    if ($roleId != 1 && (!in_array($name, $menus))) continue;
                ?>
                    <li class="nav-item <?= ($segment1 == basename($data['url'])) ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= base_url($data['url']) ?>">
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


  <!-- End Sidebar -->
  <script>
$(document).ready(function() {
    $("#logout_btn").on("click", function(e) {
        swal({
            title: "Are you sure?",
            text: "You will be logged out!",
            icon: "warning",
            buttons: {
                confirm: {
                    text: "Yes, logout",
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