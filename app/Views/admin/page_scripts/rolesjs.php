<script>
    $(document).ready(function () {
    const $saveBtn = $('#saveBtn');
    const $roleForm = $('#roleForm');
    const $roleName = $('#role_name');
    const $permissions = $('.permission-checkbox, #select_all_permissions');

    let originalData = $roleForm.serialize();

    $saveBtn.prop('disabled', true).css({ opacity: 0.6, pointerEvents: 'none' });
    function checkFormChanges() {
        let currentData = $roleForm.serialize();
        if (currentData !== originalData) {
            $saveBtn.prop('disabled', false).css({ opacity: 1, pointerEvents: 'auto' });
        } else {
            $saveBtn.prop('disabled', true).css({ opacity: 0.6, pointerEvents: 'none' });
        }
    }

    $roleForm.on('input change', 'input, select, textarea', checkFormChanges);

    function resetFormState() {
        originalData = $roleForm.serialize();
        $saveBtn.prop('disabled', true).css({ opacity: 0.6, pointerEvents: 'none' });
    }
        $('#select_all_permissions').on('change', function () {
            $('.permission-checkbox').prop('checked', $(this).prop('checked'));
        });

        $('.permission-checkbox').on('change', function () {
            if ($('.permission-checkbox:checked').length === $('.permission-checkbox').length) {
                $('#select_all_permissions').prop('checked', true);
            } else {
                $('#select_all_permissions').prop('checked', false);
            }
        });

        let table = "";
        const alertBox = $('.alert');
        table = $('#roleTable').DataTable({
            ajax: {
                url: "<?= base_url('admin/manage_role/rolelistajax') ?>",
                type: "POST",
                dataSrc: "data"
            },
            sort: true,
            searching: true,
            paging: true,
            processing: true,
            serverSide: true,
            dom: "<'row  d-flex align-items-center '<'col-sm-6'l><'col-sm-6 text-end'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row  d-flex align-items-center'<'col-sm-5'i><'col-sm-7 text-end'p>>",

            drawCallback: function () {
                let info = $('.dataTables_info');
                info.text(info.text().replace(/\(filtered.*\)/, '').trim());
            },
            columns: [
                {
                    data: "slno",
                    render: function (data) {
                        return data;
                    }
                },
                {
                    data: "role_name",
                    render: function (data, type, row) {
                        if (!data || typeof data !== 'string') return '';
                        return data.replace(/\b\w/g, c => c.toUpperCase());
                    }
                },

                {
                    data: "permissions",
                    render: function (id, type, row, meta) {
                        const permissions = row.permissions || [];
                        if (permissions.length > 0) {
                            return '<ul class="mb-0">' + permissions.map(p => `<li>${p}</li>`).join('') + '</ul>';
                        }
                        return '<em>No Permissions Assigned</em>';
                    }
                },
                {
                    data: "status",
                    render: function (data, type, row) {
                        let checked = data == 1 ? 'checked' : '';
                        return `
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-status" type="checkbox" 
                                    data-id="${row.role_id}" ${checked}>
                                
                            </div>
                        `;
                    }
                },

                {
                    data: "role_id",
                    render: function (id) {
                        return `
                        <div class="d-flex align-items-center gap-3">
                            <a href="<?= base_url('admin/add_role/edit/') ?>${id}" title="Edit" style="color:rgb(13, 162, 199); margin-right: 10px;">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <a href="javascript:void(0);" class="delete-all"  data-id="${id}" title="Delete" style="color: #dc3545;" >
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </div>
                    `;
                    }
                },
                { data: "role_id", visible: false }
            ],

            order: [[5, 'desc']],
            columnDefs: [
                { searchable: false, orderable: false, targets: [0, 2, 4] }
            ],
            language: {
                infoFiltered: "",
            }, scrollX: false,
            autoWidth: false
        });
        table.on('order.dt search.dt draw.dt', function () {
            table.column(0, { search: 'applied', order: 'applied' })
                .nodes()
                .each(function (cell, i) {
                    var pageInfo = table.page.info();
                    cell.innerHTML = pageInfo.start + i + 1;
                });
        });

        $saveBtn.prop('disabled', true).css({ opacity: 0.6, pointerEvents: 'none' });
        function enableSaveButton() {
            $saveBtn.prop('disabled', false).css({ opacity: 1, pointerEvents: 'auto' });
        } 
        $roleName.on('input', enableSaveButton);
        $permissions.on('change', enableSaveButton);
        $('#roleForm').on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
             $saveBtn.prop('disabled', true).css({ opacity: 0.6, pointerEvents: 'none' });
           $.post(url, $('#roleForm').serialize(), function(response) {
                $('#messageBox').removeClass('d-none alert-success alert-danger'); 

                if (response.status === 'success' || response.status == 1) {
                    $('#messageBox')
                        .addClass('alert-success')
                        .text(response.msg || response.message)
                        .show();

                        setTimeout(function () {
                            window.location.href = "<?php echo base_url('admin/manage_role'); ?>";
                        }, 1500);
                         resetFormState();
                } else {
                    $('#messageBox')
                        .addClass('alert-danger')
                        .text(response.message || 'Something went wrong')
                        .show();
                        checkFormChanges();
                }

                setTimeout(function() {
                    $('#messageBox').fadeOut();
                }, 2000);
            }, 'json');
            

        });

    });
    // toggle switch
    $('#roleTable').on('change', '.toggle-status', function () {
        let roleId = $(this).data('id');
        let newStatus = $(this).is(':checked') ? 1 : 2;

        $.ajax({
            url: "<?= base_url('admin/manage_role/toggleStatus') ?>",
            type: "POST",
            data: {
                role_id: roleId,
                status: newStatus,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
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
                let $msg = $('#messageBox');
                $msg.removeClass('d-none alert-success').addClass('alert-danger')
                    .text('Error updating status').show();
                setTimeout(function() { $msg.fadeOut(); }, 2000);
            }
        });
    });

    // delete pop up 

    $(document).on("click", ".delete-all", function (e) {
        e.preventDefault();
        let roleId = $(this).data("id"); 

        swal({
            title: "Are You Sure?",
            text: "You Want To Delete This Role!",
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
                    url: "<?= base_url('admin/manage_role/delete'); ?>",
                    type: "POST",
                    data: { id: roleId },
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            swal("Deleted!", response.message, {
                                icon: "success",
                                buttons: {
                                    confirm: {
                                        className: "btn btn-success",
                                    },
                                },
                            });
                            $('#roleTable').DataTable().ajax.reload();
                        } else {
                            swal("Error!", response.message, "error");
                        }
                    },
                    error: function () {
                        swal("Error!", "Something went wrong. Try again.", "error");
                    },
                });
            } else {
                swal("Your role is safe!", {
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