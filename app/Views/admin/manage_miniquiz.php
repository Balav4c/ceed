<div class="container">
  <div class="page-inner">
    <div class="card">
      <div class="card-header">
        <div class="card-header d-flex justify-content-between">
          <h3>Manage Mini Quizzes</h3>
          <a href="<?= base_url('admin/mini_quiz/add') ?>" class="btn btn-primary">Add New Quiz</a>
        </div>
      </div>
      <div class="card-body">
        <table id="quizTable" class="table table-bordered">
          <thead>
            <tr>
              <th style="width: 11%;">SI NO</th>
              <th>Course Name</th>
              <th>Module Name</th>
              <th>Question</th>
              <th>Correct Answer</th>
              <th>Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>