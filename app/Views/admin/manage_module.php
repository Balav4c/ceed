<div class="container">
    <div class="page-inner">
        <div class="card">
            <div id="messageBox" class="alert d-none text-center"  role="alert"></div>
            <div class="card-header d-flex justify-content-between">
                <h3>Modules For <?= esc($course['name']) ?></h3>
                <a href="<?= base_url('admin/manage_course') ?>" class="btn btn-primary">Back To Course</a>
            </div>
            <input type="hidden" id="course_id" value="<?= esc($course['course_id']) ?>">
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
<div class="modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- lg for wide -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="descriptionModalLabel">Course Description</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalDescription">
        <!-- Description text will be injected here -->
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="videoTitle"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <video id="videoPlayer" width="100%" controls>
          <source src="" type="video/mp4">
          Your browser does not support HTML video.
        </video>
      </div>
    </div>
  </div>
</div>





