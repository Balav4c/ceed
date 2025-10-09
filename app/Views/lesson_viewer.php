<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($module['module_name']) ?> - Lessons</title>
    <link rel="icon" href="<?php echo base_url() . ASSET_PATH; ?>admin/assets/img/logo.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f3f5;
            font-family: 'Inter', sans-serif;
        }

        .lesson-container {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: #fff;
            border-right: 1px solid #e9ecef;
            padding: 25px;
            overflow-y: auto;
        }

        .sidebar h6 {
            font-weight: 700;
            margin-bottom: 20px;
        }

        .lesson-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .lesson-list li {
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 5px;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .lesson-list li.active {
            background-color: #e6f4ea;
            color: #198754;
            font-weight: 600;
        }

        .main-content {
            flex: 1;
            background: #fff;
            padding: 30px;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 15px;
        }

        .video-player {
            background: #000;
            border-radius: 10px;
            overflow: hidden;
        }

        .btn-next {
            color: #7e8186;
            text-decoration: none;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .btn-back {
            border: 1px solid #adb5bd;
            background: transparent;
            padding: 5px 12px;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .lesson-list li.locked {
            pointer-events: none;
            opacity: 0.5;
        }

        .lesson-list li.active {
            font-weight: bold;
        }

        .status-icon {
            margin-left: 10px;
        }

        .status-icon.completed::after {
            content: "✔";
            /* tick mark */
            color: green;
        }

        .btn-back {
            color: #7e8186;
            text-decoration: none;
        }

        .btn-text {
            color: #6c6f74e8;
            text-decoration: none;
            border: 2px solid #80808026;
            border-radius: 4px;
        }

        .btn-back:hover {
            text-decoration: none;
            color: #7e8186;
        }
    </style>
</head>

<body>

    <div class="lesson-container">

        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo mb-4">
                <img src="<?php echo base_url() . ASSET_PATH; ?>assets/img/logo.png" style="width: 50%;" />
            </div>

            <h6><?= esc($module['module_name']) ?></h6>
            <ul class="lesson-list" id="lessonList">
                <?php if (!empty($videos)): ?>
                    <?php foreach ($videos as $index => $video): ?>
                        <li class="<?= $index === 0 ? 'active' : 'locked' ?>"
                            data-src="<?= base_url('public/uploads/videos/' . trim($video['video_file'])) ?>"
                            data-index="<?= $index ?>">
                            <span class="status-icon"></span>
                            <?= esc($video['title'] ?? 'Lesson ' . ($index + 1)) ?>

                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No videos available for this module.</li>
                <?php endif; ?>
            </ul>


        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="top-bar">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#"><?= esc($module['module_name']) ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lessons</li>
                    </ol>
                </nav>
                <a href="<?= base_url('course'); ?>" class="btn-back">BACK TO LMS</a>

            </div>
            <div class="video-player mb-3">
                <video id="lessonVideo" controls width="100%" height="420">
                    <?php if (!empty($videos)): ?>
                        <source src="<?= base_url('public/uploads/videos/' . trim($videos[0]['video_file'])) ?>"
                            type="video/mp4">
                    <?php endif; ?>

                    Your browser does not support the video tag.
                </video>
            </div>

            <div class="d-flex justify-content-center mt-4">
                <button class="btn-text" id="nextLessonBtn">
                    Continue To Next Lesson
                </button>
            </div>

        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const lessonList = document.getElementById("lessonList");
            const lessonVideo = document.getElementById("lessonVideo");
            const nextBtn = document.getElementById("nextLessonBtn");
            const lessons = lessonList.querySelectorAll("li");

            let currentIndex = 0;
            function playLesson(index) {
                const videoSrc = lessons[index].dataset.src;
                lessonVideo.src = videoSrc;

                lessons.forEach(li => li.classList.remove("active"));
                lessons[index].classList.add("active");

                currentIndex = index;
                lessonVideo.play();
            }
            lessons.forEach((lesson, index) => {
                lesson.addEventListener("click", function () {
                    if (!lesson.classList.contains("locked")) {
                        playLesson(index);
                    }
                });
            });
            lessonVideo.addEventListener("ended", function () {
                const current = lessons[currentIndex];
                current.classList.remove("active");
                current.classList.add("completed");
                current.querySelector(".status-icon").classList.add("completed");

                const nextIndex = currentIndex + 1;
                if (nextIndex < lessons.length) {
                    lessons[nextIndex].classList.remove("locked");
                }
            });
            nextBtn.addEventListener("click", function () {
                const nextIndex = currentIndex + 1;
                if (nextIndex < lessons.length && !lessons[nextIndex].classList.contains("locked")) {
                    playLesson(nextIndex);
                }
            });
            lessons[0].classList.remove("locked");
            playLesson(0);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>