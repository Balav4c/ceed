<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card leader-pad p-3">
                <div class="d-flex align-items-center gap-3">
                    <!-- Icon -->
                    <i class="bi bi-person icon-person"></i>

                    <!-- User info -->
                    <div>
                        <h6 class="mb-1 text-white"> <?= esc($profile['name'] ?? 'Guest'); ?></h6>
                        <p class="mb-0 text-white fs-14"> <?= esc($profile['grade'] ?? 'N/A'); ?> Grade.
                            <?= esc($profile['school'] ?? ''); ?></p>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <!-- Total Scores -->
                <div class="col-md-6">
                    <div class="card p-3 stat-card">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <!-- Text -->
                            <div class="para">
                                <p class="mb-1 text-muted fs-14">Total Courses</p>
                                <h5 class="mb-0">2</h5>
                            </div>

                            <!-- Icon -->
                            <div class="icon-sec">
                                <i class="bi bi-book bk-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Points -->
                <div class="col-md-6">
                    <div class="card p-3 stat-card point">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <div class="para">
                                <p class="mb-1 text-muted  fs-14">Points</p>
                                <h5 class="mb-0">210</h5>
                            </div>
                            <div class="icon-sec">
                                <i class="bi bi-trophy-fill tropy-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="row mt-3 mb-3">

                <!-- Streaks -->
                <div class="col-md-6 mt-4">
                    <div class="card p-3 stat-card">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <!-- Text -->
                            <div class="para">
                                <p class="mb-1 text-muted">Streaks</p>
                                <h5 class="mb-0">2</h5>
                            </div>

                            <!-- Icon -->
                            <div class="icon-sec">
                                <i class="bi bi-droplet drop-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Achivements -->

                <div class="col-md-6 mt-4">
                    <div class="card p-3 stat-card">
                        <div class="d-flex justify-content-between align-items-center p-3
                        ">
                            <!-- Text -->
                            <div class="para">
                                <p class="mb-1 text-muted">Achievements</p>
                                <h5 class="mb-0">2</h5>
                            </div>

                            <!-- Icon -->
                            <div class="icon-sec">
                                <i class="bi bi-award award-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>


            </div>





        </div>
        <!--Achievments-->

        <div class="col-md-8 mt-4">
            <div class="card p-3 stat-card">
                <div class="row">
                    <!-- Text -->
                    <div class="para">
                        <p class="mb-1 text-muted p-4">Achievements</p>
                    </div>

                    <div class="row mx-auto">
                        <div class="d-flex align-items-center gap-3 border-clr">
                            <!-- Icon -->
                            <i class="bi bi-star star-icon"></i>

                            <!-- User info -->
                            <div>
                                <h6 class="mb-1 text-black ach-font">First Steps</h6>
                                <p class="mb-0 text-black-50 fs-14 sub-font">Complete your first course</p>
                            </div>
                        </div>

                    </div>

                    <div class="row mx-auto mt-3">
                        <div class="d-flex align-items-center gap-3 border-clr">
                            <!-- Icon -->
                            <i class="bi bi-droplet droplet-icon"></i>

                            <!-- User info -->
                            <div>
                                <h6 class="mb-1 text-black ach-font">Consistency King</h6>
                                <p class="mb-0 text-black-50 fs-14 sub-font">Maintain a 30-day login streak</p>
                            </div>
                        </div>

                    </div>

                    <div class="row mx-auto mt-3">
                        <div class="d-flex align-items-center gap-3 border-clr">
                            <!-- Icon -->
                            <i class="bi bi-trophy-fill tropy-fill-icon"></i>

                            <!-- User info -->
                            <div>
                                <h6 class="mb-1 text-black ach-font">Game Champion</h6>
                                <p class="mb-0 text-black-50 fs-14 sub-font">Score 90+ any game 5 times</p>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>


    </div>



</div>