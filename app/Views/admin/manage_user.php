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
    <div class="table-responsive">
        <table class="table " id="userTable">
            <thead class="table-light">
                <tr>
                    <th>Sl No</th>
                    <th>Name</th>
                    <th>User Roles</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">Are You Sure You Want To Delete This User?</div>
            <div class="modal-footer">
                <button type="button" id="confirm-delete-btn" class="btn btn-danger">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

</div>
        </div>
    </div>
</div>
