<div class="container">
    <div class="page-inner">
        <div class="row">
<div class="container mt-6">
  <div class="page-inner">
    <div class="row justify-content-center">
      <div class="col-md-8">

        <div class="card shadow-lg">
          <div class="card-header">
            <h3 class="mb-0">Add New User</h3>
          </div>

          <div class="card-body">
            <div class="alert d-none text-center" role="alert"></div>

            <form method="post">
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
              </div>

              <button type="submit" class="btn btn-success">Save User</button>
              <a href="<?= base_url('admin/manage_user') ?>" class="btn btn-secondary">Cancel</a>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
</div>
  </div>
</div>
