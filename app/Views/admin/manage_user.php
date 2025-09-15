<div class="container">
  <div class="page-inner">
    <div class="row">
      <div class="form-control mb-3 right_container">
        
        <div class="alert d-none text-center position-fixed" role="alert"></div>

        <div class="row align-items-center">
          <div class="col-12 col-md-6">
            <h3 class="mb-3 mb-md-0">Manage User</h3>
          </div>
          <div class="col-12 col-md-6 text-end">
            <a href="<?= base_url('admin/adduser') ?>" 
               class="btn btn-secondary w-md-auto mt-2 mt-md-0">
              Add New User
            </a>
          </div>
        </div>

        <hr>

        <!-- User table -->
        <table class="table table-hover" id="userTable">
          <thead class="table-light">
            <tr>
              <th>Sl No</th>
              <th>Name</th>
              <th>Email</th>
              <th>User Roles</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

      </div> 
    </div> 
  </div> 
</div> 

