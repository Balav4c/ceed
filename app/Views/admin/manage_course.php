<div class="container">
    <div class="page-inner">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Manage Courses</h3>
                <a href="<?= base_url('admin/add_course') ?>" class="btn btn-primary">+ Add Course</a>
            </div>
            <div class="card-body">
                <table id="courseTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name/Title</th>
                            <th>Description</th>
                            <th>Duration (weeks)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    $('#courseTable').DataTable({
        ajax: "<?= base_url('admin/manage_course/courselistajax') ?>",
        columns: [
            { data: "course_id" },
            { data: "name" },
            { data: "description" },
            { data: "duration_weeks" },
            {
                data: "course_id",
                render: function(id) {
                    return `
                        <a href="<?= base_url('admin/manage_course/form/') ?>${id}" class="btn btn-sm btn-info">Edit</a>
                        <button class="btn btn-sm btn-danger delete-course" data-id="${id}">Delete</button>
                    `;
                }
            }
        ]
    });

    $(document).on('click', '.delete-course', function() {
        let id = $(this).data('id');
        if (confirm("Are you sure?")) {
            $.post("<?= base_url('admin/manage_course/delete/') ?>" + id, function(res) {
                alert(res.message);
                $('#courseTable').DataTable().ajax.reload();
            });
        }
    });
});
</script>
