<div class="container">
    <div class="page-inner">
        <div class="card">

            <div class="card-header">
                <h3 class="mb-0">Add New User</h3>
            </div>
            <div id="messageBox" class="alert d-none text-center" role="alert"></div>


            <div class="card-body">
                <div class="alert d-none text-center" role="alert"></div>

                <form id="userForm" method="post">
                    <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6 ">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-md-6 mt-4" >
                        <label class="form-label">User Role</label>
                        <select name="role_id" class="form-control" required>
                            <option value="">-- Select Role --</option>
                            <option value="1">Admin</option>
                            <option value="2">Editor</option>
                            <option value="3">User</option>
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