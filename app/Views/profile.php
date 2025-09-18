<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <!--Personal Information Card-->
            <div class="card card-pad">
                <h5 class="card-head mb-4">Personal Information</h5>
                <form>
                    <div class="mb-3">
                        <label for="fullName" class="form-label fs-14">Full Name</label>
                        <input type="text" id="fullName" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="grade" class="form-label fs-14">Grade</label>
                        <input type="text" id="grade" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="school" class="form-label fs-14">School</label>
                        <input type="text" id="school" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="bio" class="form-label fs-14">Bio</label>
                        <textarea id="bio" class="form-control" rows="3"></textarea>
                    </div>

                </form>
            </div>
            <!--contact information card-->
            <div class="card card-pad mt-4">
                <h5 class="card-head mb-4">Contact Information</h5>
                <form>
                    <div class="mb-3">
                        <label for="email" class="form-label fs-14">Email</label>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-envelope fs-5 me-2"></i>
                            <input type="email" id="email" class="form-control">
                        </div>
                    </div>


                    <div class="mb-3">
                        <label for="email" class="form-label fs-14">Phone</label>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-telephone fs-5 me-2"></i>
                            <input type="email" id="email" class="form-control">
                        </div>
                    </div>


                </form>
            </div>
            <!--Notifications--->
            <div class="card card-pad mt-4">
                <h5 class="card-head mb-4">Notifications</h5>
                <form>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-bell-fill me-2"></i>
                            <span>Push</span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="pushNotification">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-bell-fill me-2"></i>
                            <span>Assessment</span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="assessmentNotification">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-bell-fill me-2"></i>
                            <span>Email</span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="emailNotification">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-bell-fill me-2"></i>
                            <span>Achievements</span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="achievementNotification">
                        </div>
                    </div>
                </form>
            </div>

            <!--Security--->
            <div class="card card-pad mt-4">
                <h5 class="card-head mb-4">Security</h5>
                <form>
                    <div class="mb-3">
                        <label for="fullName" class="form-label fs-14">Current Password</label>
                        <input type="password" id="currentpassword" class="form-control" value="">
                    </div>
                    <div class="mb-3">
                        <label for="grade" class="form-label fs-14">New Password</label>
                        <input type="password" id="newpassword" class="form-control" value="">
                    </div>
                    <div class="mb-3">
                        <label for="grade" class="form-label fs-14">Confirm Password</label>
                        <input type="password" id="confirmpassword" class="form-control" value="">
                    </div>


                </form>
                <button type="submit" class="btn btn-primary">Change Password</button>
            </div>
            <div class="row mt-4">
                <div class="col text-end">
                    <button type="submit" class="btn btn-primary px-5">Save Changes</button>
                </div>
            </div>

        </div>

    </div>
</div>