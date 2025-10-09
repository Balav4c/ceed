<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mindsetting For Change</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .course-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .course-badge {
            font-size: 0.8rem;
            margin-right: 8px;
        }

        .btn-start {
            background-color: #28a745;
            border: none;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 6px;
        }

        .lesson-list .list-group-item {
            border: none;
            border-bottom: 1px solid #e9ecef;
            padding: 12px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .lesson-list .list-group-item.active {
            background-color: transparent;
            font-weight: 600;
            color: #198754;
        }

        .lesson-icon {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 2px solid #adb5bd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            color: #adb5bd;
        }

        .lesson-list .list-group-item.active .lesson-icon {
            background-color: #198754;
            color: #fff;
            border: none;
        }

        .truncate {
            /* white-space: nowrap; */
            overflow: hidden;
            /* text-overflow: ellipsis; */
            display: block;
            width: 100%;
            /* or fixed px value */
            height: 80px;
            margin-bottom: 60px;
        }

        .b-head {
            position: relative;
            left: 200px;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="b-head">
            <ol class="breadcrumb mb-3">
                <li class="breadcrumb-item">Course</li>
                <li class="breadcrumb-item active" aria-current="page"><?= esc($module['module_name']) ?></li>
            </ol>
        </nav>

        <div class="course-card mx-auto col-lg-8 col-md-10 col-12">

            <h3 class="fw-bold mb-2"><?= esc($module['module_name']) ?></h3>

            <div class="mb-3">
                <span class="badge bg-light text-dark course-badge">⏱ <?= esc($module['duration_weeks']) ?> Weeks</span>
                <span class="badge bg-light text-dark course-badge">🔰 Beginner</span>
                <span class="badge bg-light text-dark course-badge">📘 4 Lessons</span>
            </div>

            <div class="truncate"><?= $module['description'] ?></div>

            <a href="<?= base_url('course/lesson/' . $module['module_id']); ?>" class="btn btn-start mb-4">START COURSE</a>

            <ul class="nav nav-tabs mb-4" id="courseTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="content-tab" data-bs-toggle="tab" data-bs-target="#content"
                        type="button" role="tab">Content</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button"
                        role="tab">About</button>
                </li>
            </ul>

            <div class="tab-content" id="courseTabsContent">
                <div class="tab-pane fade show active" id="content" role="tabpanel">
                    <div class="lesson-list list-group">
                        <div class="list-group-item active">
                            <div class="lesson-icon">✔</div> Course Introduction
                        </div>
                        <div class="list-group-item">
                            <div class="lesson-icon">▶</div> Neuroplasticity
                        </div>
                        <div class="list-group-item">
                            <div class="lesson-icon">▶</div> Grit
                        </div>
                        <div class="list-group-item">
                            <div class="lesson-icon">▶</div> Growth Mindset
                        </div>
                        <div class="list-group-item">
                            <div class="lesson-icon">▶</div> Fear Setting
                        </div>
                        <div class="list-group-item">
                            <div class="lesson-icon">▶</div> Who Am I?
                        </div>
                        <div class="list-group-item">
                            <div class="lesson-icon">▶</div> Knowledge Check
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="about" role="tabpanel">
                    <p>This course will help you understand the psychology of mindset and personal development.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>