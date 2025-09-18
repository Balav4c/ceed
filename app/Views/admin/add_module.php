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

                    <div id="module-container">
                        <div class="module-item border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label>Module Name</label>
                                    <input type="text" name="module_name[]" class="form-control" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label>Duration (Weeks)</label>
                                    <input type="text" name="module_duration[]" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="page-wrapper box-content width-word">
                                        <label for="example">Description</label>
                                        <textarea class="content" id="example" name="example"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="page-wrapper box-content width-word">
                                        <!-- <ins class="adsbygoogle" style="display:block"
                                            data-ad-client="ca-pub-2783044520727903" data-ad-slot="7325992188"
                                            data-ad-format="auto" data-full-width-responsive="true"></ins>
                                        <script>
                                            (adsbygoogle = window.adsbygoogle || []).push({});
                                        </script> -->
                                        <label>Module Video</label>
                                        <div id="fileUpload"></div>
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