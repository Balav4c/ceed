<div class="container">
    <div class="page-inner">
        <div class="card">
 
            <div class="card-header">
                <h3 class="mb-0"><?= isset($userData['user_id']) ? 'Edit User' : 'Add New User' ?></h3>
            </div>
            <div id="messageBox" class="alert d-none text-center" role="alert"></div>
 
            <div class="card-body">
                <form id="userForm" method="post" data-edit="true">
                    <?php if (isset($userData['user_id'])): ?>
                        <input type="hidden" name="user_id" value="<?= esc($userData['user_id']) ?>">
                    <?php endif; ?>
 
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"  
                                   value="<?= isset($userData['name']) ? esc($userData['name']) : '' ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email<span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control"
                                   value="<?= isset($userData['email']) ? esc($userData['email']) : '' ?>" required>
                        </div>
                    </div>
 
                    <div class="row">
                        <div class="col-md-6 mt-4">
                            <label class="form-label">User Role <span class="text-danger">*</span></label>
                            <select name="role_id" id="role_id" class="form-control" required>
                                <option value="">Select Role</option>
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
 
                        <?php if (!isset($userData['user_id'])): ?>
                            <div class="col-md-6 mt-4">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <div class="col-md-6 mt-4">
                                    <label class="form-label">New Password</label>
                                    <div class="input-group">
                                        <input type="password" name="new_password" id="new_password" class="form-control">
                                        <span class="input-group-text">
                                            <i class="bi bi-eye-slash toggle-password" data-target="#new_password" style="cursor:pointer;"></i>
                                        </span>
                                    </div>
                                </div>
 
                                <div class="col-md-6 mt-4">
                                    <label class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                                        <span class="input-group-text">
                                            <i class="bi bi-eye-slash toggle-password" data-target="#confirm_password" style="cursor:pointer;"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
 
                    <div class="col-12 d-flex justify-content-end gap-2 mt-5">
                        <a href="<?= base_url('admin/manage_user') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" id="saveUserBtn" class="btn btn-primary">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
 
 