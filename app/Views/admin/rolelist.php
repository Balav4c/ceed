<div class="container">
    <div class="card">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3 class="mb-0">Roles and Permissions</h3>
            </div>
            <div class="col-md-6 text-end mt-2">
                <a href="<?= base_url('admin/rolelist') ?>" class="btn btn-secondary">Add New Role</a>
            </div>
             <hr>
            <div class="table-responsive">
                <table class="table table-bordered" id="roleTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Role Name</th>
                            <th>Permissions</th>
                            <th style="width: 110px;">Created Date</th>
                            <th  style="width: 110px;">Updated Date</th>
                            <th style="width: 160px;">Action</th>
                            <th class="d-none">ID</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
    <hr>