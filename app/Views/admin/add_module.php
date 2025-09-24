<div class="container">
    <div class="page-inner">
        <div class="card">
            <div id="messageBox" class="alert d-none text-center" role="alert"></div>
            <div class="card-header">
                <h3 class="mb-0"><?= isset($course['module_id']) ? 'Edit Module' : 'Add New Module' ?></h3>
            </div>
            <div class="card-body">
                <form id="moduleForm" method="post" action="<?= base_url('admin/save_module') ?>"
                    enctype="multipart/form-data">
                    <input type="hidden" name="course_id" value="<?= $course_id ?? '' ?>">
                   <input type="hidden" name="module_id" value="<?= $module->module_id ?? '' ?>">
                   <input type="hidden" id="uploaded_videos" name="uploaded_videos" value="">
                    <div id="module-container">
                        <div class="module-item border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label>Module Name<span class="text-danger">*</span></label>
                                    <input type="text" name="module_name[]" class="form-control" value="<?= $module['module_name'] ?? '' ?>" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label>Duration (Weeks)<span class="text-danger">*</span></label>
                                    <input type="text" name="module_duration[]" class="form-control" value="<?= $module['duration_weeks'] ?? '' ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="page-wrapper box-content width-word">
                                        <label for="example">Description</label>
                                        <textarea class="content" id="description" name="module_description[]"><?= esc($module['description'] ?? '') ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="page-wrapper box-content width-word">
                                        <label>Module Video</label>
                                        <div id="fileUpload" name="module_videos[]"  value="<?= $module['module_videos'] ?? '' ?>"></div>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-primary mb-3 " id="addModule">Add Module</button>
                    <button type="button" class="btn btn-danger mb-3 remove-module">Remove</button>
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="<?= base_url('admin/manage_module') ?>" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Save Module</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>