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
                            <th>Course Name</th>
                            <th>Description</th>
                            <th>Duration(weeks)</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Description Modal -->

<div class="modal fade" id="descriptionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Course Description</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="descriptionContent"></div>
    </div>
  </div>
</div>

