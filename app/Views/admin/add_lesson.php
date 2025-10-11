<div class="container">
    <div class="page-inner">
        <div class="card">
            <div id="messageBox" class="alert d-none text-center" role="alert"></div>
            <div class="card-header">
                <h3 class="mb-0"><?= isset($lesson) ? 'Edit Lesson' : 'Add New Lesson' ?></h3>
            </div>
            <div class="card-body">
                <form id="lessonForm">
                    <input type="hidden" id="deleted_videos" name="deleted_videos" value="">
                    <input type="hidden" name="module_id" value="<?= esc($module_id ?? '') ?>">
                    <input type="hidden" name="course_id" value="<?= esc($course_id ?? '') ?>">
                    <input type="hidden" name="lesson_id" value="<?= isset($lesson) ? $lesson['lesson_id'] : '' ?>">

                    <!-- Lesson Title -->
                    <div class="col-12 mb-3">
                        <label for="lesson_title" class="form-label fw-bold">Lesson Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="lesson_title" name="lesson_title"
                               placeholder="Enter lesson title" 
                               value="<?= isset($lesson) ? esc($lesson['lesson_title']) : '' ?>" required>
                    </div>

                    <!-- Lesson Videos -->
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Lesson Videos <span class="text-danger">*</span></label>
                        <input type="hidden" id="existing_videos" value="<?= esc($existingVideos ?? '') ?>">
                        <input type="hidden" id="uploaded_videos" name="uploaded_videos" value="<?= esc($newUploadedVideos ?? '') ?>">

                        <div id="fileUpload" class="d-flex flex-wrap gap-2">
                            <!-- Display existing videos if editing -->
                            <?php if(isset($existingVideos) && !empty($existingVideos)): ?>
                                <?php foreach(explode(',', $existingVideos) as $video): ?>
                                    <div class="existing-video card p-2 text-center" style="width: 12rem;">
                                        <video width="100%" height="150" controls>
                                            <source src="<?= base_url('public/uploads/videos/' . $video) ?>" type="video/mp4">
                                            Your browser does not support HTML video.
                                        </video>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <small class="text-truncate"><?= esc($video) ?></small>
                                            <button type="button" class="btn btn-sm btn-danger remove-existing-video" data-video="<?= esc($video) ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Form Buttons -->
                    <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                        <a href="<?= base_url('admin/manage_module/lessons/' . $module_id) ?>" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-primary">Save Lesson</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Video Modal -->
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

<!-- JS -->
<script>
$(document).ready(function() {
    // Remove existing video
    $(document).on('click', '.remove-existing-video', function() {
        const video = $(this).data('video');

        // Remove from existing_videos input
        let existing = $('#existing_videos').val().split(',').filter(v => v !== video);
        $('#existing_videos').val(existing.join(','));

        // Add to deleted_videos input for backend processing
        let deleted = $('#deleted_videos').val() ? $('#deleted_videos').val().split(',') : [];
        deleted.push(video);
        $('#deleted_videos').val(deleted.join(','));

        // Remove video card from DOM
        $(this).closest('.existing-video').remove();
    });

    // Example: handle form submission (AJAX or regular)
    $('#lessonForm').on('submit', function(e) {
        e.preventDefault();
        // collect form data and uploaded videos
        // submit via AJAX
    });
});
</script>
