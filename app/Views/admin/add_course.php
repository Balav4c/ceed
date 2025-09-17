<div class="container">
    <div class="page-inner">
        <div class="card">
            <div id="messageBox" class="alert d-none text-center"  role="alert"></div>
            <div class="card-header">
                <h3 class="mb-0"><?= isset($course['course_id']) ? 'Edit Course' : 'Add Course' ?></h3>
            </div>
            <div class="card-body">
                <form id="courseForm" action="<?= base_url('admin/manage_course/save') ?>" method="post">
                    <input type="hidden" name="course_id" value="<?= $course['course_id'] ?? '' ?>">

                    <div class="mb-3">
                        <label>Name/Title</label>
                        <input type="text" name="name" class="form-control" value="<?= $course['name'] ?? '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" maxlength="250" class="form-control"><?= $course['description'] ?? '' ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Total Duration (weeks)</label>
                        <input type="number" name="duration_weeks" class="form-control" value="<?= $course['duration_weeks'] ?? '' ?>" required>
                    </div>

                    <!-- <h5>Add Modules</h5> -->
                    <!-- <div class="table-responsive"> -->
                    <table class="table table-bordered mt-4" id="moduleTable">
                        <thead>
                            <tr>
                                <th>Module Name</th>
                                <th>Duration</th> 
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="module-container">
                            <?php if (isset($modules) && count($modules) > 0): ?>
                                <?php foreach ($modules as $m): ?>
                                    <tr class="module-row">
                                        <td><input type="text" name="module_name[]" class="form-control"
                                                value="<?= $m['module_name'] ?>"></td>
                                        <td><input type="text" name="module_duration[]" class="form-control"
                                                value="<?= $m['duration_weeks'] ?>"></td>
                                        <td class="text-center">
                                            <span class="remove-module-btn" title="Remove">
                                                <i class="fas fa-trash text-danger"></i>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="module-row">
                                    <td><input type="text" name="module_name[]" class="form-control"
                                            placeholder="Enter Module Name"></td>
                                    <td><input type="text" name="module_duration[]" class="form-control"
                                            placeholder="e.g. Week 1, Week 2-3"></td>
                                    <td class="text-center">
                                        <span class="remove-module-btn" title="Remove">
                                            <i class="fas fa-trash text-danger"></i>
                                        </span>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-outline-primary mb-34" id="addModule">Add Module</button>
                    <br><br>
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="<?= base_url('admin/manage_course') ?>" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


