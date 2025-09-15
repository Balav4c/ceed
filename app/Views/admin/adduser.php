<div class="container">
    <div class="page-inner">
        <div class="card">

            <div class="card-header">
                <h3 class="mb-0"><?= isset($userData['user_id']) ? 'Edit User' : 'Add New User' ?></h3>
            </div>
            <div id="messageBox" class="alert d-none text-center" role="alert"></div>


            <div class="card-body">
                <div class="alert d-none text-center" role="alert"></div>

                <form id="userForm" method="post">
                    <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control"  value="<?= isset($userData['name']) ? esc($userData['name']) : '' ?>" required>
                    </div>
                    <div class="col-md-6 ">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= isset($userData['email']) ? esc($userData['email']) : '' ?>" required>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mt-4">
                            <label class="form-label">User Role <span class="text-danger">*</span></label>
                            <select name="role_id" id="role_id" class="form-control" required>
                                <option value="">-- Select Role --</option>
                                <?php if (isset($roles) && !empty($roles)): ?>
                                    <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role->role_id ?>" 
                                        <?= isset($userData['role_id']) && $userData['role_id'] == $role->role_id ? 'selected' : '' ?>>
                                        <?= ucfirst($role->role_name) ?>
                                    </option>

                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">No roles available</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    <div class="col-md-6 mt-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-2 mt-5">
                    <button type="submit" id="saveUserBtn" class="btn btn-success">Save User</button>
                    <a href="<?= base_url('admin/manage_user') ?>" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

</div>