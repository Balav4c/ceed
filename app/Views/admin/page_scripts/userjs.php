<script>
$(document).ready(function () {
    var baseUrl = "<?= base_url() ?>";
    if ($('#userForm').data('edit') === true) {  
        $('#saveUserBtn').prop('disabled', true); 
    }
    $('#userForm input, #userForm select, #userForm textarea').on('input change', function () {
        $('#saveUserBtn').prop('disabled', false);
    });
    // ---  Manage admin user Save Button Click ---
   $(document).ready(function() {
    var $form = $('#userForm');
    var $btn = $('#saveUserBtn');
    var isEdit = $("input[name='user_id']").length > 0;

    if (isEdit) $btn.prop('disabled', true);

    function showMessage(message, type = 'danger') {
        var messageBox = $('#messageBox');
        messageBox.removeClass('d-none alert-success alert-danger')
                  .addClass(type === 'success' ? 'alert-success' : 'alert-danger')
                  .text(message)
                  .show();
        setTimeout(function() {
            messageBox.fadeOut();
        }, 2000);
    }
    var originalValues = {};
    $form.find("input, select, textarea").each(function() {
        if ($(this).is(':checkbox') || $(this).is(':radio')) {
            originalValues[this.name] = $(this).prop('checked');
        } else {
            originalValues[this.name] = $(this).val();
        }
    });
    function toggleSaveButton() {
        var changed = false;
        $form.find("input, select, textarea").each(function() {
            var name = this.name;
            var currentValue;

            if ($(this).is(':checkbox') || $(this).is(':radio')) {
                currentValue = $(this).prop('checked');
            } else {
                currentValue = $(this).val();
            }

            if (currentValue != originalValues[name]) {
                changed = true;
                return false; 
            }
        });
        $btn.prop('disabled', !changed);
    }
    $form.on('input change click', "input, select, textarea", toggleSaveButton);
    $btn.click(function(e) {
        e.preventDefault();
        var name = $("input[name='name']").val()?.trim();
        var email = $("input[name='email']").val()?.trim();
        var role = $("select[name='role_id']").val();
        var password = $("input[name='password']").val();
        var newPassword = $("input[name='new_password']").val();
        var confirmPassword = $("input[name='confirm_password']").val();
        if (!name || !email || !role) {
            showMessage('All Fields Are Required');
            return;
        }
        if (!isEdit) {
            if (!password || !confirmPassword) {
                showMessage('Password And Confirm Password Are Required');
                return;
            }
            if (password !== confirmPassword) {
                showMessage('Password And Confirm Password Do Not Match');
                return;
            }
            } else {
                if ((newPassword || confirmPassword) && newPassword !== confirmPassword) {
                    showMessage('New Password And Confirm Password Do Not Match');
                    return;
                }
            }
            $btn.prop('disabled', true).text('Saving...');
            $.post(baseUrl + "admin/save/user", $form.serialize(), function(response) {
                if (response.success) {
                    showMessage(response.message, 'success');
                    $form.find("input, select, textarea").each(function() {
                        if ($(this).is(':checkbox') || $(this).is(':radio')) {
                            originalValues[this.name] = $(this).prop('checked');
                        } else {
                            originalValues[this.name] = $(this).val();
                        }
                    });
                    toggleSaveButton(); 
                    if (response.redirect) {
                        setTimeout(() => window.location.href = response.redirect, 1500);
                    }
                } else {
                    showMessage(response.message);
                    $btn.prop('disabled', false).text('Save User');
                }
            }, 'json').fail(function() {
                showMessage('Server Error. Please try again.');
                $btn.prop('disabled', false).text('Save User');
            });
        });
    });
// Manage admin user table
     var table = $('#userTable').DataTable({
        ajax: {
            url: "<?= base_url('admin/manage_user/userlistajax') ?>",
            type: "POST",
            dataSrc: "data"
        },
        serverSide: true,
        processing: true,
        ordering: true,
        searching: true,
        paging: true,
        dom: "<'row mb-3'<'col-sm-6'l><'col-sm-6 text-end'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-3 d-flex align-items-center'<'col-sm-5'i><'col-sm-7 text-end'p>>",
        drawCallback: function () {
            $('.dataTables_info').text(function(_, txt) {
                return txt.replace(/\(filtered.*\)/, '').trim();
            });
        },
        columns: [
             { data: "slno", className: "text-start" }, 
            {
                data: "name",
                render: function (data) {
                    return data ? data.replace(/\b\w/g, c => c.toUpperCase()) : '';
                }
            },
            { data: "email" },
            {
                data: "role_name",
                render: function (data) {
                   return data ? data.replace(/\b\w/g, c => c.toUpperCase()) : 'N/A';
                }
            },
             {
                data: "status",
                render: function (data, type, row) {
                    let checked = data == 1 ? 'checked' : '';
                    return `
                        <div class="form-check form-switch">
                            <input class="form-check-input toggle-status" type="checkbox"
                            data-id="${row.user_id}" ${checked}>
                               
                        </div>
                    `;
                }
            },
            {
                data: "user_id",
                render: function (id) {
                    return `
                        <div class="d-flex align-items-center gap-3">
                            <a href="<?= base_url('admin/adduser/edit/') ?>${id}"
                               title="Edit" style="color:rgb(13, 162, 199); margin-right: 10px;">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <a href="javascript:void(0);"
                               class="delete-all" data-id="${id}"
                               title="Delete" style="color: #dc3545;">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </div>
                    `;
                }
            },
            { data: "user_id", visible: false }
        ],
        order: [[6, 'desc']],
        columnDefs: [
            { searchable: false, orderable: false, targets: [0, 5] }
        ],
        language: { infoFiltered: "" }
    });
 
// Manage admin user password toggle

    $(document).on("click", ".toggle-password", function () {
        let input = $($(this).data("target"));
        if (input.attr("type") === "password") {
            input.attr("type", "text"); 
            $(this).removeClass("bi-eye-slash").addClass("bi-eye");
        } else {
            input.attr("type", "password"); 
            $(this).removeClass("bi-eye").addClass("bi-eye-slash");
        }
    });

 });
 // Manage admin user toggle switch

    $('#userTable').on('change', '.toggle-status', function () {
        let userId = $(this).data('id');
        let newStatus = $(this).is(':checked') ? 1 : 2;
    
        $.ajax({
            url: "<?= base_url('admin/manage_user/toggleStatus') ?>",
            type: "POST",
            data: {
                user_id: userId,
                status: newStatus,
                "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
            },
            dataType: "json",
                success: function(response) {
                    let $msg = $('#messageBox');
                    $msg.removeClass('d-none alert-success alert-danger');
    
                    if (response.status === 'success') {
                        $msg.addClass('alert-success').text(response.message).show();
                        setTimeout(function() {
                            $msg.fadeOut();
                            table.ajax.reload(null, false);
                        }, 1500);
                    } else {
                        $msg.addClass('alert-danger').text(response.message || 'Failed To Update Status').show();
                        setTimeout(function() {
                            $msg.fadeOut();
                        }, 2000);
                    }
                },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                let $msg = $('#messageBox');
                $msg.removeClass('d-none alert-success').addClass('alert-danger')
                    .text('Error Updating Status').show();
                setTimeout(function() { $msg.fadeOut(); }, 2000);
            }
        });
    });
 
// Manage admin user delete

    $(document).on("click", ".delete-all", function (e) {
        e.preventDefault();
        let userId = $(this).data("id");
 
        swal({
            title: "Are You Sure?",
            text: "You Want To Delete This User!",
            icon: "warning",
            buttons: {
                cancel: {
                    visible: true,
                    text: "Cancel",
                    className: "btn btn-danger",
                },
                confirm: {
                    text: "Delete",
                    className: "btn btn-success",
                },
            },
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: "<?= base_url('admin/manage_user/delete'); ?>",
                    type: "POST",
                    data: { user_id: userId },
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            swal("Deleted!", response.message, {
                                icon: "success",
                                buttons: {
                                    confirm: {
                                        className: "btn btn-success",
                                    },
                                },
                            });
                            $('#userTable').DataTable().ajax.reload();
                        } else {
                            swal("Error!", response.message, "error");
                        }
                    },
                    error: function () {
                        swal("Error!", "Something Went Wrong. Try Again.", "Error");
                    },
                });
            } else {
                swal("Your Data Is Safe!", {
                    buttons: {
                        confirm: {
                            className: "btn btn-success",
                        },
                    },
                });
            }
        });
    });

    // leaderboard table js

    var table = $('#leaderTable').DataTable({
    ajax: {
        url: "<?= base_url('admin/leader_board/leaderboardListAjax') ?>",
        type: "POST",
        data: function(d) {
            d.selected_date = $('#filterDate').val(); 
        },
        dataSrc: "data"
    },
    serverSide: true,
    processing: true,
    ordering: true,
    searching: true,
    paging: true,
    columns: [
        { data: "slno" },
        { data: "name", render: d => d ? d.replace(/\b\w/g,c=>c.toUpperCase()) : '' },
        { data: "course_name", render: d => d ? d.replace(/\b\w/g,c=>c.toUpperCase()) : '' },
        { data: "module_name", render: d => d ? d.replace(/\b\w/g,c=>c.toUpperCase()) : 'N/A' },
        { data: "score" }, 
        { data: "rank" },  
        {
            data: "status",
            render: function(data, type, row){
                let checked = data == 1 ? 'checked' : '';
                return `<div class="form-check form-switch">
                            <input class="form-check-input toggle-status"
                                   type="checkbox"
                                   data-id="${row.leaderboard_id}" ${checked}>
                        </div>`;
            }
        },
        
        {
            data: "leaderboard_id",
            render: function (id) {
                return `<div class="d-flex align-items-center gap-3">
                            <a href="javascript:void(0);" class="delete-all"
                               data-id="${id}" title="Delete" style="color:#dc3545;">
                               <i class="bi bi-trash-fill"></i>
                            </a>
                        </div>`;
            }
        }
    ],
    order: [[5, 'asc']], 
    columnDefs: [
        { orderable: false, searchable: false, targets: [0,6,7] }
    ]
});
$('#filterDate').on('change', function() {
    table.ajax.reload();
});

// leaderboard delete

    $(document).on("click", ".delete-all", function (e) {
    e.preventDefault();
    let leaderboardId = $(this).data("id");

    swal({
        title: "Are You Sure?",
        text: "You Want To Delete This Entry!",
        icon: "warning",
        buttons: {
            cancel: {
                visible: true,
                text: "Cancel",
                className: "btn btn-danger",
            },
            confirm: {
                text: "Delete",
                className: "btn btn-success",
            },
        },
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: "<?= base_url('admin/leader_board/delete') ?>",
                type: "POST",
                data: {
                    leaderboard_id: leaderboardId,
                    "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        swal("Deleted!", response.message, {
                            icon: "success",
                            buttons: {
                                confirm: { className: "btn btn-success" },
                            },
                        });
                        $('#leaderTable').DataTable().ajax.reload(); 
                    } else {
                        swal("Error!", response.message, "error");
                    }
                },
                error: function () {
                    swal("Error!", "Something Went Wrong. Try Again.", "error");
                },
            });
        } else {
            swal("Your Data Is Safe!", {
                buttons: {
                    confirm: { className: "btn btn-success" },
                },
            });
        }
    });
});

// leaderboard toggle status

$('#leaderTable').on('change', '.toggle-status', function () {
    let $checkbox = $(this);
    let leaderboardId = $checkbox.data('id'); 
    let newStatus = $checkbox.is(':checked') ? 1 : 2;

    $.ajax({
        url: "<?= base_url('admin/leader_board/toggleStatus') ?>",
        type: "POST",
        data: {
            leaderboard_id: leaderboardId,
            status: newStatus,
            "<?= csrf_token() ?>": "<?= csrf_hash() ?>" 
        },
        dataType: "json",
        success: function(response) {
            let $msg = $('#messageBox');
            $msg.removeClass('d-none alert-success alert-danger');

            if (response.success) {
                $msg.addClass('alert-success').text(response.message).show();
                setTimeout(() => $msg.fadeOut(), 1500);
            } else {
                $msg.addClass('alert-danger').text(response.message || 'Failed To Update Status').show();
                $checkbox.prop('checked', !$checkbox.is(':checked')); 
                setTimeout(() => $msg.fadeOut(), 2000);
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            let $msg = $('#messageBox');
            $msg.removeClass('d-none alert-success')
                .addClass('alert-danger')
                .text('Error Updating Status')
                .show();
            $checkbox.prop('checked', !$checkbox.is(':checked')); 
            setTimeout(() => $msg.fadeOut(), 2000);
        }
    });
});
</script>
