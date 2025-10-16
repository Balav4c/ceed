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
                        let editUrl = "<?= base_url('admin/mini_quiz/edit/') ?>" + id;
                        return `<a href="${editUrl}" class="btn btn-sm btn-info me-1">Edit</a>
                        <button class="btn btn-sm btn-danger deleteQuiz" data-id="${id}">Delete</button>`;
                    }
                }
            ],
            order: [[1, 'desc']],
            columnDefs: [
                { searchable: false, orderable: false, targets: [0, 5] }
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
        $(document).on('click', '.deleteQuiz', function () {
            let id = $(this).data('id');
            if (confirm('Are you sure you want to delete this quiz?')) {
                $.post("<?= base_url('admin/mini_quiz/delete') ?>", { id: id }, function (res) {
                    if (res.status === 'success') table.ajax.reload();
                });
            }
        });
    });
</script>