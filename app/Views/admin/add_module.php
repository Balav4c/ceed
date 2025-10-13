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

                    <div id="module-container">
                        <div class="module-item  p-3 mb-3">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold">Module Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="module_name[]" class="form-control"
                                        value="<?= $module['module_name'] ?? '' ?>" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold">Duration (In Weeks)<span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="module_duration[]" class="form-control"
                                        value="<?= $module['duration_weeks'] ?? '' ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class=" ">
                                        <label class="form-label fw-bold" for="description">Description</label>
                                        <textarea class="content" id="description" style="margin-top:12px;"
                                            name="module_description[]"><?= esc($module['description'] ?? '') ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class=" ">
                                        <label class="form-label fw-bold" for="about">About</label>
                                        <textarea class="content" id="about" style="margin-top:12px;"
                                            name="module_about[]"><?= esc($module['about'] ?? '') ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:18px;">
                                <!-- Searchable Dropdown -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold" style="margin-bottom: 15px;">Select
                                        Teacher</label>
                                    <select name="teacher_id" id="teacher_id" class="form-control select2">
                                        <option value="">Select or Search...</option>
                                        <option value="AI">Artificial Intelligence</option>
                                        <option value="ML">Machine Learning</option>
                                        <option value="DS">Data Science</option>
                                        <option value="WD">Web Development</option>
                                        <option value="CS">Cyber Security</option>
                                    </select>
                                </div>
                                <!-- Normal Dropdown -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold" style="margin-bottom: 15px;">Module Level</label>
                                    <select class="form-select" name="module_level[]">
                                        <option value="">Select Level</option>
                                        <option value="Beginner" <?= (isset($module['module_level']) && strtolower($module['module_level']) === 'beginner') ||
                                            (isset($module->module_level) && strtolower($module->module_level) === 'beginner')
                                            ? 'selected' : '' ?>>
                                            Beginner
                                        </option>
                                        <option value="Master" <?= (isset($module['module_level']) && strtolower($module['module_level']) === 'master') ||
                                            (isset($module->module_level) && strtolower($module->module_level) === 'master')
                                            ? 'selected' : '' ?>>
                                            Master
                                        </option>
                                        <option value="Genius" <?= (isset($module['module_level']) && strtolower($module['module_level']) === 'genius') ||
                                            (isset($module->module_level) && strtolower($module->module_level) === 'genius')
                                            ? 'selected' : '' ?>>
                                            Genius
                                        </option>
                                    </select>

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
<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: "Select or search teacher",
            allowClear: true,
            width: '100%'
        });
    });
</script>