<script>
    $(function () {
        let table = "";
        const alertBox = $('.alert');
        table = $('#quizTable').DataTable({
            ajax: {
                url: "<?= base_url('admin/mini_quiz/quizListAjax') ?>",
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
                $('.dataTables_info').text(function (_, txt) {
                    return txt.replace(/\(filtered.*\)/, '').trim();
                });
            },

            columns: [
                { data: "slno" },
                { data: "course_id" },
                { data: "module_id" },
                { data: "question_text" },
                { data: "correct_answer" },
                {
                    data: "quiz_id",
                    render: function (id) {
                        return `<div class="d-flex align-items-center gap-3">
                                    <a href="<?= base_url('admin/mini_quiz/edit/') ?>${id}" title="Edit" style="color: rgb(13, 162, 199);">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="deleteQuiz" data-id="${id}" title="Delete" style="color: #dc3545;">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                </div>`;
                    }
                }

            ],
            order: [[6, 'desc']],
            columnDefs: [
                { searchable: false, orderable: false, targets: [0, 5] }
            ],
            language: { infoFiltered: "" },
            scrollX: false,
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
        const form = $("#quizForm");
        const messageBox = $("#messageBox");

        form.on("submit", function (e) {
            e.preventDefault();

            $.ajax({
                url: form.attr("action"),
                type: "POST",
                data: form.serialize(),
                dataType: "json",
                beforeSend: function () {
                    messageBox
                        .removeClass("d-none alert-success alert-danger")
                        .addClass("alert-info")
                        .text("Saving quiz...");
                },
                success: function (response) {
                    if (response.success) {
                        messageBox
                            .removeClass("alert-info alert-danger")
                            .addClass("alert-success")
                            .text(response.message);
                        form.trigger("reset");

                        // Hide success after 3 seconds
                        setTimeout(() => {
                            messageBox.fadeOut('slow', function () {
                                $(this).addClass('d-none').show();
                            });
                        }, 3000);
                    } else {
                        messageBox
                            .removeClass("alert-info alert-success")
                            .addClass("alert-danger")
                            .text(response.message || "Something went wrong!");
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    messageBox
                        .removeClass("alert-info alert-success")
                        .addClass("alert-danger")
                        .text("Server Error: Failed to save quiz.");
                }
            });
        });
        
        // delete pop up 
        $(document).on("click", ".deleteQuiz", function (e) {
            e.preventDefault();

            // correct variable name (was roleId / quizId confusion)
            const quizId = $(this).data("id");
            if (!quizId) return;

            swal({
                title: "Are You Sure?",
                text: "You want to delete this quiz!",
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
                if (!willDelete) return;
                let postData = { id: quizId };

                $.ajax({
                    url: "<?= base_url('admin/mini_quiz/delete') ?>",
                    type: "POST",
                    data: postData,
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            swal("Deleted!", response.message, {
                                icon: "success",
                                buttons: {
                                    confirm: { className: "btn btn-success" },
                                },
                            });
                            if ($.fn.DataTable.isDataTable('#quizTable')) {
                                $('#quizTable').DataTable().ajax.reload(null, false);
                            } else {
                                // fallback: reload page
                                location.reload();
                            }
                        } else {
                            swal("Error!", response.message || "Delete failed", "error");
                        }
                    },
                    error: function () {
                        swal("Error!", "Something went wrong. Try again.", "error");
                    },
                });
            });
        });
    });
</script>