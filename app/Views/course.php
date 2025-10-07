<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Courses</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f6fa;
      color: #333;
      overflow-x: hidden;
    }

    .profile-name {
      font-weight: 500;
      color: #333;
    }

    .course-section {
      padding: 50px 0;
    }

    .course-card {
      background: #fff;
      border-radius: 16px;
      padding: 24px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      transition: all .2s ease;
      position: relative;
      height: 100%;
    }

    .course-card:hover {
      transform: scale(1.02);
    }

    .course-card.locked {
      opacity: 0.5;
      pointer-events: none;
      position: relative;
    }

    .course-card.locked::after {
      content: "🔒 Locked";
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: rgba(0, 0, 0, 0.6);
      color: #fff;
      padding: 6px 12px;
      border-radius: 10px;
      font-weight: 500;
      font-size: 14px;
    }

    .module-label {
      position: absolute;
      top: 15px;
      right: 15px;
      background: #0d6efd;
      color: #fff;
      padding: 2px 10px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 500;
    }

    .course-card h5 {
      font-weight: 600;
      margin-bottom: 10px;
    }

    .course-card p {
      font-size: 14px;
      color: #555;
      min-height: 60px;
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

    .status {
      font-weight: 500;
    }

    footer {
      background: #fff;
      text-align: center;
      padding: 20px 0;
      font-size: 14px;
      color: #666;
      border-top: 1px solid #eee;
      margin-top: 60px;
    }
  </style>
</head>
<body>

  <!-- Course Listing -->
  <section class="course-section">
    <div class="container">
      <div class="row g-4">

        <!-- Course 1 (Unlocked) -->
        <div class="col-md-6 col-lg-6" style="margin-bottom: 32px;">
          <div class="course-card">
            <span class="module-label">Start</span>
            <h5>Mind Setting For Change</h5>
            <p>This module teaches key concepts about growth and fixed mindsets, and goal setting that prepare the mind for commitment to change.</p>
            <div class="course-meta">
              <span>⏱ 4 Lessons • 4 Weeks</span>
              <span class="status text-success">Not Started</span>
            </div>
          </div>
        </div>

        <!-- Course 2 (Locked) -->
        <div class="col-md-6 col-lg-6" style="margin-bottom: 32px;">
          <div class="course-card locked">
            <span class="module-label" style="background:#5865f2;">Start</span>
            <h5>Understanding Mindset Difference</h5>
            <p>This module will teach participants about different mindset types and the ones that support growth.</p>
            <div class="course-meta">
              <span>⏱ 3 Lessons • 4 Weeks</span>
              <span class="status text-muted">Locked</span>
            </div>
          </div>
        </div>
    </div>
    <div class="row g-4">
        <!-- Course 3 (Locked) -->
        <div class="col-md-6 col-lg-6" style="margin-bottom: 32px;">
          <div class="course-card locked">
            <span class="module-label" style="background:#f39c12;">Start</span>
            <h5>Framing Mindsets</h5>
            <p>This module introduces participants to strategies they can use to train their minds to skew towards growth mindset thinking.</p>
            <div class="course-meta">
              <span>⏱ 4 Lessons • 4 Weeks</span>
              <span class="status text-muted">Locked</span>
            </div>
          </div>
        </div>

        <!-- Course 4 (Locked) -->
        <div class="col-md-6 col-lg-6" style="margin-bottom: 32px;">
          <div class="course-card locked">
            <span class="module-label" style="background:#e67e22;">Start</span>
            <h5>Creativity</h5>
            <p>Learn how to come up with creative ideas by keeping their options open when ideating.</p>
            <div class="course-meta">
              <span>⏱ 4 Lessons • 4 Weeks</span>
              <span class="status text-muted">Locked</span>
            </div>
          </div>
        </div>
    </div>
    <div class="row g-4">
        <!-- Course 5 (Locked) -->
        <div class="col-md-6 col-lg-6" style="margin-bottom: 32px;">
          <div class="course-card locked">
            <span class="module-label" style="background:#00b894;">Start</span>
            <h5>Goal Setting For Mastering Skills</h5>
            <p>This module ties everything the participants have done together and requires them to set goals on how they can continue daily habits.</p>
            <div class="course-meta">
              <span>⏱ 3 Lessons • 4 Weeks</span>
              <span class="status text-muted">Locked</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>