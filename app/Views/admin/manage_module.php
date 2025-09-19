<div class="container">
    <div class="page-inner">
        <div class="card">
            <div id="messageBox" class="alert d-none text-center"  role="alert"></div>
            <div class="card-header d-flex justify-content-between">
                <h3>Manage Modules</h3>
                <a href="<?= base_url('admin/manage_course') ?>" class="btn btn-primary">Back To Course</a>
            </div>
            <div class="card-body">
                <table id="moduleTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SI NO</th>
                            <th>Module Name</th>
                            <th>Description</th>
                            <th>Duration (weeks)</th>
                            <th>Module Videos</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
