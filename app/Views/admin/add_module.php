 <!-- <table class="table table-bordered mt-4" id="moduleTable">
    <thead>
        <tr>
            <th>Module Name</th>
            <th>Duration</th>
            <th>Description</th>
            <th>Game Video</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="module-container">
        <?php if (isset($modules) && count($modules) > 0): ?>
            <?php foreach ($modules as $m): ?>
                <tr class="module-row">
                    <td>
                        <input type="text" name="module_name[]" class="form-control"
                               value="<?= $m['module_name'] ?>">
                    </td>
                    <td>
                        <input type="text" name="module_duration[]" class="form-control"
                               value="<?= $m['duration_weeks'] ?>">
                    </td>
                    <td>
                        <textarea name="module_description[]" class="form-control"
                                  rows="2" placeholder="Enter Description"><?= $m['description'] ?? '' ?></textarea>
                    </td>
                    <td>
                        <?php if (!empty($m['game_videos'])): ?>
                            <p class="mb-1">
                                <a href="<?= base_url('uploads/videos/' . $m['game_videos']) ?>" target="_blank">View Video</a>
                            </p>
                        <?php endif; ?>
                        <input type="file" name="module_videos[]" class="form-control">
                    </td>
                    <td class="text-center">
                        <span class="remove-module-btn" title="Remove">
                            <i class="fas fa-trash text-danger"></i>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr class="module-row">
                <td><input type="text" name="module_name[]" class="form-control" placeholder="Enter Module Name"></td>
                <td><input type="text" name="module_duration[]" class="form-control" placeholder="e.g. Week 1, Week 2-3"></td>
                <td><textarea name="module_description[]" class="form-control" rows="2" placeholder="Enter Description"></textarea></td>
                <td><input type="file" name="module_videos[]" class="form-control"></td>
                <td class="text-center">
                    <span class="remove-module-btn" title="Remove">
                        <i class="fas fa-trash text-danger"></i>
                    </span>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table> -->
<div class="container">
    <div class="page-inner">
        <div class="card">
            <div id="messageBox" class="alert d-none text-center" role="alert"></div>
            <div class="card-header">
                <h3 class="mb-0"><?= isset($course['module_id']) ? 'Edit Module' : 'Add New Module' ?></h3>
            </div>
            <div class="card-body">
                <form id="moduleForm" method="post" action="<?= base_url('admin/save_module') ?>" enctype="multipart/form-data">
                    <input type="text" name="course_id" value="<?= $course_id ?? '' ?>">

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
                                <div class="col-6 mb-3">
                                    <label>Description</label>
                                    <textarea name="module_description[]" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="col-6 mb-3">
                                    <label>Module Video</label>
                                    <input type="file" name="module_videos[]" class="form-control">
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm remove-module">Remove</button>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-primary mb-3" id="addModule">Add Module</button>

                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="<?= base_url('admin/manage_module') ?>" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Save Module</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
