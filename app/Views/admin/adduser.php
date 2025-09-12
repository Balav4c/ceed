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
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <!-- <div class="mb-3">
                <label class="form-label">User Roles</label>
                <select name="role_id" class="form-control" required>
                                                <option value="">-- Select Role --</option></select>
              </div> -->
              <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
              </div>

              <button type="submit" id="saveUserBtn" class="btn btn-success">Save User</button>
              <a href="<?= base_url('admin/manage_user') ?>" class="btn btn-secondary">Cancel</a>
            </form>
          </div>
        </div>

      </div>
    </div>

  </div>

