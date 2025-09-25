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
                    <input type="date" id="filterDate" class="form-control d-inline-block" style="width:auto;">
                </div>
            </div>

            <hr>

            <table class="table table-hover" id="leaderTable">
                <thead class="table-light">
                    <tr>
                        <th>Sl No</th>
                        <th>User Name</th>
                        <th>Course Name</th>
                        <th>Module Name</th>
                        <th>Score</th>
                        <th>Rank</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div> 
    </div> 
  </div> 
</div>
