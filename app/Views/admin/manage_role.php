<div class="container">
    <div class="page-inner">
        <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Roles and Permissions</h3>
                
                    <div class="col-6 text-end mt-2">
                        <a href="<?= base_url('admin/add_role') ?>" class="btn btn-secondary">Add New Role</a>
                    </div>
                </div>
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