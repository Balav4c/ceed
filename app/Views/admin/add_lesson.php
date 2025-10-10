<div class="container">
    <div class="page-inner">
        <div class="card">
            <div id="messageBox" class="alert d-none text-center" role="alert"></div>
            <div class="card-header">
                <h3 class="mb-0"><?= isset($course['module_id']) ? 'Edit Lesson' : 'Add New Lesson' ?></h3>
            </div>
            <div class="card-body">
                <form id="lessonForm">
                    <input type="hidden" id="deleted_videos" name="deleted_videos" value="">
                    <input type="hidden" name="module_id" value="<?= esc($module_id ?? '') ?>">
                    <input type="hidden" name="course_id" value="<?= esc($course_id ?? '') ?>">
                    <div class="col-12 mb-3">
                        <label for="lesson_title" class="form-label fw-bold">Lesson Title <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="lesson_title" name="lesson_title"
                            placeholder="Enter lesson title" required>
                    </div>
                    <div class="col-12 ">
                        <div class="width-word">
                            <label class="form-label fw-bold">Lesson Video <span class="text-danger">*</span></label>
                            <input type="hidden" id="existing_videos" value="<?= esc($existingVideos ?? '') ?>">

                            <input type="hidden" id="uploaded_videos" name="uploaded_videos"
                                value="<?= esc($newUploadedVideos ?? '') ?>">

                            <div id="fileUpload"></div>

                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                        <a href="<?= base_url('admin/manage_course') ?>" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-primary">Save Lesson</button>
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