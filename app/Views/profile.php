<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div id="profileResponse" class="mt-2"></div>
            <form id="profileForm">
                <!--Personal Information Card-->
                <div class="card card-pad">
                    <h5 class="card-head mb-4">Personal Information</h5>

                    <div class="mb-3">
                        <label for="fullName" class="form-label fs-14">Full Name</label>
                        <input type="text" id="name" name="name"  value="<?= esc($user['name'] ?? '') ?>"
                            class="form-control fs-13">
                    </div>
                    <div class="mb-3">
                        <label for="grade" class="form-label fs-14">Grade</label>
                        <input type="text" id="grade" name="grade"  value="<?= esc($user['grade'] ?? '') ?>"
                            class="form-control fs-13">
                    </div>
                    <div class="mb-3">
                        <label for="school" class="form-label fs-14">School</label>
                        <input type="text" id="school" name="school"  value="<?= esc($user['school'] ?? '') ?>"
                            class="form-control fs-13">
                    </div>
                    <div class="mb-3">
                        <label for="bio" class="form-label fs-14">Bio</label>
                        <textarea id="bio" class="form-control fs-13"  name="bio"
                            rows="3"><?= esc($user['bio'] ?? '') ?></textarea>
                    </div>


                </div>
                <!--contact information card-->
                <div class="card card-pad mt-4">
                    <h5 class="card-head mb-4">Contact Information</h5>

                    <div class="mb-3">
                        <label for="email" class="form-label fs-14">Email</label>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-envelope fs-5 me-2"></i>
                            <input type="email" id="email" name="email" value="<?= esc($user['email'] ?? '') ?>"
                                class="form-control fs-13" readonly>
                        </div>
                    </div>


                    <div class="mb-3">
                        <label for="phone" class="form-label fs-14">Phone</label>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-telephone fs-5 me-2"></i>
                            <input type="text" id="phone" name="phone" value="<?= esc($user['phone'] ?? '') ?>"
                                class="form-control fs-13">
                        </div>
                    </div>


                    <input type="hidden" name="profile_percentage"
                        value="">
                </div>
                <!--Notifications--->
                <div class="card card-pad mt-4">
                    <h5 class="card-head mb-4">Notifications</h5>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-bell-fill me-2"></i>
                            <span>Push</span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="notification[]" value="push" type="checkbox"
                                id="pushNotification"
                                <?= (isset($user['notification']) && in_array('push', $user['notification'])) ? 'checked' : '' ?>>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-bell-fill me-2"></i>
                            <span>Assessment</span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="notification[]" value="assessment" type="checkbox"
                                id="assessmentNotification"
                                <?= (isset($user['notification']) && in_array('assessment', $user['notification'])) ? 'checked' : '' ?>>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-bell-fill me-2"></i>
                            <span>Email</span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="notification[]" value="email" type="checkbox"
                                id="emailNotification"
                                <?= (isset($user['notification']) && in_array('email', $user['notification'])) ? 'checked' : '' ?>>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-bell-fill me-2"></i>
                            <span>Achievements</span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="notification[]" value="achievement" type="checkbox"
                                id="achievementNotification"
                                <?= (isset($user['notification']) && in_array('achievement', $user['notification'])) ? 'checked' : '' ?>>
                        </div>
                    </div>

                </div>

                <!--Security--->
                <div class="card card-pad mt-4">
                    <h5 class="card-head mb-4">Security</h5>
                    <div id="passwordResponse" class="mt-2"></div>
                    <!-- <div class="mb-3 position-relative">
                        <label for="password" class="form-label fs-14">Current Password</label>
                        <input type="password" id="currentpassword" class="form-control fs-13" value="">
                        <i class="bi bi-eye-slash end-0 translate-middle-y me-3 toggle-password"
                            data-target="currentpassword"></i>
                    </div> -->
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label fs-14">New Password</label>
                        <input type="password" id="newpassword" class="form-control fs-13" value="">
                        <i class="bi bi-eye-slash end-0 translate-middle-y me-3 toggle-password"
                            data-target="newpassword"></i>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label fs-14">Confirm Password</label>
                        <input type="password" id="confirmpassword" class="form-control fs-13" value="">
                        <i class="bi bi-eye-slash end-0 translate-middle-y me-3 toggle-password"
                            data-target="confirmpassword"></i>
                    </div>



                    <button type="button" class="btn btn-primary" id="changePasswordBtn">Change Password</button>
                </div>
                <div class="row mt-4">
                    <div class="col text-end">
                        <button type="submit" class="btn btn-primary px-5">Save Changes</button>
                    </div>
                </div>
            </form>

        </div>

    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    let formInitial = $("#profileForm").serialize(); // store initial form state
    let $saveBtn = $("#profileForm button[type='submit']");
    $saveBtn.prop("disabled", true);

    // Detect changes in form fields
    $("#profileForm :input").on("input change", function() {
        let formCurrent = $("#profileForm").serialize();
        $saveBtn.prop("disabled", formCurrent === formInitial ? true : false);
    });

    // Reusable showAlert function
    function showAlert(message, type = "danger", container = "#profileResponse") {
        let $alertBox = $(container);
        $alertBox.html(
            `<div class="alert alert-${type} alert-dismissible fade show">${message}
                 
            </div>`
        ).fadeIn();

        // Scroll to alert after it’s added
        $("html, body").animate({
            scrollTop: $alertBox.offset().top - 20
        }, 500);

        // Auto remove after 3 seconds
        setTimeout(() => {
            $alertBox.find(".alert").fadeOut(function() {
                $(this).remove();
            });
        }, 3000);
    }

    // Profile form submit
    $("#profileForm").submit(function(e) {
        e.preventDefault();
        let formCurrent = $(this).serialize();

        $.ajax({
            url: "<?= base_url('save/profile') ?>",
            type: "POST",
            data: formCurrent,
            dataType: "json",
            success: function(res) {
                let alertClass = res.status === "success" ? "success" : "danger";
                let message = res.status === "success" ?
                    (res.message || "Profile updated successfully!") :
                    (res.message || "Something went wrong!");

                showAlert(message, alertClass, "#profileResponse");

                if (res.status === "success") {
                    formInitial = formCurrent;
                    $saveBtn.prop("disabled", true);
                    // 🔥 Update header percentage
                    $("#profileProgressCircle")
                        .css("--percent", res.percentage + "%")
                        .text(res.percentage + "%");
                }
            },
            error: function() {
                showAlert("Error saving profile!", "danger", "#profileResponse");
            },
        });
    });

    // Change password submit
    $("#changePasswordBtn").click(function() {
        $.ajax({
            url: "<?= base_url('change/password'); ?>",
            type: "POST",
            data: {
                // currentpassword: $("#currentpassword").val(),
                newpassword: $("#newpassword").val(),
                confirmpassword: $("#confirmpassword").val(),
            },
            dataType: "json",
            success: function(response) {
                let alertClass = response.status === "success" ? "success" : "danger";
                showAlert(response.message || "Password change response", alertClass,
                    "#passwordResponse");

                // if (response.status === "success") {
                //     $("#currentpassword, #newpassword, #confirmpassword").val("");
                // }
            },
            error: function() {
                showAlert("Something went wrong. Please try again.", "danger",
                    "#passwordResponse");
            },
        });
    });
});

// Toggle password visibility
document.querySelectorAll(".toggle-password").forEach(icon => {
    icon.addEventListener("click", function() {
        const targetId = this.getAttribute("data-target");
        const input = document.getElementById(targetId);

        if (input.type === "password") {
            input.type = "text";
            this.classList.replace("bi-eye-slash", "bi-eye");
        } else {
            input.type = "password";
            this.classList.replace("bi-eye", "bi-eye-slash");
        }
    });
});
</script>