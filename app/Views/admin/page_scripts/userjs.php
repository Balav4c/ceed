<script>
$(document).ready(function () {
    var baseUrl = "<?= base_url() ?>";
    if ($('#userForm').data('edit') === true) {  
        $('#saveUserBtn').prop('disabled', true); 
    }
    $('#userForm input, #userForm select, #userForm textarea').on('input change', function () {
        $('#saveUserBtn').prop('disabled', false);
    });
    // --- Save Button Click ---
    $(document).ready(function() {
    var $form = $('#userForm');
    var $btn = $('#saveUserBtn');
    var originalData = $form.serialize(); 
    var isEdit = $("input[name='user_id']").length > 0;
    if (isEdit) {
        $btn.prop('disabled', true);
    }
    $form.on('input change', function() {
        var currentData = $form.serialize();

        if (currentData !== originalData) {
            $btn.prop('disabled', false); 
        } else {
            $btn.prop('disabled', true); 
        }
    });
    $('#saveUserBtn').click(function(e) {
        e.preventDefault();

        $btn.prop('disabled', true).text('Saving...'); 

        var url = baseUrl + "admin/save/user";
        var messageBox = $('#messageBox');
        messageBox.removeClass('alert-success alert-danger').addClass('d-none').text('');

        if (!isEdit) {
            var password = $('#password').val();
            var confirmPassword = $('#confirm_password').val();
            if (!password || !confirmPassword) {
                messageBox.removeClass('d-none alert-success')
                    .addClass('alert-danger')
                    .text('Password And Confirm Password Are Required')
                    .show();
                $btn.prop('disabled', false).text('Save User');
                return;
            }
            if (password !== confirmPassword) {
                messageBox.removeClass('d-none alert-success')
                    .addClass('alert-danger')
                    .text('Password And Confirm Password Do Not Match')
                    .show();
                $btn.prop('disabled', false).text('Save User');
                return;
            }

            } else {
                var newPassword = $('#new_password').val();
                var confirmPassword = $('#confirm_password').val();

                if (newPassword || confirmPassword) {
                    if (newPassword !== confirmPassword) {
                        messageBox.removeClass('d-none alert-success')
                            .addClass('alert-danger')
                            .text('New Password And Confirm Password Do Not Match')
                            .show();
                        $btn.prop('disabled', false).text('Save User');
                        return;
                    }
                }
            }
            $.post(url, $form.serialize(), function(response) {
                messageBox.removeClass('d-none');

                if (response.success) { 
                    messageBox.removeClass('alert-danger')
                        .addClass('alert-success')
                        .text(response.message)
                        .show();

                    if (response.redirect) {
                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 1500);
                    }
                    originalData = $form.serialize();
                    $btn.prop('disabled', true).text('Save User');

                } else {
                    messageBox.removeClass('alert-success')
                        .addClass('alert-danger')
                        .text(response.message)
                        .show();
                    $btn.prop('disabled', false).text('Save User');
                }

                setTimeout(function() {
                    messageBox.fadeOut();
                }, 2000);
            }, 'json');
        });
    });
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
                    return data ? data : "No Role";
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
        order: [[5, 'desc']],
        columnDefs: [
            { searchable: false, orderable: false, targets: [0, 5] }
        ],
        language: { infoFiltered: "" }
    });
 

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
 // toggle switch
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
                    $msg.addClass('alert-danger').text(response.message || 'Failed to update status').show();
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
                        swal("Error!", "Something went wrong. Try again.", "Error");
                    },
                });
            } else {
                swal("Your data is safe!", {
                    buttons: {
                        confirm: {
                            className: "btn btn-success",
                        },
                    },
                });
            }
        });
    });
</script>
