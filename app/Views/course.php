<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Courses</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f6fa;
      color: #333;
      overflow-x: hidden;
    }

    .course-section {
      padding: 50px 0;
    }

    .course-card {
      background: #fff;
      border-radius: 7px;
      padding: 24px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
      transition: .2s;
      position: relative;
      height: 100%;
    }

    .course-card:hover {
      transform: scale(1.02);
    }

    .module-label {
      position: absolute;
      top: 15px;
      right: 15px;
      background: #0d6efd;
      color: #fff;
      padding: 4px 12px;
      border-radius: 4px;
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
    }

    .course-card h5 {
      font-weight: 600;
      margin-bottom: 10px;
    }

    .course-card p {
      font-size: 14px;
      color: #555;
    }

    .course-meta {
      display: flex;
      justify-content: space-between;
      font-size: 13px;
      color: #777;
      border-top: 1px solid #eee;
      margin-top: 10px;
      padding-top: 10px;
    }

    .truncate {
      overflow: hidden;
      display: block;
      width: 100%;
      height: 92px;
      margin-bottom: 60px;
    }

    .lock-screen {
      font-size: 29px;
      width: 45px;
      height: 45px;
      background: #e3711078;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 45px;
      margin: auto;
    }
  </style>
</head>

<body>

  <section class="course-section">
    <div class="container">
      <div class="row g-4">

        <?php if (!empty($courses)): ?>
          <?php
          $count = 0;
          foreach ($courses as $course):
            $count++;
            ?>
            <div class="col-md-6 col-lg-6" style="margin-bottom:32px;">
              <div class="course-card">
                <span class="module-label start-btn" data-index="<?= $count ?>"
                  data-url="<?= base_url('course/modules/' . $course['course_id']) ?>">Start</span>

                <h5><?= esc($course['name']) ?></h5>
                <div class="truncate"><?= $course['description'] ?></div>
                <div class="course-meta">
                  <span>⏱ <?= esc($course['duration_weeks']) ?> Weeks</span>
                  <span class="status text-success">Not Started</span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-center">No active courses found.</p>
        <?php endif; ?>

      </div>
    </div>
  </section>

  <!-- Locked Modal -->
  <div class="modal fade" id="lockedModal" tabindex="-1" aria-labelledby="lockedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="width: 407px;">
      <div class="modal-content text-center p-2" style="border-radius: 12px;">
        <div class="modal-body">
          <div class="lock-screen"><i class="bi bi-lock"></i></div>
          <h5 class="mt-3">Locked</h5>
          <p class="text-muted">Complete Course 1 to open the next level of your learning journey.</p>
          <!-- <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">OK</button> -->
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.querySelectorAll('.start-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const index = parseInt(this.dataset.index);
        const url = this.dataset.url;

        if (index === 1) {
          // First module is unlocked → go to module page
          window.location.href = url;
        } else {
          // Other modules → show locked popup
          const modal = new bootstrap.Modal(document.getElementById('lockedModal'));
          modal.show();
        }
      });
    });
  </script>

</body>

</html>