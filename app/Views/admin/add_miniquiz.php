<div class="container">
    <div class="page-inner">
        <div class="card">
            <div id="messageBox" class="alert d-none text-center" role="alert"></div>
            <div class="card-header">
                <h3 class="mb-0"><?= isset($quiz) ? 'Edit Quiz' : 'Add New Quiz' ?></h3>
            </div>
            <div class="card-body">
                <form id="quizForm" method="post" action="<?= base_url('admin/mini_quiz/save') ?>">
                    <input type="hidden" name="quiz_id" value="<?= $quiz['quiz_id'] ?? '' ?>">
                    <input type="hidden" name="course_id" value="<?= $course_id ?? '' ?>">
                    <input type="hidden" name="module_id" value="<?= $module_id ?? '' ?>">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label>Course</label>
                            <select name="course_id" class="form-select">
                                <option value="">Select Course</option>
                                <!-- Populate dynamically -->
                            </select>
                        </div>

                        <div class="col-6 mb-3">
                            <label>Module</label>
                            <select name="module_id" class="form-select">
                                <option value="">Select Module</option>
                                <!-- Populate dynamically -->
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Question</label>
                        <textarea name="question_text"
                            class="form-control"><?= $quiz['question_text'] ?? '' ?></textarea>
                    </div>

                    <?php
                    $options = $quiz['options'] ?? ['A' => '', 'B' => '', 'C' => '', 'D' => ''];
                    $opts = ['A', 'B', 'C', 'D'];
                    for ($i = 0; $i < count($opts); $i += 2): ?>
                        <div class="row mb-3">
                            <?php for ($j = 0; $j < 2; $j++):
                                $opt = $opts[$i + $j] ?? null;
                                if (!$opt)
                                    continue;
                                ?>
                                <div class="col">
                                    <label>Option <?= $opt ?></label>
                                    <input type="text" name="option_<?= strtolower($opt) ?>"
                                        value="<?= esc($options[$opt] ?? '') ?>" class="form-control">
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php endfor; ?>

                    <div class="col-6 mb-3">
                        <label>Correct Answer</label>
                        <select name="correct_answer" class="form-select">
                            <option value="">Select</option>
                            <?php foreach (['A', 'B', 'C', 'D'] as $opt): ?>
                                <option value="<?= $opt ?>" <?= (isset($quiz['correct_answer']) && $quiz['correct_answer'] == $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                        <a href="<?= base_url('admin/manage_miniquiz') ?>" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Save Quiz</button>
                    </div>
            </div>
        </div>
    </div>