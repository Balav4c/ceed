<script>
    $(document).ready(function () {

        $('.content').richText();
        const $saveBtn = $('#saveBtn');
        const $moduleForm = $('#moduleForm');
        const $messageBox = $('#messageBox');
        const base_url = "<?= base_url() ?>";

        let initialFormData = $moduleForm.serialize();

        toggleSaveButton(false);

        function toggleSaveButton(enable) {
            $saveBtn.prop('disabled', !enable).css({
                opacity: enable ? 1 : 0.6,
                pointerEvents: enable ? 'auto' : 'none'
            });
        }

        function checkFormChanges() {
            $('.content').val($('.richText-editor').html());
            let currentFormData = $moduleForm.serialize();

            let fileChanged = false;
            $moduleForm.find('input[type="file"]').each(function () {
                if (this.files.length > 0) {
                    fileChanged = true;
                }
            });

            toggleSaveButton(currentFormData !== initialFormData || fileChanged);
        }

        $moduleForm.on('input change', 'input, textarea, select', checkFormChanges);

        $(document).on('keyup paste', '.richText-editor', function () {
            checkFormChanges();
        });

        $(document).on('change', 'input[type="file"][name="module_videos[]"]', function () {
            checkFormChanges();
        });

        // Capitalize module name
        $(document).on('input', 'input[name="module_name[]"]', function () {
            let val = $(this).val();
            val = val.replace(/\b\w/g, function (char) {
                return char.toUpperCase();
            });
            $(this).val(val);
            checkFormChanges();
        });

        // View description modal
        $('#moduleTable').on('click', '.read-desc', function () {
            var fullDescription = $(this).data('description');
            var moduleName = $(this).data('name');
            $('#descriptionModalLabel').text(moduleName);
            $('#modalDescription').html(fullDescription);
            new bootstrap.Modal(document.getElementById('descriptionModal')).show();
        });

        // Play video modal
        $(document).on("click", ".play-video-link", function () {
            let videoUrl = $(this).data("video");
            let videoTitle = $(this).data("title");

            $("#videoTitle").text(videoTitle);

            let videoPlayer = $("#videoPlayer")[0];
            $(videoPlayer).find("source").remove();

            let newSource = document.createElement("source");
            newSource.src = videoUrl;
            newSource.type = "video/mp4";
            videoPlayer.appendChild(newSource);

            videoPlayer.load();
            videoPlayer.play();

            const videoModalEl = document.getElementById('videoModal');
            const videoModal = new bootstrap.Modal(videoModalEl);
            videoModal.show();

            $(videoModalEl).on("hidden.bs.modal", function () {
                let videoPlayer = $("#videoPlayer")[0];
                $(videoPlayer).find("source").remove();
                videoPlayer.load();
            });
        });

        // Form submit
        $('#moduleForm').on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            let isValid = true;

            // Validate video files
            form.find('input[name="module_videos[]"]').each(function () {
                let files = this.files;
                if (files.length > 0) {
                    $.each(files, function (i, file) {
                        let fileType = file.type;
                        if (!['video/mp4', 'video/webm', 'video/ogg'].includes(fileType)) {
                            isValid = false;
                        }
                    });
                }
            });

            if (!isValid) {
                $messageBox
                    .removeClass('d-none alert-success')
                    .addClass('alert-danger')
                    .html("Please Upload Video Files Only.")
                    .fadeIn()
                    .delay(2000)
                    .fadeOut(500);
                return;
            }

            toggleSaveButton(false);

            // Sync editor content before submit
            $('.content').val($('.richText-editor').html());

            $.post(url, form.serialize(), function (response) {
                $messageBox.removeClass('d-none alert-success alert-danger');

                if (response.status === 'success' || response.status == 1) {
                    $messageBox
                        .addClass('alert-success')
                        .text(response.msg || response.message)
                        .show();

                    // Update initial state
                    initialFormData = $moduleForm.serialize();
                    toggleSaveButton(false);

                    setTimeout(function () {
                        window.location.href = base_url + 'admin/module/add_lesson/' + response.module_id;
                    }, 1500);

                } else {
                    $messageBox
                        .addClass('alert-danger')
                        .text(response.message || 'Something went wrong')
                        .fadeIn()
                        .delay(2000)
                        .fadeOut(500);
                    checkFormChanges();
                }
            }, 'json').fail(function () {
                $messageBox
                    .removeClass('d-none alert-success')
                    .addClass('alert-danger')
                    .text('Error submitting the form. Please try again.')
                    .show();
                checkFormChanges();
            });
        });

        let table = "";
        const alertBox = $('.alert');

        table = $('#moduleTable').DataTable({
            ajax: {
                url: "<?= base_url('admin/manage_module/modulelistajax') ?>",
                type: "POST",
                data: function (d) {
                    d.course_id = $('#course_id').val(); // send course_id
                },
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
                { data: "slno", className: "text-start" },
                {
                    data: "module_name",
                    render: function (data) {
                        return (data && typeof data === 'string')
                            ? data.replace(/\b\w/g, c => c.toUpperCase())
                            : '';
                    }
                },
                {
                    data: "description",
                    render: function (data) {
                        if (!data) return '<a href="javascript:void(0)" class="read-desc" data-description="">Read Description</a>';
                        let safeData = data.replace(/"/g, '&quot;');
                        return `<a href="javascript:void(0)" class="read-desc" data-description="${safeData}">Read Description</a>`;
                    }

                },
                {
                    data: "duration_weeks",
                    render: function (data, type, row) {
                        return data ? data + " Weeks" : "-";
                    }
                },

                {
                    data: "status",
                    render: function (data, type, row) {
                        let checked = data == 1 ? 'checked' : '';
                        return `
                        <div class="form-check form-switch">
                            <input class="form-check-input toggle-status" type="checkbox" 
                                data-id="${row.module_id}" ${checked}>
                            
                        </div>
                    `;
                    }
                },
                {
                    data: "module_id",
                    render: function (id) {
                        return `<div class="d-flex align-items-center gap-3">
                        <a href="<?= base_url('admin/add_module/edit/') ?>${id}" title="Edit" style="color:rgb(13, 162, 199); margin-right: 10px;">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        <a href="javascript:void(0);" class="delete-module" data-id="${id}" title="Delete" style="color: #dc3545;">
                            <i class="bi bi-trash-fill"></i>
                        </a>
                          <a href="javascript:void(0);" class="view-lesson" data-id="${id}" title="View Lesson" style="color:green;">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                    </div>`;
                    }
                }
            ],
            order: [[5, 'desc']],
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
       $(document).on('click', '.view-lesson', function () {
    const moduleId = $(this).data('id');
    console.log("Module ID:", moduleId);
    if (!moduleId) {
        alert("Module ID not found!");
        return;
    }
    window.location.href = "<?= base_url('admin/manage_module/lessons/') ?>" + moduleId;
});


    });
    // toggle status 
    $('#moduleTable').on('change', '.toggle-status', function () {
        let moduleId = $(this).data('id');
        let newStatus = $(this).is(':checked') ? 1 : 2;

        $.ajax({
            url: "<?= base_url('admin/manage_module/toggleStatus') ?>",
            type: "POST",
            data: {
                module_id: moduleId,
                status: newStatus,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            dataType: "json",
            success: function (response) {
                let $msg = $('#messageBox');
                $msg.removeClass('d-none alert-success alert-danger');

                if (response.status === 'success') {
                    $msg.addClass('alert-success').text(response.message).show();
                    setTimeout(function () {
                        $msg.fadeOut();
                        table.ajax.reload(null, false);
                    }, 1500);
                } else {
                    $msg.addClass('alert-danger').text(response.message || 'Failed to update status').show();
                    setTimeout(function () {
                        $msg.fadeOut();
                    }, 2000);
                }
            },
            error: function (xhr, status, error) {
                let $msg = $('#messageBox');
                $msg.removeClass('d-none alert-success').addClass('alert-danger')
                    .text('Error updating status').show();
                setTimeout(function () { $msg.fadeOut(); }, 2000);
            }
        });
    });
    // delete pop up 
    $(document).on("click", ".delete-module", function (e) {
        e.preventDefault();
        let roleId = $(this).data("id");

        swal({
            title: "Are You Sure?",
            text: "You Want To Delete This Module!",
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
                    url: "<?= base_url('admin/manage_module/delete'); ?>",
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
                            $('#moduleTable').DataTable().ajax.reload(null, false);
                        } else {
                            swal("Error!", response.message, "error");
                        }
                    },
                    error: function () {
                        swal("Error!", "Something went wrong. Try again.", "error");
                    },
                });
            }
        });
    });
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag('js', new Date());
    gtag('config', 'G-1VDDWMRSTH');
    try {
        fetch(new Request("https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js", { method: 'HEAD', mode: 'no-cors' })).then(function (response) {
            return true;
        }).catch(function (e) {
            var carbonScript = document.createElement("script");
            carbonScript.src = "//cdn.carbonads.com/carbon.js?serve=CK7DKKQU&placement=wwwjqueryscriptnet";
            carbonScript.id = "_carbonads_js";
            document.getElementById("carbon-block").appendChild(carbonScript);
        });
    } catch (error) {
        console.log(error);
    }
</script>