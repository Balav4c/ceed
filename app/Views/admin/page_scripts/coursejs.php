<script>
  $(document).ready(function() {
    const $saveBtn = $('#saveBtn');
    const $courseForm = $('#courseForm');

    $saveBtn.prop('disabled', true).css({ opacity: 0.6, pointerEvents: 'none' });
    $courseForm.on('input change', 'input, textarea', function() {
        $saveBtn.prop('disabled', false).css({ opacity: 1, pointerEvents: 'auto' });
    });

    $('#addModule').click(function() {
        $('#moduleTable tbody').append(`
            <tr class="module-row">
                <td><input type="text" name="module_name[]" class="form-control" required></td>
                <td><input type="text" name="module_duration[]" class="form-control"></td>
                <td class="text-center">
                    <span class="remove-module" title="Remove">
                        <i class="fas fa-trash text-danger"></i>
                    </span>
                </td>
            </tr>
        `);
    });

    $(document).on('click', '.remove-module', function() {
        $(this).closest('tr').remove();
    });

    $('#courseForm').on('submit', function (e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        $saveBtn.prop('disabled', true).css({ opacity: 0.6, pointerEvents: 'none' });
      
        $.post(url, $('#courseForm').serialize(), function(response) {
            $('#messageBox').removeClass('d-none alert-success alert-danger'); 

             if (response.status === 'success' || response.status == 1) {
                    $('#messageBox')
                        .addClass('alert-success')
                        .text(response.msg || response.message)
                        .show();

                        setTimeout(function () {
                            window.location.href = "<?php echo base_url('admin/manage_course'); ?>";
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
    let table = "";
    const alertBox = $('.alert');
    table = $('#courseTable').DataTable({
        ajax: { 
            url: "<?= base_url('admin/manage_course/courselistajax') ?>",
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
             {
                data: "slno", className: "text-start" },
            { data: "name",
                render: function (data, type, row) {
                        if (!data || typeof data !== 'string') return '';
                        return data.replace(/\b\w/g, c => c.toUpperCase());
                    }
            },
            { data: "description" },
            { data: "duration_weeks" },
            {
                data: "status",
                render: function (data, type, row) {
                    let checked = data == 1 ? 'checked' : '';
                    return `
                        <div class="form-check form-switch">
                            <input class="form-check-input toggle-status" type="checkbox" 
                                data-id="${row.course_id}" ${checked}>
                            
                        </div>
                    `;
                }
            },
              {
                    data: "course_id",
                    render: function (id) {
                        return `
                        <div class="d-flex align-items-center gap-3">
                            <a href="<?= base_url('admin/manage_course/edit/') ?>${id}" title="Edit" style="color:rgb(13, 162, 199); margin-right: 10px;">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <a href="javascript:void(0);" class="delete-all"  data-id="${id}" title="Delete" style="color: #dc3545;" >
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </div>
                    `;
                    }
                },
        ],
       order: [[6, 'desc']],
            columnDefs: [
                { searchable: false, orderable: false, targets: [0,3, 5] }
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
});

    // toggle status 
  $('#courseTable').on('change', '.toggle-status', function () {
    let courseId = $(this).data('id');  
    let newStatus = $(this).is(':checked') ? 1 : 2;

    $.ajax({
        url: "<?= base_url('admin/manage_course/toggleStatus') ?>",
        type: "POST",
        data: {
            course_id: courseId,     
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
            text: "You Want To Delete This Course!",
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
                    url: "<?= base_url('admin/manage_course/delete'); ?>",
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
                swal("Your Course Is Safe!", {
                    buttons: {
                        confirm: {
                            className: "btn btn-success",
                        },
                    },
                });
            }
        });
    });
 $(window).on('keydown', function (e) {
            if (e.ctrlKey && e.key === 'Enter') {
                e.preventDefault();
                $('#saveBtn').trigger('click');
            }

            if (e.ctrlKey && e.key.toLowerCase() === 'f') {
                e.preventDefault();
                $('#moduleTable').trigger('click');
            }
        });
</script>