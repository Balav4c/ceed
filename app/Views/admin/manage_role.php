<div class="container">
     
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Add Roles</h3>
        </div>
         <div class="card-body">
            <form id="roleForm"  method="post" class="p-3">
                <input type="hidden" name="role_id" id="role_id" >
                <div class="mb-3">
                    <label for="role_name" class="form-label">Role Name <span class="text-danger">*</span></label>
                    <input type="text" name="role_name" id="role_name" class="form-control capitalize"
                         required>
                </div>
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Permissions</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="select_all_permissions">
                            <label class="form-check-label fw-bold" for="select_all_permissions">Select All</label>
                        </div>
                        <div class="row">
                            
                        </div>
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <a href="<?= base_url('admin/rolelist') ?>" class="btn btn-secondary">Discard</a>
                            <button type="submit" class="btn btn-primary enter-btn" id="saveBtn">Save Role</button>
                        </div>
                    
                    </div>
                </div>
            </form>
         </div> 
    </div>
</div>