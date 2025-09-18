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
<div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> <!-- use modal-lg for big descriptions -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="descriptionModalLabel">Course Description</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalDescription" style="white-space: pre-wrap;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

