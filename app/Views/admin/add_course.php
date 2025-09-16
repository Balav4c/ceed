<div class="container">
    <div class="page-inner">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0"><?= isset($course['course_id']) ? 'Edit Course' : 'Add Course' ?></h3>
            </div>
            <div class="card-body">
                <form id="courseForm">
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

                    <h5>Modules</h5>
                    <table class="table" id="moduleTable">
                        <thead>
                            <tr>
                                <th>Module Name</th>
                                <th>Duration (e.g. week 1, week 2-3)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($modules)): ?>
                                <?php foreach ($modules as $m): ?>
                                    <tr>
                                        <td><input type="text" name="module_name[]" class="form-control" value="<?= $m['module_name'] ?>"></td>
                                        <td><input type="text" name="module_duration[]" class="form-control" value="<?= $m['duration_weeks'] ?>"></td>
                                        <td><button type="button" class="btn btn-danger remove-module">X</button></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-sm btn-primary" id="addModule">Add Module</button>

                    <br><br>
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="<?= base_url('admin/manage_course') ?>" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$('#addModule').click(function() {
    $('#moduleTable tbody').append(`
        <tr>
            <td><input type="text" name="module_name[]" class="form-control" required></td>
            <td><input type="text" name="module_duration[]" class="form-control"></td>
            <td><button type="button" class="btn btn-danger remove-module">X</button></td>
        </tr>
    `);
});

$(document).on('click', '.remove-module', function() {
    $(this).closest('tr').remove();
});

$('#courseForm').on('submit', function(e) {
    e.preventDefault();
    $.post("<?= base_url('admin/manage_course/store') ?>",  $(this).serialize(), function(res) {
        alert(res.message);
        if (res.status === 'success') {
            window.location.href = "<?= base_url('admin/manage_course') ?>";
        }
    });
});
</script>
