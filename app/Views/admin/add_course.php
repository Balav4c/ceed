<div class="container">
    <div class="page-inner">
        <div class="card">
            <div id="messageBox" class="alert d-none text-center" role="alert"></div>
            <div class="card-header">
                <h3 class="mb-0"><?= isset($course['course_id']) ? 'Edit Course' : 'Add Course' ?></h3>
            </div>
            <div class="card-body">
                <form id="courseForm" action="<?= base_url('admin/manage_course/save') ?>" method="post">
                    <input type="hidden" name="course_id" value="<?= $course['course_id'] ?? '' ?>">

                    <div class="mb-3">
                        <label>Name/Title</label>
                        <input type="text" name="name" class="form-control" value="<?= $course['name'] ?? '' ?>"
                            required>
                    </div>

                    <!-- <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="4"><?= $course['description'] ?? '' ?></textarea>
                    </div> -->
                    <div class="page-wrapper box-content width-word">

                        <label for="example">Description</label>
                        <textarea class="content" id="example" name="example"></textarea>

                    </div>

                    <div class="mb-3">
                        <label>Total Duration (In Weeks)</label>
                        <input type="number" name="duration_weeks" class="form-control"
                            value="<?= $course['duration_weeks'] ?? '' ?>" required>
                    </div>
                    <br><br>
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="<?= base_url('admin/manage_course') ?>" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                        <!-- <a href="<?= base_url('admin/add_module') ?>" class="btn btn-primary">Add Module</a> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>