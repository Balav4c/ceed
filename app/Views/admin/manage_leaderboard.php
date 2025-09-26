<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<div class="container">
  <div class="page-inner">
    <div class="row">
      <div class="form-control mb-3 right_container">
        <div id="messageBox" class="alert d-none text-center" role="alert"></div>
        <div class="row align-items-center mb-3">
          <div class="col-12 col-md-6">
            <h3 class="mb-3 mb-md-0">Manage Leader Board</h3>
          </div>
          <div class="col-12 col-md-6 text-md-end">
            <label for="filterDate" class="form-label me-2">Select Date:</label>
            <input type="text" id="filterDate" class="form-control d-inline-block" style="width:auto;"  autocomplete="off"placeholder="dd/mm/yyyy">
          </div>
        </div>
        <hr>
        <table class="table table-hover" id="leaderTable">
          <thead class="table-light">
            <tr>
              <th class="nowrap">Sl No</th>
              <th>User Name</th>
              <th>Course Name</th>
              <th>Module Name</th>
              <th>Score</th>
              <th>Rank</th>
              <th class="date-column">Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- <script src="<?= base_url('assets/js/leaderboard.js') ?>"></script> -->
<!-- 
flatpickr("#filterDate", {
    dateFormat: "d/m/Y",  
    allowInput: true,
    onChange: function(selectedDates, dateStr, instance) {
        if (dateStr) {
            const parts = dateStr.split('/');
            const formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
            $('#filterDate').data('mysql-date', formattedDate);
        } else {
            $('#filterDate').data('mysql-date', '');
        }
        table.ajax.reload();
    }
}); -->
<!-- </script> -->