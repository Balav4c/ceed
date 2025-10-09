<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= esc($course['name']) ?> - Modules</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f6fa;
      color: #333;
    }

    .course-section {
      padding: 40px 0;
    }

    .course-card {
      background: #fff;
      border-radius: 7px;
      padding: 24px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
      transition: all .2s ease;
      position: relative;
      height: 100%;
      cursor: pointer;
    }

    .course-card:hover {
      transform: scale(1.02);
    }

    .module-label {
      position: absolute;
      top: 15px;
      right: 15px;
      color: #fff;
      padding: 4px 12px;
      border-radius: 4px;
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
    }

    .course-card h5 {
      font-weight: 500;
      margin-bottom: 10px;
    }

    .course-card p {
      margin-bottom: 5rem;
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
      flex-wrap: wrap;
      gap: 5px;
    }

    .truncate {
      overflow: hidden;
      display: block;
      width: 100%;
      height: 70px;
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

    .level-icon {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      font-size: 13px;
      letter-spacing: 0.3px;
    }
  </style>
</head>

<body>

  <div class="container course-section">
    <div class="row g-4">
      <?php
      $moduleNumber = 1;
      foreach ($modules as $mod):
          // Determine label color
          if ($moduleNumber <= 2) {
              $labelClass = 'btn-primary';
          } elseif ($moduleNumber <= 4) {
              $labelClass = 'btn-warning';
          } else {
              $labelClass = 'btn-info';
          }
      ?>
        <div class="col-md-6 col-lg-6">
          <div class="course-card start-module"
               data-index="<?= $moduleNumber ?>"
               data-url="<?= base_url('course/module/' . $mod['module_id']) ?>">

            <span class="module-label btn <?= $labelClass ?>">
              Module <?= $moduleNumber ?>
            </span>

            <h5><?= esc($mod['module_name']) ?></h5>
            <div class="truncate"><?= $mod['description'] ?></div>

            <div class="course-meta">
              <span>⏱ <?= $mod['completed'] ? 'Completed' : 'Not Started' ?></span>

              <?php if (!empty($mod['level'])): ?>
                <?php
                  $level = strtolower($mod['level']);
                  $icon = '';
                  if ($level === 'beginner') {
                      $icon = '▲';
                  } elseif ($level === 'intermediate') {
                      $icon = '◆';
                  } elseif ($level === 'genius') {
                      $icon = '⬟';
                  }
                ?>
                <span class="level-icon">
                  <span class="text-warning"><?= $icon ?></span>
                  <?= ucfirst($level) ?>
                </span>
              <?php endif; ?>

              <span><?= esc($mod['lessons_count']) ?> Lessons</span>
              <span><i class="bi bi-hourglass"></i> <?= esc($mod['duration_weeks']) ?> Weeks</span>
            </div>
          </div>
        </div>
      <?php
        $moduleNumber++;
      endforeach;
      ?>
    </div>
  </div>

  <!-- Locked Modal -->
  <div class="modal fade" id="lockedModal" tabindex="-1" aria-labelledby="lockedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
      <div class="modal-content text-center p-4" style="border-radius: 12px;">
        <div class="modal-body">
          <div class="lock-screen"><i class="bi bi-lock"></i></div>
          <h5 class="mt-3">Locked</h5>
          <p class="text-muted">Complete Module 1 to unlock course and games.</p>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.querySelectorAll('.start-module').forEach(card => {
      card.addEventListener('click', function () {
        const index = parseInt(this.dataset.index);
        const url = this.dataset.url;

        if (index === 1) {
          // ✅ First module is unlocked
          window.location.href = url;
        } else {
          // 🔒 Show locked modal for all others
          const modal = new bootstrap.Modal(document.getElementById('lockedModal'));
          modal.show();
        }
      });
    });
  </script>

</body>

</html>
