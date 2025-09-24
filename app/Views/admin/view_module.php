<div class="container">
    <div class="page-inner">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Modules for: <?= esc($course['name']) ?></h3>
                <a href="<?= base_url('admin/manage_course') ?>" class="btn btn-primary">Back to Courses</a>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($modules as $index => $module): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($module['module_name']) ?></td>
                                <td><?= esc($module['description']) ?></td>
                                <td><?= esc($module['duration_weeks']) ?></td>
                                <td>
                                    <?php 
                                    $videos = $videoModel->where('module_id', $module['module_id'])->findAll(); 
                                    if ($videos):
                                        foreach ($videos as $v): ?>
                                            <a href="<?= base_url('uploads/videos/' . $v['video_file']) ?>" target="_blank"><?= esc($v['video_file']) ?></a><br>
                                    <?php endforeach; else: ?>
                                        No videos
                                    <?php endif; ?>
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
