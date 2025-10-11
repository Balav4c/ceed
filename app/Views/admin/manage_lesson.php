<div class="container">
  <div class="page-inner">
    <div class="card">
      <input type="hidden" id="module_id" value="<?= esc($module['module_id']) ?>">
      <div id="messageBox" class="alert d-none text-center" role="alert"></div>

      <div class="card-header d-flex justify-content-between align-items-center">
        <h3>Lessons for <?= esc($module['module_name']) ?></h3>
        <div>
          <a href="javascript:void(0);" id="addLessonBtn" class="btn btn-primary">Add Lesson</a>
        </div>
      </div>

      <div class="accordion accordion-flush" id="lessonAccordion">
        <?php if (!empty($lessons)): ?>
          <?php
          // Group by lesson_title
          $grouped = [];
          foreach ($lessons as $lesson) {
            $grouped[$lesson['lesson_title']][] = $lesson;
          }
          $index = 1;
          ?>
          <?php foreach ($grouped as $lessonTitle => $lessonItems): ?>
            <div class="accordion-item">
              <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading<?= $index ?>">
                <button class="accordion-button collapsed flex-grow-1 text-start" type="button" data-bs-toggle="collapse"
                  data-bs-target="#collapse<?= $index ?>" aria-expanded="false" aria-controls="collapse<?= $index ?>">
                  <?= esc($lessonTitle) ?>
                </button>
                <div class="ms-2 d-flex gap-2">
                  <a href="<?= base_url('admin/edit_lesson/' . $lessonItems[0]['lesson_id']) ?>"
                    class="btn btn-sm btn-primary" title="Edit">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-lesson"
                    data-id="<?= $lessonItems[0]['lesson_id'] ?>" title="Delete">
                    <i class="bi bi-trash"></i>
                  </a>
                </div>
              </h2>
              <div id="collapse<?= $index ?>" class="accordion-collapse collapse" data-bs-parent="#lessonAccordion">
                <div class="accordion-body">
                  <div class="row g-4">
                    <?php foreach ($lessonItems as $lesson):
                      $videos = json_decode($lesson['videos'], true) ?? [];
                      ?>
                      <?php foreach ($videos as $video): ?>
                        <div class="col-md-3">
                          <div class="card h-100 text-center">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                              <h6 class="card-title mb-2"><?= esc($video['name']) ?></h6>
                              <a href="javascript:void(0);" class="play-video mt-2"
                                data-url="<?= base_url('public/uploads/videos/' . $video['link']) ?>">
                                <i class="bi bi-play-circle"></i> Play
                              </a>
                            </div>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            </div>
            <?php $index++; ?>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="p-3 text-center text-muted">No lessons found for this module.</div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>

<!-- Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="videoModalLabel">Play Lesson Video</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <video id="videoPlayer" width="100%" height="400" controls>
          <source src="" type="video/mp4">
          Your browser does not support HTML video.
        </video>
      </div>
    </div>
  </div>
</div>

<script>
  const base_url = "<?= base_url() ?>";

  document.getElementById('addLessonBtn').addEventListener('click', function () {
    const moduleId = document.getElementById('module_id').value;
    if (moduleId) {
      window.location.href = base_url + 'admin/add_lesson/' + moduleId;
    } else {
      alert('Module ID not found!');
    }
  });

  // Play video in modal
  document.addEventListener('click', function (e) {
    if (e.target.closest('.play-video')) {
      const videoUrl = e.target.closest('.play-video').getAttribute('data-url');
      const videoPlayer = document.getElementById('videoPlayer');
      videoPlayer.src = videoUrl;
      const modal = new bootstrap.Modal(document.getElementById('videoModal'));
      modal.show();
    }
  });

  // Stop video when modal is closed
  document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
    const videoPlayer = document.getElementById('videoPlayer');
    videoPlayer.pause();
    videoPlayer.src = '';
  });
</script>