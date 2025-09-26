<div class="container">
    <div class="page-inner">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Modules for: <?= esc($course['name']) ?></h3>
                <div class="text-end">
                    <a href="<?= base_url('admin/manage_course') ?>" class="btn btn-primary">Back to Courses</a>
                    <a href="<?= base_url('admin/manage_module') ?>" class="btn btn-secondary">View Modules</a>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($modules)): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SI NO</th>
                                <th>Module Name</th>
                                <th>Description</th>
                                <th>Duration (weeks)</th>
                                <th>Videos</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($modules as $index => $module): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= esc($module['module_name']) ?></td>
                                    <td>
                                        <a href="javascript:void(0);" class="read-desc"
                                            data-description="<?= esc($module['description'], 'attr') ?>"
                                            data-name="<?= esc($module['module_name'], 'attr') ?>">
                                            Read Description
                                        </a>
                                    </td>
                                    <td><?= esc($module['duration_weeks']) ?></td>
                                    <td>
                                        <?php
                                        $videos = $videoModel->where('module_id', $module['module_id'])->findAll();
                                        if ($videos):
                                            foreach ($videos as $v): ?>
                                                <a href="javascript:void(0);" class="play-video"
                                                    data-video="<?= base_url('public/uploads/videos/' . $v['video_file']) ?>"
                                                    data-name="<?= esc($v['video_file'], 'attr') ?>">
                                                    Play Video <i class="bi bi-play-circle"></i>
                                                </a><br>
                                            <?php endforeach;
                                        else: ?>
                                            No videos
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <a href="<?= base_url('admin/add_module/edit/' . $module['module_id']) ?>"
                                               title="Edit" style="color:rgb(13, 162, 199); margin-right: 10px;">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <a href="javascript:void(0);" 
                                               class="delete-module" 
                                               data-id="<?= $module['module_id'] ?>" 
                                               title="Delete" style="color:#dc3545;">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No modules found for this course.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionModalLabel">Course Description</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalDescription"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">Video Player</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <video id="popupVideo" width="100%" controls>
                    <source src="" type="video/mp4">
                    Your browser does not support HTML5 video.
                </video>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.read-desc', function () {
        var fullDescription = $(this).data('description');
        var courseName = $(this).data('name');

        $('#descriptionModalLabel').text("Course Description: " + courseName);

        $('#modalDescription').html(fullDescription);

        var myModal = new bootstrap.Modal(document.getElementById('descriptionModal'));
        myModal.show();
    });


    $(document).on('click', '.play-video', function () {
        var videoSrc = $(this).data('video');
        var videoName = $(this).data('name');

        $('#popupVideo source').attr('src', videoSrc);
        $('#popupVideo')[0].load();

        $('#videoModalLabel').text(videoName);

        var myModal = new bootstrap.Modal(document.getElementById('videoModal'));
        myModal.show();
    });



</script>