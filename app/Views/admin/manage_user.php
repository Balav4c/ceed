<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="form-control mb-3 right_container">
                <div class="alert d-none text-center position-fixed" role="alert"></div>
                <div class="row align-items-center">
                    <div class="col-12 col-md-6">
                        <h3 class="mb-3 mb-md-0">Mange User</h3>
                    </div>
                <div class="col-12 col-md-6 text-end">
                <a href="<?= base_url('admin/adduser') ?>" class="btn btn-secondary  w-md-auto mt-2 mt-md-0">Add New User</a>
            </div>
        </div>
        <hr>
    <!-- Responsive table wrapper -->
    <!-- <div class="table-responsive"> -->
        <table class="table " id="userTable">
            <thead class="table-light">
                <tr>
                    <th>Sl No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>User Roles</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    <!-- </div> -->
</div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this user?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirm-delete-btn">Delete</button>
      </div>
    </div>
  </div>
</div>

</div>
        </div>
    </div>
</div>
