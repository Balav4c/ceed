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
                    <input type="hidden" name="module_id" value="<?= $module['module_id'] ?? '' ?>">
                    <input type="hidden" name="course_id" value="<?= $course_id ?? $module['course_id'] ?? '' ?>">
                    <input type="hidden" id="deleted_videos" name="deleted_videos" value="">
                    <div id="module-container">
                        <div class="module-item border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold">Module Name<span class="text-danger">*</span></label>
                                    <input type="text" name="module_name[]" class="form-control"
                                        value="<?= $module['module_name'] ?? '' ?>" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold">Duration (In Weeks)<span class="text-danger">*</span></label>
                                    <input type="number" name="module_duration[]" class="form-control"
                                        value="<?= $module['duration_weeks'] ?? '' ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class=" ">
                                        <label class="form-label fw-bold" for="example">Description</label>
                                        <textarea class="content" id="description" style="margin-top:12px;"
                                            name="module_description[]"><?= esc($module['description'] ?? '') ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="width-word">
                                        <label class="form-label fw-bold">Module Video</label>
                                        <input type="hidden" id="existing_videos" value="<?= esc($existingVideos ?? '') ?>">

                                        <input type="hidden" id="uploaded_videos" name="uploaded_videos"value="<?= esc($newUploadedVideos ?? '') ?>">

                                        <div id="fileUpload"></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="<?= base_url('admin/manage_course') ?>" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Save Module</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="videoTitle"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <video id="videoPlayer" width="100%" controls>
          <source src="" type="video/mp4">
          Your browser does not support HTML video.
        </video>
      </div>
    </div>
  </div>
</div>