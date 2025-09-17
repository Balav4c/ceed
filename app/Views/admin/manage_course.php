<div class="container">
    <div class="page-inner">
        <div class="card">
            <div id="messageBox" class="alert d-none text-center"  role="alert"></div>
            <div class="card-header d-flex justify-content-between">
                <h3>Manage Courses</h3>
                <a href="<?= base_url('admin/add_course') ?>" class="btn btn-primary">Add Course</a>
            </div>
            <div class="card-body">
                <table id="courseTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SI NO</th>
                            <th>Name/Title</th>
                            <th>Description</th>
                            <th>Duration (weeks)</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
