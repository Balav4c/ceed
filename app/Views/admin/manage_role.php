<div class="container">
  <div class="page-inner">
    <div class="row">
        <div class="form-control mb-3 right_container">
            <div id="messageBox" class="alert d-none text-center"  role="alert"></div>
            <div class="row align-items-center">
            <div class="col-12 col-md-6">
                <h3 class="mb-3 mb-md-0">Roles and Permissions</h3>
            </div>
            <div class="col-12 col-md-6 text-end">
            <a href="<?= base_url('admin/add_role') ?>" class="btn btn-secondary">Add New Role</a>
            </div>
            </div>
            <hr>
            <table class="table table-hover" id="roleTable">
                <thead class="table-light">
                    <tr>
                        <th>Sl No</th>
                        <th>Role Name</th>
                        <th>Permissions</th>
                        <th >Status</th>
                        <th >Action</th>
                        <th class="d-none">ID</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div> 
    </div> 
  </div> 
</div> 

